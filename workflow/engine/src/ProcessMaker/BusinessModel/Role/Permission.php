<?php
namespace ProcessMaker\BusinessModel\Role;

class Permission
{
    private $arrayFieldDefinition = array(
        "ROL_UID" => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "roleUid"),
        "PER_UID" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "permissionUid")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array(
        "filter" => "FILTER",
        "start"  => "START",
        "limit"  => "LIMIT"
    );

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            foreach ($this->arrayFieldDefinition as $key => $value) {
                $this->arrayFieldNameForException[$value["fieldNameAux"]] = $key;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the format of the fields name (uppercase, lowercase)
     *
     * @param bool $flag Value that set the format
     *
     * return void
     */
    public function setFormatFieldNameInUppercase($flag)
    {
        try {
            $this->formatFieldNameInUppercase = $flag;

            $this->setArrayFieldNameForException($this->arrayFieldNameForException);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set exception messages for fields
     *
     * @param array $arrayData Data with the fields
     *
     * return void
     */
    public function setArrayFieldNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the name of the field according to the format
     *
     * @param string $fieldName Field name
     *
     * return string Return the field name according the format
     */
    public function getFieldNameByFormatFieldName($fieldName)
    {
        try {
            return ($this->formatFieldNameInUppercase)? strtoupper($fieldName) : strtolower($fieldName);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if it's assigned the Permission to Role
     *
     * @param string $roleUid               Unique id of Role
     * @param string $permissionUid         Unique id of Permission
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if it's assigned the Permission to Role
     */
    public function throwExceptionIfItsAssignedPermissionToRole($roleUid, $permissionUid, $fieldNameForException)
    {
        try {
            $obj = \RolesPermissionsPeer::retrieveByPK($roleUid, $permissionUid);

            if (!is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_PERMISSION_IS_ALREADY_ASSIGNED", array($fieldNameForException, $permissionUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if not it's assigned the Permission to Role
     *
     * @param string $roleUid               Unique id of Role
     * @param string $permissionUid         Unique id of Permission
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if not it's assigned the Permission to Role
     */
    public function throwExceptionIfNotItsAssignedPermissionToRole($roleUid, $permissionUid, $fieldNameForException)
    {
        try {
            $obj = \RolesPermissionsPeer::retrieveByPK($roleUid, $permissionUid);

            if (is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_PERMISSION_IS_NOT_ASSIGNED", array($fieldNameForException, $permissionUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign Permission to Role
     *
     * @param string $roleUid   Unique id of Role
     * @param array  $arrayData Data
     *
     * return array Return data of the Permission assigned to Role
     */
    public function create($roleUid, array $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["ROL_UID"]);

            //Verify data
            $role = new \ProcessMaker\BusinessModel\Role();

            $role->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, true);

            $process->throwExceptionIfNotExistsPermission($arrayData["PER_UID"], $this->arrayFieldNameForException["permissionUid"]);

            $this->throwExceptionIfItsAssignedPermissionToRole($roleUid, $arrayData["PER_UID"], $this->arrayFieldNameForException["permissionUid"]);

            if ($roleUid == "00000000000000000000000000000002") {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_PERMISSION_ROLE_PERMISSIONS_CAN_NOT_BE_CHANGED", array("PROCESSMAKER_ADMIN")));
            }

            //Create
            $role = new \Roles();

            $arrayData = array_merge(array("ROL_UID" => $roleUid), $arrayData);

            $role->assignPermissionRole($arrayData);

            //Return
            if (!$this->formatFieldNameInUppercase) {
                $arrayData = array_change_key_case($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Unassign Permission of the Role
     *
     * @param string $roleUid       Unique id of Role
     * @param string $permissionUid Unique id of Permission
     *
     * return void
     */
    public function delete($roleUid, $permissionUid)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $role = new \ProcessMaker\BusinessModel\Role();

            $role->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            $process->throwExceptionIfNotExistsPermission($permissionUid, $this->arrayFieldNameForException["permissionUid"]);

            $this->throwExceptionIfNotItsAssignedPermissionToRole($roleUid, $permissionUid, $this->arrayFieldNameForException["permissionUid"]);

            if ($roleUid == "00000000000000000000000000000002") {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_PERMISSION_ROLE_PERMISSIONS_CAN_NOT_BE_CHANGED", array("PROCESSMAKER_ADMIN")));
            }

            //Delete
            $role = new \Roles();

            $role->deletePermissionRole($roleUid, $permissionUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Permission
     *
     * @param string $roleUid                   Unique id of Role
     * @param array  $arrayPermissionUidExclude Unique id of Permissions to exclude
     *
     * return object
     */
    public function getPermissionCriteria($roleUid, array $arrayPermissionUidExclude = null)
    {
        try {
            $criteria = new \Criteria("rbac");

            $criteria->addSelectColumn(\PermissionsPeer::PER_UID);
            $criteria->addSelectColumn(\PermissionsPeer::PER_CODE);

            if ($roleUid != "") {
                $criteria->addJoin(\RolesPermissionsPeer::PER_UID, \PermissionsPeer::PER_UID, \Criteria::LEFT_JOIN);
                $criteria->add(\RolesPermissionsPeer::ROL_UID, $roleUid, \Criteria::EQUAL);
            }

            $criteria->add(\PermissionsPeer::PER_STATUS, 1, \Criteria::EQUAL);

            if (!is_null($arrayPermissionUidExclude) && is_array($arrayPermissionUidExclude)) {
                $criteria->add(\PermissionsPeer::PER_UID, $arrayPermissionUidExclude, \Criteria::NOT_IN);
            }

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Permission from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Permission
     */
    public function getPermissionDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("PER_UID")  => $record["PER_UID"],
                $this->getFieldNameByFormatFieldName("PER_CODE") => $record["PER_CODE"],
                $this->getFieldNameByFormatFieldName("PER_NAME") => $record["PER_NAME"]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Permissions of a Role
     *
     * @param string $roleUid         Unique id of Role
     * @param string $option          Option (PERMISSIONS, AVAILABLE-PERMISSIONS)
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Permissions of a Role
     */
    public function getPermissions($roleUid, $option, array $arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayPermission = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $role    = new \ProcessMaker\BusinessModel\Role();

            $role->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            $process->throwExceptionIfDataNotMetFieldDefinition(
                array("OPTION" => $option),
                array("OPTION" => array("type" => "string", "required" => true, "empty" => false, "defaultValues" => array("PERMISSIONS", "AVAILABLE-PERMISSIONS"), "fieldNameAux" => "option")),
                array("option" => "\$option"),
                true
            );

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayPermission;
            }

            //Set variables
            $rolePermission = new \RolesPermissions();

            //SQL
            switch ($option) {
                case "PERMISSIONS":
                    //Criteria
                    $criteria = $this->getPermissionCriteria($roleUid);
                    break;
                case "AVAILABLE-PERMISSIONS":
                    //Get Uids
                    $arrayUid = array();

                    $criteria = $this->getPermissionCriteria($roleUid);

                    $rsCriteria = \PermissionsPeer::doSelectRS($criteria);
                    $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

                    while ($rsCriteria->next()) {
                        $row = $rsCriteria->getRow();

                        $arrayUid[] = $row["PER_UID"];
                    }

                    //Criteria
                    $criteria = $this->getPermissionCriteria("", $arrayUid);
                    break;
            }

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(\PermissionsPeer::PER_CODE, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE);
            }

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);

                if (in_array($sortField, array("PER_UID", "PER_CODE"))) {
                    $sortField = \PermissionsPeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = \PermissionsPeer::PER_CODE;
                }
            } else {
                $sortField = \PermissionsPeer::PER_CODE;
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

            $rsCriteria = \PermissionsPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $rolePermission->setPerUid($row["PER_UID"]);
                $row["PER_NAME"] = $rolePermission->getPermissionName();

                $arrayPermission[] = $this->getPermissionDataFromRecord($row);
            }

            //Return
            return $arrayPermission;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

