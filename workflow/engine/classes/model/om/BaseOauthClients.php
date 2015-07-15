<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/OauthClientsPeer.php';

/**
 * Base class that represents a row from the 'OAUTH_CLIENTS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseOauthClients extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        OauthClientsPeer
    */
    protected static $peer;

    /**
     * The value for the client_id field.
     * @var        string
     */
    protected $client_id;

    /**
     * The value for the client_secret field.
     * @var        string
     */
    protected $client_secret;

    /**
     * The value for the client_name field.
     * @var        string
     */
    protected $client_name;

    /**
     * The value for the client_description field.
     * @var        string
     */
    protected $client_description;

    /**
     * The value for the client_website field.
     * @var        string
     */
    protected $client_website;

    /**
     * The value for the redirect_uri field.
     * @var        string
     */
    protected $redirect_uri;

    /**
     * The value for the usr_uid field.
     * @var        string
     */
    protected $usr_uid;

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
     * Get the [client_id] column value.
     * 
     * @return     string
     */
    public function getClientId()
    {

        return $this->client_id;
    }

    /**
     * Get the [client_secret] column value.
     * 
     * @return     string
     */
    public function getClientSecret()
    {

        return $this->client_secret;
    }

    /**
     * Get the [client_name] column value.
     * 
     * @return     string
     */
    public function getClientName()
    {

        return $this->client_name;
    }

    /**
     * Get the [client_description] column value.
     * 
     * @return     string
     */
    public function getClientDescription()
    {

        return $this->client_description;
    }

    /**
     * Get the [client_website] column value.
     * 
     * @return     string
     */
    public function getClientWebsite()
    {

        return $this->client_website;
    }

    /**
     * Get the [redirect_uri] column value.
     * 
     * @return     string
     */
    public function getRedirectUri()
    {

        return $this->redirect_uri;
    }

    /**
     * Get the [usr_uid] column value.
     * 
     * @return     string
     */
    public function getUsrUid()
    {

        return $this->usr_uid;
    }

    /**
     * Set the value of [client_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setClientId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->client_id !== $v) {
            $this->client_id = $v;
            $this->modifiedColumns[] = OauthClientsPeer::CLIENT_ID;
        }

    } // setClientId()

    /**
     * Set the value of [client_secret] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setClientSecret($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->client_secret !== $v) {
            $this->client_secret = $v;
            $this->modifiedColumns[] = OauthClientsPeer::CLIENT_SECRET;
        }

    } // setClientSecret()

    /**
     * Set the value of [client_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setClientName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->client_name !== $v) {
            $this->client_name = $v;
            $this->modifiedColumns[] = OauthClientsPeer::CLIENT_NAME;
        }

    } // setClientName()

    /**
     * Set the value of [client_description] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setClientDescription($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->client_description !== $v) {
            $this->client_description = $v;
            $this->modifiedColumns[] = OauthClientsPeer::CLIENT_DESCRIPTION;
        }

    } // setClientDescription()

    /**
     * Set the value of [client_website] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setClientWebsite($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->client_website !== $v) {
            $this->client_website = $v;
            $this->modifiedColumns[] = OauthClientsPeer::CLIENT_WEBSITE;
        }

    } // setClientWebsite()

    /**
     * Set the value of [redirect_uri] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setRedirectUri($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->redirect_uri !== $v) {
            $this->redirect_uri = $v;
            $this->modifiedColumns[] = OauthClientsPeer::REDIRECT_URI;
        }

    } // setRedirectUri()

    /**
     * Set the value of [usr_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUsrUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->usr_uid !== $v) {
            $this->usr_uid = $v;
            $this->modifiedColumns[] = OauthClientsPeer::USR_UID;
        }

    } // setUsrUid()

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

            $this->client_id = $rs->getString($startcol + 0);

            $this->client_secret = $rs->getString($startcol + 1);

            $this->client_name = $rs->getString($startcol + 2);

            $this->client_description = $rs->getString($startcol + 3);

            $this->client_website = $rs->getString($startcol + 4);

            $this->redirect_uri = $rs->getString($startcol + 5);

            $this->usr_uid = $rs->getString($startcol + 6);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 7; // 7 = OauthClientsPeer::NUM_COLUMNS - OauthClientsPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating OauthClients object", $e);
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
            $con = Propel::getConnection(OauthClientsPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            OauthClientsPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(OauthClientsPeer::DATABASE_NAME);
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
                    $pk = OauthClientsPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += OauthClientsPeer::doUpdate($this, $con);
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


            if (($retval = OauthClientsPeer::doValidate($this, $columns)) !== true) {
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
        $pos = OauthClientsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getClientId();
                break;
            case 1:
                return $this->getClientSecret();
                break;
            case 2:
                return $this->getClientName();
                break;
            case 3:
                return $this->getClientDescription();
                break;
            case 4:
                return $this->getClientWebsite();
                break;
            case 5:
                return $this->getRedirectUri();
                break;
            case 6:
                return $this->getUsrUid();
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
        $keys = OauthClientsPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getClientId(),
            $keys[1] => $this->getClientSecret(),
            $keys[2] => $this->getClientName(),
            $keys[3] => $this->getClientDescription(),
            $keys[4] => $this->getClientWebsite(),
            $keys[5] => $this->getRedirectUri(),
            $keys[6] => $this->getUsrUid(),
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
        $pos = OauthClientsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setClientId($value);
                break;
            case 1:
                $this->setClientSecret($value);
                break;
            case 2:
                $this->setClientName($value);
                break;
            case 3:
                $this->setClientDescription($value);
                break;
            case 4:
                $this->setClientWebsite($value);
                break;
            case 5:
                $this->setRedirectUri($value);
                break;
            case 6:
                $this->setUsrUid($value);
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
        $keys = OauthClientsPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setClientId($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setClientSecret($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setClientName($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setClientDescription($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setClientWebsite($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setRedirectUri($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setUsrUid($arr[$keys[6]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(OauthClientsPeer::DATABASE_NAME);

        if ($this->isColumnModified(OauthClientsPeer::CLIENT_ID)) {
            $criteria->add(OauthClientsPeer::CLIENT_ID, $this->client_id);
        }

        if ($this->isColumnModified(OauthClientsPeer::CLIENT_SECRET)) {
            $criteria->add(OauthClientsPeer::CLIENT_SECRET, $this->client_secret);
        }

        if ($this->isColumnModified(OauthClientsPeer::CLIENT_NAME)) {
            $criteria->add(OauthClientsPeer::CLIENT_NAME, $this->client_name);
        }

        if ($this->isColumnModified(OauthClientsPeer::CLIENT_DESCRIPTION)) {
            $criteria->add(OauthClientsPeer::CLIENT_DESCRIPTION, $this->client_description);
        }

        if ($this->isColumnModified(OauthClientsPeer::CLIENT_WEBSITE)) {
            $criteria->add(OauthClientsPeer::CLIENT_WEBSITE, $this->client_website);
        }

        if ($this->isColumnModified(OauthClientsPeer::REDIRECT_URI)) {
            $criteria->add(OauthClientsPeer::REDIRECT_URI, $this->redirect_uri);
        }

        if ($this->isColumnModified(OauthClientsPeer::USR_UID)) {
            $criteria->add(OauthClientsPeer::USR_UID, $this->usr_uid);
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
        $criteria = new Criteria(OauthClientsPeer::DATABASE_NAME);

        $criteria->add(OauthClientsPeer::CLIENT_ID, $this->client_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getClientId();
    }

    /**
     * Generic method to set the primary key (client_id column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setClientId($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of OauthClients (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setClientSecret($this->client_secret);

        $copyObj->setClientName($this->client_name);

        $copyObj->setClientDescription($this->client_description);

        $copyObj->setClientWebsite($this->client_website);

        $copyObj->setRedirectUri($this->redirect_uri);

        $copyObj->setUsrUid($this->usr_uid);


        $copyObj->setNew(true);

        $copyObj->setClientId(NULL); // this is a pkey column, so set to default value

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
     * @return     OauthClients Clone of current object.
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
     * @return     OauthClientsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new OauthClientsPeer();
        }
        return self::$peer;
    }
}

