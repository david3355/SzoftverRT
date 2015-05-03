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
			$uf=new Ugyfel();
			return $uf->select();
		}
	}
?>