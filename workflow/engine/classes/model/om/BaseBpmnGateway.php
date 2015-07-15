<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnGatewayPeer.php';

/**
 * Base class that represents a row from the 'BPMN_GATEWAY' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnGateway extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnGatewayPeer
    */
    protected static $peer;

    /**
     * The value for the gat_uid field.
     * @var        string
     */
    protected $gat_uid = '';

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
     * The value for the gat_name field.
     * @var        string
     */
    protected $gat_name;

    /**
     * The value for the gat_type field.
     * @var        string
     */
    protected $gat_type = '';

    /**
     * The value for the gat_direction field.
     * @var        string
     */
    protected $gat_direction = 'UNSPECIFIED';

    /**
     * The value for the gat_instantiate field.
     * @var        int
     */
    protected $gat_instantiate = 0;

    /**
     * The value for the gat_event_gateway_type field.
     * @var        string
     */
    protected $gat_event_gateway_type = 'NONE';

    /**
     * The value for the gat_activation_count field.
     * @var        int
     */
    protected $gat_activation_count = 0;

    /**
     * The value for the gat_waiting_for_start field.
     * @var        int
     */
    protected $gat_waiting_for_start = 1;

    /**
     * The value for the gat_default_flow field.
     * @var        string
     */
    protected $gat_default_flow = '';

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
     * Get the [gat_uid] column value.
     * 
     * @return     string
     */
    public function getGatUid()
    {

        return $this->gat_uid;
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
     * Get the [gat_name] column value.
     * 
     * @return     string
     */
    public function getGatName()
    {

        return $this->gat_name;
    }

    /**
     * Get the [gat_type] column value.
     * 
     * @return     string
     */
    public function getGatType()
    {

        return $this->gat_type;
    }

    /**
     * Get the [gat_direction] column value.
     * 
     * @return     string
     */
    public function getGatDirection()
    {

        return $this->gat_direction;
    }

    /**
     * Get the [gat_instantiate] column value.
     * 
     * @return     int
     */
    public function getGatInstantiate()
    {

        return $this->gat_instantiate;
    }

    /**
     * Get the [gat_event_gateway_type] column value.
     * 
     * @return     string
     */
    public function getGatEventGatewayType()
    {

        return $this->gat_event_gateway_type;
    }

    /**
     * Get the [gat_activation_count] column value.
     * 
     * @return     int
     */
    public function getGatActivationCount()
    {

        return $this->gat_activation_count;
    }

    /**
     * Get the [gat_waiting_for_start] column value.
     * 
     * @return     int
     */
    public function getGatWaitingForStart()
    {

        return $this->gat_waiting_for_start;
    }

    /**
     * Get the [gat_default_flow] column value.
     * 
     * @return     string
     */
    public function getGatDefaultFlow()
    {

        return $this->gat_default_flow;
    }

    /**
     * Set the value of [gat_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_uid !== $v || $v === '') {
            $this->gat_uid = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_UID;
        }

    } // setGatUid()

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
            $this->modifiedColumns[] = BpmnGatewayPeer::PRJ_UID;
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
            $this->modifiedColumns[] = BpmnGatewayPeer::PRO_UID;
        }

        if ($this->aBpmnProcess !== null && $this->aBpmnProcess->getProUid() !== $v) {
            $this->aBpmnProcess = null;
        }

    } // setProUid()

    /**
     * Set the value of [gat_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_name !== $v) {
            $this->gat_name = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_NAME;
        }

    } // setGatName()

    /**
     * Set the value of [gat_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_type !== $v || $v === '') {
            $this->gat_type = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_TYPE;
        }

    } // setGatType()

    /**
     * Set the value of [gat_direction] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatDirection($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_direction !== $v || $v === 'UNSPECIFIED') {
            $this->gat_direction = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_DIRECTION;
        }

    } // setGatDirection()

    /**
     * Set the value of [gat_instantiate] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setGatInstantiate($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->gat_instantiate !== $v || $v === 0) {
            $this->gat_instantiate = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_INSTANTIATE;
        }

    } // setGatInstantiate()

    /**
     * Set the value of [gat_event_gateway_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatEventGatewayType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_event_gateway_type !== $v || $v === 'NONE') {
            $this->gat_event_gateway_type = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE;
        }

    } // setGatEventGatewayType()

    /**
     * Set the value of [gat_activation_count] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setGatActivationCount($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->gat_activation_count !== $v || $v === 0) {
            $this->gat_activation_count = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_ACTIVATION_COUNT;
        }

    } // setGatActivationCount()

    /**
     * Set the value of [gat_waiting_for_start] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setGatWaitingForStart($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->gat_waiting_for_start !== $v || $v === 1) {
            $this->gat_waiting_for_start = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_WAITING_FOR_START;
        }

    } // setGatWaitingForStart()

    /**
     * Set the value of [gat_default_flow] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGatDefaultFlow($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->gat_default_flow !== $v || $v === '') {
            $this->gat_default_flow = $v;
            $this->modifiedColumns[] = BpmnGatewayPeer::GAT_DEFAULT_FLOW;
        }

    } // setGatDefaultFlow()

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

            $this->gat_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->pro_uid = $rs->getString($startcol + 2);

            $this->gat_name = $rs->getString($startcol + 3);

            $this->gat_type = $rs->getString($startcol + 4);

            $this->gat_direction = $rs->getString($startcol + 5);

            $this->gat_instantiate = $rs->getInt($startcol + 6);

            $this->gat_event_gateway_type = $rs->getString($startcol + 7);

            $this->gat_activation_count = $rs->getInt($startcol + 8);

            $this->gat_waiting_for_start = $rs->getInt($startcol + 9);

            $this->gat_default_flow = $rs->getString($startcol + 10);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 11; // 11 = BpmnGatewayPeer::NUM_COLUMNS - BpmnGatewayPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnGateway object", $e);
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
            $con = Propel::getConnection(BpmnGatewayPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnGatewayPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnGatewayPeer::DATABASE_NAME);
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
                    $pk = BpmnGatewayPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnGatewayPeer::doUpdate($this, $con);
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


            if (($retval = BpmnGatewayPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnGatewayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getGatUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getProUid();
                break;
            case 3:
                return $this->getGatName();
                break;
            case 4:
                return $this->getGatType();
                break;
            case 5:
                return $this->getGatDirection();
                break;
            case 6:
                return $this->getGatInstantiate();
                break;
            case 7:
                return $this->getGatEventGatewayType();
                break;
            case 8:
                return $this->getGatActivationCount();
                break;
            case 9:
                return $this->getGatWaitingForStart();
                break;
            case 10:
                return $this->getGatDefaultFlow();
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
        $keys = BpmnGatewayPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getGatUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getProUid(),
            $keys[3] => $this->getGatName(),
            $keys[4] => $this->getGatType(),
            $keys[5] => $this->getGatDirection(),
            $keys[6] => $this->getGatInstantiate(),
            $keys[7] => $this->getGatEventGatewayType(),
            $keys[8] => $this->getGatActivationCount(),
            $keys[9] => $this->getGatWaitingForStart(),
            $keys[10] => $this->getGatDefaultFlow(),
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
        $pos = BpmnGatewayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setGatUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setProUid($value);
                break;
            case 3:
                $this->setGatName($value);
                break;
            case 4:
                $this->setGatType($value);
                break;
            case 5:
                $this->setGatDirection($value);
                break;
            case 6:
                $this->setGatInstantiate($value);
                break;
            case 7:
                $this->setGatEventGatewayType($value);
                break;
            case 8:
                $this->setGatActivationCount($value);
                break;
            case 9:
                $this->setGatWaitingForStart($value);
                break;
            case 10:
                $this->setGatDefaultFlow($value);
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
        $keys = BpmnGatewayPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setGatUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setProUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setGatName($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setGatType($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setGatDirection($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setGatInstantiate($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setGatEventGatewayType($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setGatActivationCount($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setGatWaitingForStart($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setGatDefaultFlow($arr[$keys[10]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnGatewayPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_UID)) {
            $criteria->add(BpmnGatewayPeer::GAT_UID, $this->gat_uid);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::PRJ_UID)) {
            $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::PRO_UID)) {
            $criteria->add(BpmnGatewayPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_NAME)) {
            $criteria->add(BpmnGatewayPeer::GAT_NAME, $this->gat_name);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_TYPE)) {
            $criteria->add(BpmnGatewayPeer::GAT_TYPE, $this->gat_type);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_DIRECTION)) {
            $criteria->add(BpmnGatewayPeer::GAT_DIRECTION, $this->gat_direction);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_INSTANTIATE)) {
            $criteria->add(BpmnGatewayPeer::GAT_INSTANTIATE, $this->gat_instantiate);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE)) {
            $criteria->add(BpmnGatewayPeer::GAT_EVENT_GATEWAY_TYPE, $this->gat_event_gateway_type);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_ACTIVATION_COUNT)) {
            $criteria->add(BpmnGatewayPeer::GAT_ACTIVATION_COUNT, $this->gat_activation_count);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_WAITING_FOR_START)) {
            $criteria->add(BpmnGatewayPeer::GAT_WAITING_FOR_START, $this->gat_waiting_for_start);
        }

        if ($this->isColumnModified(BpmnGatewayPeer::GAT_DEFAULT_FLOW)) {
            $criteria->add(BpmnGatewayPeer::GAT_DEFAULT_FLOW, $this->gat_default_flow);
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
        $criteria = new Criteria(BpmnGatewayPeer::DATABASE_NAME);

        $criteria->add(BpmnGatewayPeer::GAT_UID, $this->gat_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getGatUid();
    }

    /**
     * Generic method to set the primary key (gat_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setGatUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnGateway (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setGatName($this->gat_name);

        $copyObj->setGatType($this->gat_type);

        $copyObj->setGatDirection($this->gat_direction);

        $copyObj->setGatInstantiate($this->gat_instantiate);

        $copyObj->setGatEventGatewayType($this->gat_event_gateway_type);

        $copyObj->setGatActivationCount($this->gat_activation_count);

        $copyObj->setGatWaitingForStart($this->gat_waiting_for_start);

        $copyObj->setGatDefaultFlow($this->gat_default_flow);


        $copyObj->setNew(true);

        $copyObj->setGatUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnGateway Clone of current object.
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
     * @return     BpmnGatewayPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnGatewayPeer();
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

