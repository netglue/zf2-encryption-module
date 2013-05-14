<?php
/**
 * Abstract Options class specifically for filesystem key storage
 */


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
	protected $privateKeyFileMode = 0600;
	
	/**
	 * File create mode for the public key
	 * @var int
	 */
	protected $publicKeyFileMode = 0644;
	
	/**
	 * Set base key storage directory
	 * @param string $path
	 * @return FilesystemOptions $this
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
	
	/**
	 * Whether we should delete old keys when a replacement is generated
	 * @param bool $flag
	 * @return FilesystemOptions $this
	 */
	public function setDeleteOldKeys($flag) {
		$this->deleteOldKeys = (bool) $flag;
		return $this;
	}
	
	/**
	 * Whether we should delete old keys when a replacement is generated
	 * @return bool
	 */
	public function getDeleteOldKeys() {
		return $this->deleteOldKeys;
	}
	
	/**
	 * Set file mode for private keys
	 * @param int $mode (Must be octal)
	 * @return FilesystemOptions $this
	 */
	public function setPrivateKeyFileMode($mode) {
		$this->privateKeyFileMode = $mode;
		return $this;
	}
	
	/**
	 * Return file mode for private keys
	 * @return int
	 */
	public function getPrivateKeyFileMode() {
		return $this->privateKeyFileMode;
	}
	
	/**
	 * Set file mode for public keys
	 * @param int $mode (Must be octal)
	 * @return FilesystemOptions $this
	 */
	public function setPublicKeyFileMode($mode) {
		$this->publicKeyFileMode = $mode;
		return $this;
	}
	
	/**
	 * Return file mode for public keys
	 * @return int
	 */
	public function getPublicKeyFileMode() {
		return $this->publicKeyFileMode;
	}
}