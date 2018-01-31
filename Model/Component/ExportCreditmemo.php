<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Framework\Filesystem\Io\File;


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

    protected $io;


    public function __construct(Logger $logger, DirectoryList $directoryList, Creditmemo $creditmemoModel, File $io)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->creditmemoModel = $creditmemoModel;
        $this->io = $io;
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
        $archiveDir = $outputDir . 'archive/';

        // TODO: Get Order Collection
        $this->creditmemoCollection = $this->getCreditmemoCollection();

        if (!$this->creditmemoCollection->count()) {
            $this->logger->info("ExportCreditmemo: There are no Creditmemo for export");
            return;
        } else {
            // Create directory on local server
            if (!is_dir($outputDir)) {
                $result = $this->io->mkdir($outputDir, 0775);
                if ($result) {
                    $this->logger->info('Directory created on local server: ' . $outputDir);
                } else {
                    $this->logger->info('Fail to create directory on local server: ' . $outputDir);
                }
            }
            // Create directory for archive on local server
            if (!is_dir($archiveDir)) {
                $archiveResult = $this->io->mkdir($archiveDir, 0775);
                if ($archiveResult) {
                    $this->logger->info('Directory for archive created on local server: ' . $archiveDir);
                } else {
                    $this->logger->info('Fail to create directory for archive on local server: ' . $archiveDir);
                }
            }
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


            try {
                // Generate xml for each order
                $outputFile = fopen($outputDir . $fileName, "w");
                fwrite($outputFile, $xml->asXML());
                fclose($outputFile);
                $creditmemo->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $creditmemo->getResource()->saveAttribute($creditmemo, 'nav_last_sync_at');
                $this->logger->info("ExportCreditmemo: " . $fileName . " created");
                // Generate xml for each order to archive folder
                $archiveFile = fopen($archiveDir . $fileName, "w");
                fwrite($archiveFile, $xml->asXML());
                fclose($archiveFile);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
                throw $e;
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