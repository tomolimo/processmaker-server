<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnParticipantPeer.php';

/**
 * Base class that represents a row from the 'BPMN_PARTICIPANT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnParticipant extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnParticipantPeer
    */
    protected static $peer;

    /**
     * The value for the par_uid field.
     * @var        string
     */
    protected $par_uid = '';

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
     * The value for the lns_uid field.
     * @var        string
     */
    protected $lns_uid = '';

    /**
     * The value for the par_name field.
     * @var        string
     */
    protected $par_name = '';

    /**
     * The value for the par_minimum field.
     * @var        int
     */
    protected $par_minimum = 0;

    /**
     * The value for the par_maximum field.
     * @var        int
     */
    protected $par_maximum = 1;

    /**
     * The value for the par_num_participants field.
     * @var        int
     */
    protected $par_num_participants = 1;

    /**
     * The value for the par_is_horizontal field.
     * @var        int
     */
    protected $par_is_horizontal = 1;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

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
     * Get the [par_uid] column value.
     * 
     * @return     string
     */
    public function getParUid()
    {

        return $this->par_uid;
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
     * Get the [lns_uid] column value.
     * 
     * @return     string
     */
    public function getLnsUid()
    {

        return $this->lns_uid;
    }

    /**
     * Get the [par_name] column value.
     * 
     * @return     string
     */
    public function getParName()
    {

        return $this->par_name;
    }

    /**
     * Get the [par_minimum] column value.
     * 
     * @return     int
     */
    public function getParMinimum()
    {

        return $this->par_minimum;
    }

    /**
     * Get the [par_maximum] column value.
     * 
     * @return     int
     */
    public function getParMaximum()
    {

        return $this->par_maximum;
    }

    /**
     * Get the [par_num_participants] column value.
     * 
     * @return     int
     */
    public function getParNumParticipants()
    {

        return $this->par_num_participants;
    }

    /**
     * Get the [par_is_horizontal] column value.
     * 
     * @return     int
     */
    public function getParIsHorizontal()
    {

        return $this->par_is_horizontal;
    }

    /**
     * Set the value of [par_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setParUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->par_uid !== $v || $v === '') {
            $this->par_uid = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_UID;
        }

    } // setParUid()

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
            $this->modifiedColumns[] = BpmnParticipantPeer::PRJ_UID;
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
            $this->modifiedColumns[] = BpmnParticipantPeer::PRO_UID;
        }

    } // setProUid()

    /**
     * Set the value of [lns_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setLnsUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->lns_uid !== $v || $v === '') {
            $this->lns_uid = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::LNS_UID;
        }

    } // setLnsUid()

    /**
     * Set the value of [par_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setParName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->par_name !== $v || $v === '') {
            $this->par_name = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_NAME;
        }

    } // setParName()

    /**
     * Set the value of [par_minimum] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setParMinimum($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->par_minimum !== $v || $v === 0) {
            $this->par_minimum = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_MINIMUM;
        }

    } // setParMinimum()

    /**
     * Set the value of [par_maximum] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setParMaximum($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->par_maximum !== $v || $v === 1) {
            $this->par_maximum = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_MAXIMUM;
        }

    } // setParMaximum()

    /**
     * Set the value of [par_num_participants] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setParNumParticipants($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->par_num_participants !== $v || $v === 1) {
            $this->par_num_participants = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_NUM_PARTICIPANTS;
        }

    } // setParNumParticipants()

    /**
     * Set the value of [par_is_horizontal] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setParIsHorizontal($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->par_is_horizontal !== $v || $v === 1) {
            $this->par_is_horizontal = $v;
            $this->modifiedColumns[] = BpmnParticipantPeer::PAR_IS_HORIZONTAL;
        }

    } // setParIsHorizontal()

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

            $this->par_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->pro_uid = $rs->getString($startcol + 2);

            $this->lns_uid = $rs->getString($startcol + 3);

            $this->par_name = $rs->getString($startcol + 4);

            $this->par_minimum = $rs->getInt($startcol + 5);

            $this->par_maximum = $rs->getInt($startcol + 6);

            $this->par_num_participants = $rs->getInt($startcol + 7);

            $this->par_is_horizontal = $rs->getInt($startcol + 8);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 9; // 9 = BpmnParticipantPeer::NUM_COLUMNS - BpmnParticipantPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnParticipant object", $e);
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
            $con = Propel::getConnection(BpmnParticipantPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnParticipantPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnParticipantPeer::DATABASE_NAME);
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
                    $pk = BpmnParticipantPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnParticipantPeer::doUpdate($this, $con);
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


            if (($retval = BpmnParticipantPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnParticipantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getParUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getProUid();
                break;
            case 3:
                return $this->getLnsUid();
                break;
            case 4:
                return $this->getParName();
                break;
            case 5:
                return $this->getParMinimum();
                break;
            case 6:
                return $this->getParMaximum();
                break;
            case 7:
                return $this->getParNumParticipants();
                break;
            case 8:
                return $this->getParIsHorizontal();
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
        $keys = BpmnParticipantPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getParUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getProUid(),
            $keys[3] => $this->getLnsUid(),
            $keys[4] => $this->getParName(),
            $keys[5] => $this->getParMinimum(),
            $keys[6] => $this->getParMaximum(),
            $keys[7] => $this->getParNumParticipants(),
            $keys[8] => $this->getParIsHorizontal(),
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
        $pos = BpmnParticipantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setParUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setProUid($value);
                break;
            case 3:
                $this->setLnsUid($value);
                break;
            case 4:
                $this->setParName($value);
                break;
            case 5:
                $this->setParMinimum($value);
                break;
            case 6:
                $this->setParMaximum($value);
                break;
            case 7:
                $this->setParNumParticipants($value);
                break;
            case 8:
                $this->setParIsHorizontal($value);
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
        $keys = BpmnParticipantPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setParUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setProUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setLnsUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setParName($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setParMinimum($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setParMaximum($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setParNumParticipants($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setParIsHorizontal($arr[$keys[8]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnParticipantPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_UID)) {
            $criteria->add(BpmnParticipantPeer::PAR_UID, $this->par_uid);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PRJ_UID)) {
            $criteria->add(BpmnParticipantPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PRO_UID)) {
            $criteria->add(BpmnParticipantPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::LNS_UID)) {
            $criteria->add(BpmnParticipantPeer::LNS_UID, $this->lns_uid);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_NAME)) {
            $criteria->add(BpmnParticipantPeer::PAR_NAME, $this->par_name);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_MINIMUM)) {
            $criteria->add(BpmnParticipantPeer::PAR_MINIMUM, $this->par_minimum);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_MAXIMUM)) {
            $criteria->add(BpmnParticipantPeer::PAR_MAXIMUM, $this->par_maximum);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_NUM_PARTICIPANTS)) {
            $criteria->add(BpmnParticipantPeer::PAR_NUM_PARTICIPANTS, $this->par_num_participants);
        }

        if ($this->isColumnModified(BpmnParticipantPeer::PAR_IS_HORIZONTAL)) {
            $criteria->add(BpmnParticipantPeer::PAR_IS_HORIZONTAL, $this->par_is_horizontal);
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
        $criteria = new Criteria(BpmnParticipantPeer::DATABASE_NAME);

        $criteria->add(BpmnParticipantPeer::PAR_UID, $this->par_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getParUid();
    }

    /**
     * Generic method to set the primary key (par_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setParUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnParticipant (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setLnsUid($this->lns_uid);

        $copyObj->setParName($this->par_name);

        $copyObj->setParMinimum($this->par_minimum);

        $copyObj->setParMaximum($this->par_maximum);

        $copyObj->setParNumParticipants($this->par_num_participants);

        $copyObj->setParIsHorizontal($this->par_is_horizontal);


        $copyObj->setNew(true);

        $copyObj->setParUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnParticipant Clone of current object.
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
     * @return     BpmnParticipantPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnParticipantPeer();
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
}

