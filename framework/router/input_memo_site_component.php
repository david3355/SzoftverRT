<?php

abstract class Input_Memo_Site_Component extends Site_Component{
    
    private $inputIds = array();
    private $inputValues = array();
    private $childClassName;

    public function process() {
        $this->childClassName = get_class($this);
        $reflector = new ReflectionClass($this->childClassName);
        $cfn = dirname($reflector->getFileName())."/input_memo_config.php";
        
        $this->inputIds=require_once $cfn;
        if(!isset($this->inputIds)){
            throw new Exception('Using Input_Memo_Site_Component without required config file: "'.$cfn.'"');
        }
        
        $this->setSessionFromPost();
        $this->getSessionToPost();
        $this->subProcess();       
    }
    
    private function setSessionFromPost(){
        foreach($this->inputIds as $inputId){
            $value = $_POST[$inputId];
            if(isset($value)){
                $_SESSION[$this->childClassName.$inputId] = $value;
            } 
        }
    }
    
    private function getSessionToPost(){
        foreach($this->inputIds as $inputId){
            $value = $_SESSION[$this->childClassName.$inputId];
            $_POST[$inputId] = $value;
            $this->inputValues[$inputId] = $value;
        }
    }
    
    abstract function subProcess();

    protected function getInputValues(){
        return $this->inputValues;
    }
}

