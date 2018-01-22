<?php
namespace Cleargo\Integrationframeworks\Model\Component;
/**
 * Extended FTP client
 */
class UploadFileToFTP extends \Cleargo\Integrationframeworks\Model\Component\AbstractComponent\Ftp
{
    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    public function execute() {
        var_dump('UploadFileToFTP Start');
        $this->upload();
        var_dump('UploadFileToFTP Executed');
    }

    public function upload(){
        echo 'UPLOAD!!';
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