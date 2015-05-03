<div class="form_box">
    <h1>Bejövő számla szerkesztése (Számla)</h1>
    <input type="submit" name="" value="Mentés" class="save_button">
    <input type="submit" name="" value="Alkalmaz" class="apply_button">
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
                    <td><span class="mandatory">Sorszám<span style="color:red">*</span></span></td>
                    <td>
                        <input size="32" type="text" name="" value="">
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Fizetési mód<span style="color:red">*</span></span></td>
                    <td><select class="fizetesi_mod_dropdown" name="">
                            <option value="">Válasszon</option>
                            <option value="">Csekk</option>
                            <option value="">Készpénzes</option>
                            <option value="" selected="">Utalásos</option>
                            <option value="">Utánvétes</option>
                        </select></td>
                </tr>
                <tr>
                    <td><span class="mandatory">Kiállítás dátuma<span style="color:red">*</span></span></td>
                    <td>
                        <input class="kiallitas_datuma hasDatepicker" size="15" id="" type="text" name="" value="2015-05-03">
                        <a href="#" title="Másolás a teljesítési dátumba és fizetési határidőbe" style="color:black;">▼</a>
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Teljesítés dátuma<span style="color:red">*</span></span></td>
                    <td><input class="teljesites_datuma hasDatepicker" size="15" id="id_date_119ab2ddb" type="text"
                               name="o1744912" value="2015-05-03">
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Fizetési határidő<span style="color:red">*</span></span></td>
                    <td>
                        <input class="fizetesi_hatarido hasDatepicker" size="15" id="id_date_219ab2ddb" type="text" name="" value="2015-05-03">
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
                    <td><span class="mandatory">Kibocsátó<span style="color:red">*</span></span></td>
                    <td><input type="text" readonly="readonly" value="" title=""><input type="submit" name=""
                                                                                        value="Kiválaszt"></td>
                </tr>
                <tr>
                    <td>
                        <span class="mandatory">Név<span style="color:red">*</span></span>
                    </td>
                    <td>
                        <input id="id_kibocsato_adatok_cim_ugyfel_nev" size="32" readonly="readonly" type="text"
                               name="" value="">
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                    <td>
                        <input id="id_kibocsato_adatok_cim" size="32" readonly="readonly" type="text" name="" value="">
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Adószám<span style="color:red">*</span></span></td>
                    <td><input size="32" readonly="readonly" type="text" name="" value=""></td>
                </tr>
                <tr>
                    <td><span>Bankszámlaszám</span></td>
                    <td><input size="32" readonly="readonly" type="text" name="" value=""></td>
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
                    <td><span class="mandatory">Befogadó<span style="color:red">*</span></span></td>
                    <td>
                        <input type="text" readonly="readonly" value="" title="">
                        <input type="submit" name="" value="Kiválaszt">
                    </td>
                </tr>
                <tr>
                    <td><span class="mandatory">Név<span style="color:red">*</span></span></td>
                    <td>
                        <input id="id_befogado_adatok_cim_ugyfel_nev" size="32" readonly="readonly" type="text"
                               name="" value=""></td>
                </tr>
                <tr>
                    <td><span class="mandatory">Székhely<span style="color:red">*</span></span></td>
                    <td>
                        <input id="id_befogado_adatok_cim" size="32" readonly="readonly" type="text" name="" value="">
                    </td>
                </tr>
                <tr>
                    <td><span>Adószám</span></td>
                    <td><input size="32" readonly="readonly" type="text" name="" value=""></td>
                </tr>
                <tr>
                    <td><span>Bankszámlaszám</span></td>
                    <td><input size="32" readonly="readonly" type="text" name="" value=""></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="clear"></div>
    </div>

    <h2>Számla tételek (0)</h2>
    <input type="submit" name="" value="Új kézi számla tétel">
    <input type="submit" name="" value="Cikkek hozzáadása">

    <table cellspacing="0" cellpadding="0" class="listtable">
        <tbody>
        <tr>
            <th>Megnevezés</th>
            <th>Nettó egység / össz ár</th>
            <th>Bruttó egység / össz ár</th>
            <th>Mennyiség</th>
            <th>Mértékegység</th>
            <th>Áfakulcs</th>
            <th>Vámtarifaszám</th>
            <th>Töröl</th>
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

    <h2>Kapcsolódó számlák</h2>

    <input type="submit" name="" value="Kapcsolódó számla megadása">
    <table cellspacing="0" cellpadding="0" class="listtable">
        <tbody>
        <tr>
            <th>Azonosító</th>
            <th>Típus</th>
            <th>Befogadó</th>
            <th>Kiállítás dátuma</th>
            <th>Töröl</th>
        </tr>
        <tr>
            <td colspan="5" align="center">Nincsenek kapcsolódó számlák</td>
        </tr>
        </tbody>
    </table>

    <h2><span>Megjegyzés</span></h2>
    <textarea cols="65" rows="6" name="o1744899"></textarea>
    <input type="hidden" name="" value="" id="id_y_pos">
    <br>
    <input type="submit" name="" value="Mentés" class="save_button">
    <input type="submit" name="" value="Alkalmaz" class="apply_button">
    <input type="submit" name="" value="Mentés és új" class="save_and_new_button">
    <input type="submit" name="" value="Vissza" class="back_button">
</div>
