<?

class Admin_Login_Site_Template extends Site_Template
{
    function show()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <script src="/modules/moopage/moopage4.js"></script>
            <link rel="stylesheet" href="/admin/templates/login.css" type="text/css">
            <link rel="stylesheet" href="/admin/templates/perpetum_template.css" type="text/css">
            <title>Login</title>
        </head>
        <body>
        <h1 style="text-align: center"> Admin</h1>

        <div class="login_box">
            <div class="header">
                <span class="icon icon-lock"></span>

                <h2>Bejelentkezés</h2>
            </div>
            <?
            $this->show_slot('messages');
            $this->show_slot('login');
            ?>

            <div class="actions">
                <p>Felhasználónév: teszt4</p>
                <p>Jelszó: Teszt4</p>
            </div>

        </div>


        </body>
        </html>
    <?
    }
}
