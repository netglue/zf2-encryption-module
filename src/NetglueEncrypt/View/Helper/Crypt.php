<?php

namespace NetglueEncrypt\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Key Storage
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;
use NetglueEncrypt\Session\Container;

class Crypt extends AbstractHelper {
	
	/**
	 * Key Storage
	 * @var KeyStorageInterface|NULL
	 */
	protected $keyStorage;
	
	protected $session;
	
	protected $keyName;
	
	protected $encryptedTextReplacement = '<span class="muted encrypted">Encrypted</span>';
	
	/**
	 * By default, if the plugin is invoked with a string, the helper will try
	 * to decrypt the given input, otherwise, $this is returned to provide a fluent interface
	 * @param string $input
	 * @return Crypt|string
	 */
	public function __invoke($input = NULL) {
		if(is_string($input) && !empty($input)) {
			return $this->decrypt($input);
		}
		return $this;
	}
	
	public function setKeyName($name) {
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
		if($this->isReady()) {
			try {
				return $this->getKeyPair()->decrypt($input);
			} catch(\Exception $e) {
				return $this->encryptedTextReplacement;
			}
		}
		return $this->encryptedTextReplacement;
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