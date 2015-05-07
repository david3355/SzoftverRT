<?php

class Uzenetek_Site_Component extends Site_Component
{

    private $msg;

    function process()
    {
        $this->msg = $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    function show()
    {
        if (!empty($this->msg)) {
            echo '<h2>Üzenet</h2>';
            echo '<ul>';
            foreach ($this->msg as $m) {
                echo '<li>'.$m.'</li>';
            }
            echo '</ul>';
        }
    }
} 
