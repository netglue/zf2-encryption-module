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

The view helper is primarily intended for decrypting data present in the view automatically
when an appropriate pass phrase has been set in the session. In all other cases, the view helper should return a string
such as `<span class="encrypted">Encrypted</span>`

## TODO

* Generic Filter
* Download Keys
* Add sign and verify methods to controller plugin and view helper
* Add sign and verify features/forms to views
