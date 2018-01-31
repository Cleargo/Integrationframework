<?php
namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;
/**
 * Extended FTP client
 */
class DownloadFileFromFTP extends \Cleargo\Integrationframeworks\Model\Component\AbstractComponent\Ftp
{
    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $directoryList;

    public function __construct(Logger $logger, DirectoryList $directoryList)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
    }

    public function execute() {
        var_dump('DownloadFileFromFTP Start');
        $this->download();
        var_dump('DownloadFileFromFTP Executed');
    }

    public function download(){
        try {
            $host = $this->relationParams->ftp_host;
            $user = $this->relationParams->ftp_user;
            $password = $this->relationParams->ftp_pw;
            $secure_type = $this->relationParams->secure_type;
            $file_pattern = $this->relationParams->file_pattern;
            $source_path = $this->relationParams->source_path;
            $destination_path = $this->relationParams->destination_path;

            if (!$host || !$user || !$password || !$secure_type || !$file_pattern || !$source_path || !$destination_path) {
                $this->logger->info("Cannot connect to FTP");
                var_dump("Cannot connect to FTP");
            } else {
                var_dump("Connecting to FTP");
                $this->setConnection($this->relationParams);

                $this->cd($source_path);
                $fileList = $this->ls();
                foreach ($fileList as $file) {
                    /*$result = $this->read($source_path.'dtest.xml', $this->directoryList->getRoot().$destination_path.'dtest.xml');
                    var_dump($result);*/
                    if (preg_match('/^\w+[.]\w+$/', $file['text'])) {
                        // Check if file and download to local
                        $result = $this->read($file['id'], $this->directoryList->getRoot().$destination_path.$file['text']);
                        if ($result) {
                            $this->logger->info("File from FTP ".$file['id']." downloaded to local ".$this->directoryList->getRoot().$destination_path.$file['text']);
                            // After download the file successfully, will delete the file on FTP
                            $this->rm($file['id']);
                        } else {
                            $this->logger->info("File Download FAIL: File from FTP ".$file['id']." cannot download to local ".$this->directoryList->getRoot().$destination_path.$file['text']);
                        }
                    }
                }

                $this->close();
                var_dump("Close FTP Connection");
            }
        } catch (\Exception $e) {
            var_dump("Cannot connect to FTP");
            $this->logger->info($e->getMessage());
            throw $e;
        }
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