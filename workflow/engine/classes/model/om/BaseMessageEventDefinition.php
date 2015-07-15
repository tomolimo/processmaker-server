<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/MessageEventDefinitionPeer.php';

/**
 * Base class that represents a row from the 'MESSAGE_EVENT_DEFINITION' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseMessageEventDefinition extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        MessageEventDefinitionPeer
    */
    protected static $peer;

    /**
     * The value for the msged_uid field.
     * @var        string
     */
    protected $msged_uid;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the evn_uid field.
     * @var        string
     */
    protected $evn_uid;

    /**
     * The value for the msgt_uid field.
     * @var        string
     */
    protected $msgt_uid = '';

    /**
     * The value for the msged_usr_uid field.
     * @var        string
     */
    protected $msged_usr_uid = '';

    /**
     * The value for the msged_variables field.
     * @var        string
     */
    protected $msged_variables;

    /**
     * The value for the msged_correlation field.
     * @var        string
     */
    protected $msged_correlation = '';

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
     * Get the [msged_uid] column value.
     * 
     * @return     string
     */
    public function getMsgedUid()
    {

        return $this->msged_uid;
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
     * Get the [evn_uid] column value.
     * 
     * @return     string
     */
    public function getEvnUid()
    {

        return $this->evn_uid;
    }

    /**
     * Get the [msgt_uid] column value.
     * 
     * @return     string
     */
    public function getMsgtUid()
    {

        return $this->msgt_uid;
    }

    /**
     * Get the [msged_usr_uid] column value.
     * 
     * @return     string
     */
    public function getMsgedUsrUid()
    {

        return $this->msged_usr_uid;
    }

    /**
     * Get the [msged_variables] column value.
     * 
     * @return     string
     */
    public function getMsgedVariables()
    {

        return $this->msged_variables;
    }

    /**
     * Get the [msged_correlation] column value.
     * 
     * @return     string
     */
    public function getMsgedCorrelation()
    {

        return $this->msged_correlation;
    }

    /**
     * Set the value of [msged_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgedUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msged_uid !== $v) {
            $this->msged_uid = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::MSGED_UID;
        }

    } // setMsgedUid()

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
            $this->modifiedColumns[] = MessageEventDefinitionPeer::PRJ_UID;
        }

    } // setPrjUid()

    /**
     * Set the value of [evn_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEvnUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->evn_uid !== $v) {
            $this->evn_uid = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::EVN_UID;
        }

    } // setEvnUid()

    /**
     * Set the value of [msgt_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgtUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msgt_uid !== $v || $v === '') {
            $this->msgt_uid = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::MSGT_UID;
        }

    } // setMsgtUid()

    /**
     * Set the value of [msged_usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgedUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msged_usr_uid !== $v || $v === '') {
            $this->msged_usr_uid = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::MSGED_USR_UID;
        }

    } // setMsgedUsrUid()

    /**
     * Set the value of [msged_variables] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgedVariables($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msged_variables !== $v) {
            $this->msged_variables = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::MSGED_VARIABLES;
        }

    } // setMsgedVariables()

    /**
     * Set the value of [msged_correlation] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMsgedCorrelation($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->msged_correlation !== $v || $v === '') {
            $this->msged_correlation = $v;
            $this->modifiedColumns[] = MessageEventDefinitionPeer::MSGED_CORRELATION;
        }

    } // setMsgedCorrelation()

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

            $this->msged_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->evn_uid = $rs->getString($startcol + 2);

            $this->msgt_uid = $rs->getString($startcol + 3);

            $this->msged_usr_uid = $rs->getString($startcol + 4);

            $this->msged_variables = $rs->getString($startcol + 5);

            $this->msged_correlation = $rs->getString($startcol + 6);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 7; // 7 = MessageEventDefinitionPeer::NUM_COLUMNS - MessageEventDefinitionPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating MessageEventDefinition object", $e);
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
            $con = Propel::getConnection(MessageEventDefinitionPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            MessageEventDefinitionPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(MessageEventDefinitionPeer::DATABASE_NAME);
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
                    $pk = MessageEventDefinitionPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += MessageEventDefinitionPeer::doUpdate($this, $con);
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


            if (($retval = MessageEventDefinitionPeer::doValidate($this, $columns)) !== true) {
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
        $pos = MessageEventDefinitionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getMsgedUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getEvnUid();
                break;
            case 3:
                return $this->getMsgtUid();
                break;
            case 4:
                return $this->getMsgedUsrUid();
                break;
            case 5:
                return $this->getMsgedVariables();
                break;
            case 6:
                return $this->getMsgedCorrelation();
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
        $keys = MessageEventDefinitionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMsgedUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getEvnUid(),
            $keys[3] => $this->getMsgtUid(),
            $keys[4] => $this->getMsgedUsrUid(),
            $keys[5] => $this->getMsgedVariables(),
            $keys[6] => $this->getMsgedCorrelation(),
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
        $pos = MessageEventDefinitionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setMsgedUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setEvnUid($value);
                break;
            case 3:
                $this->setMsgtUid($value);
                break;
            case 4:
                $this->setMsgedUsrUid($value);
                break;
            case 5:
                $this->setMsgedVariables($value);
                break;
            case 6:
                $this->setMsgedCorrelation($value);
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
        $keys = MessageEventDefinitionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMsgedUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setEvnUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setMsgtUid($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setMsgedUsrUid($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setMsgedVariables($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setMsgedCorrelation($arr[$keys[6]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MessageEventDefinitionPeer::DATABASE_NAME);

        if ($this->isColumnModified(MessageEventDefinitionPeer::MSGED_UID)) {
            $criteria->add(MessageEventDefinitionPeer::MSGED_UID, $this->msged_uid);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::PRJ_UID)) {
            $criteria->add(MessageEventDefinitionPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::EVN_UID)) {
            $criteria->add(MessageEventDefinitionPeer::EVN_UID, $this->evn_uid);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::MSGT_UID)) {
            $criteria->add(MessageEventDefinitionPeer::MSGT_UID, $this->msgt_uid);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::MSGED_USR_UID)) {
            $criteria->add(MessageEventDefinitionPeer::MSGED_USR_UID, $this->msged_usr_uid);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::MSGED_VARIABLES)) {
            $criteria->add(MessageEventDefinitionPeer::MSGED_VARIABLES, $this->msged_variables);
        }

        if ($this->isColumnModified(MessageEventDefinitionPeer::MSGED_CORRELATION)) {
            $criteria->add(MessageEventDefinitionPeer::MSGED_CORRELATION, $this->msged_correlation);
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
        $criteria = new Criteria(MessageEventDefinitionPeer::DATABASE_NAME);

        $criteria->add(MessageEventDefinitionPeer::MSGED_UID, $this->msged_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getMsgedUid();
    }

    /**
     * Generic method to set the primary key (msged_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setMsgedUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of MessageEventDefinition (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setEvnUid($this->evn_uid);

        $copyObj->setMsgtUid($this->msgt_uid);

        $copyObj->setMsgedUsrUid($this->msged_usr_uid);

        $copyObj->setMsgedVariables($this->msged_variables);

        $copyObj->setMsgedCorrelation($this->msged_correlation);


        $copyObj->setNew(true);

        $copyObj->setMsgedUid(NULL); // this is a pkey column, so set to default value

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
     * @return     MessageEventDefinition Clone of current object.
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
     * @return     MessageEventDefinitionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new MessageEventDefinitionPeer();
        }
        return self::$peer;
    }
}

