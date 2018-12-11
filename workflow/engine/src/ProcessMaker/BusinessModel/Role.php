<?php

namespace ProcessMaker\BusinessModel;

use Configurations;
use Content;
use Criteria;
use DateTime;
use Exception;
use G;
use ProcessMaker\Util\Common;
use ResultSet;
use Roles as ModelRoles;
use RolesPeer;
use UsersRolesPeer;


require_once PATH_RBAC . 'model' . PATH_SEP . 'Roles.php';

class Role
{
    private $arrayFieldDefinition = [
        'ROL_UID' => ['type' => 'string', 'required' => false, 'empty' => false, 'defaultValues' => [], 'fieldNameAux' => 'roleUid'],

        'ROL_CODE' => ['type' => 'string', 'required' => true, 'empty' => false, 'defaultValues' => [], 'fieldNameAux' => 'roleCode'],
        'ROL_NAME' => ['type' => 'string', 'required' => true, 'empty' => false, 'defaultValues' => [], 'fieldNameAux' => 'roleName'],
        'ROL_STATUS' => ['type' => 'string', 'required' => false, 'empty' => false, 'defaultValues' => ['ACTIVE', 'INACTIVE'], 'fieldNameAux' => 'roleStatus']
    ];

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = [
        'filter' => 'FILTER',
        'start' => 'START',
        'limit' => 'LIMIT'
    ];

    const SYSTEM_RBAC = '00000000000000000000000000000001';
    const SYSTEM_PROCESSMAKER = '00000000000000000000000000000002';

    /**
     * Role constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        try {
            foreach ($this->arrayFieldDefinition as $key => $value) {
                $this->arrayFieldNameForException[$value["fieldNameAux"]] = $key;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the format of the fields name (uppercase, lowercase)
     *
     * @param bool $flag Value that set the format
     *
     * @return void
     * @throws Exception
     */
    public function setFormatFieldNameInUppercase($flag)
    {
        try {
            $this->formatFieldNameInUppercase = $flag;

            $this->setArrayFieldNameForException($this->arrayFieldNameForException);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set exception messages for fields
     *
     * @param array $arrayData Data with the fields
     *
     * @return void
     * @throws Exception
     */
    public function setArrayFieldNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the name of the field according to the format
     *
     * @param string $fieldName Field name
     *
     * @return string Return the field name according the format
     * @throws Exception
     */
    public function getFieldNameByFormatFieldName($fieldName)
    {
        try {
            return ($this->formatFieldNameInUppercase) ? strtoupper($fieldName) : strtolower($fieldName);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the code of a Role
     *
     * @param string $roleCode Code
     * @param string $roleSystemUid Unique id of System (00000000000000000000000000000001: RBAC,
     *     00000000000000000000000000000002: PROCESSMAKER)
     * @param string $roleUidExclude Unique id of Role to exclude
     *
     * @return bool Return true if exists the code of a Role, false otherwise
     * @throws Exception
     */
    public function existsCode($roleCode, $roleSystemUid, $roleUidExclude = "")
    {
        try {
            $criteria = new Criteria("rbac");

            $criteria->addSelectColumn(RolesPeer::ROL_UID);

            $criteria->add(RolesPeer::ROL_SYSTEM, $roleSystemUid, Criteria::EQUAL);

            if (!empty($roleUidExclude)) {
                $criteria->add(RolesPeer::ROL_UID, $roleUidExclude, Criteria::NOT_EQUAL);
            }

            $criteria->add(RolesPeer::ROL_CODE, $roleCode, Criteria::EQUAL);

            $rsCriteria = RolesPeer::doSelectRS($criteria);

            if ($rsCriteria->next()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the name of a Role
     *
     * @param string $roleName Name
     * @param string $roleSystemUid Unique id of System (00000000000000000000000000000001: RBAC,
     *     00000000000000000000000000000002: PROCESSMAKER)
     * @param string $roleUidExclude Unique id of Role to exclude
     *
     * @return bool Return true if exists the name of a Role, false otherwise
     * @throws Exception
     */
    public function existsName($roleName, $roleSystemUid, $roleUidExclude = "")
    {
        try {
            //Set variables
            $content = new Content();
            $role = new ModelRoles();

            $arrayContentByRole = $content->getAllContentsByRole();

            //SQL
            $criteria = new Criteria("rbac");

            $criteria->addSelectColumn(RolesPeer::ROL_UID);

            $criteria->add(RolesPeer::ROL_SYSTEM, $roleSystemUid, Criteria::EQUAL);

            if ($roleUidExclude != "") {
                $criteria->add(RolesPeer::ROL_UID, $roleUidExclude, Criteria::NOT_EQUAL);
            }

            $rsCriteria = RolesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $roleUid = $row["ROL_UID"];

                if (isset($arrayContentByRole[$roleUid])) {
                    $roleNameAux = $arrayContentByRole[$roleUid];
                } else {
                    $rowAux = $role->load($roleUid);
                    $roleNameAux = $rowAux["ROL_NAME"];
                }

                if ($roleNameAux == $roleName) {
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exist the Role in table ROLES
     *
     * @param string $roleUid Unique id of Role
     * @param string $fieldNameForException Field name for the exception
     *
     * @return void Throw exception if does not exist the Role in table ROLES
     * @throws Exception
     */
    public function throwExceptionIfNotExistsRole($roleUid, $fieldNameForException)
    {
        try {
            $obj = RolesPeer::retrieveByPK($roleUid);

            if (is_null($obj)) {
                throw new Exception(G::LoadTranslation("ID_ROLE_DOES_NOT_EXIST", [$fieldNameForException, $roleUid]));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the code of a Role
     *
     * @param string $roleCode Code
     * @param string $roleSystemUid Unique id of System (00000000000000000000000000000001: RBAC,
     *     00000000000000000000000000000002: PROCESSMAKER)
     * @param string $fieldNameForException Field name for the exception
     * @param string $roleUidExclude Unique id of Role to exclude
     *
     * @return void Throw exception if exists the code of a Role
     * @throws Exception
     */
    public function throwExceptionIfExistsCode($roleCode, $roleSystemUid, $fieldNameForException, $roleUidExclude = "")
    {
        try {
            if ($this->existsCode($roleCode, $roleSystemUid, $roleUidExclude)) {
                throw new Exception(G::LoadTranslation("ID_ROLE_CODE_ALREADY_EXISTS", [$fieldNameForException, $roleCode]));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the name of a Role
     *
     * @param string $roleName Name
     * @param string $roleSystemUid Unique id of System (00000000000000000000000000000001: RBAC,
     *     00000000000000000000000000000002: PROCESSMAKER)
     * @param string $fieldNameForException Field name for the exception
     * @param string $roleUidExclude Unique id of Role to exclude
     *
     * @return void Throw exception if exists the name of a Role
     * @throws Exception
     */
    public function throwExceptionIfExistsName($roleName, $roleSystemUid, $fieldNameForException, $roleUidExclude = "")
    {
        try {
            if ($this->existsName($roleName, $roleSystemUid, $roleUidExclude)) {
                throw new Exception(G::LoadTranslation("ID_ROLE_NAME_ALREADY_EXISTS", [$fieldNameForException, $roleName]));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $roleUid Unique id of Role
     * @param array $arrayData Data
     *
     * @return void Throw exception if data has an invalid value
     * @throws Exception
     */
    public function throwExceptionIfDataIsInvalid($roleUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayRoleData = ($roleUid == "") ? [] : $this->getRole($roleUid, true);
            $flagInsert = ($roleUid == "") ? true : false;

            $arrayDataMain = array_merge($arrayRoleData, $arrayData);

            //Verify data - Field definition
            $process = new Process();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);

            //Verify data
            if (isset($arrayData["ROL_CODE"]) && !preg_match("/^\w+$/", $arrayData["ROL_CODE"])) {
                throw new Exception(G::LoadTranslation("ID_ROLE_FIELD_CANNOT_CONTAIN_SPECIAL_CHARACTERS", [$this->arrayFieldNameForException["roleCode"]]));
            }

            if (isset($arrayData["ROL_CODE"])) {
                $this->throwExceptionIfExistsCode($arrayData["ROL_CODE"], $arrayDataMain["ROL_SYSTEM"], $this->arrayFieldNameForException["roleCode"], $roleUid);
            }

            if (isset($arrayData["ROL_NAME"])) {
                $this->throwExceptionIfExistsName($arrayData["ROL_NAME"], $arrayDataMain["ROL_SYSTEM"], $this->arrayFieldNameForException["roleName"], $roleUid);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Role
     *
     * @param array $arrayData Data
     *
     * @return array Return data of the new Role created
     * @throws Exception
     */
    public function create(array $arrayData)
    {
        try {
            //Verify data
            $validator = new Validator();

            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData['ROL_UID']);

            $arrayData['ROL_SYSTEM'] = self::SYSTEM_PROCESSMAKER;

            //Verify data
            $this->throwExceptionIfDataIsInvalid('', $arrayData);

            //Create
            $role = new ModelRoles();

            $roleUid = Common::generateUID();

            $arrayData['ROL_UID'] = $roleUid;
            $arrayData['ROL_STATUS'] = isset($arrayData['ROL_STATUS']) ? $arrayData['ROL_STATUS'] === 'ACTIVE' ? 1 : 0 : 1;
            $arrayData['ROL_CREATE_DATE'] = date('Y-M-d H:i:s');
            $arrayData['ROL_UPDATE_DATE'] = date('Y-M-d H:i:s');

            $role->createRole($arrayData);

            //Return
            return $this->getRole($roleUid);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Role
     *
     * @param string $roleUid Unique id of Role
     * @param array $arrayData Data
     *
     * @return array Return data of the Role updated
     * @throws Exception
     */
    public function update($roleUid, array $arrayData)
    {
        try {
            //Verify data
            $validator = new Validator();

            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);
            $arrayDataBackup = $arrayData;

            $arrayRoleData = $this->getRole($roleUid);

            //Verify data
            $this->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException['roleUid']);

            if ($roleUid === self::SYSTEM_PROCESSMAKER) {
                throw new Exception(G::LoadTranslation('ID_ROLES_MSG'));
            }

            $this->throwExceptionIfDataIsInvalid($roleUid, $arrayData);

            //Update
            $role = new ModelRoles();

            $arrayData['ROL_UID'] = $roleUid;
            $arrayData['ROL_UPDATE_DATE'] = date('Y-M-d H:i:s');

            if (!isset($arrayData['ROL_NAME'])) {
                $arrayData['ROL_NAME'] = $arrayRoleData[$this->getFieldNameByFormatFieldName('ROL_NAME')];
            }

            if (isset($arrayData['ROL_STATUS'])) {
                $arrayData['ROL_STATUS'] = $arrayData['ROL_STATUS'] === 'ACTIVE' ? 1 : 0;
            }

            $role->updateRole($arrayData);

            $arrayData = $arrayDataBackup;

            //Return
            if (!$this->formatFieldNameInUppercase) {
                $arrayData = array_change_key_case($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Role
     *
     * @param string $roleUid Unique id of Role
     *
     * @return void
     * @throws Exception
     */
    public function delete($roleUid)
    {
        try {
            $role = new ModelRoles();

            //Verify data
            $this->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            if ($role->numUsersWithRole($roleUid) > 0) {
                throw new Exception(G::LoadTranslation("ID_ROLES_CAN_NOT_DELETE"));
            }

            //Delete
            $result = $role->removeRole($roleUid);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Role
     *
     * @return object
     * @throws Exception
     */
    public function getRoleCriteria()
    {
        try {
            $criteria = new Criteria("rbac");

            $criteria->addSelectColumn(RolesPeer::ROL_UID);
            $criteria->addSelectColumn(RolesPeer::ROL_PARENT);
            $criteria->addSelectColumn(RolesPeer::ROL_CODE);
            $criteria->addSelectColumn(RolesPeer::ROL_STATUS);
            $criteria->addSelectColumn(RolesPeer::ROL_SYSTEM);
            $criteria->addSelectColumn(RolesPeer::ROL_CREATE_DATE);
            $criteria->addSelectColumn(RolesPeer::ROL_UPDATE_DATE);

            $criteria->add(RolesPeer::ROL_SYSTEM, self::SYSTEM_PROCESSMAKER, Criteria::EQUAL); //PROCESSMAKER

            return $criteria;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Role from a record
     *
     * @param array $record Record
     *
     * @return array Return an array with data Role
     * @throws Exception
     */
    public function getRoleDataFromRecord(array $record)
    {
        try {
            $conf = new Configurations();
            $confEnvSetting = $conf->getFormats();

            $dateTime = new DateTime($record['ROL_CREATE_DATE']);
            $roleCreateDate = $dateTime->format($confEnvSetting['dateFormat']);

            $roleUpdateDate = '';

            if (!empty($record['ROL_UPDATE_DATE'])) {
                $dateTime = new DateTime($record['ROL_UPDATE_DATE']);
                $roleUpdateDate = $dateTime->format($confEnvSetting['dateFormat']);
            }

            return [
                $this->getFieldNameByFormatFieldName('ROL_UID') => $record['ROL_UID'],
                $this->getFieldNameByFormatFieldName('ROL_CODE') => $record['ROL_CODE'],
                $this->getFieldNameByFormatFieldName('ROL_NAME') => $record['ROL_NAME'],
                $this->getFieldNameByFormatFieldName('ROL_STATUS') => $record['ROL_STATUS'] . '' === '1' ? 'ACTIVE' : 'INACTIVE',
                $this->getFieldNameByFormatFieldName('ROL_SYSTEM') => $record['ROL_SYSTEM'],
                $this->getFieldNameByFormatFieldName('ROL_CREATE_DATE') => $roleCreateDate,
                $this->getFieldNameByFormatFieldName('ROL_UPDATE_DATE') => $roleUpdateDate,
                $this->getFieldNameByFormatFieldName('ROL_TOTAL_USERS') => (int)$record['ROL_TOTAL_USERS']
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Roles
     *
     * @param array $arrayFilterData Data of the filters
     * @param string $sortField Field name to sort
     * @param string $sortDir Direction of sorting (ASC, DESC)
     * @param int $start Start
     * @param int $limit Limit
     *
     * @return array Return an array with all Roles
     * @throws Exception
     */
    public function getRoles(array $arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayRole = [];

            //Verify data
            $process = new Process();

            $process->throwExceptionIfDataNotMetPagerVarDefinition(["start" => $start, "limit" => $limit], $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayRole;
            }

            //Set variables
            $content = new Content();
            $role = new ModelRoles();

            $arrayContentByRole = $content->getAllContentsByRole();

            //SQL
            $criteria = $this->getRoleCriteria();

            $criteria->addAsColumn("ROL_TOTAL_USERS", "(SELECT COUNT(" . UsersRolesPeer::ROL_UID . ") FROM " . UsersRolesPeer::TABLE_NAME . " WHERE " . UsersRolesPeer::ROL_UID . " = " . RolesPeer::ROL_UID . ")");

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(RolesPeer::ROL_CODE, "%" . $arrayFilterData["filter"] . "%", Criteria::LIKE);
            }

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);

                if (in_array($sortField, ["ROL_UID", "ROL_PARENT", "ROL_STATUS", "ROL_SYSTEM", "ROL_CREATE_DATE", "ROL_UPDATE_DATE"])) {
                    $sortField = RolesPeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = RolesPeer::ROL_CODE;
                }
            } else {
                $sortField = RolesPeer::ROL_CODE;
            }

            if (!is_null($sortDir) && trim($sortDir) != "" && strtoupper($sortDir) == "DESC") {
                $criteria->addDescendingOrderByColumn($sortField);
            } else {
                $criteria->addAscendingOrderByColumn($sortField);
            }

            if (!is_null($start)) {
                $criteria->setOffset((int)($start));
            }

            if (!is_null($limit)) {
                $criteria->setLimit((int)($limit));
            }

            $rsCriteria = RolesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $roleUid = $row["ROL_UID"];

                if (isset($arrayContentByRole[$roleUid])) {
                    $roleName = $arrayContentByRole[$roleUid];
                } else {
                    $rowAux = $role->load($roleUid);
                    $roleName = $rowAux["ROL_NAME"];
                }

                $row["ROL_NAME"] = $roleName;

                $arrayRole[] = $this->getRoleDataFromRecord($row);
            }

            //Return
            return $arrayRole;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Role
     *
     * @param string $roleUid Unique id of Role
     * @param bool $flagGetRecord Value that set the getting
     *
     * @return array Return an array with data of a Role
     * @throws Exception
     */
    public function getRole($roleUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            //Set variables
            if (!$flagGetRecord) {
                $content = new Content();
                $role = new ModelRoles();

                $arrayContentByRole = $content->getAllContentsByRole();
            }

            //Get data
            //SQL
            $criteria = $this->getRoleCriteria();

            if (!$flagGetRecord) {
                $criteria->addAsColumn("ROL_TOTAL_USERS", "(SELECT COUNT(" . UsersRolesPeer::ROL_UID . ") FROM " . UsersRolesPeer::TABLE_NAME . " WHERE " . UsersRolesPeer::ROL_UID . " = " . RolesPeer::ROL_UID . ")");
            }

            $criteria->add(RolesPeer::ROL_UID, $roleUid, Criteria::EQUAL);

            $rsCriteria = RolesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            if (!$flagGetRecord) {
                if (isset($arrayContentByRole[$roleUid])) {
                    $roleName = $arrayContentByRole[$roleUid];
                } else {
                    $rowAux = $role->load($roleUid);
                    $roleName = $rowAux["ROL_NAME"];
                }

                $row["ROL_NAME"] = $roleName;
            }

            //Return
            return (!$flagGetRecord) ? $this->getRoleDataFromRecord($row) : $row;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

