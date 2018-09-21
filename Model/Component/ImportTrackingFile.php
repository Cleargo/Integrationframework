<?php

namespace Cleargo\Integrationframeworks\Model\Component;

class ImportTrackingFile
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Cleargo\Integrationframeworks\Logger\Logger
     */
    private $logger;

    public function __construct(
        \Cleargo\Integrationframeworks\Logger\Logger $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    )
    {
        $this->directoryList = $directoryList;
        $this->logger = $logger;
    }

    public function execute()
    {
        chdir($this->directoryList->getRoot());
        $line = exec('php bin/magento cleargo:import:tracking');
        $this->logger->info($line);
    }

    public function setRelationParams($params)
    {
        $this->relationParams = json_decode($params);
        return $this;
    }

    public function setWebsiteId($websiteId)
    {
        $this->websiteId = $websiteId;
        return $this;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function setScheduleLogLevel($scheduleLogLevel)
    {
        $this->scheduleLogLevel = $scheduleLogLevel;
        return $this;
    }
}