<?php

class EmailServer extends BaseEmailServer
{
    /**
     * Load the default account
     *
     * @return array
     */
    public function loadDefaultAccount()
    {
        $c = new Criteria('workflow');
        $c->clearSelectColumns();
        $c->addSelectColumn(EmailServerPeer::MESS_ACCOUNT);
        $c->add(EmailServerPeer::MESS_DEFAULT, 1);
        $rs = EmailServerPeer::doSelectRS($c, Propel::getDBConnection('workflow_ro'));
        $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $rs->next();
        $response = $rs->getRow();

        return $response;
    }

    /**
     * Check if the MESS_UID exist
     *
     * @param string $emailServerUid
     *
     * @return boolean
     * @throws Exception
    */
    public static function exists($emailServerUid)
    {
        try {
            $criteria = new Criteria('workflow');
            $criteria->add(EmailServerPeer::MESS_UID, $emailServerUid, Criteria::EQUAL);
            $dataset = EmailServerPeer::doSelectOne($criteria);

            return !is_null($dataset);

        } catch (Exception $e) {
            throw $e;
        }
    }
}

