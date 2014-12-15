<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnFlowPeer.php';

/**
 * Base class that represents a row from the 'BPMN_FLOW' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnFlow extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnFlowPeer
    */
    protected static $peer;

    /**
     * The value for the flo_uid field.
     * @var        string
     */
    protected $flo_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid = '';

    /**
     * The value for the dia_uid field.
     * @var        string
     */
    protected $dia_uid = '';

    /**
     * The value for the flo_type field.
     * @var        string
     */
    protected $flo_type = '';

    /**
     * The value for the flo_name field.
     * @var        string
     */
    protected $flo_name = '';

    /**
     * The value for the flo_element_origin field.
     * @var        string
     */
    protected $flo_element_origin = '';

    /**
     * The value for the flo_element_origin_type field.
     * @var        string
     */
    protected $flo_element_origin_type = '';

    /**
     * The value for the flo_element_origin_port field.
     * @var        int
     */
    protected $flo_element_origin_port = 0;

    /**
     * The value for the flo_element_dest field.
     * @var        string
     */
    protected $flo_element_dest = '';

    /**
     * The value for the flo_element_dest_type field.
     * @var        string
     */
    protected $flo_element_dest_type = '';

    /**
     * The value for the flo_element_dest_port field.
     * @var        int
     */
    protected $flo_element_dest_port = 0;

    /**
     * The value for the flo_is_inmediate field.
     * @var        int
     */
    protected $flo_is_inmediate;

    /**
     * The value for the flo_condition field.
     * @var        string
     */
    protected $flo_condition;

    /**
     * The value for the flo_x1 field.
     * @var        int
     */
    protected $flo_x1 = 0;

    /**
     * The value for the flo_y1 field.
     * @var        int
     */
    protected $flo_y1 = 0;

    /**
     * The value for the flo_x2 field.
     * @var        int
     */
    protected $flo_x2 = 0;

    /**
     * The value for the flo_y2 field.
     * @var        int
     */
    protected $flo_y2 = 0;

    /**
     * The value for the flo_state field.
     * @var        string
     */
    protected $flo_state;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * @var        BpmnDiagram
     */
    protected $aBpmnDiagram;

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
     * Get the [flo_uid] column value.
     * 
     * @return     string
     */
    public function getFloUid()
    {

        return $this->flo_uid;
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
     * Get the [dia_uid] column value.
     * 
     * @return     string
     */
    public function getDiaUid()
    {

        return $this->dia_uid;
    }

    /**
     * Get the [flo_type] column value.
     * 
     * @return     string
     */
    public function getFloType()
    {

        return $this->flo_type;
    }

    /**
     * Get the [flo_name] column value.
     * 
     * @return     string
     */
    public function getFloName()
    {

        return $this->flo_name;
    }

    /**
     * Get the [flo_element_origin] column value.
     * 
     * @return     string
     */
    public function getFloElementOrigin()
    {

        return $this->flo_element_origin;
    }

    /**
     * Get the [flo_element_origin_type] column value.
     * 
     * @return     string
     */
    public function getFloElementOriginType()
    {

        return $this->flo_element_origin_type;
    }

    /**
     * Get the [flo_element_origin_port] column value.
     * 
     * @return     int
     */
    public function getFloElementOriginPort()
    {

        return $this->flo_element_origin_port;
    }

    /**
     * Get the [flo_element_dest] column value.
     * 
     * @return     string
     */
    public function getFloElementDest()
    {

        return $this->flo_element_dest;
    }

    /**
     * Get the [flo_element_dest_type] column value.
     * 
     * @return     string
     */
    public function getFloElementDestType()
    {

        return $this->flo_element_dest_type;
    }

    /**
     * Get the [flo_element_dest_port] column value.
     * 
     * @return     int
     */
    public function getFloElementDestPort()
    {

        return $this->flo_element_dest_port;
    }

    /**
     * Get the [flo_is_inmediate] column value.
     * 
     * @return     int
     */
    public function getFloIsInmediate()
    {

        return $this->flo_is_inmediate;
    }

    /**
     * Get the [flo_condition] column value.
     * 
     * @return     string
     */
    public function getFloCondition()
    {

        return $this->flo_condition;
    }

    /**
     * Get the [flo_x1] column value.
     * 
     * @return     int
     */
    public function getFloX1()
    {

        return $this->flo_x1;
    }

    /**
     * Get the [flo_y1] column value.
     * 
     * @return     int
     */
    public function getFloY1()
    {

        return $this->flo_y1;
    }

    /**
     * Get the [flo_x2] column value.
     * 
     * @return     int
     */
    public function getFloX2()
    {

        return $this->flo_x2;
    }

    /**
     * Get the [flo_y2] column value.
     * 
     * @return     int
     */
    public function getFloY2()
    {

        return $this->flo_y2;
    }

    /**
     * Get the [flo_state] column value.
     * 
     * @return     string
     */
    public function getFloState()
    {

        return $this->flo_state;
    }

    /**
     * Set the value of [flo_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_uid !== $v || $v === '') {
            $this->flo_uid = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_UID;
        }

    } // setFloUid()

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
            $this->modifiedColumns[] = BpmnFlowPeer::PRJ_UID;
        }

        if ($this->aBpmnProject !== null && $this->aBpmnProject->getPrjUid() !== $v) {
            $this->aBpmnProject = null;
        }

    } // setPrjUid()

    /**
     * Set the value of [dia_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDiaUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dia_uid !== $v || $v === '') {
            $this->dia_uid = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::DIA_UID;
        }

        if ($this->aBpmnDiagram !== null && $this->aBpmnDiagram->getDiaUid() !== $v) {
            $this->aBpmnDiagram = null;
        }

    } // setDiaUid()

    /**
     * Set the value of [flo_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_type !== $v || $v === '') {
            $this->flo_type = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_TYPE;
        }

    } // setFloType()

    /**
     * Set the value of [flo_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_name !== $v || $v === '') {
            $this->flo_name = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_NAME;
        }

    } // setFloName()

    /**
     * Set the value of [flo_element_origin] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloElementOrigin($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_element_origin !== $v || $v === '') {
            $this->flo_element_origin = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_ORIGIN;
        }

    } // setFloElementOrigin()

    /**
     * Set the value of [flo_element_origin_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloElementOriginType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_element_origin_type !== $v || $v === '') {
            $this->flo_element_origin_type = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE;
        }

    } // setFloElementOriginType()

    /**
     * Set the value of [flo_element_origin_port] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloElementOriginPort($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_element_origin_port !== $v || $v === 0) {
            $this->flo_element_origin_port = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT;
        }

    } // setFloElementOriginPort()

    /**
     * Set the value of [flo_element_dest] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloElementDest($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_element_dest !== $v || $v === '') {
            $this->flo_element_dest = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_DEST;
        }

    } // setFloElementDest()

    /**
     * Set the value of [flo_element_dest_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloElementDestType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_element_dest_type !== $v || $v === '') {
            $this->flo_element_dest_type = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE;
        }

    } // setFloElementDestType()

    /**
     * Set the value of [flo_element_dest_port] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloElementDestPort($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_element_dest_port !== $v || $v === 0) {
            $this->flo_element_dest_port = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_ELEMENT_DEST_PORT;
        }

    } // setFloElementDestPort()

    /**
     * Set the value of [flo_is_inmediate] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloIsInmediate($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_is_inmediate !== $v) {
            $this->flo_is_inmediate = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_IS_INMEDIATE;
        }

    } // setFloIsInmediate()

    /**
     * Set the value of [flo_condition] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloCondition($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_condition !== $v) {
            $this->flo_condition = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_CONDITION;
        }

    } // setFloCondition()

    /**
     * Set the value of [flo_x1] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloX1($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_x1 !== $v || $v === 0) {
            $this->flo_x1 = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_X1;
        }

    } // setFloX1()

    /**
     * Set the value of [flo_y1] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloY1($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_y1 !== $v || $v === 0) {
            $this->flo_y1 = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_Y1;
        }

    } // setFloY1()

    /**
     * Set the value of [flo_x2] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloX2($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_x2 !== $v || $v === 0) {
            $this->flo_x2 = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_X2;
        }

    } // setFloX2()

    /**
     * Set the value of [flo_y2] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setFloY2($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->flo_y2 !== $v || $v === 0) {
            $this->flo_y2 = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_Y2;
        }

    } // setFloY2()

    /**
     * Set the value of [flo_state] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setFloState($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->flo_state !== $v) {
            $this->flo_state = $v;
            $this->modifiedColumns[] = BpmnFlowPeer::FLO_STATE;
        }

    } // setFloState()

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

            $this->flo_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->dia_uid = $rs->getString($startcol + 2);

            $this->flo_type = $rs->getString($startcol + 3);

            $this->flo_name = $rs->getString($startcol + 4);

            $this->flo_element_origin = $rs->getString($startcol + 5);

            $this->flo_element_origin_type = $rs->getString($startcol + 6);

            $this->flo_element_origin_port = $rs->getInt($startcol + 7);

            $this->flo_element_dest = $rs->getString($startcol + 8);

            $this->flo_element_dest_type = $rs->getString($startcol + 9);

            $this->flo_element_dest_port = $rs->getInt($startcol + 10);

            $this->flo_is_inmediate = $rs->getInt($startcol + 11);

            $this->flo_condition = $rs->getString($startcol + 12);

            $this->flo_x1 = $rs->getInt($startcol + 13);

            $this->flo_y1 = $rs->getInt($startcol + 14);

            $this->flo_x2 = $rs->getInt($startcol + 15);

            $this->flo_y2 = $rs->getInt($startcol + 16);

            $this->flo_state = $rs->getString($startcol + 17);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 18; // 18 = BpmnFlowPeer::NUM_COLUMNS - BpmnFlowPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnFlow object", $e);
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
            $con = Propel::getConnection(BpmnFlowPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnFlowPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnFlowPeer::DATABASE_NAME);
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

            if ($this->aBpmnDiagram !== null) {
                if ($this->aBpmnDiagram->isModified()) {
                    $affectedRows += $this->aBpmnDiagram->save($con);
                }
                $this->setBpmnDiagram($this->aBpmnDiagram);
            }


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnFlowPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnFlowPeer::doUpdate($this, $con);
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

            if ($this->aBpmnDiagram !== null) {
                if (!$this->aBpmnDiagram->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnDiagram->getValidationFailures());
                }
            }


            if (($retval = BpmnFlowPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnFlowPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getFloUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getDiaUid();
                break;
            case 3:
                return $this->getFloType();
                break;
            case 4:
                return $this->getFloName();
                break;
            case 5:
                return $this->getFloElementOrigin();
                break;
            case 6:
                return $this->getFloElementOriginType();
                break;
            case 7:
                return $this->getFloElementOriginPort();
                break;
            case 8:
                return $this->getFloElementDest();
                break;
            case 9:
                return $this->getFloElementDestType();
                break;
            case 10:
                return $this->getFloElementDestPort();
                break;
            case 11:
                return $this->getFloIsInmediate();
                break;
            case 12:
                return $this->getFloCondition();
                break;
            case 13:
                return $this->getFloX1();
                break;
            case 14:
                return $this->getFloY1();
                break;
            case 15:
                return $this->getFloX2();
                break;
            case 16:
                return $this->getFloY2();
                break;
            case 17:
                return $this->getFloState();
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
        $keys = BpmnFlowPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getFloUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getDiaUid(),
            $keys[3] => $this->getFloType(),
            $keys[4] => $this->getFloName(),
            $keys[5] => $this->getFloElementOrigin(),
            $keys[6] => $this->getFloElementOriginType(),
            $keys[7] => $this->getFloElementOriginPort(),
            $keys[8] => $this->getFloElementDest(),
            $keys[9] => $this->getFloElementDestType(),
            $keys[10] => $this->getFloElementDestPort(),
            $keys[11] => $this->getFloIsInmediate(),
            $keys[12] => $this->getFloCondition(),
            $keys[13] => $this->getFloX1(),
            $keys[14] => $this->getFloY1(),
            $keys[15] => $this->getFloX2(),
            $keys[16] => $this->getFloY2(),
            $keys[17] => $this->getFloState(),
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
        $pos = BpmnFlowPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setFloUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setDiaUid($value);
                break;
            case 3:
                $this->setFloType($value);
                break;
            case 4:
                $this->setFloName($value);
                break;
            case 5:
                $this->setFloElementOrigin($value);
                break;
            case 6:
                $this->setFloElementOriginType($value);
                break;
            case 7:
                $this->setFloElementOriginPort($value);
                break;
            case 8:
                $this->setFloElementDest($value);
                break;
            case 9:
                $this->setFloElementDestType($value);
                break;
            case 10:
                $this->setFloElementDestPort($value);
                break;
            case 11:
                $this->setFloIsInmediate($value);
                break;
            case 12:
                $this->setFloCondition($value);
                break;
            case 13:
                $this->setFloX1($value);
                break;
            case 14:
                $this->setFloY1($value);
                break;
            case 15:
                $this->setFloX2($value);
                break;
            case 16:
                $this->setFloY2($value);
                break;
            case 17:
                $this->setFloState($value);
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
        $keys = BpmnFlowPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setFloUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setDiaUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setFloType($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setFloName($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setFloElementOrigin($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setFloElementOriginType($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setFloElementOriginPort($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setFloElementDest($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setFloElementDestType($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setFloElementDestPort($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setFloIsInmediate($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setFloCondition($arr[$keys[12]]);
        }

        if (array_key_exists($keys[13], $arr)) {
            $this->setFloX1($arr[$keys[13]]);
        }

        if (array_key_exists($keys[14], $arr)) {
            $this->setFloY1($arr[$keys[14]]);
        }

        if (array_key_exists($keys[15], $arr)) {
            $this->setFloX2($arr[$keys[15]]);
        }

        if (array_key_exists($keys[16], $arr)) {
            $this->setFloY2($arr[$keys[16]]);
        }

        if (array_key_exists($keys[17], $arr)) {
            $this->setFloState($arr[$keys[17]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnFlowPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnFlowPeer::FLO_UID)) {
            $criteria->add(BpmnFlowPeer::FLO_UID, $this->flo_uid);
        }

        if ($this->isColumnModified(BpmnFlowPeer::PRJ_UID)) {
            $criteria->add(BpmnFlowPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnFlowPeer::DIA_UID)) {
            $criteria->add(BpmnFlowPeer::DIA_UID, $this->dia_uid);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_TYPE)) {
            $criteria->add(BpmnFlowPeer::FLO_TYPE, $this->flo_type);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_NAME)) {
            $criteria->add(BpmnFlowPeer::FLO_NAME, $this->flo_name);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_ORIGIN)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_ORIGIN, $this->flo_element_origin);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE, $this->flo_element_origin_type);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_ORIGIN_PORT, $this->flo_element_origin_port);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_DEST)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_DEST, $this->flo_element_dest);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE, $this->flo_element_dest_type);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_ELEMENT_DEST_PORT)) {
            $criteria->add(BpmnFlowPeer::FLO_ELEMENT_DEST_PORT, $this->flo_element_dest_port);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_IS_INMEDIATE)) {
            $criteria->add(BpmnFlowPeer::FLO_IS_INMEDIATE, $this->flo_is_inmediate);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_CONDITION)) {
            $criteria->add(BpmnFlowPeer::FLO_CONDITION, $this->flo_condition);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_X1)) {
            $criteria->add(BpmnFlowPeer::FLO_X1, $this->flo_x1);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_Y1)) {
            $criteria->add(BpmnFlowPeer::FLO_Y1, $this->flo_y1);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_X2)) {
            $criteria->add(BpmnFlowPeer::FLO_X2, $this->flo_x2);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_Y2)) {
            $criteria->add(BpmnFlowPeer::FLO_Y2, $this->flo_y2);
        }

        if ($this->isColumnModified(BpmnFlowPeer::FLO_STATE)) {
            $criteria->add(BpmnFlowPeer::FLO_STATE, $this->flo_state);
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
        $criteria = new Criteria(BpmnFlowPeer::DATABASE_NAME);

        $criteria->add(BpmnFlowPeer::FLO_UID, $this->flo_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getFloUid();
    }

    /**
     * Generic method to set the primary key (flo_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setFloUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnFlow (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setDiaUid($this->dia_uid);

        $copyObj->setFloType($this->flo_type);

        $copyObj->setFloName($this->flo_name);

        $copyObj->setFloElementOrigin($this->flo_element_origin);

        $copyObj->setFloElementOriginType($this->flo_element_origin_type);

        $copyObj->setFloElementOriginPort($this->flo_element_origin_port);

        $copyObj->setFloElementDest($this->flo_element_dest);

        $copyObj->setFloElementDestType($this->flo_element_dest_type);

        $copyObj->setFloElementDestPort($this->flo_element_dest_port);

        $copyObj->setFloIsInmediate($this->flo_is_inmediate);

        $copyObj->setFloCondition($this->flo_condition);

        $copyObj->setFloX1($this->flo_x1);

        $copyObj->setFloY1($this->flo_y1);

        $copyObj->setFloX2($this->flo_x2);

        $copyObj->setFloY2($this->flo_y2);

        $copyObj->setFloState($this->flo_state);


        $copyObj->setNew(true);

        $copyObj->setFloUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnFlow Clone of current object.
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
     * @return     BpmnFlowPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnFlowPeer();
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
     * Declares an association between this object and a BpmnDiagram object.
     *
     * @param      BpmnDiagram $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnDiagram($v)
    {


        if ($v === null) {
            $this->setDiaUid('');
        } else {
            $this->setDiaUid($v->getDiaUid());
        }


        $this->aBpmnDiagram = $v;
    }


    /**
     * Get the associated BpmnDiagram object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnDiagram The associated BpmnDiagram object.
     * @throws     PropelException
     */
    public function getBpmnDiagram($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnDiagramPeer.php';

        if ($this->aBpmnDiagram === null && (($this->dia_uid !== "" && $this->dia_uid !== null))) {

            $this->aBpmnDiagram = BpmnDiagramPeer::retrieveByPK($this->dia_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnDiagramPeer::retrieveByPK($this->dia_uid, $con);
               $obj->addBpmnDiagrams($this);
             */
        }
        return $this->aBpmnDiagram;
    }
}

