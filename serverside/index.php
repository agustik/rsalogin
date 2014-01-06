<?php

$username = (array_key_exists('username', $_POST)) ? $_POST['username'] : false;
$password = (array_key_exists('password', $_POST)) ? $_POST['password'] : false;
$rsa_data = (array_key_exists('rsa_data', $_POST)) ? $_POST['rsa_data'] : false;


$output = new stdClass();

function DecryptRSA($message,$private_key, $passphrase = false){
	
	// decode the base 64 message retuned from javascript.
	$message = base64_decode($message);

	// Unlock the priate key.
	$key = openssl_get_privatekey($private_key, $passphrase);

	// Decrypt the message.
	openssl_private_decrypt($encrypted,$newsource,$key);

	// Openssl returns message in base64.. so we need to decode.
	return base64_decode($newsource);
}
