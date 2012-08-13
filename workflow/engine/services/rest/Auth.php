<?php
G::LoadClass('sessions');
G::LoadClass('wsBase');

class Auth implements iAuthenticate
{
    public $realm = 'Restricted API';

    function __isAuthenticated()
    {
        if (array_key_exists('HTTP_AUTH_KEY', $_SERVER)) {
            $authKey = $_SERVER['HTTP_AUTH_KEY'];
        } elseif (array_key_exists('auth_key', $_GET)) {
            $authKey = $_GET['auth_key'];
        } else {
            throw new RestException(401, 'Authentication Required');
        }

        $sessions = new Sessions();
        $session  = $sessions->verifySession($authKey);

        if (is_array($session)) {
            return true;
        }

        throw new RestException(401, 'Wrong Credentials!');
    }

    /**
     * @url POST
     */
    public function login($user, $passwd)
    {
        $wsBase = new wsBase();
        return $wsBase->login($user, $passwd);
    }
}
