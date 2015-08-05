<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/TimerEventPeer.php';

/**
 * Base class that represents a row from the 'TIMER_EVENT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseTimerEvent extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TimerEventPeer
    */
    protected static $peer;

    /**
     * The value for the tmrevn_uid field.
     * @var        string
     */
    protected $tmrevn_uid;

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
     * The value for the tmrevn_option field.
     * @var        string
     */
    protected $tmrevn_option = 'DAILY';

    /**
     * The value for the tmrevn_start_date field.
     * @var        int
     */
    protected $tmrevn_start_date;

    /**
     * The value for the tmrevn_end_date field.
     * @var        int
     */
    protected $tmrevn_end_date;

    /**
     * The value for the tmrevn_day field.
     * @var        string
     */
    protected $tmrevn_day = '';

    /**
     * The value for the tmrevn_hour field.
     * @var        string
     */
    protected $tmrevn_hour = '';

    /**
     * The value for the tmrevn_minute field.
     * @var        string
     */
    protected $tmrevn_minute = '';

    /**
     * The value for the tmrevn_configuration_data field.
     * @var        string
     */
    protected $tmrevn_configuration_data = '';

    /**
     * The value for the tmrevn_next_run_date field.
     * @var        int
     */
    protected $tmrevn_next_run_date;

    /**
     * The value for the tmrevn_last_run_date field.
     * @var        int
     */
    protected $tmrevn_last_run_date;

    /**
     * The value for the tmrevn_last_execution_date field.
     * @var        int
     */
    protected $tmrevn_last_execution_date;

    /**
     * The value for the tmrevn_status field.
     * @var        string
     */
    protected $tmrevn_status = 'ACTIVE';

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
     * Get the [tmrevn_uid] column value.
     * 
     * @return     string
     */
    public function getTmrevnUid()
    {

        return $this->tmrevn_uid;
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
     * Get the [tmrevn_option] column value.
     * 
     * @return     string
     */
    public function getTmrevnOption()
    {

        return $this->tmrevn_option;
    }

    /**
     * Get the [optionally formatted] [tmrevn_start_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getTmrevnStartDate($format = 'Y-m-d')
    {

        if ($this->tmrevn_start_date === null || $this->tmrevn_start_date === '') {
            return null;
        } elseif (!is_int($this->tmrevn_start_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->tmrevn_start_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [tmrevn_start_date] as date/time value: " .
                    var_export($this->tmrevn_start_date, true));
            }
        } else {
            $ts = $this->tmrevn_start_date;
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
     * Get the [optionally formatted] [tmrevn_end_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getTmrevnEndDate($format = 'Y-m-d')
    {

        if ($this->tmrevn_end_date === null || $this->tmrevn_end_date === '') {
            return null;
        } elseif (!is_int($this->tmrevn_end_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->tmrevn_end_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [tmrevn_end_date] as date/time value: " .
                    var_export($this->tmrevn_end_date, true));
            }
        } else {
            $ts = $this->tmrevn_end_date;
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
     * Get the [tmrevn_day] column value.
     * 
     * @return     string
     */
    public function getTmrevnDay()
    {

        return $this->tmrevn_day;
    }

    /**
     * Get the [tmrevn_hour] column value.
     * 
     * @return     string
     */
    public function getTmrevnHour()
    {

        return $this->tmrevn_hour;
    }

    /**
     * Get the [tmrevn_minute] column value.
     * 
     * @return     string
     */
    public function getTmrevnMinute()
    {

        return $this->tmrevn_minute;
    }

    /**
     * Get the [tmrevn_configuration_data] column value.
     * 
     * @return     string
     */
    public function getTmrevnConfigurationData()
    {

        return $this->tmrevn_configuration_data;
    }

    /**
     * Get the [optionally formatted] [tmrevn_next_run_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getTmrevnNextRunDate($format = 'Y-m-d H:i:s')
    {

        if ($this->tmrevn_next_run_date === null || $this->tmrevn_next_run_date === '') {
            return null;
        } elseif (!is_int($this->tmrevn_next_run_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->tmrevn_next_run_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [tmrevn_next_run_date] as date/time value: " .
                    var_export($this->tmrevn_next_run_date, true));
            }
        } else {
            $ts = $this->tmrevn_next_run_date;
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
     * Get the [optionally formatted] [tmrevn_last_run_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getTmrevnLastRunDate($format = 'Y-m-d H:i:s')
    {

        if ($this->tmrevn_last_run_date === null || $this->tmrevn_last_run_date === '') {
            return null;
        } elseif (!is_int($this->tmrevn_last_run_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->tmrevn_last_run_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [tmrevn_last_run_date] as date/time value: " .
                    var_export($this->tmrevn_last_run_date, true));
            }
        } else {
            $ts = $this->tmrevn_last_run_date;
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
     * Get the [optionally formatted] [tmrevn_last_execution_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getTmrevnLastExecutionDate($format = 'Y-m-d H:i:s')
    {

        if ($this->tmrevn_last_execution_date === null || $this->tmrevn_last_execution_date === '') {
            return null;
        } elseif (!is_int($this->tmrevn_last_execution_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->tmrevn_last_execution_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [tmrevn_last_execution_date] as date/time value: " .
                    var_export($this->tmrevn_last_execution_date, true));
            }
        } else {
            $ts = $this->tmrevn_last_execution_date;
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
     * Get the [tmrevn_status] column value.
     * 
     * @return     string
     */
    public function getTmrevnStatus()
    {

        return $this->tmrevn_status;
    }

    /**
     * Set the value of [tmrevn_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_uid !== $v) {
            $this->tmrevn_uid = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_UID;
        }

    } // setTmrevnUid()

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
            $this->modifiedColumns[] = TimerEventPeer::PRJ_UID;
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
            $this->modifiedColumns[] = TimerEventPeer::EVN_UID;
        }

    } // setEvnUid()

    /**
     * Set the value of [tmrevn_option] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnOption($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_option !== $v || $v === 'DAILY') {
            $this->tmrevn_option = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_OPTION;
        }

    } // setTmrevnOption()

    /**
     * Set the value of [tmrevn_start_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTmrevnStartDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [tmrevn_start_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->tmrevn_start_date !== $ts) {
            $this->tmrevn_start_date = $ts;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_START_DATE;
        }

    } // setTmrevnStartDate()

    /**
     * Set the value of [tmrevn_end_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTmrevnEndDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [tmrevn_end_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->tmrevn_end_date !== $ts) {
            $this->tmrevn_end_date = $ts;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_END_DATE;
        }

    } // setTmrevnEndDate()

    /**
     * Set the value of [tmrevn_day] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnDay($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_day !== $v || $v === '') {
            $this->tmrevn_day = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_DAY;
        }

    } // setTmrevnDay()

    /**
     * Set the value of [tmrevn_hour] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnHour($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_hour !== $v || $v === '') {
            $this->tmrevn_hour = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_HOUR;
        }

    } // setTmrevnHour()

    /**
     * Set the value of [tmrevn_minute] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnMinute($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_minute !== $v || $v === '') {
            $this->tmrevn_minute = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_MINUTE;
        }

    } // setTmrevnMinute()

    /**
     * Set the value of [tmrevn_configuration_data] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnConfigurationData($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_configuration_data !== $v || $v === '') {
            $this->tmrevn_configuration_data = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_CONFIGURATION_DATA;
        }

    } // setTmrevnConfigurationData()

    /**
     * Set the value of [tmrevn_next_run_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTmrevnNextRunDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [tmrevn_next_run_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->tmrevn_next_run_date !== $ts) {
            $this->tmrevn_next_run_date = $ts;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_NEXT_RUN_DATE;
        }

    } // setTmrevnNextRunDate()

    /**
     * Set the value of [tmrevn_last_run_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTmrevnLastRunDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [tmrevn_last_run_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->tmrevn_last_run_date !== $ts) {
            $this->tmrevn_last_run_date = $ts;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_LAST_RUN_DATE;
        }

    } // setTmrevnLastRunDate()

    /**
     * Set the value of [tmrevn_last_execution_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTmrevnLastExecutionDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [tmrevn_last_execution_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->tmrevn_last_execution_date !== $ts) {
            $this->tmrevn_last_execution_date = $ts;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_LAST_EXECUTION_DATE;
        }

    } // setTmrevnLastExecutionDate()

    /**
     * Set the value of [tmrevn_status] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTmrevnStatus($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tmrevn_status !== $v || $v === 'ACTIVE') {
            $this->tmrevn_status = $v;
            $this->modifiedColumns[] = TimerEventPeer::TMREVN_STATUS;
        }

    } // setTmrevnStatus()

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

            $this->tmrevn_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->evn_uid = $rs->getString($startcol + 2);

            $this->tmrevn_option = $rs->getString($startcol + 3);

            $this->tmrevn_start_date = $rs->getDate($startcol + 4, null);

            $this->tmrevn_end_date = $rs->getDate($startcol + 5, null);

            $this->tmrevn_day = $rs->getString($startcol + 6);

            $this->tmrevn_hour = $rs->getString($startcol + 7);

            $this->tmrevn_minute = $rs->getString($startcol + 8);

            $this->tmrevn_configuration_data = $rs->getString($startcol + 9);

            $this->tmrevn_next_run_date = $rs->getTimestamp($startcol + 10, null);

            $this->tmrevn_last_run_date = $rs->getTimestamp($startcol + 11, null);

            $this->tmrevn_last_execution_date = $rs->getTimestamp($startcol + 12, null);

            $this->tmrevn_status = $rs->getString($startcol + 13);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 14; // 14 = TimerEventPeer::NUM_COLUMNS - TimerEventPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating TimerEvent object", $e);
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
            $con = Propel::getConnection(TimerEventPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            TimerEventPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(TimerEventPeer::DATABASE_NAME);
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
                    $pk = TimerEventPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += TimerEventPeer::doUpdate($this, $con);
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


            if (($retval = TimerEventPeer::doValidate($this, $columns)) !== true) {
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
        $pos = TimerEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTmrevnUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getEvnUid();
                break;
            case 3:
                return $this->getTmrevnOption();
                break;
            case 4:
                return $this->getTmrevnStartDate();
                break;
            case 5:
                return $this->getTmrevnEndDate();
                break;
            case 6:
                return $this->getTmrevnDay();
                break;
            case 7:
                return $this->getTmrevnHour();
                break;
            case 8:
                return $this->getTmrevnMinute();
                break;
            case 9:
                return $this->getTmrevnConfigurationData();
                break;
            case 10:
                return $this->getTmrevnNextRunDate();
                break;
            case 11:
                return $this->getTmrevnLastRunDate();
                break;
            case 12:
                return $this->getTmrevnLastExecutionDate();
                break;
            case 13:
                return $this->getTmrevnStatus();
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
        $keys = TimerEventPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getTmrevnUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getEvnUid(),
            $keys[3] => $this->getTmrevnOption(),
            $keys[4] => $this->getTmrevnStartDate(),
            $keys[5] => $this->getTmrevnEndDate(),
            $keys[6] => $this->getTmrevnDay(),
            $keys[7] => $this->getTmrevnHour(),
            $keys[8] => $this->getTmrevnMinute(),
            $keys[9] => $this->getTmrevnConfigurationData(),
            $keys[10] => $this->getTmrevnNextRunDate(),
            $keys[11] => $this->getTmrevnLastRunDate(),
            $keys[12] => $this->getTmrevnLastExecutionDate(),
            $keys[13] => $this->getTmrevnStatus(),
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
        $pos = TimerEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setTmrevnUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setEvnUid($value);
                break;
            case 3:
                $this->setTmrevnOption($value);
                break;
            case 4:
                $this->setTmrevnStartDate($value);
                break;
            case 5:
                $this->setTmrevnEndDate($value);
                break;
            case 6:
                $this->setTmrevnDay($value);
                break;
            case 7:
                $this->setTmrevnHour($value);
                break;
            case 8:
                $this->setTmrevnMinute($value);
                break;
            case 9:
                $this->setTmrevnConfigurationData($value);
                break;
            case 10:
                $this->setTmrevnNextRunDate($value);
                break;
            case 11:
                $this->setTmrevnLastRunDate($value);
                break;
            case 12:
                $this->setTmrevnLastExecutionDate($value);
                break;
            case 13:
                $this->setTmrevnStatus($value);
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
        $keys = TimerEventPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setTmrevnUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setEvnUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setTmrevnOption($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setTmrevnStartDate($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setTmrevnEndDate($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setTmrevnDay($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setTmrevnHour($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setTmrevnMinute($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setTmrevnConfigurationData($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setTmrevnNextRunDate($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setTmrevnLastRunDate($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setTmrevnLastExecutionDate($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setTmrevnStatus($arr[$keys[13]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TimerEventPeer::DATABASE_NAME);

        if ($this->isColumnModified(TimerEventPeer::TMREVN_UID)) {
            $criteria->add(TimerEventPeer::TMREVN_UID, $this->tmrevn_uid);
        }

        if ($this->isColumnModified(TimerEventPeer::PRJ_UID)) {
            $criteria->add(TimerEventPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(TimerEventPeer::EVN_UID)) {
            $criteria->add(TimerEventPeer::EVN_UID, $this->evn_uid);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_OPTION)) {
            $criteria->add(TimerEventPeer::TMREVN_OPTION, $this->tmrevn_option);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_START_DATE)) {
            $criteria->add(TimerEventPeer::TMREVN_START_DATE, $this->tmrevn_start_date);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_END_DATE)) {
            $criteria->add(TimerEventPeer::TMREVN_END_DATE, $this->tmrevn_end_date);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_DAY)) {
            $criteria->add(TimerEventPeer::TMREVN_DAY, $this->tmrevn_day);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_HOUR)) {
            $criteria->add(TimerEventPeer::TMREVN_HOUR, $this->tmrevn_hour);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_MINUTE)) {
            $criteria->add(TimerEventPeer::TMREVN_MINUTE, $this->tmrevn_minute);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_CONFIGURATION_DATA)) {
            $criteria->add(TimerEventPeer::TMREVN_CONFIGURATION_DATA, $this->tmrevn_configuration_data);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_NEXT_RUN_DATE)) {
            $criteria->add(TimerEventPeer::TMREVN_NEXT_RUN_DATE, $this->tmrevn_next_run_date);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_LAST_RUN_DATE)) {
            $criteria->add(TimerEventPeer::TMREVN_LAST_RUN_DATE, $this->tmrevn_last_run_date);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_LAST_EXECUTION_DATE)) {
            $criteria->add(TimerEventPeer::TMREVN_LAST_EXECUTION_DATE, $this->tmrevn_last_execution_date);
        }

        if ($this->isColumnModified(TimerEventPeer::TMREVN_STATUS)) {
            $criteria->add(TimerEventPeer::TMREVN_STATUS, $this->tmrevn_status);
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
        $criteria = new Criteria(TimerEventPeer::DATABASE_NAME);

        $criteria->add(TimerEventPeer::TMREVN_UID, $this->tmrevn_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getTmrevnUid();
    }

    /**
     * Generic method to set the primary key (tmrevn_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setTmrevnUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of TimerEvent (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setEvnUid($this->evn_uid);

        $copyObj->setTmrevnOption($this->tmrevn_option);

        $copyObj->setTmrevnStartDate($this->tmrevn_start_date);

        $copyObj->setTmrevnEndDate($this->tmrevn_end_date);

        $copyObj->setTmrevnDay($this->tmrevn_day);

        $copyObj->setTmrevnHour($this->tmrevn_hour);

        $copyObj->setTmrevnMinute($this->tmrevn_minute);

        $copyObj->setTmrevnConfigurationData($this->tmrevn_configuration_data);

        $copyObj->setTmrevnNextRunDate($this->tmrevn_next_run_date);

        $copyObj->setTmrevnLastRunDate($this->tmrevn_last_run_date);

        $copyObj->setTmrevnLastExecutionDate($this->tmrevn_last_execution_date);

        $copyObj->setTmrevnStatus($this->tmrevn_status);


        $copyObj->setNew(true);

        $copyObj->setTmrevnUid(NULL); // this is a pkey column, so set to default value

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
     * @return     TimerEvent Clone of current object.
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
     * @return     TimerEventPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new TimerEventPeer();
        }
        return self::$peer;
    }
}

