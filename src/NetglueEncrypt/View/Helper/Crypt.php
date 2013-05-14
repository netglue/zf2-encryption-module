<?php

namespace NetglueEncrypt\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Key Storage & Session Container
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;
use NetglueEncrypt\Session\Container;

/**
 * Exceptions
 */
use Zend\Crypt\PublicKey\Rsa\Exception\ExceptionInterface as RsaException;
use NetglueEncrypt\KeyStorage\Exception\ExceptionInterface as StorageException;

class Crypt extends AbstractHelper {
	
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
	 * Text/Html string returned when decryption is not possible
	 * @var string
	 */
	protected $placeholder = '<span class="muted encrypted">Encrypted</span>';
	
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
	
	/**
	 * Set Placeholder Text
	 * @param string $text
	 * @return Crypt $this
	 */
	public function setPlaceholder($text) {
		$this->placeholder = (string) $text;
		return $this;
	}
	
	/**
	 * Return placeholder text
	 * @return string
	 */
	public function getPlaceholder() {
		return $this->placeholder;
	}
	
	/**
	 * Set the key pair used for en/decryption by name
	 * @param string $name
	 * @return Crypt $this
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
	 * Whether it is theoretically possible for the view helper to decrypt or encrypt data
	 * The method is not capable of determining whether we are using the correct key pair for any given data
	 * @return bool
	 */
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
	
	/**
	 * Return the RSA Key Pair from storage
	 * 
	 * There's every chance an exception will be thrown while trying to retreive a key pair
	 * if for example, no password has been set
	 * @return Zend\Crypt\PublicKey\Rsa
	 */
	public function getKeyPair() {
		$storage = $this->getKeyStorage();
		$session = $this->getSession();
		$name = $this->getKeyName();
		$pass = $session->getPassPhrase($name);
		return $storage->get($name, $pass);
	}
	
	/**
	 * Encrypt Input with current selected key pair
	 * 
	 * This method does not try to catch any exceptions, nor does it check whether
	 * the key pair is ready for use - this is because the primary purpose of the
	 * view helper is for decryption
	 * 
	 * @param string $input
	 * @return string
	 */
	public function encrypt($input) {
		$rsa = $this->getKeyPair();
		return $rsa->encrypt($input);
	}
	
	/**
	 * Decrypt input
	 * 
	 * This is the method called by __invoke when appropriate
	 * @param string $input
	 * @return string
	 */
	public function decrypt($input) {
		if($this->isReady()) {
			try {
				return $this->getKeyPair()->decrypt($input);
			} catch(RsaException $e) {
				// Probably password is not set
			} catch(StorageException $e) {
				// No key pair exists or storage caught an RsaException
			}
		}
		return $this->placeholder;
	}
	
	/**
	 * Set Key Storage
	 * @param KeyStorageInterface $storage
	 * @return Crypt $this
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
	 * @return Crypt $this
	 */
	public function setSession(Container $session) {
		$this->session = $session;
		return $this;
	}
	
}