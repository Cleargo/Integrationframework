<?php

namespace Cleargo\Integrationframeworks\Model\Component;

class SmbUploader
{
    use BaseComponentTrait, SmbTrait;

    public function execute()
    {
        echo "start uploading to smb\n";
        try {
            $this->getSmbShare();

            foreach ($this->loadFilesToUpload() as $fileToUpload) {
                echo "\tuploading {$fileToUpload}\n";
                $this->upload($fileToUpload);
            }
        } catch (\Exception $e){
            echo "smb error {$e->getMessage()}\n";
            var_dump($this->relationParams);
            return;
        }
    }

    private function loadFilesToUpload()
    {
        $return = [];
        $scanPath = $this->relationParams->local_path;

        if(!file_exists($scanPath))
            return $return;

        foreach(scandir($scanPath) as $fileToUpload){
            $filePath = "{$scanPath}{$fileToUpload}";
            if(is_file($filePath)){
                $return[] = $filePath;
            }
        }

        return $return;
    }

    private function upload($filePath)
    {
        $fileParts = explode("/", $filePath);
        $fileName  = end($fileParts);

        $result    = $this->smbShare->put(
            $filePath,
            "{$this->relationParams->smb_path}/{$fileName}"
        );

        //remove after uploaded
        if($result)
            unlink($filePath);
    }
}