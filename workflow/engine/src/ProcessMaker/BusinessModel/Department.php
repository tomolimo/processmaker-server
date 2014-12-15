<?php
namespace ProcessMaker\BusinessModel;

use \G;
use \UsersPeer;
use \DepartmentPeer;

/**
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 */
class Department
{
    /**
     * Get list for Departments
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getDepartments()
    {
        $oDepartment = new \Department();
        $aDepts = $oDepartment->getDepartments('');
        foreach ($aDepts as &$depData) {
            $depData['DEP_CHILDREN'] = $this->getChildren($depData);
            $depData = array_change_key_case($depData, CASE_LOWER);
        }
        return $aDepts;
    }

    /**
     * Get list for Assigned User
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getAssignedUser($dep_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $oDept = new \Department();
        $oDept->Load( $dep_uid );
        $manager = $oDept->getDepManager();
        $oCriteria = new \Criteria( 'workflow' );
        $oCriteria->addSelectColumn( UsersPeer::USR_UID );
        $oCriteria->addSelectColumn( UsersPeer::USR_USERNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_FIRSTNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_LASTNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_STATUS );
        $oCriteria->add( UsersPeer::DEP_UID, '' );
        $oCriteria->add( UsersPeer::USR_STATUS, 'CLOSED', \Criteria::NOT_EQUAL );
        $oCriteria->add( UsersPeer::DEP_UID, $dep_uid );
        $oDataset = UsersPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
        $aUsers = array ();
        while ($oDataset->next()) {
            $dataTemp = $oDataset->getRow();
            $aUsers[] = array_change_key_case($dataTemp, CASE_LOWER);
            $index = sizeof( $aUsers ) - 1;
            $aUsers[$index]['usr_supervisor'] = ($manager == $aUsers[$index]['usr_uid']) ? true : false;
        }
        return $aUsers;
    }

    /**
     * Get list for Available User
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getAvailableUser($dep_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $oCriteria = new \Criteria( 'workflow' );
        $oCriteria->addSelectColumn( UsersPeer::USR_UID );
        $oCriteria->addSelectColumn( UsersPeer::USR_USERNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_FIRSTNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_LASTNAME );
        $oCriteria->addSelectColumn( UsersPeer::USR_STATUS );
        $oCriteria->add( UsersPeer::DEP_UID, '' );
        $oCriteria->add( UsersPeer::USR_STATUS, 'CLOSED', \Criteria::NOT_EQUAL );

        $oDataset = UsersPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
        $aUsers = array ();
        while ($oDataset->next()) {
            $dataTemp = $oDataset->getRow();
            $aUsers[] = array_change_key_case($dataTemp, CASE_LOWER);
        }
        return $aUsers;
    }

    /**
     * Put Assign User
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function assignUser($dep_uid, $usr_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $usr_uid = Validator::usrUid($usr_uid);

        $dep = new \Department();
        $dep->load($dep_uid);
        $dep_manager = $dep->getDepManager();
        $manager = ($dep_manager == '') ? true : false;
        $dep->addUserToDepartment( $dep_uid, $usr_uid, $manager, false );
        $dep->updateDepartmentManager( $dep_uid );
    }

    /**
     * Post Unassign User
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function unassignUser($dep_uid, $usr_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $usr_uid = Validator::usrUid($usr_uid);

        $dep = new \Department();
        $dep->load( $dep_uid );
        $manager = $dep->getDepManager();
        $dep->removeUserFromDepartment( $dep_uid, $usr_uid );
        if ($usr_uid == $manager) {
            $editDepto['DEP_UID'] = $dep_uid;
            $editDepto['DEP_MANAGER'] = '';
            $dep->update( $editDepto );
            $dep->updateDepartmentManager($dep_uid);
        }
    }

    /**
     * Put Set Manager User
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function setManagerUser($dep_uid, $usr_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $usr_uid = Validator::usrUid($usr_uid);

        $editDepartment['DEP_UID'] = $dep_uid;
        $editDepartment['DEP_MANAGER'] = $usr_uid;
        $oDept = new \Department();
        $oDept->update( $editDepartment );
        $oDept->updateDepartmentManager( $dep_uid );
    }

    /**
     * Get list for Departments
     * @var string $dep_uid. Uid for Department
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getDepartment($dep_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $criteria = new \Criteria( 'workflow' );
        $criteria->add( DepartmentPeer::DEP_UID, $dep_uid, \Criteria::EQUAL );
        $con = \Propel::getConnection( DepartmentPeer::DATABASE_NAME );
        $objects = DepartmentPeer::doSelect( $criteria, $con );
        $oUsers = new \Users();

        $node = array ();
        foreach ($objects as $oDepartment) {
            $node['DEP_UID'] = $oDepartment->getDepUid();
            $node['DEP_PARENT'] = $oDepartment->getDepParent();
            $node['DEP_TITLE'] = $oDepartment->getDepTitle();
            $node['DEP_STATUS'] = $oDepartment->getDepStatus();
            $node['DEP_MANAGER'] = $oDepartment->getDepManager();
            $node['DEP_LDAP_DN'] = $oDepartment->getDepLdapDn();
            $node['DEP_LAST'] = 0;

            $manager = $oDepartment->getDepManager();
            if ($manager != '') {
                $UserUID = $oUsers->load($manager);
                $node['DEP_MANAGER_USERNAME'] = isset( $UserUID['USR_USERNAME'] ) ? $UserUID['USR_USERNAME'] : '';
                $node['DEP_MANAGER_FIRSTNAME'] = isset( $UserUID['USR_FIRSTNAME'] ) ? $UserUID['USR_FIRSTNAME'] : '';
                $node['DEP_MANAGER_LASTNAME'] = isset( $UserUID['USR_LASTNAME'] ) ? $UserUID['USR_LASTNAME'] : '';
            } else {
                $node['DEP_MANAGER_USERNAME'] = '';
                $node['DEP_MANAGER_FIRSTNAME'] = '';
                $node['DEP_MANAGER_LASTNAME'] = '';
            }

            $criteriaCount = new \Criteria( 'workflow' );
            $criteriaCount->clearSelectColumns();
            $criteriaCount->addSelectColumn( 'COUNT(*)' );
            $criteriaCount->add( DepartmentPeer::DEP_PARENT, $oDepartment->getDepUid(), \Criteria::EQUAL );
            $rs = DepartmentPeer::doSelectRS( $criteriaCount );
            $rs->next();
            $row = $rs->getRow();
            $node['HAS_CHILDREN'] = $row[0];
        }
        $node = array_change_key_case($node, CASE_LOWER);
        return $node;
    }

    /**
     * Save Department
     * @var string $dep_data. Data for Process
     * @var string $create. Flag for create or update
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function saveDepartment($dep_data, $create = true)
    {
        Validator::isArray($dep_data, '$dep_data');
        Validator::isNotEmpty($dep_data, '$dep_data');
        Validator::isBoolean($create, '$create');

        $dep_data = array_change_key_case($dep_data, CASE_UPPER);
        $oDepartment = new \Department();
        if (isset($dep_data['DEP_UID']) && $dep_data['DEP_UID'] != '') {
            Validator::depUid($dep_data['DEP_UID']);
        }
        if (isset($dep_data['DEP_PARENT']) && $dep_data['DEP_PARENT'] != '') {
            Validator::depUid($dep_data['DEP_PARENT'], 'dep_parent');
        }
        if (isset($dep_data['DEP_MANAGER']) && $dep_data['DEP_MANAGER'] != '') {
            Validator::usrUid($dep_data['DEP_MANAGER'], 'dep_manager');
        }
        if (isset($dep_data['DEP_STATUS'])) {
            Validator::depStatus($dep_data['DEP_STATUS']);
        }

        if (!$create) {
            $dep_data['DEPO_TITLE'] = $dep_data['DEP_TITLE'];
            if (isset($dep_data['DEP_TITLE'])) {
                Validator::depTitle($dep_data['DEP_TITLE'], $dep_data['DEP_UID']);
            }
            $oDepartment->update($dep_data);
            $oDepartment->updateDepartmentManager($dep_data['DEP_UID']);
        } else {
            if (isset($dep_data['DEP_TITLE'])) {
                Validator::depTitle($dep_data['DEP_TITLE']);
            } else {
                throw (new \Exception(\G::LoadTranslation("ID_FIELD_REQUIRED", array('dep_title'))));
            }
            $dep_uid = $oDepartment->create($dep_data);
            $response = $this->getDepartment($dep_uid);
            return $response;
        }
    }

    /**
     * Delete department
     * @var string $dep_uid. Uid for department
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function deleteDepartment($dep_uid)
    {
        $dep_uid = Validator::depUid($dep_uid);
        $oDepartment = new \Department();
        $countUsers = $oDepartment->cantUsersInDepartment($dep_uid);
        if ($countUsers != 0) {
            throw (new \Exception(\G::LoadTranslation("ID_CANT_DELETE_DEPARTMENT_HAS_USERS")));
        }
        $dep_data = $this->getDepartment($dep_uid);
        if ($dep_data['has_children'] != 0) {
            throw (new \Exception(\G::LoadTranslation("ID_CANT_DELETE_DEPARTMENT_HAS_CHILDREN")));
        }
        $oDepartment->remove($dep_uid);
    }

    /**
     * Look for Children for department
     * @var array $dataDep. Data for child department
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    protected function getChildren ($dataDep)
    {
        $children = array();
        if ((int)$dataDep['HAS_CHILDREN'] > 0) {
            $oDepartment = new \Department();
            $aDepts = $oDepartment->getDepartments($dataDep['DEP_UID']);
            foreach ($aDepts as &$depData) {
                $depData['DEP_CHILDREN'] = $this->getChildren($depData);
                $depData = array_change_key_case($depData, CASE_LOWER);
                $children[] = $depData;
            }
        }
        return $children;
    }
}

