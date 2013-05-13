<?php
/**
 * Routes provided for viewing and managing log records
 * 
 */

$defaultName = \NetglueEncrypt\KeyStorage\KeyStorageInterface::DEFAULT_KEY_NAME;

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
		
		'setpass' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/set-pass-phrase[/:keyName]',
				'defaults' => array(
					'action' => 'set-pass-phrase',
					'keyName' => $defaultName,
				),
			),
		),
		'clearpass' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/clear-pass-phrase[/:keyName]',
				'defaults' => array(
					'action' => 'clear-pass-phrase',
					'keyName' => $defaultName,
				),
			),
		),
		'clearall' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/clear-all-pass-phrases',
				'defaults' => array(
					'action' => 'clear-all-pass-phrases',
				),
			),
		),
		'view' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/view-key-pair[/:keyName]',
				'defaults' => array(
					'action' => 'view',
					'keyName' => $defaultName,
				),
			),
		),
		'delete' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/delete-key-pair[/:keyName]',
				'defaults' => array(
					'action' => 'delete',
				),
			),
		),
		
	), // fi 'child_routes'
);

return array('netglue_encrypt' => $routes);
