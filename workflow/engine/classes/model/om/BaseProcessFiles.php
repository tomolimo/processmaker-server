<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/ProcessFilesPeer.php';

/**
 * Base class that represents a row from the 'PROCESS_FILES' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseProcessFiles extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ProcessFilesPeer
    */
    protected static $peer;

    /**
     * The value for the prf_uid field.
     * @var        string
     */
    protected $prf_uid;

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid;

    /**
     * The value for the usr_uid field.
     * @var        string
     */
    protected $usr_uid;

    /**
     * The value for the prf_update_usr_uid field.
     * @var        string
     */
    protected $prf_update_usr_uid;

    /**
     * The value for the prf_path field.
     * @var        string
     */
    protected $prf_path = '';

    /**
     * The value for the prf_type field.
     * @var        string
     */
    protected $prf_type = '';

    /**
     * The value for the prf_editable field.
     * @var        int
     */
    protected $prf_editable = 1;

    /**
     * The value for the prf_create_date field.
     * @var        int
     */
    protected $prf_create_date;

    /**
     * The value for the prf_update_date field.
     * @var        int
     */
    protected $prf_update_date;

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
     * Get the [prf_uid] column value.
     * 
     * @return     string
     */
    public function getPrfUid()
    {

        return $this->prf_uid;
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
     * Get the [usr_uid] column value.
     * 
     * @return     string
     */
    public function getUsrUid()
    {

        return $this->usr_uid;
    }

    /**
     * Get the [prf_update_usr_uid] column value.
     * 
     * @return     string
     */
    public function getPrfUpdateUsrUid()
    {

        return $this->prf_update_usr_uid;
    }

    /**
     * Get the [prf_path] column value.
     * 
     * @return     string
     */
    public function getPrfPath()
    {

        return $this->prf_path;
    }

    /**
     * Get the [prf_type] column value.
     * 
     * @return     string
     */
    public function getPrfType()
    {

        return $this->prf_type;
    }

    /**
     * Get the [prf_editable] column value.
     * 
     * @return     int
     */
    public function getPrfEditable()
    {

        return $this->prf_editable;
    }

    /**
     * Get the [optionally formatted] [prf_create_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getPrfCreateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->prf_create_date === null || $this->prf_create_date === '') {
            return null;
        } elseif (!is_int($this->prf_create_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->prf_create_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [prf_create_date] as date/time value: " .
                    var_export($this->prf_create_date, true));
            }
        } else {
            $ts = $this->prf_create_date;
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
     * Get the [optionally formatted] [prf_update_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getPrfUpdateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->prf_update_date === null || $this->prf_update_date === '') {
            return null;
        } elseif (!is_int($this->prf_update_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->prf_update_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [prf_update_date] as date/time value: " .
                    var_export($this->prf_update_date, true));
            }
        } else {
            $ts = $this->prf_update_date;
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
     * Set the value of [prf_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrfUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prf_uid !== $v) {
            $this->prf_uid = $v;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_UID;
        }

    } // setPrfUid()

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
            $this->modifiedColumns[] = ProcessFilesPeer::PRO_UID;
        }

    } // setProUid()

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
            $this->modifiedColumns[] = ProcessFilesPeer::USR_UID;
        }

    } // setUsrUid()

    /**
     * Set the value of [prf_update_usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrfUpdateUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prf_update_usr_uid !== $v) {
            $this->prf_update_usr_uid = $v;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_UPDATE_USR_UID;
        }

    } // setPrfUpdateUsrUid()

    /**
     * Set the value of [prf_path] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrfPath($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prf_path !== $v || $v === '') {
            $this->prf_path = $v;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_PATH;
        }

    } // setPrfPath()

    /**
     * Set the value of [prf_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrfType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prf_type !== $v || $v === '') {
            $this->prf_type = $v;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_TYPE;
        }

    } // setPrfType()

    /**
     * Set the value of [prf_editable] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setPrfEditable($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->prf_editable !== $v || $v === 1) {
            $this->prf_editable = $v;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_EDITABLE;
        }

    } // setPrfEditable()

    /**
     * Set the value of [prf_create_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setPrfCreateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [prf_create_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->prf_create_date !== $ts) {
            $this->prf_create_date = $ts;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_CREATE_DATE;
        }

    } // setPrfCreateDate()

    /**
     * Set the value of [prf_update_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setPrfUpdateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [prf_update_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->prf_update_date !== $ts) {
            $this->prf_update_date = $ts;
            $this->modifiedColumns[] = ProcessFilesPeer::PRF_UPDATE_DATE;
        }

    } // setPrfUpdateDate()

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

            $this->prf_uid = $rs->getString($startcol + 0);

            $this->pro_uid = $rs->getString($startcol + 1);

            $this->usr_uid = $rs->getString($startcol + 2);

            $this->prf_update_usr_uid = $rs->getString($startcol + 3);

            $this->prf_path = $rs->getString($startcol + 4);

            $this->prf_type = $rs->getString($startcol + 5);

            $this->prf_editable = $rs->getInt($startcol + 6);

            $this->prf_create_date = $rs->getTimestamp($startcol + 7, null);

            $this->prf_update_date = $rs->getTimestamp($startcol + 8, null);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 9; // 9 = ProcessFilesPeer::NUM_COLUMNS - ProcessFilesPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating ProcessFiles object", $e);
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
            $con = Propel::getConnection(ProcessFilesPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            ProcessFilesPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(ProcessFilesPeer::DATABASE_NAME);
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
                    $pk = ProcessFilesPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += ProcessFilesPeer::doUpdate($this, $con);
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


            if (($retval = ProcessFilesPeer::doValidate($this, $columns)) !== true) {
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
        $pos = ProcessFilesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPrfUid();
                break;
            case 1:
                return $this->getProUid();
                break;
            case 2:
                return $this->getUsrUid();
                break;
            case 3:
                return $this->getPrfUpdateUsrUid();
                break;
            case 4:
                return $this->getPrfPath();
                break;
            case 5:
                return $this->getPrfType();
                break;
            case 6:
                return $this->getPrfEditable();
                break;
            case 7:
                return $this->getPrfCreateDate();
                break;
            case 8:
                return $this->getPrfUpdateDate();
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
        $keys = ProcessFilesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPrfUid(),
            $keys[1] => $this->getProUid(),
            $keys[2] => $this->getUsrUid(),
            $keys[3] => $this->getPrfUpdateUsrUid(),
            $keys[4] => $this->getPrfPath(),
            $keys[5] => $this->getPrfType(),
            $keys[6] => $this->getPrfEditable(),
            $keys[7] => $this->getPrfCreateDate(),
            $keys[8] => $this->getPrfUpdateDate(),
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
        $pos = ProcessFilesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setPrfUid($value);
                break;
            case 1:
                $this->setProUid($value);
                break;
            case 2:
                $this->setUsrUid($value);
                break;
            case 3:
                $this->setPrfUpdateUsrUid($value);
                break;
            case 4:
                $this->setPrfPath($value);
                break;
            case 5:
                $this->setPrfType($value);
                break;
            case 6:
                $this->setPrfEditable($value);
                break;
            case 7:
                $this->setPrfCreateDate($value);
                break;
            case 8:
                $this->setPrfUpdateDate($value);
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
        $keys = ProcessFilesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPrfUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setProUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setUsrUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setPrfUpdateUsrUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setPrfPath($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setPrfType($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setPrfEditable($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setPrfCreateDate($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setPrfUpdateDate($arr[$keys[8]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ProcessFilesPeer::DATABASE_NAME);

        if ($this->isColumnModified(ProcessFilesPeer::PRF_UID)) {
            $criteria->add(ProcessFilesPeer::PRF_UID, $this->prf_uid);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRO_UID)) {
            $criteria->add(ProcessFilesPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(ProcessFilesPeer::USR_UID)) {
            $criteria->add(ProcessFilesPeer::USR_UID, $this->usr_uid);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_UPDATE_USR_UID)) {
            $criteria->add(ProcessFilesPeer::PRF_UPDATE_USR_UID, $this->prf_update_usr_uid);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_PATH)) {
            $criteria->add(ProcessFilesPeer::PRF_PATH, $this->prf_path);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_TYPE)) {
            $criteria->add(ProcessFilesPeer::PRF_TYPE, $this->prf_type);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_EDITABLE)) {
            $criteria->add(ProcessFilesPeer::PRF_EDITABLE, $this->prf_editable);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_CREATE_DATE)) {
            $criteria->add(ProcessFilesPeer::PRF_CREATE_DATE, $this->prf_create_date);
        }

        if ($this->isColumnModified(ProcessFilesPeer::PRF_UPDATE_DATE)) {
            $criteria->add(ProcessFilesPeer::PRF_UPDATE_DATE, $this->prf_update_date);
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
        $criteria = new Criteria(ProcessFilesPeer::DATABASE_NAME);

        $criteria->add(ProcessFilesPeer::PRF_UID, $this->prf_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getPrfUid();
    }

    /**
     * Generic method to set the primary key (prf_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setPrfUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of ProcessFiles (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setUsrUid($this->usr_uid);

        $copyObj->setPrfUpdateUsrUid($this->prf_update_usr_uid);

        $copyObj->setPrfPath($this->prf_path);

        $copyObj->setPrfType($this->prf_type);

        $copyObj->setPrfEditable($this->prf_editable);

        $copyObj->setPrfCreateDate($this->prf_create_date);

        $copyObj->setPrfUpdateDate($this->prf_update_date);


        $copyObj->setNew(true);

        $copyObj->setPrfUid(NULL); // this is a pkey column, so set to default value

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
     * @return     ProcessFiles Clone of current object.
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
     * @return     ProcessFilesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ProcessFilesPeer();
        }
        return self::$peer;
    }
}

