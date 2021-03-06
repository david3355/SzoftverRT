<?
	/*ERP API server
	@params:
		-$_REQUEST
			-class: betöltendő osztály teljes neve
				-method: futtatandó metódus neve
					-params: az osztály/metódus paraméterei
	
	@return: JSON array
		-success: true|false
		-data: array, a kért adatok
		-msg: hibakódok*/
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/loader/loader_main.php");
	
	$apps=array("2e6766863522c270667cd91952db15f5" => "Ügyfélkapu API");
	
	try
	{
		$params=$_REQUEST;
		$class=$params['class'];
		$method=$params['method'];
		$auth_code=$params['auth_code'];
		
		//Hozzáférési kód ellenőrzése
		if(!isset($apps[$auth_code]))
		{
			throw new Exception("NO_ACCESS");
		}
		
		//ellenőrzi, h létezik-e az adott osztály, autoload=true -> ha még nem betöltött, de létezik is true
		if(class_exists($class, $autoload=true)===false)
		{
			throw new Exception("INVALID_CLASS");
		}
		
		$class=new $class($params);
		
		//ellenőrzi, h létezik-e az adott metódus
		if(method_exists($class, $method)===false)
		{
			throw new Exception("INVALID_METHOD");
		}
		
		//lefuttatja a metódust
		$result['data']=$class->$method();
		$result['success']=true;
		
	}catch(Exception $e)
	{
		$result=array();
		$result['success']=false;
		$result['msg']=$e->getMessage();
	}
	
	echo json_encode($result);
	exit();
?>