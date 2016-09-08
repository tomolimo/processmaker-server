<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AddonsStorePeer.php';

/**
 * Base class that represents a row from the 'ADDONS_STORE' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAddonsStore extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AddonsStorePeer
    */
    protected static $peer;

    /**
     * The value for the store_id field.
     * @var        string
     */
    protected $store_id;

    /**
     * The value for the store_version field.
     * @var        int
     */
    protected $store_version;

    /**
     * The value for the store_location field.
     * @var        string
     */
    protected $store_location;

    /**
     * The value for the store_type field.
     * @var        string
     */
    protected $store_type;

    /**
     * The value for the store_last_updated field.
     * @var        int
     */
    protected $store_last_updated;

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
     * Get the [store_id] column value.
     * 
     * @return     string
     */
    public function getStoreId()
    {

        return $this->store_id;
    }

    /**
     * Get the [store_version] column value.
     * 
     * @return     int
     */
    public function getStoreVersion()
    {

        return $this->store_version;
    }

    /**
     * Get the [store_location] column value.
     * 
     * @return     string
     */
    public function getStoreLocation()
    {

        return $this->store_location;
    }

    /**
     * Get the [store_type] column value.
     * 
     * @return     string
     */
    public function getStoreType()
    {

        return $this->store_type;
    }

    /**
     * Get the [optionally formatted] [store_last_updated] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getStoreLastUpdated($format = 'Y-m-d H:i:s')
    {

        if ($this->store_last_updated === null || $this->store_last_updated === '') {
            return null;
        } elseif (!is_int($this->store_last_updated)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->store_last_updated);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [store_last_updated] as date/time value: " .
                    var_export($this->store_last_updated, true));
            }
        } else {
            $ts = $this->store_last_updated;
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
     * Set the value of [store_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setStoreId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->store_id !== $v) {
            $this->store_id = $v;
            $this->modifiedColumns[] = AddonsStorePeer::STORE_ID;
        }

    } // setStoreId()

    /**
     * Set the value of [store_version] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setStoreVersion($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->store_version !== $v) {
            $this->store_version = $v;
            $this->modifiedColumns[] = AddonsStorePeer::STORE_VERSION;
        }

    } // setStoreVersion()

    /**
     * Set the value of [store_location] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setStoreLocation($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->store_location !== $v) {
            $this->store_location = $v;
            $this->modifiedColumns[] = AddonsStorePeer::STORE_LOCATION;
        }

    } // setStoreLocation()

    /**
     * Set the value of [store_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setStoreType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->store_type !== $v) {
            $this->store_type = $v;
            $this->modifiedColumns[] = AddonsStorePeer::STORE_TYPE;
        }

    } // setStoreType()

    /**
     * Set the value of [store_last_updated] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setStoreLastUpdated($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [store_last_updated] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->store_last_updated !== $ts) {
            $this->store_last_updated = $ts;
            $this->modifiedColumns[] = AddonsStorePeer::STORE_LAST_UPDATED;
        }

    } // setStoreLastUpdated()

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

            $this->store_id = $rs->getString($startcol + 0);

            $this->store_version = $rs->getInt($startcol + 1);

            $this->store_location = $rs->getString($startcol + 2);

            $this->store_type = $rs->getString($startcol + 3);

            $this->store_last_updated = $rs->getTimestamp($startcol + 4, null);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 5; // 5 = AddonsStorePeer::NUM_COLUMNS - AddonsStorePeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AddonsStore object", $e);
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
            $con = Propel::getConnection(AddonsStorePeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AddonsStorePeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AddonsStorePeer::DATABASE_NAME);
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
                    $pk = AddonsStorePeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += AddonsStorePeer::doUpdate($this, $con);
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


            if (($retval = AddonsStorePeer::doValidate($this, $columns)) !== true) {
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
        $pos = AddonsStorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getStoreId();
                break;
            case 1:
                return $this->getStoreVersion();
                break;
            case 2:
                return $this->getStoreLocation();
                break;
            case 3:
                return $this->getStoreType();
                break;
            case 4:
                return $this->getStoreLastUpdated();
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
        $keys = AddonsStorePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getStoreId(),
            $keys[1] => $this->getStoreVersion(),
            $keys[2] => $this->getStoreLocation(),
            $keys[3] => $this->getStoreType(),
            $keys[4] => $this->getStoreLastUpdated(),
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
        $pos = AddonsStorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setStoreId($value);
                break;
            case 1:
                $this->setStoreVersion($value);
                break;
            case 2:
                $this->setStoreLocation($value);
                break;
            case 3:
                $this->setStoreType($value);
                break;
            case 4:
                $this->setStoreLastUpdated($value);
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
        $keys = AddonsStorePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setStoreId($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setStoreVersion($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setStoreLocation($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setStoreType($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setStoreLastUpdated($arr[$keys[4]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AddonsStorePeer::DATABASE_NAME);

        if ($this->isColumnModified(AddonsStorePeer::STORE_ID)) {
            $criteria->add(AddonsStorePeer::STORE_ID, $this->store_id);
        }

        if ($this->isColumnModified(AddonsStorePeer::STORE_VERSION)) {
            $criteria->add(AddonsStorePeer::STORE_VERSION, $this->store_version);
        }

        if ($this->isColumnModified(AddonsStorePeer::STORE_LOCATION)) {
            $criteria->add(AddonsStorePeer::STORE_LOCATION, $this->store_location);
        }

        if ($this->isColumnModified(AddonsStorePeer::STORE_TYPE)) {
            $criteria->add(AddonsStorePeer::STORE_TYPE, $this->store_type);
        }

        if ($this->isColumnModified(AddonsStorePeer::STORE_LAST_UPDATED)) {
            $criteria->add(AddonsStorePeer::STORE_LAST_UPDATED, $this->store_last_updated);
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
        $criteria = new Criteria(AddonsStorePeer::DATABASE_NAME);

        $criteria->add(AddonsStorePeer::STORE_ID, $this->store_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getStoreId();
    }

    /**
     * Generic method to set the primary key (store_id column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setStoreId($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AddonsStore (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setStoreVersion($this->store_version);

        $copyObj->setStoreLocation($this->store_location);

        $copyObj->setStoreType($this->store_type);

        $copyObj->setStoreLastUpdated($this->store_last_updated);


        $copyObj->setNew(true);

        $copyObj->setStoreId(NULL); // this is a pkey column, so set to default value

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
     * @return     AddonsStore Clone of current object.
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
     * @return     AddonsStorePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AddonsStorePeer();
        }
        return self::$peer;
    }
}

