<?php
namespace NetglueEncrypt;

/**
 * Autoloader
 */
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Service Provider
 */
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Config Provider
 */
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Controller Plugin Provider
 */
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;

/**
 * Controller Provider
 */
use Zend\ModuleManager\Feature\ControllerProviderInterface;

/**
 * Bootstrap Listener
 */
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface as Event;

use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements
	AutoloaderProviderInterface,
	ServiceProviderInterface,
	ConfigProviderInterface,
	BootstrapListenerInterface,
	ControllerPluginProviderInterface,
	ControllerProviderInterface,
	ViewHelperProviderInterface {
	
	
	/**
	 * Return autoloader configuration
	 * @link http://framework.zend.com/manual/2.0/en/user-guide/modules.html
	 * @return array
	 */
	public function getAutoloaderConfig() {
    return array(
			AutoloaderFactory::STANDARD_AUTOLOADER => array(
				StandardAutoloader::LOAD_NS => array(
					__NAMESPACE__ => __DIR__,
				),
			),
		);
	}
	
	/**
	 * Include/Return module configuration
	 * @return array
	 * @implements ConfigProviderInterface
	 */
	public function getConfig() {
		return include __DIR__ . '/../../config/module.config.php';
	}
	
	/**
	 * Return Service Config
	 * @return array
	 * @implements ServiceProviderInterface
	 */
	public function getServiceConfig() {
		return include __DIR__ . '/../../config/services.config.php';
	}
	
	/**
	 * Return controller plugin config
	 * @return array
	 */
	public function getControllerPluginConfig() {
		return array(
			'factories' => array(
				'NetglueEncrypt\Controller\Plugin\Crypt' => function($sm) {
					$sl = $sm->getServiceLocator();
					$plugin = new \NetglueEncrypt\Controller\Plugin\Crypt;
					$plugin->setKeyStorage($sl->get('NetglueEncrypt\KeyStorage'));
					$plugin->setSession($sl->get('NetglueEncrypt\Session'));
					return $plugin;
				},
			),
			'aliases' => array(
				'ngCrypt' => 'NetglueEncrypt\Controller\Plugin\Crypt',
			),
		);
	}
	
	/**
	 * Return view helper plugin config
	 * @return array
	 */
	public function getViewHelperConfig() {
		return array(
			'factories' => array(
				'NetglueEncrypt\View\Helper\Crypt' => function($sm) {
					$sl = $sm->getServiceLocator();
					$plugin = new \NetglueEncrypt\View\Helper\Crypt;
					$plugin->setKeyStorage($sl->get('NetglueEncrypt\KeyStorage'));
					$plugin->setSession($sl->get('NetglueEncrypt\Session'));
					return $plugin;
				},
			),
			'aliases' => array(
				'ngCrypt' => 'NetglueEncrypt\View\Helper\Crypt',
			),
		);
	}
	
	/**
	 * Return controller configuration
	 * @return array
	 */
	public function getControllerConfig() {
		return include __DIR__ . '/../../config/controllers.config.php';
	}
	
	/**
	 * MVC Bootstrap Event
	 *
	 * @param Event $e
	 * @return void
	 * @implements BootstrapListenerInterface
	 */
	public function onBootstrap(Event $e) {
		$app = $e->getApplication();
		$serviceMgr = $app->getServiceManager();
	}
}
