<?php


namespace Cleargo\Integrationframeworks\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition\CollectionFactory as WorkflowComponentDefinitionCollectionFactory;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition as ResourceWorkflowComponentDefinition;
use Magento\Framework\Api\SortOrder;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Cleargo\Integrationframeworks\Api\WorkflowComponentDefinitionRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterfaceFactory;

class WorkflowComponentDefinitionRepository implements workflowComponentDefinitionRepositoryInterface
{

    protected $dataObjectProcessor;

    protected $workflowComponentDefinitionFactory;

    protected $dataObjectHelper;

    protected $dataWorkflowComponentDefinitionFactory;

    protected $searchResultsFactory;

    protected $resource;

    protected $workflowComponentDefinitionCollectionFactory;

    private $storeManager;


    /**
     * @param ResourceWorkflowComponentDefinition $resource
     * @param WorkflowComponentDefinitionFactory $workflowComponentDefinitionFactory
     * @param WorkflowComponentDefinitionInterfaceFactory $dataWorkflowComponentDefinitionFactory
     * @param WorkflowComponentDefinitionCollectionFactory $workflowComponentDefinitionCollectionFactory
     * @param WorkflowComponentDefinitionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWorkflowComponentDefinition $resource,
        WorkflowComponentDefinitionFactory $workflowComponentDefinitionFactory,
        WorkflowComponentDefinitionInterfaceFactory $dataWorkflowComponentDefinitionFactory,
        WorkflowComponentDefinitionCollectionFactory $workflowComponentDefinitionCollectionFactory,
        WorkflowComponentDefinitionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->workflowComponentDefinitionFactory = $workflowComponentDefinitionFactory;
        $this->workflowComponentDefinitionCollectionFactory = $workflowComponentDefinitionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWorkflowComponentDefinitionFactory = $dataWorkflowComponentDefinitionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
    ) {
        /* if (empty($workflowComponentDefinition->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $workflowComponentDefinition->setStoreId($storeId);
        } */
        try {
            $workflowComponentDefinition->getResource()->save($workflowComponentDefinition);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the workflowComponentDefinition: %1',
                $exception->getMessage()
            ));
        }
        return $workflowComponentDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($workflowComponentDefinitionId)
    {
        $workflowComponentDefinition = $this->workflowComponentDefinitionFactory->create();
        $workflowComponentDefinition->getResource()->load($workflowComponentDefinition, $workflowComponentDefinitionId);
        if (!$workflowComponentDefinition->getId()) {
            throw new NoSuchEntityException(__('WorkflowComponentDefinition with id "%1" does not exist.', $workflowComponentDefinitionId));
        }
        return $workflowComponentDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->workflowComponentDefinitionCollectionFactory->create();
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
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
    ) {
        try {
            $workflowComponentDefinition->getResource()->delete($workflowComponentDefinition);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WorkflowComponentDefinition: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($workflowComponentDefinitionId)
    {
        return $this->delete($this->getById($workflowComponentDefinitionId));
    }
}
