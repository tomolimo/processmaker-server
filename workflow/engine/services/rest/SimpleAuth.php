<?php
class SimpleAuth implements iAuthenticate
{
    const KEY = 'sample';

    function __isAuthenticated()
    {
        print_r($_SERVER);
        return isset($_GET['key']) && $_GET['key']==SimpleAuth::KEY ? TRUE : FALSE;
    }

    function key()
    {
        return SimpleAuth::KEY;
    }
}
