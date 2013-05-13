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

return array(
	'netglue_encrypt' => array(
		
	),
	'router' => array(
		'routes' => $routes,
	),
	
	'view_manager' => array(
		'template_path_stack' => array(
			'netglue_encrypt' => __DIR__ . '/../view',
		),
	),
);
