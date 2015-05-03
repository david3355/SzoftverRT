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