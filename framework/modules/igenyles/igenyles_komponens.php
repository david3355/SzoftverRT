<?php

class IgenylesKomponens extends Site_Component{

    private $showFormPage = false;
    private $pm;
	private $igenylesDataTable;

    protected function afterConstruction()
    {
        $this->pm = PersistenceManager::getInstance();
		$this->igenylesDataTable = new Igenyles_Lazy_Data_Table();
    }

    function process()
    {
        if(!empty($_POST['edit'])){
            $this->showFormPage = true;
		}
		
		if(!empty($_POST['save'])){
			$params=json_encode(array("id"=>$_POST['id'], "statusz"=>$_POST['statusz']));
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://ugyfelkapu.fejlesztesgyak2015.info/api.php?module=erp_api&function=updateIgenyles");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			
			//execute the request
			$res=curl_exec($ch);
			
			
			echo "<script>alert('msg: ".$res."');</script>";
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
                <h1>Igénylés szerkesztése</h1>
                <input type="submit" name="save" value="Mentés" class="save_button">
                <input type="submit" name="back" value="Vissza" class="back_button">
                <br/>
                <br/>
                <div class="form_szurke_doboz">
                    <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
                    <table class="formtable">
                        <tbody>
                        <tr>
                            <td valign="top">
                                <table>
                                    <tbody>                                   
                                    <tr>
                                        <td><span class="mandatory">Státusz<span style="color:red">*</span></span></td>
                                        <td><select name="statusz">
                                    <option value="0">Válasszon</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select></td>
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
            <h1>Igénylések</h1>
        </div>

        <!--<div class="list_upper_box">
            <div class="search">
                <input id="search_field" size="32" type="text" name="search_field" value=""/>
                <input type="submit" name="search_button" value="Keres" class="search_button"/>
            </div>
            <div class="clear_right"></div>
            <div class="defaultoperationsbox">
                <form action="" method="post">
                    <button type="submit" name="new" value="new">Új igénylés</button>
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
        </div>-->
<form action="" method="POST">
           <input type="submit" name="edit" value="Mentés" class="save_button">
                <input type="text" name="id" value="">
        </form>

    <?php
	$this->igenylesDataTable->printTable();
    }
}