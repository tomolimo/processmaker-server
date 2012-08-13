<?php
G::loadClass('wsBase');
G::LoadClass('sessions');

class Services_Rest_Login
{
    public function post($user, $passwd)
    {
        $wsBase = new wsBase();
        $result = $wsBase->login($user, $passwd);

        if ($result->status_code == 0) {
            return array(
                'auth_key' => $result->message,
            );
        } else {
            throw new RestException(401, $result->message);
        }
    }
}
