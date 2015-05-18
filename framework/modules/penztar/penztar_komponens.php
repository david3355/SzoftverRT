<?php

/**
 * Class PenztarKomponens
 */
class PenztarKomponens extends Input_Memo_Site_Component
{

    private $showFormPage = false;
    private $showPenztarTetelsPage = false;
    private $showPenztarTetelsFormPage = false;
    private $pm;
    private $penztarDataTable;
    private $penztarTetelDataTable;
    private $penztarData = null;
    private $actualPenztarName;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->penztarDataTable = new Penztar_Lazy_Data_Table();
        $this->penztarTetelDataTable = new Penztar_Tetel_Lazy_Data_Table();
    }

    function subProcess()
    {
        if(isset($_POST['storno'])){
            $actualId = $_POST['id'];
            
            $this->pm->getObject($actualId)->doStorno();
            
            $penztarTableAction = false;
            $actualId = $_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'];
        } else {
            $penztarTableAction = true;
            $actualId = $_POST['id'];
        }

        if(!isset($actualId)){
            $penztarTableAction = false;
            $actualId = $_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'];
        }
        
        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }

        //törlés
        if(isset($_POST['delete']))
        {
            $res = $this->pm->getObject($actualId)->delete();
            if($res > 1) $msg = "Törölve";      // Az objektumnak minimum 2 helyről kell törlődnie
            else $msg = "Törlés sikertelen!";
            //echo"<script>alert('".$msg."')</script>";
            $_SESSION['msg'] = $msg;
            unset($_SESSION['penztar_edit_id']);
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
                    /*$msg = implode(', ', $result);
                    echo "<script>alert('Edit error: " . $msg . "')</script>";*/
                    $_SESSION['msg'] = $result;
                    $this->showFormPage = true;
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
                    /*$msg = implode(', ', $penztar);
                    echo "<script>alert('Create error: " . $msg . "')</script>";*/
                    $_SESSION['msg'] = $penztar;
                    $this->showFormPage = true;
                }
            }
        }
        
        if(!$penztarTableAction || isset($_POST['penztarTetels'])){
            if(isset($_POST['new_penztar_tetel'])){
                $this->showPenztarTetelsFormPage = true;
            }  

            if(isset($_POST['save_and_new_pentartetel'])){
                $this->showPenztarTetelsFormPage = true;
            }

            if(isset($_POST['save_pentartetel']) || isset($_POST['save_and_new_pentartetel'])){
                $penztartetel_adatok = array(
                    'penztar_fk' => $actualId,
                    'sorszam' => 1,
                    'megnevezes' => $_POST['megnevezes'],
                    'osszeg' => $_POST['osszeg'],
                    'datum' => date("Y-m-d"),
                    'storno' => 0
                );

                $penztartetel = $this->pm->createObject('PenztarTetel', $penztartetel_adatok);
                // Hibakód visszaadása a felületre, ha a $penztar egy array, majd ide kell valami elegáns:
                if(is_array($penztartetel)) {
                    /*$msg = implode(', ', $penztar);
                    echo "<script>alert('Create error: " . $msg . "')</script>";*/
                    $_SESSION['msg'] = $penztartetel;
                    $this->showPenztarTetelsFormPage = true;
                } else {
                    if(isset($_POST['save_pentartetel'])){
                        $_POST['id'] = $actualId;
                        $this->penztarTetelDataTable->process($_POST);
                        $this->showPenztarTetelsPage = true;
                        $this->actualPenztarName = $this->penztarData=$this->pm->getObject($actualId)->getPenztarAdatok()['megnevezes'];
                    }                 
                }
            }

            if(isset($_POST['penztarTetels']) || !isset($_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'])){
                $_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'] = $actualId;
            }

            if(!isset($_POST['new_penztar_tetel']) && !isset($_POST['save_and_new_pentartetel']) && !isset($_POST['back_penztartetel']) && !isset($_POST['save_pentartetel'])){
                if(isset($_POST['penztarTetels']) || isset($_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'])){
                    $_POST['id'] = $actualId;
                    $this->penztarTetelDataTable->process($_POST);
                    $this->showPenztarTetelsPage = true;
                    $this->actualPenztarName = $this->penztarData=$this->pm->getObject($actualId)->getPenztarAdatok()['megnevezes'];          
                } 
            }

            if(isset($_POST['sub-back']) ){
                $this->showFormPage = false;
                $this->showPenztarTetelsPage = false;
                $this->showPenztarTetelsFormPage = false;
                unset($_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID']);
            }

            if(isset($_POST['back_penztartetel'])){
                $_POST['id'] = $actualId;
                $this->penztarTetelDataTable->process($_POST);
                $this->showPenztarTetelsPage = true;
                $this->actualPenztarName = $this->penztarData=$this->pm->getObject($actualId)->getPenztarAdatok()['megnevezes'];
            }
        }
        
        if(!isset($_SESSION['PENZTAR_TETEL_FOR_PENZTAR_ID'])){
            $this->penztarDataTable->process($_POST);
        }
    }

    function show()
    {
        if ($this->showFormPage) {
            $this->showForm();
        } else if($this->showPenztarTetelsPage){
            $this->showPenztarTetelsList();
        } else if($this->showPenztarTetelsFormPage){
            $this->showPenztarTetelsForm();
        } else {
            $this->showList();
        }
    }

    private function showForm()
    {
        Bonus::showTheMagic();
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
        Bonus::showTheMagic();
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
    
    private function showPenztarTetelsForm()
    {
        Bonus::showTheMagic();
        ?>
        <form action="" method="POST">
            <div class="form_box">
                <h1>Új pénztártétel</h1>
                <input type="submit" name="save_pentartetel" value="Mentés" class="save_button">
                <input type="submit" name="save_and_new_pentartetel" value="Mentés és új" class="save_and_new_button">
                <input type="submit" name="back_penztartetel" value="Vissza" class="back_button">
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
                                        <td><input size="32" type="text" name="megnevezes" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Összeg<span style="color:red">*</span></span></td>
                                        <td><input type="number" name="osszeg" value=""></td>
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
    
    private function showPenztarTetelsList()
    {
        ?>
        <div class="header">
            <h1><?php echo $this->actualPenztarName ?> pénztár tételek</h1>
        </div>
        <form action="" method="POST">
            <input type="submit" name="sub-back" value="Vissza" class="back_button">
        </form>
        <div class="list_upper_box">
            <div class="search">
                <form action="" method="POST">
                    <input id="search_field" size="32" type="text" name="search_tetel_field" value="<?php echo $this->getInputValues()['search_tetel_field']?>"/>
                    <input type="submit" name="search_tetel_button" value="Keresés" class="search_button"/>
                </form>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new_penztar_tetel" value="new_penztar_tetel">Új pénztártétel</button>
                </form>
            </div>
        </div>

        <div class="clear"></div>
        <?php
        $this->penztarTetelDataTable->printTable();
        
    }
    
}