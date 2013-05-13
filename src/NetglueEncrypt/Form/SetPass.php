<?php
namespace NetglueEncrypt\Form;
use Zend\InputFilter\InputFilterProviderInterface;


class SetPass extends Base implements InputFilterProviderInterface {
	
	public function __construct() {
		parent::__construct('netglue_encrypt_set_pass');
		
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
		
		$this->add(array(
			'name' => 'setPass',
			'type' => 'button',
			'attributes' => array(
				'id' => 'setPass',
				'type' => 'submit',
			),
			'options' => array(
				'label' => 'Set Pass Phrase',
			),
		));
	}
	
	public function getInputFilterSpecification() {
		return array(
			'keyPassPhrase' => array(
				'required' => true,
			),
		);
	}
	
}