<?php

/**
 * Class SzamlatombKomponens
 */
class SzamlatombKomponens extends Site_Component
{

    private $showFormPage = false;
    private $pm;
    private $szamlatombDataTable;
    private $actualSzamlatomb = array();

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
	$this->szamlatombDataTable = new Szamlatomb_Lazy_Data_Table();
    }

    function process()
    {
        $actualId = $_POST['id'];
        
        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }

        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showFormPage = false;
        }
		
	if (!empty($_POST['save_and_new']) || !empty($_POST['save'])) {
            $szamlatomb_adatok = array(
                'megnevezes' => $_POST['megnevezes'],
                'szamla_elotag' => $_POST['szamla_elotag'],
                'szamla_aktual_szam' => $_POST['szamla_aktual_szam']
            );
            
            if(!empty($_POST['id'])){
                $result = $this->pm->getObject($actualId)->setSzamlatombAdatok($szamlatomb_adatok);
            } else {
                $result = $this->pm->createObject('Szamlatomb', $szamlatomb_adatok);
            }
            
            if(is_array($result)) {
                $msg = implode(', ', $result);
                echo "<script>alert('Edit error: " . $msg . "')</script>";
                $this->showFormPage = true;
            }
        }
        
        if(!empty($_POST['delete'])){
            $this->pm->getObject($actualId)->delete();
        }
        
        if(!empty($_POST['edit'])){
            $this->actualSzamlatomb = $this->pm->getObject($actualId)->getSzamlatombAdatok();
        }
        
        if(!empty($_POST['close'])){
            $this->pm->getObject($actualId)->setLezaras(true);
        }
        
        $this->szamlatombDataTable->process($_POST);
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
                <h1>Számlatömb szerkesztése</h1>
                <input type="submit" name="save" value="Mentés" class="save_button">
                <input type="submit" name="save_and_new" value="Mentés és új" class="save_and_new_button">
                <input type="submit" name="back" value="Vissza" class="back_button">
                <br/>
                <br/>
                <div class="form_szurke_doboz">
                    <input type="hidden" name="id" value="<?php echo $this->actualSzamlatomb['id'] ?>">
                    <table class="formtable">
                        <tbody>
                        <tr>
                            <td valign="top">
                                <table>
                                    <tbody>                                   
                                    <tr>
                                        <td><span class="mandatory">Megnevezés<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="text" name="megnevezes" value="<?php echo $this->actualSzamlatomb['megnevezes'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Előtag</span></td>
                                        <td><input size="32" type="text" name="szamla_elotag" value="<?php echo $this->actualSzamlatomb['szamla_elotag'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Kezdőszám</span></td>
                                        <td><input size="32" <?php if(!empty($_POST['edit'])) {echo 'readonly';}   ?> type="number" name="szamla_aktual_szam" value="<?php echo $this->actualSzamlatomb['szamla_aktual_szam'] ?>"></td>
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
            <h1>Számlatömbök</h1>
        </div>

        <div class="list_upper_box">
            <div class="search">
                <form action="" method="POST">
                    <input id="search_field" size="32" type="text" name="search_field" value=""/>
                    <input type="submit" name="search_button" value="Keres" class="search_button"/>
                </form>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új számlatömb</button>
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
        $this->szamlatombDataTable->printTable();
    }
}