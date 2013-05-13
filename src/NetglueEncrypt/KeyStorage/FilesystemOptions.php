<?php

namespace NetglueEncrypt\KeyStorage;

use Zend\Stdlib\AbstractOptions;

class FilesystemOptions extends AbstractOptions {
	
	/**
	 * Base path for key storage
	 * @var string|NULL
	 */
	protected $basePath;
	
	/**
	 * Whether replaced keys should be removed from disk when changed
	 * @var bool
	 */
	protected $deleteOldKeys = false;
	
	/**
	 * File create mode for the private key
	 * @var int
	 */
	protected $privateKeyFileMode = 0700;
	
	/**
	 * File create mode for the public key
	 * @var int
	 */
	protected $publicKeyFileMode = 0744;
	
	/**
	 * Set base key storage directory
	 * @param string $path
	 * @return FilesystemOptions $this
	 * @throws Exception\InvalidArgumentException if the directory doesn't exist, is not readable or is not writable
	 */
	public function setBasePath($path) {
		$this->basePath = rtrim($path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	/**
	 * Return base key storage directory
	 * @return string
	 */
	public function getBasePath() {
		return $this->basePath;
	}
	
	public function setDeleteOldKeys($flag) {
		$this->deleteOldKeys = (bool) $flag;
		return $this;
	}
	
	public function getDeleteOldKeys() {
		return $this->deleteOldKeys;
	}
	
	public function setPrivateKeyFileMode($mode) {
		$this->privateKeyFileMode = $mode;
		return $this;
	}
	
	public function getPrivateKeyFileMode() {
		return $this->privateKeyFileMode;
	}
	
	public function setPublicKeyFileMode($mode) {
		$this->publicKeyFileMode = $mode;
		return $this;
	}
	
	public function getPublicKeyFileMode() {
		return $this->publicKeyFileMode;
	}
}