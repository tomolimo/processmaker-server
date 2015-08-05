<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/EmailEventPeer.php';

/**
 * Base class that represents a row from the 'EMAIL_EVENT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseEmailEvent extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EmailEventPeer
    */
    protected static $peer;

    /**
     * The value for the email_event_uid field.
     * @var        string
     */
    protected $email_event_uid;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid = '';

    /**
     * The value for the evn_uid field.
     * @var        string
     */
    protected $evn_uid;

    /**
     * The value for the email_event_from field.
     * @var        string
     */
    protected $email_event_from = '';

    /**
     * The value for the email_event_to field.
     * @var        string
     */
    protected $email_event_to;

    /**
     * The value for the email_event_subject field.
     * @var        string
     */
    protected $email_event_subject = '';

    /**
     * The value for the prf_uid field.
     * @var        string
     */
    protected $prf_uid = '';

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
     * Get the [email_event_uid] column value.
     * 
     * @return     string
     */
    public function getEmailEventUid()
    {

        return $this->email_event_uid;
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
     * Get the [email_event_from] column value.
     * 
     * @return     string
     */
    public function getEmailEventFrom()
    {

        return $this->email_event_from;
    }

    /**
     * Get the [email_event_to] column value.
     * 
     * @return     string
     */
    public function getEmailEventTo()
    {

        return $this->email_event_to;
    }

    /**
     * Get the [email_event_subject] column value.
     * 
     * @return     string
     */
    public function getEmailEventSubject()
    {

        return $this->email_event_subject;
    }

    /**
     * Get the [prf_uid] column value.
     * 
     * @return     string
     */
    public function getPrfUid()
    {

        return $this->prf_uid;
    }

    /**
     * Set the value of [email_event_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEmailEventUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->email_event_uid !== $v) {
            $this->email_event_uid = $v;
            $this->modifiedColumns[] = EmailEventPeer::EMAIL_EVENT_UID;
        }

    } // setEmailEventUid()

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
            $this->modifiedColumns[] = EmailEventPeer::PRJ_UID;
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
            $this->modifiedColumns[] = EmailEventPeer::EVN_UID;
        }

    } // setEvnUid()

    /**
     * Set the value of [email_event_from] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEmailEventFrom($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->email_event_from !== $v || $v === '') {
            $this->email_event_from = $v;
            $this->modifiedColumns[] = EmailEventPeer::EMAIL_EVENT_FROM;
        }

    } // setEmailEventFrom()

    /**
     * Set the value of [email_event_to] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEmailEventTo($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->email_event_to !== $v) {
            $this->email_event_to = $v;
            $this->modifiedColumns[] = EmailEventPeer::EMAIL_EVENT_TO;
        }

    } // setEmailEventTo()

    /**
     * Set the value of [email_event_subject] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setEmailEventSubject($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->email_event_subject !== $v || $v === '') {
            $this->email_event_subject = $v;
            $this->modifiedColumns[] = EmailEventPeer::EMAIL_EVENT_SUBJECT;
        }

    } // setEmailEventSubject()

    /**
     * Set the value of [prf_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrfUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prf_uid !== $v || $v === '') {
            $this->prf_uid = $v;
            $this->modifiedColumns[] = EmailEventPeer::PRF_UID;
        }

    } // setPrfUid()

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

            $this->email_event_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->evn_uid = $rs->getString($startcol + 2);

            $this->email_event_from = $rs->getString($startcol + 3);

            $this->email_event_to = $rs->getString($startcol + 4);

            $this->email_event_subject = $rs->getString($startcol + 5);

            $this->prf_uid = $rs->getString($startcol + 6);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 7; // 7 = EmailEventPeer::NUM_COLUMNS - EmailEventPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating EmailEvent object", $e);
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
            $con = Propel::getConnection(EmailEventPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            EmailEventPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(EmailEventPeer::DATABASE_NAME);
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
                    $pk = EmailEventPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += EmailEventPeer::doUpdate($this, $con);
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


            if (($retval = EmailEventPeer::doValidate($this, $columns)) !== true) {
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
        $pos = EmailEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEmailEventUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getEvnUid();
                break;
            case 3:
                return $this->getEmailEventFrom();
                break;
            case 4:
                return $this->getEmailEventTo();
                break;
            case 5:
                return $this->getEmailEventSubject();
                break;
            case 6:
                return $this->getPrfUid();
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
        $keys = EmailEventPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEmailEventUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getEvnUid(),
            $keys[3] => $this->getEmailEventFrom(),
            $keys[4] => $this->getEmailEventTo(),
            $keys[5] => $this->getEmailEventSubject(),
            $keys[6] => $this->getPrfUid(),
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
        $pos = EmailEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setEmailEventUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setEvnUid($value);
                break;
            case 3:
                $this->setEmailEventFrom($value);
                break;
            case 4:
                $this->setEmailEventTo($value);
                break;
            case 5:
                $this->setEmailEventSubject($value);
                break;
            case 6:
                $this->setPrfUid($value);
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
        $keys = EmailEventPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEmailEventUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setEvnUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setEmailEventFrom($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setEmailEventTo($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setEmailEventSubject($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setPrfUid($arr[$keys[6]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EmailEventPeer::DATABASE_NAME);

        if ($this->isColumnModified(EmailEventPeer::EMAIL_EVENT_UID)) {
            $criteria->add(EmailEventPeer::EMAIL_EVENT_UID, $this->email_event_uid);
        }

        if ($this->isColumnModified(EmailEventPeer::PRJ_UID)) {
            $criteria->add(EmailEventPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(EmailEventPeer::EVN_UID)) {
            $criteria->add(EmailEventPeer::EVN_UID, $this->evn_uid);
        }

        if ($this->isColumnModified(EmailEventPeer::EMAIL_EVENT_FROM)) {
            $criteria->add(EmailEventPeer::EMAIL_EVENT_FROM, $this->email_event_from);
        }

        if ($this->isColumnModified(EmailEventPeer::EMAIL_EVENT_TO)) {
            $criteria->add(EmailEventPeer::EMAIL_EVENT_TO, $this->email_event_to);
        }

        if ($this->isColumnModified(EmailEventPeer::EMAIL_EVENT_SUBJECT)) {
            $criteria->add(EmailEventPeer::EMAIL_EVENT_SUBJECT, $this->email_event_subject);
        }

        if ($this->isColumnModified(EmailEventPeer::PRF_UID)) {
            $criteria->add(EmailEventPeer::PRF_UID, $this->prf_uid);
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
        $criteria = new Criteria(EmailEventPeer::DATABASE_NAME);

        $criteria->add(EmailEventPeer::EMAIL_EVENT_UID, $this->email_event_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getEmailEventUid();
    }

    /**
     * Generic method to set the primary key (email_event_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setEmailEventUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of EmailEvent (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setEvnUid($this->evn_uid);

        $copyObj->setEmailEventFrom($this->email_event_from);

        $copyObj->setEmailEventTo($this->email_event_to);

        $copyObj->setEmailEventSubject($this->email_event_subject);

        $copyObj->setPrfUid($this->prf_uid);


        $copyObj->setNew(true);

        $copyObj->setEmailEventUid(NULL); // this is a pkey column, so set to default value

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
     * @return     EmailEvent Clone of current object.
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
     * @return     EmailEventPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EmailEventPeer();
        }
        return self::$peer;
    }
}

