<?php
/**
 * Routes provided for viewing and managing log records
 * 
 */

$routes = array(
	'type' => 'Literal',
	'options' => array(
		'route' => '/keys',
		'defaults' => array(
			'__NAMESPACE__' => 'NetglueEncrypt\Controller',
			'controller' => 'KeyController',
			'action' => 'index',
		),
	),
	'may_terminate' => true,
	'child_routes' => array(
		
		'generate' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/generate',
				'defaults' => array(
					'action' => 'generate',
				),
			),
		),
		
		'manual' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/encrypt-decrypt',
				'defaults' => array(
					'action' => 'manual',
				),
			),
		),
		
	), // fi 'child_routes'
);

return array('netglue_encrypt' => $routes);
