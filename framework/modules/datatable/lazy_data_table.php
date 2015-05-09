<?php

abstract class Abstract_Lazy_Data_Table
{

    protected $tableName;
    protected $selectedPageNumber;
    protected $steps;
    protected $selectedStep;
    protected $numberOfAllRows;
    protected $dataColumns;
    protected $operationColumns;
    protected $rows;
    protected $selectedSortColumn;

    final public function __construct()
    {
        $this->steps = array(50, 100, 500);

        $this->init();
    }

    public function process(array $post)
    {
        if (!empty($post[$this->tableName . '-selectedStep'])) {
            $this->selectedStep = $post[$this->tableName . '-selectedStep'];
        }
        if (!empty($post[$this->tableName . '-selectedPageNumber'])) {
            $this->selectedPageNumber = $post[$this->tableName . '-selectedPageNumber'];
        }
        if(!empty($post[$this->tableName.'-selectedSortColumn']) && !empty($post[$this->tableName.'-selectedSortDest'])){
            $this->selectedSortColumn = array('column' => $post[$this->tableName.'-selectedSortColumn'], 'dest' => $post[$this->tableName.'-selectedSortDest']);
        }
        
        if (empty($this->selectedStep)) {
            $this->selectedStep = $this->steps[0];
        }
        
        if (empty($this->selectedPageNumber)) {
            $this->selectedPageNumber = 1;
        }
        
        $this->rows = $this->getData($post);
    }

    public function printTable()
    {

        $this->printPaginator(0);
        ?>
        <div class="clear"></div>
        <form id="<?php echo $this->tableName ?>-form" method="post">
        <div class="itemlist">
            <input type="hidden" name="<?php echo $this->tableName ?>-selectedSortColumn" id="table-sort-column-input" value="<?php echo $this->selectedSortColumn['column'] ?>"/>
            <input type="hidden" name="<?php echo $this->tableName ?>-selectedSortDest" id="table-sort-dest-input" value="<?php echo $this->selectedSortColumn['dest'] ?>"/>
            <table cellspacing="0" cellpadding="0" class="listtable">
                <thead>
                <tr>
                    <?php
                    foreach ($this->dataColumns as $key => $dataColumns) {
                        if($dataColumns['sortable']){
                            echo '<th><a ';
                            $clearKey = str_replace(' ', '', $key);
                            if($this->selectedSortColumn['column'] == $clearKey){
                                if($this->selectedSortColumn['dest'] == 'desc'){
                                    echo ' class="tableheader_sort_desc" ';
                                } else {
                                    echo ' class="tableheader_sort_asc" ';
                                }
                            }       
                            echo 'onclick="setSortColumn(\''.$key.'\');submitForm();" style="color: #0b55c4;">' . $dataColumns['name'] . '</a></th>';
                        } else {
                            echo '<th>' . $dataColumns['name'] . '</th>';
                        }
                    }

                    if (count($this->operationColumns) > 0) {
                        echo '<th colspan="' . count($this->operationColumns) . '">Műveletek</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($this->rows as $row) {
                    echo '<tr>';
                    foreach ($this->dataColumns as $key => $dataColumns) {
                        echo '<td>' . $row[$key] . '</td>';
                    }

                    foreach ($this->operationColumns as $operationCoulomb) {
                        echo '<td>';
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" value="' . $row['id'] . '" name="id">';
                        echo '<button type="submit" name="' . $operationCoulomb['name'] . '" value="' . $operationCoulomb['name'] . '">' . $operationCoulomb['text'] . '</button>';
                        echo '</form>';
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <input id="selectedPageNumber-input" type="hidden" name="<?php echo $this->tableName ?>-selectedPageNumber"
                value="<?php echo $this->selectedPageNumber ?>"/>
        </div>
        <?php $this->printPaginator(1); ?>
        </form>
        <?php
    }

    private function printPaginator($isLast)
    {
        ?>
        <div class="clear"></div>
        <div class="pagination">
            <div class="pagination_element_count">Találatok száma: <?php echo $this->numberOfAllRows ?></div>
            <select class="step-select-<?php echo $isLast?>" onchange="setStepSelects(<?php echo $isLast?>);submitForm();" name="<?php echo $this->tableName ?>-selectedStep">
                <?php
                foreach ($this->steps as $step) {
                    if ($step == $this->selectedStep) {
                        echo '<option value="' . $step . '" selected="">' . $step . '</option>';
                    } else {
                        echo '<option value="' . $step . '">' . $step . '</option>';
                    }
                }
                ?>
            </select>
            <?php 
                if($this->selectedPageNumber == 1){
                    echo 'Előző';
                } else {
                    echo '<a onclick="setSelectedPageInputAndSubmit('.($this->selectedPageNumber-1).')">Előző</a>';
                }
            ?>
                <span class="pagination_page_number">
                    <?php
                        for ($x = 1; $x < $this->selectedPageNumber; $x++) {
                            echo "<a onclick='setSelectedPageInputAndSubmit($x)'>$x </a>";
                        } 
                    ?>
                    <span class="pagination_active_page_number"><?php echo $this->selectedPageNumber ?></span>
                    <?php
                        for ($x = $this->selectedPageNumber+1; $x <= $this->getLastPage(); $x++) {
                            echo "<a onclick='setSelectedPageInputAndSubmit($x)'>$x </a>";
                        } 
                    ?>
                </span>
            <?php 
                if($this->numberOfAllRows == 0){
                    echo 'Következő';
                } else {
                    if($this->getLastPage() != 0 && $this->selectedPageNumber == $this->getLastPage()){
                        echo 'Következő';
                    } else {
                        echo '<a onclick="setSelectedPageInputAndSubmit('.($this->selectedPageNumber+1).')">Következő</a>';
                    }
                }
            ?>           
        </div>
        <script>
            function setSelectedPageInputAndSubmit(targetPage){
                console.log(targetPage);
                $('#selectedPageNumber-input').val(targetPage);
                submitForm();
            }
            function submitForm(){
                $('form#<?php echo $this->tableName ?>-form').submit();
            }
            function setStepSelects(top){
                if(top == 0){
                    $(".step-select-1").val($(".step-select-0").val());
                } else {
                    $(".step-select-0").val($(".step-select-1").val());
                }
                $('#selectedPageNumber-input').val(1);
            }
            function setSortColumn(column){
                if($('#table-sort-column-input').val() == column){
                    if($('#table-sort-dest-input').val() == 'desc'){
                        $('#table-sort-dest-input').val('asc');
                    } else {
                        $('#table-sort-dest-input').val('desc');
                    }
                } else {
                    $('#table-sort-dest-input').val('desc');
                }
                $('#table-sort-column-input').val(column);
            }
        </script>
    <?php
    }

    private function getLastPage()
    {
        if(!empty($this->numberOfAllRows)){
            return round(($this->numberOfAllRows/$this->selectedStep)+0.4);
        }
        return 0;
    }

    abstract protected function getData(array $post = null);

    abstract protected function init();

}
