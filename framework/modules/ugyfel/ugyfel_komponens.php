<?php

/**
 * Class UgyfelKomponens
 */
class UgyfelKomponens extends Site_Component
{

    private $showFormPage = false;
    private $ugyfelDataTable;

    private $pm;

    private $ufdata;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
        $this->ugyfelDataTable = new Ugyfel_Lazy_Data_Table();
    }

    function process()
    {
        if(!empty($_POST['new']) || !empty($_POST['edit']) || !empty($_POST['save_and_new'])){
            $this->showFormPage = true;
        }
		
		//törlés
		if(isset($_POST['delete']))
		{
            $uf=$this->pm->getObject($_POST['id']);
			$msg=$uf->delete();
			echo"<script>alert('".$msg."')</script>";
        }

        //módosítás
        if(!empty($_POST['edit']))
        {
            $uf=$this->pm->getObject($_POST['id']);
            $this->ufdata=$uf->getUgyfelAdatok();
            $_SESSION['ugyfel_edit_id']=$_POST['id'];
        }

        if(!empty($_POST['back']) || !empty($_POST['save'])){
            $this->showFormPage = false;
        }

		if (!empty($_POST['save_and_new']) || !empty($_POST['save'])) {
            $uf_adatok = array(
                'azonosito' => $_POST['azonosito'],
				'nev' => $_POST['nev'],
				'cim_irszam' => $_POST['cim_irszam'],
				'cim_varos' => $_POST['cim_varos'],
				'cim_utca_hsz' => $_POST['cim_utca_hsz'],
				'telefon' => $_POST['telefon'],
				'email' => $_POST['email']
            );

            if(isset($_SESSION['ugyfel_edit_id']))
            {
                $uf=$this->pm->getObject($_SESSION['ugyfel_edit_id']);
                unset($uf_adatok['azonosito']);
                $result = $uf->setUgyfelAdatok($uf_adatok);
                if(is_array($result)) {
                    $msg = implode(', ', $result);
                    echo "<script>alert('Edit error: " . $msg . "')</script>";
                }
                else
                {
                    unset($_SESSION['ugyfel_edit_id']);
                }
            }
            else
            {
                $uf = $this->pm->createObject('Ugyfel', $uf_adatok);
                // Hibakód visszaadása a felületre, ha az $uf egy array, majd ide kell valami elegáns:
                if(is_array($uf)) {
                    $msg = implode(', ', $uf);
                    echo "<script>alert('Create error: " . $msg . "')</script>";
                }
            }
        }

        $this->ugyfelDataTable->process($_POST);
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
                <h1>Ügyfél szerkesztése</h1>
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
                                        <td><input size="32" type="text" name="azonosito" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['azonosito'];}   ?>" <?php if(!empty($_POST['edit'])) {echo 'readonly';}   ?>></td>
                                    </tr>
                                    <tr>
                                        <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                                        <td><input class="ugyfel_nev" size="32" type="text" name="nev" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['nev'];}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Irányítószám</span></td>
                                        <td><input size="32" type="text" name="cim_irszam" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['cim_irszam'];}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Város</span></td>
                                        <td><input size="32" type="text" name="cim_varos" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['cim_varos'];}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Utca, házszám</span></td>
                                        <td><input size="32" type="text" name="cim_utca_hsz" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['cim_utca_hsz'];}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Telefon</span></td>
                                        <td><input size="32" type="text" name="telefon" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['telefon'];}   ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Email</span></td>
                                        <td><input size="32" type="text" name="email" value="<?php  if(!empty($_POST['edit'])) {echo $this->ufdata['email'];}   ?>"></td>
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
            <h1>Ügyfelek</h1>
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
                    <button type="submit" name="new" value="new">Új ügyfél</button>
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
        $this->ugyfelDataTable->printTable();

    }
}