<?

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once "exporter.php";

$res=array(
  array('nev'=>'Elek','Cim'=>'Debrecen')
  ,array('nev'=>'Pista','Cim'=>'Budapest')
);
/*
             
------------ Innentől kezdődik az export -------------------

*/
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: filename="teszt.xls"');

$exporter=new PerpetuumERP_Excel_Exporter();

$exporter->beginExport();

$exporter->addRows($res);

$exporter->endExport();
