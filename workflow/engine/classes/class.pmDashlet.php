<?php

require_once 'interfaces/dashletInterface.php';
require_once 'model/Dashlet.php';
require_once 'model/DashletInstance.php';

class PMDashlet extends DashletInstance implements DashletInterface {

  // Own properties
  private $dashletObject;

  // Interface functions

  public function setup($dasInsUid) {
    try {
      $dashletInstance = $this->getDashletInstance($dasInsUid);
      G::LoadClass($dashletInstance['DAS_CLASS']);
      $this->dashletObject = new $dashletInstance['DAS_CLASS']();
      $this->dashletObject->setup($dashletInstance);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function render($width = 300) {
    try {
      if (is_null($this->dashletObject)) {
        throw new Exception('Please call to the function "setup" before call the function "render".');
      }
      $this->dashletObject->render($width);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  // Own functions

  public function getDashletsInstances($start = null, $limit = null) {
    try {
      $dashletsInstances = array();
      $criteria = new Criteria('workflow');
      $criteria->addSelectColumn('*');
      $criteria->addJoin(DashletInstancePeer::DAS_UID, DashletPeer::DAS_UID, Criteria::LEFT_JOIN);
      if (!is_null($start)) {
        $criteria->setOffset($start);
      }
      if (!is_null($limit)) {
        $criteria->setLimit($limit);
      }
      $dataset = DashletInstancePeer::doSelectRS($criteria);
      $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $dataset->next();
      while ($row = $dataset->getRow()) {
        $row['DAS_INS_STATUS_LABEL'] = ($row['DAS_INS_STATUS'] == '1' ? G::LoadTranslation('ID_ACTIVE') : G::LoadTranslation('ID_INACTIVE'));
        switch ($row['DAS_INS_OWNER_TYPE']) {
          case 'DEPARTMENT':
            require_once 'classes/model/Department.php';
            $departmentInstance = new Department();
            $department = $departmentInstance->load($row['DAS_INS_OWNER_UID']);
            $row['DAS_INS_OWNER_TITLE'] = $department['DEPO_TITLE'];
          break;
          default:
            $row['DAS_INS_OWNER_TITLE'] = $row['DAS_INS_OWNER_TYPE'];
          break;
        }
        $dashletsInstances[] = $row;
        $dataset->next();
      }
      return $dashletsInstances;
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletsInstancesQuantity() {
    try {
      $criteria = new Criteria('workflow');
      $criteria->addSelectColumn('*');
      $criteria->addJoin(DashletInstancePeer::DAS_UID, DashletPeer::DAS_UID, Criteria::LEFT_JOIN);
      return DashletInstancePeer::doCount($criteria);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletInstance($dasInsUid) {
    try {
      $dashletInstance = $this->load($dasInsUid);
      $dashlet = new Dashlet();
      $dashletFields = $dashlet->load($dashletInstance['DAS_UID']);
      return array_merge($dashletFields, $dashletInstance);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function saveDashletInstance($data) {
    try {
      $this->createOrUpdate($data);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function deleteDashletInstance($dasInsUid) {
    try {
      $this->remove($dasInsUid);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletsInstancesForUser($userUid) {
    try {
      $dashletsInstances = array();
      // Include required classes
      require_once 'classes/model/Department.php';
      // Check for "public" dashlets
      // ToDo: Next release
      // Check for the direct assignments
      // ToDo: Next release
      // Check for department assigments
      $departmentInstance = new Department();
      $departments = $departmentInstance->getDepartmentsForUser($userUid);
      foreach ($departments as $depUid => $department)  {
        $criteria = new Criteria('workflow');
        $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
        $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
        $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_CONTEXT_TIME);
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_TYPE, 'DEPARTMENT');
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_UID, $depUid);
        $dataset = DashletInstancePeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        while ($row = $dataset->getRow()) {
          if (!isset($dashletsInstances[$row['DAS_INS_UID']])) {
            $row['DAS_TITLE'] .= ' (' . $row['DAS_INS_CONTEXT_TIME'] . ')';
            $dashletsInstances[$row['DAS_INS_UID']] = $row;
          }
          $dataset->next();
        }
      }
      // Check for group assignments
      // ToDo: Next release
      // Check for role assigments
      // ToDo: Next release
      // Check for permission assigments
      // ToDo: Next release
      return array_values($dashletsInstances);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

}