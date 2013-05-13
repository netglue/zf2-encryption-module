<?php
/**
 * Service Configuration for the NetglueEncrypt Module
 */

return array(
	
	'invokables' => array(
		'NetglueEncrypt\Form\Manual' => 'NetglueEncrypt\Form\Manual',
	),
	
	'initializers' => array(
		'NetglueEncrypt\Form\KeyStorageAware' => function($instance, $sl) {
			if(method_exists($instance, 'setKeyStorage')) {
				$storage = $sl->get('NetglueEncrypt\KeyStorage');
				$instance->setKeyStorage($storage);
			}
		},
	),
	
	'factories' => array(
		'NetglueEncrypt\KeyStorage' => 'NetglueEncrypt\Service\KeyStorageFactory',
		
		'NetglueEncrypt\Form\GenerateKeys' => function($sm) {
			$storage = $sm->get('NetglueEncrypt\KeyStorage');
			$form = new \NetglueEncrypt\Form\Generate($storage);
			return $form;
		},
		
	),
	
	'aliases' => array(
		
	),
	
);