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
            $this->selectedStep = 1;
        }

        if (empty($this->selectedPageNumber)) {
            $this->selectedPageNumber = $this->steps[0];
        }

        $this->rows = $this->getData($post);
    }

    public function printTable()
    {

        $this->printPaginator();
        ?>
        <div class="clear"></div>
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
        </div>
        <?php
        $this->printPaginator();
    }

    private function printPaginator()
    {
        ?>
        <div class="clear"></div>
        <div class="pagination">
            <div class="pagination_element_count">Találatok száma: <?php echo $this->numberOfAllRows ?></div>
            <select name="<?php echo $this->tableName ?>-selectedStep">
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
            Előző
                <span class="pagination_page_number">
                    <input type="hidden" name="<?php echo $this->tableName ?>-selectedPageNumber"
                           value="<?php echo $this->selectedPageNumber ?>"/>
                    <span class="pagination_active_page_number"><?php echo $this->selectedPageNumber ?></span>
                </span>
            Következő
        </div>
    <?php
    }


    abstract protected function getData(array $post = null);

    abstract protected function init();

}
