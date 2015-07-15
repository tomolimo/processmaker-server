<?php

require_once 'classes/model/om/BasePmoauthUserAccessTokens.php';


/**
 * Skeleton subclass for representing a row from the 'PMOAUTH_USER_ACCESS_TOKENS' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class PmoauthUserAccessTokens extends BasePmoauthUserAccessTokens
{
    /**
     * @param $token
     * @return \PmoauthUserAccessTokens|bool
     */
    public function getSessionData($token)
    {
        $c = new Criteria('workflow');
        //$c->addSelectColumn(PmoauthUserAccessTokensPeer::ACCESS_TOKEN);
        $c->add(PmoauthUserAccessTokensPeer::ACCESS_TOKEN, $token, Criteria::EQUAL);
        $result = PmoauthUserAccessTokensPeer::doSelect($c);

        return (is_array($result) && empty($result)) ? false : $result[0];
    }
} // PmoauthUserAccessTokens
