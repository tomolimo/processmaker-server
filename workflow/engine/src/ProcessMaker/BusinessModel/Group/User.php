<?php
namespace ProcessMaker\BusinessModel\Group;

class User
{
    private $arrayFieldDefinition = array(
        "GRP_UID" => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "groupUid"),
        "USR_UID" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "userUid")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array();

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
    public function setArrayFieldNameForException($arrayData)
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
     * Verify if doesn't exist the User in Group
     *
     * @param string $groupUid              Unique id of Group
     * @param string $userUid               Unique id of User
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if doesn't exist the User in Group
     */
    public function throwExceptionIfNotExistsGroupUser($groupUid, $userUid, $fieldNameForException)
    {
        try {
            $obj = \GroupUserPeer::retrieveByPK($groupUid, $userUid);

            if (!(is_object($obj) && get_class($obj) == "GroupUser")) {
                throw new \Exception(\G::LoadTranslation("ID_GROUP_USER_IS_NOT_ASSIGNED", array($fieldNameForException, $userUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the User in Group
     *
     * @param string $groupUid              Unique id of Group
     * @param string $userUid               Unique id of User
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if exists the User in Group
     */
    public function throwExceptionIfExistsGroupUser($groupUid, $userUid, $fieldNameForException)
    {
        try {
            $obj = \GroupUserPeer::retrieveByPK($groupUid, $userUid);

            if (is_object($obj) && get_class($obj) == "GroupUser") {
                throw new \Exception(\G::LoadTranslation("ID_GROUP_USER_IS_ALREADY_ASSIGNED", array($fieldNameForException, $userUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign User to Group
     *
     * @param string $groupUid  Unique id of Group
     * @param array  $arrayData Data
     *
     * return array Return data of the User assigned to Group
     */
    public function create($groupUid, $arrayData)
    {
        try {
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["GRP_UID"]);

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $group = new \ProcessMaker\BusinessModel\Group();

            $group->throwExceptionIfNotExistsGroup($groupUid, $this->arrayFieldNameForException["groupUid"]);

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, true);

            $process->throwExceptionIfNotExistsUser($arrayData["USR_UID"], $this->arrayFieldNameForException["userUid"]);

            $this->throwExceptionIfExistsGroupUser($groupUid, $arrayData["USR_UID"], $this->arrayFieldNameForException["userUid"]);

            //Create
            $group = new \Groups();

            $group->addUserToGroup($groupUid, $arrayData["USR_UID"]);

            //Return
            $arrayData = array_merge(array("GRP_UID" => $groupUid), $arrayData);

            if (!$this->formatFieldNameInUppercase) {
                $arrayData = array_change_key_case($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign users to groups
     *
     * @param array  $arrayData Data of users and groups
     *
     * return array Return result
     */
    public function createBatch(array $arrayData)
    {
        try {
            //Verify data
            if (empty($arrayData)) {
                throw new \Exception(\G::LoadTranslation('ID_INFORMATION_EMPTY'));
            }

            $arrayAux = [];

            foreach ($arrayData as $value) {
                $arrayAux = $value;

                if (!isset($arrayAux['groupUid'])){
                    throw new \Exception(\G::LoadTranslation('ID_DOES_NOT_EXIST', ['groupUid']));
                }

                if (gettype($arrayAux['groupUid']) != 'string'){
                    throw new \Exception(\G::LoadTranslation('ID_INVALID_VALUE_STRING', ['groupUid']));
                }

                if (!isset($arrayAux['users'])) {
                   throw new \Exception(\G::LoadTranslation('ID_DOES_NOT_EXIST', ['users']));
                }

                if(gettype($arrayAux['users']) != 'array') {
                    throw new \Exception(\G::LoadTranslation('ID_INVALID_VALUE_ARRAY', ['users']));
                }
            }

            //Assign
            $group = new \Groupwf();

            $arrayResult = [];
            $arrayUsrGrp = [];

            foreach ($arrayData as $value) {
                $flagAssignGrp = 1;
                $arrayMsg = [];

                $arrayUsrGrp = $value;

                //Verify data
                $grpUid = \GroupwfPeer::retrieveByPK($arrayUsrGrp['groupUid']);

                if (is_null($grpUid)) {
                    $arrayMsg['groupUid'] = [$arrayUsrGrp['groupUid'] => 'GROUP_NOT_EXISTS'];
                    $flagAssignGrp = 0;
                }

                if ($flagAssignGrp == 1) {
                    $arrayMsg['groupUid'] = [$arrayUsrGrp['groupUid'] => 'GROUP_EXISTS'];

                    $arrayUsr = $arrayUsrGrp['users'];
                    $arrayResultUsr = [];

                    foreach ($arrayUsr as $valueUidUser) {
                        $flagAssignUsr = 1;

                        //Verify data
                        $userUid = \UsersPeer::retrieveByPK($valueUidUser);

                        if (is_null($userUid)) {
                            $arrayResultUsr[$valueUidUser] = 'USER_NOT_EXISTS';
                            $flagAssignUsr = 0;
                        }

                        if ($flagAssignUsr == 1 && $userUid->getUsrStatus() == 'CLOSED') {
                            $arrayResultUsr[$valueUidUser] = 'USER_INACTIVE';
                            $flagAssignUsr = 0;
                        }

                        $groupUser = \GroupUserPeer::retrieveByPK($arrayUsrGrp['groupUid'], $valueUidUser);

                        if ($flagAssignUsr == 1 && !is_null($groupUser)) {
                            $arrayResultUsr[$valueUidUser] = 'USER_ALREADY_ASSIGNED';
                            $flagAssignUsr = 0;
                        }

                        //Assign
                        if ($flagAssignUsr == 1) {
                            $group = new \Groups();

                            $group->addUserToGroup($arrayUsrGrp['groupUid'], $valueUidUser);

                            $arrayResultUsr[$valueUidUser] = 'USER_SUCCESSFULLY_ASSIGNED';
                        }

                        $arrayMsg['users'] = $arrayResultUsr;
                    }
                }

                $arrayResult[] = $arrayMsg;
            }

            //Return
            return $arrayResult;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Unassign User of the Group
     *
     * @param string $groupUid Unique id of Group
     * @param string $userUid  Unique id of User
     *
     * return void
     */
    public function delete($groupUid, $userUid)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $group = new \ProcessMaker\BusinessModel\Group();

            $group->throwExceptionIfNotExistsGroup($groupUid, $this->arrayFieldNameForException["groupUid"]);

            $process->throwExceptionIfNotExistsUser($userUid, $this->arrayFieldNameForException["userUid"]);

            $this->throwExceptionIfNotExistsGroupUser($groupUid, $userUid, $this->arrayFieldNameForException["userUid"]);

            //Delete
            $group = new \Groups();

            $group->removeUserOfGroup($groupUid, $userUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Unassign users to group
     *
     * @param string $groupUid  Unique id of Group
     * @param array  $arrayData Data uid of Users
     *
     * @return array Return result
     */
    public function unassignUsers($groupUid, array $users)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();

            $result = [];

            foreach ($users as $value) {
                $userUid = $value;
                $flagRemove = 1;

                //Verify data
                $arrayUserData = $user->getUserRecordByPk($userUid, [], false);

                if ($arrayUserData === false) {
                    $result[$userUid] = 'USER_NOT_EXISTS';
                    $flagRemove = 0;
                }

                if ($flagRemove == 1 && $arrayUserData['USR_STATUS'] == 'CLOSED') {
                    $result[$userUid] = 'USER_CLOSED';
                    $flagRemove = 0;
                }

                $groupUser = \GroupUserPeer::retrieveByPK($groupUid, $userUid);

                if ($flagRemove == 1 && is_null($groupUser)) {
                    $result[$userUid] = 'USER_NOT_BELONG_TO_GROUP';
                    $flagRemove = 0;
                }

                //Remove
                $group = new \Groups();

                if ($flagRemove == 1) {
                    $group->removeUserOfGroup($groupUid, $userUid);

                    $result[$userUid] = 'USER_SUCCESSFULLY_REMOVED';
                }
            }

            //Return
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

