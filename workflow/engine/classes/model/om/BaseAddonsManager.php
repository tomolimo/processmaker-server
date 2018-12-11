<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AddonsManagerPeer.php';

/**
 * Base class that represents a row from the 'ADDONS_MANAGER' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAddonsManager extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AddonsManagerPeer
    */
    protected static $peer;

    /**
     * The value for the addon_id field.
     * @var        string
     */
    protected $addon_id;

    /**
     * The value for the store_id field.
     * @var        string
     */
    protected $store_id;

    /**
     * The value for the addon_name field.
     * @var        string
     */
    protected $addon_name;

    /**
     * The value for the addon_nick field.
     * @var        string
     */
    protected $addon_nick;

    /**
     * The value for the addon_download_filename field.
     * @var        string
     */
    protected $addon_download_filename;

    /**
     * The value for the addon_description field.
     * @var        string
     */
    protected $addon_description;

    /**
     * The value for the addon_state field.
     * @var        string
     */
    protected $addon_state = '';

    /**
     * The value for the addon_state_changed field.
     * @var        int
     */
    protected $addon_state_changed;

    /**
     * The value for the addon_status field.
     * @var        string
     */
    protected $addon_status;

    /**
     * The value for the addon_version field.
     * @var        string
     */
    protected $addon_version;

    /**
     * The value for the addon_type field.
     * @var        string
     */
    protected $addon_type;

    /**
     * The value for the addon_publisher field.
     * @var        string
     */
    protected $addon_publisher;

    /**
     * The value for the addon_release_date field.
     * @var        int
     */
    protected $addon_release_date;

    /**
     * The value for the addon_release_type field.
     * @var        string
     */
    protected $addon_release_type;

    /**
     * The value for the addon_release_notes field.
     * @var        string
     */
    protected $addon_release_notes;

    /**
     * The value for the addon_download_url field.
     * @var        string
     */
    protected $addon_download_url;

    /**
     * The value for the addon_download_progress field.
     * @var        double
     */
    protected $addon_download_progress;

    /**
     * The value for the addon_download_md5 field.
     * @var        string
     */
    protected $addon_download_md5;

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
     * Get the [addon_id] column value.
     * 
     * @return     string
     */
    public function getAddonId()
    {

        return $this->addon_id;
    }

    /**
     * Get the [store_id] column value.
     * 
     * @return     string
     */
    public function getStoreId()
    {

        return $this->store_id;
    }

    /**
     * Get the [addon_name] column value.
     * 
     * @return     string
     */
    public function getAddonName()
    {

        return $this->addon_name;
    }

    /**
     * Get the [addon_nick] column value.
     * 
     * @return     string
     */
    public function getAddonNick()
    {

        return $this->addon_nick;
    }

    /**
     * Get the [addon_download_filename] column value.
     * 
     * @return     string
     */
    public function getAddonDownloadFilename()
    {

        return $this->addon_download_filename;
    }

    /**
     * Get the [addon_description] column value.
     * 
     * @return     string
     */
    public function getAddonDescription()
    {

        return $this->addon_description;
    }

    /**
     * Get the [addon_state] column value.
     * 
     * @return     string
     */
    public function getAddonState()
    {

        return $this->addon_state;
    }

    /**
     * Get the [optionally formatted] [addon_state_changed] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getAddonStateChanged($format = 'Y-m-d H:i:s')
    {

        if ($this->addon_state_changed === null || $this->addon_state_changed === '') {
            return null;
        } elseif (!is_int($this->addon_state_changed)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->addon_state_changed);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [addon_state_changed] as date/time value: " .
                    var_export($this->addon_state_changed, true));
            }
        } else {
            $ts = $this->addon_state_changed;
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
     * Get the [addon_status] column value.
     * 
     * @return     string
     */
    public function getAddonStatus()
    {

        return $this->addon_status;
    }

    /**
     * Get the [addon_version] column value.
     * 
     * @return     string
     */
    public function getAddonVersion()
    {

        return $this->addon_version;
    }

    /**
     * Get the [addon_type] column value.
     * 
     * @return     string
     */
    public function getAddonType()
    {

        return $this->addon_type;
    }

    /**
     * Get the [addon_publisher] column value.
     * 
     * @return     string
     */
    public function getAddonPublisher()
    {

        return $this->addon_publisher;
    }

    /**
     * Get the [optionally formatted] [addon_release_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getAddonReleaseDate($format = 'Y-m-d H:i:s')
    {

        if ($this->addon_release_date === null || $this->addon_release_date === '') {
            return null;
        } elseif (!is_int($this->addon_release_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->addon_release_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [addon_release_date] as date/time value: " .
                    var_export($this->addon_release_date, true));
            }
        } else {
            $ts = $this->addon_release_date;
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
     * Get the [addon_release_type] column value.
     * 
     * @return     string
     */
    public function getAddonReleaseType()
    {

        return $this->addon_release_type;
    }

    /**
     * Get the [addon_release_notes] column value.
     * 
     * @return     string
     */
    public function getAddonReleaseNotes()
    {

        return $this->addon_release_notes;
    }

    /**
     * Get the [addon_download_url] column value.
     * 
     * @return     string
     */
    public function getAddonDownloadUrl()
    {

        return $this->addon_download_url;
    }

    /**
     * Get the [addon_download_progress] column value.
     * 
     * @return     double
     */
    public function getAddonDownloadProgress()
    {

        return $this->addon_download_progress;
    }

    /**
     * Get the [addon_download_md5] column value.
     * 
     * @return     string
     */
    public function getAddonDownloadMd5()
    {

        return $this->addon_download_md5;
    }

    /**
     * Set the value of [addon_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_id !== $v) {
            $this->addon_id = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_ID;
        }

    } // setAddonId()

    /**
     * Set the value of [store_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setStoreId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->store_id !== $v) {
            $this->store_id = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::STORE_ID;
        }

    } // setStoreId()

    /**
     * Set the value of [addon_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_name !== $v) {
            $this->addon_name = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_NAME;
        }

    } // setAddonName()

    /**
     * Set the value of [addon_nick] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonNick($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_nick !== $v) {
            $this->addon_nick = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_NICK;
        }

    } // setAddonNick()

    /**
     * Set the value of [addon_download_filename] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonDownloadFilename($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_download_filename !== $v) {
            $this->addon_download_filename = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME;
        }

    } // setAddonDownloadFilename()

    /**
     * Set the value of [addon_description] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonDescription($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_description !== $v) {
            $this->addon_description = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_DESCRIPTION;
        }

    } // setAddonDescription()

    /**
     * Set the value of [addon_state] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonState($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_state !== $v || $v === '') {
            $this->addon_state = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_STATE;
        }

    } // setAddonState()

    /**
     * Set the value of [addon_state_changed] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAddonStateChanged($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [addon_state_changed] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->addon_state_changed !== $ts) {
            $this->addon_state_changed = $ts;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_STATE_CHANGED;
        }

    } // setAddonStateChanged()

    /**
     * Set the value of [addon_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_status !== $v) {
            $this->addon_status = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_STATUS;
        }

    } // setAddonStatus()

    /**
     * Set the value of [addon_version] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonVersion($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_version !== $v) {
            $this->addon_version = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_VERSION;
        }

    } // setAddonVersion()

    /**
     * Set the value of [addon_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_type !== $v) {
            $this->addon_type = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_TYPE;
        }

    } // setAddonType()

    /**
     * Set the value of [addon_publisher] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonPublisher($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_publisher !== $v) {
            $this->addon_publisher = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_PUBLISHER;
        }

    } // setAddonPublisher()

    /**
     * Set the value of [addon_release_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAddonReleaseDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [addon_release_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->addon_release_date !== $ts) {
            $this->addon_release_date = $ts;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_RELEASE_DATE;
        }

    } // setAddonReleaseDate()

    /**
     * Set the value of [addon_release_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonReleaseType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_release_type !== $v) {
            $this->addon_release_type = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_RELEASE_TYPE;
        }

    } // setAddonReleaseType()

    /**
     * Set the value of [addon_release_notes] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonReleaseNotes($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_release_notes !== $v) {
            $this->addon_release_notes = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_RELEASE_NOTES;
        }

    } // setAddonReleaseNotes()

    /**
     * Set the value of [addon_download_url] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonDownloadUrl($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_download_url !== $v) {
            $this->addon_download_url = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_DOWNLOAD_URL;
        }

    } // setAddonDownloadUrl()

    /**
     * Set the value of [addon_download_progress] column.
     * 
     * @param      double $v new value
     * @return     void
     */
    public function setAddonDownloadProgress($v)
    {

        if ($this->addon_download_progress !== $v) {
            $this->addon_download_progress = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS;
        }

    } // setAddonDownloadProgress()

    /**
     * Set the value of [addon_download_md5] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAddonDownloadMd5($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->addon_download_md5 !== $v) {
            $this->addon_download_md5 = $v;
            $this->modifiedColumns[] = AddonsManagerPeer::ADDON_DOWNLOAD_MD5;
        }

    } // setAddonDownloadMd5()

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

            $this->addon_id = $rs->getString($startcol + 0);

            $this->store_id = $rs->getString($startcol + 1);

            $this->addon_name = $rs->getString($startcol + 2);

            $this->addon_nick = $rs->getString($startcol + 3);

            $this->addon_download_filename = $rs->getString($startcol + 4);

            $this->addon_description = $rs->getString($startcol + 5);

            $this->addon_state = $rs->getString($startcol + 6);

            $this->addon_state_changed = $rs->getTimestamp($startcol + 7, null);

            $this->addon_status = $rs->getString($startcol + 8);

            $this->addon_version = $rs->getString($startcol + 9);

            $this->addon_type = $rs->getString($startcol + 10);

            $this->addon_publisher = $rs->getString($startcol + 11);

            $this->addon_release_date = $rs->getTimestamp($startcol + 12, null);

            $this->addon_release_type = $rs->getString($startcol + 13);

            $this->addon_release_notes = $rs->getString($startcol + 14);

            $this->addon_download_url = $rs->getString($startcol + 15);

            $this->addon_download_progress = $rs->getFloat($startcol + 16);

            $this->addon_download_md5 = $rs->getString($startcol + 17);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 18; // 18 = AddonsManagerPeer::NUM_COLUMNS - AddonsManagerPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AddonsManager object", $e);
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
            $con = Propel::getConnection(AddonsManagerPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AddonsManagerPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AddonsManagerPeer::DATABASE_NAME);
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
                    $pk = AddonsManagerPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += AddonsManagerPeer::doUpdate($this, $con);
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


            if (($retval = AddonsManagerPeer::doValidate($this, $columns)) !== true) {
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
        $pos = AddonsManagerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAddonId();
                break;
            case 1:
                return $this->getStoreId();
                break;
            case 2:
                return $this->getAddonName();
                break;
            case 3:
                return $this->getAddonNick();
                break;
            case 4:
                return $this->getAddonDownloadFilename();
                break;
            case 5:
                return $this->getAddonDescription();
                break;
            case 6:
                return $this->getAddonState();
                break;
            case 7:
                return $this->getAddonStateChanged();
                break;
            case 8:
                return $this->getAddonStatus();
                break;
            case 9:
                return $this->getAddonVersion();
                break;
            case 10:
                return $this->getAddonType();
                break;
            case 11:
                return $this->getAddonPublisher();
                break;
            case 12:
                return $this->getAddonReleaseDate();
                break;
            case 13:
                return $this->getAddonReleaseType();
                break;
            case 14:
                return $this->getAddonReleaseNotes();
                break;
            case 15:
                return $this->getAddonDownloadUrl();
                break;
            case 16:
                return $this->getAddonDownloadProgress();
                break;
            case 17:
                return $this->getAddonDownloadMd5();
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
        $keys = AddonsManagerPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAddonId(),
            $keys[1] => $this->getStoreId(),
            $keys[2] => $this->getAddonName(),
            $keys[3] => $this->getAddonNick(),
            $keys[4] => $this->getAddonDownloadFilename(),
            $keys[5] => $this->getAddonDescription(),
            $keys[6] => $this->getAddonState(),
            $keys[7] => $this->getAddonStateChanged(),
            $keys[8] => $this->getAddonStatus(),
            $keys[9] => $this->getAddonVersion(),
            $keys[10] => $this->getAddonType(),
            $keys[11] => $this->getAddonPublisher(),
            $keys[12] => $this->getAddonReleaseDate(),
            $keys[13] => $this->getAddonReleaseType(),
            $keys[14] => $this->getAddonReleaseNotes(),
            $keys[15] => $this->getAddonDownloadUrl(),
            $keys[16] => $this->getAddonDownloadProgress(),
            $keys[17] => $this->getAddonDownloadMd5(),
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
        $pos = AddonsManagerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setAddonId($value);
                break;
            case 1:
                $this->setStoreId($value);
                break;
            case 2:
                $this->setAddonName($value);
                break;
            case 3:
                $this->setAddonNick($value);
                break;
            case 4:
                $this->setAddonDownloadFilename($value);
                break;
            case 5:
                $this->setAddonDescription($value);
                break;
            case 6:
                $this->setAddonState($value);
                break;
            case 7:
                $this->setAddonStateChanged($value);
                break;
            case 8:
                $this->setAddonStatus($value);
                break;
            case 9:
                $this->setAddonVersion($value);
                break;
            case 10:
                $this->setAddonType($value);
                break;
            case 11:
                $this->setAddonPublisher($value);
                break;
            case 12:
                $this->setAddonReleaseDate($value);
                break;
            case 13:
                $this->setAddonReleaseType($value);
                break;
            case 14:
                $this->setAddonReleaseNotes($value);
                break;
            case 15:
                $this->setAddonDownloadUrl($value);
                break;
            case 16:
                $this->setAddonDownloadProgress($value);
                break;
            case 17:
                $this->setAddonDownloadMd5($value);
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
        $keys = AddonsManagerPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAddonId($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setStoreId($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setAddonName($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setAddonNick($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setAddonDownloadFilename($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setAddonDescription($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setAddonState($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setAddonStateChanged($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setAddonStatus($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setAddonVersion($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setAddonType($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setAddonPublisher($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setAddonReleaseDate($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setAddonReleaseType($arr[$keys[13]]);
        }

        if (array_key_exists($keys[14], $arr)) {
            $this->setAddonReleaseNotes($arr[$keys[14]]);
        }

        if (array_key_exists($keys[15], $arr)) {
            $this->setAddonDownloadUrl($arr[$keys[15]]);
        }

        if (array_key_exists($keys[16], $arr)) {
            $this->setAddonDownloadProgress($arr[$keys[16]]);
        }

        if (array_key_exists($keys[17], $arr)) {
            $this->setAddonDownloadMd5($arr[$keys[17]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AddonsManagerPeer::DATABASE_NAME);

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_ID)) {
            $criteria->add(AddonsManagerPeer::ADDON_ID, $this->addon_id);
        }

        if ($this->isColumnModified(AddonsManagerPeer::STORE_ID)) {
            $criteria->add(AddonsManagerPeer::STORE_ID, $this->store_id);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_NAME)) {
            $criteria->add(AddonsManagerPeer::ADDON_NAME, $this->addon_name);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_NICK)) {
            $criteria->add(AddonsManagerPeer::ADDON_NICK, $this->addon_nick);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME)) {
            $criteria->add(AddonsManagerPeer::ADDON_DOWNLOAD_FILENAME, $this->addon_download_filename);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_DESCRIPTION)) {
            $criteria->add(AddonsManagerPeer::ADDON_DESCRIPTION, $this->addon_description);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_STATE)) {
            $criteria->add(AddonsManagerPeer::ADDON_STATE, $this->addon_state);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_STATE_CHANGED)) {
            $criteria->add(AddonsManagerPeer::ADDON_STATE_CHANGED, $this->addon_state_changed);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_STATUS)) {
            $criteria->add(AddonsManagerPeer::ADDON_STATUS, $this->addon_status);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_VERSION)) {
            $criteria->add(AddonsManagerPeer::ADDON_VERSION, $this->addon_version);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_TYPE)) {
            $criteria->add(AddonsManagerPeer::ADDON_TYPE, $this->addon_type);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_PUBLISHER)) {
            $criteria->add(AddonsManagerPeer::ADDON_PUBLISHER, $this->addon_publisher);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_RELEASE_DATE)) {
            $criteria->add(AddonsManagerPeer::ADDON_RELEASE_DATE, $this->addon_release_date);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_RELEASE_TYPE)) {
            $criteria->add(AddonsManagerPeer::ADDON_RELEASE_TYPE, $this->addon_release_type);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_RELEASE_NOTES)) {
            $criteria->add(AddonsManagerPeer::ADDON_RELEASE_NOTES, $this->addon_release_notes);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_DOWNLOAD_URL)) {
            $criteria->add(AddonsManagerPeer::ADDON_DOWNLOAD_URL, $this->addon_download_url);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS)) {
            $criteria->add(AddonsManagerPeer::ADDON_DOWNLOAD_PROGRESS, $this->addon_download_progress);
        }

        if ($this->isColumnModified(AddonsManagerPeer::ADDON_DOWNLOAD_MD5)) {
            $criteria->add(AddonsManagerPeer::ADDON_DOWNLOAD_MD5, $this->addon_download_md5);
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
        $criteria = new Criteria(AddonsManagerPeer::DATABASE_NAME);

        $criteria->add(AddonsManagerPeer::ADDON_ID, $this->addon_id);
        $criteria->add(AddonsManagerPeer::STORE_ID, $this->store_id);

        return $criteria;
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return     array
     */
    public function getPrimaryKey()
    {
        $pks = array();

        $pks[0] = $this->getAddonId();

        $pks[1] = $this->getStoreId();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param      array $keys The elements of the composite key (order must match the order in XML file).
     * @return     void
     */
    public function setPrimaryKey($keys)
    {

        $this->setAddonId($keys[0]);

        $this->setStoreId($keys[1]);

    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AddonsManager (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setAddonName($this->addon_name);

        $copyObj->setAddonNick($this->addon_nick);

        $copyObj->setAddonDownloadFilename($this->addon_download_filename);

        $copyObj->setAddonDescription($this->addon_description);

        $copyObj->setAddonState($this->addon_state);

        $copyObj->setAddonStateChanged($this->addon_state_changed);

        $copyObj->setAddonStatus($this->addon_status);

        $copyObj->setAddonVersion($this->addon_version);

        $copyObj->setAddonType($this->addon_type);

        $copyObj->setAddonPublisher($this->addon_publisher);

        $copyObj->setAddonReleaseDate($this->addon_release_date);

        $copyObj->setAddonReleaseType($this->addon_release_type);

        $copyObj->setAddonReleaseNotes($this->addon_release_notes);

        $copyObj->setAddonDownloadUrl($this->addon_download_url);

        $copyObj->setAddonDownloadProgress($this->addon_download_progress);

        $copyObj->setAddonDownloadMd5($this->addon_download_md5);


        $copyObj->setNew(true);

        $copyObj->setAddonId(NULL); // this is a pkey column, so set to default value

        $copyObj->setStoreId(NULL); // this is a pkey column, so set to default value

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
     * @return     AddonsManager Clone of current object.
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
     * @return     AddonsManagerPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AddonsManagerPeer();
        }
        return self::$peer;
    }
}

