<form method="POST">
    <div class="form_box">
        <h1>Felhasználó szerkesztése</h1>
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
                                  <td><span>Email</span></td>
                                  <td><input size="32" type="text" name="email" value=""></td>
                               </tr>
                               <tr>
                                  <td><span>Jelszo</span></td>
                                  <td><input size="32" type="password" name="jelszo" value=""></td>
                               </tr>
                               <tr>
                                  <td><span>Jog</span></td>
                                  <td>
                                      <select name="jog">
                                        <option value="-" selected="">-</option>
                                        <option value="admin">Admin</option>
                                        <option value="felhasznalo">Felhasználó</option>
                                     </select>
                                  </td>
                               </tr>
                               <tr>
                                  <td><span>Aktív</span></td>
                                  <td><input type="checkbox" name="aktiv" value=""></td>
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