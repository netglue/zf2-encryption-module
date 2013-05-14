<?php
/**
 * Simple Session container with methods specifically for managing pass phrases for
 * RSA key pairs
 */

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
	
	/**
	 * Store the pass phrase for the given key pair name
	 * @param string $passPhrase
	 * @param string $name If null the default key name will be used
	 * @return Container $this
	 */
	public function setPassPhrase($passPhrase, $name = NULL) {
		if(NULL === $name) {
			$name = KeyStorageInterface::DEFAULT_KEY_NAME;
		}
		$name = (string) $name;
		$this->getPassPhrases();
		$this->passPhrases[$name] = (string) $passPhrase;
		return $this;
	}
	
	/**
	 * Remove a single pass phrase from storage for the given key pair name
	 * @param string $name If null, default key name is used
	 * @return Container $this
	 */
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
	
	/**
	 * Remove all pass phrases from session storage
	 * @return Container $this
	 */
	public function clearAllPassPhrases() {
		foreach($this->getPassPhrases() as $name => $info) {
			$this->clearPassPhrase($name);
		}
		return $this;
	}
	
	/**
	 * Return the pass phrase for the given key pair name
	 *
	 * Returns boolean false if the pass phrase has not been set
	 *
	 * @param string $name
	 * @return string|false
	 */
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
