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
				'getExcelSzla'
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
				case 'getExcelSzla':
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: filename="export.xls"');

					$pm = PersistenceManager::getInstance();
					
					$exporter=new PerpetuumERP_Excel_Exporter();
					$exporter->beginExport();
					$exporter->addRows($pm->select('Szamla')->exeSelect());
					$exporter->endExport();
				break;
				
				default:
					echo "NO_ACCESS";
				break;
			}
		}
	}
?>