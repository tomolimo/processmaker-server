<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AbeResponsesPeer.php';

/**
 * Base class that represents a row from the 'ABE_RESPONSES' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAbeResponses extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AbeResponsesPeer
    */
    protected static $peer;

    /**
     * The value for the abe_res_uid field.
     * @var        string
     */
    protected $abe_res_uid = '';

    /**
     * The value for the abe_req_uid field.
     * @var        string
     */
    protected $abe_req_uid = '';

    /**
     * The value for the abe_res_client_ip field.
     * @var        string
     */
    protected $abe_res_client_ip = '';

    /**
     * The value for the abe_res_data field.
     * @var        string
     */
    protected $abe_res_data;

    /**
     * The value for the abe_res_date field.
     * @var        int
     */
    protected $abe_res_date;

    /**
     * The value for the abe_res_status field.
     * @var        string
     */
    protected $abe_res_status = '';

    /**
     * The value for the abe_res_message field.
     * @var        string
     */
    protected $abe_res_message = '';

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
     * Get the [abe_res_uid] column value.
     * 
     * @return     string
     */
    public function getAbeResUid()
    {

        return $this->abe_res_uid;
    }

    /**
     * Get the [abe_req_uid] column value.
     * 
     * @return     string
     */
    public function getAbeReqUid()
    {

        return $this->abe_req_uid;
    }

    /**
     * Get the [abe_res_client_ip] column value.
     * 
     * @return     string
     */
    public function getAbeResClientIp()
    {

        return $this->abe_res_client_ip;
    }

    /**
     * Get the [abe_res_data] column value.
     * 
     * @return     string
     */
    public function getAbeResData()
    {

        return $this->abe_res_data;
    }

    /**
     * Get the [optionally formatted] [abe_res_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getAbeResDate($format = 'Y-m-d H:i:s')
    {

        if ($this->abe_res_date === null || $this->abe_res_date === '') {
            return null;
        } elseif (!is_int($this->abe_res_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->abe_res_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [abe_res_date] as date/time value: " .
                    var_export($this->abe_res_date, true));
            }
        } else {
            $ts = $this->abe_res_date;
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
     * Get the [abe_res_status] column value.
     * 
     * @return     string
     */
    public function getAbeResStatus()
    {

        return $this->abe_res_status;
    }

    /**
     * Get the [abe_res_message] column value.
     * 
     * @return     string
     */
    public function getAbeResMessage()
    {

        return $this->abe_res_message;
    }

    /**
     * Set the value of [abe_res_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeResUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_res_uid !== $v || $v === '') {
            $this->abe_res_uid = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_UID;
        }

    } // setAbeResUid()

    /**
     * Set the value of [abe_req_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeReqUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_req_uid !== $v || $v === '') {
            $this->abe_req_uid = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_REQ_UID;
        }

    } // setAbeReqUid()

    /**
     * Set the value of [abe_res_client_ip] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeResClientIp($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_res_client_ip !== $v || $v === '') {
            $this->abe_res_client_ip = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_CLIENT_IP;
        }

    } // setAbeResClientIp()

    /**
     * Set the value of [abe_res_data] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeResData($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_res_data !== $v) {
            $this->abe_res_data = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_DATA;
        }

    } // setAbeResData()

    /**
     * Set the value of [abe_res_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAbeResDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [abe_res_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->abe_res_date !== $ts) {
            $this->abe_res_date = $ts;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_DATE;
        }

    } // setAbeResDate()

    /**
     * Set the value of [abe_res_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeResStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_res_status !== $v || $v === '') {
            $this->abe_res_status = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_STATUS;
        }

    } // setAbeResStatus()

    /**
     * Set the value of [abe_res_message] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeResMessage($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_res_message !== $v || $v === '') {
            $this->abe_res_message = $v;
            $this->modifiedColumns[] = AbeResponsesPeer::ABE_RES_MESSAGE;
        }

    } // setAbeResMessage()

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

            $this->abe_res_uid = $rs->getString($startcol + 0);

            $this->abe_req_uid = $rs->getString($startcol + 1);

            $this->abe_res_client_ip = $rs->getString($startcol + 2);

            $this->abe_res_data = $rs->getString($startcol + 3);

            $this->abe_res_date = $rs->getTimestamp($startcol + 4, null);

            $this->abe_res_status = $rs->getString($startcol + 5);

            $this->abe_res_message = $rs->getString($startcol + 6);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 7; // 7 = AbeResponsesPeer::NUM_COLUMNS - AbeResponsesPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AbeResponses object", $e);
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
            $con = Propel::getConnection(AbeResponsesPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AbeResponsesPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AbeResponsesPeer::DATABASE_NAME);
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
                    $pk = AbeResponsesPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += AbeResponsesPeer::doUpdate($this, $con);
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


            if (($retval = AbeResponsesPeer::doValidate($this, $columns)) !== true) {
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
        $pos = AbeResponsesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAbeResUid();
                break;
            case 1:
                return $this->getAbeReqUid();
                break;
            case 2:
                return $this->getAbeResClientIp();
                break;
            case 3:
                return $this->getAbeResData();
                break;
            case 4:
                return $this->getAbeResDate();
                break;
            case 5:
                return $this->getAbeResStatus();
                break;
            case 6:
                return $this->getAbeResMessage();
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
        $keys = AbeResponsesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAbeResUid(),
            $keys[1] => $this->getAbeReqUid(),
            $keys[2] => $this->getAbeResClientIp(),
            $keys[3] => $this->getAbeResData(),
            $keys[4] => $this->getAbeResDate(),
            $keys[5] => $this->getAbeResStatus(),
            $keys[6] => $this->getAbeResMessage(),
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
        $pos = AbeResponsesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setAbeResUid($value);
                break;
            case 1:
                $this->setAbeReqUid($value);
                break;
            case 2:
                $this->setAbeResClientIp($value);
                break;
            case 3:
                $this->setAbeResData($value);
                break;
            case 4:
                $this->setAbeResDate($value);
                break;
            case 5:
                $this->setAbeResStatus($value);
                break;
            case 6:
                $this->setAbeResMessage($value);
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
        $keys = AbeResponsesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAbeResUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setAbeReqUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setAbeResClientIp($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setAbeResData($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setAbeResDate($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setAbeResStatus($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setAbeResMessage($arr[$keys[6]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AbeResponsesPeer::DATABASE_NAME);

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_UID)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_UID, $this->abe_res_uid);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_REQ_UID)) {
            $criteria->add(AbeResponsesPeer::ABE_REQ_UID, $this->abe_req_uid);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_CLIENT_IP)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_CLIENT_IP, $this->abe_res_client_ip);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_DATA)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_DATA, $this->abe_res_data);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_DATE)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_DATE, $this->abe_res_date);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_STATUS)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_STATUS, $this->abe_res_status);
        }

        if ($this->isColumnModified(AbeResponsesPeer::ABE_RES_MESSAGE)) {
            $criteria->add(AbeResponsesPeer::ABE_RES_MESSAGE, $this->abe_res_message);
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
        $criteria = new Criteria(AbeResponsesPeer::DATABASE_NAME);

        $criteria->add(AbeResponsesPeer::ABE_RES_UID, $this->abe_res_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getAbeResUid();
    }

    /**
     * Generic method to set the primary key (abe_res_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setAbeResUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AbeResponses (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setAbeReqUid($this->abe_req_uid);

        $copyObj->setAbeResClientIp($this->abe_res_client_ip);

        $copyObj->setAbeResData($this->abe_res_data);

        $copyObj->setAbeResDate($this->abe_res_date);

        $copyObj->setAbeResStatus($this->abe_res_status);

        $copyObj->setAbeResMessage($this->abe_res_message);


        $copyObj->setNew(true);

        $copyObj->setAbeResUid(''); // this is a pkey column, so set to default value

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
     * @return     AbeResponses Clone of current object.
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
     * @return     AbeResponsesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AbeResponsesPeer();
        }
        return self::$peer;
    }
}

