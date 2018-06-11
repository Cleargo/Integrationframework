<?php

namespace Cleargo\Integrationframeworks\Model\Component;

class ImportPickingResult
{
    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $importer;

    public function __construct(
        \Cleargo\Integrationframeworks\Logger\Logger $logger,
        \Cleargo\PickingResult\Model\ImportPickingResult $importer
    )
    {
        $this->logger = $logger;
        $this->importer = $importer;
    }
    
    public function execute() {
        $this->importer->setLogger($this->logger);
        $this->importer->processSavedFiles();
    }

    public function setRelationParams($params) {
        $this->relationParams = json_decode($params);
        return $this;
    }

    public function setWebsiteId($websiteId) {
        $this->websiteId = $websiteId;
        return $this;
    }

    public function setStoreId($storeId) {
        $this->storeId = $storeId;
        return $this;
    }

    public function setScheduleLogLevel($scheduleLogLevel) {
        $this->scheduleLogLevel = $scheduleLogLevel;
        return $this;
    }
}
