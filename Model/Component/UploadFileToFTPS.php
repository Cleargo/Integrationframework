<?php
namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;

/**
 * Extended FTP client
 */
class UploadFileToFTPS
{
    protected $logger;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $ftps;

    protected $directoryList;

    public function __construct(Logger $logger,
                                DirectoryList $directoryList)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;

    }



    public function execute(){
        var_dump('UploadFileToFTPS Start');
        try {
            $host = $this->relationParams->ftp_host;
            $user = $this->relationParams->ftp_user;
            $password = $this->relationParams->ftp_pw;
            $secure_type = $this->relationParams->secure_type;
            $file_pattern = $this->relationParams->file_pattern;
            $upload_path = $this->relationParams->upload_path;
            $source_path = $this->relationParams->source_path;
            $archivePath = "processed/";
            $this->ftps = new \Cleargo\Integrationframeworks\Model\Component\AbstractComponent\ImplicitFtp(
                $host,$user,$password
            );



            //\Zend_Debug::dump($this->relationParams);


            $localDir = $this->directoryList->getRoot() . $source_path;


            $fileLists = array_diff(scandir($localDir, SCANDIR_SORT_DESCENDING), array('.', '..'));
            foreach ($fileLists as $file) {

                $localfile = $localDir.$file;
                if (!is_dir($localfile)) {
                    //\Zend_Debug::dump($localfile);
                    $remotefile = $upload_path.$file;
                    $this->logger->info("File from local ".$localfile." uploaded to FTP ".$remotefile);
                    //\Zend_Debug::dump($remotefile);
                    $result = $this->ftps->upload($localfile, $remotefile);

                    if ($result){
                        // TODO: Move the successfully read file into archive folder
                        rename($localfile, $localDir.$archivePath.$file);

                    }

                }

            }

            //\Zend_Debug::dump($result);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->logger->info($e->getMessage());
            throw $e;
        }
        var_dump('UploadFileToFTPS Executed');
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