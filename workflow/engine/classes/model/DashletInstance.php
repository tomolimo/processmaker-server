<?php

require_once 'classes/model/om/BaseDashletInstance.php';


/**
 * Skeleton subclass for representing a row from the 'DASHLET_INSTANCE' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class DashletInstance extends BaseDashletInstance {

  public function load($dasInsUid) {
    try {
      $dashletInstance = DashletInstancePeer::retrieveByPK($dasInsUid);
      $fields = $dashletInstance->toArray(BasePeer::TYPE_FIELDNAME);
      if ($fields['DAS_INS_ADDITIONAL_PROPERTIES'] != '') {
        $fields = array_merge($fields, unserialize($fields['DAS_INS_ADDITIONAL_PROPERTIES']));
      }
      return $fields;
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function save($data) {
    $connection = Propel::getConnection(DashletInstancePeer::DATABASE_NAME);
    try {
      if (!isset($data['DAS_INS_UID'])) {
        $data['DAS_INS_UID'] = G::generateUniqueID();
        $dashletInstance = new DashletInstance();
      }
      else {
        $dashletInstance = DashletInstancePeer::retrieveByPK($data['DAS_INS_UID']);
      }
      $dashletInstance->fromArray($data, BasePeer::TYPE_FIELDNAME);
      if ($dashletInstance->validate()) {
        $connection->begin();
        $result = $dashletInstance->save();
        $connection->commit();
        return $data['DAS_INS_UID'];
      }
      else {
        $message = '';
        $validationFailures = $dashletInstance->getValidationFailures();
        foreach($validationFailures as $validationFailure) {
          $message .= $validationFailure->getMessage() . '. ';
        }
        throw(new Exception('Error trying to update: ' . $message));
      }
    }
    catch (Exception $error) {
      $connection->rollback();
      throw $error;
    }
  }

  public function remove($dasInsUid) {
    $connection = Propel::getConnection(DashletInstancePeer::DATABASE_NAME);
    try {
      $dashletInstance = DashletInstancePeer::retrieveByPK($dasInsUid);
      if (!is_null($dashletInstance)) {
        $connection->begin();
        $result = $dashletInstance->delete();
        $connection->commit();
        return $result;
      }
      else {
        throw new Exception('Error trying to delete: The row "' .  $dasInsUid. '" not exists.');
      }
    }
    catch (Exception $error) {
      $connection->rollback();
      throw $error;
    }
  }

} // DashletInstance
