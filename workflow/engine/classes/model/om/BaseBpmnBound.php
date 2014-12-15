<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnBoundPeer.php';

/**
 * Base class that represents a row from the 'BPMN_BOUND' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnBound extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnBoundPeer
    */
    protected static $peer;

    /**
     * The value for the bou_uid field.
     * @var        string
     */
    protected $bou_uid = '';

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
     * The value for the element_uid field.
     * @var        string
     */
    protected $element_uid = '';

    /**
     * The value for the bou_element field.
     * @var        string
     */
    protected $bou_element = '';

    /**
     * The value for the bou_element_type field.
     * @var        string
     */
    protected $bou_element_type = '';

    /**
     * The value for the bou_x field.
     * @var        int
     */
    protected $bou_x = 0;

    /**
     * The value for the bou_y field.
     * @var        int
     */
    protected $bou_y = 0;

    /**
     * The value for the bou_width field.
     * @var        int
     */
    protected $bou_width = 0;

    /**
     * The value for the bou_height field.
     * @var        int
     */
    protected $bou_height = 0;

    /**
     * The value for the bou_rel_position field.
     * @var        int
     */
    protected $bou_rel_position = 0;

    /**
     * The value for the bou_size_identical field.
     * @var        int
     */
    protected $bou_size_identical = 0;

    /**
     * The value for the bou_container field.
     * @var        string
     */
    protected $bou_container = '';

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
     * Get the [bou_uid] column value.
     * 
     * @return     string
     */
    public function getBouUid()
    {

        return $this->bou_uid;
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
     * Get the [element_uid] column value.
     * 
     * @return     string
     */
    public function getElementUid()
    {

        return $this->element_uid;
    }

    /**
     * Get the [bou_element] column value.
     * 
     * @return     string
     */
    public function getBouElement()
    {

        return $this->bou_element;
    }

    /**
     * Get the [bou_element_type] column value.
     * 
     * @return     string
     */
    public function getBouElementType()
    {

        return $this->bou_element_type;
    }

    /**
     * Get the [bou_x] column value.
     * 
     * @return     int
     */
    public function getBouX()
    {

        return $this->bou_x;
    }

    /**
     * Get the [bou_y] column value.
     * 
     * @return     int
     */
    public function getBouY()
    {

        return $this->bou_y;
    }

    /**
     * Get the [bou_width] column value.
     * 
     * @return     int
     */
    public function getBouWidth()
    {

        return $this->bou_width;
    }

    /**
     * Get the [bou_height] column value.
     * 
     * @return     int
     */
    public function getBouHeight()
    {

        return $this->bou_height;
    }

    /**
     * Get the [bou_rel_position] column value.
     * 
     * @return     int
     */
    public function getBouRelPosition()
    {

        return $this->bou_rel_position;
    }

    /**
     * Get the [bou_size_identical] column value.
     * 
     * @return     int
     */
    public function getBouSizeIdentical()
    {

        return $this->bou_size_identical;
    }

    /**
     * Get the [bou_container] column value.
     * 
     * @return     string
     */
    public function getBouContainer()
    {

        return $this->bou_container;
    }

    /**
     * Set the value of [bou_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setBouUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->bou_uid !== $v || $v === '') {
            $this->bou_uid = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_UID;
        }

    } // setBouUid()

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
            $this->modifiedColumns[] = BpmnBoundPeer::PRJ_UID;
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
            $this->modifiedColumns[] = BpmnBoundPeer::DIA_UID;
        }

        if ($this->aBpmnDiagram !== null && $this->aBpmnDiagram->getDiaUid() !== $v) {
            $this->aBpmnDiagram = null;
        }

    } // setDiaUid()

    /**
     * Set the value of [element_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setElementUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->element_uid !== $v || $v === '') {
            $this->element_uid = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::ELEMENT_UID;
        }

    } // setElementUid()

    /**
     * Set the value of [bou_element] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setBouElement($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->bou_element !== $v || $v === '') {
            $this->bou_element = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_ELEMENT;
        }

    } // setBouElement()

    /**
     * Set the value of [bou_element_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setBouElementType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->bou_element_type !== $v || $v === '') {
            $this->bou_element_type = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_ELEMENT_TYPE;
        }

    } // setBouElementType()

    /**
     * Set the value of [bou_x] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouX($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_x !== $v || $v === 0) {
            $this->bou_x = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_X;
        }

    } // setBouX()

    /**
     * Set the value of [bou_y] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouY($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_y !== $v || $v === 0) {
            $this->bou_y = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_Y;
        }

    } // setBouY()

    /**
     * Set the value of [bou_width] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouWidth($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_width !== $v || $v === 0) {
            $this->bou_width = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_WIDTH;
        }

    } // setBouWidth()

    /**
     * Set the value of [bou_height] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouHeight($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_height !== $v || $v === 0) {
            $this->bou_height = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_HEIGHT;
        }

    } // setBouHeight()

    /**
     * Set the value of [bou_rel_position] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouRelPosition($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_rel_position !== $v || $v === 0) {
            $this->bou_rel_position = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_REL_POSITION;
        }

    } // setBouRelPosition()

    /**
     * Set the value of [bou_size_identical] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setBouSizeIdentical($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bou_size_identical !== $v || $v === 0) {
            $this->bou_size_identical = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_SIZE_IDENTICAL;
        }

    } // setBouSizeIdentical()

    /**
     * Set the value of [bou_container] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setBouContainer($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->bou_container !== $v || $v === '') {
            $this->bou_container = $v;
            $this->modifiedColumns[] = BpmnBoundPeer::BOU_CONTAINER;
        }

    } // setBouContainer()

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

            $this->bou_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->dia_uid = $rs->getString($startcol + 2);

            $this->element_uid = $rs->getString($startcol + 3);

            $this->bou_element = $rs->getString($startcol + 4);

            $this->bou_element_type = $rs->getString($startcol + 5);

            $this->bou_x = $rs->getInt($startcol + 6);

            $this->bou_y = $rs->getInt($startcol + 7);

            $this->bou_width = $rs->getInt($startcol + 8);

            $this->bou_height = $rs->getInt($startcol + 9);

            $this->bou_rel_position = $rs->getInt($startcol + 10);

            $this->bou_size_identical = $rs->getInt($startcol + 11);

            $this->bou_container = $rs->getString($startcol + 12);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 13; // 13 = BpmnBoundPeer::NUM_COLUMNS - BpmnBoundPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnBound object", $e);
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
            $con = Propel::getConnection(BpmnBoundPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnBoundPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnBoundPeer::DATABASE_NAME);
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
                    $pk = BpmnBoundPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnBoundPeer::doUpdate($this, $con);
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


            if (($retval = BpmnBoundPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnBoundPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getBouUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getDiaUid();
                break;
            case 3:
                return $this->getElementUid();
                break;
            case 4:
                return $this->getBouElement();
                break;
            case 5:
                return $this->getBouElementType();
                break;
            case 6:
                return $this->getBouX();
                break;
            case 7:
                return $this->getBouY();
                break;
            case 8:
                return $this->getBouWidth();
                break;
            case 9:
                return $this->getBouHeight();
                break;
            case 10:
                return $this->getBouRelPosition();
                break;
            case 11:
                return $this->getBouSizeIdentical();
                break;
            case 12:
                return $this->getBouContainer();
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
        $keys = BpmnBoundPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getBouUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getDiaUid(),
            $keys[3] => $this->getElementUid(),
            $keys[4] => $this->getBouElement(),
            $keys[5] => $this->getBouElementType(),
            $keys[6] => $this->getBouX(),
            $keys[7] => $this->getBouY(),
            $keys[8] => $this->getBouWidth(),
            $keys[9] => $this->getBouHeight(),
            $keys[10] => $this->getBouRelPosition(),
            $keys[11] => $this->getBouSizeIdentical(),
            $keys[12] => $this->getBouContainer(),
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
        $pos = BpmnBoundPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setBouUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setDiaUid($value);
                break;
            case 3:
                $this->setElementUid($value);
                break;
            case 4:
                $this->setBouElement($value);
                break;
            case 5:
                $this->setBouElementType($value);
                break;
            case 6:
                $this->setBouX($value);
                break;
            case 7:
                $this->setBouY($value);
                break;
            case 8:
                $this->setBouWidth($value);
                break;
            case 9:
                $this->setBouHeight($value);
                break;
            case 10:
                $this->setBouRelPosition($value);
                break;
            case 11:
                $this->setBouSizeIdentical($value);
                break;
            case 12:
                $this->setBouContainer($value);
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
        $keys = BpmnBoundPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setBouUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setDiaUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setElementUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setBouElement($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setBouElementType($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setBouX($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setBouY($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setBouWidth($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setBouHeight($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setBouRelPosition($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setBouSizeIdentical($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setBouContainer($arr[$keys[12]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnBoundPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnBoundPeer::BOU_UID)) {
            $criteria->add(BpmnBoundPeer::BOU_UID, $this->bou_uid);
        }

        if ($this->isColumnModified(BpmnBoundPeer::PRJ_UID)) {
            $criteria->add(BpmnBoundPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnBoundPeer::DIA_UID)) {
            $criteria->add(BpmnBoundPeer::DIA_UID, $this->dia_uid);
        }

        if ($this->isColumnModified(BpmnBoundPeer::ELEMENT_UID)) {
            $criteria->add(BpmnBoundPeer::ELEMENT_UID, $this->element_uid);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_ELEMENT)) {
            $criteria->add(BpmnBoundPeer::BOU_ELEMENT, $this->bou_element);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_ELEMENT_TYPE)) {
            $criteria->add(BpmnBoundPeer::BOU_ELEMENT_TYPE, $this->bou_element_type);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_X)) {
            $criteria->add(BpmnBoundPeer::BOU_X, $this->bou_x);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_Y)) {
            $criteria->add(BpmnBoundPeer::BOU_Y, $this->bou_y);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_WIDTH)) {
            $criteria->add(BpmnBoundPeer::BOU_WIDTH, $this->bou_width);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_HEIGHT)) {
            $criteria->add(BpmnBoundPeer::BOU_HEIGHT, $this->bou_height);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_REL_POSITION)) {
            $criteria->add(BpmnBoundPeer::BOU_REL_POSITION, $this->bou_rel_position);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_SIZE_IDENTICAL)) {
            $criteria->add(BpmnBoundPeer::BOU_SIZE_IDENTICAL, $this->bou_size_identical);
        }

        if ($this->isColumnModified(BpmnBoundPeer::BOU_CONTAINER)) {
            $criteria->add(BpmnBoundPeer::BOU_CONTAINER, $this->bou_container);
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
        $criteria = new Criteria(BpmnBoundPeer::DATABASE_NAME);

        $criteria->add(BpmnBoundPeer::BOU_UID, $this->bou_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getBouUid();
    }

    /**
     * Generic method to set the primary key (bou_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setBouUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnBound (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setDiaUid($this->dia_uid);

        $copyObj->setElementUid($this->element_uid);

        $copyObj->setBouElement($this->bou_element);

        $copyObj->setBouElementType($this->bou_element_type);

        $copyObj->setBouX($this->bou_x);

        $copyObj->setBouY($this->bou_y);

        $copyObj->setBouWidth($this->bou_width);

        $copyObj->setBouHeight($this->bou_height);

        $copyObj->setBouRelPosition($this->bou_rel_position);

        $copyObj->setBouSizeIdentical($this->bou_size_identical);

        $copyObj->setBouContainer($this->bou_container);


        $copyObj->setNew(true);

        $copyObj->setBouUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnBound Clone of current object.
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
     * @return     BpmnBoundPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnBoundPeer();
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

