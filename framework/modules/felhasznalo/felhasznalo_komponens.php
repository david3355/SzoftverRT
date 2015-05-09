<?php

/**
 * Class FelhasznaloKomponens
 */
class FelhasznaloKomponens extends Site_Component
{

    private $showFormPage = false;
    private $pm;
    private $felhasznaloDataTable;
    private $actualFelhasznalo = array();

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->felhasznaloDataTable = new Felhasznalo_Lazy_Data_Table();
    }

    function process()
    {
        $actualId = $_POST['id'];
        
        if (!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])) {
            $this->showFormPage = true;
        }

        if (!empty($_POST['back']) || !empty($_POST['save'])) {
            $this->showFormPage = false;
        }

        if (!empty($_POST['save_and_new']) || !empty($_POST['save'])) {
            $felhasznalo_adatok = array(
                'nev' => $_POST['nev'],
                'email' => $_POST['email'],
                'jelszo' => $_POST['jelszo'],
                'jog' => $_POST['jog'],
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            );
            
            $editedFelhasznalo = $_POST['azon'];
            
            if(!empty($editedFelhasznalo)){
                $this->pm->getObject($editedFelhasznalo)->setFelhasznaloAdatok($felhasznalo_adatok);
            } else {
                $felhasznalo = $this->pm->createObject('Felhasznalo', $felhasznalo_adatok);
            }
        }
        
        if(!empty($_POST['delete'])){
            $this->pm->getObject($actualId)->delete();
        }
        
        if(!empty($_POST['edit'])){
            $this->actualFelhasznalo = $this->pm->getObject($actualId)->getFelhasznaloAdatok();
        }
        
        if(!empty($_POST['inactive'])){
            $felhasznalo = $this->pm->getObject($actualId);
            $active = $felhasznalo->getFelhasznaloAdatok()[0]['aktiv'] == 0 ? 1 : 0;
            
            $felhasznalo->setFelhasznaloAdatok(array('aktiv' => $active));
        }
        
        $this->felhasznaloDataTable->process($_POST);
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
        <form method="POST">
            <div class="form_box">
                <h1>Felhasználó szerkesztése</h1>
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
                                        <td><span>Azonosító</span></td>
                                        <td><input size="32" readonly="readonly" type="text" name="azon" value="<?php echo $this->actualFelhasznalo[0]['id']?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                                        <td><input class="ugyfel_nev" size="32" type="text" name="nev" value="<?php echo $this->actualFelhasznalo[0]['nev']?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Email<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="text" name="email" value="<?php echo $this->actualFelhasznalo[0]['email']?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Jelszo<span style="color:red">*</span></span></td>
                                        <td><input size="32" <?php if(isset($this->actualFelhasznalo[0])) echo 'readonly="readonly"' ?> type="password" name="jelszo" value="<?php if(isset($this->actualFelhasznalo[0])) echo 'jelszo' ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Jog</span></td>
                                        <td>
                                            <select name="jog">
                                                <option value="0" <?php if($this->actualFelhasznalo[0]['jog'] == 0) echo 'selected'?>>-</option>
                                                <option value="1" <?php if($this->actualFelhasznalo[0]['jog'] == 1) echo 'selected'?>>Admin</option>
                                                <option value="2" <?php if($this->actualFelhasznalo[0]['jog'] == 2) echo 'selected'?>>Felhasználó</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Aktív</span></td>
                                        <td><input type="checkbox" name="aktiv" <?php if($this->actualFelhasznalo[0]['aktiv']) echo 'checked'?> value="<?php echo $this->actualFelhasznalo[0]['aktiv']?>"></td>
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
            <h1>Felhasználók</h1>
        </div>

        <div class="list_upper_box">
            <div class="search">
                <input id="search_field" size="32" type="text" name="search_field" value=""/>
                <input type="submit" name="search_button" value="Keres" class="search_button"/>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új felhasználó</button>
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
        $this->felhasznaloDataTable->printTable();

    }
}