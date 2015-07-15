<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/WebEntryEventPeer.php';

/**
 * Base class that represents a row from the 'WEB_ENTRY_EVENT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseWebEntryEvent extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        WebEntryEventPeer
    */
    protected static $peer;

    /**
     * The value for the wee_uid field.
     * @var        string
     */
    protected $wee_uid;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the evn_uid field.
     * @var        string
     */
    protected $evn_uid;

    /**
     * The value for the act_uid field.
     * @var        string
     */
    protected $act_uid;

    /**
     * The value for the dyn_uid field.
     * @var        string
     */
    protected $dyn_uid;

    /**
     * The value for the usr_uid field.
     * @var        string
     */
    protected $usr_uid;

    /**
     * The value for the wee_status field.
     * @var        string
     */
    protected $wee_status = 'ENABLED';

    /**
     * The value for the wee_we_uid field.
     * @var        string
     */
    protected $wee_we_uid = '';

    /**
     * The value for the wee_we_tas_uid field.
     * @var        string
     */
    protected $wee_we_tas_uid = '';

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
     * Get the [wee_uid] column value.
     * 
     * @return     string
     */
    public function getWeeUid()
    {

        return $this->wee_uid;
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
     * Get the [evn_uid] column value.
     * 
     * @return     string
     */
    public function getEvnUid()
    {

        return $this->evn_uid;
    }

    /**
     * Get the [act_uid] column value.
     * 
     * @return     string
     */
    public function getActUid()
    {

        return $this->act_uid;
    }

    /**
     * Get the [dyn_uid] column value.
     * 
     * @return     string
     */
    public function getDynUid()
    {

        return $this->dyn_uid;
    }

    /**
     * Get the [usr_uid] column value.
     * 
     * @return     string
     */
    public function getUsrUid()
    {

        return $this->usr_uid;
    }

    /**
     * Get the [wee_status] column value.
     * 
     * @return     string
     */
    public function getWeeStatus()
    {

        return $this->wee_status;
    }

    /**
     * Get the [wee_we_uid] column value.
     * 
     * @return     string
     */
    public function getWeeWeUid()
    {

        return $this->wee_we_uid;
    }

    /**
     * Get the [wee_we_tas_uid] column value.
     * 
     * @return     string
     */
    public function getWeeWeTasUid()
    {

        return $this->wee_we_tas_uid;
    }

    /**
     * Set the value of [wee_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeeUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->wee_uid !== $v) {
            $this->wee_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::WEE_UID;
        }

    } // setWeeUid()

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
            $this->modifiedColumns[] = WebEntryEventPeer::PRJ_UID;
        }

    } // setPrjUid()

    /**
     * Set the value of [evn_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_uid !== $v) {
            $this->evn_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::EVN_UID;
        }

    } // setEvnUid()

    /**
     * Set the value of [act_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_uid !== $v) {
            $this->act_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::ACT_UID;
        }

    } // setActUid()

    /**
     * Set the value of [dyn_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDynUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dyn_uid !== $v) {
            $this->dyn_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::DYN_UID;
        }

    } // setDynUid()

    /**
     * Set the value of [usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_uid !== $v) {
            $this->usr_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::USR_UID;
        }

    } // setUsrUid()

    /**
     * Set the value of [wee_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeeStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->wee_status !== $v || $v === 'ENABLED') {
            $this->wee_status = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::WEE_STATUS;
        }

    } // setWeeStatus()

    /**
     * Set the value of [wee_we_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeeWeUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->wee_we_uid !== $v || $v === '') {
            $this->wee_we_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::WEE_WE_UID;
        }

    } // setWeeWeUid()

    /**
     * Set the value of [wee_we_tas_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeeWeTasUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->wee_we_tas_uid !== $v || $v === '') {
            $this->wee_we_tas_uid = $v;
            $this->modifiedColumns[] = WebEntryEventPeer::WEE_WE_TAS_UID;
        }

    } // setWeeWeTasUid()

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

            $this->wee_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->evn_uid = $rs->getString($startcol + 2);

            $this->act_uid = $rs->getString($startcol + 3);

            $this->dyn_uid = $rs->getString($startcol + 4);

            $this->usr_uid = $rs->getString($startcol + 5);

            $this->wee_status = $rs->getString($startcol + 6);

            $this->wee_we_uid = $rs->getString($startcol + 7);

            $this->wee_we_tas_uid = $rs->getString($startcol + 8);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 9; // 9 = WebEntryEventPeer::NUM_COLUMNS - WebEntryEventPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating WebEntryEvent object", $e);
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
            $con = Propel::getConnection(WebEntryEventPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            WebEntryEventPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(WebEntryEventPeer::DATABASE_NAME);
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


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = WebEntryEventPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += WebEntryEventPeer::doUpdate($this, $con);
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


            if (($retval = WebEntryEventPeer::doValidate($this, $columns)) !== true) {
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
        $pos = WebEntryEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getWeeUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getEvnUid();
                break;
            case 3:
                return $this->getActUid();
                break;
            case 4:
                return $this->getDynUid();
                break;
            case 5:
                return $this->getUsrUid();
                break;
            case 6:
                return $this->getWeeStatus();
                break;
            case 7:
                return $this->getWeeWeUid();
                break;
            case 8:
                return $this->getWeeWeTasUid();
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
        $keys = WebEntryEventPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getWeeUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getEvnUid(),
            $keys[3] => $this->getActUid(),
            $keys[4] => $this->getDynUid(),
            $keys[5] => $this->getUsrUid(),
            $keys[6] => $this->getWeeStatus(),
            $keys[7] => $this->getWeeWeUid(),
            $keys[8] => $this->getWeeWeTasUid(),
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
        $pos = WebEntryEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setWeeUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setEvnUid($value);
                break;
            case 3:
                $this->setActUid($value);
                break;
            case 4:
                $this->setDynUid($value);
                break;
            case 5:
                $this->setUsrUid($value);
                break;
            case 6:
                $this->setWeeStatus($value);
                break;
            case 7:
                $this->setWeeWeUid($value);
                break;
            case 8:
                $this->setWeeWeTasUid($value);
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
        $keys = WebEntryEventPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setWeeUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setEvnUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setActUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setDynUid($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setUsrUid($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setWeeStatus($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setWeeWeUid($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setWeeWeTasUid($arr[$keys[8]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WebEntryEventPeer::DATABASE_NAME);

        if ($this->isColumnModified(WebEntryEventPeer::WEE_UID)) {
            $criteria->add(WebEntryEventPeer::WEE_UID, $this->wee_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::PRJ_UID)) {
            $criteria->add(WebEntryEventPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::EVN_UID)) {
            $criteria->add(WebEntryEventPeer::EVN_UID, $this->evn_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::ACT_UID)) {
            $criteria->add(WebEntryEventPeer::ACT_UID, $this->act_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::DYN_UID)) {
            $criteria->add(WebEntryEventPeer::DYN_UID, $this->dyn_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::USR_UID)) {
            $criteria->add(WebEntryEventPeer::USR_UID, $this->usr_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::WEE_STATUS)) {
            $criteria->add(WebEntryEventPeer::WEE_STATUS, $this->wee_status);
        }

        if ($this->isColumnModified(WebEntryEventPeer::WEE_WE_UID)) {
            $criteria->add(WebEntryEventPeer::WEE_WE_UID, $this->wee_we_uid);
        }

        if ($this->isColumnModified(WebEntryEventPeer::WEE_WE_TAS_UID)) {
            $criteria->add(WebEntryEventPeer::WEE_WE_TAS_UID, $this->wee_we_tas_uid);
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
        $criteria = new Criteria(WebEntryEventPeer::DATABASE_NAME);

        $criteria->add(WebEntryEventPeer::WEE_UID, $this->wee_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getWeeUid();
    }

    /**
     * Generic method to set the primary key (wee_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setWeeUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of WebEntryEvent (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setEvnUid($this->evn_uid);

        $copyObj->setActUid($this->act_uid);

        $copyObj->setDynUid($this->dyn_uid);

        $copyObj->setUsrUid($this->usr_uid);

        $copyObj->setWeeStatus($this->wee_status);

        $copyObj->setWeeWeUid($this->wee_we_uid);

        $copyObj->setWeeWeTasUid($this->wee_we_tas_uid);


        $copyObj->setNew(true);

        $copyObj->setWeeUid(NULL); // this is a pkey column, so set to default value

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
     * @return     WebEntryEvent Clone of current object.
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
     * @return     WebEntryEventPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new WebEntryEventPeer();
        }
        return self::$peer;
    }
}

