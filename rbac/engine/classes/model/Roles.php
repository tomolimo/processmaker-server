<?php
/**
 * @access public
 */
require_once 'classes/model/Permissions.php';
require_once 'classes/model/Systems.php';
require_once 'classes/model/RolesPermissions.php';
require_once 'classes/model/RbacUsers.php';

require_once 'classes/model/om/BaseRoles.php';
require_once 'classes/model/om/BaseRbacUsers.php';
require_once 'classes/model/om/BaseUsersRoles.php';

require_once 'classes/model/Content.php';

/**
 * Skeleton subclass for representing a row from the 'ROLES' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package  rbac-classes-model
 */
class Roles extends BaseRoles
{

    public $rol_name;

    /**
     * Function load role by uid
     *
     * @param string $rolUid
     *
     * @return array
     * @throws Exception
     */
    public function load($rolUid)
    {
        try {
            $row = RolesPeer::retrieveByPK($rolUid);
            if ($row) {
                $aFields = $row->toArray(BasePeer::TYPE_FIELDNAME);
                $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
                $this->setNew(false);

                $this->getRolName();
                $aFields['ROL_NAME'] = !empty($this->rol_name) ? $this->rol_name : $this->getRolCode();

                return $aFields;
            } else {
                throw new Exception("The '$rolUid' row doesn't exist!");
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Load role by Code
     *
     * @param string $rolCode
     *
     * @return array
     * @throws Exception
     */
    function loadByCode($rolCode = '')
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->add(RolesPeer::ROL_CODE, $rolCode);
            $dataSet = RolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataSet->next();
            $row = $dataSet->getRow();

            $roles = new Roles();
            $roles->load($row['ROL_UID']);
            $row['ROL_NAME'] = $roles->getRolName();
            if (empty($row['ROL_NAME'])) {
                $row['ROL_NAME'] = $roles->getRolCode();
            }

            if (is_array($row)) {
                return $row;
            } else {
                throw new Exception("The role '$rolCode' doesn\'t exist!");
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Create criteria with all roles
     *
     * @param string $systemCode
     * @param string $filter
     *
     * @return Criteria
     * @throws Exception
     */
    function listAllRoles($systemCode = 'PROCESSMAKER', $filter = '')
    {
        try {

            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_PARENT);
            $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
            $criteria->addSelectColumn(SystemsPeer::SYS_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_CREATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_UPDATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_STATUS);
            $criteria->add(RolesPeer::ROL_UID, '', Criteria::NOT_EQUAL);
            $criteria->add(RolesPeer::ROL_CODE, RBAC::PROCESSMAKER_GUEST, Criteria::NOT_EQUAL);
            $criteria->add(SystemsPeer::SYS_CODE, $systemCode);
            $criteria->add(RolesPeer::ROL_CREATE_DATE, '', Criteria::NOT_EQUAL);
            $criteria->add(RolesPeer::ROL_UPDATE_DATE, '', Criteria::NOT_EQUAL);

            if (!empty($filter)) {
                $criteria->add(RolesPeer::ROL_CODE, '%' . $filter . '%', Criteria::LIKE);
            }
            $criteria->addJoin(RolesPeer::ROL_SYSTEM, SystemsPeer::SYS_UID);

            return $criteria;

        } catch (Exception $exception) {
            throw new Exception("Class ROLES::FATAL ERROR. Criteria with rbac Can't initialized ");
        }
    }

    /**
     * This function get all Roles filters
     * We can apply page and filters
     *
     * @param integer $start
     * @param integer $limit
     * @param string $filter
     *
     * @return array
     */
    function getAllRolesFilter($start, $limit, $filter = '')
    {
        $systemCode = 'PROCESSMAKER';
        $criteria = new Criteria('rbac');
        $result = [];

        $criteria->addSelectColumn('COUNT(*) AS CNT');
        $criteria->add(RolesPeer::ROL_UID, '', Criteria::NOT_EQUAL);
        $criteria->add(SystemsPeer::SYS_CODE, $systemCode);
        $criteria->add(RolesPeer::ROL_UID, ['', RBAC::PROCESSMAKER_GUEST_UID], Criteria::NOT_IN);
        $criteria->addJoin(RolesPeer::ROL_SYSTEM, SystemsPeer::SYS_UID);

        if (!empty($filter)) {
            $criteria->add(RolesPeer::ROL_CODE, '%' . $filter . '%', Criteria::LIKE);
        }
        $result['COUNTER'] = clone $criteria;

        $criteria->clearSelectColumns();
        $criteria->addSelectColumn(RolesPeer::ROL_UID);
        $criteria->addSelectColumn(RolesPeer::ROL_PARENT);
        $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
        $criteria->addSelectColumn(SystemsPeer::SYS_CODE);
        $criteria->addSelectColumn(RolesPeer::ROL_CODE);
        $criteria->addSelectColumn(RolesPeer::ROL_CREATE_DATE);
        $criteria->addSelectColumn(RolesPeer::ROL_UPDATE_DATE);
        $criteria->addSelectColumn(RolesPeer::ROL_STATUS);

        $criteria->setOffset($start);
        $criteria->setLimit($limit);

        $result['LIST'] = $criteria;
        return $result;
    }

    /**
     * Load roles by system code
     *
     * @param string $systemCode
     *
     * @return array
     * @throws Exception
     */
    function getAllRoles($systemCode = 'PROCESSMAKER')
    {
        try {
            $criteria = $this->listAllRoles($systemCode);
            $rs = RolesPeer::doSelectRS($criteria);
            $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            $rows = [];
            while ($rs->next()) {
                $row = $rs->getRow();
                $o = new Roles();
                $o->load($row['ROL_UID']);
                $row['ROL_NAME'] = $o->getRolName();
                if (empty($row['ROL_NAME'])) {
                    $row['ROL_NAME'] = $o->getRolCode();
                }
                $rows[] = $row;
            }
            return $rows;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Load all permissions by System Code
     *
     * @param string $systemCode
     *
     * @return Criteria
     * @throws Exception
     */
    function listAllPermissions($systemCode = 'PROCESSMAKER')
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(PermissionsPeer::PER_UID);
            $criteria->addSelectColumn(PermissionsPeer::PER_CODE);
            $criteria->addSelectColumn(PermissionsPeer::PER_CREATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_UPDATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_STATUS);
            $criteria->add(PermissionsPeer::PER_CODE, substr($systemCode, 0, 3) . '_%', Criteria::LIKE);

            return $criteria;

        } catch (Exception $exception) {
            throw new Exception("Class ROLES::FATAL ERROR. Criteria with rbac Can't initialized ");
        }
    }

    /**
     * Check if exists role in system
     *
     * @param string $rolCode
     * @param string $system
     *
     * @return array
     * @throws Exception
     */
    public function existsRolSystem($rolCode, $system)
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->add(RolesPeer::ROL_CODE, $rolCode);
            $criteria->add(RolesPeer::ROL_SYSTEM, $system);
            $dataSet = RolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $row = [];
            if ($dataSet->next()) {
                $row = $dataSet->getRow();
            }
            return $row;
        } catch (Exception $exception) {
            throw $exception;
        }

    }

    /**
     * Create role in system
     *
     * @param array $data
     *
     * @return array|int
     * @throws Exception
     */
    function createRole($data)
    {
        try {
            $con = Propel::getConnection(RolesPeer::DATABASE_NAME);
            $rolCode = $data['ROL_CODE'];
            $rolSystem = $data['ROL_SYSTEM'];
            $status = $fields['ROL_STATUS'] = 1 ? 'ACTIVE' : 'INACTIVE';
            $info = $this->existsRolSystem($rolCode, $rolSystem);
            if ($info) {
                return $info;
            }

            if (!isset($data['ROL_NAME'])) {
                $data['ROL_NAME'] = '';
            }
            if (!isset($data['ROL_CREATE_DATE'])) {
                $data['ROL_CREATE_DATE'] = date('Y-M-d H:i:s');
            }
            if (!isset($data['ROL_UPDATE_DATE'])) {
                $data['ROL_UPDATE_DATE'] = date('Y-M-d H:i:s');
            }
            $rolName = $data['ROL_NAME'];
            unset($data['ROL_NAME']);

            $obj = new Roles();
            $obj->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($obj->validate()) {
                $con->begin();
                $result = $obj->save();
                $con->commit();
                $obj->setRolName($rolName);
                G::auditLog('CreateRole', 'Role Name: ' . $rolName . ' - Role Code: ' . $data['ROL_CODE'] . ' - Role Status: ' . $status);
            } else {
                $exception = new Exception('Failed Validation in class ' . get_class($this) . '.');
                $exception->aValidationFailures = $this->getValidationFailures();
                throw $exception;
            }
            return $result;
        } catch (Exception $exception) {
            $con->rollback();
            throw $exception;
        }
    }

    /**
     * Update role
     *
     * @param array $fields
     *
     * @return int
     * @throws Exception
     */
    public function updateRole($fields)
    {
        try {
            $con = Propel::getConnection(RolesPeer::DATABASE_NAME);
            $con->begin();
            $this->load($fields['ROL_UID']);
            $rol_name = $fields['ROL_NAME'];
            unset($fields['ROL_NAME']);

            if (!isset($data['ROL_UPDATE_DATE'])) {
                $fields['ROL_UPDATE_DATE'] = date('Y-M-d H:i:s');
            }

            $this->fromArray($fields, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $result = $this->save();
                $con->commit();
                $this->setRolName($rol_name);
                $status = $fields['ROL_STATUS'] = 1 ? 'ACTIVE' : 'INACTIVE';

                $rolCode = isset($fields['ROL_CODE']) ? '- Role Code: ' . $fields['ROL_CODE'] : '';

                G::auditLog('UpdateRole', 'Role Name: ' . $rol_name . ' - Role ID: (' . $fields['ROL_UID'] . ') ' . $rolCode . ' - Role Status: ' . $status);

                return $result;
            } else {
                $con->rollback();
                throw new Exception('Failed Validation in class ' . get_class($this) . '.');
            }
        } catch (Exception $exception) {
            $con->rollback();
            throw $exception;
        }
    }

    /**
     * Remove Role by Uid
     *
     * @param $rolUid
     * @throws Exception
     */
    function removeRole($rolUid)
    {
        try {
            $con = Propel::getConnection(RolesPeer::DATABASE_NAME);
            $con->begin();
            $this->setRolUid($rolUid);
            $rol_name = $this->load($rolUid);
            Content::removeContent('ROL_NAME', '', $this->getRolUid());
            $result = $this->delete();
            $con->commit();
            G::auditLog('DeleteRole', 'Role Name: ' . $rol_name['ROL_NAME'] . ' Role UID: (' . $rolUid . ') ');
            return $result;
        } catch (Exception $exception) {
            $con->rollback();
            throw $exception;
        }
    }

    /**
     * Check if exists Role Code
     *
     * @param string $code
     * @return bool
     */
    function verifyNewRole($code)
    {
        $code = trim($code);
        $criteria = new Criteria('rbac');
        $criteria->addSelectColumn(RolesPeer::ROL_UID);
        $criteria->add(RolesPeer::ROL_CODE, $code);

        if (RolesPeer::doCount($criteria) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Load role information by uid
     *
     * @param string $rolUid
     *
     * @return array
     * @throws Exception
     */
    function loadById($rolUid)
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_PARENT);
            $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_CREATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_UPDATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_STATUS);
            $criteria->add(RolesPeer::ROL_UID, $rolUid);

            $result = RolesPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $row = [];

            if ($result->next()) {
                $row = $result->getRow();
                $o = RolesPeer::retrieveByPK($row['ROL_UID']);
                $row['ROL_NAME'] = $o->getRolName();
                if (empty($row['ROL_NAME'])) {
                    $row['ROL_NAME'] = $o->getRolCode();
                }
            }
            return $row;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get Role code by Role Uid
     *
     * @param string $rolUid
     *
     * @return string
     * @throws Exception
     */
    function getRoleCode($rolUid)
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->add(RolesPeer::ROL_UID, $rolUid);

            $result = RolesPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $role = '';
            if ($result->next()) {
                $row = $result->getRow();
                $role = $row['ROL_CODE'];
            }
            return $role;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Gets number of users by role
     *
     * @return array
     * @throws Exception
     */
    function getAllUsersByRole()
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(UsersRolesPeer::ROL_UID);
            $criteria->addSelectColumn('COUNT(*) AS CNT');
            $criteria->addJoin(RbacUsersPeer::USR_UID, UsersRolesPeer::USR_UID, Criteria::INNER_JOIN);
            $criteria->add(RbacUsersPeer::USR_STATUS, 0, Criteria::NOT_EQUAL);
            $criteria->addGroupByColumn(UsersRolesPeer::ROL_UID);
            $dataSet = UsersRolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $roles = [];
            while ($dataSet->next()) {
                $row = $dataSet->getRow();
                $roles[$row['ROL_UID']] = $row['CNT'];
            }
            return $roles;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Gets number of users by department
     *
     * @return array
     * @throws Exception
     */
    function getAllUsersByDepartment()
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->addSelectColumn(UsersPeer::DEP_UID);
            $criteria->addSelectColumn('COUNT(*) AS CNT');
            $criteria->add(UsersPeer::USR_STATUS, 'CLOSED', Criteria::NOT_EQUAL);
            $criteria->addGroupByColumn(UsersPeer::DEP_UID);
            $dataSet = UsersPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $departments = [];
            while ($dataSet->next()) {
                $row = $dataSet->getRow();
                $departments[$row['DEP_UID']] = $row['CNT'];
            }
            return $departments;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get users by role
     *
     * @param $rolUid
     * @param string $filter
     *
     * @throws Exception
     * @deprecated
     */
    function getRoleUsers($rolUid, $filter = '')
    {
        throw new Exception(__METHOD__ . ': The method is deprecated');
    }

    /**
     * Get all users by role
     *
     * @param $rolUid
     * @param string $filter
     *
     * @throws Exception
     * @deprecated
     */
    function getAllUsers($rolUid, $filter = '')
    {
        throw new Exception(__METHOD__ . ': The method is deprecated');
    }

    /**
     * Assign User to Role
     *
     * @param array $data
     *
     * @throws Exception
     */
    function assignUserToRole($data)
    {
        try {
            /*find the system uid for this role */
            require_once 'classes/model/Users.php';
            $criteria = new Criteria();
            $criteria->add(RolesPeer::ROL_UID, $data['ROL_UID']);
            $result = RolesPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $result->next();
            $row = $result->getRow();
            $systemId = $row['ROL_SYSTEM'];

            //updating the role into users table
            $criteria1 = new Criteria('workflow');
            $criteria1->add(UsersPeer::USR_UID, $data['USR_UID'], Criteria::EQUAL);
            $criteria2 = new Criteria('workflow');
            $criteria2->add(UsersPeer::USR_ROLE, $row['ROL_CODE']);
            BasePeer::doUpdate($criteria1, $criteria2, Propel::getConnection('workflow'));

            //delete roles for the same System
            $criteria = new Criteria();
            $criteria->addSelectColumn(UsersRolesPeer::USR_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
            $criteria->add(UsersRolesPeer::USR_UID, $data['USR_UID']);
            $criteria->add(RolesPeer::ROL_SYSTEM, $systemId);
            $criteria->addJoin(RolesPeer::ROL_UID, UsersRolesPeer::ROL_UID);
            $result = RolesPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            while ($result->next()) {
                $row = $result->getRow();
                $criteriaDelete = new Criteria();
                $criteriaDelete->add(UsersRolesPeer::USR_UID, $row['USR_UID']);
                $criteriaDelete->add(UsersRolesPeer::ROL_UID, $row['ROL_UID']);
                UsersRolesPeer::doDelete($criteriaDelete);
            }

            //save the unique role for this system
            $oUsersRoles = new UsersRoles();
            $oUsersRoles->setUsrUid($data['USR_UID']);
            $oUsersRoles->setRolUid($data['ROL_UID']);
            $oUsersRoles->save();

            $rol = $this->load($data['ROL_UID']);
            $userRbac = new RbacUsers();
            $user = $userRbac->load($data['USR_UID']);
            G::auditLog('AssignUserToRole', 'Assign user ' . $user['USR_USERNAME'] . ' (' . $data['USR_UID'] . ') to Role ' . $rol['ROL_NAME'] . ' (' . $data['ROL_UID'] . ') ');
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete a role from users
     *
     * @param string $rolUid
     * @param string $userUid
     *
     * @throws Exception
     */
    function deleteUserRole($rolUid, $userUid)
    {
        try {
            $criteria = new Criteria();
            $criteria->add(UsersRolesPeer::USR_UID, $userUid);

            if ($rolUid !== '%') {
                $criteria->add(UsersRolesPeer::ROL_UID, $rolUid);
            }
            UsersRolesPeer::doDelete($criteria);
            $rol = $this->load($rolUid);
            $userRbac = new RbacUsers();
            $user = $userRbac->load($userUid);

            G::auditLog('DeleteUserToRole', 'Delete user ' . $user['USR_USERNAME'] . ' (' . $userUid . ') to Role ' . $rol['ROL_NAME'] . ' (' . $rolUid . ') ');
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get permissions by Role Uid
     *
     * @param string $roleUid
     *
     * @return ResultSet
     * @throws Exception
     */
    function getRolePermissionsByPerUid($roleUid)
    {
        try {
            $criteria = new Criteria();
            $criteria->addSelectColumn(RolesPermissionsPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPermissionsPeer::PER_UID);
            $criteria->add(RolesPermissionsPeer::PER_UID, $roleUid);

            $dataSet = RolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            return $dataSet;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Checks if a permission is already assigned to a Role
     *
     * @param string $rolUid
     * @param string $perUid
     *
     * @return bool
     * @throws Exception
     */
    function getPermissionAssignedRole($rolUid, $perUid)
    {
        try {
            $criteria = new Criteria();
            $criteria->addSelectColumn(RolesPermissionsPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPermissionsPeer::PER_UID);
            $criteria->add(RolesPermissionsPeer::ROL_UID, $rolUid, Criteria::EQUAL);
            $criteria->add(RolesPermissionsPeer::PER_UID, $perUid, Criteria::EQUAL);

            $dataSet = RolesPermissionsPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataSet->next();
            if ($rowRP = $dataSet->getRow()) {
                return true;
            }
            return false;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get role information and permissions
     *
     * @param string $rolUid
     * @param string $filter
     * @param int $status
     *
     * @return ResultSet
     * @throws Exception
     */
    function getRolePermissions($rolUid, $filter = '', $status = null)
    {
        try {
            $criteria = new Criteria();
            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_PARENT);
            $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_CREATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_UPDATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_STATUS);
            $criteria->addSelectColumn(PermissionsPeer::PER_UID);
            $criteria->addSelectColumn(PermissionsPeer::PER_CODE);
            $criteria->addSelectColumn(PermissionsPeer::PER_CREATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_UPDATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_STATUS);
            $criteria->add(RolesPeer::ROL_UID, "", Criteria::NOT_EQUAL);
            $criteria->add(RolesPeer::ROL_UID, $rolUid);
            $criteria->addJoin(RolesPeer::ROL_UID, RolesPermissionsPeer::ROL_UID);
            $criteria->addJoin(RolesPermissionsPeer::PER_UID, PermissionsPeer::PER_UID);

            if (!empty($filter)) {
                $criteria->add(PermissionsPeer::PER_CODE, '%' . $filter . '%', Criteria::LIKE);
            }

            if (!is_null($status) && ($status === 1 || $status === 0)) {
                $criteria->add(PermissionsPeer::PER_STATUS, $status);
            }

            $dataSet = RolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            return $dataSet;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get all permissions by Role Uid
     *
     * @param string $rolUid
     * @param string $perSystem
     * @param string $filter
     * @param int $status
     *
     * @return ResultSet
     * @throws Exception
     */
    function getAllPermissions($rolUid, $perSystem = '', $filter = '', $status = null)
    {
        try {
            $criteria = new Criteria();
            $criteria->addSelectColumn(PermissionsPeer::PER_UID);
            $criteria->add(RolesPeer::ROL_UID, $rolUid);
            $criteria->addJoin(RolesPeer::ROL_UID, RolesPermissionsPeer::ROL_UID);
            $criteria->addJoin(RolesPermissionsPeer::PER_UID, PermissionsPeer::PER_UID);

            $result = PermissionsPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $result->next();

            $a = [RBAC::PM_GUEST_CASE_UID];
            while ($row = $result->getRow()) {
                $a[] = $row['PER_UID'];
                $result->next();
            }

            $criteria = new Criteria();
            $criteria->addSelectColumn(PermissionsPeer::PER_UID);
            $criteria->addSelectColumn(PermissionsPeer::PER_CODE);
            $criteria->addSelectColumn(PermissionsPeer::PER_CREATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_UPDATE_DATE);
            $criteria->addSelectColumn(PermissionsPeer::PER_STATUS);
            $criteria->addSelectColumn(PermissionsPeer::PER_SYSTEM);
            $criteria->addSelectColumn(SystemsPeer::SYS_CODE);
            $criteria->add(PermissionsPeer::PER_UID, $a, Criteria::NOT_IN);
            if (!empty($perSystem)) {
                $criteria->add(SystemsPeer::SYS_CODE, $perSystem);
            }
            $criteria->addJoin(PermissionsPeer::PER_SYSTEM, SystemsPeer::SYS_UID);

            if (!empty($filter)) {
                $criteria->add(PermissionsPeer::PER_CODE, '%' . $filter . '%', Criteria::LIKE);
            }

            if (!is_null($status) && ($status == 1 || $status == 0)) {
                $criteria->add(PermissionsPeer::PER_STATUS, $status);
            }

            $dataSet = PermissionsPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            return $dataSet;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Assign Permission to role
     *
     * @param array $data
     *
     * @throws Exception
     */
    function assignPermissionRole($data)
    {
        try {
            $rolePermission = new RolesPermissions();
            $rolePermission->setPerUid($data['PER_UID']);
            $rolePermission->setRolUid($data['ROL_UID']);
            if (isset($data['PER_NAME'])) {
                $rolePermission->setPermissionName($data['PER_NAME']);
            }
            $permission = $rolePermission->getPermissionName($data['PER_UID']);
            $role = $this->load($data['ROL_UID']);
            $rolePermission->save();
            G::auditLog('AddPermissionToRole', 'Add Permission ' . $permission . ' (' . $data['PER_UID'] . ') to Role ' . $role['ROL_NAME'] . ' (' . $data['ROL_UID'] . ') ');
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete permission from a role
     *
     * @param string $rolUid
     * @param string $perUid
     *
     * @throws Exception
     */
    function deletePermissionRole($rolUid, $perUid)
    {
        try {
            $criteria = new Criteria();
            $criteria->add(RolesPermissionsPeer::ROL_UID, $rolUid);
            $criteria->add(RolesPermissionsPeer::PER_UID, $perUid);
            RolesPermissionsPeer::doDelete($criteria);

            $rolePermission = new RolesPermissions();
            $rolePermission->setPerUid($perUid);
            $permission = $rolePermission->getPermissionName($perUid);
            $role = $this->load($rolUid);

            G::auditLog('DeletePermissionToRole', 'Delete Permission ' . $permission . ' (' . $perUid . ') from Role ' . $role['ROL_NAME'] . ' (' . $rolUid . ') ');
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Count users with a specific role
     *
     * @param string $rolUid
     *
     * @return int
     */
    function numUsersWithRole($rolUid)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn(RbacUsersPeer::USR_UID);
        $criteria->add(RolesPeer::ROL_UID, '', Criteria::NOT_EQUAL);
        $criteria->add(RolesPeer::ROL_UID, $rolUid);
        $criteria->addJoin(RolesPeer::ROL_UID, UsersRolesPeer::ROL_UID);
        $criteria->addJoin(UsersRolesPeer::USR_UID, RbacUsersPeer::USR_UID);

        return RolesPeer::doCount($criteria);
    }

    /**
     * Check if already exists a Role Code
     *
     * @param string $rolCode
     *
     * @return int
     *
     * @throws Exception
     */
    function verifyByCode($rolCode = '')
    {
        try {
            $criteria = new Criteria('rbac');
            $criteria->add(RolesPeer::ROL_CODE, $rolCode);
            $dataSet = RolesPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataSet->next();
            $row = $dataSet->getRow();
            if ($row) {
                return 1;
            }
            return 0;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get Role name
     *
     * @return string
     *
     * @throws Exception
     */
    public function getRolName()
    {
        if (empty($this->getRolUid())) {
            throw new Exception("Error in getRolName, the ROL_UID can't be blank");
        }
        $lang = defined('SYS_LANG') ? SYS_LANG : 'en';
        $this->rol_name = Content::load('ROL_NAME', '', $this->getRolUid(), $lang);
        return $this->rol_name;
    }

    /**
     * Save Role name in content
     *
     * @param string $roleName
     *
     * @throws Exception
     */
    public function setRolName($roleName)
    {
        if ($this->getRolUid() == '') {
            throw new Exception("Error in setProTitle, the PRO_UID can't be blank");
        }
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($roleName !== null && !is_string($roleName)) {
            $roleName = (string)$roleName;
        }

        if ($this->rol_name !== $roleName || $roleName === '') {
            $this->rol_name = $roleName;
            $lang = defined('SYS_LANG') ? SYS_LANG : 'en';
            $res = Content::addContent('ROL_NAME', '', $this->getRolUid(), $lang, $this->rol_name);
        }
    }
} // Roles
