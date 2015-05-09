<?
	require_once "exporter.php";

	class Excel_API extends API_Module
	{

		/**
		 * return array of string
		 * Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
		 * A függvény nevét a ?module=függvényneve url paraméterben kell megadni.
		 */
		function getSupportedFunctions()
		{
			return [
				'getExcel'
			];
		}

		/**
		 * $function=$_GET['function']
		 * $params=$_GET
		 * $data=$_POST
		 *
		 * A http választ a kimenetre kell írni, és headert beállítani
		 * A $function elfogadott értékeit a getSupportedFunctions metódusban kell visszaadni.
		 */
		function handleRequest($function, array $params, array $data = null)
		{
			switch($function){
				case 'getExcel':
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: filename="export.xls"');

					$exporter=new PerpetuumERP_Excel_Exporter();

					$exporter->beginExport();

					$exporter->addRows($params);

					$exporter->endExport();
				break;
				
				default:
					echo "NO_ACCESS";
				break;
			}
		}
	}
?>