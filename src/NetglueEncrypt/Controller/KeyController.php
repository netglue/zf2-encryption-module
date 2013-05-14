<?php

namespace NetglueEncrypt\Controller;

use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response as Response;

use Zend\Crypt\PublicKey\RsaOptions;
use Zend\Crypt\PublicKey\Rsa;


class KeyController extends AbstractController {
	
	const ROUTE_HOME = 'netglue_encrypt';
	const ROUTE_GEN_KEYS = 'netglue_encrypt/generate';
	const ROUTE_SET_PASS = 'netglue_encrypt/setpass';
	
	
	/**
	 * List Key Pairs
	 * @return ViewModel
	 */
	public function indexAction() {
		$view = new ViewModel;
		
		$keyPairs = array();
		
		$storage = $this->getKeyStorage();
		$session = $this->getSession();
		
		foreach($storage->getKeyPairNames() as $name) {
			$keyPairs[$name] = array(
				'name' => $name,
				'passwordSet' => $session->hasPassPhrase($name),
				'requiresPassword' => $storage->requiresPassPhrase($name),
			);
		}
		$view->keyPairs = $keyPairs;
		return $view;
	}
	
	/**
	 * View Key Pair
	 * @return ViewModel
	 */
	public function viewAction() {
		$params = $this->params()->fromRoute();
		$keyName = $params['keyName'];
		$storage = $this->getKeyStorage();
		$session = $this->getSession();
		if(!$storage->has($keyName)) {
			$this->flashMessenger()->addErrorMessage("There is no key pair by the name {$keyName}");
			return $this->redirect()->toRoute(static::ROUTE_HOME);
		}
		$view = new ViewModel;
		$pass = $session->getPassPhrase($keyName);
		$view->rsa = $storage->get($keyName, $pass);
		$view->keyName = $keyName;
		return $view;
	}
	
	/**
	 * Delete Key Pair
	 * @return ViewModel
	 */
	public function deleteAction() {
		$params = $this->params()->fromRoute();
		$keyName = $params['keyName'];
		$storage = $this->getKeyStorage();
		$session = $this->getSession();
		if(!$storage->has($keyName)) {
			$this->flashMessenger()->addErrorMessage("There is no key pair by the name {$keyName}");
			return $this->redirect()->toRoute(static::ROUTE_HOME);
		}
		if($storage->requiresPassPhrase($keyName) && !$session->hasPassPhrase($keyName)) {
			$this->flashMessenger()->addInfoMessage("Please set the pass phrase for {$keyName} before deleting it");
			return $this->redirect()->toRoute(static::ROUTE_SET_PASS, array('keyName' => $keyName));
		}
		$storage->delete($keyName);
		$this->flashMessenger()->addSuccessMessage("The key pair {$keyName} has been deleted");
		return $this->redirect()->toRoute(static::ROUTE_HOME);
	}
	
	/**
	 * Clear all pass phrases from the session
	 * @return void
	 */
	public function clearAllPassPhrasesAction() {
		$session = $this->getSession();
		$session->clearAllPassPhrases();
		$this->flashMessenger()->addSuccessMessage("All set pass phrases have been cleared");
		return $this->redirect()->toRoute(static::ROUTE_HOME);
	}
	
	/**
	 * Clear a single pass phrase from the session
	 * @return void
	 */
	public function clearPassPhraseAction() {
		$params = $this->params()->fromRoute();
		$keyName = $params['keyName'];
		$storage = $this->getKeyStorage();
		if(!$storage->has($keyName)) {
			$this->flashMessenger()->addErrorMessage("There is no key pair by the name {$keyName}");
		} elseif(!$storage->requiresPassPhrase($keyName)) {
			$this->flashMessenger()->addInfoMessage("The key pair {$keyName} does not require a pass phrase");
		} else {
			$session = $this->getSession();
			$session->clearPassPhrase($keyName);
			$this->flashMessenger()->addSuccessMessage("The pass phrase for the key pair {$keyName} has been cleared");
		}
		return $this->redirect()->toRoute(static::ROUTE_HOME);
	}
	
	/**
	 * Set the pass phrase for a key pair
	 * @return ViewModel
	 */
	public function setPassPhraseAction() {
		$params = $this->params()->fromRoute();
		$keyName = $params['keyName'];
		$storage = $this->getKeyStorage();
		if(!$storage->has($keyName)) {
			$this->flashMessenger()->addErrorMessage("There is no key pair by the name {$keyName}");
			return $this->redirect()->toRoute(static::ROUTE_HOME);
		}
		if(!$storage->requiresPassPhrase($keyName)) {
			$this->flashMessenger()->addInfoMessage("The key pair {$keyName} does not require a pass phrase");
			return $this->redirect()->toRoute(static::ROUTE_HOME);
		}
		
		$view = new ViewModel;
		$view->keyName = $keyName;
		$view->form = $this->getPassPhraseForm();
		$view->error = false;
		$redirectUrl = $this->url()->fromRoute(self::ROUTE_SET_PASS, array(), array(), true);
		$view->form->setAttribute('action', $redirectUrl);
		$prg = $this->prg($redirectUrl, true);
		if($prg instanceof Response) {
			return $prg;
		}
		if($prg === false) {
			return $view;
		}
		$view->form->setData($prg);
		if(!$view->form->isValid()) {
			$view->error = true;
			return $view;
		}
		
		$post = $view->form->getData();
		$storage = $this->getKeyStorage();
		// Make sure the password is correct
		try {
			$rsa = $storage->get($keyName, $post['keyPassPhrase']);
		} catch(\Exception $e) {
			$view->form->setMessages(array(
				'keyPassPhrase' => array(
					'The pass phrase entered is probably incorrect',
					$e->getMessage(),
				),
			));
			return $view;
		}
		$session = $this->getSession();
		$session->setPassPhrase($post['keyPassPhrase'], $keyName);
		$this->flashMessenger()->addSuccessMessage("The pass phrase for the key pair {$keyName} has been set");
		return $this->redirect()->toRoute(static::ROUTE_HOME);
	}
	
	/**
	 * Return session container for storing pass phrases
	 * @return NetglueEncrypt\Session\Container
	 */
	public function getSession() {
		return $this->getServiceLocator()->get('NetglueEncrypt\Session');
	}
	
	/**
	 * Manually encypt or decrypt data
	 * @return ViewModel
	 */
	public function manualAction() {
		$view = new ViewModel;
		$view->form = $this->getManualForm();
		$view->error = false;
		$view->result = NULL;
		
		$request = $this->getRequest();
		if(!$request->isPost()) {
			return $view;	
		}
		$view->form->setData($request->getPost());
		if(!$view->form->isValid()) {
			$view->error = true;
			return $view;
		}
		
		$post = $view->form->getData();
		$keys = $this->getKeyStorage();
		try {
			$pass = $post['keyPassPhrase'];
			$session = $this->getSession();
			if($session->hasPassPhrase($post['keyName'])) {
				$pass = $session->getPassPhrase($post['keyName']);
			}
			$session->setPassPhrase($pass, $post['keyName']);
			$rsa = $keys->get($post['keyName'], $pass);
		} catch(\Exception $e) {
			$view->form->setMessages(array(
				'keyName' => array(
					'Failed to load keys. Please check the pass phrase.',
					$e->getMessage(),
				),
			));
			return $view;
		}
		$rsa->getOptions()->setBinaryOutput($post['outputType'] === 'binary');
		$method = $post['direction'] === 'encrypt' ? 'encrypt' : 'decrypt';
		try {
			$view->result = $rsa->{$method}($post['sourceText']);
		} catch(\Exception $e) {
			$view->form->setMessages(array(
				'keyName' => array(
					'Failed to encrypt or decrypt data',
					$e->getMessage(),
				),
			));
		}
		return $view;
	}
	
	/**
	 * Controller Action for generation of a new key pair
	 * @return ViewModel
	 */
	public function generateAction() {
		$view = new ViewModel;
		$view->form = $this->getGenerateForm();
		$view->error = false;
		
		$redirectUrl = $this->url()->fromRoute(static::ROUTE_GEN_KEYS);
		$prg = $this->prg($redirectUrl, true);
		
		if($prg instanceof Response) {
			return $prg;
		}
		
		if($prg === false) {
			return $view;
		}
		
		$view->form->setData($prg);
		if(!$view->form->isValid()) {
			$view->error = true;
			return $view;
		}
		
		$post = $view->form->getData();
		$keys = $this->getKeyStorage();
		
		try {
			$rsaOptions = array();
			if(!empty($post['keyPassPhrase'])) {
				$rsaOptions['passPhrase'] = $post['keyPassPhrase'];
			}
			$rsaOptions['binaryOutput'] = $post['outputType'] === 'binary';
			$rsaOptions['hashAlgorithm'] = $post['digestAlgo'];
			$rsaOptions = new RsaOptions($rsaOptions);
			$rsaOptions->generateKeys(array(
				'private_key_bits' => $post['keySize'],
			));
			$rsa = new Rsa($rsaOptions);
			$keys->set($rsa, $post['keyName']);
		} catch(Exception $e) {
			$view->error = true;
			$message = 'Failed to Create Key Pair: '.$e->getMessage();
			$form->setMessages(array('keyName' => $message));
			return $view;
		}
		$this->flashMessenger()->addSuccessMessage('New RSA Key Pair Created Successfully');
		return $this->redirect()->toRoute(static::ROUTE_HOME);
	}
	
	/**
	 * Return form for generating keys
	 * @return NetglueEncrypt\Form\Generate
	 */
	public function getGenerateForm() {
		$sl = $this->getServiceLocator();
		return $sl->get('NetglueEncrypt\Form\GenerateKeys');
	}
	
	/**
	 * Return form for encrypting and decrypting data
	 * @return NetglueEncrypt\Form\Manual
	 */
	public function getManualForm() {
		$sl = $this->getServiceLocator();
		return $sl->get('NetglueEncrypt\Form\Manual');
	}
	
	/**
	 * Return form for setting the passphrase for a key
	 * @return NetglueEncrypt\Form\SetPass
	 */
	public function getPassPhraseForm() {
		$sl = $this->getServiceLocator();
		return $sl->get('NetglueEncrypt\Form\SetPass');
	}
}