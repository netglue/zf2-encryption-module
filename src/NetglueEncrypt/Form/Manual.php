<?php
namespace NetglueEncrypt\Form;
use Zend\InputFilter\InputFilterProviderInterface;
/**
 * Key Storage
 */
use NetglueEncrypt\KeyStorage\KeyStorageInterface;

class Manual extends Base implements InputFilterProviderInterface {
	
	public function __construct() {
		parent::__construct('netglue_manual_encrypt');
	}
	
	public function setKeyStorage(KeyStorageInterface $storage) {
		parent::setKeyStorage($storage);
		
		$this->add($this->getKeyListSelect('keyName'));
		
		$this->add(array(
			'name' => 'keyPassPhrase',
			'type' => 'password',
			'options' => array(
				'label' => 'Pass Phrase',
			),
			'attributes' => array(
				'id' => 'keyPassPhrase',
				'title' => 'Provide the password associated with your private key',
			),
		));
		
		
		$dir = new \Zend\Form\Element\Select('direction');
		$dir->setAttributes(array(
			'id' => 'direction',
		));
		$opt = array(
			'encrypt' => 'Encrypt',
			'decrypt' => 'Decrypt',
		);
		$dir->setValueOptions($opt);
		$dir->setLabel('Encrypt or Decrypt');
		$this->add($dir);
		
		$out = $this->getOutputSelect('outputType');
		$out->setLabel('Set encrypted output format');
		$this->add($out);
		
		$this->add(array(
			'name' => 'sourceText',
			'type' => 'textarea',
			'options' => array(
				'label' => 'Input Text',
			),
			'attributes' => array(
				'id' => 'sourceText',
			),
		));
		
		$this->add(array(
			'name' => 'processText',
			'type' => 'button',
			'attributes' => array(
				'id' => 'processText',
				'type' => 'submit',
			),
			'options' => array(
				'label' => 'Encrypt/Decrypt',
			),
		));
	}
	
	public function getInputFilterSpecification() {
		return array(
			'keyName' => array(
				'required' => true,
			),
			'keyPassPhrase' => array(
				'required' => false,
			),
			'direction' => array(
				'required' => true,
			),
			'outputType' => array(
				'required' => false,
			),
			'sourceText' => array(
				'required' => true,
			),
		);
	}
	
}