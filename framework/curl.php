<?
	//initialize and setup the curl handler
	//$params=array();
	$params['class']="Erpugyfel";
	$params['method']="getAllUgyfel";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost:801/SzoftverRT/framework/erp_api_server/erp_api_server.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, count($params));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

	//execute the request
	echo $result = curl_exec($ch);

?>