<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AbeRequestsPeer.php';

/**
 * Base class that represents a row from the 'ABE_REQUESTS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAbeRequests extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AbeRequestsPeer
    */
    protected static $peer;

    /**
     * The value for the abe_req_uid field.
     * @var        string
     */
    protected $abe_req_uid = '';

    /**
     * The value for the abe_uid field.
     * @var        string
     */
    protected $abe_uid = '';

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
     * The value for the abe_req_sent_to field.
     * @var        string
     */
    protected $abe_req_sent_to = '';

    /**
     * The value for the abe_req_subject field.
     * @var        string
     */
    protected $abe_req_subject = '';

    /**
     * The value for the abe_req_body field.
     * @var        string
     */
    protected $abe_req_body;

    /**
     * The value for the abe_req_date field.
     * @var        int
     */
    protected $abe_req_date;

    /**
     * The value for the abe_req_status field.
     * @var        string
     */
    protected $abe_req_status = '';

    /**
     * The value for the abe_req_answered field.
     * @var        int
     */
    protected $abe_req_answered = 0;

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
     * Get the [abe_req_uid] column value.
     * 
     * @return     string
     */
    public function getAbeReqUid()
    {

        return $this->abe_req_uid;
    }

    /**
     * Get the [abe_uid] column value.
     * 
     * @return     string
     */
    public function getAbeUid()
    {

        return $this->abe_uid;
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
     * Get the [abe_req_sent_to] column value.
     * 
     * @return     string
     */
    public function getAbeReqSentTo()
    {

        return $this->abe_req_sent_to;
    }

    /**
     * Get the [abe_req_subject] column value.
     * 
     * @return     string
     */
    public function getAbeReqSubject()
    {

        return $this->abe_req_subject;
    }

    /**
     * Get the [abe_req_body] column value.
     * 
     * @return     string
     */
    public function getAbeReqBody()
    {

        return $this->abe_req_body;
    }

    /**
     * Get the [optionally formatted] [abe_req_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getAbeReqDate($format = 'Y-m-d H:i:s')
    {

        if ($this->abe_req_date === null || $this->abe_req_date === '') {
            return null;
        } elseif (!is_int($this->abe_req_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->abe_req_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [abe_req_date] as date/time value: " .
                    var_export($this->abe_req_date, true));
            }
        } else {
            $ts = $this->abe_req_date;
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
     * Get the [abe_req_status] column value.
     * 
     * @return     string
     */
    public function getAbeReqStatus()
    {

        return $this->abe_req_status;
    }

    /**
     * Get the [abe_req_answered] column value.
     * 
     * @return     int
     */
    public function getAbeReqAnswered()
    {

        return $this->abe_req_answered;
    }

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
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_UID;
        }

    } // setAbeReqUid()

    /**
     * Set the value of [abe_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_uid !== $v || $v === '') {
            $this->abe_uid = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_UID;
        }

    } // setAbeUid()

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
            $this->modifiedColumns[] = AbeRequestsPeer::APP_UID;
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
            $this->modifiedColumns[] = AbeRequestsPeer::DEL_INDEX;
        }

    } // setDelIndex()

    /**
     * Set the value of [abe_req_sent_to] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeReqSentTo($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_req_sent_to !== $v || $v === '') {
            $this->abe_req_sent_to = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_SENT_TO;
        }

    } // setAbeReqSentTo()

    /**
     * Set the value of [abe_req_subject] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeReqSubject($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_req_subject !== $v || $v === '') {
            $this->abe_req_subject = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_SUBJECT;
        }

    } // setAbeReqSubject()

    /**
     * Set the value of [abe_req_body] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeReqBody($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_req_body !== $v) {
            $this->abe_req_body = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_BODY;
        }

    } // setAbeReqBody()

    /**
     * Set the value of [abe_req_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAbeReqDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [abe_req_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->abe_req_date !== $ts) {
            $this->abe_req_date = $ts;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_DATE;
        }

    } // setAbeReqDate()

    /**
     * Set the value of [abe_req_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAbeReqStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->abe_req_status !== $v || $v === '') {
            $this->abe_req_status = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_STATUS;
        }

    } // setAbeReqStatus()

    /**
     * Set the value of [abe_req_answered] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAbeReqAnswered($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->abe_req_answered !== $v || $v === 0) {
            $this->abe_req_answered = $v;
            $this->modifiedColumns[] = AbeRequestsPeer::ABE_REQ_ANSWERED;
        }

    } // setAbeReqAnswered()

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

            $this->abe_req_uid = $rs->getString($startcol + 0);

            $this->abe_uid = $rs->getString($startcol + 1);

            $this->app_uid = $rs->getString($startcol + 2);

            $this->del_index = $rs->getInt($startcol + 3);

            $this->abe_req_sent_to = $rs->getString($startcol + 4);

            $this->abe_req_subject = $rs->getString($startcol + 5);

            $this->abe_req_body = $rs->getString($startcol + 6);

            $this->abe_req_date = $rs->getTimestamp($startcol + 7, null);

            $this->abe_req_status = $rs->getString($startcol + 8);

            $this->abe_req_answered = $rs->getInt($startcol + 9);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 10; // 10 = AbeRequestsPeer::NUM_COLUMNS - AbeRequestsPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AbeRequests object", $e);
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
            $con = Propel::getConnection(AbeRequestsPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AbeRequestsPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AbeRequestsPeer::DATABASE_NAME);
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
                    $pk = AbeRequestsPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += AbeRequestsPeer::doUpdate($this, $con);
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


            if (($retval = AbeRequestsPeer::doValidate($this, $columns)) !== true) {
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
        $pos = AbeRequestsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAbeReqUid();
                break;
            case 1:
                return $this->getAbeUid();
                break;
            case 2:
                return $this->getAppUid();
                break;
            case 3:
                return $this->getDelIndex();
                break;
            case 4:
                return $this->getAbeReqSentTo();
                break;
            case 5:
                return $this->getAbeReqSubject();
                break;
            case 6:
                return $this->getAbeReqBody();
                break;
            case 7:
                return $this->getAbeReqDate();
                break;
            case 8:
                return $this->getAbeReqStatus();
                break;
            case 9:
                return $this->getAbeReqAnswered();
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
        $keys = AbeRequestsPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAbeReqUid(),
            $keys[1] => $this->getAbeUid(),
            $keys[2] => $this->getAppUid(),
            $keys[3] => $this->getDelIndex(),
            $keys[4] => $this->getAbeReqSentTo(),
            $keys[5] => $this->getAbeReqSubject(),
            $keys[6] => $this->getAbeReqBody(),
            $keys[7] => $this->getAbeReqDate(),
            $keys[8] => $this->getAbeReqStatus(),
            $keys[9] => $this->getAbeReqAnswered(),
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
        $pos = AbeRequestsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setAbeReqUid($value);
                break;
            case 1:
                $this->setAbeUid($value);
                break;
            case 2:
                $this->setAppUid($value);
                break;
            case 3:
                $this->setDelIndex($value);
                break;
            case 4:
                $this->setAbeReqSentTo($value);
                break;
            case 5:
                $this->setAbeReqSubject($value);
                break;
            case 6:
                $this->setAbeReqBody($value);
                break;
            case 7:
                $this->setAbeReqDate($value);
                break;
            case 8:
                $this->setAbeReqStatus($value);
                break;
            case 9:
                $this->setAbeReqAnswered($value);
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
        $keys = AbeRequestsPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAbeReqUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setAbeUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setAppUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setDelIndex($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setAbeReqSentTo($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setAbeReqSubject($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setAbeReqBody($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setAbeReqDate($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setAbeReqStatus($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setAbeReqAnswered($arr[$keys[9]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AbeRequestsPeer::DATABASE_NAME);

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_UID)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $this->abe_req_uid);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_UID)) {
            $criteria->add(AbeRequestsPeer::ABE_UID, $this->abe_uid);
        }

        if ($this->isColumnModified(AbeRequestsPeer::APP_UID)) {
            $criteria->add(AbeRequestsPeer::APP_UID, $this->app_uid);
        }

        if ($this->isColumnModified(AbeRequestsPeer::DEL_INDEX)) {
            $criteria->add(AbeRequestsPeer::DEL_INDEX, $this->del_index);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_SENT_TO)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_SENT_TO, $this->abe_req_sent_to);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_SUBJECT)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_SUBJECT, $this->abe_req_subject);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_BODY)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_BODY, $this->abe_req_body);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_DATE)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_DATE, $this->abe_req_date);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_STATUS)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_STATUS, $this->abe_req_status);
        }

        if ($this->isColumnModified(AbeRequestsPeer::ABE_REQ_ANSWERED)) {
            $criteria->add(AbeRequestsPeer::ABE_REQ_ANSWERED, $this->abe_req_answered);
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
        $criteria = new Criteria(AbeRequestsPeer::DATABASE_NAME);

        $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $this->abe_req_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getAbeReqUid();
    }

    /**
     * Generic method to set the primary key (abe_req_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setAbeReqUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AbeRequests (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setAbeUid($this->abe_uid);

        $copyObj->setAppUid($this->app_uid);

        $copyObj->setDelIndex($this->del_index);

        $copyObj->setAbeReqSentTo($this->abe_req_sent_to);

        $copyObj->setAbeReqSubject($this->abe_req_subject);

        $copyObj->setAbeReqBody($this->abe_req_body);

        $copyObj->setAbeReqDate($this->abe_req_date);

        $copyObj->setAbeReqStatus($this->abe_req_status);

        $copyObj->setAbeReqAnswered($this->abe_req_answered);


        $copyObj->setNew(true);

        $copyObj->setAbeReqUid(''); // this is a pkey column, so set to default value

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
     * @return     AbeRequests Clone of current object.
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
     * @return     AbeRequestsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AbeRequestsPeer();
        }
        return self::$peer;
    }
}

