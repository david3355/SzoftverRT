<?php

abstract class Abstract_Lazy_Data_Table {
    
    protected $tableName;
    protected $selectedPageNumber;
    protected $steps;
    protected $selectedStep;
    protected $numberOfAllRows;
    protected $dataCoulombs;
    protected $operationCoulombs;
    protected $rows;
    
    public function __construct() {
        $this->init();
    }
    
    public function process(array $post){
        
        if(!empty($post[$this->tableName.'-selectedStep'])){
            $this->selectedStep = $post[$this->tableName.'-selectedStep'];
        }       
        if(!empty($post[$this->tableName.'-selectedPageNumber'])){
            $this->selectedPageNumber = $post[$this->tableName.'-selectedPageNumber'];
        }
        
        if(empty($this->selectedStep)){
            $this->selectedStep = 1;
        }
        
        if(empty($this->selectedPageNumber)){
            $this->selectedPageNumber = $this->steps[0];
        }
        
        $this->rows = $this->getData($post);
    }
    
    public function printTable(){
        
        $this->printPaginator();
        ?>
        <div class="clear"></div>
        <div class="itemlist">
            <table cellspacing="0" cellpadding="0" class="listtable">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox">
                    </th>
                    
                    <?php
                        foreach($this->dataCoulombs as $dataCoulombs){
                            echo '<th>'.$dataCoulombs['name'].'</th>';
                        }
                        
                        if (count($this->operationCoulombs) > 0) {
                            echo '<th colspan="'.count($this->operationCoulombs).'">Műveletek</th>';
                        }
                    ?>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($this->rows as $row){
                            echo '<tr>';
                            echo '<td></td>';
                            foreach($this->dataCoulombs as $key => $dataCoulombs){
                                echo '<td>'.$row[$key].'</td>';
                            }
                            
                            foreach($this->operationCoulombs as $operationCoulomb){
                                echo '<td>';
                                echo '<form action="" method="post">';
                                echo    '<input type="hidden" value="'.$row['id'].'" name="id">';
                                echo    '<button type="submit" name="'.$operationCoulomb['name'].'" value="'.$operationCoulomb['name'].'">'.$operationCoulomb['text'].'</button>';
                                echo '</form>';
                                echo '</td>';
                            }
                            echo '</tr>';
                        }
                    ?>                   
                </tbody>
            </table>
        </div>
        <?php
        $this->printPaginator();
    }
    
    private function printPaginator(){        
        ?>
            <div class="clear"></div>
            <div class="pagination">
                <div class="pagination_element_count">Találatok száma: <?php echo $this->numberOfAllRows ?></div>
                <select name="<?php echo $this->tableName ?>-selectedStep">
                    <?php
                        foreach($this->steps as $step){
                            if($step == $this->selectedStep){
                                echo '<option value="'.$step.'" selected="">'.$step.'</option>';
                            } else {
                                echo '<option value="'.$step.'">'.$step.'</option>';
                            }
                        }
                    ?>
                </select>
                Előző
                <span class="pagination_page_number">
                    <input type="hidden" name="<?php echo $this->tableName ?>-selectedPageNumber" value="<?php echo $this->selectedPageNumber ?>"/>
                    <span class="pagination_active_page_number"><?php echo $this->selectedPageNumber ?></span>
                </span>
                Következő
            </div>
        <?php
    }


    abstract protected function getData(array $post);
    abstract protected function init();
    
}

?>