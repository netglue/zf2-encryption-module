<?php

return array(
	
	'invokables' => array(
		'NetglueEncrypt\Controller\KeyController' => 'NetglueEncrypt\Controller\KeyController',
	),
	
	'initializers' => array(
		'NetglueEncrypt\Controller\KeyStorageAware' => function($instance, $sm) {
			$sl = $sm->getServiceLocator();
			if(method_exists($instance, 'setKeyStorage')) {
				$storage = $sl->get('NetglueEncrypt\KeyStorage');
				$instance->setKeyStorage($storage);
			}
		},
	),
	
	'factories' => array(
	
	),
	
	'aliases' => array(
	
	),
	
	'abstract_factories' => array(
	
	),
	
);