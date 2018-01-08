<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml;

abstract class WorkflowComponentDefinition extends \Magento\Backend\App\Action
{

    protected $_coreRegistry;
    const ADMIN_RESOURCE = 'Cleargo_Integrationframeworks::top_level';

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Cleargo'), __('Cleargo'))
            ->addBreadcrumb(__('Workflowcomponentdefinition'), __('Workflowcomponentdefinition'));
        return $resultPage;
    }
}
