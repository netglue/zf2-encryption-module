<?php

namespace NetglueEncrypt\Form;

use Zend\Form\Form;

/**
 * Key Storage
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;

class Base extends Form {
	
	protected $keyStorage;
	
	public function setKeyStorage(KeyStorageInterface $storage) {
		$this->keyStorage = $storage;
		return $this;
	}
	
	public function getKeyStorage() {
		return $this->keyStorage;
	}
	
	public function getKeyListSelect($name) {
		$select = new \Zend\Form\Element\Select($name);
		$select->setAttributes(array(
			'id' => $name,
		));
		$select->setLabel('Key Pair');
		$select->setEmptyOption('-- Choose a Key Pair --');
		$names = $this->getKeyStorage()->getKeyPairNames();
		$opt = array();
		foreach($names as $name) {
			$opt[$name] = $name;
		}
		$select->setValueOptions($opt);
		$select->setValue('default');
		return $select;
	}
	
	protected function getOutputSelect($name) {
		$select = new \Zend\Form\Element\Select($name);
		$select->setAttributes(array(
			'id' => $name,
		));
		$select->setLabel('Output Type');
		$select->setEmptyOption('-- Select Output Type --');
		$opt = array(
			'binary' => 'Binary',
			'base64' => 'Base 64 Encoded',
		);
		$select->setValueOptions($opt);
		$select->setValue('base64');
		return $select;
	}
	
}