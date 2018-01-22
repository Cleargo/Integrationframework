<?php
namespace Cleargo\Integrationframeworks\Model\Component;
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

    public function execute() {
        var_dump('DownloadFileFromFTP Start');
        $this->download();
        var_dump('DownloadFileFromFTP Executed');
    }

    public function download(){
        echo 'DownloadFileFromFTP!!';
        echo $this->setConnection("host=xxxx");
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