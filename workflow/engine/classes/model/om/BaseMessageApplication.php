<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/MessageApplicationPeer.php';

/**
 * Base class that represents a row from the 'MESSAGE_APPLICATION' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseMessageApplication extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        MessageApplicationPeer
    */
    protected static $peer;

    /**
     * The value for the msgapp_uid field.
     * @var        string
     */
    protected $msgapp_uid;

    /**
     * The value for the app_uid field.
     * @var        string
     */
    protected $app_uid;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the evn_uid_throw field.
     * @var        string
     */
    protected $evn_uid_throw;

    /**
     * The value for the evn_uid_catch field.
     * @var        string
     */
    protected $evn_uid_catch;

    /**
     * The value for the msgapp_variables field.
     * @var        string
     */
    protected $msgapp_variables;

    /**
     * The value for the msgapp_correlation field.
     * @var        string
     */
    protected $msgapp_correlation = '';

    /**
     * The value for the msgapp_throw_date field.
     * @var        int
     */
    protected $msgapp_throw_date;

    /**
     * The value for the msgapp_catch_date field.
     * @var        int
     */
    protected $msgapp_catch_date;

    /**
     * The value for the msgapp_status field.
     * @var        string
     */
    protected $msgapp_status = 'UNREAD';

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
     * Get the [msgapp_uid] column value.
     * 
     * @return     string
     */
    public function getMsgappUid()
    {

        return $this->msgapp_uid;
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
     * Get the [prj_uid] column value.
     * 
     * @return     string
     */
    public function getPrjUid()
    {

        return $this->prj_uid;
    }

    /**
     * Get the [evn_uid_throw] column value.
     * 
     * @return     string
     */
    public function getEvnUidThrow()
    {

        return $this->evn_uid_throw;
    }

    /**
     * Get the [evn_uid_catch] column value.
     * 
     * @return     string
     */
    public function getEvnUidCatch()
    {

        return $this->evn_uid_catch;
    }

    /**
     * Get the [msgapp_variables] column value.
     * 
     * @return     string
     */
    public function getMsgappVariables()
    {

        return $this->msgapp_variables;
    }

    /**
     * Get the [msgapp_correlation] column value.
     * 
     * @return     string
     */
    public function getMsgappCorrelation()
    {

        return $this->msgapp_correlation;
    }

    /**
     * Get the [optionally formatted] [msgapp_throw_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getMsgappThrowDate($format = 'Y-m-d H:i:s')
    {

        if ($this->msgapp_throw_date === null || $this->msgapp_throw_date === '') {
            return null;
        } elseif (!is_int($this->msgapp_throw_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->msgapp_throw_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [msgapp_throw_date] as date/time value: " .
                    var_export($this->msgapp_throw_date, true));
            }
        } else {
            $ts = $this->msgapp_throw_date;
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
     * Get the [optionally formatted] [msgapp_catch_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getMsgappCatchDate($format = 'Y-m-d H:i:s')
    {

        if ($this->msgapp_catch_date === null || $this->msgapp_catch_date === '') {
            return null;
        } elseif (!is_int($this->msgapp_catch_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->msgapp_catch_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [msgapp_catch_date] as date/time value: " .
                    var_export($this->msgapp_catch_date, true));
            }
        } else {
            $ts = $this->msgapp_catch_date;
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
     * Get the [msgapp_status] column value.
     * 
     * @return     string
     */
    public function getMsgappStatus()
    {

        return $this->msgapp_status;
    }

    /**
     * Set the value of [msgapp_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgappUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msgapp_uid !== $v) {
            $this->msgapp_uid = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_UID;
        }

    } // setMsgappUid()

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

        if ($this->app_uid !== $v) {
            $this->app_uid = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::APP_UID;
        }

    } // setAppUid()

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
            $this->modifiedColumns[] = MessageApplicationPeer::PRJ_UID;
        }

    } // setPrjUid()

    /**
     * Set the value of [evn_uid_throw] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnUidThrow($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_uid_throw !== $v) {
            $this->evn_uid_throw = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::EVN_UID_THROW;
        }

    } // setEvnUidThrow()

    /**
     * Set the value of [evn_uid_catch] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnUidCatch($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_uid_catch !== $v) {
            $this->evn_uid_catch = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::EVN_UID_CATCH;
        }

    } // setEvnUidCatch()

    /**
     * Set the value of [msgapp_variables] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgappVariables($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msgapp_variables !== $v) {
            $this->msgapp_variables = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_VARIABLES;
        }

    } // setMsgappVariables()

    /**
     * Set the value of [msgapp_correlation] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgappCorrelation($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msgapp_correlation !== $v || $v === '') {
            $this->msgapp_correlation = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_CORRELATION;
        }

    } // setMsgappCorrelation()

    /**
     * Set the value of [msgapp_throw_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMsgappThrowDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [msgapp_throw_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->msgapp_throw_date !== $ts) {
            $this->msgapp_throw_date = $ts;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_THROW_DATE;
        }

    } // setMsgappThrowDate()

    /**
     * Set the value of [msgapp_catch_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMsgappCatchDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [msgapp_catch_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->msgapp_catch_date !== $ts) {
            $this->msgapp_catch_date = $ts;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_CATCH_DATE;
        }

    } // setMsgappCatchDate()

    /**
     * Set the value of [msgapp_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgappStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msgapp_status !== $v || $v === 'UNREAD') {
            $this->msgapp_status = $v;
            $this->modifiedColumns[] = MessageApplicationPeer::MSGAPP_STATUS;
        }

    } // setMsgappStatus()

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

            $this->msgapp_uid = $rs->getString($startcol + 0);

            $this->app_uid = $rs->getString($startcol + 1);

            $this->prj_uid = $rs->getString($startcol + 2);

            $this->evn_uid_throw = $rs->getString($startcol + 3);

            $this->evn_uid_catch = $rs->getString($startcol + 4);

            $this->msgapp_variables = $rs->getString($startcol + 5);

            $this->msgapp_correlation = $rs->getString($startcol + 6);

            $this->msgapp_throw_date = $rs->getTimestamp($startcol + 7, null);

            $this->msgapp_catch_date = $rs->getTimestamp($startcol + 8, null);

            $this->msgapp_status = $rs->getString($startcol + 9);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 10; // 10 = MessageApplicationPeer::NUM_COLUMNS - MessageApplicationPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating MessageApplication object", $e);
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
            $con = Propel::getConnection(MessageApplicationPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            MessageApplicationPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(MessageApplicationPeer::DATABASE_NAME);
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
                    $pk = MessageApplicationPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += MessageApplicationPeer::doUpdate($this, $con);
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


            if (($retval = MessageApplicationPeer::doValidate($this, $columns)) !== true) {
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
        $pos = MessageApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getMsgappUid();
                break;
            case 1:
                return $this->getAppUid();
                break;
            case 2:
                return $this->getPrjUid();
                break;
            case 3:
                return $this->getEvnUidThrow();
                break;
            case 4:
                return $this->getEvnUidCatch();
                break;
            case 5:
                return $this->getMsgappVariables();
                break;
            case 6:
                return $this->getMsgappCorrelation();
                break;
            case 7:
                return $this->getMsgappThrowDate();
                break;
            case 8:
                return $this->getMsgappCatchDate();
                break;
            case 9:
                return $this->getMsgappStatus();
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
        $keys = MessageApplicationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMsgappUid(),
            $keys[1] => $this->getAppUid(),
            $keys[2] => $this->getPrjUid(),
            $keys[3] => $this->getEvnUidThrow(),
            $keys[4] => $this->getEvnUidCatch(),
            $keys[5] => $this->getMsgappVariables(),
            $keys[6] => $this->getMsgappCorrelation(),
            $keys[7] => $this->getMsgappThrowDate(),
            $keys[8] => $this->getMsgappCatchDate(),
            $keys[9] => $this->getMsgappStatus(),
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
        $pos = MessageApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setMsgappUid($value);
                break;
            case 1:
                $this->setAppUid($value);
                break;
            case 2:
                $this->setPrjUid($value);
                break;
            case 3:
                $this->setEvnUidThrow($value);
                break;
            case 4:
                $this->setEvnUidCatch($value);
                break;
            case 5:
                $this->setMsgappVariables($value);
                break;
            case 6:
                $this->setMsgappCorrelation($value);
                break;
            case 7:
                $this->setMsgappThrowDate($value);
                break;
            case 8:
                $this->setMsgappCatchDate($value);
                break;
            case 9:
                $this->setMsgappStatus($value);
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
        $keys = MessageApplicationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMsgappUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setAppUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setPrjUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setEvnUidThrow($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setEvnUidCatch($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setMsgappVariables($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setMsgappCorrelation($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setMsgappThrowDate($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setMsgappCatchDate($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setMsgappStatus($arr[$keys[9]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MessageApplicationPeer::DATABASE_NAME);

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_UID)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_UID, $this->msgapp_uid);
        }

        if ($this->isColumnModified(MessageApplicationPeer::APP_UID)) {
            $criteria->add(MessageApplicationPeer::APP_UID, $this->app_uid);
        }

        if ($this->isColumnModified(MessageApplicationPeer::PRJ_UID)) {
            $criteria->add(MessageApplicationPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(MessageApplicationPeer::EVN_UID_THROW)) {
            $criteria->add(MessageApplicationPeer::EVN_UID_THROW, $this->evn_uid_throw);
        }

        if ($this->isColumnModified(MessageApplicationPeer::EVN_UID_CATCH)) {
            $criteria->add(MessageApplicationPeer::EVN_UID_CATCH, $this->evn_uid_catch);
        }

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_VARIABLES)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_VARIABLES, $this->msgapp_variables);
        }

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_CORRELATION)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_CORRELATION, $this->msgapp_correlation);
        }

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_THROW_DATE)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_THROW_DATE, $this->msgapp_throw_date);
        }

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_CATCH_DATE)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_CATCH_DATE, $this->msgapp_catch_date);
        }

        if ($this->isColumnModified(MessageApplicationPeer::MSGAPP_STATUS)) {
            $criteria->add(MessageApplicationPeer::MSGAPP_STATUS, $this->msgapp_status);
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
        $criteria = new Criteria(MessageApplicationPeer::DATABASE_NAME);

        $criteria->add(MessageApplicationPeer::MSGAPP_UID, $this->msgapp_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getMsgappUid();
    }

    /**
     * Generic method to set the primary key (msgapp_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setMsgappUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of MessageApplication (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setAppUid($this->app_uid);

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setEvnUidThrow($this->evn_uid_throw);

        $copyObj->setEvnUidCatch($this->evn_uid_catch);

        $copyObj->setMsgappVariables($this->msgapp_variables);

        $copyObj->setMsgappCorrelation($this->msgapp_correlation);

        $copyObj->setMsgappThrowDate($this->msgapp_throw_date);

        $copyObj->setMsgappCatchDate($this->msgapp_catch_date);

        $copyObj->setMsgappStatus($this->msgapp_status);


        $copyObj->setNew(true);

        $copyObj->setMsgappUid(NULL); // this is a pkey column, so set to default value

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
     * @return     MessageApplication Clone of current object.
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
     * @return     MessageApplicationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new MessageApplicationPeer();
        }
        return self::$peer;
    }
}

