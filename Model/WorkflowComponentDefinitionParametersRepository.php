<?php


namespace Cleargo\Integrationframeworks\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinitionParameters\CollectionFactory as WorkflowComponentDefinitionParametersCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersSearchResultsInterfaceFactory;
use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinitionParameters as ResourceWorkflowComponentDefinitionParameters;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterfaceFactory;
use Cleargo\Integrationframeworks\Api\WorkflowComponentDefinitionParametersRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;

class WorkflowComponentDefinitionParametersRepository implements workflowComponentDefinitionParametersRepositoryInterface
{

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $workflowComponentDefinitionParametersFactory;

    protected $searchResultsFactory;

    protected $workflowComponentDefinitionParametersCollectionFactory;

    protected $resource;

    protected $dataWorkflowComponentDefinitionParametersFactory;

    private $storeManager;


    /**
     * @param ResourceWorkflowComponentDefinitionParameters $resource
     * @param WorkflowComponentDefinitionParametersFactory $workflowComponentDefinitionParametersFactory
     * @param WorkflowComponentDefinitionParametersInterfaceFactory $dataWorkflowComponentDefinitionParametersFactory
     * @param WorkflowComponentDefinitionParametersCollectionFactory $workflowComponentDefinitionParametersCollectionFactory
     * @param WorkflowComponentDefinitionParametersSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceWorkflowComponentDefinitionParameters $resource,
        WorkflowComponentDefinitionParametersFactory $workflowComponentDefinitionParametersFactory,
        WorkflowComponentDefinitionParametersInterfaceFactory $dataWorkflowComponentDefinitionParametersFactory,
        WorkflowComponentDefinitionParametersCollectionFactory $workflowComponentDefinitionParametersCollectionFactory,
        WorkflowComponentDefinitionParametersSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->workflowComponentDefinitionParametersFactory = $workflowComponentDefinitionParametersFactory;
        $this->workflowComponentDefinitionParametersCollectionFactory = $workflowComponentDefinitionParametersCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWorkflowComponentDefinitionParametersFactory = $dataWorkflowComponentDefinitionParametersFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
    ) {
        /* if (empty($workflowComponentDefinitionParameters->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $workflowComponentDefinitionParameters->setStoreId($storeId);
        } */
        try {
            $workflowComponentDefinitionParameters->getResource()->save($workflowComponentDefinitionParameters);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the workflowComponentDefinitionParameters: %1',
                $exception->getMessage()
            ));
        }
        return $workflowComponentDefinitionParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($workflowComponentDefinitionParametersId)
    {
        $workflowComponentDefinitionParameters = $this->workflowComponentDefinitionParametersFactory->create();
        $workflowComponentDefinitionParameters->getResource()->load($workflowComponentDefinitionParameters, $workflowComponentDefinitionParametersId);
        if (!$workflowComponentDefinitionParameters->getId()) {
            throw new NoSuchEntityException(__('WorkflowComponentDefinitionParameters with id "%1" does not exist.', $workflowComponentDefinitionParametersId));
        }
        return $workflowComponentDefinitionParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->workflowComponentDefinitionParametersCollectionFactory->create();
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
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
    ) {
        try {
            $workflowComponentDefinitionParameters->getResource()->delete($workflowComponentDefinitionParameters);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the WorkflowComponentDefinitionParameters: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($workflowComponentDefinitionParametersId)
    {
        return $this->delete($this->getById($workflowComponentDefinitionParametersId));
    }
}
