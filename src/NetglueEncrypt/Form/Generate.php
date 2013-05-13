<?php

namespace NetglueEncrypt\Form;

use NetglueEncrypt\KeyStorage\KeyStorageInterface;

use Zend\InputFilter\InputFilterProviderInterface;

class Generate extends Base implements InputFilterProviderInterface {
	
	
	public function __construct(KeyStorageInterface $storage) {
		$this->setKeyStorage($storage);
		parent::__construct('netglue_key_generation');
		
		$this->add(array(
			'name' => 'keyName',
			'type' => 'text',
			'options' => array(
				'label' => 'Key Pair Name',
			),
			'attributes' => array(
				'id' => 'keyName',
				'title' => 'Provide a unique name for the new encrytion keys',
			),
		));
		
		$this->add(array(
			'name' => 'keyPassPhrase',
			'type' => 'password',
			'options' => array(
				'label' => 'Pass Phrase',
			),
			'attributes' => array(
				'id' => 'keyPassPhrase',
				'title' => 'Provide a password used to encrypt your private key',
			),
		));
		
		$this->add($this->getHashSelect('digestAlgo'));
		$this->add($this->getKeySizeSelect('keySize'));
		$this->add($this->getOutputSelect('outputType'));
		
		
		$this->add(array(
			'name' => 'saveKeys',
			'type' => 'button',
			'attributes' => array(
				'id' => 'saveKeys',
				'type' => 'submit',
			),
			'options' => array(
				'label' => 'Generate Keys',
			),
		));
		
		
	}
	
	protected function getHashSelect($name) {
		$select = new \Zend\Form\Element\Select($name);
		$select->setAttributes(array(
			'id' => $name,
		));
		$select->setLabel('Digest Algorithm');
		$select->setEmptyOption('-- Select Digest Algorithm --');
		$algos = openssl_get_md_methods();
		$hashes = array();
		foreach($algos as $hash) {
			$upper = strtoupper($hash);
			if(defined('OPENSSL_ALGO_' . $upper)) {
				$hashes[$hash] = $upper;
			}
		}
		$hashes = array_unique($hashes);
		$select->setValueOptions($hashes);
		$select->setValue('sha1');
		return $select;
	}
	
	protected function getKeySizeSelect($name) {
		$select = new \Zend\Form\Element\Select($name);
		$select->setAttributes(array(
			'id' => $name,
		));
		$select->setLabel('Key Size');
		$select->setEmptyOption('-- Select Key Size --');
		$opt = array();
		for($pow = 11; $pow <= 15; $pow++) {
			$v = pow(2, $pow);
			$opt[$v] = number_format($v).' Bytes';
		}
		$select->setValueOptions($opt);
		$select->setValue(\Zend\Crypt\PublicKey\Rsa\PrivateKey::DEFAULT_KEY_SIZE);
		return $select;
	}
	
	
	
	
	public function getInputFilterSpecification() {
		return array(
			'keyName' => array(
				'required' => true,
				'filters' => array(
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'min' => 2,
						),
					),
				),
			),
			'keyPassPhrase' => array(
				'required' => false,
			),
			'digestAlgo' => array(
				'required' => true,
			),
			'keySize' => array(
				'required' => true,
				'filters' => array(
					array('name'=>'Int'),
				),
			),
			'outputType' => array(
				'required' => true,
			),
		);
	}
	
	
	
}