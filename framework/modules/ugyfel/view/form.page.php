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
                                <td><input size="32" readonly="readonly" type="text" name="azon" value=""></td>
                            </tr>
                            <tr>
                                <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                                <td><input class="ugyfel_nev" size="32" type="text" name="nev" value=""></td>
                            </tr>
                            <tr>
                                <td><span>Cím</span></td>
                                <td><input size="32" type="text" name="cim" value=""></td>
                            </tr>
                            <tr>
                                <td><span>Város</span></td>
                                <td><input size="32" type="text" name="varos" value=""></td>
                            </tr>
                            <tr>
                                <td><span>Irányítószám</span></td>
                                <td><input size="32" type="text" name="ir" value=""></td>
                            </tr>
                            <tr>
                                <td><span>Telefon</span></td>
                                <td><input size="32" type="text" name="tel" value=""></td>
                            </tr>
                            <tr>
                                <td><span>Email</span></td>
                                <td><input size="32" type="text" name="email" value=""></td>
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