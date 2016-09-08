<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/ElementTaskRelationPeer.php';

/**
 * Base class that represents a row from the 'ELEMENT_TASK_RELATION' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseElementTaskRelation extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ElementTaskRelationPeer
    */
    protected static $peer;

    /**
     * The value for the etr_uid field.
     * @var        string
     */
    protected $etr_uid;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the element_uid field.
     * @var        string
     */
    protected $element_uid;

    /**
     * The value for the element_type field.
     * @var        string
     */
    protected $element_type = '';

    /**
     * The value for the tas_uid field.
     * @var        string
     */
    protected $tas_uid;

    /**
     * The value for the element_uid_dest field.
     * @var        string
     */
    protected $element_uid_dest = '';

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
     * Get the [etr_uid] column value.
     * 
     * @return     string
     */
    public function getEtrUid()
    {

        return $this->etr_uid;
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
     * Get the [element_uid] column value.
     * 
     * @return     string
     */
    public function getElementUid()
    {

        return $this->element_uid;
    }

    /**
     * Get the [element_type] column value.
     * 
     * @return     string
     */
    public function getElementType()
    {

        return $this->element_type;
    }

    /**
     * Get the [tas_uid] column value.
     * 
     * @return     string
     */
    public function getTasUid()
    {

        return $this->tas_uid;
    }

    /**
     * Get the [element_uid_dest] column value.
     * 
     * @return     string
     */
    public function getElementUidDest()
    {

        return $this->element_uid_dest;
    }

    /**
     * Set the value of [etr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEtrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->etr_uid !== $v) {
            $this->etr_uid = $v;
            $this->modifiedColumns[] = ElementTaskRelationPeer::ETR_UID;
        }

    } // setEtrUid()

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
            $this->modifiedColumns[] = ElementTaskRelationPeer::PRJ_UID;
        }

    } // setPrjUid()

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

        if ($this->element_uid !== $v) {
            $this->element_uid = $v;
            $this->modifiedColumns[] = ElementTaskRelationPeer::ELEMENT_UID;
        }

    } // setElementUid()

    /**
     * Set the value of [element_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setElementType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->element_type !== $v || $v === '') {
            $this->element_type = $v;
            $this->modifiedColumns[] = ElementTaskRelationPeer::ELEMENT_TYPE;
        }

    } // setElementType()

    /**
     * Set the value of [tas_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setTasUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->tas_uid !== $v) {
            $this->tas_uid = $v;
            $this->modifiedColumns[] = ElementTaskRelationPeer::TAS_UID;
        }

    } // setTasUid()

    /**
     * Set the value of [element_uid_dest] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setElementUidDest($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->element_uid_dest !== $v || $v === '') {
            $this->element_uid_dest = $v;
            $this->modifiedColumns[] = ElementTaskRelationPeer::ELEMENT_UID_DEST;
        }

    } // setElementUidDest()

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

            $this->etr_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->element_uid = $rs->getString($startcol + 2);

            $this->element_type = $rs->getString($startcol + 3);

            $this->tas_uid = $rs->getString($startcol + 4);

            $this->element_uid_dest = $rs->getString($startcol + 5);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 6; // 6 = ElementTaskRelationPeer::NUM_COLUMNS - ElementTaskRelationPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating ElementTaskRelation object", $e);
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
            $con = Propel::getConnection(ElementTaskRelationPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            ElementTaskRelationPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(ElementTaskRelationPeer::DATABASE_NAME);
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
                    $pk = ElementTaskRelationPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += ElementTaskRelationPeer::doUpdate($this, $con);
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


            if (($retval = ElementTaskRelationPeer::doValidate($this, $columns)) !== true) {
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
        $pos = ElementTaskRelationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEtrUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getElementUid();
                break;
            case 3:
                return $this->getElementType();
                break;
            case 4:
                return $this->getTasUid();
                break;
            case 5:
                return $this->getElementUidDest();
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
        $keys = ElementTaskRelationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEtrUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getElementUid(),
            $keys[3] => $this->getElementType(),
            $keys[4] => $this->getTasUid(),
            $keys[5] => $this->getElementUidDest(),
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
        $pos = ElementTaskRelationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setEtrUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setElementUid($value);
                break;
            case 3:
                $this->setElementType($value);
                break;
            case 4:
                $this->setTasUid($value);
                break;
            case 5:
                $this->setElementUidDest($value);
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
        $keys = ElementTaskRelationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEtrUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setElementUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setElementType($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setTasUid($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setElementUidDest($arr[$keys[5]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ElementTaskRelationPeer::DATABASE_NAME);

        if ($this->isColumnModified(ElementTaskRelationPeer::ETR_UID)) {
            $criteria->add(ElementTaskRelationPeer::ETR_UID, $this->etr_uid);
        }

        if ($this->isColumnModified(ElementTaskRelationPeer::PRJ_UID)) {
            $criteria->add(ElementTaskRelationPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(ElementTaskRelationPeer::ELEMENT_UID)) {
            $criteria->add(ElementTaskRelationPeer::ELEMENT_UID, $this->element_uid);
        }

        if ($this->isColumnModified(ElementTaskRelationPeer::ELEMENT_TYPE)) {
            $criteria->add(ElementTaskRelationPeer::ELEMENT_TYPE, $this->element_type);
        }

        if ($this->isColumnModified(ElementTaskRelationPeer::TAS_UID)) {
            $criteria->add(ElementTaskRelationPeer::TAS_UID, $this->tas_uid);
        }

        if ($this->isColumnModified(ElementTaskRelationPeer::ELEMENT_UID_DEST)) {
            $criteria->add(ElementTaskRelationPeer::ELEMENT_UID_DEST, $this->element_uid_dest);
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
        $criteria = new Criteria(ElementTaskRelationPeer::DATABASE_NAME);

        $criteria->add(ElementTaskRelationPeer::ETR_UID, $this->etr_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getEtrUid();
    }

    /**
     * Generic method to set the primary key (etr_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setEtrUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of ElementTaskRelation (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setElementUid($this->element_uid);

        $copyObj->setElementType($this->element_type);

        $copyObj->setTasUid($this->tas_uid);

        $copyObj->setElementUidDest($this->element_uid_dest);


        $copyObj->setNew(true);

        $copyObj->setEtrUid(NULL); // this is a pkey column, so set to default value

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
     * @return     ElementTaskRelation Clone of current object.
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
     * @return     ElementTaskRelationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ElementTaskRelationPeer();
        }
        return self::$peer;
    }
}

