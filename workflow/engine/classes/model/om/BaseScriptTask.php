<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/ScriptTaskPeer.php';

/**
 * Base class that represents a row from the 'SCRIPT_TASK' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseScriptTask extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ScriptTaskPeer
    */
    protected static $peer;

    /**
     * The value for the scrtas_uid field.
     * @var        string
     */
    protected $scrtas_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid = '';

    /**
     * The value for the act_uid field.
     * @var        string
     */
    protected $act_uid = '';

    /**
     * The value for the scrtas_obj_type field.
     * @var        string
     */
    protected $scrtas_obj_type = 'TRIGGER';

    /**
     * The value for the scrtas_obj_uid field.
     * @var        string
     */
    protected $scrtas_obj_uid = '';

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
     * Get the [scrtas_uid] column value.
     * 
     * @return     string
     */
    public function getScrtasUid()
    {

        return $this->scrtas_uid;
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
     * Get the [act_uid] column value.
     * 
     * @return     string
     */
    public function getActUid()
    {

        return $this->act_uid;
    }

    /**
     * Get the [scrtas_obj_type] column value.
     * 
     * @return     string
     */
    public function getScrtasObjType()
    {

        return $this->scrtas_obj_type;
    }

    /**
     * Get the [scrtas_obj_uid] column value.
     * 
     * @return     string
     */
    public function getScrtasObjUid()
    {

        return $this->scrtas_obj_uid;
    }

    /**
     * Set the value of [scrtas_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setScrtasUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->scrtas_uid !== $v || $v === '') {
            $this->scrtas_uid = $v;
            $this->modifiedColumns[] = ScriptTaskPeer::SCRTAS_UID;
        }

    } // setScrtasUid()

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
            $this->modifiedColumns[] = ScriptTaskPeer::PRJ_UID;
        }

    } // setPrjUid()

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
            $this->modifiedColumns[] = ScriptTaskPeer::ACT_UID;
        }

    } // setActUid()

    /**
     * Set the value of [scrtas_obj_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setScrtasObjType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->scrtas_obj_type !== $v || $v === 'TRIGGER') {
            $this->scrtas_obj_type = $v;
            $this->modifiedColumns[] = ScriptTaskPeer::SCRTAS_OBJ_TYPE;
        }

    } // setScrtasObjType()

    /**
     * Set the value of [scrtas_obj_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setScrtasObjUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->scrtas_obj_uid !== $v || $v === '') {
            $this->scrtas_obj_uid = $v;
            $this->modifiedColumns[] = ScriptTaskPeer::SCRTAS_OBJ_UID;
        }

    } // setScrtasObjUid()

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

            $this->scrtas_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->act_uid = $rs->getString($startcol + 2);

            $this->scrtas_obj_type = $rs->getString($startcol + 3);

            $this->scrtas_obj_uid = $rs->getString($startcol + 4);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 5; // 5 = ScriptTaskPeer::NUM_COLUMNS - ScriptTaskPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating ScriptTask object", $e);
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
            $con = Propel::getConnection(ScriptTaskPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            ScriptTaskPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(ScriptTaskPeer::DATABASE_NAME);
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
                    $pk = ScriptTaskPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += ScriptTaskPeer::doUpdate($this, $con);
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


            if (($retval = ScriptTaskPeer::doValidate($this, $columns)) !== true) {
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
        $pos = ScriptTaskPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getScrtasUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getActUid();
                break;
            case 3:
                return $this->getScrtasObjType();
                break;
            case 4:
                return $this->getScrtasObjUid();
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
        $keys = ScriptTaskPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getScrtasUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getActUid(),
            $keys[3] => $this->getScrtasObjType(),
            $keys[4] => $this->getScrtasObjUid(),
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
        $pos = ScriptTaskPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setScrtasUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setActUid($value);
                break;
            case 3:
                $this->setScrtasObjType($value);
                break;
            case 4:
                $this->setScrtasObjUid($value);
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
        $keys = ScriptTaskPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setScrtasUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setActUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setScrtasObjType($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setScrtasObjUid($arr[$keys[4]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ScriptTaskPeer::DATABASE_NAME);

        if ($this->isColumnModified(ScriptTaskPeer::SCRTAS_UID)) {
            $criteria->add(ScriptTaskPeer::SCRTAS_UID, $this->scrtas_uid);
        }

        if ($this->isColumnModified(ScriptTaskPeer::PRJ_UID)) {
            $criteria->add(ScriptTaskPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(ScriptTaskPeer::ACT_UID)) {
            $criteria->add(ScriptTaskPeer::ACT_UID, $this->act_uid);
        }

        if ($this->isColumnModified(ScriptTaskPeer::SCRTAS_OBJ_TYPE)) {
            $criteria->add(ScriptTaskPeer::SCRTAS_OBJ_TYPE, $this->scrtas_obj_type);
        }

        if ($this->isColumnModified(ScriptTaskPeer::SCRTAS_OBJ_UID)) {
            $criteria->add(ScriptTaskPeer::SCRTAS_OBJ_UID, $this->scrtas_obj_uid);
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
        $criteria = new Criteria(ScriptTaskPeer::DATABASE_NAME);

        $criteria->add(ScriptTaskPeer::SCRTAS_UID, $this->scrtas_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getScrtasUid();
    }

    /**
     * Generic method to set the primary key (scrtas_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setScrtasUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of ScriptTask (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setActUid($this->act_uid);

        $copyObj->setScrtasObjType($this->scrtas_obj_type);

        $copyObj->setScrtasObjUid($this->scrtas_obj_uid);


        $copyObj->setNew(true);

        $copyObj->setScrtasUid(''); // this is a pkey column, so set to default value

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
     * @return     ScriptTask Clone of current object.
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
     * @return     ScriptTaskPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ScriptTaskPeer();
        }
        return self::$peer;
    }
}

