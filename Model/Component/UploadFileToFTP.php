<?php
namespace Cleargo\Integrationframeworks\Model\Component;
/**
 * Extended FTP client
 */
class UploadFileToFTP extends \Cleargo\Integrationframeworks\Model\Component\AbstractComponent\Ftp
{
    public function upload(){
        echo 'UPLOAD!!';
        echo $this->setConnection("host=xxxx");
        return true;
    }
}