<?php
/**
 * Factory for returning a Key Storage instance
 */


namespace NetglueEncrypt\Service;

/**
 * To implement factory interface
 */
use Zend\ServiceManager\FactoryInterface;

/**
 * To accept Service Locator Objects
 */
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * For throwing ServiceManager Exceptions
 */
use Zend\ServiceManager\Exception as ZE;

use NetglueEncrypt\KeyStorage\KeyStorageInterface;

class KeyStorageFactory implements FactoryInterface {
	
	/**
	 * Create Service, Return a key storage instance as configured
	 * @return KeyStorageInterface
	 * @param ServiceLocatorInterface $services
	 */
	public function createService(ServiceLocatorInterface $services) {
		$serviceLocator = $services;
		$options = $serviceLocator->get('Config');
		$config = isset($options['netglue_encrypt']['key_storage']) ? $options['netglue_encrypt']['key_storage'] : false;
		if(false === $config || !is_array($config)) {
			throw new ZE\ServiceNotCreatedException('No key storage configuration provided');
		}
		$name = $config['name'];
		if($serviceLocator->has($name)) {
			return $serviceLocator->get($name);
		}
		$instance = NULL;
		if(class_exists($name)) {
			$instance = new $name($config['options']);
		}
		if(!$instance instanceof KeyStorageInterface) {
			throw new ZE\ServiceNotCreatedException('Unable to create an instance of KeyStorageInterface');
		}
		return $instance;
	}
	
}
