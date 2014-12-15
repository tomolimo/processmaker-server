<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/OauthAuthorizationCodesPeer.php';

/**
 * Base class that represents a row from the 'OAUTH_AUTHORIZATION_CODES' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseOauthAuthorizationCodes extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        OauthAuthorizationCodesPeer
    */
    protected static $peer;

    /**
     * The value for the authorization_code field.
     * @var        string
     */
    protected $authorization_code;

    /**
     * The value for the client_id field.
     * @var        string
     */
    protected $client_id;

    /**
     * The value for the user_id field.
     * @var        string
     */
    protected $user_id;

    /**
     * The value for the redirect_uri field.
     * @var        string
     */
    protected $redirect_uri;

    /**
     * The value for the expires field.
     * @var        int
     */
    protected $expires;

    /**
     * The value for the scope field.
     * @var        string
     */
    protected $scope;

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
     * Get the [authorization_code] column value.
     * 
     * @return     string
     */
    public function getAuthorizationCode()
    {

        return $this->authorization_code;
    }

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
     * Get the [user_id] column value.
     * 
     * @return     string
     */
    public function getUserId()
    {

        return $this->user_id;
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
     * Get the [optionally formatted] [expires] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getExpires($format = 'Y-m-d H:i:s')
    {

        if ($this->expires === null || $this->expires === '') {
            return null;
        } elseif (!is_int($this->expires)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->expires);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [expires] as date/time value: " .
                    var_export($this->expires, true));
            }
        } else {
            $ts = $this->expires;
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
     * Get the [scope] column value.
     * 
     * @return     string
     */
    public function getScope()
    {

        return $this->scope;
    }

    /**
     * Set the value of [authorization_code] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setAuthorizationCode($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->authorization_code !== $v) {
            $this->authorization_code = $v;
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::AUTHORIZATION_CODE;
        }

    } // setAuthorizationCode()

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
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::CLIENT_ID;
        }

    } // setClientId()

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
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::USER_ID;
        }

    } // setUserId()

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
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::REDIRECT_URI;
        }

    } // setRedirectUri()

    /**
     * Set the value of [expires] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setExpires($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [expires] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->expires !== $ts) {
            $this->expires = $ts;
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::EXPIRES;
        }

    } // setExpires()

    /**
     * Set the value of [scope] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setScope($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->scope !== $v) {
            $this->scope = $v;
            $this->modifiedColumns[] = OauthAuthorizationCodesPeer::SCOPE;
        }

    } // setScope()

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

            $this->authorization_code = $rs->getString($startcol + 0);

            $this->client_id = $rs->getString($startcol + 1);

            $this->user_id = $rs->getString($startcol + 2);

            $this->redirect_uri = $rs->getString($startcol + 3);

            $this->expires = $rs->getTimestamp($startcol + 4, null);

            $this->scope = $rs->getString($startcol + 5);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 6; // 6 = OauthAuthorizationCodesPeer::NUM_COLUMNS - OauthAuthorizationCodesPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating OauthAuthorizationCodes object", $e);
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
            $con = Propel::getConnection(OauthAuthorizationCodesPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            OauthAuthorizationCodesPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(OauthAuthorizationCodesPeer::DATABASE_NAME);
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
                    $pk = OauthAuthorizationCodesPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += OauthAuthorizationCodesPeer::doUpdate($this, $con);
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


            if (($retval = OauthAuthorizationCodesPeer::doValidate($this, $columns)) !== true) {
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
        $pos = OauthAuthorizationCodesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAuthorizationCode();
                break;
            case 1:
                return $this->getClientId();
                break;
            case 2:
                return $this->getUserId();
                break;
            case 3:
                return $this->getRedirectUri();
                break;
            case 4:
                return $this->getExpires();
                break;
            case 5:
                return $this->getScope();
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
        $keys = OauthAuthorizationCodesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAuthorizationCode(),
            $keys[1] => $this->getClientId(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getRedirectUri(),
            $keys[4] => $this->getExpires(),
            $keys[5] => $this->getScope(),
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
        $pos = OauthAuthorizationCodesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setAuthorizationCode($value);
                break;
            case 1:
                $this->setClientId($value);
                break;
            case 2:
                $this->setUserId($value);
                break;
            case 3:
                $this->setRedirectUri($value);
                break;
            case 4:
                $this->setExpires($value);
                break;
            case 5:
                $this->setScope($value);
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
        $keys = OauthAuthorizationCodesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAuthorizationCode($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setClientId($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setRedirectUri($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setExpires($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setScope($arr[$keys[5]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(OauthAuthorizationCodesPeer::DATABASE_NAME);

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::AUTHORIZATION_CODE)) {
            $criteria->add(OauthAuthorizationCodesPeer::AUTHORIZATION_CODE, $this->authorization_code);
        }

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::CLIENT_ID)) {
            $criteria->add(OauthAuthorizationCodesPeer::CLIENT_ID, $this->client_id);
        }

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::USER_ID)) {
            $criteria->add(OauthAuthorizationCodesPeer::USER_ID, $this->user_id);
        }

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::REDIRECT_URI)) {
            $criteria->add(OauthAuthorizationCodesPeer::REDIRECT_URI, $this->redirect_uri);
        }

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::EXPIRES)) {
            $criteria->add(OauthAuthorizationCodesPeer::EXPIRES, $this->expires);
        }

        if ($this->isColumnModified(OauthAuthorizationCodesPeer::SCOPE)) {
            $criteria->add(OauthAuthorizationCodesPeer::SCOPE, $this->scope);
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
        $criteria = new Criteria(OauthAuthorizationCodesPeer::DATABASE_NAME);

        $criteria->add(OauthAuthorizationCodesPeer::AUTHORIZATION_CODE, $this->authorization_code);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getAuthorizationCode();
    }

    /**
     * Generic method to set the primary key (authorization_code column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setAuthorizationCode($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of OauthAuthorizationCodes (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setClientId($this->client_id);

        $copyObj->setUserId($this->user_id);

        $copyObj->setRedirectUri($this->redirect_uri);

        $copyObj->setExpires($this->expires);

        $copyObj->setScope($this->scope);


        $copyObj->setNew(true);

        $copyObj->setAuthorizationCode(NULL); // this is a pkey column, so set to default value

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
     * @return     OauthAuthorizationCodes Clone of current object.
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
     * @return     OauthAuthorizationCodesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new OauthAuthorizationCodesPeer();
        }
        return self::$peer;
    }
}

