<?php

namespace NetglueEncrypt\Filter;

use Zend\Filter\AbstractFilter as ZendFilter;

/**
 * Key Storage & Session Container
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;
use NetglueEncrypt\Session\Container;


abstract class AbstractFilter extends ZendFilter {
	
	/**
	 * Key Storage
	 * @var KeyStorageInterface|NULL
	 */
	protected $keyStorage;
	
	/**
	 * Session Container
	 * @var Container|NULL
	 */
	protected $session;
	
	/**
	 * Current selected key pair name
	 * @var string|NULL
	 */
	protected $keyName;
	
	/**
	 * Set the key pair used for en/decryption by name
	 * @param string $name
	 * @return AbstractFilter $this
	 */
	public function setKeyName($name) {
		$this->keyName = $name;
		return $this;
	}
	
	/**
	 * Return current selected key name
	 * Returns the default key name if not set
	 * @return string
	 */
	public function getKeyName() {
		if(NULL !== $this->keyName) {
			return $this->keyName;
		}
		return KeyStorageInterface::DEFAULT_KEY_NAME;
	}
	
	/**
	 * Set Key Storage
	 * @param KeyStorageInterface $storage
	 * @return AbstractFilter $this
	 */
	public function setKeyStorage(KeyStorageInterface $storage) {
		$this->keyStorage = $storage;
		return $this;
	}
	
	/**
	 * Return Key Pair Storage device
	 * @return KeyStorageInterface|NULL
	 */
	public function getKeyStorage() {
		return $this->keyStorage;
	}
	
	/**
	 * Return session container for storing pass phrases
	 * @return Container|NULL
	 */
	public function getSession() {
		return $this->session;
	}
	
	/**
	 * Set Session Container
	 * @param Container $session
	 * @return AbstractFilter $this
	 */
	public function setSession(Container $session) {
		$this->session = $session;
		return $this;
	}
	
}