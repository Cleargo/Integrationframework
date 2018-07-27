<?php

namespace Cleargo\Integrationframeworks\Model\Component;

trait SmbTrait
{
    protected $smbShare;
    
    protected function getSmbShare()
    {
        if(!$this->smbShare)
            $this->loadSmbShare();
        return $this->smbShare;
    }

    public function loadSmbShare()
    {
        $server = (new \Icewind\SMB\ServerFactory())
                    ->createServer(
                        $this->relationParams->smb_host,
                        new \Icewind\SMB\BasicAuth(
                            $this->relationParams->smb_user,
                            null,
                            $this->relationParams->smb_pw
                        )
                    );

        $this->smbShare = $server->getShare(
            $this->relationParams->smb_share ?? 'Eshop'
        );
    }
}