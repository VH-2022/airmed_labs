<?php
		ini_set('display_errors',1);
	/* Credentials Given by ICICI */
	$p = [];
	$p["AGGRID"]="OTOE0193";	
	$p["CORPID"]="PIXELDIA02092020";
	$p["USERID"]="AMITGUPT";
	$p["URN"]="SR189932540";	
	$p["AGGRNAME"]="URJA";
	$p["DEBITACC"]="403105000300";
	$p["CREDITACC"]="403105000301";
	$p["IFSC"]="ICIC0000011";
	$p["AMOUNT"] = "1.00";		
	$p["ALIASID"] =""; 
	$p["CURRENCY"] ="INR"; 
	$p["TXNTYPE"] = "OWN";
	$p["PAYEENAME"] = "URJA";
	$p["UNIQUEID"] = "UNQ123";
	$p["REMARKS"] = "TEST TRANSACTION";

	/* Credentials Given by ICICI */
	
	$httpUrl ="107.180.78.16";
	
    $request = [
        "CORPID" => $p["CORPID"],
        "USERID" => $p["USERID"],
        "AGGRNAME" =>  $p["AGGRNAME"],
        "AGGRID" => $p["AGGRID"],
        "DEBITACC" => $p["DEBITACC"],
        "CREDITACC" => $p["CREDITACC"],
        "IFSC" => $p["IFSC"],
        "AMOUNT" => $p["AMOUNT"],
        "URN" => $p["URN"],
		"CURRENCY" => $p["CURRENCY"],
		"TXNTYPE" => $p["TXNTYPE"],
		"PAYEENAME" => $p["PAYEENAME"],
		"UNIQUEID" => $p["UNIQUEID"],
		"REMARKS" => $p["REMARKS"],
    ];
    
    $header = [
        'apikey:sACwGe9c3WjBS4SGZ2g65IghV5Fc3KpN',
        'Content-type:text/plain'
    ];
	
	//echo 'JSON :' ;
	//print_r(json_encode($request));
	//echo '<br />';
	
	$url = 'https://apibankingone.icicibank.com/api/Corporate/CIB/v1/Transaction'; 
	
	// openssl_get_publickey($pub_key);
	$pub_key = openssl_pkey_get_public(file_get_contents("rsa_apikey.crt"));
	
	openssl_public_encrypt(json_encode($request),$crypttext,$pub_key, OPENSSL_PKCS1_PADDING);
    $enc = base64_encode($crypttext);
	
	$final_request = json_encode($enc);
	
	//echo 'encrypted JSON with public key :';
	//print_r($final_request); 
	//echo '<br />';
	
	//echo "API URL: ". $url . '<br />';
	
    $url;
	
    $curl = curl_init();
    curl_setopt_array($curl, array(        
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
    curl_close($curl);
    $response;
	$httpcode;
	//echo $httpcode;
	//echo "Error :- ";
	//echo $err;
	//echo 'Response :- ';
    //echo $response; 
	//die();
	
	$fp=fopen("private.key","r");
    $private_key=fread($fp,8192);
    fclose($fp);
	
	$private_key;
	$private_key = openssl_get_privatekey($private_key, "");
	openssl_private_decrypt(base64_decode($response), $resp, $private_key);
	echo $resp;
	exit;
	
?>