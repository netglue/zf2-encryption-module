<?php

namespace NetglueEncrypt\Session;

use Zend\Session\Container as ZendContainer;

use NetglueEncrypt\KeyStorage\KeyStorageInterface;

class Container extends ZendContainer {
	
	/**
	 * Whether we have a pass phrase for the key pair identified by $name
	 * @param string $name
	 * @return bool
	 */
	public function hasPassPhrase($name = NULL) {
		if(NULL === $name) {
			$name = KeyStorageInterface::DEFAULT_KEY_NAME;
		}
		return array_key_exists($name, $this->getPassPhrases());
	}
	
	public function setPassPhrase($passPhrase, $name = NULL) {
		if(NULL === $name) {
			$name = KeyStorageInterface::DEFAULT_KEY_NAME;
		}
		$name = (string) $name;
		$this->getPassPhrases();
		$this->passPhrases[$name] = (string) $passPhrase;
		return $this;
	}
	
	public function clearPassPhrase($name = NULL) {
		if(NULL === $name) {
			$name = KeyStorageInterface::DEFAULT_KEY_NAME;
		}
		$name = (string) $name;
		$this->getPassPhrases();
		$this->passPhrases[$name] = NULL;
		unset($this->passPhrases[$name]);
		return $this;
	}
	
	public function clearAllPassPhrases() {
		foreach($this->getPassPhrases() as $name => $info) {
			$this->clearPassPhrase($name);
		}
		return $this;
	}
	
	public function getPassPhrase($name = NULL) {
		if(NULL === $name) {
			$name = KeyStorageInterface::DEFAULT_KEY_NAME;
		}
		$name = (string) $name;
		$all = $this->getPassPhrases();
		if(array_key_exists($name, $all)) {
			return $all[$name];
		}
		return false;
	}
	
	/**
	 * Return array of pass phrases stored
	 * @return array
	 */
	protected function getPassPhrases() {
		if(!isset($this->passPhrases)) {
			$this->passPhrases = array();
		}
		return $this->passPhrases;
	}
}