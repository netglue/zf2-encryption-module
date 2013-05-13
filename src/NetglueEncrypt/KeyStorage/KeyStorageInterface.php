<?php

namespace NetglueEncrypt\KeyStorage;

use Zend\Crypt\PublicKey\Rsa;
use Zend\Crypt\PublicKey\RsaOptions;

interface KeyStorageInterface {
	
	const DEFAULT_KEY_NAME = 'default';
	
	/**
	 * Return Rsa encryption instance using the options for the given named key pair
	 * @param string $name
	 * @param string $passPhrase Key password must be provided to load private keys that are encrypted with a pass phrase
	 * @return Rsa
	 */
	public function get($name = self::DEFAULT_KEY_NAME, $passPhrase = NULL);
	
	/**
	 * Persist a key pair using the provided name for identification
	 * @param Rsa $rsa
	 * @param string $name
	 * @return KeyStorageInterface $this
	 */
	public function set(Rsa $rsa, $name = self::DEFAULT_KEY_NAME);
	
	/**
	 * Whether the named key pair exists
	 * @param string $name
	 * @return bool
	 */
	public function has($name = self::DEFAULT_KEY_NAME);
	
	/**
	 * Return an array of available key names
	 * @return array
	 */
	public function getKeyPairNames();
	
}