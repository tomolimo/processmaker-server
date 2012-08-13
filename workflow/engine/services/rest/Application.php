<?php

class Application
{
    protected function get($id = null)
    {
        if (is_null($id)) {
            // getting all records.
            G::loadClass('applications');
            $app->getAll($userUid, $start=null, $limit=null, $action=null);
        } else {
            // get a specific record.
            G::loadClass('wsBase');
            $case = new wsBase();
            $case->getCaseInfo($id);
        }
    }
}
