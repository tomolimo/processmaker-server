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

} // DashletInstance
