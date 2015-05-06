<?php

/**
 * Class FelhasznaloKomponens
 */
class FelhasznaloKomponens extends Site_Component
{

    private $showFormPage = false;
    private $pm;
    private $felhasznaloDataTable;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->felhasznaloDataTable = new Felhasznalo_Lazy_Data_Table();
    }

    function process()
    {
        $this->felhasznaloDataTable->process($_POST);

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

            $felhasznalo = $this->pm->createObject('Felhasznalo', $felhasznalo_adatok);
        }
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
                                        <td><input size="32" readonly="readonly" type="text" name="azon" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                                        <td><input class="ugyfel_nev" size="32" type="text" name="nev" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Email<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="text" name="email" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Jelszo<span style="color:red">*</span></span></td>
                                        <td><input size="32" type="password" name="jelszo" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span>Jog</span></td>
                                        <td>
                                            <select name="jog">
                                                <option value="0">-</option>
                                                <option value="1">Admin</option>
                                                <option value="2">Felhasználó</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Aktív</span></td>
                                        <td><input type="checkbox" name="aktiv" value=""></td>
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

                <div class="filter_item">
                    Irány: <select name="o6164761">
                        <option selected>Összes</option>
                        <option>Kimenő</option>
                        <option>Bejövő</option>
                    </select></div>
            </div>
        </div>

        <div class="clear"></div>
        <?php
        $this->felhasznaloDataTable->printTable();

    }
}