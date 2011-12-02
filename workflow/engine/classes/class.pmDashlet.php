<?php

require_once 'interfaces/dashletInterface.php';
require_once 'model/Dashlet.php';
require_once 'model/DashletInstance.php';

class PMDashlet extends DashletInstance implements DashletInterface {

  // Own properties
  private $dashletInstance;
  private $dashletObject;

  // Interface functions

  public static function getAdditionalFields($className) {
    try {
      //Change this in the next release
      G::LoadClass($className);
      eval("\$additionalFields = $className::getAdditionalFields(\$className);");
      return $additionalFields;
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function setup($dasInsUid) {
    try {
      $this->dashletInstance = $this->loadDashletInstance($dasInsUid);
      G::LoadClass($this->dashletInstance['DAS_CLASS']);
      $this->dashletObject = new $this->dashletInstance['DAS_CLASS']();
      $this->dashletObject->setup($this->dashletInstance);
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

  // Getter and Setters

  public function getDashletInstance() {
    return $this->dashletInstance;
  }

  public function getDashletObject() {
    return $this->dashletObject;
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
          case 'EVERYBODY':
            $row['DAS_INS_OWNER_TITLE'] = G::LoadTranslation('ID_ALL_USERS');
          break;
          case 'USER':
            require_once 'classes/model/Users.php';
            $userInstance = new Users();
            $user = $userInstance->load($row['DAS_INS_OWNER_UID']);
            $row['DAS_INS_OWNER_TITLE'] = $user['USR_FIRSTNAME'] . ' ' . $user['USR_LASTNAME'];
          break;
          case 'DEPARTMENT':
            require_once 'classes/model/Department.php';
            $departmentInstance = new Department();
            $department = $departmentInstance->load($row['DAS_INS_OWNER_UID']);
            $row['DAS_INS_OWNER_TITLE'] = $department['DEPO_TITLE'];
          break;
          case 'GROUP':
            require_once 'classes/model/Groupwf.php';
            $groupInstance = new Groupwf();
            $group = $groupInstance->load($row['DAS_INS_OWNER_UID']);
            $row['DAS_INS_OWNER_TITLE'] = $group['GRP_TITLE'];
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

  public function loadDashletInstance($dasInsUid) {
    try {
      $dashletInstance = $this->load($dasInsUid);
      //Load data from the serialized field
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
      require_once 'classes/model/Users.php';
      // Check for "public" dashlets
      $criteria = new Criteria('workflow');
      $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
      $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
      $criteria->add(DashletInstancePeer::DAS_INS_OWNER_TYPE, 'EVERYBODY');
      $dataset = DashletInstancePeer::doSelectRS($criteria);
      $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $dataset->next();
      while ($row = $dataset->getRow()) {
        if (!isset($dashletsInstances[$row['DAS_INS_UID']])) {
          $dashletsInstances[$row['DAS_INS_UID']] = $row;
        }
        $dataset->next();
      }
      // Check for the direct assignments
      $usersInstance = new Users();
      $criteria = new Criteria('workflow');
      $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
      $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
      $criteria->add(DashletInstancePeer::DAS_INS_OWNER_TYPE, 'USER');
      $criteria->add(DashletInstancePeer::DAS_INS_OWNER_UID, $userUid);
      $dataset = DashletInstancePeer::doSelectRS($criteria);
      $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $dataset->next();
      while ($row = $dataset->getRow()) {
        if (!isset($dashletsInstances[$row['DAS_INS_UID']])) {
          $dashletsInstances[$row['DAS_INS_UID']] = $row;
        }
        $dataset->next();
      }
      // Check for department assigments
      $departmentInstance = new Department();
      $departments = $departmentInstance->getDepartmentsForUser($userUid);
      foreach ($departments as $depUid => $department) {
        $criteria = new Criteria('workflow');
        $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
        $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_TYPE, 'DEPARTMENT');
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_UID, $depUid);
        $dataset = DashletInstancePeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        while ($row = $dataset->getRow()) {
          if (!isset($dashletsInstances[$row['DAS_INS_UID']])) {
            $dashletsInstances[$row['DAS_INS_UID']] = $row;
          }
          $dataset->next();
        }
      }
      // Check for group assignments
      G::LoadClass('groups');
      $groupsInstance = new Groups();
      $groups = $groupsInstance->getGroupsForUser($userUid);
      foreach ($groups as $grpUid => $group) {
        $criteria = new Criteria('workflow');
        $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
        $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_TYPE, 'GROUP');
        $criteria->add(DashletInstancePeer::DAS_INS_OWNER_UID, $grpUid);
        $dataset = DashletInstancePeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        while ($row = $dataset->getRow()) {
          if (!isset($dashletsInstances[$row['DAS_INS_UID']])) {
            $dashletsInstances[$row['DAS_INS_UID']] = $row;
          }
          $dataset->next();
        }
      }
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