<?php
namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;

/**
 * Extended FTP client
 */
class UploadSingleFileToFTP extends \Cleargo\Integrationframeworks\Model\Component\AbstractComponent\Ftp
{
    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $ftp;

    protected $directoryList;

    public function __construct(Logger $logger, DirectoryList $directoryList)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
    }

    public function execute() {
        var_dump('UploadSingleFileToFTP Start');
        $this->upload();
        var_dump('UploadSingleFileToFTP Executed');
    }

    public function upload(){
        try {
            $host = $this->relationParams->ftp_host;
            $user = $this->relationParams->ftp_user;
            $password = $this->relationParams->ftp_pw;
            $secure_type = $this->relationParams->secure_type;
            $file_pattern = $this->relationParams->file_pattern;
            $upload_path = $this->relationParams->upload_path;
            $source_path = $this->relationParams->source_path;
            $port = $this->relationParams->port;

            if (!$host || !$user || !$password || !$secure_type || !$file_pattern || !$upload_path || !$source_path) {
                $this->logger->info("Cannot connect to FTP");
                var_dump("Cannot connect to FTP");
            } else {
                var_dump("Connecting to FTP");

                // Connect ftp
                $this->setConnection($this->relationParams);

                // Upload file and remove it in magento local
                    $this->write($upload_path, \file_get_contents($this->directoryList->getRoot() . $source_path));
                    $this->logger->info("File from local ".$source_path." uploaded to FTP ".$upload_path);
                    // Remove uploaded file on local

                // Close connection
                $this->close();
                var_dump("FTP Connect Closed");
            }
        } catch (\Exception $e) {
            var_dump("Cannot connect to FTP");
            $this->logger->info($e->getMessage());
            throw $e;
        }
        return true;
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