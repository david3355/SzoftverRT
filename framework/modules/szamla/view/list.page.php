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