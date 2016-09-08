<?php

require_once 'classes/model/om/BaseAppSequence.php';


/**
 * Skeleton subclass for representing a row from the 'APP_SEQUENCE' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class AppSequence extends BaseAppSequence {

    /**
     * Get an Set new sequence number
     *
     * @return mixed
     * @throws Exception
     */
    public function sequenceNumber()
    {
        try {
            $con = Propel::getConnection('workflow');
            $stmt = $con->createStatement();
            //UPDATE SEQUENCES SET SEQ_VALUE = LAST_INSERT_ID(SEQ_VALUE + 1);
            $sql = "UPDATE APP_SEQUENCE SET ID=LAST_INSERT_ID(ID+1)";
            $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
            //SELECT LAST_INSERT_ID()
            $sql = "SELECT LAST_INSERT_ID()";
            $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
            $rs->next();
            $row = $rs->getRow();
            $result = $row['LAST_INSERT_ID()'];
        } catch (\Exception $e) {
            throw ($e);
        }
        return $result;
    }


    /**
     * Update sequence number
     *
     * @return mixed
     * @throws Exception
     */
    public function updateSequenceNumber($number)
    {
        try {
            $con = Propel::getConnection('workflow');
            $stmt = $con->createStatement();
            $c = new Criteria();
            $rs = AppSequencePeer::doSelectRS($c);
            $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rs->next();
            $row = $rs->getRow();
            if ($row) {
                $sql = "UPDATE APP_SEQUENCE SET ID=LAST_INSERT_ID('$number')";
            } else {
                $sql = "INSERT INTO APP_SEQUENCE (ID) VALUES ('$number');";
            }
            $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
        } catch (\Exception $e) {
            throw ($e);
        }
    }

} // AppSequence
