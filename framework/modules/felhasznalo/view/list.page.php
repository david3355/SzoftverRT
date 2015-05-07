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
?>

