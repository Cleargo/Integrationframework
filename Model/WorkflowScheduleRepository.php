<?php


namespace Cleargo\Integrationframeworks\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule\CollectionFactory as WorkflowScheduleCollectionFactory;
use Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterfaceFactory;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule as ResourceWorkflowSchedule;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleSearchResultsInterfaceFactory;
use Cleargo\Integrationframeworks\Api\WorkflowScheduleRepositoryInterface;

class WorkflowScheduleRepository implements workflowScheduleRepositoryInterface
{

    protected $workflowScheduleFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $workflowScheduleCollectionFactory;

    protected $dataWorkflowScheduleFactory;

    protected $searchResultsFactory;

    protected $resource;

    private $storeManager;


    /**
     * @param ResourceWorkflowSchedule $resource
     * @param WorkflowScheduleFactory $workflowScheduleFactory
     * @param WorkflowScheduleInterfaceFactory $dataWorkflowScheduleFactory
     * @param WorkflowScheduleCollectionFactory $workflowScheduleCollectionFactory
     * @param WorkflowScheduleSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWorkflowSchedule $resource,
        WorkflowScheduleFactory $workflowScheduleFactory,
        WorkflowScheduleInterfaceFactory $dataWorkflowScheduleFactory,
        WorkflowScheduleCollectionFactory $workflowScheduleCollectionFactory,
        WorkflowScheduleSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
        $this->workflowScheduleCollectionFactory = $workflowScheduleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWorkflowScheduleFactory = $dataWorkflowScheduleFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
    ) {
        /* if (empty($workflowSchedule->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $workflowSchedule->setStoreId($storeId);
        } */
        try {
            $workflowSchedule->getResource()->save($workflowSchedule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the workflowSchedule: %1',
                $exception->getMessage()
            ));
        }
        return $workflowSchedule;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($workflowScheduleId)
    {
        $workflowSchedule = $this->workflowScheduleFactory->create();
        $workflowSchedule->getResource()->load($workflowSchedule, $workflowScheduleId);
        if (!$workflowSchedule->getId()) {
            throw new NoSuchEntityException(__('WorkflowSchedule with id "%1" does not exist.', $workflowScheduleId));
        }
        return $workflowSchedule;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->workflowScheduleCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
    ) {
        try {
            $workflowSchedule->getResource()->delete($workflowSchedule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WorkflowSchedule: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($workflowScheduleId)
    {
        return $this->delete($this->getById($workflowScheduleId));
    }
}
