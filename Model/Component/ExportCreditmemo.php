<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\Order\Creditmemo;

class ExportCreditmemo
{
    protected $logger;

    protected $creditmemoCollection;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $directoryList;

    protected $creditmemoModel;


    public function __construct(Logger $logger, DirectoryList $directoryList, Creditmemo $creditmemoModel)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->creditmemoModel = $creditmemoModel;
    }

    public function execute() {
        var_dump('Export Creditmemo Start');

        // TODO: Export Order xml
        /*var_dump($this->relationParams);
        var_dump($this->websiteId);
        var_dump($this->storeId);*/
        $this->exportCreditmemoXml();


        // TODO: Update last_sync for Creditmemo


        var_dump('Export Creditmemo Executed');
    }

    public function exportCreditmemoXml() {
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;

        // TODO: Get Order Collection
        $this->creditmemoCollection = $this->getCreditmemoCollection();

        if (!$this->creditmemoCollection->count()) {
            $this->logger->info("ExportCreditmemo: There are no Creditmemo for export");
        } else {
            $this->logger->info("ExportCreditmemo: " . $this->creditmemoCollection->count() . " creditmemo(s) processed");
        }

        // TODO: Export Order Xml
        foreach ($this->creditmemoCollection as $creditmemo) {
            $currentTime = time();
            $fileTime = date("Ymd_HisS", $currentTime);
            $fileName = 'creditmemo_export_' . $creditmemo->getIncrementId() . '_' . $fileTime . '.xml';
            //var_dump($fileName);

            $content = "<response>&#xA;";
            $content .=  $creditmemo->toXml([], null, null, false);
            $content .= "</response>";
            $xml = new \SimpleXMLElement($content);

            // TODO: generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            try {
                fwrite($outputFile, $xml->asXML());
                fclose($outputFile);
                $creditmemo->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $creditmemo->getResource()->saveAttribute($creditmemo, 'nav_last_sync_at');
                $this->logger->info("ExportCreditmemo: " . $fileName . " created");
            } catch (Exception $e) {
                $this->logger->addDebug($e->getMessage());
            }
        }
    }

    public function getCreditmemoCollection() {
        // TODO: Change criteria with nav_last_sync_at, website_id, store_id also after order attribute added
        // Use relation params for filter
            $creditmemoCollection = $this->creditmemoModel->getCollection();
            $data = $creditmemoCollection->addFieldToFilter('store_id', $this->storeId)
                ->addFieldToFilter('nav_last_sync_at', array('null' => true));

        return $data;
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