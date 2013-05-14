<?php

namespace NetglueEncrypt\Filter;

class Decrypt extends AbstractFilter {
	
	public function filter($input) {
		$session = $this->getSession();
		$storage = $this->getKeyStorage();
		$name = $this->getKeyName();
		$pass = $session->getPassPhrase($name);
		$rsa = $storage->get($name, $pass);
		return $rsa->decrypt($input);
	}
	
}