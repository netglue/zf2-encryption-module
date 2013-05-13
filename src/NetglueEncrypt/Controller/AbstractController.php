<?php

namespace NetglueEncrypt\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Logging
 */
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;



abstract class AbstractController extends AbstractActionController {
	
	/**
	 * Logger
	 * @var LoggerInterface|NULL
	 */
	protected $logger;
	
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
	
}
