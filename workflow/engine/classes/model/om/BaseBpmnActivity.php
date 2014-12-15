<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnActivityPeer.php';

/**
 * Base class that represents a row from the 'BPMN_ACTIVITY' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnActivity extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnActivityPeer
    */
    protected static $peer;

    /**
     * The value for the act_uid field.
     * @var        string
     */
    protected $act_uid = '';

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
     * The value for the act_name field.
     * @var        string
     */
    protected $act_name;

    /**
     * The value for the act_type field.
     * @var        string
     */
    protected $act_type = 'TASK';

    /**
     * The value for the act_is_for_compensation field.
     * @var        int
     */
    protected $act_is_for_compensation = 0;

    /**
     * The value for the act_start_quantity field.
     * @var        int
     */
    protected $act_start_quantity = 1;

    /**
     * The value for the act_completion_quantity field.
     * @var        int
     */
    protected $act_completion_quantity = 1;

    /**
     * The value for the act_task_type field.
     * @var        string
     */
    protected $act_task_type = 'EMPTY';

    /**
     * The value for the act_implementation field.
     * @var        string
     */
    protected $act_implementation;

    /**
     * The value for the act_instantiate field.
     * @var        int
     */
    protected $act_instantiate = 0;

    /**
     * The value for the act_script_type field.
     * @var        string
     */
    protected $act_script_type;

    /**
     * The value for the act_script field.
     * @var        string
     */
    protected $act_script;

    /**
     * The value for the act_loop_type field.
     * @var        string
     */
    protected $act_loop_type = 'NONE';

    /**
     * The value for the act_test_before field.
     * @var        int
     */
    protected $act_test_before = 0;

    /**
     * The value for the act_loop_maximum field.
     * @var        int
     */
    protected $act_loop_maximum = 0;

    /**
     * The value for the act_loop_condition field.
     * @var        string
     */
    protected $act_loop_condition;

    /**
     * The value for the act_loop_cardinality field.
     * @var        int
     */
    protected $act_loop_cardinality = 0;

    /**
     * The value for the act_loop_behavior field.
     * @var        string
     */
    protected $act_loop_behavior = 'NONE';

    /**
     * The value for the act_is_adhoc field.
     * @var        int
     */
    protected $act_is_adhoc = 0;

    /**
     * The value for the act_is_collapsed field.
     * @var        int
     */
    protected $act_is_collapsed = 1;

    /**
     * The value for the act_completion_condition field.
     * @var        string
     */
    protected $act_completion_condition;

    /**
     * The value for the act_ordering field.
     * @var        string
     */
    protected $act_ordering = 'PARALLEL';

    /**
     * The value for the act_cancel_remaining_instances field.
     * @var        int
     */
    protected $act_cancel_remaining_instances = 1;

    /**
     * The value for the act_protocol field.
     * @var        string
     */
    protected $act_protocol;

    /**
     * The value for the act_method field.
     * @var        string
     */
    protected $act_method;

    /**
     * The value for the act_is_global field.
     * @var        int
     */
    protected $act_is_global = 0;

    /**
     * The value for the act_referer field.
     * @var        string
     */
    protected $act_referer = '';

    /**
     * The value for the act_default_flow field.
     * @var        string
     */
    protected $act_default_flow = '';

    /**
     * The value for the act_master_diagram field.
     * @var        string
     */
    protected $act_master_diagram = '';

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
     * Get the [act_uid] column value.
     * 
     * @return     string
     */
    public function getActUid()
    {

        return $this->act_uid;
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
     * Get the [act_name] column value.
     * 
     * @return     string
     */
    public function getActName()
    {

        return $this->act_name;
    }

    /**
     * Get the [act_type] column value.
     * 
     * @return     string
     */
    public function getActType()
    {

        return $this->act_type;
    }

    /**
     * Get the [act_is_for_compensation] column value.
     * 
     * @return     int
     */
    public function getActIsForCompensation()
    {

        return $this->act_is_for_compensation;
    }

    /**
     * Get the [act_start_quantity] column value.
     * 
     * @return     int
     */
    public function getActStartQuantity()
    {

        return $this->act_start_quantity;
    }

    /**
     * Get the [act_completion_quantity] column value.
     * 
     * @return     int
     */
    public function getActCompletionQuantity()
    {

        return $this->act_completion_quantity;
    }

    /**
     * Get the [act_task_type] column value.
     * 
     * @return     string
     */
    public function getActTaskType()
    {

        return $this->act_task_type;
    }

    /**
     * Get the [act_implementation] column value.
     * 
     * @return     string
     */
    public function getActImplementation()
    {

        return $this->act_implementation;
    }

    /**
     * Get the [act_instantiate] column value.
     * 
     * @return     int
     */
    public function getActInstantiate()
    {

        return $this->act_instantiate;
    }

    /**
     * Get the [act_script_type] column value.
     * 
     * @return     string
     */
    public function getActScriptType()
    {

        return $this->act_script_type;
    }

    /**
     * Get the [act_script] column value.
     * 
     * @return     string
     */
    public function getActScript()
    {

        return $this->act_script;
    }

    /**
     * Get the [act_loop_type] column value.
     * 
     * @return     string
     */
    public function getActLoopType()
    {

        return $this->act_loop_type;
    }

    /**
     * Get the [act_test_before] column value.
     * 
     * @return     int
     */
    public function getActTestBefore()
    {

        return $this->act_test_before;
    }

    /**
     * Get the [act_loop_maximum] column value.
     * 
     * @return     int
     */
    public function getActLoopMaximum()
    {

        return $this->act_loop_maximum;
    }

    /**
     * Get the [act_loop_condition] column value.
     * 
     * @return     string
     */
    public function getActLoopCondition()
    {

        return $this->act_loop_condition;
    }

    /**
     * Get the [act_loop_cardinality] column value.
     * 
     * @return     int
     */
    public function getActLoopCardinality()
    {

        return $this->act_loop_cardinality;
    }

    /**
     * Get the [act_loop_behavior] column value.
     * 
     * @return     string
     */
    public function getActLoopBehavior()
    {

        return $this->act_loop_behavior;
    }

    /**
     * Get the [act_is_adhoc] column value.
     * 
     * @return     int
     */
    public function getActIsAdhoc()
    {

        return $this->act_is_adhoc;
    }

    /**
     * Get the [act_is_collapsed] column value.
     * 
     * @return     int
     */
    public function getActIsCollapsed()
    {

        return $this->act_is_collapsed;
    }

    /**
     * Get the [act_completion_condition] column value.
     * 
     * @return     string
     */
    public function getActCompletionCondition()
    {

        return $this->act_completion_condition;
    }

    /**
     * Get the [act_ordering] column value.
     * 
     * @return     string
     */
    public function getActOrdering()
    {

        return $this->act_ordering;
    }

    /**
     * Get the [act_cancel_remaining_instances] column value.
     * 
     * @return     int
     */
    public function getActCancelRemainingInstances()
    {

        return $this->act_cancel_remaining_instances;
    }

    /**
     * Get the [act_protocol] column value.
     * 
     * @return     string
     */
    public function getActProtocol()
    {

        return $this->act_protocol;
    }

    /**
     * Get the [act_method] column value.
     * 
     * @return     string
     */
    public function getActMethod()
    {

        return $this->act_method;
    }

    /**
     * Get the [act_is_global] column value.
     * 
     * @return     int
     */
    public function getActIsGlobal()
    {

        return $this->act_is_global;
    }

    /**
     * Get the [act_referer] column value.
     * 
     * @return     string
     */
    public function getActReferer()
    {

        return $this->act_referer;
    }

    /**
     * Get the [act_default_flow] column value.
     * 
     * @return     string
     */
    public function getActDefaultFlow()
    {

        return $this->act_default_flow;
    }

    /**
     * Get the [act_master_diagram] column value.
     * 
     * @return     string
     */
    public function getActMasterDiagram()
    {

        return $this->act_master_diagram;
    }

    /**
     * Set the value of [act_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_uid !== $v || $v === '') {
            $this->act_uid = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_UID;
        }

    } // setActUid()

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
            $this->modifiedColumns[] = BpmnActivityPeer::PRJ_UID;
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
            $this->modifiedColumns[] = BpmnActivityPeer::PRO_UID;
        }

        if ($this->aBpmnProcess !== null && $this->aBpmnProcess->getProUid() !== $v) {
            $this->aBpmnProcess = null;
        }

    } // setProUid()

    /**
     * Set the value of [act_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_name !== $v) {
            $this->act_name = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_NAME;
        }

    } // setActName()

    /**
     * Set the value of [act_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_type !== $v || $v === 'TASK') {
            $this->act_type = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_TYPE;
        }

    } // setActType()

    /**
     * Set the value of [act_is_for_compensation] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActIsForCompensation($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_is_for_compensation !== $v || $v === 0) {
            $this->act_is_for_compensation = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_IS_FOR_COMPENSATION;
        }

    } // setActIsForCompensation()

    /**
     * Set the value of [act_start_quantity] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActStartQuantity($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_start_quantity !== $v || $v === 1) {
            $this->act_start_quantity = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_START_QUANTITY;
        }

    } // setActStartQuantity()

    /**
     * Set the value of [act_completion_quantity] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActCompletionQuantity($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_completion_quantity !== $v || $v === 1) {
            $this->act_completion_quantity = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_COMPLETION_QUANTITY;
        }

    } // setActCompletionQuantity()

    /**
     * Set the value of [act_task_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActTaskType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_task_type !== $v || $v === 'EMPTY') {
            $this->act_task_type = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_TASK_TYPE;
        }

    } // setActTaskType()

    /**
     * Set the value of [act_implementation] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActImplementation($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_implementation !== $v) {
            $this->act_implementation = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_IMPLEMENTATION;
        }

    } // setActImplementation()

    /**
     * Set the value of [act_instantiate] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActInstantiate($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_instantiate !== $v || $v === 0) {
            $this->act_instantiate = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_INSTANTIATE;
        }

    } // setActInstantiate()

    /**
     * Set the value of [act_script_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActScriptType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_script_type !== $v) {
            $this->act_script_type = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_SCRIPT_TYPE;
        }

    } // setActScriptType()

    /**
     * Set the value of [act_script] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActScript($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_script !== $v) {
            $this->act_script = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_SCRIPT;
        }

    } // setActScript()

    /**
     * Set the value of [act_loop_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActLoopType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_loop_type !== $v || $v === 'NONE') {
            $this->act_loop_type = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_LOOP_TYPE;
        }

    } // setActLoopType()

    /**
     * Set the value of [act_test_before] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActTestBefore($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_test_before !== $v || $v === 0) {
            $this->act_test_before = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_TEST_BEFORE;
        }

    } // setActTestBefore()

    /**
     * Set the value of [act_loop_maximum] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActLoopMaximum($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_loop_maximum !== $v || $v === 0) {
            $this->act_loop_maximum = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_LOOP_MAXIMUM;
        }

    } // setActLoopMaximum()

    /**
     * Set the value of [act_loop_condition] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActLoopCondition($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_loop_condition !== $v) {
            $this->act_loop_condition = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_LOOP_CONDITION;
        }

    } // setActLoopCondition()

    /**
     * Set the value of [act_loop_cardinality] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActLoopCardinality($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_loop_cardinality !== $v || $v === 0) {
            $this->act_loop_cardinality = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_LOOP_CARDINALITY;
        }

    } // setActLoopCardinality()

    /**
     * Set the value of [act_loop_behavior] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActLoopBehavior($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_loop_behavior !== $v || $v === 'NONE') {
            $this->act_loop_behavior = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_LOOP_BEHAVIOR;
        }

    } // setActLoopBehavior()

    /**
     * Set the value of [act_is_adhoc] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActIsAdhoc($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_is_adhoc !== $v || $v === 0) {
            $this->act_is_adhoc = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_IS_ADHOC;
        }

    } // setActIsAdhoc()

    /**
     * Set the value of [act_is_collapsed] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActIsCollapsed($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_is_collapsed !== $v || $v === 1) {
            $this->act_is_collapsed = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_IS_COLLAPSED;
        }

    } // setActIsCollapsed()

    /**
     * Set the value of [act_completion_condition] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActCompletionCondition($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_completion_condition !== $v) {
            $this->act_completion_condition = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_COMPLETION_CONDITION;
        }

    } // setActCompletionCondition()

    /**
     * Set the value of [act_ordering] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActOrdering($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_ordering !== $v || $v === 'PARALLEL') {
            $this->act_ordering = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_ORDERING;
        }

    } // setActOrdering()

    /**
     * Set the value of [act_cancel_remaining_instances] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActCancelRemainingInstances($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_cancel_remaining_instances !== $v || $v === 1) {
            $this->act_cancel_remaining_instances = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES;
        }

    } // setActCancelRemainingInstances()

    /**
     * Set the value of [act_protocol] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActProtocol($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_protocol !== $v) {
            $this->act_protocol = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_PROTOCOL;
        }

    } // setActProtocol()

    /**
     * Set the value of [act_method] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActMethod($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_method !== $v) {
            $this->act_method = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_METHOD;
        }

    } // setActMethod()

    /**
     * Set the value of [act_is_global] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setActIsGlobal($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->act_is_global !== $v || $v === 0) {
            $this->act_is_global = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_IS_GLOBAL;
        }

    } // setActIsGlobal()

    /**
     * Set the value of [act_referer] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActReferer($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_referer !== $v || $v === '') {
            $this->act_referer = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_REFERER;
        }

    } // setActReferer()

    /**
     * Set the value of [act_default_flow] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActDefaultFlow($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_default_flow !== $v || $v === '') {
            $this->act_default_flow = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_DEFAULT_FLOW;
        }

    } // setActDefaultFlow()

    /**
     * Set the value of [act_master_diagram] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setActMasterDiagram($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->act_master_diagram !== $v || $v === '') {
            $this->act_master_diagram = $v;
            $this->modifiedColumns[] = BpmnActivityPeer::ACT_MASTER_DIAGRAM;
        }

    } // setActMasterDiagram()

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

            $this->act_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->pro_uid = $rs->getString($startcol + 2);

            $this->act_name = $rs->getString($startcol + 3);

            $this->act_type = $rs->getString($startcol + 4);

            $this->act_is_for_compensation = $rs->getInt($startcol + 5);

            $this->act_start_quantity = $rs->getInt($startcol + 6);

            $this->act_completion_quantity = $rs->getInt($startcol + 7);

            $this->act_task_type = $rs->getString($startcol + 8);

            $this->act_implementation = $rs->getString($startcol + 9);

            $this->act_instantiate = $rs->getInt($startcol + 10);

            $this->act_script_type = $rs->getString($startcol + 11);

            $this->act_script = $rs->getString($startcol + 12);

            $this->act_loop_type = $rs->getString($startcol + 13);

            $this->act_test_before = $rs->getInt($startcol + 14);

            $this->act_loop_maximum = $rs->getInt($startcol + 15);

            $this->act_loop_condition = $rs->getString($startcol + 16);

            $this->act_loop_cardinality = $rs->getInt($startcol + 17);

            $this->act_loop_behavior = $rs->getString($startcol + 18);

            $this->act_is_adhoc = $rs->getInt($startcol + 19);

            $this->act_is_collapsed = $rs->getInt($startcol + 20);

            $this->act_completion_condition = $rs->getString($startcol + 21);

            $this->act_ordering = $rs->getString($startcol + 22);

            $this->act_cancel_remaining_instances = $rs->getInt($startcol + 23);

            $this->act_protocol = $rs->getString($startcol + 24);

            $this->act_method = $rs->getString($startcol + 25);

            $this->act_is_global = $rs->getInt($startcol + 26);

            $this->act_referer = $rs->getString($startcol + 27);

            $this->act_default_flow = $rs->getString($startcol + 28);

            $this->act_master_diagram = $rs->getString($startcol + 29);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 30; // 30 = BpmnActivityPeer::NUM_COLUMNS - BpmnActivityPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnActivity object", $e);
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
            $con = Propel::getConnection(BpmnActivityPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnActivityPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnActivityPeer::DATABASE_NAME);
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
                    $pk = BpmnActivityPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnActivityPeer::doUpdate($this, $con);
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


            if (($retval = BpmnActivityPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnActivityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getActUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getProUid();
                break;
            case 3:
                return $this->getActName();
                break;
            case 4:
                return $this->getActType();
                break;
            case 5:
                return $this->getActIsForCompensation();
                break;
            case 6:
                return $this->getActStartQuantity();
                break;
            case 7:
                return $this->getActCompletionQuantity();
                break;
            case 8:
                return $this->getActTaskType();
                break;
            case 9:
                return $this->getActImplementation();
                break;
            case 10:
                return $this->getActInstantiate();
                break;
            case 11:
                return $this->getActScriptType();
                break;
            case 12:
                return $this->getActScript();
                break;
            case 13:
                return $this->getActLoopType();
                break;
            case 14:
                return $this->getActTestBefore();
                break;
            case 15:
                return $this->getActLoopMaximum();
                break;
            case 16:
                return $this->getActLoopCondition();
                break;
            case 17:
                return $this->getActLoopCardinality();
                break;
            case 18:
                return $this->getActLoopBehavior();
                break;
            case 19:
                return $this->getActIsAdhoc();
                break;
            case 20:
                return $this->getActIsCollapsed();
                break;
            case 21:
                return $this->getActCompletionCondition();
                break;
            case 22:
                return $this->getActOrdering();
                break;
            case 23:
                return $this->getActCancelRemainingInstances();
                break;
            case 24:
                return $this->getActProtocol();
                break;
            case 25:
                return $this->getActMethod();
                break;
            case 26:
                return $this->getActIsGlobal();
                break;
            case 27:
                return $this->getActReferer();
                break;
            case 28:
                return $this->getActDefaultFlow();
                break;
            case 29:
                return $this->getActMasterDiagram();
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
        $keys = BpmnActivityPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getActUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getProUid(),
            $keys[3] => $this->getActName(),
            $keys[4] => $this->getActType(),
            $keys[5] => $this->getActIsForCompensation(),
            $keys[6] => $this->getActStartQuantity(),
            $keys[7] => $this->getActCompletionQuantity(),
            $keys[8] => $this->getActTaskType(),
            $keys[9] => $this->getActImplementation(),
            $keys[10] => $this->getActInstantiate(),
            $keys[11] => $this->getActScriptType(),
            $keys[12] => $this->getActScript(),
            $keys[13] => $this->getActLoopType(),
            $keys[14] => $this->getActTestBefore(),
            $keys[15] => $this->getActLoopMaximum(),
            $keys[16] => $this->getActLoopCondition(),
            $keys[17] => $this->getActLoopCardinality(),
            $keys[18] => $this->getActLoopBehavior(),
            $keys[19] => $this->getActIsAdhoc(),
            $keys[20] => $this->getActIsCollapsed(),
            $keys[21] => $this->getActCompletionCondition(),
            $keys[22] => $this->getActOrdering(),
            $keys[23] => $this->getActCancelRemainingInstances(),
            $keys[24] => $this->getActProtocol(),
            $keys[25] => $this->getActMethod(),
            $keys[26] => $this->getActIsGlobal(),
            $keys[27] => $this->getActReferer(),
            $keys[28] => $this->getActDefaultFlow(),
            $keys[29] => $this->getActMasterDiagram(),
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
        $pos = BpmnActivityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setActUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setProUid($value);
                break;
            case 3:
                $this->setActName($value);
                break;
            case 4:
                $this->setActType($value);
                break;
            case 5:
                $this->setActIsForCompensation($value);
                break;
            case 6:
                $this->setActStartQuantity($value);
                break;
            case 7:
                $this->setActCompletionQuantity($value);
                break;
            case 8:
                $this->setActTaskType($value);
                break;
            case 9:
                $this->setActImplementation($value);
                break;
            case 10:
                $this->setActInstantiate($value);
                break;
            case 11:
                $this->setActScriptType($value);
                break;
            case 12:
                $this->setActScript($value);
                break;
            case 13:
                $this->setActLoopType($value);
                break;
            case 14:
                $this->setActTestBefore($value);
                break;
            case 15:
                $this->setActLoopMaximum($value);
                break;
            case 16:
                $this->setActLoopCondition($value);
                break;
            case 17:
                $this->setActLoopCardinality($value);
                break;
            case 18:
                $this->setActLoopBehavior($value);
                break;
            case 19:
                $this->setActIsAdhoc($value);
                break;
            case 20:
                $this->setActIsCollapsed($value);
                break;
            case 21:
                $this->setActCompletionCondition($value);
                break;
            case 22:
                $this->setActOrdering($value);
                break;
            case 23:
                $this->setActCancelRemainingInstances($value);
                break;
            case 24:
                $this->setActProtocol($value);
                break;
            case 25:
                $this->setActMethod($value);
                break;
            case 26:
                $this->setActIsGlobal($value);
                break;
            case 27:
                $this->setActReferer($value);
                break;
            case 28:
                $this->setActDefaultFlow($value);
                break;
            case 29:
                $this->setActMasterDiagram($value);
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
        $keys = BpmnActivityPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setActUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setProUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setActName($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setActType($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setActIsForCompensation($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setActStartQuantity($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setActCompletionQuantity($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setActTaskType($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setActImplementation($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setActInstantiate($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setActScriptType($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setActScript($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setActLoopType($arr[$keys[13]]);
        }

        if (array_key_exists($keys[14], $arr)) {
            $this->setActTestBefore($arr[$keys[14]]);
        }

        if (array_key_exists($keys[15], $arr)) {
            $this->setActLoopMaximum($arr[$keys[15]]);
        }

        if (array_key_exists($keys[16], $arr)) {
            $this->setActLoopCondition($arr[$keys[16]]);
        }

        if (array_key_exists($keys[17], $arr)) {
            $this->setActLoopCardinality($arr[$keys[17]]);
        }

        if (array_key_exists($keys[18], $arr)) {
            $this->setActLoopBehavior($arr[$keys[18]]);
        }

        if (array_key_exists($keys[19], $arr)) {
            $this->setActIsAdhoc($arr[$keys[19]]);
        }

        if (array_key_exists($keys[20], $arr)) {
            $this->setActIsCollapsed($arr[$keys[20]]);
        }

        if (array_key_exists($keys[21], $arr)) {
            $this->setActCompletionCondition($arr[$keys[21]]);
        }

        if (array_key_exists($keys[22], $arr)) {
            $this->setActOrdering($arr[$keys[22]]);
        }

        if (array_key_exists($keys[23], $arr)) {
            $this->setActCancelRemainingInstances($arr[$keys[23]]);
        }

        if (array_key_exists($keys[24], $arr)) {
            $this->setActProtocol($arr[$keys[24]]);
        }

        if (array_key_exists($keys[25], $arr)) {
            $this->setActMethod($arr[$keys[25]]);
        }

        if (array_key_exists($keys[26], $arr)) {
            $this->setActIsGlobal($arr[$keys[26]]);
        }

        if (array_key_exists($keys[27], $arr)) {
            $this->setActReferer($arr[$keys[27]]);
        }

        if (array_key_exists($keys[28], $arr)) {
            $this->setActDefaultFlow($arr[$keys[28]]);
        }

        if (array_key_exists($keys[29], $arr)) {
            $this->setActMasterDiagram($arr[$keys[29]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnActivityPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnActivityPeer::ACT_UID)) {
            $criteria->add(BpmnActivityPeer::ACT_UID, $this->act_uid);
        }

        if ($this->isColumnModified(BpmnActivityPeer::PRJ_UID)) {
            $criteria->add(BpmnActivityPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnActivityPeer::PRO_UID)) {
            $criteria->add(BpmnActivityPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_NAME)) {
            $criteria->add(BpmnActivityPeer::ACT_NAME, $this->act_name);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_TYPE)) {
            $criteria->add(BpmnActivityPeer::ACT_TYPE, $this->act_type);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_IS_FOR_COMPENSATION)) {
            $criteria->add(BpmnActivityPeer::ACT_IS_FOR_COMPENSATION, $this->act_is_for_compensation);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_START_QUANTITY)) {
            $criteria->add(BpmnActivityPeer::ACT_START_QUANTITY, $this->act_start_quantity);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_COMPLETION_QUANTITY)) {
            $criteria->add(BpmnActivityPeer::ACT_COMPLETION_QUANTITY, $this->act_completion_quantity);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_TASK_TYPE)) {
            $criteria->add(BpmnActivityPeer::ACT_TASK_TYPE, $this->act_task_type);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_IMPLEMENTATION)) {
            $criteria->add(BpmnActivityPeer::ACT_IMPLEMENTATION, $this->act_implementation);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_INSTANTIATE)) {
            $criteria->add(BpmnActivityPeer::ACT_INSTANTIATE, $this->act_instantiate);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_SCRIPT_TYPE)) {
            $criteria->add(BpmnActivityPeer::ACT_SCRIPT_TYPE, $this->act_script_type);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_SCRIPT)) {
            $criteria->add(BpmnActivityPeer::ACT_SCRIPT, $this->act_script);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_LOOP_TYPE)) {
            $criteria->add(BpmnActivityPeer::ACT_LOOP_TYPE, $this->act_loop_type);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_TEST_BEFORE)) {
            $criteria->add(BpmnActivityPeer::ACT_TEST_BEFORE, $this->act_test_before);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_LOOP_MAXIMUM)) {
            $criteria->add(BpmnActivityPeer::ACT_LOOP_MAXIMUM, $this->act_loop_maximum);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_LOOP_CONDITION)) {
            $criteria->add(BpmnActivityPeer::ACT_LOOP_CONDITION, $this->act_loop_condition);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_LOOP_CARDINALITY)) {
            $criteria->add(BpmnActivityPeer::ACT_LOOP_CARDINALITY, $this->act_loop_cardinality);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_LOOP_BEHAVIOR)) {
            $criteria->add(BpmnActivityPeer::ACT_LOOP_BEHAVIOR, $this->act_loop_behavior);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_IS_ADHOC)) {
            $criteria->add(BpmnActivityPeer::ACT_IS_ADHOC, $this->act_is_adhoc);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_IS_COLLAPSED)) {
            $criteria->add(BpmnActivityPeer::ACT_IS_COLLAPSED, $this->act_is_collapsed);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_COMPLETION_CONDITION)) {
            $criteria->add(BpmnActivityPeer::ACT_COMPLETION_CONDITION, $this->act_completion_condition);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_ORDERING)) {
            $criteria->add(BpmnActivityPeer::ACT_ORDERING, $this->act_ordering);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES)) {
            $criteria->add(BpmnActivityPeer::ACT_CANCEL_REMAINING_INSTANCES, $this->act_cancel_remaining_instances);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_PROTOCOL)) {
            $criteria->add(BpmnActivityPeer::ACT_PROTOCOL, $this->act_protocol);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_METHOD)) {
            $criteria->add(BpmnActivityPeer::ACT_METHOD, $this->act_method);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_IS_GLOBAL)) {
            $criteria->add(BpmnActivityPeer::ACT_IS_GLOBAL, $this->act_is_global);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_REFERER)) {
            $criteria->add(BpmnActivityPeer::ACT_REFERER, $this->act_referer);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_DEFAULT_FLOW)) {
            $criteria->add(BpmnActivityPeer::ACT_DEFAULT_FLOW, $this->act_default_flow);
        }

        if ($this->isColumnModified(BpmnActivityPeer::ACT_MASTER_DIAGRAM)) {
            $criteria->add(BpmnActivityPeer::ACT_MASTER_DIAGRAM, $this->act_master_diagram);
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
        $criteria = new Criteria(BpmnActivityPeer::DATABASE_NAME);

        $criteria->add(BpmnActivityPeer::ACT_UID, $this->act_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getActUid();
    }

    /**
     * Generic method to set the primary key (act_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setActUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnActivity (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setActName($this->act_name);

        $copyObj->setActType($this->act_type);

        $copyObj->setActIsForCompensation($this->act_is_for_compensation);

        $copyObj->setActStartQuantity($this->act_start_quantity);

        $copyObj->setActCompletionQuantity($this->act_completion_quantity);

        $copyObj->setActTaskType($this->act_task_type);

        $copyObj->setActImplementation($this->act_implementation);

        $copyObj->setActInstantiate($this->act_instantiate);

        $copyObj->setActScriptType($this->act_script_type);

        $copyObj->setActScript($this->act_script);

        $copyObj->setActLoopType($this->act_loop_type);

        $copyObj->setActTestBefore($this->act_test_before);

        $copyObj->setActLoopMaximum($this->act_loop_maximum);

        $copyObj->setActLoopCondition($this->act_loop_condition);

        $copyObj->setActLoopCardinality($this->act_loop_cardinality);

        $copyObj->setActLoopBehavior($this->act_loop_behavior);

        $copyObj->setActIsAdhoc($this->act_is_adhoc);

        $copyObj->setActIsCollapsed($this->act_is_collapsed);

        $copyObj->setActCompletionCondition($this->act_completion_condition);

        $copyObj->setActOrdering($this->act_ordering);

        $copyObj->setActCancelRemainingInstances($this->act_cancel_remaining_instances);

        $copyObj->setActProtocol($this->act_protocol);

        $copyObj->setActMethod($this->act_method);

        $copyObj->setActIsGlobal($this->act_is_global);

        $copyObj->setActReferer($this->act_referer);

        $copyObj->setActDefaultFlow($this->act_default_flow);

        $copyObj->setActMasterDiagram($this->act_master_diagram);


        $copyObj->setNew(true);

        $copyObj->setActUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnActivity Clone of current object.
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
     * @return     BpmnActivityPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnActivityPeer();
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

