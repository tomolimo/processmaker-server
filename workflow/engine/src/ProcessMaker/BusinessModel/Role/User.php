<?php
namespace ProcessMaker\BusinessModel\Role;

class User
{
    private $arrayFieldDefinition = array(
        "ROL_UID" => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "roleUid"),
        "USR_UID" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "userUid")
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
     * Verify if it's assigned the User to Role
     *
     * @param string $roleUid               Unique id of Role
     * @param string $userUid               Unique id of User
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if it's assigned the User to Role
     */
    public function throwExceptionIfItsAssignedUserToRole($roleUid, $userUid, $fieldNameForException)
    {
        try {
            $obj = \UsersRolesPeer::retrieveByPK($userUid, $roleUid);

            if (!is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_USER_IS_ALREADY_ASSIGNED", array($fieldNameForException, $userUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if not it's assigned the User to Role
     *
     * @param string $roleUid               Unique id of Role
     * @param string $userUid               Unique id of User
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if not it's assigned the User to Role
     */
    public function throwExceptionIfNotItsAssignedUserToRole($roleUid, $userUid, $fieldNameForException)
    {
        try {
            $obj = \UsersRolesPeer::retrieveByPK($userUid, $roleUid);

            if (is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_ROLE_USER_IS_NOT_ASSIGNED", array($fieldNameForException, $userUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign User to Role
     *
     * @param string $roleUid   Unique id of Role
     * @param array  $arrayData Data
     *
     * return array Return data of the User assigned to Role
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

            $process->throwExceptionIfNotExistsUser($arrayData["USR_UID"], $this->arrayFieldNameForException["userUid"]);

            $this->throwExceptionIfItsAssignedUserToRole($roleUid, $arrayData["USR_UID"], $this->arrayFieldNameForException["userUid"]);

            if ($arrayData["USR_UID"] == "00000000000000000000000000000001") {
                throw new \Exception(\G::LoadTranslation("ID_ADMINISTRATOR_ROLE_CANT_CHANGED"));
            }

            //Create
            $role = new \Roles();

            $arrayData = array_merge(array("ROL_UID" => $roleUid), $arrayData);

            $role->assignUserToRole($arrayData);

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
     * Unassign User of the Role
     *
     * @param string $roleUid Unique id of Role
     * @param string $userUid Unique id of User
     *
     * return void
     */
    public function delete($roleUid, $userUid)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $role = new \ProcessMaker\BusinessModel\Role();

            $role->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            $process->throwExceptionIfNotExistsUser($userUid, $this->arrayFieldNameForException["userUid"]);

            $this->throwExceptionIfNotItsAssignedUserToRole($roleUid, $userUid, $this->arrayFieldNameForException["userUid"]);

            if ($userUid == "00000000000000000000000000000001") {
                throw new \Exception(\G::LoadTranslation("ID_ADMINISTRATOR_ROLE_CANT_CHANGED"));
            }

            //Delete
            $role = new \Roles();

            $role->deleteUserRole($roleUid, $userUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for User
     *
     * @param string $roleUid             Unique id of Role
     * @param array  $arrayUserUidExclude Unique id of Users to exclude
     *
     * return object
     */
    public function getUserCriteria($roleUid, array $arrayUserUidExclude = null)
    {
        try {
            $criteria = new \Criteria("rbac");

            $criteria->addSelectColumn(\RbacUsersPeer::USR_UID);
            $criteria->addSelectColumn(\RbacUsersPeer::USR_USERNAME);
            $criteria->addSelectColumn(\RbacUsersPeer::USR_FIRSTNAME);
            $criteria->addSelectColumn(\RbacUsersPeer::USR_LASTNAME);
            $criteria->addSelectColumn(\RbacUsersPeer::USR_STATUS);

            $criteria->addAlias("USR", \RbacUsersPeer::TABLE_NAME);

            $arrayCondition = array();
            $arrayCondition[] = array(\RbacUsersPeer::USR_UID, "USR.USR_UID", \Criteria::EQUAL);
            $criteria->addJoinMC($arrayCondition, \Criteria::LEFT_JOIN);

            if ($roleUid != "") {
                $criteria->addJoin(\UsersRolesPeer::USR_UID, \RbacUsersPeer::USR_UID, \Criteria::LEFT_JOIN);
                $criteria->add(\UsersRolesPeer::ROL_UID, $roleUid, \Criteria::EQUAL);
            }

            $criteria->add("USR.USR_USERNAME", "", \Criteria::NOT_EQUAL);

            if (!is_null($arrayUserUidExclude) && is_array($arrayUserUidExclude)) {
                $criteria->add(\RbacUsersPeer::USR_UID, $arrayUserUidExclude, \Criteria::NOT_IN);
            }

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a User from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data User
     */
    public function getUserDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("USR_UID")       => $record["USR_UID"],
                $this->getFieldNameByFormatFieldName("USR_USERNAME")  => $record["USR_USERNAME"],
                $this->getFieldNameByFormatFieldName("USR_FIRSTNAME") => $record["USR_FIRSTNAME"] . "",
                $this->getFieldNameByFormatFieldName("USR_LASTNAME")  => $record["USR_LASTNAME"] . "",
                $this->getFieldNameByFormatFieldName("USR_STATUS")    => ($record["USR_STATUS"] . "" == "1")? "ACTIVE" : "INACTIVE"
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Users of a Role
     *
     * @param string $roleUid         Unique id of Role
     * @param string $option          Option (USERS, AVAILABLE-USERS)
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Users of a Role
     */
    public function getUsers($roleUid, $option, array $arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayUser = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $role    = new \ProcessMaker\BusinessModel\Role();

            $role->throwExceptionIfNotExistsRole($roleUid, $this->arrayFieldNameForException["roleUid"]);

            $process->throwExceptionIfDataNotMetFieldDefinition(
                array("OPTION" => $option),
                array("OPTION" => array("type" => "string", "required" => true, "empty" => false, "defaultValues" => array("USERS", "AVAILABLE-USERS"), "fieldNameAux" => "option")),
                array("option" => "\$option"),
                true
            );

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayUser;
            }

            //SQL
            switch ($option) {
                case "USERS":
                    //Criteria
                    $criteria = $this->getUserCriteria($roleUid);
                    break;
                case "AVAILABLE-USERS":
                    //Get Uids
                    $arrayUid = array();

                    $criteria = $this->getUserCriteria($roleUid);

                    $rsCriteria = \RbacUsersPeer::doSelectRS($criteria);
                    $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

                    while ($rsCriteria->next()) {
                        $row = $rsCriteria->getRow();

                        $arrayUid[] = $row["USR_UID"];
                    }

                    //Criteria
                    $criteria = $this->getUserCriteria("", $arrayUid);
                    break;
            }

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(
                    $criteria->getNewCriterion(\RbacUsersPeer::USR_USERNAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE)->addOr(
                    $criteria->getNewCriterion(\RbacUsersPeer::USR_FIRSTNAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE)->addOr(
                    $criteria->getNewCriterion(\RbacUsersPeer::USR_LASTNAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE)))
                );
            }

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);

                if (in_array($sortField, array("USR_UID", "USR_USERNAME", "USR_FIRSTNAME", "USR_LASTNAME", "USR_STATUS"))) {
                    $sortField = \RbacUsersPeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = \RbacUsersPeer::USR_USERNAME;
                }
            } else {
                $sortField = \RbacUsersPeer::USR_USERNAME;
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

            $rsCriteria = \RbacUsersPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $arrayUser[] = $this->getUserDataFromRecord($row);
            }

            //Return
            return $arrayUser;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

