<?php


namespace Cleargo\Integrationframeworks\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowPlans\CollectionFactory as WorkflowPlansCollectionFactory;
use Magento\Framework\Api\SortOrder;
use Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterfaceFactory;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowPlans as ResourceWorkflowPlans;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Cleargo\Integrationframeworks\Api\WorkflowPlansRepositoryInterface;
use Cleargo\Integrationframeworks\Api\Data\WorkflowPlansSearchResultsInterfaceFactory;

class WorkflowPlansRepository implements workflowPlansRepositoryInterface
{

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $workflowPlansFactory;

    protected $dataWorkflowPlansFactory;

    protected $workflowPlansCollectionFactory;

    protected $searchResultsFactory;

    protected $resource;

    private $storeManager;


    /**
     * @param ResourceWorkflowPlans $resource
     * @param WorkflowPlansFactory $workflowPlansFactory
     * @param WorkflowPlansInterfaceFactory $dataWorkflowPlansFactory
     * @param WorkflowPlansCollectionFactory $workflowPlansCollectionFactory
     * @param WorkflowPlansSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWorkflowPlans $resource,
        WorkflowPlansFactory $workflowPlansFactory,
        WorkflowPlansInterfaceFactory $dataWorkflowPlansFactory,
        WorkflowPlansCollectionFactory $workflowPlansCollectionFactory,
        WorkflowPlansSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->workflowPlansFactory = $workflowPlansFactory;
        $this->workflowPlansCollectionFactory = $workflowPlansCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWorkflowPlansFactory = $dataWorkflowPlansFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
    ) {
        /* if (empty($workflowPlans->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $workflowPlans->setStoreId($storeId);
        } */
        try {
            $workflowPlans->getResource()->save($workflowPlans);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the workflowPlans: %1',
                $exception->getMessage()
            ));
        }
        return $workflowPlans;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($workflowPlansId)
    {
        $workflowPlans = $this->workflowPlansFactory->create();
        $workflowPlans->getResource()->load($workflowPlans, $workflowPlansId);
        if (!$workflowPlans->getId()) {
            throw new NoSuchEntityException(__('WorkflowPlans with id "%1" does not exist.', $workflowPlansId));
        }
        return $workflowPlans;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->workflowPlansCollectionFactory->create();
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
        \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
    ) {
        try {
            $workflowPlans->getResource()->delete($workflowPlans);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WorkflowPlans: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($workflowPlansId)
    {
        return $this->delete($this->getById($workflowPlansId));
    }
}
