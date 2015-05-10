<?php

/**
 * Class SzamlaKomponens
 */
class SzamlaKomponens extends Site_Component
{
    private $showFormPage = false;

    private $pm;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
    }

    function process()
    {
        if (!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])) {
            $this->showFormPage = true;
        }

        //törlés
        if (isset($_POST['delete'])) {
            $Szamla = new Szamla($_POST['id']);
            $msg = $Szamla->delete();
            echo "<script>alert('" . $msg . "')</script>";
        }

        if (!empty($_POST['back']) || !empty($_POST['save'])) {
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
        <div class="form_box">
            <h1>Bejövő számla szerkesztése (Számla)</h1>
            <input type="submit" name="" value="Mentés" class="save_button">
            <input type="submit" name="" value="Mentés és új" class="save_and_new_button">
            <input type="submit" name="" value="Vissza" class="back_button">
            <br><br>

            <div class="form_szurke_doboz">
                <div></div>
                <div class="clear"></div>

                <div class="float_left">
                    <h2>Számla adatai</h2>
                    <table class="formtable" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td><span class="mandatory">Fizetési mód<span style="color:red">*</span></span></td>
                            <td><select class="fizetesi_mod_dropdown" name="fizetesi_mod">
                                    <option value="0">Válasszon</option>
                                    <option value="1">Csekk</option>
                                    <option value="2">Készpénzes</option>
                                    <option value="3">Utalásos</option>
                                    <option value="4">Utánvétes</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Kiállítás dátuma<span style="color:red">*</span></span></td>
                            <td>
                                <input class="kiallitas_datum datepicker" size="15" id="" type="text"
                                       name="kiallitas_datum" value="<?php echo date('Y-m-d'); ?>">
                                <a class="datum_masolas" href="#"
                                   title="Másolás a teljesítési dátumba és fizetési határidőbe"
                                   style="color:black;">▼</a>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Teljesítés dátuma<span style="color:red">*</span></span></td>
                            <td><input class="teljesites_datum datepicker" size="15" type="text" name="teljesites_datum"
                                       value="<?php echo date('Y-m-d'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Fizetési határidő<span style="color:red">*</span></span></td>
                            <td>
                                <input class="fizetesi_hatarido datepicker" size="15" type="text"
                                       name="fizetesi_hatarido" value="<?php echo date('Y-m-d'); ?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="float_left" style="margin-left:10px;">
                    <div class="float_left">
                        <h2>Kibocsátó adatai</h2>
                    </div>
                    <div class="float_left" style="padding: 20px 0px 0px 25px;"></div>
                    <div class="clear"></div>

                    <table cellspacing="0" cellpadding="0" class="formtable">
                        <tbody>
                        <tr>
                            <td>
                                <span class="mandatory">Név<span style="color:red">*</span></span>
                            </td>
                            <td>
                                <input size="32" type="text" name="kibocsato_nev">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                            <td>
                                <input size="32" type="text" name="kibocsato_szekhely">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Adószám<span style="color:red">*</span></span></td>
                            <td><input size="32" type="text" name="kibocsato_adoszam"></td>
                        </tr>
                        <tr>
                            <td><span>Bankszámlaszám</span></td>
                            <td><input size="32" type="text" name="kibocsato_bankszamlaszam"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="float_left" style="margin-left:10px;">
                    <div class="float_left">
                        <h2>Befogadó adatai</h2>
                    </div>
                    <div class="float_left" style="padding: 20px 0px 0px 25px;"></div>
                    <div class="clear"></div>

                    <table cellspacing="0" cellpadding="0" class="formtable">
                        <tbody>
                        <tr>
                            <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                            <td>
                                <input size="32" type="text" name="befogado_nev"></td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                            <td>
                                <input size="32" type="text" name="befogado_szekhely">
                            </td>
                        </tr>
                        <tr>
                            <td><span>Adószám</span></td>
                            <td><input size="32" type="text" name="befogado_adoszam"></td>
                        </tr>
                        <tr>
                            <td><span>Bankszámlaszám</span></td>
                            <td><input size="32" type="text" name="befogado_bankszamlaszam"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>
            </div>

            <h2>Számla tételek (0)</h2>
            <button class="uj_szamla_tetel">Új számlatétel</button>

            <table cellspacing="0" cellpadding="0" class="listtable szamla_tetel_tabla">
                <thead>
                <tr>
                    <th>Megnevezés</th>
                    <th>Nettó egység / össz ár</th>
                    <th>Bruttó egység / össz ár</th>
                    <th>Mennyiség</th>
                    <th>Mennyiségi egység</th>
                    <th>ÁFA</th>
                    <th>VTSZ</th>
                    <th>Művelet</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input name="megnevezes[]" type="text">
                    </td>
                    <td>
                        <input class="egy_netto" name="netto[]" type="text">
                    </td>
                    <td>
                        <input class="egy_brutto" name="brutto[]" type="text">
                    </td>
                    <td>
                        <input name="mennyiseg[]" type="text">
                    </td>
                    <td>
                        <input name="mennyisegi_egyseg[]" type="text">
                    </td>
                    <td>
                        <input name="afa[]" type="text">
                    </td>
                    <td>
                        <input name="vtsz[]" type="text">
                    </td>
                    <td>
                        <button class="torles_gomb" name="torles">Törlés</button>
                    </td>
                </tr>


                <tr id="osszegzo" style="font-weight:bold;font-size:15px;">
                    <td align="right">Összesen:</td>
                    <td align="center">
                        Nettó összeg: <span class="netto">0</span> <span class="penznem">Forint</span>
                    </td>
                    <td align="center">
                        Bruttó összeg: <span class="brutto">0</span> <span class="penznem">Forint</span>
                    </td>
                    <td colspan="5"></td>
                </tr>
                </tbody>
            </table>

            <h2><span>Megjegyzés</span></h2>
            <textarea cols="65" rows="6" name="o1744899"></textarea>
            <input type="hidden" name="" value="" id="id_y_pos">
            <br>
            <input type="submit" name="" value="Mentés" class="save_button">
            <input type="submit" name="" value="Mentés és új" class="save_and_new_button">
            <input type="submit" name="" value="Vissza" class="back_button">
        </div>


        <script>
            $(document).ready(function () {

                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    monthNames: ["Január", "Február", "Március",
                        "Április", "Május", "Június",
                        "Július", "Augusztus", "Szeptember",
                        "Október", "November", "December"],
                    monthNamesShort: ["Jan", "Feb", "Márc",
                        "Ápr", "Máj", "Jún",
                        "Júl", "Aug", "Szept",
                        "Okt", "Nov", "Dec"],
                    dayNames: ["Vasárnap", "Hétfő", "Kedd",
                        "Szerda", "Csütörtök", "Péntek", "Szombat"],
                    dayNamesMin: ["V", "H", "K", "Sze", "Cs", "P", "Szo"],
                    dayNamesShort: ["V", "H", "K", "Sze", "Cs", "P", "Szo"],
                    minDate: "-7d"

                });

                $('.datum_masolas').on('click', this, function (e) {
                    e.preventDefault();
                    var datum = $('.kiallitas_datum').val();
                    $('.teljesites_datum').val(datum);
                    $('.fizetesi_hatarido').val(datum);
                });

                $('.uj_szamla_tetel').on('click', this, function (e) {
                    e.preventDefault();

                    var tr = '<tr>';
                    tr += '<td> <input name="megnevezes[]" type="text"> </td>';
                    tr += '<td> <input class="egy_netto" name="netto[]" type="text"> </td>';
                    tr += '<td> <input class="egy_brutto" name="brutto[]" type="text"> </td>';
                    tr += '<td> <input name="mennyiseg[]" type="text"> </td>';
                    tr += '<td> <input name="mennyisegi_egyseg[]" type="text"> </td>';
                    tr += '<td> <input name="afa[]" type="text"> </td>';
                    tr += '<td> <input name="vtsz[]" type="text"> </td>';
                    tr += '<td> <button class="torles_gomb" name="torles"> Törlés </button> </td>';
                    tr += '</tr>';

                    $(tr).insertBefore('#osszegzo');
                });

                $('.szamla_tetel_tabla').on('click','.torles_gomb',function(e){
                    e.preventDefault();

                    $(this).closest('tr').remove();

                    setNetto();
                    setBrutto();
                });

                $('.szamla_tetel_tabla').on('keyup','.egy_netto',function(e){
                    e.preventDefault();

                    setNetto();

                });

                $('.szamla_tetel_tabla').on('keyup','.egy_brutto',function(e){
                    e.preventDefault();

                    setBrutto();

                });


                var setNetto = function(){
                    var ossz = 0;
                    $('.egy_netto').each(function(){
                        var value = $(this).val() == '' ? 0 : $(this).val();
                        ossz += parseFloat(value);
                    });
                    $('.netto').html(ossz);
                }

                var setBrutto = function(){
                    var ossz = 0;
                    $('.egy_brutto').each(function(){
                        var value = $(this).val() == '' ? 0 : $(this).val();
                        ossz += parseFloat(value);
                    });
                    $('.brutto').html(ossz);
                }

            });
        </script>

    <?php
    }

    private function showList()
    {
        ?>
        <div class="header">
            <h1>Számlák</h1>
        </div>

        <div class="list_upper_box">
            <div class="search">
                <input id="search_field" size="32" type="text" name="search_field" value=""/>
                <input type="submit" name="search_button" value="Keres" class="search_button"/>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új számla</button>
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
                    </select></div><a href="http://erp.fejlesztesgyak2015.info/api.php?module=excel_api&function=getExcelSzla">Excel export</a>
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
                        Sorszám
                    </th>
                    <th>
                        Irány
                    </th>
                    <th>
                        Típus
                    </th>
                    <th>
                        Kiállítás dátuma
                    </th>
                    <th>
                        Fizetési határidő
                    </th>
                    <th>
                        Teljesítés dátuma
                    </th>
                    <th>
                        Kibocsátó
                    </th>
                    <th>
                        Befogadó
                    </th>
                    <th>
                        Fizetési mód
                    </th>
                    <th>
                        Nettó összeg
                    </th>
                    <th>
                        Bruttó összeg
                    </th>
                    <th>
                        ÁFA összeg
                    </th>
                    <th colspan="3">
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
                    <td></td>
                    <td></td>
                    <td></td>
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
                    <td>
                        <form action="" method="post">
                            <input type="hidden" value="" name="id">
                            <button type="submit" name="storno">Sztornózás</button>
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