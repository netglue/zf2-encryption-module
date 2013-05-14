# ZF2 RSA Key Pair Management Module

## Introduction

ZF2's RSA Key Tools are super easy to use and truly excellent! This module is
intended to solve the problem where you have non-technical users that need to be
able to decrypt sensitive information stored in the app but still keep some semblance of
security and privacy.

It's expected that the various routes/controllers would be protected by some other
kind of authentication module such as [ZfcUser](https://github.com/ZF-Commons/ZfcUser),
[ZfcRbac](https://github.com/ZF-Commons/ZfcRbac), [ZfcAcl](https://github.com/ZF-Commons/ZfcAcl) et al
This module makes no attempt to prevent unathorised access to the key pair management tools.

## Security Issues

I'm no security or encryption expert. It makes sense to me that it's not a great
idea to have the private and public keys stored together on the server. A better
solution might be to only store the public key and have the user paste in the private
key and store that in the session whenever decryption needs to occur, but for my
needs it's simpler and easier for the user to just enter a password to enable decryption
therefore I would never advocate not encrypting the private key with a pass phrase. At least
then if your database full of encrypted data _and_ the private key are compromised, the
password contains one last line of defence...

Also, as the session container has no special configuration, it would likely be prudent to
set up the default session manager with some sane values such as limiting the 
lifetime of the session container etc.

## Installation

The module should be installed with [composer](http://getcomposer.org). It's name is `netglue/zf2-encryption-module`
If you have problems installing with composer, check your `minimum-stability` setting.

Enable the module in your main config file. The module name you should enter is `NetglueEncrypt`

Look in the `vendor/netglue/zf2-encryption-module` directory once installed and look at the config files to see what can be altered for your app.

## View Scripts

All of the view scripts are mapped using the `['view_manager']['template_map']` keys with template names prefixed with `netglue-encrypt`
so they should be easy to override with your own view scripts

## Routes

If you look in `module-name/config/routes.config.php` you'll find that the url locations can be easily overridden.

## Key Pair Storage

This is the main functionality of the module that provides an interface to an abstract key storage idea.
At the moment there is only filesystem key storage but the theory is that you can drop in different classes that implement
`NetglueEncrypt\KeyStorage\KeyStorageInterface` without much bother.

The default key storage instance that gets configured is accessed with the service manager using the key:
	
	$serviceLocator->get('NetglueEncrypt\KeyStorage');

## Session Pass Phrase Storage

You can get the session container where passwords are stored from the service locator

	$serviceLocator->get('NetglueEncrypt\Session');

The container has some very straight forward methods for getting/setting pass phrases and checking whether one has been set or not.

For interoperability with other modules, I figured it best not to do any
special setup of the session using the session manager - I didn't think it was
an appropriate place to determine how a session should behave as it's something
that gets done usually on an application-wide basis. So, the container is just
instantiated with `new Container('netglue_encrypt')` meaning you should be able to
retrieve it from the default session manager with this name too.

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

Operation is pretty similar to the controller plugin.

	// Assuming the controller has provided $this->encryptedData to the view model
	// and we're using the default key pair
	
	// You should expect no exceptions to be thrown when decrypting data
	echo $this->ngCrypt($this->encryptedData); // Either the decrypted string or the placeholder such as 'Encrypted'
	
	// Using a different key pair
	echo $this->ngCrypt()->setKeyName('Some Key')->decrypt($this->encryptedData);
	// or
	$this->ngCrypt()->setKeyName('Some Key');
	echo $this->ngCrypt($this->encryptedData);
	
	// Change the placeholder text returned when decryption is not possible
	$this->ngCrypt()->setPlaceholder('<strong>No Workie</strong>');
	
## Filters

There are 2 filters avalaible for use and instances can be retrieved from the service manager:
	
	$encryptFilter = $serviceLocator->get('NetlgueEncrypt\Filter\Encrypt');
	$decryptFilter = $serviceLocator->get('NetlgueEncrypt\Filter\Decrypt');

They behave as you'd expect - I'm not sure about having them retrieved from the service manager and whether this is good practice or not. I'm open to alterbnatives!


## TODO

* Download Keys
* Import Keys
* Add sign and verify methods to controller plugin and view helper
* Add sign and verify features/forms to views
* Clean up exception handling throughout to catch the correct class of exceptions in try blocks
* Tests
* Inline Docs in views
* More key storage devices such as DB? Mongo?

## Further ideas

* It might be useful to know how old a key pair is and it would be trivial to implement this in the key storage interface
* Provide features that make it easy to sign and encrypt email messages
* Perhaps allow a way to publicly expose and download public keys so they can be used by third parties to encrypt data or verify signatures etc.


