<?php

class Gauth
{
    /**
     * Defined type authentication.
     */
    const AUTH_TYPE = 'gauth';

    /**
     * Authentication of a user through the class RBAC_user
     *
     * verifies that a user has permission to start an application
     *
     * Function verifyLogin
     *
     * @access public
     * @param  string $userName UserId  (login) de usuario
     * @param  string $password Password
     * @return type
     *  -1: no user exists
     *  -2: wrong password
     *  -3: inactive user
     *  -4: expired user
     *  -6: role inactive
     *  n : string user uid
     * @throws Exception
     */
    public function VerifyLogin($userName, $password)
    {
        $validationMethod = function($inputPassword, $storedPassword) {
            return Bootstrap::verifyHashPassword($inputPassword, $storedPassword);
        };

        if (app()->getProvider(Illuminate\Session\SessionServiceProvider::class) !== null) {
            if (session()->has(Gauth::AUTH_TYPE) && session(Gauth::AUTH_TYPE) === true) {
                $user = Socialite::driver('google')->userFromToken($password);
                $token = $user->token;
                $validationMethod = function($inputPassword, $storedPassword) use($token) {
                    return $token === $inputPassword;
                };
            }
        }

        //invalid user
        if ($userName == '') {
            return -1;
        }
        //invalid password
        if ($password == '') {
            return -2;
        }
        $con = Propel::getConnection(RbacUsersPeer::DATABASE_NAME);
        try {
            $c = new Criteria('rbac');
            $c->add(RbacUsersPeer::USR_USERNAME, $userName);

            $rs = RbacUsersPeer::doSelect($c, Propel::getDbConnection('rbac_ro'));
            if (is_array($rs) && isset($rs[0]) && is_object($rs[0]) && get_class($rs[0]) == 'RbacUsers') {
                $dataFields = $rs[0]->toArray(BasePeer::TYPE_FIELDNAME);
                //verify password with md5, and md5 format
                if (mb_strtoupper($userName, 'utf-8') === mb_strtoupper($dataFields['USR_USERNAME'], 'utf-8')) {
                    if ($validationMethod($password, $rs[0]->getUsrPassword())) {
                        if ($dataFields['USR_DUE_DATE'] < date('Y-m-d')) {
                            return -4;
                        }
                        if ($dataFields['USR_STATUS'] != 1 && $dataFields['USR_UID'] !== RBAC::GUEST_USER_UID) {
                            return -3;
                        }

                        $rbacUsers = new RbacUsers();
                        $role = $rbacUsers->getUserRole($dataFields['USR_UID']);
                        if ($role['ROL_STATUS'] == 0) {
                            return -6;
                        }

                        return $dataFields['USR_UID'];
                    } else {
                        return -2;
                    }
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } catch (Exception $error) {
            throw($error);
        }

        return -1;
    }
}
