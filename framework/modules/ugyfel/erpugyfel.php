<?
	//Az Ügyfélkapu részére továbbítja az ERP összes ügyfél minden attributumát
	class Erpugyfel
	{
		//array
		private $params;
		
		public function __construct($params)
		{
			$this->params=$params;
		}
		
		public function getAllUgyfel()
		{
			$pm = PersistenceManager::getInstance();
			return $pm->select('Ugyfel')->get();
		}
	}
?>