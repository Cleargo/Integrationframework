<?php

namespace Cleargo\Integrationframeworks\Model\Component;


class SmbDownloader
{
    use BaseComponentTrait , SmbTrait;

    public function execute()
    {
        echo "start downloading files from smb\n";

        try {
            $this->getSmbShare();

            foreach ($this->loadFilesToDownload() as $fileToDownload) {
                echo "downloading {$fileToDownload}\n";
                $this->download($fileToDownload);
            }
        } catch (\Exception $e){
            echo "smb error {$e->getMessage()}\n";
            var_dump($this->relationParams);
            return;
        }
    }

    private function loadFilesToDownload()
    {
        $return = [];
        $content = $this->smbShare->dir(
            $this->relationParams->smb_path
        );
        foreach ($content as $info) {
            $return[] = trim($info->getName());
        }
        return $return;
    }

    private function download($fileName)
    {
        $this->createLocalDirectoryIfNeed();

        $result = $this->smbShare->get(
            "{$this->relationParams->smb_path}{$fileName}",
            "{$this->relationParams->local_path}{$fileName}"
        );
        //del after download , consider move this history folder
        $this->smbShare->del(
            "{$this->relationParams->smb_path}{$fileName}"
        );
    }

    private function createLocalDirectoryIfNeed()
    {
        if(!file_exists($this->relationParams->local_path)){
            mkdir(
                $this->relationParams->local_path,
                0777,
                true
            );
        }
    }
}