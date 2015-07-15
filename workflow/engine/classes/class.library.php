<?php
class Library
{
    public static function getCasesNumRec($userUid)
    {
        $cnn = Propel::getConnection("workflow");
        $stmt = $cnn->createStatement();

        //Number of records active
        $criteria = new Criteria("workflow");

        //SELECT
        $criteria->addSelectColumn(CaseConsolidatedCorePeer::CON_STATUS);
        //FROM
        //WHERE
        $criteria->add(CaseConsolidatedCorePeer::CON_STATUS, "ACTIVE");

        $activeNumRec = CaseConsolidatedCorePeer::doCount($criteria);

        //Number of records
        $numRec = 0;

        $sql = "SELECT COUNT(APP_CACHE_VIEW.TAS_UID) AS NUMREC
                FROM   CASE_CONSOLIDATED
                       LEFT JOIN APP_CACHE_VIEW ON (CASE_CONSOLIDATED.TAS_UID = APP_CACHE_VIEW.TAS_UID)
                WHERE  APP_CACHE_VIEW.USR_UID = '$userUid' AND
                       APP_CACHE_VIEW.DEL_THREAD_STATUS = 'OPEN' AND
                       APP_CACHE_VIEW.APP_STATUS = 'TO_DO'";

        $rsSql = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);

        while ($rsSql->next()) {
            $row = $rsSql->getRow();

            $numRec = $row["NUMREC"];
        }

        $numRec = ($activeNumRec > 0)? $numRec : 0;

        return $numRec;
    }
}

