<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnEventPeer.php';

/**
 * Base class that represents a row from the 'BPMN_EVENT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnEvent extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnEventPeer
    */
    protected static $peer;

    /**
     * The value for the evn_uid field.
     * @var        string
     */
    protected $evn_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid = '';

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid = '';

    /**
     * The value for the evn_name field.
     * @var        string
     */
    protected $evn_name;

    /**
     * The value for the evn_type field.
     * @var        string
     */
    protected $evn_type = '';

    /**
     * The value for the evn_marker field.
     * @var        string
     */
    protected $evn_marker = 'EMPTY';

    /**
     * The value for the evn_is_interrupting field.
     * @var        int
     */
    protected $evn_is_interrupting = 1;

    /**
     * The value for the evn_attached_to field.
     * @var        string
     */
    protected $evn_attached_to = '';

    /**
     * The value for the evn_cancel_activity field.
     * @var        int
     */
    protected $evn_cancel_activity = 0;

    /**
     * The value for the evn_activity_ref field.
     * @var        string
     */
    protected $evn_activity_ref = '';

    /**
     * The value for the evn_wait_for_completion field.
     * @var        int
     */
    protected $evn_wait_for_completion = 1;

    /**
     * The value for the evn_error_name field.
     * @var        string
     */
    protected $evn_error_name;

    /**
     * The value for the evn_error_code field.
     * @var        string
     */
    protected $evn_error_code;

    /**
     * The value for the evn_escalation_name field.
     * @var        string
     */
    protected $evn_escalation_name;

    /**
     * The value for the evn_escalation_code field.
     * @var        string
     */
    protected $evn_escalation_code;

    /**
     * The value for the evn_condition field.
     * @var        string
     */
    protected $evn_condition;

    /**
     * The value for the evn_message field.
     * @var        string
     */
    protected $evn_message;

    /**
     * The value for the evn_operation_name field.
     * @var        string
     */
    protected $evn_operation_name;

    /**
     * The value for the evn_operation_implementation_ref field.
     * @var        string
     */
    protected $evn_operation_implementation_ref;

    /**
     * The value for the evn_time_date field.
     * @var        string
     */
    protected $evn_time_date;

    /**
     * The value for the evn_time_cycle field.
     * @var        string
     */
    protected $evn_time_cycle;

    /**
     * The value for the evn_time_duration field.
     * @var        string
     */
    protected $evn_time_duration;

    /**
     * The value for the evn_behavior field.
     * @var        string
     */
    protected $evn_behavior = 'CATCH';

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * @var        BpmnProcess
     */
    protected $aBpmnProcess;

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
     * Get the [evn_uid] column value.
     * 
     * @return     string
     */
    public function getEvnUid()
    {

        return $this->evn_uid;
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
     * Get the [pro_uid] column value.
     * 
     * @return     string
     */
    public function getProUid()
    {

        return $this->pro_uid;
    }

    /**
     * Get the [evn_name] column value.
     * 
     * @return     string
     */
    public function getEvnName()
    {

        return $this->evn_name;
    }

    /**
     * Get the [evn_type] column value.
     * 
     * @return     string
     */
    public function getEvnType()
    {

        return $this->evn_type;
    }

    /**
     * Get the [evn_marker] column value.
     * 
     * @return     string
     */
    public function getEvnMarker()
    {

        return $this->evn_marker;
    }

    /**
     * Get the [evn_is_interrupting] column value.
     * 
     * @return     int
     */
    public function getEvnIsInterrupting()
    {

        return $this->evn_is_interrupting;
    }

    /**
     * Get the [evn_attached_to] column value.
     * 
     * @return     string
     */
    public function getEvnAttachedTo()
    {

        return $this->evn_attached_to;
    }

    /**
     * Get the [evn_cancel_activity] column value.
     * 
     * @return     int
     */
    public function getEvnCancelActivity()
    {

        return $this->evn_cancel_activity;
    }

    /**
     * Get the [evn_activity_ref] column value.
     * 
     * @return     string
     */
    public function getEvnActivityRef()
    {

        return $this->evn_activity_ref;
    }

    /**
     * Get the [evn_wait_for_completion] column value.
     * 
     * @return     int
     */
    public function getEvnWaitForCompletion()
    {

        return $this->evn_wait_for_completion;
    }

    /**
     * Get the [evn_error_name] column value.
     * 
     * @return     string
     */
    public function getEvnErrorName()
    {

        return $this->evn_error_name;
    }

    /**
     * Get the [evn_error_code] column value.
     * 
     * @return     string
     */
    public function getEvnErrorCode()
    {

        return $this->evn_error_code;
    }

    /**
     * Get the [evn_escalation_name] column value.
     * 
     * @return     string
     */
    public function getEvnEscalationName()
    {

        return $this->evn_escalation_name;
    }

    /**
     * Get the [evn_escalation_code] column value.
     * 
     * @return     string
     */
    public function getEvnEscalationCode()
    {

        return $this->evn_escalation_code;
    }

    /**
     * Get the [evn_condition] column value.
     * 
     * @return     string
     */
    public function getEvnCondition()
    {

        return $this->evn_condition;
    }

    /**
     * Get the [evn_message] column value.
     * 
     * @return     string
     */
    public function getEvnMessage()
    {

        return $this->evn_message;
    }

    /**
     * Get the [evn_operation_name] column value.
     * 
     * @return     string
     */
    public function getEvnOperationName()
    {

        return $this->evn_operation_name;
    }

    /**
     * Get the [evn_operation_implementation_ref] column value.
     * 
     * @return     string
     */
    public function getEvnOperationImplementationRef()
    {

        return $this->evn_operation_implementation_ref;
    }

    /**
     * Get the [evn_time_date] column value.
     * 
     * @return     string
     */
    public function getEvnTimeDate()
    {

        return $this->evn_time_date;
    }

    /**
     * Get the [evn_time_cycle] column value.
     * 
     * @return     string
     */
    public function getEvnTimeCycle()
    {

        return $this->evn_time_cycle;
    }

    /**
     * Get the [evn_time_duration] column value.
     * 
     * @return     string
     */
    public function getEvnTimeDuration()
    {

        return $this->evn_time_duration;
    }

    /**
     * Get the [evn_behavior] column value.
     * 
     * @return     string
     */
    public function getEvnBehavior()
    {

        return $this->evn_behavior;
    }

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

        if ($this->evn_uid !== $v || $v === '') {
            $this->evn_uid = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_UID;
        }

    } // setEvnUid()

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

        if ($this->prj_uid !== $v || $v === '') {
            $this->prj_uid = $v;
            $this->modifiedColumns[] = BpmnEventPeer::PRJ_UID;
        }

        if ($this->aBpmnProject !== null && $this->aBpmnProject->getPrjUid() !== $v) {
            $this->aBpmnProject = null;
        }

    } // setPrjUid()

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

        if ($this->pro_uid !== $v || $v === '') {
            $this->pro_uid = $v;
            $this->modifiedColumns[] = BpmnEventPeer::PRO_UID;
        }

        if ($this->aBpmnProcess !== null && $this->aBpmnProcess->getProUid() !== $v) {
            $this->aBpmnProcess = null;
        }

    } // setProUid()

    /**
     * Set the value of [evn_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_name !== $v) {
            $this->evn_name = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_NAME;
        }

    } // setEvnName()

    /**
     * Set the value of [evn_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_type !== $v || $v === '') {
            $this->evn_type = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_TYPE;
        }

    } // setEvnType()

    /**
     * Set the value of [evn_marker] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnMarker($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_marker !== $v || $v === 'EMPTY') {
            $this->evn_marker = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_MARKER;
        }

    } // setEvnMarker()

    /**
     * Set the value of [evn_is_interrupting] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setEvnIsInterrupting($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->evn_is_interrupting !== $v || $v === 1) {
            $this->evn_is_interrupting = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_IS_INTERRUPTING;
        }

    } // setEvnIsInterrupting()

    /**
     * Set the value of [evn_attached_to] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnAttachedTo($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_attached_to !== $v || $v === '') {
            $this->evn_attached_to = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ATTACHED_TO;
        }

    } // setEvnAttachedTo()

    /**
     * Set the value of [evn_cancel_activity] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setEvnCancelActivity($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->evn_cancel_activity !== $v || $v === 0) {
            $this->evn_cancel_activity = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_CANCEL_ACTIVITY;
        }

    } // setEvnCancelActivity()

    /**
     * Set the value of [evn_activity_ref] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnActivityRef($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_activity_ref !== $v || $v === '') {
            $this->evn_activity_ref = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ACTIVITY_REF;
        }

    } // setEvnActivityRef()

    /**
     * Set the value of [evn_wait_for_completion] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setEvnWaitForCompletion($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->evn_wait_for_completion !== $v || $v === 1) {
            $this->evn_wait_for_completion = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_WAIT_FOR_COMPLETION;
        }

    } // setEvnWaitForCompletion()

    /**
     * Set the value of [evn_error_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnErrorName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_error_name !== $v) {
            $this->evn_error_name = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ERROR_NAME;
        }

    } // setEvnErrorName()

    /**
     * Set the value of [evn_error_code] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnErrorCode($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_error_code !== $v) {
            $this->evn_error_code = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ERROR_CODE;
        }

    } // setEvnErrorCode()

    /**
     * Set the value of [evn_escalation_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnEscalationName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_escalation_name !== $v) {
            $this->evn_escalation_name = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ESCALATION_NAME;
        }

    } // setEvnEscalationName()

    /**
     * Set the value of [evn_escalation_code] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnEscalationCode($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_escalation_code !== $v) {
            $this->evn_escalation_code = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_ESCALATION_CODE;
        }

    } // setEvnEscalationCode()

    /**
     * Set the value of [evn_condition] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnCondition($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_condition !== $v) {
            $this->evn_condition = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_CONDITION;
        }

    } // setEvnCondition()

    /**
     * Set the value of [evn_message] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnMessage($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_message !== $v) {
            $this->evn_message = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_MESSAGE;
        }

    } // setEvnMessage()

    /**
     * Set the value of [evn_operation_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnOperationName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_operation_name !== $v) {
            $this->evn_operation_name = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_OPERATION_NAME;
        }

    } // setEvnOperationName()

    /**
     * Set the value of [evn_operation_implementation_ref] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnOperationImplementationRef($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_operation_implementation_ref !== $v) {
            $this->evn_operation_implementation_ref = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF;
        }

    } // setEvnOperationImplementationRef()

    /**
     * Set the value of [evn_time_date] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnTimeDate($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_time_date !== $v) {
            $this->evn_time_date = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_TIME_DATE;
        }

    } // setEvnTimeDate()

    /**
     * Set the value of [evn_time_cycle] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnTimeCycle($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_time_cycle !== $v) {
            $this->evn_time_cycle = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_TIME_CYCLE;
        }

    } // setEvnTimeCycle()

    /**
     * Set the value of [evn_time_duration] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnTimeDuration($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_time_duration !== $v) {
            $this->evn_time_duration = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_TIME_DURATION;
        }

    } // setEvnTimeDuration()

    /**
     * Set the value of [evn_behavior] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnBehavior($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_behavior !== $v || $v === 'CATCH') {
            $this->evn_behavior = $v;
            $this->modifiedColumns[] = BpmnEventPeer::EVN_BEHAVIOR;
        }

    } // setEvnBehavior()

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

            $this->evn_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->pro_uid = $rs->getString($startcol + 2);

            $this->evn_name = $rs->getString($startcol + 3);

            $this->evn_type = $rs->getString($startcol + 4);

            $this->evn_marker = $rs->getString($startcol + 5);

            $this->evn_is_interrupting = $rs->getInt($startcol + 6);

            $this->evn_attached_to = $rs->getString($startcol + 7);

            $this->evn_cancel_activity = $rs->getInt($startcol + 8);

            $this->evn_activity_ref = $rs->getString($startcol + 9);

            $this->evn_wait_for_completion = $rs->getInt($startcol + 10);

            $this->evn_error_name = $rs->getString($startcol + 11);

            $this->evn_error_code = $rs->getString($startcol + 12);

            $this->evn_escalation_name = $rs->getString($startcol + 13);

            $this->evn_escalation_code = $rs->getString($startcol + 14);

            $this->evn_condition = $rs->getString($startcol + 15);

            $this->evn_message = $rs->getString($startcol + 16);

            $this->evn_operation_name = $rs->getString($startcol + 17);

            $this->evn_operation_implementation_ref = $rs->getString($startcol + 18);

            $this->evn_time_date = $rs->getString($startcol + 19);

            $this->evn_time_cycle = $rs->getString($startcol + 20);

            $this->evn_time_duration = $rs->getString($startcol + 21);

            $this->evn_behavior = $rs->getString($startcol + 22);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 23; // 23 = BpmnEventPeer::NUM_COLUMNS - BpmnEventPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnEvent object", $e);
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
            $con = Propel::getConnection(BpmnEventPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnEventPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnEventPeer::DATABASE_NAME);
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


            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if ($this->aBpmnProject->isModified()) {
                    $affectedRows += $this->aBpmnProject->save($con);
                }
                $this->setBpmnProject($this->aBpmnProject);
            }

            if ($this->aBpmnProcess !== null) {
                if ($this->aBpmnProcess->isModified()) {
                    $affectedRows += $this->aBpmnProcess->save($con);
                }
                $this->setBpmnProcess($this->aBpmnProcess);
            }


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnEventPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnEventPeer::doUpdate($this, $con);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if (!$this->aBpmnProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnProject->getValidationFailures());
                }
            }

            if ($this->aBpmnProcess !== null) {
                if (!$this->aBpmnProcess->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnProcess->getValidationFailures());
                }
            }


            if (($retval = BpmnEventPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEvnUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getProUid();
                break;
            case 3:
                return $this->getEvnName();
                break;
            case 4:
                return $this->getEvnType();
                break;
            case 5:
                return $this->getEvnMarker();
                break;
            case 6:
                return $this->getEvnIsInterrupting();
                break;
            case 7:
                return $this->getEvnAttachedTo();
                break;
            case 8:
                return $this->getEvnCancelActivity();
                break;
            case 9:
                return $this->getEvnActivityRef();
                break;
            case 10:
                return $this->getEvnWaitForCompletion();
                break;
            case 11:
                return $this->getEvnErrorName();
                break;
            case 12:
                return $this->getEvnErrorCode();
                break;
            case 13:
                return $this->getEvnEscalationName();
                break;
            case 14:
                return $this->getEvnEscalationCode();
                break;
            case 15:
                return $this->getEvnCondition();
                break;
            case 16:
                return $this->getEvnMessage();
                break;
            case 17:
                return $this->getEvnOperationName();
                break;
            case 18:
                return $this->getEvnOperationImplementationRef();
                break;
            case 19:
                return $this->getEvnTimeDate();
                break;
            case 20:
                return $this->getEvnTimeCycle();
                break;
            case 21:
                return $this->getEvnTimeDuration();
                break;
            case 22:
                return $this->getEvnBehavior();
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
        $keys = BpmnEventPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEvnUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getProUid(),
            $keys[3] => $this->getEvnName(),
            $keys[4] => $this->getEvnType(),
            $keys[5] => $this->getEvnMarker(),
            $keys[6] => $this->getEvnIsInterrupting(),
            $keys[7] => $this->getEvnAttachedTo(),
            $keys[8] => $this->getEvnCancelActivity(),
            $keys[9] => $this->getEvnActivityRef(),
            $keys[10] => $this->getEvnWaitForCompletion(),
            $keys[11] => $this->getEvnErrorName(),
            $keys[12] => $this->getEvnErrorCode(),
            $keys[13] => $this->getEvnEscalationName(),
            $keys[14] => $this->getEvnEscalationCode(),
            $keys[15] => $this->getEvnCondition(),
            $keys[16] => $this->getEvnMessage(),
            $keys[17] => $this->getEvnOperationName(),
            $keys[18] => $this->getEvnOperationImplementationRef(),
            $keys[19] => $this->getEvnTimeDate(),
            $keys[20] => $this->getEvnTimeCycle(),
            $keys[21] => $this->getEvnTimeDuration(),
            $keys[22] => $this->getEvnBehavior(),
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
        $pos = BpmnEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setEvnUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setProUid($value);
                break;
            case 3:
                $this->setEvnName($value);
                break;
            case 4:
                $this->setEvnType($value);
                break;
            case 5:
                $this->setEvnMarker($value);
                break;
            case 6:
                $this->setEvnIsInterrupting($value);
                break;
            case 7:
                $this->setEvnAttachedTo($value);
                break;
            case 8:
                $this->setEvnCancelActivity($value);
                break;
            case 9:
                $this->setEvnActivityRef($value);
                break;
            case 10:
                $this->setEvnWaitForCompletion($value);
                break;
            case 11:
                $this->setEvnErrorName($value);
                break;
            case 12:
                $this->setEvnErrorCode($value);
                break;
            case 13:
                $this->setEvnEscalationName($value);
                break;
            case 14:
                $this->setEvnEscalationCode($value);
                break;
            case 15:
                $this->setEvnCondition($value);
                break;
            case 16:
                $this->setEvnMessage($value);
                break;
            case 17:
                $this->setEvnOperationName($value);
                break;
            case 18:
                $this->setEvnOperationImplementationRef($value);
                break;
            case 19:
                $this->setEvnTimeDate($value);
                break;
            case 20:
                $this->setEvnTimeCycle($value);
                break;
            case 21:
                $this->setEvnTimeDuration($value);
                break;
            case 22:
                $this->setEvnBehavior($value);
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
        $keys = BpmnEventPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEvnUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setProUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setEvnName($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setEvnType($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setEvnMarker($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setEvnIsInterrupting($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setEvnAttachedTo($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setEvnCancelActivity($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setEvnActivityRef($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setEvnWaitForCompletion($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setEvnErrorName($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setEvnErrorCode($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setEvnEscalationName($arr[$keys[13]]);
        }

        if (array_key_exists($keys[14], $arr)) {
            $this->setEvnEscalationCode($arr[$keys[14]]);
        }

        if (array_key_exists($keys[15], $arr)) {
            $this->setEvnCondition($arr[$keys[15]]);
        }

        if (array_key_exists($keys[16], $arr)) {
            $this->setEvnMessage($arr[$keys[16]]);
        }

        if (array_key_exists($keys[17], $arr)) {
            $this->setEvnOperationName($arr[$keys[17]]);
        }

        if (array_key_exists($keys[18], $arr)) {
            $this->setEvnOperationImplementationRef($arr[$keys[18]]);
        }

        if (array_key_exists($keys[19], $arr)) {
            $this->setEvnTimeDate($arr[$keys[19]]);
        }

        if (array_key_exists($keys[20], $arr)) {
            $this->setEvnTimeCycle($arr[$keys[20]]);
        }

        if (array_key_exists($keys[21], $arr)) {
            $this->setEvnTimeDuration($arr[$keys[21]]);
        }

        if (array_key_exists($keys[22], $arr)) {
            $this->setEvnBehavior($arr[$keys[22]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnEventPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnEventPeer::EVN_UID)) {
            $criteria->add(BpmnEventPeer::EVN_UID, $this->evn_uid);
        }

        if ($this->isColumnModified(BpmnEventPeer::PRJ_UID)) {
            $criteria->add(BpmnEventPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnEventPeer::PRO_UID)) {
            $criteria->add(BpmnEventPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_NAME)) {
            $criteria->add(BpmnEventPeer::EVN_NAME, $this->evn_name);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_TYPE)) {
            $criteria->add(BpmnEventPeer::EVN_TYPE, $this->evn_type);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_MARKER)) {
            $criteria->add(BpmnEventPeer::EVN_MARKER, $this->evn_marker);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_IS_INTERRUPTING)) {
            $criteria->add(BpmnEventPeer::EVN_IS_INTERRUPTING, $this->evn_is_interrupting);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ATTACHED_TO)) {
            $criteria->add(BpmnEventPeer::EVN_ATTACHED_TO, $this->evn_attached_to);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_CANCEL_ACTIVITY)) {
            $criteria->add(BpmnEventPeer::EVN_CANCEL_ACTIVITY, $this->evn_cancel_activity);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ACTIVITY_REF)) {
            $criteria->add(BpmnEventPeer::EVN_ACTIVITY_REF, $this->evn_activity_ref);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_WAIT_FOR_COMPLETION)) {
            $criteria->add(BpmnEventPeer::EVN_WAIT_FOR_COMPLETION, $this->evn_wait_for_completion);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ERROR_NAME)) {
            $criteria->add(BpmnEventPeer::EVN_ERROR_NAME, $this->evn_error_name);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ERROR_CODE)) {
            $criteria->add(BpmnEventPeer::EVN_ERROR_CODE, $this->evn_error_code);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ESCALATION_NAME)) {
            $criteria->add(BpmnEventPeer::EVN_ESCALATION_NAME, $this->evn_escalation_name);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_ESCALATION_CODE)) {
            $criteria->add(BpmnEventPeer::EVN_ESCALATION_CODE, $this->evn_escalation_code);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_CONDITION)) {
            $criteria->add(BpmnEventPeer::EVN_CONDITION, $this->evn_condition);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_MESSAGE)) {
            $criteria->add(BpmnEventPeer::EVN_MESSAGE, $this->evn_message);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_OPERATION_NAME)) {
            $criteria->add(BpmnEventPeer::EVN_OPERATION_NAME, $this->evn_operation_name);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF)) {
            $criteria->add(BpmnEventPeer::EVN_OPERATION_IMPLEMENTATION_REF, $this->evn_operation_implementation_ref);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_TIME_DATE)) {
            $criteria->add(BpmnEventPeer::EVN_TIME_DATE, $this->evn_time_date);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_TIME_CYCLE)) {
            $criteria->add(BpmnEventPeer::EVN_TIME_CYCLE, $this->evn_time_cycle);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_TIME_DURATION)) {
            $criteria->add(BpmnEventPeer::EVN_TIME_DURATION, $this->evn_time_duration);
        }

        if ($this->isColumnModified(BpmnEventPeer::EVN_BEHAVIOR)) {
            $criteria->add(BpmnEventPeer::EVN_BEHAVIOR, $this->evn_behavior);
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
        $criteria = new Criteria(BpmnEventPeer::DATABASE_NAME);

        $criteria->add(BpmnEventPeer::EVN_UID, $this->evn_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getEvnUid();
    }

    /**
     * Generic method to set the primary key (evn_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setEvnUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnEvent (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setEvnName($this->evn_name);

        $copyObj->setEvnType($this->evn_type);

        $copyObj->setEvnMarker($this->evn_marker);

        $copyObj->setEvnIsInterrupting($this->evn_is_interrupting);

        $copyObj->setEvnAttachedTo($this->evn_attached_to);

        $copyObj->setEvnCancelActivity($this->evn_cancel_activity);

        $copyObj->setEvnActivityRef($this->evn_activity_ref);

        $copyObj->setEvnWaitForCompletion($this->evn_wait_for_completion);

        $copyObj->setEvnErrorName($this->evn_error_name);

        $copyObj->setEvnErrorCode($this->evn_error_code);

        $copyObj->setEvnEscalationName($this->evn_escalation_name);

        $copyObj->setEvnEscalationCode($this->evn_escalation_code);

        $copyObj->setEvnCondition($this->evn_condition);

        $copyObj->setEvnMessage($this->evn_message);

        $copyObj->setEvnOperationName($this->evn_operation_name);

        $copyObj->setEvnOperationImplementationRef($this->evn_operation_implementation_ref);

        $copyObj->setEvnTimeDate($this->evn_time_date);

        $copyObj->setEvnTimeCycle($this->evn_time_cycle);

        $copyObj->setEvnTimeDuration($this->evn_time_duration);

        $copyObj->setEvnBehavior($this->evn_behavior);


        $copyObj->setNew(true);

        $copyObj->setEvnUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnEvent Clone of current object.
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
     * @return     BpmnEventPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnEventPeer();
        }
        return self::$peer;
    }

    /**
     * Declares an association between this object and a BpmnProject object.
     *
     * @param      BpmnProject $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnProject($v)
    {


        if ($v === null) {
            $this->setPrjUid('');
        } else {
            $this->setPrjUid($v->getPrjUid());
        }


        $this->aBpmnProject = $v;
    }


    /**
     * Get the associated BpmnProject object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnProject The associated BpmnProject object.
     * @throws     PropelException
     */
    public function getBpmnProject($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnProjectPeer.php';

        if ($this->aBpmnProject === null && (($this->prj_uid !== "" && $this->prj_uid !== null))) {

            $this->aBpmnProject = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);
               $obj->addBpmnProjects($this);
             */
        }
        return $this->aBpmnProject;
    }

    /**
     * Declares an association between this object and a BpmnProcess object.
     *
     * @param      BpmnProcess $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnProcess($v)
    {


        if ($v === null) {
            $this->setProUid('');
        } else {
            $this->setProUid($v->getProUid());
        }


        $this->aBpmnProcess = $v;
    }


    /**
     * Get the associated BpmnProcess object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnProcess The associated BpmnProcess object.
     * @throws     PropelException
     */
    public function getBpmnProcess($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnProcessPeer.php';

        if ($this->aBpmnProcess === null && (($this->pro_uid !== "" && $this->pro_uid !== null))) {

            $this->aBpmnProcess = BpmnProcessPeer::retrieveByPK($this->pro_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnProcessPeer::retrieveByPK($this->pro_uid, $con);
               $obj->addBpmnProcesss($this);
             */
        }
        return $this->aBpmnProcess;
    }
}

