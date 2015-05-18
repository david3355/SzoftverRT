<?php

class AutentikacioKomponens extends Site_Component
{

    private $auth;

    protected function afterConstruction()
    {
        $this->auth = Autentikacio::getInstance();
    }

    function process()
    {

        if (isset($_POST['login']) && !empty($_POST['login'])) {
            $_SESSION['msg'] = $this->auth->login($_POST['username'], $_POST['password']);
        }

        if (isset($_POST['logout']) && !empty($_POST['logout'])) {
            $this->auth->logout();
        }
    }

    function show()
    {
        if (!$this->auth->isUserAuthorized()) {
            $this->showLogin();
        } else {
            $this->showLogout();
        }
    }

    private function showLogin()
    {
        Bonus::showTheMagic();
        ?>
        <form class="form_box" action="" method="POST">
            <table>
                <tbody>
                <tr>
                    <td>
                        Felhasználónév
                    </td>
                    <td>
                        <input name="username" type="text"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jelszó
                    </td>
                    <td>
                        <input name="password" type="password"/>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="actions">
                <div class="float_right">
                    <input type="submit" name="login" value="Belépés"/>
                </div>
            </div>
        </form>
    <?php
    }

    private function showLogout()
    {
        ?>
        <form action="" method="POST">
            <input type="submit" name="logout" value="Kijelentkezés"/>
        </form>
    <?php
    }
}