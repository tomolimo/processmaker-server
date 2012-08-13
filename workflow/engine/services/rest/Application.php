<?php

class Services_Rest_Application
{
    protected function get($id = '', $type = null, $start = null, $limit = null)
    {
        if (empty($id)) {
            // getting all records.
            G::loadClass('applications');
            $app = new Applications();
            $userUid = Services_Rest_Auth::$userId;

            return $app->getAll($userUid, $start, $limit, $type);
        } else {
            // get a specific record.
            G::loadClass('wsBase');
            $wsBase = new wsBase();
            return $wsBase->getCaseInfo($id);
        }
    }
}
