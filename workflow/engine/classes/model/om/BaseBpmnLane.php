<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnLanePeer.php';

/**
 * Base class that represents a row from the 'BPMN_LANE' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnLane extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnLanePeer
    */
    protected static $peer;

    /**
     * The value for the lan_uid field.
     * @var        string
     */
    protected $lan_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the lns_uid field.
     * @var        string
     */
    protected $lns_uid;

    /**
     * The value for the lan_name field.
     * @var        string
     */
    protected $lan_name;

    /**
     * The value for the lan_child_laneset field.
     * @var        string
     */
    protected $lan_child_laneset;

    /**
     * The value for the lan_is_horizontal field.
     * @var        int
     */
    protected $lan_is_horizontal = 1;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * @var        BpmnLaneset
     */
    protected $aBpmnLaneset;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Get the [lan_uid] column value.
     * 
     * @return     string
     */
    public function getLanUid()
    {

        return $this->lan_uid;
    }

    /**
     * Get the [prj_uid] column value.
     * 
     * @return     string
     */
    public function getPrjUid()
    {

        return $this->prj_uid;
    }

    /**
     * Get the [lns_uid] column value.
     * 
     * @return     string
     */
    public function getLnsUid()
    {

        return $this->lns_uid;
    }

    /**
     * Get the [lan_name] column value.
     * 
     * @return     string
     */
    public function getLanName()
    {

        return $this->lan_name;
    }

    /**
     * Get the [lan_child_laneset] column value.
     * 
     * @return     string
     */
    public function getLanChildLaneset()
    {

        return $this->lan_child_laneset;
    }

    /**
     * Get the [lan_is_horizontal] column value.
     * 
     * @return     int
     */
    public function getLanIsHorizontal()
    {

        return $this->lan_is_horizontal;
    }

    /**
     * Set the value of [lan_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLanUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->lan_uid !== $v || $v === '') {
            $this->lan_uid = $v;
            $this->modifiedColumns[] = BpmnLanePeer::LAN_UID;
        }

    } // setLanUid()

    /**
     * Set the value of [prj_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_uid !== $v) {
            $this->prj_uid = $v;
            $this->modifiedColumns[] = BpmnLanePeer::PRJ_UID;
        }

        if ($this->aBpmnProject !== null && $this->aBpmnProject->getPrjUid() !== $v) {
            $this->aBpmnProject = null;
        }

    } // setPrjUid()

    /**
     * Set the value of [lns_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLnsUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->lns_uid !== $v) {
            $this->lns_uid = $v;
            $this->modifiedColumns[] = BpmnLanePeer::LNS_UID;
        }

        if ($this->aBpmnLaneset !== null && $this->aBpmnLaneset->getLnsUid() !== $v) {
            $this->aBpmnLaneset = null;
        }

    } // setLnsUid()

    /**
     * Set the value of [lan_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLanName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->lan_name !== $v) {
            $this->lan_name = $v;
            $this->modifiedColumns[] = BpmnLanePeer::LAN_NAME;
        }

    } // setLanName()

    /**
     * Set the value of [lan_child_laneset] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLanChildLaneset($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->lan_child_laneset !== $v) {
            $this->lan_child_laneset = $v;
            $this->modifiedColumns[] = BpmnLanePeer::LAN_CHILD_LANESET;
        }

    } // setLanChildLaneset()

    /**
     * Set the value of [lan_is_horizontal] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setLanIsHorizontal($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->lan_is_horizontal !== $v || $v === 1) {
            $this->lan_is_horizontal = $v;
            $this->modifiedColumns[] = BpmnLanePeer::LAN_IS_HORIZONTAL;
        }

    } // setLanIsHorizontal()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (1-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
     * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
     * @return     int next starting column
     * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(ResultSet $rs, $startcol = 1)
    {
        try {

            $this->lan_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->lns_uid = $rs->getString($startcol + 2);

            $this->lan_name = $rs->getString($startcol + 3);

            $this->lan_child_laneset = $rs->getString($startcol + 4);

            $this->lan_is_horizontal = $rs->getInt($startcol + 5);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 6; // 6 = BpmnLanePeer::NUM_COLUMNS - BpmnLanePeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnLane object", $e);
        }
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      Connection $con
     * @return     void
     * @throws     PropelException
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete($con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(BpmnLanePeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnLanePeer::doDelete($this, $con);
            $this->setDeleted(true);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Stores the object in the database.  If the object is new,
     * it inserts it; otherwise an update is performed.  This method
     * wraps the doSave() worker method in a transaction.
     *
     * @param      Connection $con
     * @return     int The number of rows affected by this insert/update
     * @throws     PropelException
     * @see        doSave()
     */
    public function save($con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(BpmnLanePeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            $affectedRows = $this->doSave($con);
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Stores the object in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      Connection $con
     * @return     int The number of rows affected by this insert/update and any referring
     * @throws     PropelException
     * @see        save()
     */
    protected function doSave($con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;


            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if ($this->aBpmnProject->isModified()) {
                    $affectedRows += $this->aBpmnProject->save($con);
                }
                $this->setBpmnProject($this->aBpmnProject);
            }

            if ($this->aBpmnLaneset !== null) {
                if ($this->aBpmnLaneset->isModified()) {
                    $affectedRows += $this->aBpmnLaneset->save($con);
                }
                $this->setBpmnLaneset($this->aBpmnLaneset);
            }


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnLanePeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnLanePeer::doUpdate($this, $con);
                }
                $this->resetModified(); // [HL] After being saved an object is no longer 'modified'
            }

            $this->alreadyInSave = false;
        }
        return $affectedRows;
    } // doSave()

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return     array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param      mixed $columns Column name or an array of column names.
     * @return     boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();
            return true;
        } else {
            $this->validationFailures = $res;
            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param      array $columns Array of column names to validate.
     * @return     mixed <code>true</code> if all validations pass; 
                   array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if (!$this->aBpmnProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnProject->getValidationFailures());
                }
            }

            if ($this->aBpmnLaneset !== null) {
                if (!$this->aBpmnLaneset->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnLaneset->getValidationFailures());
                }
            }


            if (($retval = BpmnLanePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TYPE_PHPNAME,
     *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return     mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = BpmnLanePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        return $this->getByPosition($pos);
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return     mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch($pos) {
            case 0:
                return $this->getLanUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getLnsUid();
                break;
            case 3:
                return $this->getLanName();
                break;
            case 4:
                return $this->getLanChildLaneset();
                break;
            case 5:
                return $this->getLanIsHorizontal();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param      string $keyType One of the class type constants TYPE_PHPNAME,
     *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return     an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = BpmnLanePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getLanUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getLnsUid(),
            $keys[3] => $this->getLanName(),
            $keys[4] => $this->getLanChildLaneset(),
            $keys[5] => $this->getLanIsHorizontal(),
        );
        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name peer name
     * @param      mixed $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TYPE_PHPNAME,
     *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return     void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = BpmnLanePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return     void
     */
    public function setByPosition($pos, $value)
    {
        switch($pos) {
            case 0:
                $this->setLanUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setLnsUid($value);
                break;
            case 3:
                $this->setLanName($value);
                break;
            case 4:
                $this->setLanChildLaneset($value);
                break;
            case 5:
                $this->setLanIsHorizontal($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
     * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return     void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = BpmnLanePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setLanUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setLnsUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setLanName($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setLanChildLaneset($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setLanIsHorizontal($arr[$keys[5]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnLanePeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnLanePeer::LAN_UID)) {
            $criteria->add(BpmnLanePeer::LAN_UID, $this->lan_uid);
        }

        if ($this->isColumnModified(BpmnLanePeer::PRJ_UID)) {
            $criteria->add(BpmnLanePeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnLanePeer::LNS_UID)) {
            $criteria->add(BpmnLanePeer::LNS_UID, $this->lns_uid);
        }

        if ($this->isColumnModified(BpmnLanePeer::LAN_NAME)) {
            $criteria->add(BpmnLanePeer::LAN_NAME, $this->lan_name);
        }

        if ($this->isColumnModified(BpmnLanePeer::LAN_CHILD_LANESET)) {
            $criteria->add(BpmnLanePeer::LAN_CHILD_LANESET, $this->lan_child_laneset);
        }

        if ($this->isColumnModified(BpmnLanePeer::LAN_IS_HORIZONTAL)) {
            $criteria->add(BpmnLanePeer::LAN_IS_HORIZONTAL, $this->lan_is_horizontal);
        }


        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return     Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(BpmnLanePeer::DATABASE_NAME);

        $criteria->add(BpmnLanePeer::LAN_UID, $this->lan_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getLanUid();
    }

    /**
     * Generic method to set the primary key (lan_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setLanUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnLane (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setLnsUid($this->lns_uid);

        $copyObj->setLanName($this->lan_name);

        $copyObj->setLanChildLaneset($this->lan_child_laneset);

        $copyObj->setLanIsHorizontal($this->lan_is_horizontal);


        $copyObj->setNew(true);

        $copyObj->setLanUid(''); // this is a pkey column, so set to default value

    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return     BpmnLane Clone of current object.
     * @throws     PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);
        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return     BpmnLanePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnLanePeer();
        }
        return self::$peer;
    }

    /**
     * Declares an association between this object and a BpmnProject object.
     *
     * @param      BpmnProject $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnProject($v)
    {


        if ($v === null) {
            $this->setPrjUid(NULL);
        } else {
            $this->setPrjUid($v->getPrjUid());
        }


        $this->aBpmnProject = $v;
    }


    /**
     * Get the associated BpmnProject object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnProject The associated BpmnProject object.
     * @throws     PropelException
     */
    public function getBpmnProject($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnProjectPeer.php';

        if ($this->aBpmnProject === null && (($this->prj_uid !== "" && $this->prj_uid !== null))) {

            $this->aBpmnProject = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);
               $obj->addBpmnProjects($this);
             */
        }
        return $this->aBpmnProject;
    }

    /**
     * Declares an association between this object and a BpmnLaneset object.
     *
     * @param      BpmnLaneset $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnLaneset($v)
    {


        if ($v === null) {
            $this->setLnsUid(NULL);
        } else {
            $this->setLnsUid($v->getLnsUid());
        }


        $this->aBpmnLaneset = $v;
    }


    /**
     * Get the associated BpmnLaneset object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnLaneset The associated BpmnLaneset object.
     * @throws     PropelException
     */
    public function getBpmnLaneset($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';

        if ($this->aBpmnLaneset === null && (($this->lns_uid !== "" && $this->lns_uid !== null))) {

            $this->aBpmnLaneset = BpmnLanesetPeer::retrieveByPK($this->lns_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnLanesetPeer::retrieveByPK($this->lns_uid, $con);
               $obj->addBpmnLanesets($this);
             */
        }
        return $this->aBpmnLaneset;
    }
}

