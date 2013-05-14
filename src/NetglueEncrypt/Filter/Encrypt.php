<?php

namespace NetglueEncrypt\Filter;

class Encrypt extends AbstractFilter {
	
	public function filter($input) {
		return $this->getKeyStorage()->get($this->getKeyName())->encrypt($input);
	}
	
}