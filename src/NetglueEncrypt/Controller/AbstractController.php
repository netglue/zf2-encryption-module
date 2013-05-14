<?php

namespace NetglueEncrypt\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Logging
 */
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;

/**
 * Key Storage
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;


abstract class AbstractController extends AbstractActionController {
	
	/**
	 * Logger
	 * @var LoggerInterface|NULL
	 */
	protected $logger;
	
	/**
	 * Key Storage
	 * @var KeyStorageInterface|NULL
	 */
	protected $keyStorage;
	
	/**
	 * Set Logger
	 * @param LoggerInterface $logger
	 * @return LoggerAwareInterface
	 */
	public function setLogger(LoggerInterface $logger) {
		$this->logger = $logger;
		return $this;
	}
	
	/**
	 * Return logger
	 * @return LoggerInterface|NULL
	 */
	public function getLogger() {
		return $this->logger;
	}
	
	/**
	 * Whether we have a logger
	 * @return bool
	 */
	public function hasLogger() {
		return $this->logger instanceof LoggerInterface;
	}
	
	/**
	 * Set Key Storage
	 * @param KeyStorageInterface $storage
	 * @return AbstractController $this
	 */
	public function setKeyStorage(KeyStorageInterface $storage) {
		$this->keyStorage = $storage;
		return $this;
	}
	
	/**
	 * Get Key Storage
	 * @return KeyStorageInterface|NULL
	 */
	public function getKeyStorage() {
		return $this->keyStorage;
	}
	
}
