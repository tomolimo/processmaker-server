<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/PmoauthUserAccessTokensPeer.php';

/**
 * Base class that represents a row from the 'PMOAUTH_USER_ACCESS_TOKENS' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BasePmoauthUserAccessTokens extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PmoauthUserAccessTokensPeer
    */
    protected static $peer;

    /**
     * The value for the access_token field.
     * @var        string
     */
    protected $access_token;

    /**
     * The value for the refresh_token field.
     * @var        string
     */
    protected $refresh_token;

    /**
     * The value for the user_id field.
     * @var        string
     */
    protected $user_id;

    /**
     * The value for the session_id field.
     * @var        string
     */
    protected $session_id;

    /**
     * The value for the session_name field.
     * @var        string
     */
    protected $session_name;

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
     * Get the [access_token] column value.
     * 
     * @return     string
     */
    public function getAccessToken()
    {

        return $this->access_token;
    }

    /**
     * Get the [refresh_token] column value.
     * 
     * @return     string
     */
    public function getRefreshToken()
    {

        return $this->refresh_token;
    }

    /**
     * Get the [user_id] column value.
     * 
     * @return     string
     */
    public function getUserId()
    {

        return $this->user_id;
    }

    /**
     * Get the [session_id] column value.
     * 
     * @return     string
     */
    public function getSessionId()
    {

        return $this->session_id;
    }

    /**
     * Get the [session_name] column value.
     * 
     * @return     string
     */
    public function getSessionName()
    {

        return $this->session_name;
    }

    /**
     * Set the value of [access_token] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAccessToken($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->access_token !== $v) {
            $this->access_token = $v;
            $this->modifiedColumns[] = PmoauthUserAccessTokensPeer::ACCESS_TOKEN;
        }

    } // setAccessToken()

    /**
     * Set the value of [refresh_token] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setRefreshToken($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->refresh_token !== $v) {
            $this->refresh_token = $v;
            $this->modifiedColumns[] = PmoauthUserAccessTokensPeer::REFRESH_TOKEN;
        }

    } // setRefreshToken()

    /**
     * Set the value of [user_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setUserId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = PmoauthUserAccessTokensPeer::USER_ID;
        }

    } // setUserId()

    /**
     * Set the value of [session_id] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setSessionId($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->session_id !== $v) {
            $this->session_id = $v;
            $this->modifiedColumns[] = PmoauthUserAccessTokensPeer::SESSION_ID;
        }

    } // setSessionId()

    /**
     * Set the value of [session_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setSessionName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->session_name !== $v) {
            $this->session_name = $v;
            $this->modifiedColumns[] = PmoauthUserAccessTokensPeer::SESSION_NAME;
        }

    } // setSessionName()

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

            $this->access_token = $rs->getString($startcol + 0);

            $this->refresh_token = $rs->getString($startcol + 1);

            $this->user_id = $rs->getString($startcol + 2);

            $this->session_id = $rs->getString($startcol + 3);

            $this->session_name = $rs->getString($startcol + 4);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 5; // 5 = PmoauthUserAccessTokensPeer::NUM_COLUMNS - PmoauthUserAccessTokensPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating PmoauthUserAccessTokens object", $e);
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
            $con = Propel::getConnection(PmoauthUserAccessTokensPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            PmoauthUserAccessTokensPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(PmoauthUserAccessTokensPeer::DATABASE_NAME);
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
                    $pk = PmoauthUserAccessTokensPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += PmoauthUserAccessTokensPeer::doUpdate($this, $con);
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


            if (($retval = PmoauthUserAccessTokensPeer::doValidate($this, $columns)) !== true) {
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
        $pos = PmoauthUserAccessTokensPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAccessToken();
                break;
            case 1:
                return $this->getRefreshToken();
                break;
            case 2:
                return $this->getUserId();
                break;
            case 3:
                return $this->getSessionId();
                break;
            case 4:
                return $this->getSessionName();
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
        $keys = PmoauthUserAccessTokensPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAccessToken(),
            $keys[1] => $this->getRefreshToken(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getSessionId(),
            $keys[4] => $this->getSessionName(),
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
        $pos = PmoauthUserAccessTokensPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setAccessToken($value);
                break;
            case 1:
                $this->setRefreshToken($value);
                break;
            case 2:
                $this->setUserId($value);
                break;
            case 3:
                $this->setSessionId($value);
                break;
            case 4:
                $this->setSessionName($value);
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
        $keys = PmoauthUserAccessTokensPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAccessToken($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setRefreshToken($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setSessionId($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setSessionName($arr[$keys[4]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PmoauthUserAccessTokensPeer::DATABASE_NAME);

        if ($this->isColumnModified(PmoauthUserAccessTokensPeer::ACCESS_TOKEN)) {
            $criteria->add(PmoauthUserAccessTokensPeer::ACCESS_TOKEN, $this->access_token);
        }

        if ($this->isColumnModified(PmoauthUserAccessTokensPeer::REFRESH_TOKEN)) {
            $criteria->add(PmoauthUserAccessTokensPeer::REFRESH_TOKEN, $this->refresh_token);
        }

        if ($this->isColumnModified(PmoauthUserAccessTokensPeer::USER_ID)) {
            $criteria->add(PmoauthUserAccessTokensPeer::USER_ID, $this->user_id);
        }

        if ($this->isColumnModified(PmoauthUserAccessTokensPeer::SESSION_ID)) {
            $criteria->add(PmoauthUserAccessTokensPeer::SESSION_ID, $this->session_id);
        }

        if ($this->isColumnModified(PmoauthUserAccessTokensPeer::SESSION_NAME)) {
            $criteria->add(PmoauthUserAccessTokensPeer::SESSION_NAME, $this->session_name);
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
        $criteria = new Criteria(PmoauthUserAccessTokensPeer::DATABASE_NAME);

        $criteria->add(PmoauthUserAccessTokensPeer::ACCESS_TOKEN, $this->access_token);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getAccessToken();
    }

    /**
     * Generic method to set the primary key (access_token column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setAccessToken($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of PmoauthUserAccessTokens (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setRefreshToken($this->refresh_token);

        $copyObj->setUserId($this->user_id);

        $copyObj->setSessionId($this->session_id);

        $copyObj->setSessionName($this->session_name);


        $copyObj->setNew(true);

        $copyObj->setAccessToken(NULL); // this is a pkey column, so set to default value

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
     * @return     PmoauthUserAccessTokens Clone of current object.
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
     * @return     PmoauthUserAccessTokensPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PmoauthUserAccessTokensPeer();
        }
        return self::$peer;
    }
}

