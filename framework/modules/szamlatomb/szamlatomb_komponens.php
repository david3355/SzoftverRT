<?php

/**
 * Class SzamlatombKomponens
 */
class SzamlatombKomponens extends Site_Component
{

    private $showFormPage = false;

    private $pm;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
    }

    function process()
    {
        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }

        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showFormPage = false;
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
        <form action="" method="POST">
            <div class="form_box">
                <h1>Számlatömb szerkesztése</h1>
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
                                        <td><input size="32" type="text" name="megnevezes" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span>Előtag</span></td>
                                        <td><input size="32" type="text" name="elotag" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span>Kezdőszám</span></td>
                                        <td><input size="32" type="text" name="kezdoszam" value=""></td>
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
                <input id="search_field" size="32" type="text" name="search_field" value=""/>
                <input type="submit" name="search_button" value="Keres" class="search_button"/>
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

                <div class="filter_item">
                    Irány: <select name="">
                        <option selected>Összes</option>
                        <option>Kimenő</option>
                        <option>Bejövő</option>
                    </select></div>
            </div>
        </div>

        <div class="clear"></div>
        <div class="pagination">
            <div class="pagination_element_count">Találatok száma: 3</div>
            <select>
                <option value="50" selected="">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </select>
            Előző
    <span class="pagination_page_number">
        <span class="pagination_active_page_number">1</span>
    </span>
            Következő
        </div>
        <div class="clear"></div>
        <div class="itemlist">
            <table cellspacing="0" cellpadding="0" class="listtable">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox"></th>
                    <th>
                        Megnevezés
                    </th>
                    <th>
                        Előtag
                    </th>
                    <th>
                        Kezdőszám
                    </th>
                    <th>
                        Lezárás dátuma
                    </th>
                    <th colspan="2">
                        Műveletek
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" value="" name="id">
                            <button type="submit" name="edit">Szerkesztés</button>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" value="" name="id">
                            <button type="submit" name="delete">Törlés</button>
                        </form>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="clear"></div>
        <div class="pagination">
            <select>
                <option value="50" selected="">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </select> Előző
    <span class="pagination_page_number">
        <span class="pagination_active_page_number">1</span>
    </span>
            Következő
        </div>
    <?php
    }
}