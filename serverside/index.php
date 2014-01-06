<?php

$username = (array_key_exists('username', $_POST)) 	? $_POST['username'] : false;
$password = (array_key_exists('password', $_POST)) 	? $_POST['password'] : false;
$rsa_data = (array_key_exists('base64', $_POST)) 	? $_POST['base64'] : false;



// load the private key.
$private_key = file_get_contents('../keys/private.key');

$output->status='fail';

$output = new stdClass();

if ($rsa_data){
	$decrypted = json_decode(DecryptRSA($rsa_data, $private_key));
	$output->status = "success";
	$output->data->message="Message was decrypted successfully";
	$output->data->username=$decrypted->username;
}else{
	$output->status = "success";
	$output->data->message="Fallback to cleartext";
	$output->data->username = $username;
}

print json_encode($output);

function DecryptRSA($message,$private_key, $passphrase = false){
	try {
		// decode the base 64 message retuned from javascript.
		$message = base64_decode($message);

		// Unlock the priate key.
		$key = openssl_get_privatekey($private_key, $passphrase);

		// Decrypt the message.
		openssl_private_decrypt($message,$newsource,$key);

		// Openssl returns message in base64.. so we need to decode.
		return base64_decode($newsource);
	}
	catch (Exception $e){
		return false;
	}
	
}
