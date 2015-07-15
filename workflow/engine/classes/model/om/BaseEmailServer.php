<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/EmailServerPeer.php';

/**
 * Base class that represents a row from the 'EMAIL_SERVER' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseEmailServer extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EmailServerPeer
    */
    protected static $peer;

    /**
     * The value for the mess_uid field.
     * @var        string
     */
    protected $mess_uid = '';

    /**
     * The value for the mess_engine field.
     * @var        string
     */
    protected $mess_engine = '';

    /**
     * The value for the mess_server field.
     * @var        string
     */
    protected $mess_server = '';

    /**
     * The value for the mess_port field.
     * @var        int
     */
    protected $mess_port = 0;

    /**
     * The value for the mess_rauth field.
     * @var        int
     */
    protected $mess_rauth = 0;

    /**
     * The value for the mess_account field.
     * @var        string
     */
    protected $mess_account = '';

    /**
     * The value for the mess_password field.
     * @var        string
     */
    protected $mess_password = '';

    /**
     * The value for the mess_from_mail field.
     * @var        string
     */
    protected $mess_from_mail = '';

    /**
     * The value for the mess_from_name field.
     * @var        string
     */
    protected $mess_from_name = '';

    /**
     * The value for the smtpsecure field.
     * @var        string
     */
    protected $smtpsecure = 'No';

    /**
     * The value for the mess_try_send_inmediatly field.
     * @var        int
     */
    protected $mess_try_send_inmediatly = 0;

    /**
     * The value for the mail_to field.
     * @var        string
     */
    protected $mail_to = '';

    /**
     * The value for the mess_default field.
     * @var        int
     */
    protected $mess_default = 0;

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
     * Get the [mess_uid] column value.
     * 
     * @return     string
     */
    public function getMessUid()
    {

        return $this->mess_uid;
    }

    /**
     * Get the [mess_engine] column value.
     * 
     * @return     string
     */
    public function getMessEngine()
    {

        return $this->mess_engine;
    }

    /**
     * Get the [mess_server] column value.
     * 
     * @return     string
     */
    public function getMessServer()
    {

        return $this->mess_server;
    }

    /**
     * Get the [mess_port] column value.
     * 
     * @return     int
     */
    public function getMessPort()
    {

        return $this->mess_port;
    }

    /**
     * Get the [mess_rauth] column value.
     * 
     * @return     int
     */
    public function getMessRauth()
    {

        return $this->mess_rauth;
    }

    /**
     * Get the [mess_account] column value.
     * 
     * @return     string
     */
    public function getMessAccount()
    {

        return $this->mess_account;
    }

    /**
     * Get the [mess_password] column value.
     * 
     * @return     string
     */
    public function getMessPassword()
    {

        return $this->mess_password;
    }

    /**
     * Get the [mess_from_mail] column value.
     * 
     * @return     string
     */
    public function getMessFromMail()
    {

        return $this->mess_from_mail;
    }

    /**
     * Get the [mess_from_name] column value.
     * 
     * @return     string
     */
    public function getMessFromName()
    {

        return $this->mess_from_name;
    }

    /**
     * Get the [smtpsecure] column value.
     * 
     * @return     string
     */
    public function getSmtpsecure()
    {

        return $this->smtpsecure;
    }

    /**
     * Get the [mess_try_send_inmediatly] column value.
     * 
     * @return     int
     */
    public function getMessTrySendInmediatly()
    {

        return $this->mess_try_send_inmediatly;
    }

    /**
     * Get the [mail_to] column value.
     * 
     * @return     string
     */
    public function getMailTo()
    {

        return $this->mail_to;
    }

    /**
     * Get the [mess_default] column value.
     * 
     * @return     int
     */
    public function getMessDefault()
    {

        return $this->mess_default;
    }

    /**
     * Set the value of [mess_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_uid !== $v || $v === '') {
            $this->mess_uid = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_UID;
        }

    } // setMessUid()

    /**
     * Set the value of [mess_engine] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessEngine($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_engine !== $v || $v === '') {
            $this->mess_engine = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_ENGINE;
        }

    } // setMessEngine()

    /**
     * Set the value of [mess_server] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessServer($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_server !== $v || $v === '') {
            $this->mess_server = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_SERVER;
        }

    } // setMessServer()

    /**
     * Set the value of [mess_port] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMessPort($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mess_port !== $v || $v === 0) {
            $this->mess_port = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_PORT;
        }

    } // setMessPort()

    /**
     * Set the value of [mess_rauth] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMessRauth($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mess_rauth !== $v || $v === 0) {
            $this->mess_rauth = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_RAUTH;
        }

    } // setMessRauth()

    /**
     * Set the value of [mess_account] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessAccount($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_account !== $v || $v === '') {
            $this->mess_account = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_ACCOUNT;
        }

    } // setMessAccount()

    /**
     * Set the value of [mess_password] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessPassword($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_password !== $v || $v === '') {
            $this->mess_password = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_PASSWORD;
        }

    } // setMessPassword()

    /**
     * Set the value of [mess_from_mail] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessFromMail($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_from_mail !== $v || $v === '') {
            $this->mess_from_mail = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_FROM_MAIL;
        }

    } // setMessFromMail()

    /**
     * Set the value of [mess_from_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMessFromName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mess_from_name !== $v || $v === '') {
            $this->mess_from_name = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_FROM_NAME;
        }

    } // setMessFromName()

    /**
     * Set the value of [smtpsecure] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setSmtpsecure($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->smtpsecure !== $v || $v === 'No') {
            $this->smtpsecure = $v;
            $this->modifiedColumns[] = EmailServerPeer::SMTPSECURE;
        }

    } // setSmtpsecure()

    /**
     * Set the value of [mess_try_send_inmediatly] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMessTrySendInmediatly($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mess_try_send_inmediatly !== $v || $v === 0) {
            $this->mess_try_send_inmediatly = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_TRY_SEND_INMEDIATLY;
        }

    } // setMessTrySendInmediatly()

    /**
     * Set the value of [mail_to] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setMailTo($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->mail_to !== $v || $v === '') {
            $this->mail_to = $v;
            $this->modifiedColumns[] = EmailServerPeer::MAIL_TO;
        }

    } // setMailTo()

    /**
     * Set the value of [mess_default] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setMessDefault($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->mess_default !== $v || $v === 0) {
            $this->mess_default = $v;
            $this->modifiedColumns[] = EmailServerPeer::MESS_DEFAULT;
        }

    } // setMessDefault()

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

            $this->mess_uid = $rs->getString($startcol + 0);

            $this->mess_engine = $rs->getString($startcol + 1);

            $this->mess_server = $rs->getString($startcol + 2);

            $this->mess_port = $rs->getInt($startcol + 3);

            $this->mess_rauth = $rs->getInt($startcol + 4);

            $this->mess_account = $rs->getString($startcol + 5);

            $this->mess_password = $rs->getString($startcol + 6);

            $this->mess_from_mail = $rs->getString($startcol + 7);

            $this->mess_from_name = $rs->getString($startcol + 8);

            $this->smtpsecure = $rs->getString($startcol + 9);

            $this->mess_try_send_inmediatly = $rs->getInt($startcol + 10);

            $this->mail_to = $rs->getString($startcol + 11);

            $this->mess_default = $rs->getInt($startcol + 12);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 13; // 13 = EmailServerPeer::NUM_COLUMNS - EmailServerPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating EmailServer object", $e);
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
            $con = Propel::getConnection(EmailServerPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            EmailServerPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(EmailServerPeer::DATABASE_NAME);
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
                    $pk = EmailServerPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += EmailServerPeer::doUpdate($this, $con);
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


            if (($retval = EmailServerPeer::doValidate($this, $columns)) !== true) {
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
        $pos = EmailServerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getMessUid();
                break;
            case 1:
                return $this->getMessEngine();
                break;
            case 2:
                return $this->getMessServer();
                break;
            case 3:
                return $this->getMessPort();
                break;
            case 4:
                return $this->getMessRauth();
                break;
            case 5:
                return $this->getMessAccount();
                break;
            case 6:
                return $this->getMessPassword();
                break;
            case 7:
                return $this->getMessFromMail();
                break;
            case 8:
                return $this->getMessFromName();
                break;
            case 9:
                return $this->getSmtpsecure();
                break;
            case 10:
                return $this->getMessTrySendInmediatly();
                break;
            case 11:
                return $this->getMailTo();
                break;
            case 12:
                return $this->getMessDefault();
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
        $keys = EmailServerPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMessUid(),
            $keys[1] => $this->getMessEngine(),
            $keys[2] => $this->getMessServer(),
            $keys[3] => $this->getMessPort(),
            $keys[4] => $this->getMessRauth(),
            $keys[5] => $this->getMessAccount(),
            $keys[6] => $this->getMessPassword(),
            $keys[7] => $this->getMessFromMail(),
            $keys[8] => $this->getMessFromName(),
            $keys[9] => $this->getSmtpsecure(),
            $keys[10] => $this->getMessTrySendInmediatly(),
            $keys[11] => $this->getMailTo(),
            $keys[12] => $this->getMessDefault(),
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
        $pos = EmailServerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setMessUid($value);
                break;
            case 1:
                $this->setMessEngine($value);
                break;
            case 2:
                $this->setMessServer($value);
                break;
            case 3:
                $this->setMessPort($value);
                break;
            case 4:
                $this->setMessRauth($value);
                break;
            case 5:
                $this->setMessAccount($value);
                break;
            case 6:
                $this->setMessPassword($value);
                break;
            case 7:
                $this->setMessFromMail($value);
                break;
            case 8:
                $this->setMessFromName($value);
                break;
            case 9:
                $this->setSmtpsecure($value);
                break;
            case 10:
                $this->setMessTrySendInmediatly($value);
                break;
            case 11:
                $this->setMailTo($value);
                break;
            case 12:
                $this->setMessDefault($value);
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
        $keys = EmailServerPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMessUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setMessEngine($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setMessServer($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setMessPort($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setMessRauth($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setMessAccount($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setMessPassword($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setMessFromMail($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setMessFromName($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setSmtpsecure($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setMessTrySendInmediatly($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setMailTo($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setMessDefault($arr[$keys[12]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EmailServerPeer::DATABASE_NAME);

        if ($this->isColumnModified(EmailServerPeer::MESS_UID)) {
            $criteria->add(EmailServerPeer::MESS_UID, $this->mess_uid);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_ENGINE)) {
            $criteria->add(EmailServerPeer::MESS_ENGINE, $this->mess_engine);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_SERVER)) {
            $criteria->add(EmailServerPeer::MESS_SERVER, $this->mess_server);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_PORT)) {
            $criteria->add(EmailServerPeer::MESS_PORT, $this->mess_port);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_RAUTH)) {
            $criteria->add(EmailServerPeer::MESS_RAUTH, $this->mess_rauth);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_ACCOUNT)) {
            $criteria->add(EmailServerPeer::MESS_ACCOUNT, $this->mess_account);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_PASSWORD)) {
            $criteria->add(EmailServerPeer::MESS_PASSWORD, $this->mess_password);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_FROM_MAIL)) {
            $criteria->add(EmailServerPeer::MESS_FROM_MAIL, $this->mess_from_mail);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_FROM_NAME)) {
            $criteria->add(EmailServerPeer::MESS_FROM_NAME, $this->mess_from_name);
        }

        if ($this->isColumnModified(EmailServerPeer::SMTPSECURE)) {
            $criteria->add(EmailServerPeer::SMTPSECURE, $this->smtpsecure);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_TRY_SEND_INMEDIATLY)) {
            $criteria->add(EmailServerPeer::MESS_TRY_SEND_INMEDIATLY, $this->mess_try_send_inmediatly);
        }

        if ($this->isColumnModified(EmailServerPeer::MAIL_TO)) {
            $criteria->add(EmailServerPeer::MAIL_TO, $this->mail_to);
        }

        if ($this->isColumnModified(EmailServerPeer::MESS_DEFAULT)) {
            $criteria->add(EmailServerPeer::MESS_DEFAULT, $this->mess_default);
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
        $criteria = new Criteria(EmailServerPeer::DATABASE_NAME);

        $criteria->add(EmailServerPeer::MESS_UID, $this->mess_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getMessUid();
    }

    /**
     * Generic method to set the primary key (mess_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setMessUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of EmailServer (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setMessEngine($this->mess_engine);

        $copyObj->setMessServer($this->mess_server);

        $copyObj->setMessPort($this->mess_port);

        $copyObj->setMessRauth($this->mess_rauth);

        $copyObj->setMessAccount($this->mess_account);

        $copyObj->setMessPassword($this->mess_password);

        $copyObj->setMessFromMail($this->mess_from_mail);

        $copyObj->setMessFromName($this->mess_from_name);

        $copyObj->setSmtpsecure($this->smtpsecure);

        $copyObj->setMessTrySendInmediatly($this->mess_try_send_inmediatly);

        $copyObj->setMailTo($this->mail_to);

        $copyObj->setMessDefault($this->mess_default);


        $copyObj->setNew(true);

        $copyObj->setMessUid(''); // this is a pkey column, so set to default value

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
     * @return     EmailServer Clone of current object.
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
     * @return     EmailServerPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EmailServerPeer();
        }
        return self::$peer;
    }
}

