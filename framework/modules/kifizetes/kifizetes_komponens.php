<?php

class KifizetesKomponens extends Input_Memo_Site_Component
{

    private $showFormPage = false;
    private $showGlobalPage = false;
    private $pm;
    private $kifizetesDataTable;
    private $szamlaszams = array();
    private $penztars = array();
    private $actualKifizetes = array();

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->kifizetesDataTable = new Kifizetes_Lazy_Data_Table();
    }

    function subProcess()
    {
        $actualId = $_POST['id'];

        if (!empty($_POST['global'])) {
            $this->showGlobalPage = true;
            $this->showFormPage = false;
        }

        if (!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])) {
            $this->showFormPage = true;
            $this->showGlobalPage = false;
        }

        if (!empty($_POST['back']) || !empty($_POST['save'])) {
            $this->showFormPage = false;
            $this->showGlobalPage = false;
        }

        //módosítás
        if (!empty($_POST['edit'])) {
            $kifizetes = $this->pm->getObject($_POST['id']);
            $this->actualKifizetes = $kifizetes->getSzamlaKifizetesAdatok();
        }

        if (!empty($_POST['save_and_new']) || !empty($_POST['save'])) {
            $kifizetes_adatok = array(
                'kifizetes_datum' => $_POST['kifizetes_datum'],
                'osszeg' => $_POST['osszeg'],
                'szamla_fk' => $this->getSzamlaFkFromSzamlas($this->szamlaszams, $_POST['szamla_sorszam'])
            );

            if (isset($actualId)) {
                $kifizetes = $this->pm->getObject($actualId);
                $result = $kifizetes->setSzamlaKifizetesAdatok($kifizetes_adatok);
                if (is_array($result)) {
                    /*$msg = implode(', ', $result);
                    echo "<script>alert('Edit error: " . $msg . "')</script>";*/
                    $_SESSION['msg'] = $result;
                    $this->showFormPage = true;
                }
            } else {
                $kifizetes = $this->pm->createObject('Kifizetes', $kifizetes_adatok);
                if (is_array($kifizetes)) {
                    /*$msg = implode(', ', $felh);
                    echo "<script>alert('Create error: " . $msg . "')</script>";*/
                    $_SESSION['msg'] = $kifizetes;
                    $this->showFormPage = true;
                }
            }
        }

        //törlés
        if (isset($_POST['delete'])) {
            $kifizetes = new Kifizetes($_POST['id']);
            $msg = $kifizetes->delete();
            echo "<script>alert('" . $msg . "')</script>";
        }

        if (!empty($_POST['new']) || !empty($_POST['edit'])) {
            $this->szamlaszams = $this->pm->select('Szamla', ['id', 'sorszam_elotag', 'sorszam_szam'])->exeSelect();
            $this->penztars = $this->pm->select('Penztar', ['id', 'megnevezes'])->exeSelect();
        }

        $this->kifizetesDataTable->process($_POST);
    }

    private function getSzamlaFkFromSzamlas($array, $value)
    {
        foreach ($array as $a) {
            if ($a['sorszam_elotag'] . $a['sorszam_szam'] == $value) {
                return $a['id'];
            }
        }
    }

    function show()
    {
        if ($this->showGlobalPage) {
            $this->showGlobal();
        } else {
            if ($this->showFormPage) {
                $this->showForm();
            } else {
                $this->showList();
            }
        }
    }


    private function showForm()
    {
        ?>
        <form action="" method="POST">
            <div class="form_box">
                <h1>Kifizetés szerkesztése</h1>
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
                                        <td><span class="mandatory">Kifizetés dátum<span
                                                    style="color:red">*</span></span></td>
                                        <td><input id="kifizetes_datuma" size="32" type="text" name="kifizetes_datum"
                                                   value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span>Összeg</span></td>
                                        <td><input type="number" name="osszeg" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span>Számla sorszám</span></td>
                                        <td><input id="szamla_sorszam" size="32" type="text" name="szamla_sorszam"
                                                   value="<?php if (isset($this->actualKifizetes)) echo $this->pm->getObject($this->actualKifizetes['szamla_fk'])->getSzamlaAdatok()['sorszam_elotag'] . $this->pm->getObject($this->actualKifizetes['szamla_fk'])->getSzamlaAdatok()['sorszam_szam']; ?>">
                                        </td>
                                    </tr>
                                    <tr <?php if (isset($this->actualKifizetes)) echo 'style="display:none;"'; ?>>
                                        <td><span>Pénztár</span></td>
                                        <td>
                                            <select name="penztar">
                                                <?php
                                                var_dump($this->penztars);
                                                foreach ($this->penztars as $pentar) {
                                                    echo '<option value="' . $pentar['id'] . '">' . $pentar['megnevezes'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
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
        <script>
            $(function () {
                var availableTags = [
                    <?php 
                        foreach($this->szamlaszams as $szamlaszam){
                            echo '"'.$szamlaszam['sorszam_elotag'].$szamlaszam['sorszam_szam'].'", ';
                        }
                    ?>
                ];
                $("#szamla_sorszam").autocomplete({
                    source: availableTags
                });


                $("#kifizetes_datuma").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            });
        </script>
    <?php
    }

    private function showList()
    {
        ?>
        <div class="header">
            <h1>Kifizetesek</h1>
        </div>

        <div class="list_upper_box">
            <div class="search">
                <form action="" method="POST">
                    <input id="search_field" size="32" type="text" name="search_field"
                           value="<?php echo $this->getInputValues()['search_field']?>"/>
                    <input type="submit" name="search_button" value="Keres" class="search_button"/>
                </form>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új kifizetes</button>
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
        $this->kifizetesDataTable->printTable();
    }


    private function showGlobal()
    {
        ?>



    <?php
    }

}