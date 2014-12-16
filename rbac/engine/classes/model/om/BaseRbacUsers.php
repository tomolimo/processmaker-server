<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/RbacUsersPeer.php';

/**
 * Base class that represents a row from the 'RBAC_USERS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseRbacUsers extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        RbacUsersPeer
    */
    protected static $peer;

    /**
     * The value for the usr_uid field.
     * @var        string
     */
    protected $usr_uid = '';

    /**
     * The value for the usr_username field.
     * @var        string
     */
    protected $usr_username = '';

    /**
     * The value for the usr_password field.
     * @var        string
     */
    protected $usr_password = '';

    /**
     * The value for the usr_firstname field.
     * @var        string
     */
    protected $usr_firstname = '';

    /**
     * The value for the usr_lastname field.
     * @var        string
     */
    protected $usr_lastname = '';

    /**
     * The value for the usr_email field.
     * @var        string
     */
    protected $usr_email = '';

    /**
     * The value for the usr_due_date field.
     * @var        int
     */
    protected $usr_due_date;

    /**
     * The value for the usr_create_date field.
     * @var        int
     */
    protected $usr_create_date;

    /**
     * The value for the usr_update_date field.
     * @var        int
     */
    protected $usr_update_date;

    /**
     * The value for the usr_status field.
     * @var        int
     */
    protected $usr_status = 1;

    /**
     * The value for the usr_auth_type field.
     * @var        string
     */
    protected $usr_auth_type = '';

    /**
     * The value for the uid_auth_source field.
     * @var        string
     */
    protected $uid_auth_source = '';

    /**
     * The value for the usr_auth_user_dn field.
     * @var        string
     */
    protected $usr_auth_user_dn = '';

    /**
     * The value for the usr_auth_supervisor_dn field.
     * @var        string
     */
    protected $usr_auth_supervisor_dn = '';

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
     * Get the [usr_uid] column value.
     * 
     * @return     string
     */
    public function getUsrUid()
    {

        return $this->usr_uid;
    }

    /**
     * Get the [usr_username] column value.
     * 
     * @return     string
     */
    public function getUsrUsername()
    {

        return $this->usr_username;
    }

    /**
     * Get the [usr_password] column value.
     * 
     * @return     string
     */
    public function getUsrPassword()
    {

        return $this->usr_password;
    }

    /**
     * Get the [usr_firstname] column value.
     * 
     * @return     string
     */
    public function getUsrFirstname()
    {

        return $this->usr_firstname;
    }

    /**
     * Get the [usr_lastname] column value.
     * 
     * @return     string
     */
    public function getUsrLastname()
    {

        return $this->usr_lastname;
    }

    /**
     * Get the [usr_email] column value.
     * 
     * @return     string
     */
    public function getUsrEmail()
    {

        return $this->usr_email;
    }

    /**
     * Get the [optionally formatted] [usr_due_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getUsrDueDate($format = 'Y-m-d')
    {

        if ($this->usr_due_date === null || $this->usr_due_date === '') {
            return null;
        } elseif (!is_int($this->usr_due_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->usr_due_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [usr_due_date] as date/time value: " .
                    var_export($this->usr_due_date, true));
            }
        } else {
            $ts = $this->usr_due_date;
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
     * Get the [optionally formatted] [usr_create_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getUsrCreateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->usr_create_date === null || $this->usr_create_date === '') {
            return null;
        } elseif (!is_int($this->usr_create_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->usr_create_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [usr_create_date] as date/time value: " .
                    var_export($this->usr_create_date, true));
            }
        } else {
            $ts = $this->usr_create_date;
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
     * Get the [optionally formatted] [usr_update_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getUsrUpdateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->usr_update_date === null || $this->usr_update_date === '') {
            return null;
        } elseif (!is_int($this->usr_update_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->usr_update_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [usr_update_date] as date/time value: " .
                    var_export($this->usr_update_date, true));
            }
        } else {
            $ts = $this->usr_update_date;
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
     * Get the [usr_status] column value.
     * 
     * @return     int
     */
    public function getUsrStatus()
    {

        return $this->usr_status;
    }

    /**
     * Get the [usr_auth_type] column value.
     * 
     * @return     string
     */
    public function getUsrAuthType()
    {

        return $this->usr_auth_type;
    }

    /**
     * Get the [uid_auth_source] column value.
     * 
     * @return     string
     */
    public function getUidAuthSource()
    {

        return $this->uid_auth_source;
    }

    /**
     * Get the [usr_auth_user_dn] column value.
     * 
     * @return     string
     */
    public function getUsrAuthUserDn()
    {

        return $this->usr_auth_user_dn;
    }

    /**
     * Get the [usr_auth_supervisor_dn] column value.
     * 
     * @return     string
     */
    public function getUsrAuthSupervisorDn()
    {

        return $this->usr_auth_supervisor_dn;
    }

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
            $this->modifiedColumns[] = RbacUsersPeer::USR_UID;
        }

    } // setUsrUid()

    /**
     * Set the value of [usr_username] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrUsername($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_username !== $v || $v === '') {
            $this->usr_username = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_USERNAME;
        }

    } // setUsrUsername()

    /**
     * Set the value of [usr_password] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrPassword($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_password !== $v || $v === '') {
            $this->usr_password = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_PASSWORD;
        }

    } // setUsrPassword()

    /**
     * Set the value of [usr_firstname] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrFirstname($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_firstname !== $v || $v === '') {
            $this->usr_firstname = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_FIRSTNAME;
        }

    } // setUsrFirstname()

    /**
     * Set the value of [usr_lastname] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrLastname($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_lastname !== $v || $v === '') {
            $this->usr_lastname = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_LASTNAME;
        }

    } // setUsrLastname()

    /**
     * Set the value of [usr_email] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrEmail($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_email !== $v || $v === '') {
            $this->usr_email = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_EMAIL;
        }

    } // setUsrEmail()

    /**
     * Set the value of [usr_due_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setUsrDueDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [usr_due_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->usr_due_date !== $ts) {
            $this->usr_due_date = $ts;
            $this->modifiedColumns[] = RbacUsersPeer::USR_DUE_DATE;
        }

    } // setUsrDueDate()

    /**
     * Set the value of [usr_create_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setUsrCreateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [usr_create_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->usr_create_date !== $ts) {
            $this->usr_create_date = $ts;
            $this->modifiedColumns[] = RbacUsersPeer::USR_CREATE_DATE;
        }

    } // setUsrCreateDate()

    /**
     * Set the value of [usr_update_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setUsrUpdateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [usr_update_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->usr_update_date !== $ts) {
            $this->usr_update_date = $ts;
            $this->modifiedColumns[] = RbacUsersPeer::USR_UPDATE_DATE;
        }

    } // setUsrUpdateDate()

    /**
     * Set the value of [usr_status] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setUsrStatus($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->usr_status !== $v || $v === 1) {
            $this->usr_status = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_STATUS;
        }

    } // setUsrStatus()

    /**
     * Set the value of [usr_auth_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrAuthType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_auth_type !== $v || $v === '') {
            $this->usr_auth_type = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_AUTH_TYPE;
        }

    } // setUsrAuthType()

    /**
     * Set the value of [uid_auth_source] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUidAuthSource($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->uid_auth_source !== $v || $v === '') {
            $this->uid_auth_source = $v;
            $this->modifiedColumns[] = RbacUsersPeer::UID_AUTH_SOURCE;
        }

    } // setUidAuthSource()

    /**
     * Set the value of [usr_auth_user_dn] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrAuthUserDn($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_auth_user_dn !== $v || $v === '') {
            $this->usr_auth_user_dn = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_AUTH_USER_DN;
        }

    } // setUsrAuthUserDn()

    /**
     * Set the value of [usr_auth_supervisor_dn] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrAuthSupervisorDn($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_auth_supervisor_dn !== $v || $v === '') {
            $this->usr_auth_supervisor_dn = $v;
            $this->modifiedColumns[] = RbacUsersPeer::USR_AUTH_SUPERVISOR_DN;
        }

    } // setUsrAuthSupervisorDn()

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

            $this->usr_uid = $rs->getString($startcol + 0);

            $this->usr_username = $rs->getString($startcol + 1);

            $this->usr_password = $rs->getString($startcol + 2);

            $this->usr_firstname = $rs->getString($startcol + 3);

            $this->usr_lastname = $rs->getString($startcol + 4);

            $this->usr_email = $rs->getString($startcol + 5);

            $this->usr_due_date = $rs->getDate($startcol + 6, null);

            $this->usr_create_date = $rs->getTimestamp($startcol + 7, null);

            $this->usr_update_date = $rs->getTimestamp($startcol + 8, null);

            $this->usr_status = $rs->getInt($startcol + 9);

            $this->usr_auth_type = $rs->getString($startcol + 10);

            $this->uid_auth_source = $rs->getString($startcol + 11);

            $this->usr_auth_user_dn = $rs->getString($startcol + 12);

            $this->usr_auth_supervisor_dn = $rs->getString($startcol + 13);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 14; // 14 = RbacUsersPeer::NUM_COLUMNS - RbacUsersPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating RbacUsers object", $e);
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
            $con = Propel::getConnection(RbacUsersPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            RbacUsersPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(RbacUsersPeer::DATABASE_NAME);
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
                    $pk = RbacUsersPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += RbacUsersPeer::doUpdate($this, $con);
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


            if (($retval = RbacUsersPeer::doValidate($this, $columns)) !== true) {
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
        $pos = RbacUsersPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUsrUid();
                break;
            case 1:
                return $this->getUsrUsername();
                break;
            case 2:
                return $this->getUsrPassword();
                break;
            case 3:
                return $this->getUsrFirstname();
                break;
            case 4:
                return $this->getUsrLastname();
                break;
            case 5:
                return $this->getUsrEmail();
                break;
            case 6:
                return $this->getUsrDueDate();
                break;
            case 7:
                return $this->getUsrCreateDate();
                break;
            case 8:
                return $this->getUsrUpdateDate();
                break;
            case 9:
                return $this->getUsrStatus();
                break;
            case 10:
                return $this->getUsrAuthType();
                break;
            case 11:
                return $this->getUidAuthSource();
                break;
            case 12:
                return $this->getUsrAuthUserDn();
                break;
            case 13:
                return $this->getUsrAuthSupervisorDn();
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
        $keys = RbacUsersPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUsrUid(),
            $keys[1] => $this->getUsrUsername(),
            $keys[2] => $this->getUsrPassword(),
            $keys[3] => $this->getUsrFirstname(),
            $keys[4] => $this->getUsrLastname(),
            $keys[5] => $this->getUsrEmail(),
            $keys[6] => $this->getUsrDueDate(),
            $keys[7] => $this->getUsrCreateDate(),
            $keys[8] => $this->getUsrUpdateDate(),
            $keys[9] => $this->getUsrStatus(),
            $keys[10] => $this->getUsrAuthType(),
            $keys[11] => $this->getUidAuthSource(),
            $keys[12] => $this->getUsrAuthUserDn(),
            $keys[13] => $this->getUsrAuthSupervisorDn(),
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
        $pos = RbacUsersPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setUsrUid($value);
                break;
            case 1:
                $this->setUsrUsername($value);
                break;
            case 2:
                $this->setUsrPassword($value);
                break;
            case 3:
                $this->setUsrFirstname($value);
                break;
            case 4:
                $this->setUsrLastname($value);
                break;
            case 5:
                $this->setUsrEmail($value);
                break;
            case 6:
                $this->setUsrDueDate($value);
                break;
            case 7:
                $this->setUsrCreateDate($value);
                break;
            case 8:
                $this->setUsrUpdateDate($value);
                break;
            case 9:
                $this->setUsrStatus($value);
                break;
            case 10:
                $this->setUsrAuthType($value);
                break;
            case 11:
                $this->setUidAuthSource($value);
                break;
            case 12:
                $this->setUsrAuthUserDn($value);
                break;
            case 13:
                $this->setUsrAuthSupervisorDn($value);
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
        $keys = RbacUsersPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setUsrUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setUsrUsername($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setUsrPassword($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setUsrFirstname($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setUsrLastname($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setUsrEmail($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setUsrDueDate($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setUsrCreateDate($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setUsrUpdateDate($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setUsrStatus($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setUsrAuthType($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setUidAuthSource($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setUsrAuthUserDn($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setUsrAuthSupervisorDn($arr[$keys[13]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RbacUsersPeer::DATABASE_NAME);

        if ($this->isColumnModified(RbacUsersPeer::USR_UID)) {
            $criteria->add(RbacUsersPeer::USR_UID, $this->usr_uid);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_USERNAME)) {
            $criteria->add(RbacUsersPeer::USR_USERNAME, $this->usr_username);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_PASSWORD)) {
            $criteria->add(RbacUsersPeer::USR_PASSWORD, $this->usr_password);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_FIRSTNAME)) {
            $criteria->add(RbacUsersPeer::USR_FIRSTNAME, $this->usr_firstname);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_LASTNAME)) {
            $criteria->add(RbacUsersPeer::USR_LASTNAME, $this->usr_lastname);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_EMAIL)) {
            $criteria->add(RbacUsersPeer::USR_EMAIL, $this->usr_email);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_DUE_DATE)) {
            $criteria->add(RbacUsersPeer::USR_DUE_DATE, $this->usr_due_date);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_CREATE_DATE)) {
            $criteria->add(RbacUsersPeer::USR_CREATE_DATE, $this->usr_create_date);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_UPDATE_DATE)) {
            $criteria->add(RbacUsersPeer::USR_UPDATE_DATE, $this->usr_update_date);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_STATUS)) {
            $criteria->add(RbacUsersPeer::USR_STATUS, $this->usr_status);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_AUTH_TYPE)) {
            $criteria->add(RbacUsersPeer::USR_AUTH_TYPE, $this->usr_auth_type);
        }

        if ($this->isColumnModified(RbacUsersPeer::UID_AUTH_SOURCE)) {
            $criteria->add(RbacUsersPeer::UID_AUTH_SOURCE, $this->uid_auth_source);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_AUTH_USER_DN)) {
            $criteria->add(RbacUsersPeer::USR_AUTH_USER_DN, $this->usr_auth_user_dn);
        }

        if ($this->isColumnModified(RbacUsersPeer::USR_AUTH_SUPERVISOR_DN)) {
            $criteria->add(RbacUsersPeer::USR_AUTH_SUPERVISOR_DN, $this->usr_auth_supervisor_dn);
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
        $criteria = new Criteria(RbacUsersPeer::DATABASE_NAME);

        $criteria->add(RbacUsersPeer::USR_UID, $this->usr_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getUsrUid();
    }

    /**
     * Generic method to set the primary key (usr_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setUsrUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of RbacUsers (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setUsrUsername($this->usr_username);

        $copyObj->setUsrPassword($this->usr_password);

        $copyObj->setUsrFirstname($this->usr_firstname);

        $copyObj->setUsrLastname($this->usr_lastname);

        $copyObj->setUsrEmail($this->usr_email);

        $copyObj->setUsrDueDate($this->usr_due_date);

        $copyObj->setUsrCreateDate($this->usr_create_date);

        $copyObj->setUsrUpdateDate($this->usr_update_date);

        $copyObj->setUsrStatus($this->usr_status);

        $copyObj->setUsrAuthType($this->usr_auth_type);

        $copyObj->setUidAuthSource($this->uid_auth_source);

        $copyObj->setUsrAuthUserDn($this->usr_auth_user_dn);

        $copyObj->setUsrAuthSupervisorDn($this->usr_auth_supervisor_dn);


        $copyObj->setNew(true);

        $copyObj->setUsrUid(''); // this is a pkey column, so set to default value

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
     * @return     RbacUsers Clone of current object.
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
     * @return     RbacUsersPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new RbacUsersPeer();
        }
        return self::$peer;
    }
}

