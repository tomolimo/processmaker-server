<?php

require_once 'classes/model/om/BaseGateway.php';


/**
 * Skeleton subclass for representing a row from the 'GATEWAY' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class Gateway extends BaseGateway {

  function create ($aData)
  {
    $con = Propel::getConnection(GatewayPeer::DATABASE_NAME);
    try
    {
      $this->fromArray($aData, BasePeer::TYPE_FIELDNAME);
      if($this->validate())
      {
        $result=$this->save();
      }
      else
      {
        $e=new Exception("Failed Validation in class ".get_class($this).".");
        $e->aValidationFailures=$this->getValidationFailures();
        throw($e);
      }
      $con->commit();
      return $result;
    }
    catch(Exception $e)
    {
      $con->rollback();
      throw($e);
    }
  }

  public function load($GatewayUid)
  {
    try {
      $oRow = GatewayPeer::retrieveByPK( $GatewayUid );
      if (!is_null($oRow))
      {
        $aFields = $oRow->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray($aFields,BasePeer::TYPE_FIELDNAME);
        $this->setNew(false);
        return $aFields;
      }
      else {
        throw(new Exception( "The row '" . $GatewayUid . "' in table Gateway doesn't exist!" ));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

   public function update($fields)
  {
    $con = Propel::getConnection(GatewayPeer::DATABASE_NAME);
    try
    {
      $con->begin();
      $this->load($fields['GAT_UID']);
      $this->fromArray($fields,BasePeer::TYPE_FIELDNAME);
      if($this->validate())
      {
        $result=$this->save();
        $con->commit();
        return $result;
      }
      else
      {
        $con->rollback();
        throw(new Exception("Failed Validation in class ".get_class($this)."."));
      }
    }
    catch(Exception $e)
    {
      $con->rollback();
      throw($e);
    }
  }
  
  function remove($GatewayUid)
  {
    $con = Propel::getConnection(GatewayPeer::DATABASE_NAME);
    try
    {
      $con->begin();
      $this->setGatUid($GatewayUid);
      $result=$this->delete();
      $con->commit();
      return $result;
    }
    catch(Exception $e)
    {
      $con->rollback();
      throw($e);
    }
  }
} // Gateway
