<?php

$username = (array_key_exists('username', $_POST)) 	? $_POST['username'] : false;
$password = (array_key_exists('password', $_POST)) 	? $_POST['password'] : false;
$rsa_data = (array_key_exists('base64', $_POST)) 	? $_POST['base64'] : false;


$private_key = file_get_contents('../keys/private.key');


$output = new stdClass();

if ($rsa_data){
	$decrypted = DecryptRSA($rsa_data, $private_key);
	var_dump($decrypted);
}





function DecryptRSA($message,$private_key, $passphrase = false){
	try {
		// decode the base 64 message retuned from javascript.
		$message = base64_decode($message);

		// Unlock the priate key.
		$key = openssl_get_privatekey($private_key, $passphrase);

		// Decrypt the message.
		openssl_private_decrypt($encrypted,$newsource,$key);

		// Openssl returns message in base64.. so we need to decode.
		return base64_decode($newsource);
	}
	catch (Exception $e){
		return false;
	}
	
}
