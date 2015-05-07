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

        $this->printPaginator();
        ?>
        <div class="clear"></div>
        <form id="<?php echo $this->tableName ?>-form" method="post">
        <div class="itemlist">
            <table cellspacing="0" cellpadding="0" class="listtable">
                <thead>
                <tr>
                    <?php
                    foreach ($this->dataColumns as $dataColumns) {
                        echo '<th>' . $dataColumns['name'] . '</th>';
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
        <?php $this->printPaginator(); ?>
        </form>
        <?php
    }

    private function printPaginator()
    {
        ?>
        <div class="clear"></div>
        <div class="pagination">
            <div class="pagination_element_count">Találatok száma: <?php echo $this->numberOfAllRows ?></div>
            <select onchange="submitForm();" name="<?php echo $this->tableName ?>-selectedStep">
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
            <a onclick="setSelectedPageInputAndSubmit(<?php echo $this->selectedPageNumber-1 ?>)">Előző</a>
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
            <a onclick="setSelectedPageInputAndSubmit(<?php echo $this->selectedPageNumber+1 ?>)">Következő</a>
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
