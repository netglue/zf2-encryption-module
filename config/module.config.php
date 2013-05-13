<?php
/**
 * Base Configuration for the Net Glue Encryption Module
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2012 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 */

/**
 * Default configuration options
 */

/**
 * Router Config
 */
$routes = include __DIR__.'/routes.config.php';

/**
 * View Script Path
 */
$viewPath = __DIR__ . '/../view/netglue-encrypt/key';

return array(
	'netglue_encrypt' => array(
		
		'key_storage' => array(
			'name' => 'NetglueEncrypt\KeyStorage\Filesystem',
			'options' => array(
				'basePath' => __DIR__ . '/../data',
			),
		),
	),
	
	
	'router' => array(
		'routes' => $routes,
	),
	
	'view_manager' => array(
		'template_path_stack' => array(
			//'netglue_encrypt' => __DIR__ . '/../view',
		),
		'template_map' => array(
			'netglue-encrypt/top' => $viewPath.'/_top.phtml',
			'netglue-encrypt/key/generate' => $viewPath.'/generate.phtml',
			'netglue-encrypt/key/index' => $viewPath.'/index.phtml',
			'netglue-encrypt/key/view' => $viewPath.'/view.phtml',
			'netglue-encrypt/key/manual' => $viewPath.'/manual.phtml',
			'netglue-encrypt/key/set-pass-phrase' => $viewPath.'/set-pass-phrase.phtml',
		),
	),
);
