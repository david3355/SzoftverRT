<?php

class Igenyles_Lazy_Data_Table extends Abstract_Lazy_Data_Table {

    protected function getData(array $post = NULL)
    {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ugyfelkapu.fejlesztesgyak2015.info/api.php?module=erp_api&function=getIgenyles");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		//execute the request
		$result = curl_exec($ch);
		$adatsor=json_decode($result, true);
		
		return $adatsor;
    }

    protected function init()
    {
        //$this->pm = PersistenceManager::getInstance();

        $this->tableName = 'igenyles-datatable';

        $this->dataColumns = array(
            "ugyfel_azon" => array(
                'name' => 'Ügyfél azonosító',
                'sortable' => false
            ),
            "id" => array(
                'name' => 'Igénylés azonosító',
                'sortable' => false
            ),
            "statusz" => array(
                'name' => 'Státusz',
                'sortable' => false
            ),
            "letrehozas_datuma" => array(
                'name' => 'Dátum',
                'sortable' => false
            ),
            "sablon_azon" => array(
                'name' => 'Sablon',
                'sortable' => false
            )
        );
        $this->operationColumns = array(
            array(
                'name' => 'edit',
                'text' => 'Szerkesztés'
            )
        );
    }
}