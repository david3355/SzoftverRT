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

        //módosítás
        if(!empty($_POST['edit']))
        {
            $felh=$this->pm->getObject($_POST['id']);
            $this->actualFelhasznalo=$felh->getFelhasznaloAdatok();
            $_SESSION['felhasznalo_edit_id']=$_POST['id'];
        }

        if (!empty($_POST['save_and_new']) || !empty($_POST['save'])) {
            $felhasznalo_adatok = array(
                'nev' => $_POST['nev'],
                'email' => $_POST['email'],
                'jelszo' => $_POST['jelszo'],
                'jog' => $_POST['jog'],
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            );

            if(isset($_SESSION['felhasznalo_edit_id']))
            {
                $felh=$this->pm->getObject($_SESSION['felhasznalo_edit_id']);
                unset($felhasznalo_adatok['nev']);
                unset($felhasznalo_adatok['jelszo']);
                $result = $felh->setFelhasznaloAdatok($felhasznalo_adatok);
                if(is_array($result)) {
                    $msg = implode(', ', $result);
                    echo "<script>alert('Edit error: " . $msg . "')</script>";
                }
                else
                {
                    unset($_SESSION['felhasznalo_edit_id']);
                }
            }
            else
            {
                $felh = $this->pm->createObject('Felhasznalo', $felhasznalo_adatok);
                // Hibakód visszaadása a felületre, ha az $felh egy array, majd ide kell valami elegáns:
                if(is_array($felh)) {
                    $msg = implode(', ', $felh);
                    echo "<script>alert('Create error: " . $msg . "')</script>";
                }
            }
        }
        
        if(!empty($_POST['delete'])){
            $this->pm->getObject($actualId)->delete();
        }

        
        if(!empty($_POST['inactive'])){
            $felhasznalo = $this->pm->getObject($actualId);
            $active = $felhasznalo->getFelhasznaloAdatok()['aktiv'] == 0 ? 1 : 0;
            $felhasznalo->setActive($active);
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
                                        <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                                        <td><input class="ugyfel_nev" size="32" type="text" name="nev" <?php if(!empty($_POST['edit'])) echo 'readonly'; ?> value="<?php if(!empty($_POST['edit'])) echo $this->actualFelhasznalo['nev']; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Email<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="text" name="email" value="<?php if(!empty($_POST['edit'])) echo $this->actualFelhasznalo['email'];?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Jelszo<span style="color:red">*</span></span></td>
                                        <td><input size="32" <?php if(!empty($_POST['edit'])) {echo 'readonly';}   ?> type="password" name="jelszo" value="<?php if(!empty($_POST['edit'])) {echo 'jelszo';}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Jog</span></td>
                                        <td>
                                            <select name="jog">
                                                <option value="0" <?php if($this->actualFelhasznalo['jog'] == 0) echo 'selected'?>>-</option>
                                                <option value="1" <?php if($this->actualFelhasznalo['jog'] == 1) echo 'selected'?>>Admin</option>
                                                <option value="2" <?php if($this->actualFelhasznalo['jog'] == 2) echo 'selected'?>>Felhasználó</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Aktív</span></td>
                                        <td><input type="checkbox" name="aktiv" <?php if($this->actualFelhasznalo['aktiv']) echo 'checked'?> value="<?php echo $this->actualFelhasznalo['aktiv']?>"></td>
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