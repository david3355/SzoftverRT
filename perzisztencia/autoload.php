<?php

spl_autoload_register('loadClass');

function loadClass($class)
{
    require_once ($class.'.php');
}