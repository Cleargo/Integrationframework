<?php

namespace Cleargo\Integrationframeworks\Model\Component\AbstractComponent;

class ImplicitFtp {

	private $server;
	private $username;
	private $password;
	private $curlhandle;

	public function __construct($server, $username, $password) {
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->curlhandle = curl_init();
	}
	
	

	public function __destruct() {
		if (!empty($this->curlhandle))
			@curl_close($this->curlhandle);
	}

	public function download($remote, $local) {
		if ($fp = fopen($local, 'w')) {
			$this->curlhandle = $this->_setBaseOption($remote);
			curl_setopt($this->curlhandle, CURLOPT_UPLOAD, 0);
			curl_setopt($this->curlhandle, CURLOPT_FILE, $fp);

			curl_exec($this->curlhandle);
			if ($ret = curl_error($this->curlhandle)) {
				throw new \Exception($ret);
			}
			else {
				return $local;
			}
		}
		else {
			throw new \Exception("Fail to open file");
		}
	}

	public function upload($local ,$remote) {
		if ($fp = fopen($local, 'r')) {
			$this->curlhandle = $this->_setBaseOption($remote);
			curl_setopt($this->curlhandle, CURLOPT_UPLOAD, 1);
			curl_setopt($this->curlhandle, CURLOPT_INFILE, $fp);

			curl_exec($this->curlhandle);
			if ($ret = curl_error($this->curlhandle)) {
				throw new \Exception($ret);
			}
			else {
				return true;
			}
		}
		else {
			throw new \Exception("Fail to open file");
		}
	}

	public function delete($remote) {
		$this->curlhandle = $this->_setBaseOption("");
		curl_setopt($this->curlhandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curlhandle, CURLOPT_QUOTE, array("DELE /".$remote));

		curl_exec($this->curlhandle);
		if ($ret = curl_error($this->curlhandle)) {
			throw new \Exception($ret);
		}
		else {
			return true;
		}
	}
	
	/**
	 * @param string $remote remote path
	 * @return resource a cURL handle on success, false on errors.
	 */
	private function _setBaseOption($remote) {
		/*curl_reset($this->curlhandle);
		curl_setopt($this->curlhandle, CURLOPT_URL, 'ftps://' . $this->server . '/' . $remote);
		curl_setopt($this->curlhandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		curl_setopt($this->curlhandle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->curlhandle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->curlhandle, CURLOPT_FTP_SSL, CURLFTPSSL_ALL);
		curl_setopt($this->curlhandle, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_DEFAULT);
*/
		//curl_reset($this->curlhandle);
		curl_setopt($this->curlhandle, CURLOPT_URL, 'ftp://' . $this->server . '/' . $remote);
		curl_setopt($this->curlhandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		/*curl_setopt($this->curlhandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($this->curlhandle, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->curlhandle, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
        curl_setopt($this->curlhandle, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);*/
		return $this->curlhandle;
	}
}