<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AppAssignSelfServiceValuePeer.php';

/**
 * Base class that represents a row from the 'APP_ASSIGN_SELF_SERVICE_VALUE' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseAppAssignSelfServiceValue extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AppAssignSelfServiceValuePeer
    */
    protected static $peer;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the app_uid field.
     * @var        string
     */
    protected $app_uid;

    /**
     * The value for the app_number field.
     * @var        int
     */
    protected $app_number = 0;

    /**
     * The value for the del_index field.
     * @var        int
     */
    protected $del_index = 0;

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid;

    /**
     * The value for the tas_uid field.
     * @var        string
     */
    protected $tas_uid;

    /**
     * The value for the tas_id field.
     * @var        int
     */
    protected $tas_id = 0;

    /**
     * The value for the grp_uid field.
     * @var        string
     */
    protected $grp_uid;

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
     * Get the [id] column value.
     * 
     * @return     int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [app_uid] column value.
     * 
     * @return     string
     */
    public function getAppUid()
    {

        return $this->app_uid;
    }

    /**
     * Get the [app_number] column value.
     * 
     * @return     int
     */
    public function getAppNumber()
    {

        return $this->app_number;
    }

    /**
     * Get the [del_index] column value.
     * 
     * @return     int
     */
    public function getDelIndex()
    {

        return $this->del_index;
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
     * Get the [tas_uid] column value.
     * 
     * @return     string
     */
    public function getTasUid()
    {

        return $this->tas_uid;
    }

    /**
     * Get the [tas_id] column value.
     * 
     * @return     int
     */
    public function getTasId()
    {

        return $this->tas_id;
    }

    /**
     * Get the [grp_uid] column value.
     * 
     * @return     string
     */
    public function getGrpUid()
    {

        return $this->grp_uid;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::ID;
        }

    } // setId()

    /**
     * Set the value of [app_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAppUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->app_uid !== $v) {
            $this->app_uid = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::APP_UID;
        }

    } // setAppUid()

    /**
     * Set the value of [app_number] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setAppNumber($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->app_number !== $v || $v === 0) {
            $this->app_number = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::APP_NUMBER;
        }

    } // setAppNumber()

    /**
     * Set the value of [del_index] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setDelIndex($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->del_index !== $v || $v === 0) {
            $this->del_index = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::DEL_INDEX;
        }

    } // setDelIndex()

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

        if ($this->pro_uid !== $v) {
            $this->pro_uid = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::PRO_UID;
        }

    } // setProUid()

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
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::TAS_UID;
        }

    } // setTasUid()

    /**
     * Set the value of [tas_id] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setTasId($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tas_id !== $v || $v === 0) {
            $this->tas_id = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::TAS_ID;
        }

    } // setTasId()

    /**
     * Set the value of [grp_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setGrpUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->grp_uid !== $v) {
            $this->grp_uid = $v;
            $this->modifiedColumns[] = AppAssignSelfServiceValuePeer::GRP_UID;
        }

    } // setGrpUid()

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

            $this->id = $rs->getInt($startcol + 0);

            $this->app_uid = $rs->getString($startcol + 1);

            $this->app_number = $rs->getInt($startcol + 2);

            $this->del_index = $rs->getInt($startcol + 3);

            $this->pro_uid = $rs->getString($startcol + 4);

            $this->tas_uid = $rs->getString($startcol + 5);

            $this->tas_id = $rs->getInt($startcol + 6);

            $this->grp_uid = $rs->getString($startcol + 7);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 8; // 8 = AppAssignSelfServiceValuePeer::NUM_COLUMNS - AppAssignSelfServiceValuePeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating AppAssignSelfServiceValue object", $e);
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
            $con = Propel::getConnection(AppAssignSelfServiceValuePeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            AppAssignSelfServiceValuePeer::doDelete($this, $con);
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
            $con = Propel::getConnection(AppAssignSelfServiceValuePeer::DATABASE_NAME);
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
                    $pk = AppAssignSelfServiceValuePeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setId($pk);  //[IMV] update autoincrement primary key

                    $this->setNew(false);
                } else {
                    $affectedRows += AppAssignSelfServiceValuePeer::doUpdate($this, $con);
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


            if (($retval = AppAssignSelfServiceValuePeer::doValidate($this, $columns)) !== true) {
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
        $pos = AppAssignSelfServiceValuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getId();
                break;
            case 1:
                return $this->getAppUid();
                break;
            case 2:
                return $this->getAppNumber();
                break;
            case 3:
                return $this->getDelIndex();
                break;
            case 4:
                return $this->getProUid();
                break;
            case 5:
                return $this->getTasUid();
                break;
            case 6:
                return $this->getTasId();
                break;
            case 7:
                return $this->getGrpUid();
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
        $keys = AppAssignSelfServiceValuePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAppUid(),
            $keys[2] => $this->getAppNumber(),
            $keys[3] => $this->getDelIndex(),
            $keys[4] => $this->getProUid(),
            $keys[5] => $this->getTasUid(),
            $keys[6] => $this->getTasId(),
            $keys[7] => $this->getGrpUid(),
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
        $pos = AppAssignSelfServiceValuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setId($value);
                break;
            case 1:
                $this->setAppUid($value);
                break;
            case 2:
                $this->setAppNumber($value);
                break;
            case 3:
                $this->setDelIndex($value);
                break;
            case 4:
                $this->setProUid($value);
                break;
            case 5:
                $this->setTasUid($value);
                break;
            case 6:
                $this->setTasId($value);
                break;
            case 7:
                $this->setGrpUid($value);
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
        $keys = AppAssignSelfServiceValuePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setAppUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setAppNumber($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setDelIndex($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setProUid($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setTasUid($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setTasId($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setGrpUid($arr[$keys[7]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AppAssignSelfServiceValuePeer::DATABASE_NAME);

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::ID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::ID, $this->id);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::APP_UID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::APP_UID, $this->app_uid);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::APP_NUMBER)) {
            $criteria->add(AppAssignSelfServiceValuePeer::APP_NUMBER, $this->app_number);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::DEL_INDEX)) {
            $criteria->add(AppAssignSelfServiceValuePeer::DEL_INDEX, $this->del_index);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::PRO_UID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::TAS_UID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::TAS_UID, $this->tas_uid);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::TAS_ID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::TAS_ID, $this->tas_id);
        }

        if ($this->isColumnModified(AppAssignSelfServiceValuePeer::GRP_UID)) {
            $criteria->add(AppAssignSelfServiceValuePeer::GRP_UID, $this->grp_uid);
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
        $criteria = new Criteria(AppAssignSelfServiceValuePeer::DATABASE_NAME);

        $criteria->add(AppAssignSelfServiceValuePeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param      int $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of AppAssignSelfServiceValue (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setAppUid($this->app_uid);

        $copyObj->setAppNumber($this->app_number);

        $copyObj->setDelIndex($this->del_index);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setTasUid($this->tas_uid);

        $copyObj->setTasId($this->tas_id);

        $copyObj->setGrpUid($this->grp_uid);


        $copyObj->setNew(true);

        $copyObj->setId(NULL); // this is a pkey column, so set to default value

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
     * @return     AppAssignSelfServiceValue Clone of current object.
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
     * @return     AppAssignSelfServiceValuePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AppAssignSelfServiceValuePeer();
        }
        return self::$peer;
    }
}

