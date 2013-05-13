<?php

namespace NetglueEncrypt\Service;

/**
 * To implement factory interface
 */
use Zend\ServiceManager\FactoryInterface;

/**
 * To accept Service Locator Objects
 */
use Zend\ServiceManager\ServiceLocatorInterface;



class KeyStorageFactory implements FactoryInterface {
	
	/**
	 * Create Service, Return a key storage instance as configured
	 * @return LogController
	 * @param ServiceLocatorInterface $services
	 */
	public function createService(ServiceLocatorInterface $services) {
		$serviceLocator = $services;
		$options = $serviceLocator->get('Config');
		$config = isset($options['netglue_encrypt']['key_storage']) ? $options['netglue_encrypt']['key_storage'] : false;
		if(false === $config || !is_array($config)) {
			throw new \RuntimeException('No key storage configuration provided');
		}
		$name = $config['name'];
		if($serviceLocator->has($name)) {
			return $serviceLocator->get($name);
		}
		if(class_exists($name)) {
			return new $name($config['options']);
		}
	}
	
	
}