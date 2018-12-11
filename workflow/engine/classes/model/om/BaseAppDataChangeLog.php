<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AppDataChangeLogPeer.php';

/**
 * Base class that represents a row from the 'APP_DATA_CHANGE_LOG' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAppDataChangeLog extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AppDataChangeLogPeer
    */
    protected static $peer;

    /**
     * The value for the change_log_id field.
     * @var        int
     */
    protected $change_log_id;

    /**
     * The value for the date field.
     * @var        int
     */
    protected $date;

    /**
     * The value for the app_number field.
     * @var        int
     */
    protected $app_number = 0;

    /**
     * The value for the del_index field.
     * @var        int
     */
    protected $del_index = 0;

    /**
     * The value for the pro_id field.
     * @var        int
     */
    protected $pro_id = 0;

    /**
     * The value for the tas_id field.
     * @var        int
     */
    protected $tas_id = 0;

    /**
     * The value for the usr_id field.
     * @var        int
     */
    protected $usr_id = 0;

    /**
     * The value for the object_type field.
     * @var        int
     */
    protected $object_type = 0;

    /**
     * The value for the object_id field.
     * @var        int
     */
    protected $object_id = 0;

    /**
     * The value for the object_uid field.
     * @var        string
     */
    protected $object_uid = '';

    /**
     * The value for the executed_at field.
     * @var        int
     */
    protected $executed_at = 0;

    /**
     * The value for the source_id field.
     * @var        int
     */
    protected $source_id = 0;

    /**
     * The value for the data field.
     * @var        string
     */
    protected $data;

    /**
     * The value for the skin field.
     * @var        string
     */
    protected $skin = '';

    /**
     * The value for the language field.
     * @var        string
     */
    protected $language = '';

    /**
     * The value for the row_migration field.
     * @var        int
     */
    protected $row_migration = 0;

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
     * Get the [change_log_id] column value.
     * 
     * @return     int
     */
    public function getChangeLogId()
    {

        return $this->change_log_id;
    }

    /**
     * Get the [optionally formatted] [date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getDate($format = 'Y-m-d H:i:s')
    {

        if ($this->date === null || $this->date === '') {
            return null;
        } elseif (!is_int($this->date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [date] as date/time value: " .
                    var_export($this->date, true));
            }
        } else {
            $ts = $this->date;
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
     * Get the [app_number] column value.
     * 
     * @return     int
     */
    public function getAppNumber()
    {

        return $this->app_number;
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
     * Get the [pro_id] column value.
     * 
     * @return     int
     */
    public function getProId()
    {

        return $this->pro_id;
    }

    /**
     * Get the [tas_id] column value.
     * 
     * @return     int
     */
    public function getTasId()
    {

        return $this->tas_id;
    }

    /**
     * Get the [usr_id] column value.
     * 
     * @return     int
     */
    public function getUsrId()
    {

        return $this->usr_id;
    }

    /**
     * Get the [object_type] column value.
     * 
     * @return     int
     */
    public function getObjectType()
    {

        return $this->object_type;
    }

    /**
     * Get the [object_id] column value.
     * 
     * @return     int
     */
    public function getObjectId()
    {

        return $this->object_id;
    }

    /**
     * Get the [object_uid] column value.
     * 
     * @return     string
     */
    public function getObjectUid()
    {

        return $this->object_uid;
    }

    /**
     * Get the [executed_at] column value.
     * 
     * @return     int
     */
    public function getExecutedAt()
    {

        return $this->executed_at;
    }

    /**
     * Get the [source_id] column value.
     * 
     * @return     int
     */
    public function getSourceId()
    {

        return $this->source_id;
    }

    /**
     * Get the [data] column value.
     * 
     * @return     string
     */
    public function getData()
    {

        return $this->data;
    }

    /**
     * Get the [skin] column value.
     * 
     * @return     string
     */
    public function getSkin()
    {

        return $this->skin;
    }

    /**
     * Get the [language] column value.
     * 
     * @return     string
     */
    public function getLanguage()
    {

        return $this->language;
    }

    /**
     * Get the [row_migration] column value.
     * 
     * @return     int
     */
    public function getRowMigration()
    {

        return $this->row_migration;
    }

    /**
     * Set the value of [change_log_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setChangeLogId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->change_log_id !== $v) {
            $this->change_log_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::CHANGE_LOG_ID;
        }

    } // setChangeLogId()

    /**
     * Set the value of [date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->date !== $ts) {
            $this->date = $ts;
            $this->modifiedColumns[] = AppDataChangeLogPeer::DATE;
        }

    } // setDate()

    /**
     * Set the value of [app_number] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAppNumber($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->app_number !== $v || $v === 0) {
            $this->app_number = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::APP_NUMBER;
        }

    } // setAppNumber()

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
            $this->modifiedColumns[] = AppDataChangeLogPeer::DEL_INDEX;
        }

    } // setDelIndex()

    /**
     * Set the value of [pro_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setProId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->pro_id !== $v || $v === 0) {
            $this->pro_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::PRO_ID;
        }

    } // setProId()

    /**
     * Set the value of [tas_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTasId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tas_id !== $v || $v === 0) {
            $this->tas_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::TAS_ID;
        }

    } // setTasId()

    /**
     * Set the value of [usr_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setUsrId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->usr_id !== $v || $v === 0) {
            $this->usr_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::USR_ID;
        }

    } // setUsrId()

    /**
     * Set the value of [object_type] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setObjectType($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->object_type !== $v || $v === 0) {
            $this->object_type = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::OBJECT_TYPE;
        }

    } // setObjectType()

    /**
     * Set the value of [object_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setObjectId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->object_id !== $v || $v === 0) {
            $this->object_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::OBJECT_ID;
        }

    } // setObjectId()

    /**
     * Set the value of [object_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setObjectUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->object_uid !== $v || $v === '') {
            $this->object_uid = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::OBJECT_UID;
        }

    } // setObjectUid()

    /**
     * Set the value of [executed_at] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setExecutedAt($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->executed_at !== $v || $v === 0) {
            $this->executed_at = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::EXECUTED_AT;
        }

    } // setExecutedAt()

    /**
     * Set the value of [source_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setSourceId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->source_id !== $v || $v === 0) {
            $this->source_id = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::SOURCE_ID;
        }

    } // setSourceId()

    /**
     * Set the value of [data] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setData($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->data !== $v) {
            $this->data = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::DATA;
        }

    } // setData()

    /**
     * Set the value of [skin] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setSkin($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->skin !== $v || $v === '') {
            $this->skin = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::SKIN;
        }

    } // setSkin()

    /**
     * Set the value of [language] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLanguage($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->language !== $v || $v === '') {
            $this->language = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::LANGUAGE;
        }

    } // setLanguage()

    /**
     * Set the value of [row_migration] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setRowMigration($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->row_migration !== $v || $v === 0) {
            $this->row_migration = $v;
            $this->modifiedColumns[] = AppDataChangeLogPeer::ROW_MIGRATION;
        }

    } // setRowMigration()

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

            $this->change_log_id = $rs->getInt($startcol + 0);

            $this->date = $rs->getTimestamp($startcol + 1, null);

            $this->app_number = $rs->getInt($startcol + 2);

            $this->del_index = $rs->getInt($startcol + 3);

            $this->pro_id = $rs->getInt($startcol + 4);

            $this->tas_id = $rs->getInt($startcol + 5);

            $this->usr_id = $rs->getInt($startcol + 6);

            $this->object_type = $rs->getInt($startcol + 7);

            $this->object_id = $rs->getInt($startcol + 8);

            $this->object_uid = $rs->getString($startcol + 9);

            $this->executed_at = $rs->getInt($startcol + 10);

            $this->source_id = $rs->getInt($startcol + 11);

            $this->data = $rs->getString($startcol + 12);

            $this->skin = $rs->getString($startcol + 13);

            $this->language = $rs->getString($startcol + 14);

            $this->row_migration = $rs->getInt($startcol + 15);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 16; // 16 = AppDataChangeLogPeer::NUM_COLUMNS - AppDataChangeLogPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AppDataChangeLog object", $e);
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
            $con = Propel::getConnection(AppDataChangeLogPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AppDataChangeLogPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AppDataChangeLogPeer::DATABASE_NAME);
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
                    $pk = AppDataChangeLogPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setChangeLogId($pk);  //[IMV] update autoincrement primary key

                    $this->setNew(false);
                } else {
                    $affectedRows += AppDataChangeLogPeer::doUpdate($this, $con);
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


            if (($retval = AppDataChangeLogPeer::doValidate($this, $columns)) !== true) {
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
        $pos = AppDataChangeLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getChangeLogId();
                break;
            case 1:
                return $this->getDate();
                break;
            case 2:
                return $this->getAppNumber();
                break;
            case 3:
                return $this->getDelIndex();
                break;
            case 4:
                return $this->getProId();
                break;
            case 5:
                return $this->getTasId();
                break;
            case 6:
                return $this->getUsrId();
                break;
            case 7:
                return $this->getObjectType();
                break;
            case 8:
                return $this->getObjectId();
                break;
            case 9:
                return $this->getObjectUid();
                break;
            case 10:
                return $this->getExecutedAt();
                break;
            case 11:
                return $this->getSourceId();
                break;
            case 12:
                return $this->getData();
                break;
            case 13:
                return $this->getSkin();
                break;
            case 14:
                return $this->getLanguage();
                break;
            case 15:
                return $this->getRowMigration();
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
        $keys = AppDataChangeLogPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getChangeLogId(),
            $keys[1] => $this->getDate(),
            $keys[2] => $this->getAppNumber(),
            $keys[3] => $this->getDelIndex(),
            $keys[4] => $this->getProId(),
            $keys[5] => $this->getTasId(),
            $keys[6] => $this->getUsrId(),
            $keys[7] => $this->getObjectType(),
            $keys[8] => $this->getObjectId(),
            $keys[9] => $this->getObjectUid(),
            $keys[10] => $this->getExecutedAt(),
            $keys[11] => $this->getSourceId(),
            $keys[12] => $this->getData(),
            $keys[13] => $this->getSkin(),
            $keys[14] => $this->getLanguage(),
            $keys[15] => $this->getRowMigration(),
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
        $pos = AppDataChangeLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setChangeLogId($value);
                break;
            case 1:
                $this->setDate($value);
                break;
            case 2:
                $this->setAppNumber($value);
                break;
            case 3:
                $this->setDelIndex($value);
                break;
            case 4:
                $this->setProId($value);
                break;
            case 5:
                $this->setTasId($value);
                break;
            case 6:
                $this->setUsrId($value);
                break;
            case 7:
                $this->setObjectType($value);
                break;
            case 8:
                $this->setObjectId($value);
                break;
            case 9:
                $this->setObjectUid($value);
                break;
            case 10:
                $this->setExecutedAt($value);
                break;
            case 11:
                $this->setSourceId($value);
                break;
            case 12:
                $this->setData($value);
                break;
            case 13:
                $this->setSkin($value);
                break;
            case 14:
                $this->setLanguage($value);
                break;
            case 15:
                $this->setRowMigration($value);
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
        $keys = AppDataChangeLogPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setChangeLogId($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setDate($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setAppNumber($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setDelIndex($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setProId($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setTasId($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setUsrId($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setObjectType($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setObjectId($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setObjectUid($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setExecutedAt($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setSourceId($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setData($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setSkin($arr[$keys[13]]);
        }

        if (array_key_exists($keys[14], $arr)) {
            $this->setLanguage($arr[$keys[14]]);
        }

        if (array_key_exists($keys[15], $arr)) {
            $this->setRowMigration($arr[$keys[15]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AppDataChangeLogPeer::DATABASE_NAME);

        if ($this->isColumnModified(AppDataChangeLogPeer::CHANGE_LOG_ID)) {
            $criteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, $this->change_log_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::DATE)) {
            $criteria->add(AppDataChangeLogPeer::DATE, $this->date);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::APP_NUMBER)) {
            $criteria->add(AppDataChangeLogPeer::APP_NUMBER, $this->app_number);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::DEL_INDEX)) {
            $criteria->add(AppDataChangeLogPeer::DEL_INDEX, $this->del_index);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::PRO_ID)) {
            $criteria->add(AppDataChangeLogPeer::PRO_ID, $this->pro_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::TAS_ID)) {
            $criteria->add(AppDataChangeLogPeer::TAS_ID, $this->tas_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::USR_ID)) {
            $criteria->add(AppDataChangeLogPeer::USR_ID, $this->usr_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::OBJECT_TYPE)) {
            $criteria->add(AppDataChangeLogPeer::OBJECT_TYPE, $this->object_type);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::OBJECT_ID)) {
            $criteria->add(AppDataChangeLogPeer::OBJECT_ID, $this->object_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::OBJECT_UID)) {
            $criteria->add(AppDataChangeLogPeer::OBJECT_UID, $this->object_uid);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::EXECUTED_AT)) {
            $criteria->add(AppDataChangeLogPeer::EXECUTED_AT, $this->executed_at);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::SOURCE_ID)) {
            $criteria->add(AppDataChangeLogPeer::SOURCE_ID, $this->source_id);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::DATA)) {
            $criteria->add(AppDataChangeLogPeer::DATA, $this->data);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::SKIN)) {
            $criteria->add(AppDataChangeLogPeer::SKIN, $this->skin);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::LANGUAGE)) {
            $criteria->add(AppDataChangeLogPeer::LANGUAGE, $this->language);
        }

        if ($this->isColumnModified(AppDataChangeLogPeer::ROW_MIGRATION)) {
            $criteria->add(AppDataChangeLogPeer::ROW_MIGRATION, $this->row_migration);
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
        $criteria = new Criteria(AppDataChangeLogPeer::DATABASE_NAME);

        $criteria->add(AppDataChangeLogPeer::CHANGE_LOG_ID, $this->change_log_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     int
     */
    public function getPrimaryKey()
    {
        return $this->getChangeLogId();
    }

    /**
     * Generic method to set the primary key (change_log_id column).
     *
     * @param      int $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setChangeLogId($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AppDataChangeLog (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setDate($this->date);

        $copyObj->setAppNumber($this->app_number);

        $copyObj->setDelIndex($this->del_index);

        $copyObj->setProId($this->pro_id);

        $copyObj->setTasId($this->tas_id);

        $copyObj->setUsrId($this->usr_id);

        $copyObj->setObjectType($this->object_type);

        $copyObj->setObjectId($this->object_id);

        $copyObj->setObjectUid($this->object_uid);

        $copyObj->setExecutedAt($this->executed_at);

        $copyObj->setSourceId($this->source_id);

        $copyObj->setData($this->data);

        $copyObj->setSkin($this->skin);

        $copyObj->setLanguage($this->language);

        $copyObj->setRowMigration($this->row_migration);


        $copyObj->setNew(true);

        $copyObj->setChangeLogId(NULL); // this is a pkey column, so set to default value

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
     * @return     AppDataChangeLog Clone of current object.
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
     * @return     AppDataChangeLogPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AppDataChangeLogPeer();
        }
        return self::$peer;
    }
}

