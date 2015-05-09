<?

// TODO állapot exception-ök

class PerpetuumERP_Excel_Exporter{
  const StandBy="standby";
  const Opened="opened";
  const Closed="closed";
  
  private $row_number=0;
  private $output_encoding,$input_encoding;
  private $state=self::StandBy;
  private $column_types=array();
  
  const Column_Type_String="str";
  const Column_Type_Number="num";
  
  function __construct($input_encoding="UTF-8",$output_encoding="ISO-8859-2"){
    $this->input_encoding=$input_encoding;
    $this->output_encoding=$output_encoding;
  }
  
  final protected function xlsBOF() {
    return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
  }
  
  final protected function xlsEOF() {
    return pack("ss", 0x0A, 0x00);
  }
  
  final protected function xlsWriteNumber($Row, $Col, $Value) {
    $ret=pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    $ret.=pack("d", $Value);
    return $ret;
  }
  
  final protected function xlsWriteLabel($Row, $Col, $Value ) {
    $newvalue=mb_convert_encoding($Value, $this->output_encoding,$this->input_encoding);
    $L = strlen($newvalue);
    $ret=pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    $ret.=$newvalue;
    return $ret;
  }
   
  protected function writeOutput($data){
    echo $data;
  } 
  
  final function getState(){
    return $this->state;
  }
  
  /**
  $column_types=array (column_name=>{self::Column_Type_String|self::Column_Type_Number})
  */
  final function setDefaultColumnTypes(array $column_types=null){
    if (isset($column_types)) $this->column_types=$column_types;
  }
   
  /**
  $column_types=array (column_name=>{self::Column_Type_String|self::Column_Type_Number})
  if $column_types===null, then the setting given in setDefaultColumnTypes() will be used.
  */
  final function addRow($row,array $column_types=null){
    if ($this->getState()!==self::Opened) return false;
    
    if (!isset($column_types)) $column_types=$this->column_types;
  
    $row_data="";
    $cell_number=0;
  	foreach ($row as $key=>$value) {
  	  $row_data.=((!isset($column_types[$key]) and is_numeric($value)) or $column_types[$key]===self::Column_Type_Number)
                 ? $this->xlsWriteNumber($this->row_number,$cell_number,$value)
                 : $this->xlsWriteLabel($this->row_number,$cell_number,$value);   
      $cell_number++;
    }
 	  $this->row_number++;
 	  
 	  $this->writeOutput($row_data);
  }
  
  final function addRows(array $array_of_rows=null,array $column_types=null){
    if ($this->getState()!==self::Opened) return false;
    
  	foreach ($array_of_rows as $row) {
   	  $this->addRow($row,$column_types);
    }
  }
  
  final function beginExport(){
    if ($this->getState()!==self::StandBy) return false;
    $this->state=self::Opened;
    
    $this->writeOutput($this->xlsBOF());
    
    return true;
  }
  
  final function endExport(){
    if ($this->getState()!==self::Opened) return false;

    $this->state=self::Closed;
    
    $this->writeOutput($this->xlsEOF());
    
    return true;
  }
}
