<?php

/**
 * Class PenztarKomponens
 */
class PenztarKomponens extends Input_Memo_Site_Component
{

    private $showFormPage = false;
    private $pm;
    private $penztarDataTable;
    private $penztarData = null;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
		$this->penztarDataTable = new Penztar_Lazy_Data_Table();
    }

    function subProcess()
    {
        $actualId = $_POST['id'];

        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }

		//törlés
		if(isset($_POST['delete']))
		{
           $res = $this->pm->getObject($actualId)->delete();
            if($res > 1) $msg = "Törölve";      // Az objektumnak minimum 2 helyről kell törlődnie
            else $msg = "Törlés sikertelen!";
            echo"<script>alert('".$msg."')</script>";
        }
		
		//módosításhoz lekérdezzük az aktuális objektum adatait
        if(!empty($_POST['edit']))
        {
            $this->penztarData=$this->pm->getObject($actualId)->getPenztarAdatok();
            $_SESSION['penztar_edit_id']=$actualId;
        }
		
        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showFormPage = false;
        }
		
		if (!empty($_POST['save_and_new']) || !empty($_POST['save']))
		{
            $p_adatok = array(
                'megnevezes' => $_POST['megnevezes']
            );

			// módosítás vagy létrehozás
			if(isset($_SESSION['penztar_edit_id']))    // Edit
            {
                $penztar=$this->pm->getObject($_SESSION['penztar_edit_id']);
                $result = $penztar->setPenztarAdatok($p_adatok);
                if(is_array($result)) {
                    $msg = implode(', ', $result);
                    echo "<script>alert('Edit error: " . $msg . "')</script>";
                }
                else
                {
                   unset($_SESSION['penztar_edit_id']);
                }
            }
            else    // Create
            {
                $penztar = $this->pm->createObject('Penztar', $p_adatok);
                // Hibakód visszaadása a felületre, ha a $penztar egy array, majd ide kell valami elegáns:
                if(is_array($penztar)) {
                    $msg = implode(', ', $penztar);
                    echo "<script>alert('Create error: " . $msg . "')</script>";
                }
            }
        }

        $this->penztarDataTable->process($_POST);
    }

    function show()
    {
        if ($this->showFormPage) {
            $this->showForm();
        } else {
            $this->showList();
        }
    }

    private function showForm()
    {
        ?>
        <form action="" method="POST">
            <div class="form_box">
                <h1>Pénztár szerkesztése</h1>
                <input type="submit" name="save" value="Mentés" class="save_button">
                <input type="submit" name="save_and_new" value="Mentés és új" class="save_and_new_button">
                <input type="submit" name="back" value="Vissza" class="back_button">
                <br/>
                <br/>

                <div class="form_szurke_doboz">
                    <table class="formtable">
                        <tbody>
                        <tr>
                            <td valign="top">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><span class="mandatory">Megnevezés<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="text" name="megnevezes" value="<?php if(!is_null($this->penztarData)) echo $this->penztarData['megnevezes'];   ?>"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    <?php
    }

    private function showList()
    {
        ?>
        <div class="header">
            <h1>Pénztárak</h1>
        </div>

        <div class="list_upper_box">
            <div class="search">
                <form action="" method="POST">
                    <input id="search_field" size="32" type="text" name="search_field" value="<?php echo $this->getInputValues()['search_field']?>"/>
                    <input type="submit" name="search_button" value="Keres" class="search_button"/>
                </form>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új pénztár</button>
                </form>
            </div>
            <div class="filtersbox">
                <a href="#" title="Szűrők frissítése">
                    <div class="filtersbox_refresh_icon"></div>
                </a>
            </div>
        </div>

        <div class="clear"></div>
        <?php
        $this->penztarDataTable->printTable();




    }
}