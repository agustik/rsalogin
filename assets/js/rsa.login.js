

$(document).on('submit','#rsalogin', function(e){
  e.preventDefault();

  /*
    Define vars;
  */

	var elm 		= $(this),
		username	= elm.find('input[name="username"]').val(),
		password	= elm.find('input[name="password"]').val(),
		server 		= elm.attr('action'), data,
    secure_login = elm.attr('data-secure'),
    encrypted = false;


    /* Create object for handle username and password */
    var cleartext = {
      username : username,
      password : password
    }

    if(secure_login !== 'false'){

      /* Try to encrypt the object as string */
      encrypted = EncryptMessage(JSON.stringify(cleartext));
    }

  /* Selcect the encrypted if success */
  data = (encrypted) ? encrypted : cleartext;

  /* Do the ajax call. */
  $.ajax({
    url : server,
    method : 'POST',
    data : data,
    dataType:'JSON',
    success : function (result){
      console.log(result);
    }
  });
  return false;
});

function FetchPublicKey(){
  /*
    function to fetch the public key, can be ajax etc.
  */
	return  $('#public_key').val();
}

function EncryptMessage(message){

  /*
    Try's to encrypt the message if works.
  */

  try {
    var public_key = CleanPublickey(FetchPublicKey()), 
      result,
      key,
      rsa,
      asn,
      tree,
      crypted;
    if (public_key){
          
      key = pidCryptUtil.decodeBase64(public_key);
      //new RSA instance
      rsa = new pidCrypt.RSA();
      //RSA encryption

      //ASN1 parsing
      asn = pidCrypt.ASN1.decode(pidCryptUtil.toByteArray(key));

      //..
      tree = asn.toHexTree();

      //setting the public key for encryption
      rsa.setPublicKeyFromASN(tree);

      // Encrypted output.
      crypted = rsa.encrypt(message);

      // return as object.
      result = {
        hex : pidCryptUtil.formatHex(crypted,63),
        base64 : pidCryptUtil.fragment(pidCryptUtil.encodeBase64(pidCryptUtil.convertFromHex(crypted)),64),
      }
      return result;
    }else{
      console.log('Public key error.');
      return false;
    }
  }catch (e){
    console.log(e);
    return false;

  }
	
}


function CleanPublickey(p){
  try {

    // if any whitespaces then remove.
    if(p.indexOf(' ') !==-1){
      p = p.replace(/ /g,"");
    }

    // if header then remove.

    if(p.indexOf('-----BEGINPUBLICKEY-----') !== -1){
      p = p.replace(/-----BEGINPUBLICKEY-----/g,"")
    }

    // if footer, then remove.
    if(p.indexOf('-----ENDPUBLICKEY-----') !== -1){
      p = p.replace(/-----ENDPUBLICKEY-----/g,"")
    }

    // if any new lines, then remove.
    if(p.indexOf('\n') !==-1){
      p = p.replace(/\n/g,"");
    }
    return p;
  }
  catch (e){
    console.log(e);
    return false;
  }
}

