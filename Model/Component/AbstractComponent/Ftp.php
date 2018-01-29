<?php
namespace Cleargo\Integrationframeworks\Model\Component\AbstractComponent;
/**
 * Extended FTP client
 */
class Ftp extends \Magento\Framework\Filesystem\Io\Ftp
{
    /**
     * Returns the last modified time of the given file
     * Note: Not all servers support this feature! Does not work with directories.
     *
     * @param string $filename
     *
     * @see http://php.net/manual/en/function.ftp-mdtm.php
     *
     * @return int
     */
    public function mdtm($filename)
    {
        return @ftp_mdtm($this->_conn, $filename);
    }

    public function setConnection($config){
        var_dump("setConnection START");
        $host = $config->ftp_host;
        $user = $config->ftp_user;
        $password = $config->ftp_pw;
        $secure_type = $config->secure_type;
        $file_pattern = $config->file_pattern;
        $upload_path = $config->upload_path;

        $connection = $this->open(
                    array(
                        'host' => $host,
                        'user' => $user,
                        'password' => $password,
                        'passive' => true
                    )
                );

        var_dump("setConnection END");
        return $connection;
    }
}