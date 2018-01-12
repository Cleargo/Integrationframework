<?php


namespace Cleargo\Integrationframeworks\Cron;

use Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule\CollectionFactory;

class RunWorkflow
{

    protected $logger;

    protected $workflowScheduleFactory;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, CollectionFactory $workflowScheduleFactory)
    {
        $this->logger = $logger;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function generatePlan()
    {
        $workflowScheduleCollection = $this->workflowScheduleFactory->create();
        $this->logger->addDebug("Cronjob RunWorkflow dtest.");
        $this->logger->addDebug($workflowScheduleCollection->count());
        $this->logger->addDebug("Cronjob RunWorkflow is generated.");
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function executePlan()
    {
        $this->logger->addDebug("Cronjob RunWorkflow is executed.");
    }
}
