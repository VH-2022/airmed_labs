<?php
	
	ini_set('display_errors',1);
	/* Credentials Given by ICICI */
	$p = [];
	$p["AGGRID"]="OTOE0193";
	$p["CORPID"]="CIBNEXT";
	$p["USERID"]="CIBTESTING6";
	$p["URN"]="SR198614934";
	$p['ACCOUNTNO']="000405001611";
	$p['FROMDATE']="01-01-2016";
	$p['TODATE']="30-12-2016";

	/* Credentials Given by ICICI */
	
	$httpUrl ="107.180.78.16";
	
    $request = [
        "CORPID" => $p["CORPID"],
        "USERID" => $p["USERID"],
        "AGGRID" => $p["AGGRID"],        
		"ACCOUNTNO" => $p['ACCOUNTNO'],
		"URN" => $p["URN"],
		"FROMDATE" => $p['FROMDATE'],
		"TODATE" => $p['TODATE']
    ];
    
    $header = [
        'apikey:VqvdtUsuAv4gon8lOhGNeJRwzoJhw8Xp',
        'Content-type:text/plain'
    ];
	
	//echo 'JSON :' ;
	//print_r(json_encode($request));
	//echo '<br />';
	
	//$url = 'https://apibankingonesandbox.icicibank.com/api/Corporate/CIB/v1/BalanceInquiry'; 
	$url = 'https://apibankingonesandbox.icicibank.com/api/Corporate/CIB/v1/AccountStatement';
	
	// openssl_get_publickey($pub_key);
	$pub_key = openssl_pkey_get_public(file_get_contents("cibnextapi.crt"));
	
	openssl_public_encrypt(json_encode($request),$crypttext,$pub_key, OPENSSL_PKCS1_PADDING);
    $enc = base64_encode($crypttext);
	
	$final_request = json_encode($enc);
	//echo 'encrypted JSON with public key :';
	//print_r($final_request); 
	//echo '<br />';
	
	//echo "API URL: ". $url . '<br />';
	
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "443",
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $final_request,
        CURLOPT_HTTPHEADER => $header
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
	print_r($err); 
    curl_close($curl);
    //echo "<br />Response: " . $response . '<br />';
	//echo $httpcode;
	
	$final_request = json_decode($response);
	//print_r($final_request->encryptedData);
	echo  '<br />';
	//$data = "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";
	//$enkey = "NEGBcO9nXYAQ4fPbpmmjdty0fY9mK69K4DNmlQBxk1kgI4Rpmkm9CaZgtccrThbcBZealMe8za1YKEu4KuigYEqGWdS/HBz8xw0QU+bV/7kshamQGlZZPI4xEfhTEAT6GNtBs8jy5086ifG5EGjFWz/NijM+S/uiGI3MrucKW/+vdvAlO803v6PxI8Kv4ob9ElFB3oaUHV2cKzGLW7tpa3QwLIZnWeJ7y7cGtqFLdoHK2JxKghAyqy2wSKAh7dvSs9PkqzpX0qFAHahD4BQvlbUeOuKq+uJ+LIA0PvoaVJ0vve9Vhr3iEZqDZ3hYoQhPsSuCYdt4WQtfBO3bYSP7Tfb3ur6YFq7ylny+PS3huEbKWSPyRN19YpbrFjTq2A/9knUJib2duUU9YZg06ANYApzjvU80jOTrfQwr20p8ZS0J13SXhwXWnXOWn4MZzERjJaS28mwE5RoThLnZZvOOZe0F66+ID52I5+Qv4vdSO7NtIdcV0vSAYqDtqVTxUix2EA3fKCOEeqbANWzfMx+XzZ8Lgz2sc25S3TPJfkEFxvXDe3UULhpj7G34lIJbUCD1FJARjJxHlNHVC+K3KG5IP549tD5OBvt5Uf6MqEpcIPzz8qkBdnVeJIfns1ylIaZ/AvMCBsf4psXGaMl+ML1UtngSuCMdLdDntfKUTtOfpTI=";
	//$resp = json_decode($response);
	//$data = base64_decode($data);
	$data = base64_decode($final_request->encryptedData);
	$iv = substr($data,0,16);
	//$key = DecryptData($enkey);
	$key = DecryptData($final_request->encryptedKey);
	$data = openssl_decrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
	echo $response = substr($data, 16);	
	$response= json_decode($response);
	
	//$response;
	
	function DecryptData($crypttext)
	{
		$private_key = openssl_pkey_get_private(file_get_contents("private.key"));
		openssl_private_decrypt(base64_decode($crypttext),$decrypttext,$private_key );
		return $decrypttext;
	}
?>