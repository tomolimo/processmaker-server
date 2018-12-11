<?php

require_once 'classes/model/om/BaseAppAssignSelfServiceValueGroup.php';

/**
 * Skeleton subclass for representing a row from the 'APP_ASSIGN_SELF_SERVICE_VALUE_GROUP' table.
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AppAssignSelfServiceValueGroup extends BaseAppAssignSelfServiceValueGroup
{

    /**
     * Insert multiple rows in table "APP_ASSIGN_SELF_SERVICE_VALUE_GROUP"
     * 
     * @param string $appAssignSelfServiceValueId
     * @param mixed $dataVariable
     * 
     * @return void
     * @throws Exception
     */
    public function createRows($appAssignSelfServiceValueId, $dataVariable)
    {
        $con = Propel::getConnection(AppAssignSelfServiceValuePeer::DATABASE_NAME);
        $con->begin();
        $statement = $con->createStatement();
        if (is_array($dataVariable)) {
            foreach ($dataVariable as $uid) {
                $this->createRow($statement, $appAssignSelfServiceValueId, $uid);
            }
        } else {
            $this->createRow($statement, $appAssignSelfServiceValueId, $dataVariable);
        }
        $con->commit();
    }

    /**
     * Insert a row in table "APP_ASSIGN_SELF_SERVICE_VALUE_GROUP"
     * 
     * @param object $statement
     * @param string $appAssignSelfServiceValueId
     * @param string $id
     * 
     * @return void
     */
    public function createRow($statement, $appAssignSelfServiceValueId, $id)
    {
        $object = $this->getTypeUserOrGroup($id);
        if ($object->id === -1) {
            $dataLog = Bootstrap::getDefaultContextLog();
            $dataLog['ASSIGNEE_ID'] = $id;
            $dataLog['ASSIGNEE_TYPE'] = $object->type;
            Bootstrap::registerMonolog('AssignSelfServiceValue', 300, 'Invalid identifier value  for Assign Self Service Value', $dataLog, $dataLog['workspace'], 'processmaker.log');
        } else {
            $sql = "INSERT INTO "
                    . AppAssignSelfServiceValueGroupPeer::TABLE_NAME
                    . " ("
                    . AppAssignSelfServiceValueGroupPeer::ID . ", "
                    . AppAssignSelfServiceValueGroupPeer::GRP_UID . ", "
                    . AppAssignSelfServiceValueGroupPeer::ASSIGNEE_ID . ", "
                    . AppAssignSelfServiceValueGroupPeer::ASSIGNEE_TYPE
                    . ") "
                    . "VALUES ("
                    . $appAssignSelfServiceValueId . ", '"
                    . $id . "', "
                    . $object->id . ", "
                    . $object->type
                    . ");";
            $result = $statement->executeQuery($sql);
        }
    }

    /**
     * Gets the 'id' that corresponds to a user or group and its type, the type 
     * is 1 for user and 2 for group, if it is not found, -1 is returned.
     * 
     * @param string $uid
     * 
     * @return stdClass
     */
    public function getTypeUserOrGroup($uid)
    {
        $object = new stdClass();
        $group = GroupwfPeer::retrieveByPK($uid);
        if (!empty($group)) {
            $object->type = 2;
            $object->id = $group->getGrpId();
            return $object;
        }
        $user = UsersPeer::retrieveByPK($uid);
        if (!empty($user)) {
            $object->type = 1;
            $object->id = $user->getUsrId();
            return $object;
        }
        $object->type = -1;
        $object->id = -1;
        return $object;
    }
}
