<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnProcessPeer.php';

/**
 * Base class that represents a row from the 'BPMN_PROCESS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnProcess extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnProcessPeer
    */
    protected static $peer;

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the dia_uid field.
     * @var        string
     */
    protected $dia_uid;

    /**
     * The value for the pro_name field.
     * @var        string
     */
    protected $pro_name;

    /**
     * The value for the pro_type field.
     * @var        string
     */
    protected $pro_type = 'NONE';

    /**
     * The value for the pro_is_executable field.
     * @var        int
     */
    protected $pro_is_executable = 0;

    /**
     * The value for the pro_is_closed field.
     * @var        int
     */
    protected $pro_is_closed = 0;

    /**
     * The value for the pro_is_subprocess field.
     * @var        int
     */
    protected $pro_is_subprocess = 0;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * Collection to store aggregation of collBpmnActivitys.
     * @var        array
     */
    protected $collBpmnActivitys;

    /**
     * The criteria used to select the current contents of collBpmnActivitys.
     * @var        Criteria
     */
    protected $lastBpmnActivityCriteria = null;

    /**
     * Collection to store aggregation of collBpmnArtifacts.
     * @var        array
     */
    protected $collBpmnArtifacts;

    /**
     * The criteria used to select the current contents of collBpmnArtifacts.
     * @var        Criteria
     */
    protected $lastBpmnArtifactCriteria = null;

    /**
     * Collection to store aggregation of collBpmnDatas.
     * @var        array
     */
    protected $collBpmnDatas;

    /**
     * The criteria used to select the current contents of collBpmnDatas.
     * @var        Criteria
     */
    protected $lastBpmnDataCriteria = null;

    /**
     * Collection to store aggregation of collBpmnEvents.
     * @var        array
     */
    protected $collBpmnEvents;

    /**
     * The criteria used to select the current contents of collBpmnEvents.
     * @var        Criteria
     */
    protected $lastBpmnEventCriteria = null;

    /**
     * Collection to store aggregation of collBpmnGateways.
     * @var        array
     */
    protected $collBpmnGateways;

    /**
     * The criteria used to select the current contents of collBpmnGateways.
     * @var        Criteria
     */
    protected $lastBpmnGatewayCriteria = null;

    /**
     * Collection to store aggregation of collBpmnLanesets.
     * @var        array
     */
    protected $collBpmnLanesets;

    /**
     * The criteria used to select the current contents of collBpmnLanesets.
     * @var        Criteria
     */
    protected $lastBpmnLanesetCriteria = null;

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
     * Get the [pro_uid] column value.
     * 
     * @return     string
     */
    public function getProUid()
    {

        return $this->pro_uid;
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
     * Get the [pro_name] column value.
     * 
     * @return     string
     */
    public function getProName()
    {

        return $this->pro_name;
    }

    /**
     * Get the [pro_type] column value.
     * 
     * @return     string
     */
    public function getProType()
    {

        return $this->pro_type;
    }

    /**
     * Get the [pro_is_executable] column value.
     * 
     * @return     int
     */
    public function getProIsExecutable()
    {

        return $this->pro_is_executable;
    }

    /**
     * Get the [pro_is_closed] column value.
     * 
     * @return     int
     */
    public function getProIsClosed()
    {

        return $this->pro_is_closed;
    }

    /**
     * Get the [pro_is_subprocess] column value.
     * 
     * @return     int
     */
    public function getProIsSubprocess()
    {

        return $this->pro_is_subprocess;
    }

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
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_UID;
        }

    } // setProUid()

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
            $this->modifiedColumns[] = BpmnProcessPeer::PRJ_UID;
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

        if ($this->dia_uid !== $v) {
            $this->dia_uid = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::DIA_UID;
        }

    } // setDiaUid()

    /**
     * Set the value of [pro_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setProName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->pro_name !== $v) {
            $this->pro_name = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_NAME;
        }

    } // setProName()

    /**
     * Set the value of [pro_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setProType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->pro_type !== $v || $v === 'NONE') {
            $this->pro_type = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_TYPE;
        }

    } // setProType()

    /**
     * Set the value of [pro_is_executable] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setProIsExecutable($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->pro_is_executable !== $v || $v === 0) {
            $this->pro_is_executable = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_IS_EXECUTABLE;
        }

    } // setProIsExecutable()

    /**
     * Set the value of [pro_is_closed] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setProIsClosed($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->pro_is_closed !== $v || $v === 0) {
            $this->pro_is_closed = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_IS_CLOSED;
        }

    } // setProIsClosed()

    /**
     * Set the value of [pro_is_subprocess] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setProIsSubprocess($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->pro_is_subprocess !== $v || $v === 0) {
            $this->pro_is_subprocess = $v;
            $this->modifiedColumns[] = BpmnProcessPeer::PRO_IS_SUBPROCESS;
        }

    } // setProIsSubprocess()

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

            $this->pro_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->dia_uid = $rs->getString($startcol + 2);

            $this->pro_name = $rs->getString($startcol + 3);

            $this->pro_type = $rs->getString($startcol + 4);

            $this->pro_is_executable = $rs->getInt($startcol + 5);

            $this->pro_is_closed = $rs->getInt($startcol + 6);

            $this->pro_is_subprocess = $rs->getInt($startcol + 7);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 8; // 8 = BpmnProcessPeer::NUM_COLUMNS - BpmnProcessPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnProcess object", $e);
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
            $con = Propel::getConnection(BpmnProcessPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnProcessPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnProcessPeer::DATABASE_NAME);
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


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnProcessPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnProcessPeer::doUpdate($this, $con);
                }
                $this->resetModified(); // [HL] After being saved an object is no longer 'modified'
            }

            if ($this->collBpmnActivitys !== null) {
                foreach($this->collBpmnActivitys as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnArtifacts !== null) {
                foreach($this->collBpmnArtifacts as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnDatas !== null) {
                foreach($this->collBpmnDatas as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnEvents !== null) {
                foreach($this->collBpmnEvents as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnGateways !== null) {
                foreach($this->collBpmnGateways as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnLanesets !== null) {
                foreach($this->collBpmnLanesets as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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


            if (($retval = BpmnProcessPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBpmnActivitys !== null) {
                    foreach($this->collBpmnActivitys as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnArtifacts !== null) {
                    foreach($this->collBpmnArtifacts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnDatas !== null) {
                    foreach($this->collBpmnDatas as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnEvents !== null) {
                    foreach($this->collBpmnEvents as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnGateways !== null) {
                    foreach($this->collBpmnGateways as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnLanesets !== null) {
                    foreach($this->collBpmnLanesets as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $pos = BpmnProcessPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getProUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getDiaUid();
                break;
            case 3:
                return $this->getProName();
                break;
            case 4:
                return $this->getProType();
                break;
            case 5:
                return $this->getProIsExecutable();
                break;
            case 6:
                return $this->getProIsClosed();
                break;
            case 7:
                return $this->getProIsSubprocess();
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
        $keys = BpmnProcessPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getProUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getDiaUid(),
            $keys[3] => $this->getProName(),
            $keys[4] => $this->getProType(),
            $keys[5] => $this->getProIsExecutable(),
            $keys[6] => $this->getProIsClosed(),
            $keys[7] => $this->getProIsSubprocess(),
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
        $pos = BpmnProcessPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setProUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setDiaUid($value);
                break;
            case 3:
                $this->setProName($value);
                break;
            case 4:
                $this->setProType($value);
                break;
            case 5:
                $this->setProIsExecutable($value);
                break;
            case 6:
                $this->setProIsClosed($value);
                break;
            case 7:
                $this->setProIsSubprocess($value);
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
        $keys = BpmnProcessPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setProUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setDiaUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setProName($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setProType($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setProIsExecutable($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setProIsClosed($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setProIsSubprocess($arr[$keys[7]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnProcessPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnProcessPeer::PRO_UID)) {
            $criteria->add(BpmnProcessPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRJ_UID)) {
            $criteria->add(BpmnProcessPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnProcessPeer::DIA_UID)) {
            $criteria->add(BpmnProcessPeer::DIA_UID, $this->dia_uid);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRO_NAME)) {
            $criteria->add(BpmnProcessPeer::PRO_NAME, $this->pro_name);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRO_TYPE)) {
            $criteria->add(BpmnProcessPeer::PRO_TYPE, $this->pro_type);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRO_IS_EXECUTABLE)) {
            $criteria->add(BpmnProcessPeer::PRO_IS_EXECUTABLE, $this->pro_is_executable);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRO_IS_CLOSED)) {
            $criteria->add(BpmnProcessPeer::PRO_IS_CLOSED, $this->pro_is_closed);
        }

        if ($this->isColumnModified(BpmnProcessPeer::PRO_IS_SUBPROCESS)) {
            $criteria->add(BpmnProcessPeer::PRO_IS_SUBPROCESS, $this->pro_is_subprocess);
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
        $criteria = new Criteria(BpmnProcessPeer::DATABASE_NAME);

        $criteria->add(BpmnProcessPeer::PRO_UID, $this->pro_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getProUid();
    }

    /**
     * Generic method to set the primary key (pro_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setProUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnProcess (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setDiaUid($this->dia_uid);

        $copyObj->setProName($this->pro_name);

        $copyObj->setProType($this->pro_type);

        $copyObj->setProIsExecutable($this->pro_is_executable);

        $copyObj->setProIsClosed($this->pro_is_closed);

        $copyObj->setProIsSubprocess($this->pro_is_subprocess);


        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach($this->getBpmnActivitys() as $relObj) {
                $copyObj->addBpmnActivity($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnArtifacts() as $relObj) {
                $copyObj->addBpmnArtifact($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnDatas() as $relObj) {
                $copyObj->addBpmnData($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnEvents() as $relObj) {
                $copyObj->addBpmnEvent($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnGateways() as $relObj) {
                $copyObj->addBpmnGateway($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnLanesets() as $relObj) {
                $copyObj->addBpmnLaneset($relObj->copy($deepCopy));
            }

        } // if ($deepCopy)


        $copyObj->setNew(true);

        $copyObj->setProUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnProcess Clone of current object.
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
     * @return     BpmnProcessPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnProcessPeer();
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
            $this->setPrjUid(NULL);
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
     * Temporary storage of collBpmnActivitys to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnActivitys()
    {
        if ($this->collBpmnActivitys === null) {
            $this->collBpmnActivitys = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnActivitys from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnActivitys($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnActivitys === null) {
            if ($this->isNew()) {
               $this->collBpmnActivitys = array();
            } else {

                $criteria->add(BpmnActivityPeer::PRO_UID, $this->getProUid());

                BpmnActivityPeer::addSelectColumns($criteria);
                $this->collBpmnActivitys = BpmnActivityPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnActivityPeer::PRO_UID, $this->getProUid());

                BpmnActivityPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnActivityCriteria) || !$this->lastBpmnActivityCriteria->equals($criteria)) {
                    $this->collBpmnActivitys = BpmnActivityPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnActivityCriteria = $criteria;
        return $this->collBpmnActivitys;
    }

    /**
     * Returns the number of related BpmnActivitys.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnActivitys($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnActivityPeer::PRO_UID, $this->getProUid());

        return BpmnActivityPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnActivity object to this object
     * through the BpmnActivity foreign key attribute
     *
     * @param      BpmnActivity $l BpmnActivity
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnActivity(BpmnActivity $l)
    {
        $this->collBpmnActivitys[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnActivitys from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnActivitysJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnActivitys === null) {
            if ($this->isNew()) {
                $this->collBpmnActivitys = array();
            } else {

                $criteria->add(BpmnActivityPeer::PRO_UID, $this->getProUid());

                $this->collBpmnActivitys = BpmnActivityPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnActivityPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnActivityCriteria) || !$this->lastBpmnActivityCriteria->equals($criteria)) {
                $this->collBpmnActivitys = BpmnActivityPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnActivityCriteria = $criteria;

        return $this->collBpmnActivitys;
    }

    /**
     * Temporary storage of collBpmnArtifacts to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnArtifacts()
    {
        if ($this->collBpmnArtifacts === null) {
            $this->collBpmnArtifacts = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnArtifacts from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnArtifacts($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnArtifacts === null) {
            if ($this->isNew()) {
               $this->collBpmnArtifacts = array();
            } else {

                $criteria->add(BpmnArtifactPeer::PRO_UID, $this->getProUid());

                BpmnArtifactPeer::addSelectColumns($criteria);
                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnArtifactPeer::PRO_UID, $this->getProUid());

                BpmnArtifactPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnArtifactCriteria) || !$this->lastBpmnArtifactCriteria->equals($criteria)) {
                    $this->collBpmnArtifacts = BpmnArtifactPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnArtifactCriteria = $criteria;
        return $this->collBpmnArtifacts;
    }

    /**
     * Returns the number of related BpmnArtifacts.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnArtifacts($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnArtifactPeer::PRO_UID, $this->getProUid());

        return BpmnArtifactPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnArtifact object to this object
     * through the BpmnArtifact foreign key attribute
     *
     * @param      BpmnArtifact $l BpmnArtifact
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnArtifact(BpmnArtifact $l)
    {
        $this->collBpmnArtifacts[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnArtifacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnArtifactsJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnArtifacts === null) {
            if ($this->isNew()) {
                $this->collBpmnArtifacts = array();
            } else {

                $criteria->add(BpmnArtifactPeer::PRO_UID, $this->getProUid());

                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnArtifactPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnArtifactCriteria) || !$this->lastBpmnArtifactCriteria->equals($criteria)) {
                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnArtifactCriteria = $criteria;

        return $this->collBpmnArtifacts;
    }

    /**
     * Temporary storage of collBpmnDatas to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnDatas()
    {
        if ($this->collBpmnDatas === null) {
            $this->collBpmnDatas = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnDatas from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnDatas($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDatas === null) {
            if ($this->isNew()) {
               $this->collBpmnDatas = array();
            } else {

                $criteria->add(BpmnDataPeer::PRO_UID, $this->getProUid());

                BpmnDataPeer::addSelectColumns($criteria);
                $this->collBpmnDatas = BpmnDataPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnDataPeer::PRO_UID, $this->getProUid());

                BpmnDataPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnDataCriteria) || !$this->lastBpmnDataCriteria->equals($criteria)) {
                    $this->collBpmnDatas = BpmnDataPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnDataCriteria = $criteria;
        return $this->collBpmnDatas;
    }

    /**
     * Returns the number of related BpmnDatas.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnDatas($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnDataPeer::PRO_UID, $this->getProUid());

        return BpmnDataPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnData object to this object
     * through the BpmnData foreign key attribute
     *
     * @param      BpmnData $l BpmnData
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnData(BpmnData $l)
    {
        $this->collBpmnDatas[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnDatas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnDatasJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDatas === null) {
            if ($this->isNew()) {
                $this->collBpmnDatas = array();
            } else {

                $criteria->add(BpmnDataPeer::PRO_UID, $this->getProUid());

                $this->collBpmnDatas = BpmnDataPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnDataPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnDataCriteria) || !$this->lastBpmnDataCriteria->equals($criteria)) {
                $this->collBpmnDatas = BpmnDataPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnDataCriteria = $criteria;

        return $this->collBpmnDatas;
    }

    /**
     * Temporary storage of collBpmnEvents to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnEvents()
    {
        if ($this->collBpmnEvents === null) {
            $this->collBpmnEvents = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnEvents from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnEvents($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnEvents === null) {
            if ($this->isNew()) {
               $this->collBpmnEvents = array();
            } else {

                $criteria->add(BpmnEventPeer::PRO_UID, $this->getProUid());

                BpmnEventPeer::addSelectColumns($criteria);
                $this->collBpmnEvents = BpmnEventPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnEventPeer::PRO_UID, $this->getProUid());

                BpmnEventPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnEventCriteria) || !$this->lastBpmnEventCriteria->equals($criteria)) {
                    $this->collBpmnEvents = BpmnEventPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnEventCriteria = $criteria;
        return $this->collBpmnEvents;
    }

    /**
     * Returns the number of related BpmnEvents.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnEvents($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnEventPeer::PRO_UID, $this->getProUid());

        return BpmnEventPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnEvent object to this object
     * through the BpmnEvent foreign key attribute
     *
     * @param      BpmnEvent $l BpmnEvent
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnEvent(BpmnEvent $l)
    {
        $this->collBpmnEvents[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnEvents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnEventsJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnEvents === null) {
            if ($this->isNew()) {
                $this->collBpmnEvents = array();
            } else {

                $criteria->add(BpmnEventPeer::PRO_UID, $this->getProUid());

                $this->collBpmnEvents = BpmnEventPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnEventPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnEventCriteria) || !$this->lastBpmnEventCriteria->equals($criteria)) {
                $this->collBpmnEvents = BpmnEventPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnEventCriteria = $criteria;

        return $this->collBpmnEvents;
    }

    /**
     * Temporary storage of collBpmnGateways to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnGateways()
    {
        if ($this->collBpmnGateways === null) {
            $this->collBpmnGateways = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnGateways from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnGateways($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnGateways === null) {
            if ($this->isNew()) {
               $this->collBpmnGateways = array();
            } else {

                $criteria->add(BpmnGatewayPeer::PRO_UID, $this->getProUid());

                BpmnGatewayPeer::addSelectColumns($criteria);
                $this->collBpmnGateways = BpmnGatewayPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnGatewayPeer::PRO_UID, $this->getProUid());

                BpmnGatewayPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnGatewayCriteria) || !$this->lastBpmnGatewayCriteria->equals($criteria)) {
                    $this->collBpmnGateways = BpmnGatewayPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnGatewayCriteria = $criteria;
        return $this->collBpmnGateways;
    }

    /**
     * Returns the number of related BpmnGateways.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnGateways($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnGatewayPeer::PRO_UID, $this->getProUid());

        return BpmnGatewayPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnGateway object to this object
     * through the BpmnGateway foreign key attribute
     *
     * @param      BpmnGateway $l BpmnGateway
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnGateway(BpmnGateway $l)
    {
        $this->collBpmnGateways[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnGateways from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnGatewaysJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnGateways === null) {
            if ($this->isNew()) {
                $this->collBpmnGateways = array();
            } else {

                $criteria->add(BpmnGatewayPeer::PRO_UID, $this->getProUid());

                $this->collBpmnGateways = BpmnGatewayPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnGatewayPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnGatewayCriteria) || !$this->lastBpmnGatewayCriteria->equals($criteria)) {
                $this->collBpmnGateways = BpmnGatewayPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnGatewayCriteria = $criteria;

        return $this->collBpmnGateways;
    }

    /**
     * Temporary storage of collBpmnLanesets to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnLanesets()
    {
        if ($this->collBpmnLanesets === null) {
            $this->collBpmnLanesets = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnLanesets from storage.
     * If this BpmnProcess is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnLanesets($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnLanesets === null) {
            if ($this->isNew()) {
               $this->collBpmnLanesets = array();
            } else {

                $criteria->add(BpmnLanesetPeer::PRO_UID, $this->getProUid());

                BpmnLanesetPeer::addSelectColumns($criteria);
                $this->collBpmnLanesets = BpmnLanesetPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnLanesetPeer::PRO_UID, $this->getProUid());

                BpmnLanesetPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnLanesetCriteria) || !$this->lastBpmnLanesetCriteria->equals($criteria)) {
                    $this->collBpmnLanesets = BpmnLanesetPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnLanesetCriteria = $criteria;
        return $this->collBpmnLanesets;
    }

    /**
     * Returns the number of related BpmnLanesets.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnLanesets($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnLanesetPeer::PRO_UID, $this->getProUid());

        return BpmnLanesetPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnLaneset object to this object
     * through the BpmnLaneset foreign key attribute
     *
     * @param      BpmnLaneset $l BpmnLaneset
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnLaneset(BpmnLaneset $l)
    {
        $this->collBpmnLanesets[] = $l;
        $l->setBpmnProcess($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProcess is new, it will return
     * an empty collection; or if this BpmnProcess has previously
     * been saved, it will retrieve related BpmnLanesets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProcess.
     */
    public function getBpmnLanesetsJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnLanesets === null) {
            if ($this->isNew()) {
                $this->collBpmnLanesets = array();
            } else {

                $criteria->add(BpmnLanesetPeer::PRO_UID, $this->getProUid());

                $this->collBpmnLanesets = BpmnLanesetPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnLanesetPeer::PRO_UID, $this->getProUid());

            if (!isset($this->lastBpmnLanesetCriteria) || !$this->lastBpmnLanesetCriteria->equals($criteria)) {
                $this->collBpmnLanesets = BpmnLanesetPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnLanesetCriteria = $criteria;

        return $this->collBpmnLanesets;
    }
}

