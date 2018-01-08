<?php


namespace Cleargo\Integrationframeworks\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationSearchResultsInterfaceFactory;
use Cleargo\Integrationframeworks\Api\WorkflowComponentScheduleRelationRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentScheduleRelation as ResourceWorkflowComponentScheduleRelation;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentScheduleRelation\CollectionFactory as WorkflowComponentScheduleRelationCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;

class WorkflowComponentScheduleRelationRepository implements workflowComponentScheduleRelationRepositoryInterface
{

    protected $workflowComponentScheduleRelationFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $dataWorkflowComponentScheduleRelationFactory;

    protected $workflowComponentScheduleRelationCollectionFactory;

    protected $searchResultsFactory;

    protected $resource;

    private $storeManager;


    /**
     * @param ResourceWorkflowComponentScheduleRelation $resource
     * @param WorkflowComponentScheduleRelationFactory $workflowComponentScheduleRelationFactory
     * @param WorkflowComponentScheduleRelationInterfaceFactory $dataWorkflowComponentScheduleRelationFactory
     * @param WorkflowComponentScheduleRelationCollectionFactory $workflowComponentScheduleRelationCollectionFactory
     * @param WorkflowComponentScheduleRelationSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWorkflowComponentScheduleRelation $resource,
        WorkflowComponentScheduleRelationFactory $workflowComponentScheduleRelationFactory,
        WorkflowComponentScheduleRelationInterfaceFactory $dataWorkflowComponentScheduleRelationFactory,
        WorkflowComponentScheduleRelationCollectionFactory $workflowComponentScheduleRelationCollectionFactory,
        WorkflowComponentScheduleRelationSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->workflowComponentScheduleRelationFactory = $workflowComponentScheduleRelationFactory;
        $this->workflowComponentScheduleRelationCollectionFactory = $workflowComponentScheduleRelationCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWorkflowComponentScheduleRelationFactory = $dataWorkflowComponentScheduleRelationFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
    ) {
        /* if (empty($workflowComponentScheduleRelation->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $workflowComponentScheduleRelation->setStoreId($storeId);
        } */
        try {
            $workflowComponentScheduleRelation->getResource()->save($workflowComponentScheduleRelation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the workflowComponentScheduleRelation: %1',
                $exception->getMessage()
            ));
        }
        return $workflowComponentScheduleRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($workflowComponentScheduleRelationId)
    {
        $workflowComponentScheduleRelation = $this->workflowComponentScheduleRelationFactory->create();
        $workflowComponentScheduleRelation->getResource()->load($workflowComponentScheduleRelation, $workflowComponentScheduleRelationId);
        if (!$workflowComponentScheduleRelation->getId()) {
            throw new NoSuchEntityException(__('WorkflowComponentScheduleRelation with id "%1" does not exist.', $workflowComponentScheduleRelationId));
        }
        return $workflowComponentScheduleRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->workflowComponentScheduleRelationCollectionFactory->create();
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
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
    ) {
        try {
            $workflowComponentScheduleRelation->getResource()->delete($workflowComponentScheduleRelation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WorkflowComponentScheduleRelation: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($workflowComponentScheduleRelationId)
    {
        return $this->delete($this->getById($workflowComponentScheduleRelationId));
    }
}
