<?php

require_once 'classes/model/om/BaseListUnassignedGroup.php';


/**
 * Skeleton subclass for representing a row from the 'LIST_UNASSIGNED_GROUP' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
// @codingStandardsIgnoreStart
class ListUnassignedGroup extends BaseListUnassignedGroup
{
    // @codingStandardsIgnoreEnd
    /**
     * Create List Unassigned Group Table
     *
     * @param type $data
     * @return type
     *
     */
    public function create($data)
    {
        if (!empty($data['USR_UID'])) {
            $u = new Users();
            $data['USR_ID'] = $u->load($data['USR_UID'])['USR_ID'];
        }
        $con = Propel::getConnection(ListUnassignedGroupPeer::DATABASE_NAME);
        try {
            $this->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $result = $this->save();
            } else {
                $e = new Exception("Failed Validation in class " . get_class($this) . ".");
                $e->aValidationFailures = $this->getValidationFailures();
                throw ($e);
            }
            $con->commit();
            return $result;
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }
    /**
     *  Update List Unassigned Group Table
     *
     * @param type $data
     * @return type
     * @throws type
     */
    public function update($data)
    {
        if (!empty($data['USR_UID'])) {
            $u = new Users();
            $data['USR_ID'] = $u->load($data['USR_UID'])['USR_ID'];
        }
        $con = Propel::getConnection(ListUnassignedGroupPeer::DATABASE_NAME);
        try {
            $con->begin();
            $this->setNew(false);
            $this->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $result = $this->save();
                $con->commit();
                return $result;
            } else {
                $con->rollback();
                throw (new Exception("Failed Validation in class " . get_class($this) . "."));
            }
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     * Remove List Unassigned Group
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function remove($app_uid, $una_uid)
    {
        $con = Propel::getConnection(ListUnassignedGroupPeer::DATABASE_NAME);
        try {
            $this->setAppUid($app_uid);
            $this->setUnaUid($una_uid);
            $con->begin();
            $this->delete();
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }
    /**
     * newRow List Unassigned Group
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function newRow($unaUid, $usrUid, $type, $typeUid = '')
    {
        $data['UNA_UID'] = $unaUid;
        $data['USR_UID'] = $usrUid;
        $data['TYPE']    = $type;
        $data['TYP_UID'] = $typeUid;
        self::create($data);
    }
} // ListUnassignedGroup
