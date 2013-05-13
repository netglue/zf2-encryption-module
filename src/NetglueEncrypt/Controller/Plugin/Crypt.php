<?php

namespace NetglueEncrypt\Controller\Plugin;

/**
 * Key Storage
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;
use NetglueEncrypt\Session\Container;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Crypt extends AbstractPlugin {
	
	/**
	 * Key Storage
	 * @var KeyStorageInterface|NULL
	 */
	protected $keyStorage;
	
	protected $session;
	
	protected $keyName;
	
	public function __invoke() {
		return $this;
	}
	
	public function setKeyName($name) {
		$storage = $this->getKeyStorage();
		if(!$storage->has($name)) {
			throw new \InvalidArgumentException("There is no key pair with the name {$name}");
		}
		$this->keyName = $name;
		return $this;
	}
	
	public function getKeyName() {
		if(NULL !== $this->keyName) {
			return $this->keyName;
		}
		return KeyStorageInterface::DEFAULT_KEY_NAME;
	}
	
	public function isReady() {
		$name = $this->getKeyName();
		$storage = $this->getKeyStorage();
		if(!$storage->has($name)) {
			return false;
		}
		if($storage->requiresPassPhrase($name)) {
			$session = $this->getSession();
			if(!$session->hasPassPhrase($name)) {
				return false;
			}
		}
		return true;
	}
	
	public function getKeyPair() {
		$storage = $this->getKeyStorage();
		$session = $this->getSession();
		$name = $this->getKeyName();
		$pass = $session->getPassPhrase($name);
		return $storage->get($name, $pass);
	}
	
	public function encrypt($input) {
		$rsa = $this->getKeyPair();
		return $rsa->encrypt($input);
	}
	
	public function decrypt($input) {
		return $this->getKeyPair()->decrypt($input);
	}
	
	public function setKeyStorage(KeyStorageInterface $storage) {
		$this->keyStorage = $storage;
		return $this;
	}
	
	public function getKeyStorage() {
		return $this->keyStorage;
	}
	
	/**
	 * Return session container for storing pass phrases
	 * @return NetglueEncrypt\Session\Container
	 */
	public function getSession() {
		return $this->session;
	}
	
	public function setSession(Container $session) {
		$this->session = $session;
		return $this;
	}
	
}