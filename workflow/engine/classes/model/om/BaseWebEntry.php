<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/WebEntryPeer.php';

/**
 * Base class that represents a row from the 'WEB_ENTRY' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseWebEntry extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        WebEntryPeer
    */
    protected static $peer;

    /**
     * The value for the we_uid field.
     * @var        string
     */
    protected $we_uid;

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid;

    /**
     * The value for the tas_uid field.
     * @var        string
     */
    protected $tas_uid;

    /**
     * The value for the dyn_uid field.
     * @var        string
     */
    protected $dyn_uid;

    /**
     * The value for the usr_uid field.
     * @var        string
     */
    protected $usr_uid = '';

    /**
     * The value for the we_method field.
     * @var        string
     */
    protected $we_method = 'HTML';

    /**
     * The value for the we_input_document_access field.
     * @var        int
     */
    protected $we_input_document_access = 0;

    /**
     * The value for the we_data field.
     * @var        string
     */
    protected $we_data;

    /**
     * The value for the we_create_usr_uid field.
     * @var        string
     */
    protected $we_create_usr_uid = '';

    /**
     * The value for the we_update_usr_uid field.
     * @var        string
     */
    protected $we_update_usr_uid = '';

    /**
     * The value for the we_create_date field.
     * @var        int
     */
    protected $we_create_date;

    /**
     * The value for the we_update_date field.
     * @var        int
     */
    protected $we_update_date;

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
     * Get the [we_uid] column value.
     * 
     * @return     string
     */
    public function getWeUid()
    {

        return $this->we_uid;
    }

    /**
     * Get the [pro_uid] column value.
     * 
     * @return     string
     */
    public function getProUid()
    {

        return $this->pro_uid;
    }

    /**
     * Get the [tas_uid] column value.
     * 
     * @return     string
     */
    public function getTasUid()
    {

        return $this->tas_uid;
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
     * Get the [we_method] column value.
     * 
     * @return     string
     */
    public function getWeMethod()
    {

        return $this->we_method;
    }

    /**
     * Get the [we_input_document_access] column value.
     * 
     * @return     int
     */
    public function getWeInputDocumentAccess()
    {

        return $this->we_input_document_access;
    }

    /**
     * Get the [we_data] column value.
     * 
     * @return     string
     */
    public function getWeData()
    {

        return $this->we_data;
    }

    /**
     * Get the [we_create_usr_uid] column value.
     * 
     * @return     string
     */
    public function getWeCreateUsrUid()
    {

        return $this->we_create_usr_uid;
    }

    /**
     * Get the [we_update_usr_uid] column value.
     * 
     * @return     string
     */
    public function getWeUpdateUsrUid()
    {

        return $this->we_update_usr_uid;
    }

    /**
     * Get the [optionally formatted] [we_create_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getWeCreateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->we_create_date === null || $this->we_create_date === '') {
            return null;
        } elseif (!is_int($this->we_create_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->we_create_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [we_create_date] as date/time value: " .
                    var_export($this->we_create_date, true));
            }
        } else {
            $ts = $this->we_create_date;
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
     * Get the [optionally formatted] [we_update_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getWeUpdateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->we_update_date === null || $this->we_update_date === '') {
            return null;
        } elseif (!is_int($this->we_update_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->we_update_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [we_update_date] as date/time value: " .
                    var_export($this->we_update_date, true));
            }
        } else {
            $ts = $this->we_update_date;
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
     * Set the value of [we_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->we_uid !== $v) {
            $this->we_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_UID;
        }

    } // setWeUid()

    /**
     * Set the value of [pro_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setProUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->pro_uid !== $v) {
            $this->pro_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::PRO_UID;
        }

    } // setProUid()

    /**
     * Set the value of [tas_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTasUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tas_uid !== $v) {
            $this->tas_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::TAS_UID;
        }

    } // setTasUid()

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
            $this->modifiedColumns[] = WebEntryPeer::DYN_UID;
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

        if ($this->usr_uid !== $v || $v === '') {
            $this->usr_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::USR_UID;
        }

    } // setUsrUid()

    /**
     * Set the value of [we_method] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeMethod($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->we_method !== $v || $v === 'HTML') {
            $this->we_method = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_METHOD;
        }

    } // setWeMethod()

    /**
     * Set the value of [we_input_document_access] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setWeInputDocumentAccess($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->we_input_document_access !== $v || $v === 0) {
            $this->we_input_document_access = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_INPUT_DOCUMENT_ACCESS;
        }

    } // setWeInputDocumentAccess()

    /**
     * Set the value of [we_data] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeData($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->we_data !== $v) {
            $this->we_data = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_DATA;
        }

    } // setWeData()

    /**
     * Set the value of [we_create_usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeCreateUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->we_create_usr_uid !== $v || $v === '') {
            $this->we_create_usr_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_CREATE_USR_UID;
        }

    } // setWeCreateUsrUid()

    /**
     * Set the value of [we_update_usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setWeUpdateUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->we_update_usr_uid !== $v || $v === '') {
            $this->we_update_usr_uid = $v;
            $this->modifiedColumns[] = WebEntryPeer::WE_UPDATE_USR_UID;
        }

    } // setWeUpdateUsrUid()

    /**
     * Set the value of [we_create_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setWeCreateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [we_create_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->we_create_date !== $ts) {
            $this->we_create_date = $ts;
            $this->modifiedColumns[] = WebEntryPeer::WE_CREATE_DATE;
        }

    } // setWeCreateDate()

    /**
     * Set the value of [we_update_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setWeUpdateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [we_update_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->we_update_date !== $ts) {
            $this->we_update_date = $ts;
            $this->modifiedColumns[] = WebEntryPeer::WE_UPDATE_DATE;
        }

    } // setWeUpdateDate()

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

            $this->we_uid = $rs->getString($startcol + 0);

            $this->pro_uid = $rs->getString($startcol + 1);

            $this->tas_uid = $rs->getString($startcol + 2);

            $this->dyn_uid = $rs->getString($startcol + 3);

            $this->usr_uid = $rs->getString($startcol + 4);

            $this->we_method = $rs->getString($startcol + 5);

            $this->we_input_document_access = $rs->getInt($startcol + 6);

            $this->we_data = $rs->getString($startcol + 7);

            $this->we_create_usr_uid = $rs->getString($startcol + 8);

            $this->we_update_usr_uid = $rs->getString($startcol + 9);

            $this->we_create_date = $rs->getTimestamp($startcol + 10, null);

            $this->we_update_date = $rs->getTimestamp($startcol + 11, null);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 12; // 12 = WebEntryPeer::NUM_COLUMNS - WebEntryPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating WebEntry object", $e);
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
            $con = Propel::getConnection(WebEntryPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            WebEntryPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(WebEntryPeer::DATABASE_NAME);
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
                    $pk = WebEntryPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += WebEntryPeer::doUpdate($this, $con);
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


            if (($retval = WebEntryPeer::doValidate($this, $columns)) !== true) {
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
        $pos = WebEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getWeUid();
                break;
            case 1:
                return $this->getProUid();
                break;
            case 2:
                return $this->getTasUid();
                break;
            case 3:
                return $this->getDynUid();
                break;
            case 4:
                return $this->getUsrUid();
                break;
            case 5:
                return $this->getWeMethod();
                break;
            case 6:
                return $this->getWeInputDocumentAccess();
                break;
            case 7:
                return $this->getWeData();
                break;
            case 8:
                return $this->getWeCreateUsrUid();
                break;
            case 9:
                return $this->getWeUpdateUsrUid();
                break;
            case 10:
                return $this->getWeCreateDate();
                break;
            case 11:
                return $this->getWeUpdateDate();
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
        $keys = WebEntryPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getWeUid(),
            $keys[1] => $this->getProUid(),
            $keys[2] => $this->getTasUid(),
            $keys[3] => $this->getDynUid(),
            $keys[4] => $this->getUsrUid(),
            $keys[5] => $this->getWeMethod(),
            $keys[6] => $this->getWeInputDocumentAccess(),
            $keys[7] => $this->getWeData(),
            $keys[8] => $this->getWeCreateUsrUid(),
            $keys[9] => $this->getWeUpdateUsrUid(),
            $keys[10] => $this->getWeCreateDate(),
            $keys[11] => $this->getWeUpdateDate(),
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
        $pos = WebEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setWeUid($value);
                break;
            case 1:
                $this->setProUid($value);
                break;
            case 2:
                $this->setTasUid($value);
                break;
            case 3:
                $this->setDynUid($value);
                break;
            case 4:
                $this->setUsrUid($value);
                break;
            case 5:
                $this->setWeMethod($value);
                break;
            case 6:
                $this->setWeInputDocumentAccess($value);
                break;
            case 7:
                $this->setWeData($value);
                break;
            case 8:
                $this->setWeCreateUsrUid($value);
                break;
            case 9:
                $this->setWeUpdateUsrUid($value);
                break;
            case 10:
                $this->setWeCreateDate($value);
                break;
            case 11:
                $this->setWeUpdateDate($value);
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
        $keys = WebEntryPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setWeUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setProUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setTasUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setDynUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setUsrUid($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setWeMethod($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setWeInputDocumentAccess($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setWeData($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setWeCreateUsrUid($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setWeUpdateUsrUid($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setWeCreateDate($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setWeUpdateDate($arr[$keys[11]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WebEntryPeer::DATABASE_NAME);

        if ($this->isColumnModified(WebEntryPeer::WE_UID)) {
            $criteria->add(WebEntryPeer::WE_UID, $this->we_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::PRO_UID)) {
            $criteria->add(WebEntryPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::TAS_UID)) {
            $criteria->add(WebEntryPeer::TAS_UID, $this->tas_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::DYN_UID)) {
            $criteria->add(WebEntryPeer::DYN_UID, $this->dyn_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::USR_UID)) {
            $criteria->add(WebEntryPeer::USR_UID, $this->usr_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_METHOD)) {
            $criteria->add(WebEntryPeer::WE_METHOD, $this->we_method);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_INPUT_DOCUMENT_ACCESS)) {
            $criteria->add(WebEntryPeer::WE_INPUT_DOCUMENT_ACCESS, $this->we_input_document_access);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_DATA)) {
            $criteria->add(WebEntryPeer::WE_DATA, $this->we_data);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_CREATE_USR_UID)) {
            $criteria->add(WebEntryPeer::WE_CREATE_USR_UID, $this->we_create_usr_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_UPDATE_USR_UID)) {
            $criteria->add(WebEntryPeer::WE_UPDATE_USR_UID, $this->we_update_usr_uid);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_CREATE_DATE)) {
            $criteria->add(WebEntryPeer::WE_CREATE_DATE, $this->we_create_date);
        }

        if ($this->isColumnModified(WebEntryPeer::WE_UPDATE_DATE)) {
            $criteria->add(WebEntryPeer::WE_UPDATE_DATE, $this->we_update_date);
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
        $criteria = new Criteria(WebEntryPeer::DATABASE_NAME);

        $criteria->add(WebEntryPeer::WE_UID, $this->we_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getWeUid();
    }

    /**
     * Generic method to set the primary key (we_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setWeUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of WebEntry (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setTasUid($this->tas_uid);

        $copyObj->setDynUid($this->dyn_uid);

        $copyObj->setUsrUid($this->usr_uid);

        $copyObj->setWeMethod($this->we_method);

        $copyObj->setWeInputDocumentAccess($this->we_input_document_access);

        $copyObj->setWeData($this->we_data);

        $copyObj->setWeCreateUsrUid($this->we_create_usr_uid);

        $copyObj->setWeUpdateUsrUid($this->we_update_usr_uid);

        $copyObj->setWeCreateDate($this->we_create_date);

        $copyObj->setWeUpdateDate($this->we_update_date);


        $copyObj->setNew(true);

        $copyObj->setWeUid(NULL); // this is a pkey column, so set to default value

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
     * @return     WebEntry Clone of current object.
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
     * @return     WebEntryPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new WebEntryPeer();
        }
        return self::$peer;
    }
}

