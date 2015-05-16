<?php

/**
 * Class SzamlaKomponens
 */
class SzamlaKomponens extends Input_Memo_Site_Component
{
    private $showFormPage = false;
    private $szamlaDataTable;
    private $pm;
    private $szamlaData = null;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->szamlaDataTable = new Szamla_Lazy_Data_Table();
    }

    function printTetel(array $tetel = null)
    {
        if($tetel==null)
		{
			$tetel = array('id'=>"", 'megnevezes'=>"", 'netto_ar'=>"", 'brutto_ar'=>"", 'mennyiseg'=>"", 'mennyiseg_egyseg'=>"",'afa'=> "", 'vamtarifaszam'=>"");
			$ro = '';
		}
		else $ro = 'readonly';

        echo  sprintf(
            '<tr>
                    <td>
                        <input name="sztetel_id[]" type="text" value="%s" readonly>
                    </td>
                    <td>
                        <input name="megnevezes[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input class="egy_netto" name="netto[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input class="egy_brutto" name="brutto[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input name="mennyiseg[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input name="mennyisegi_egyseg[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input name="afa[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <input name="vtsz[]" type="text" value="%s" %s>
                    </td>
                    <td>
                        <button class="torles_gomb" name="torles">Törlés</button>
                    </td>
                </tr>', $tetel['id'],  $tetel['megnevezes'],$ro ,$tetel['netto_ar'],$ro,$tetel['brutto_ar'],$ro,$tetel['mennyiseg'],$ro,$tetel['mennyiseg_egyseg'],$ro,$tetel['afa'],$ro,$tetel['vamtarifaszam'],$ro);
    }

    function subProcess()
    {
        $aktID = $_POST['id'];

        if (!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])) {
            $this->showFormPage = true;
        }

        if (!empty($_POST['new'])) unset($_SESSION['szamla_edit_id']);

        //törlés
        if (isset($_POST['delete'])) {
            $szamla =$this->pm->getObject($aktID);
            $msg = $szamla->delete();
            echo "<script>alert('" . $msg . "')</script>";
        }

        if(!empty($_POST['edit']))
        {
            $_SESSION['szamla_edit_id']=$aktID;
        }

        if(!empty($_SESSION['szamla_edit_id']))
        {
            $this->szamlaData=$this->pm->getObject($_SESSION['szamla_edit_id'])->getSzamlaAdatok();
        }

		//beiras
		if (!empty($_POST['save_and_new']) || !empty($_POST['save']))
		{
            $szla_adatok = array(
                'szlatomb_obj_id' => $_POST['szlatomb_obj_id'],

				'kiallito_neve' => $_POST['kiallito_neve'],
				'kiallito_cim' => $_POST['kiallito_cim'],
				'kiallito_adoszam' => $_POST['kiallito_adoszam'],
				'kiallito_bszla' => $_POST['kiallito_bszla'],
				
				'befogado_nev' => $_POST['befogado_nev'],
				'befogado_cim' => $_POST['befogado_cim'],
				'befogado_adoszam' => $_POST['befogado_adoszam'],
				'befogado_bszla' => $_POST['befogado_bszla'],
				
				'fizetesi_mod' => $_POST['fizetesi_mod'],
				'kiallitas_datum' => $_POST['kiallitas_datum'],
				'teljesites_datum' => $_POST['teljesites_datum'],
				'fizetes_datum' => $_POST['fizetesi_hatarido'],
				'megjegyzes' => $_POST['megjegyzes']
            );

            $sztid = $_POST['sztetel_id'];
            $megnevezes = $_POST['megnevezes'];
            $netto = $_POST['netto'];
            $brutto = $_POST['brutto'];
            $mennyiseg = $_POST['mennyiseg'];
            $mennyisegi_egyseg = $_POST['mennyisegi_egyseg'];
            $afa = $_POST['afa'];
            $vtsz = $_POST['vtsz'];

            $tetelek = array();
            for($i = 0; $i < sizeof($megnevezes); $i++)
            {
                $tetelek[] = array('id' => $sztid[$i], 'megnevezes'=>$megnevezes[$i], 'netto_ar'=>$netto[$i], 'brutto_ar'=>$brutto[$i], 'mennyiseg'=>$mennyiseg[$i], 'mennyiseg_egyseg'=>$mennyisegi_egyseg[$i],'afa'=> $afa[$i], 'vamtarifaszam'=>$vtsz[$i]);
            }
            $szla_adatok['tetelek'] = $tetelek;

            $errors = array();
			// módosítás vagy létrehozás
            if(isset($_SESSION['szamla_edit_id']))    // Edit
            {
                $szamla=$this->pm->getObject($_SESSION['szamla_edit_id']);
                $result = $szamla->setSzamlaAdatok($szla_adatok, $errors);
                if(!empty($errors))   $msg = "Edit errors: ".implode(', ', $errors);
                else
                {
                    $msg = sprintf("Successfully edited %s instances", $result);
                    unset($_SESSION['szamla_edit_id']);
                }
                echo"<script>alert('".$msg."')</script>";
            }
            else    // Create
            {
                $szla = $this->pm->createObject('Szamla', $szla_adatok, $errors);
                // Hibakód visszaadása a felületre, ha a $szla egy array, majd ide kell valami elegáns:
                if(!empty($errors)) {
                    $msg = implode(', ', $szla);
                    echo "<script>alert('Create error: " . $msg . "')</script>";
                }
            }

            if (!empty($_POST['back'])) {
                $this->showFormPage = false;
            }

            if(!empty($_POST['save']))
            {
              if(!empty($errors))  $this->showFormPage = true;
              else $this->showFormPage = false;
            }
        }
		
        $this->szamlaDataTable->process($_POST);
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
            <h1>
                <?php
                if(!is_null($this->szamlaData)) echo sprintf('Számla adatai (%s)', $this->szamlaData['szla_sorszam']);
                else echo 'Új számla létrehozása';
                ?>
                </h1>
            <?php
            if(is_null($this->szamlaData))  echo '<input type="submit" name="save" value="Mentés" class="save_button">';
            ?>
            <input type="submit" name="back" value="Vissza" class="back_button">
            <br><br>

            <div class="form_szurke_doboz">
                <div></div>
                <div class="clear"></div>

                <div class="float_left">
                    <h2>Számla adatai</h2>
                    <table class="formtable" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td><span class="mandatory">Számlatömb<span style="color:red">*</span></span></td>
                            <td><select class="fizetesi_mod_dropdown" name="szlatomb_obj_id" <?php if(!is_null($this->szamlaData)) echo 'disabled'; ?> >
                                    <?php
                                        $sztombok = $this->pm->select('Szamlatomb', array('id', 'megnevezes'))->where('lezaras_datum', '=', '0000-00-00')->exeSelect();
                                    foreach($sztombok as $szt)
                                    {
                                        if(!is_null($this->szamlaData) && $this->szamlaData['szlatomb_obj_id'] == $szt['id']) $selected = "selected";
                                        else $selected = "";
                                        echo sprintf('<option  value="%s" %s>%s</option>', $szt['id'], $selected, $szt['megnevezes']);
                                    }
                                    ?>
                                </select></td>
                        </tr>
						<tr>
                            <td><span class="mandatory">Fizetési mód<span style="color:red">*</span></span></td>
                            <td><select class="fizetesi_mod_dropdown" name="fizetesi_mod" <?php if(!is_null($this->szamlaData)) echo 'disabled'; ?>>
                                    <?php
                                    $fm = array('0'=>'Válasszon', '1'=>'Csekk', '2'=>'Készpénzes', '3'=>'Utalásos', '4'=>'Utánvétes');
                                    foreach($fm as $key=>$val)
                                    {
                                        if(!is_null($this->szamlaData) && $this->szamlaData['fizetesi_mod'] == $key) $selected = "selected";
                                        else $selected = "";
                                        echo sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Kiállítás dátuma<span style="color:red">*</span></span></td>
                            <td>
                                <input class="kiallitas_datum datepicker" size="15" id="" type="text"
                                       name="kiallitas_datum" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['kiallitas_datum']); else echo sprintf('value="%s"', date('Y-m-d')); ?>>
                                <a class="datum_masolas" href="#"
                                   title="Másolás a teljesítési dátumba és fizetési határidőbe"
                                   style="color:black;">▼</a>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Teljesítés dátuma<span style="color:red">*</span></span></td>
                            <td><input class="teljesites_datum datepicker" size="15" type="text" name="teljesites_datum"
                                       <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['teljesites_datum']); else echo sprintf('value="%s"', date('Y-m-d')); ?>>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Fizetési határidő<span style="color:red">*</span></span></td>
                            <td>
                                <input class="fizetesi_hatarido datepicker" size="15" type="text"
                                       name="fizetesi_hatarido" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['fizetes_datum']); else echo sprintf('value="%s"', date('Y-m-d')); ?>>
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
                                <input size="32" type="text" name="kiallito_neve" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['kiallito_neve']); ?>>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                            <td>
                                <input size="32" type="text" name="kiallito_cim" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['kiallito_cim']); ?>>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Adószám<span style="color:red">*</span></span></td>
                            <td><input size="32" type="text" name="kiallito_adoszam" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['kiallito_adoszam']); ?>></td>
                        </tr>
                        <tr>
                            <td><span>Bankszámlaszám</span></td>
                            <td><input size="32" type="text" name="kiallito_bszla" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['kiallito_bszla']); ?>></td>
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
                                        <input size="32" type="text" name="befogado_nev" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['befogado_nev']); ?>></td>
                        </tr>
                        <tr>
                            <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                            <td>
                                <input size="32" type="text" name="befogado_cim" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['befogado_cim']); ?>>
                            </td>
                        </tr>
                        <tr>
                            <td><span>Adószám</span></td>
                            <td><input size="32" type="text" name="befogado_adoszam" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['befogado_adoszam']); ?> ></td>
                        </tr>
                        <tr>
                            <td><span>Bankszámlaszám</span></td>
                            <td><input size="32" type="text" name="befogado_bszla" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['befogado_bszla']); ?>></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>
            </div>

            <h2>Számla tételek (0)</h2>
            <?php
            if(is_null($this->szamlaData)) echo '<button class="uj_szamla_tetel">Új számlatétel</button>';
            ?>


            <table cellspacing="0" cellpadding="0" class="listtable szamla_tetel_tabla">
                <thead>
                <tr>
                    <th>Azonosító</th>
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

                <?php


                if(!is_null($this->szamlaData))
                {
                    $tetelek = $this->szamlaData['tetelek'];
                    foreach($tetelek as $tetel)
                    {
                        $this->printTetel($tetel);
                    }
                }
                else $this->printTetel();

                ?>

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
            <textarea cols="65" rows="6" name="megjegyzes" <?php if(!is_null($this->szamlaData)) echo  sprintf('value="%s" readonly', $this->szamlaData['megjegyzes']); ?>></textarea>
            <br>
            <?php
            if(is_null($this->szamlaData))  echo '<input type="submit" name="save" value="Mentés" class="save_button">';
            ?>
            <input type="submit" name="back" value="Vissza" class="back_button">
        </div>
		</form>


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

                $('.uj_szamla_tetel').on('click', this, newRowClick );

                function newRowClick(e)
                {
                    e.preventDefault();
                    var tr = '<tr>';
                    tr += '<td> <input name="sztetel_id[]" type="text" readonly> </td>';
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
                }

                $(".search_all").checked(function() {
                    if(this.checked) {
                        document.getElementById("search_kibocsato").checked = true;
                        document.getElementById("search_kibocsato").checked = true;
                    }
                    else
                    {
                        document.getElementById("search_kibocsato").checked = false;
                        document.getElementById("search_kibocsato").checked = false;
                    }
                });



                $('.szamla_tetel_tabla').on('click', '.torles_gomb', function (e) {
                    e.preventDefault();

                    $(this).closest('tr').remove();

                    setNetto();
                    setBrutto();
                });

                $('.szamla_tetel_tabla').on('keyup', '.egy_netto', function (e) {
                    e.preventDefault();

                    setNetto();

                });

                $('.szamla_tetel_tabla').on('keyup', '.egy_brutto', function (e) {
                    e.preventDefault();

                    setBrutto();

                });


                var setNetto = function () {
                    var ossz = 0;
                    $('.egy_netto').each(function () {
                        var value = $(this).val() == '' ? 0 : $(this).val();
                        ossz += parseFloat(value);
                    });
                    $('.netto').html(ossz);
                }

                var setBrutto = function () {
                    var ossz = 0;
                    $('.egy_brutto').each(function () {
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
                <form action="" method="POST">
                    <input id="search_field" size="32" type="text" name="search_field" value="<?php echo $this->getInputValues()['search_field']?>"/>
                    <input type="submit" name="search_button" value="Keres" class="search_button"/>
                    <div class="searchfields">
                    <div class="float_left">
                        <input type="checkbox" id="search_all" name="search_all" checked>
                        <label for="search_all">Összes</label>
                    </div>
                    <div class="float_left">
                        <input type="checkbox" id="search_kibocsato" name="search_kibocsato" checked>
                        <label for="search_kibocsato">Kibocsátó neve</label>
                    </div>
                    <div class="float_left">
                        <input type="checkbox" id="search_befogado" name="search_befogado" checked>
                        <label for="search_befogado">Befogadó neve</label>
                    </div>
                    <div class="clear"></div>
                    </div>
                </form>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új számla</button>
                </form>
                <a class="button" href="http://erp.fejlesztesgyak2015.info/api.php?module=excel_api&function=getExcelSzla">Excel
                    export</a>
            </div>
            <div class="filtersbox">
                <a href="#" title="Szűrők frissítése">
                    <div class="filtersbox_refresh_icon"></div>
                </a>

                <div class="filter_item"></div>

            </div>
        </div>

        <div class="clear"></div>
    <?php

        $this->szamlaDataTable->printTable();
    }
}