# ZF2 RSA Public Key Encryption Module


## Controller Plugin

The controller plugin will throw exceptions where the password has not been set in the session and a password is required
	
	// Use 'Default' Key Pair
	$encrypted = $this->ngCrypt()->encrypt('Some Text');
	$plain = $this->ngCrypt()->decrypt($encrypted);
	
	// Set Key Pair to use with it's name
	$this->ngCrypt()->setKeyName('Some Other Key Pair');
	
	// Check it's possible to use the plugin with the current selected key pair
	$plugin = $this->ngCrypt();
	if($plugin->isReady()) {
		$out = $plugin->encrypt('Foo');
		var_dump($out);
	}
	
## View Helper


## TODO

* View Helper
* Controller Plugin
* Generic Filter
* Download Keys
* Delete Keys
