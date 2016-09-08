<?php

require_once 'classes/model/om/BaseProcessVariables.php';


/**
 * Skeleton subclass for representing a row from the 'PROCESS_VARIABLES' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class ProcessVariables extends BaseProcessVariables {

    public function create ($aData)
    {
        $con = Propel::getConnection( ProcessVariablesPeer::DATABASE_NAME );
        try {
            $con->begin();
            $this->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            if ($this->validate()) {
                $result = $this->save();
            } else {
                $e = new Exception( "Failed Validation in class " . get_class( $this ) . "." );
                $e->aValidationFailures = $this->getValidationFailures();
                throw ($e);
            }
            $con->commit();
            return $result;
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function update ($aData)
    {
        $con = Propel::getConnection( ProcessVariablesPeer::DATABASE_NAME );
        try {
            $con->begin();
            $variable = ProcessVariablesPeer::retrieveByPK($aData['VAR_UID']);
            $variable->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            if ($variable->validate()) {
                $result = $variable->save();
            } else {
                $e = new Exception( "Failed Validation in class " . get_class( $variable ) . "." );
                $e->aValidationFailures = $variable->getValidationFailures();
                throw ($e);
            }
            $con->commit();
            return $result;
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function remove($sVarUid)
    {
        $oConnection = Propel::getConnection(ProcessVariablesPeer::DATABASE_NAME);
        try {
            $oProcessVariables = ProcessVariablesPeer::retrieveByPK($sVarUid);
            if (!is_null($oProcessVariables)) {
                $oConnection->begin();
                $iResult = $oProcessVariables->delete();
                $oConnection->commit();
                return $iResult;
            } else {
                throw(new Exception('This row doesn\'t exist!'));
            }
        } catch (Exception $oError) {
            $oConnection->rollback();
            throw($oError);
        }
    }

    public function Exists ($sVarUid)
    {
        try {
            $oObj = ProcessVariablesPeer::retrieveByPk($sVarUid);
            return (is_object($oObj) && get_class($oObj) == 'ProcessVariables');
        } catch (Exception $oError) {
            throw($oError);
        }
    }

    /**
     * verify if process variable row specified in [sUid] exists.
     *
     * @param      string $sUid   the uid
     */
    public function ProcessVariableExists($sUid)
    {
        $con = Propel::getConnection(ProcessVariablesPeer::DATABASE_NAME);

        try {
            $oObj = ProcessVariablesPeer::retrieveByPk($sUid);

            if (is_object($oObj) && get_class($oObj) == 'ProcessVariables') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $oError) {
            throw ($oError);
        }
    }
} // ProcessVariables
