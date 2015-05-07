<?
	//initialize and setup the curl handler
	//$params=array();
	$params['class']="Erpugyfel";
	$params['method']="getAllUgyfel";
	$params['auth_code']="2e6766863522c270667cd91952db15f5";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://erp.fejlesztesgyak2015.info/erp_api_server/erp_api_server.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, count($params));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

	//execute the request
	$result = curl_exec($ch);
	
	/*SEGíTSÉG AZ ÜGYFÉLKAPUNAK:
		-json_decode(array, true)
		-ezután lehet hivatkozni a tömbre
			-eredmény: 'data'
			-hányadik
			-melyik attributum*/
		
	$res=json_decode($result, true);
	echo $res['data'][0]['nev'];
?>