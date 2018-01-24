<?php


namespace Cleargo\Integrationframeworks\Cron;

use Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory;
use Cleargo\Integrationframeworks\Model\WorkflowComponentScheduleRelationFactory;
use Cleargo\Integrationframeworks\Model\WorkflowPlansFactory;
use Cleargo\Integrationframeworks\Logger\Logger;

class RunWorkflow
{

    protected $logger;

    protected $objectManager;

    protected $workflowScheduleFactory;

    protected $workflowComponentScheduleRelationFactory;

    protected $workflowPlansFactory;


    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(Logger $logger, \Magento\Framework\ObjectManagerInterface $objectManager, WorkflowScheduleFactory $workflowScheduleFactory,
                                WorkflowComponentScheduleRelationFactory $workflowComponentScheduleRelationFactory,
                                WorkflowPlansFactory $workflowPlansFactory)
    {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
        $this->workflowComponentScheduleRelationFactory = $workflowComponentScheduleRelationFactory;
        $this->workflowPlansFactory = $workflowPlansFactory;
    }

    /**
     * Generate the cron
     *
     * @return void
     */
    public function generatePlan()
    {
        $this->logger->addDebug("---------- RunWorkflow generatePlan START ----------");
        // Get Schedule Model
        $workflowSchedule = $this->workflowScheduleFactory->create();
        $workflowScheduleCollection = $workflowSchedule->getCollection();

        foreach ($workflowScheduleCollection as $scheduleItem) {
            /*$detail = json_encode($item->getData());
            $this->logger->addDebug($detail);*/

            $workflowScheduleType = $scheduleItem->getScheduleType();
            $workflowScheduleId = $scheduleItem->getWorkflowscheduleId();
            $workflowName = $scheduleItem->getName();
            $workflowWebsiteId = $scheduleItem->getWebsiteId();
            $workflowStoreId = $scheduleItem->getStoreId();
            $workflowExecutionActiveFrom = new \DateTime($scheduleItem->getExecutionActiveFrom());
            $workflowExecutionInterval = $scheduleItem->getExecutionInterval();
            $workflowExecutionType = $scheduleItem->getExecutionType();

            $workflowPlans = $this->workflowPlansFactory->create();
            $workflowPlansCollection = $workflowPlans->getCollection();
            $workflowPlansCollection->addFieldToFilter('schedule_id', $workflowScheduleId);

            if ($workflowScheduleType == "SINGLE") {
                // Create plan for SINGLE schedule
                // TODO: inject plan model and create new plan
                if (!$workflowPlansCollection->count()) {
                    // generate plan here if no such SINGLE plan generated before
                    // TODO: IMPORTANT!!! Confirmed with Leo, will remove relation_id in workflow_plan table. Each schedule will only generate a single row plan. Relation linkage will be used on Plan Execution.

                    $planStartTime = $workflowExecutionActiveFrom->modify($workflowExecutionInterval.$workflowExecutionType);
                    $planStartTime = date_format($planStartTime, 'Y-m-d H:i:s');

                    // Set Data for workFlowPlans Model
                    $workflowPlans->setScheduleId($workflowScheduleId);
                    $workflowPlans->setWebsiteId($workflowWebsiteId);
                    $workflowPlans->setStoreId($workflowStoreId);
                    $workflowPlans->setScheduleName($workflowName);
                    //$workflowPlans->setRelationId($relationItem->getId());
                    $workflowPlans->setStartTime($planStartTime);
                    $workflowPlans->setStatus('pending');
                    $workflowPlans->save();

                    /*$this->logger->addDebug("-----START-----");
                    $this->logger->addDebug('$workflowScheduleId: ' . $workflowScheduleId);
                    $this->logger->addDebug('$workflowWebsiteId: ' . $workflowWebsiteId);
                    $this->logger->addDebug('$workflowStoreId: ' . $workflowStoreId);
                    $this->logger->addDebug('$workflowName: ' . $workflowName);
                    $this->logger->addDebug('$workflowExecutionActiveFrom(json): ' . json_encode($planStartTime));
                    $this->logger->addDebug('$workflowExecutionActiveFrom(str): ' . $planStartTime);
                    $this->logger->addDebug("-----END-----");*/
                }
            } elseif ($workflowScheduleType == "RECURRING") {
                // Create plan for RECURRING schedule
                $this->logger->addDebug('RECURRING dtest');
                if ($workflowPlansCollection->count() < 5) {
                    // TODO: Create at least 5 pending plans for the schedule, update later

                }
            }
        }
        $this->logger->addDebug("---------- RunWorkflow generatePlan END ----------");
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function executePlan()
    {
        // TODO: directly run core method
        $this->logger->info("------------------------------ executePlan in RunWorkflow START ------------------------------");
        var_dump("---------- executePlan in RunWorkflow ----------");

        // TODO: loop plan for execution, check start_time and status to determine which plan to run
        $currentTime = date("Y-m-d H:i:s", time());
        //$this->logger->addDebug(json_encode($currentTime));

        $workflowPlans = $this->workflowPlansFactory->create();
        $workflowPlansCollection = $workflowPlans->getCollection();
        $workflowPlansCollection->addFieldToFilter('start_time', ['lteq' => $currentTime])
            ->addFieldToFilter('status', 'pending');
        /*echo $workflowPlansCollection->getSelect();
        $this->logger->addDebug('count: ' . $workflowPlansCollection->count());*/

        // Each plan should have only one pending record normally
        foreach ($workflowPlansCollection as $plan) {
            try {
                var_dump("RunWorkflow: Workflow plan(id: ". $plan->getId() .") START");
                $plan->setExecutionAt($currentTime);
                $this->logger->info("----------RunWorkflow: Workflow plan(id: ". $plan->getId() .") executed----------");
                // TODO: Load schedule by schedule_id and take the relation & component data
                $scheduleId = $plan->getScheduleId();
                $websiteId = $plan->getWebsiteId();
                $storeId = $plan->getStoreId();
                $workflowSchedule = $this->workflowScheduleFactory->create()->load($scheduleId)->loadRelation();
                $scheduleLogLevel = $workflowSchedule->getFileLogLevel();
                //echo $workflowSchedule->getSelect();
                $relations = $workflowSchedule->getRelation();
                foreach ($relations as $rel) {
                    $components = $rel['component'];
                    $params = $rel['parameters'];
                    foreach ($components as $comp) {
                        // TODO: get the component name from relation and load the corresponding model. For example, ExportOrder
                        $compName = $comp['name'];
                        $modelName = "Cleargo\\Integrationframeworks\\Model\\Component\\" . $compName;
                        //var_dump($modelName);

                        // TODO: run execute method in component model, will create interface for component later
                        // TODO: Relation to Component is 1 to 1 ??
                        $compModel = $this->objectManager->create($modelName)
                            ->setRelationParams($params)
                            ->setWebsiteId($websiteId)
                            ->setStoreId($storeId)
                            ->setScheduleLogLevel($scheduleLogLevel);
                        // Relation to Component is 1 to 1 ???

                        $this->logger->info("RunWorkflow: Component(".$compName.") executed");
                        $compModel->execute();
                        //var_dump($comp);
                    }
                }
                $endTime = date("Y-m-d H:i:s", time());
                $plan->setEndTime($endTime);

                // TODO: Temporally not change the plan status to completed for K11 NAV. Make all the plans loop foreach as magneto cron job.
                // $plan->setStatus('completed');

                var_dump("RunWorkflow: Workflow plan(id: ". $plan->getId() .") END");
                $this->logger->info("----------RunWorkflow: Workflow plan(id: ". $plan->getId() .") completed----------");
            } catch (\Exception $e) {
                $plan->setStatus('error');
            }
            $plan->save();
        }
        $this->logger->info("------------------------------ executePlan in RunWorkflow END ------------------------------");
    }
}
