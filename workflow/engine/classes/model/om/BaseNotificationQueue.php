<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/NotificationQueuePeer.php';

/**
 * Base class that represents a row from the 'NOTIFICATION_QUEUE' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseNotificationQueue extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        NotificationQueuePeer
    */
    protected static $peer;

    /**
     * The value for the not_uid field.
     * @var        string
     */
    protected $not_uid;

    /**
     * The value for the dev_type field.
     * @var        string
     */
    protected $dev_type;

    /**
     * The value for the dev_uid field.
     * @var        string
     */
    protected $dev_uid;

    /**
     * The value for the not_msg field.
     * @var        string
     */
    protected $not_msg;

    /**
     * The value for the not_data field.
     * @var        string
     */
    protected $not_data;

    /**
     * The value for the not_status field.
     * @var        string
     */
    protected $not_status;

    /**
     * The value for the not_send_date field.
     * @var        int
     */
    protected $not_send_date;

    /**
     * The value for the app_uid field.
     * @var        string
     */
    protected $app_uid = '';

    /**
     * The value for the del_index field.
     * @var        int
     */
    protected $del_index = 0;

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
     * Get the [not_uid] column value.
     * 
     * @return     string
     */
    public function getNotUid()
    {

        return $this->not_uid;
    }

    /**
     * Get the [dev_type] column value.
     * 
     * @return     string
     */
    public function getDevType()
    {

        return $this->dev_type;
    }

    /**
     * Get the [dev_uid] column value.
     * 
     * @return     string
     */
    public function getDevUid()
    {

        return $this->dev_uid;
    }

    /**
     * Get the [not_msg] column value.
     * 
     * @return     string
     */
    public function getNotMsg()
    {

        return $this->not_msg;
    }

    /**
     * Get the [not_data] column value.
     * 
     * @return     string
     */
    public function getNotData()
    {

        return $this->not_data;
    }

    /**
     * Get the [not_status] column value.
     * 
     * @return     string
     */
    public function getNotStatus()
    {

        return $this->not_status;
    }

    /**
     * Get the [optionally formatted] [not_send_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getNotSendDate($format = 'Y-m-d H:i:s')
    {

        if ($this->not_send_date === null || $this->not_send_date === '') {
            return null;
        } elseif (!is_int($this->not_send_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->not_send_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [not_send_date] as date/time value: " .
                    var_export($this->not_send_date, true));
            }
        } else {
            $ts = $this->not_send_date;
        }
        if ($format === null) {
            return $ts;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $ts);
        } else {
            return date($format, $ts);
        }
    }

    /**
     * Get the [app_uid] column value.
     * 
     * @return     string
     */
    public function getAppUid()
    {

        return $this->app_uid;
    }

    /**
     * Get the [del_index] column value.
     * 
     * @return     int
     */
    public function getDelIndex()
    {

        return $this->del_index;
    }

    /**
     * Set the value of [not_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setNotUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->not_uid !== $v) {
            $this->not_uid = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::NOT_UID;
        }

    } // setNotUid()

    /**
     * Set the value of [dev_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDevType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dev_type !== $v) {
            $this->dev_type = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::DEV_TYPE;
        }

    } // setDevType()

    /**
     * Set the value of [dev_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDevUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dev_uid !== $v) {
            $this->dev_uid = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::DEV_UID;
        }

    } // setDevUid()

    /**
     * Set the value of [not_msg] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setNotMsg($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->not_msg !== $v) {
            $this->not_msg = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::NOT_MSG;
        }

    } // setNotMsg()

    /**
     * Set the value of [not_data] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setNotData($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->not_data !== $v) {
            $this->not_data = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::NOT_DATA;
        }

    } // setNotData()

    /**
     * Set the value of [not_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setNotStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->not_status !== $v) {
            $this->not_status = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::NOT_STATUS;
        }

    } // setNotStatus()

    /**
     * Set the value of [not_send_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setNotSendDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [not_send_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->not_send_date !== $ts) {
            $this->not_send_date = $ts;
            $this->modifiedColumns[] = NotificationQueuePeer::NOT_SEND_DATE;
        }

    } // setNotSendDate()

    /**
     * Set the value of [app_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAppUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->app_uid !== $v || $v === '') {
            $this->app_uid = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::APP_UID;
        }

    } // setAppUid()

    /**
     * Set the value of [del_index] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setDelIndex($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->del_index !== $v || $v === 0) {
            $this->del_index = $v;
            $this->modifiedColumns[] = NotificationQueuePeer::DEL_INDEX;
        }

    } // setDelIndex()

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

            $this->not_uid = $rs->getString($startcol + 0);

            $this->dev_type = $rs->getString($startcol + 1);

            $this->dev_uid = $rs->getString($startcol + 2);

            $this->not_msg = $rs->getString($startcol + 3);

            $this->not_data = $rs->getString($startcol + 4);

            $this->not_status = $rs->getString($startcol + 5);

            $this->not_send_date = $rs->getTimestamp($startcol + 6, null);

            $this->app_uid = $rs->getString($startcol + 7);

            $this->del_index = $rs->getInt($startcol + 8);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 9; // 9 = NotificationQueuePeer::NUM_COLUMNS - NotificationQueuePeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating NotificationQueue object", $e);
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
            $con = Propel::getConnection(NotificationQueuePeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            NotificationQueuePeer::doDelete($this, $con);
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
            $con = Propel::getConnection(NotificationQueuePeer::DATABASE_NAME);
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
                    $pk = NotificationQueuePeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += NotificationQueuePeer::doUpdate($this, $con);
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


            if (($retval = NotificationQueuePeer::doValidate($this, $columns)) !== true) {
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
        $pos = NotificationQueuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getNotUid();
                break;
            case 1:
                return $this->getDevType();
                break;
            case 2:
                return $this->getDevUid();
                break;
            case 3:
                return $this->getNotMsg();
                break;
            case 4:
                return $this->getNotData();
                break;
            case 5:
                return $this->getNotStatus();
                break;
            case 6:
                return $this->getNotSendDate();
                break;
            case 7:
                return $this->getAppUid();
                break;
            case 8:
                return $this->getDelIndex();
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
        $keys = NotificationQueuePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getNotUid(),
            $keys[1] => $this->getDevType(),
            $keys[2] => $this->getDevUid(),
            $keys[3] => $this->getNotMsg(),
            $keys[4] => $this->getNotData(),
            $keys[5] => $this->getNotStatus(),
            $keys[6] => $this->getNotSendDate(),
            $keys[7] => $this->getAppUid(),
            $keys[8] => $this->getDelIndex(),
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
        $pos = NotificationQueuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setNotUid($value);
                break;
            case 1:
                $this->setDevType($value);
                break;
            case 2:
                $this->setDevUid($value);
                break;
            case 3:
                $this->setNotMsg($value);
                break;
            case 4:
                $this->setNotData($value);
                break;
            case 5:
                $this->setNotStatus($value);
                break;
            case 6:
                $this->setNotSendDate($value);
                break;
            case 7:
                $this->setAppUid($value);
                break;
            case 8:
                $this->setDelIndex($value);
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
        $keys = NotificationQueuePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setNotUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setDevType($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setDevUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setNotMsg($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setNotData($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setNotStatus($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setNotSendDate($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setAppUid($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setDelIndex($arr[$keys[8]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(NotificationQueuePeer::DATABASE_NAME);

        if ($this->isColumnModified(NotificationQueuePeer::NOT_UID)) {
            $criteria->add(NotificationQueuePeer::NOT_UID, $this->not_uid);
        }

        if ($this->isColumnModified(NotificationQueuePeer::DEV_TYPE)) {
            $criteria->add(NotificationQueuePeer::DEV_TYPE, $this->dev_type);
        }

        if ($this->isColumnModified(NotificationQueuePeer::DEV_UID)) {
            $criteria->add(NotificationQueuePeer::DEV_UID, $this->dev_uid);
        }

        if ($this->isColumnModified(NotificationQueuePeer::NOT_MSG)) {
            $criteria->add(NotificationQueuePeer::NOT_MSG, $this->not_msg);
        }

        if ($this->isColumnModified(NotificationQueuePeer::NOT_DATA)) {
            $criteria->add(NotificationQueuePeer::NOT_DATA, $this->not_data);
        }

        if ($this->isColumnModified(NotificationQueuePeer::NOT_STATUS)) {
            $criteria->add(NotificationQueuePeer::NOT_STATUS, $this->not_status);
        }

        if ($this->isColumnModified(NotificationQueuePeer::NOT_SEND_DATE)) {
            $criteria->add(NotificationQueuePeer::NOT_SEND_DATE, $this->not_send_date);
        }

        if ($this->isColumnModified(NotificationQueuePeer::APP_UID)) {
            $criteria->add(NotificationQueuePeer::APP_UID, $this->app_uid);
        }

        if ($this->isColumnModified(NotificationQueuePeer::DEL_INDEX)) {
            $criteria->add(NotificationQueuePeer::DEL_INDEX, $this->del_index);
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
        $criteria = new Criteria(NotificationQueuePeer::DATABASE_NAME);

        $criteria->add(NotificationQueuePeer::NOT_UID, $this->not_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getNotUid();
    }

    /**
     * Generic method to set the primary key (not_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setNotUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of NotificationQueue (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setDevType($this->dev_type);

        $copyObj->setDevUid($this->dev_uid);

        $copyObj->setNotMsg($this->not_msg);

        $copyObj->setNotData($this->not_data);

        $copyObj->setNotStatus($this->not_status);

        $copyObj->setNotSendDate($this->not_send_date);

        $copyObj->setAppUid($this->app_uid);

        $copyObj->setDelIndex($this->del_index);


        $copyObj->setNew(true);

        $copyObj->setNotUid(NULL); // this is a pkey column, so set to default value

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
     * @return     NotificationQueue Clone of current object.
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
     * @return     NotificationQueuePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new NotificationQueuePeer();
        }
        return self::$peer;
    }
}

