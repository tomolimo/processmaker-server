<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/AuthenticationSourcePeer.php';

/**
 * Base class that represents a row from the 'AUTHENTICATION_SOURCE' table.
 *
 * 
 *
 * @package  rbac-classes-model
 */
abstract class BaseAuthenticationSource extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        AuthenticationSourcePeer
	 */
	protected static $peer;


	/**
	 * The value for the auth_source_uid field.
	 * @var        string
	 */
	protected $auth_source_uid = '';


	/**
	 * The value for the auth_source_name field.
	 * @var        string
	 */
	protected $auth_source_name = '';


	/**
	 * The value for the auth_source_provider field.
	 * @var        string
	 */
	protected $auth_source_provider = '';


	/**
	 * The value for the auth_source_server_name field.
	 * @var        string
	 */
	protected $auth_source_server_name = '';


	/**
	 * The value for the auth_source_port field.
	 * @var        int
	 */
	protected $auth_source_port = 389;


	/**
	 * The value for the auth_source_enabled_tls field.
	 * @var        int
	 */
	protected $auth_source_enabled_tls = 0;


	/**
	 * The value for the auth_source_version field.
	 * @var        string
	 */
	protected $auth_source_version = '3';


	/**
	 * The value for the auth_source_base_dn field.
	 * @var        string
	 */
	protected $auth_source_base_dn = '';


	/**
	 * The value for the auth_anonymous field.
	 * @var        int
	 */
	protected $auth_anonymous = 0;


	/**
	 * The value for the auth_source_search_user field.
	 * @var        string
	 */
	protected $auth_source_search_user = '';


	/**
	 * The value for the auth_source_password field.
	 * @var        string
	 */
	protected $auth_source_password = '';


	/**
	 * The value for the auth_source_attributes field.
	 * @var        string
	 */
	protected $auth_source_attributes = '';


	/**
	 * The value for the auth_source_object_classes field.
	 * @var        string
	 */
	protected $auth_source_object_classes = '';


	/**
	 * The value for the auth_source_data field.
	 * @var        string
	 */
	protected $auth_source_data;

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
	 * Get the [auth_source_uid] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceUid()
	{

		return $this->auth_source_uid;
	}

	/**
	 * Get the [auth_source_name] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceName()
	{

		return $this->auth_source_name;
	}

	/**
	 * Get the [auth_source_provider] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceProvider()
	{

		return $this->auth_source_provider;
	}

	/**
	 * Get the [auth_source_server_name] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceServerName()
	{

		return $this->auth_source_server_name;
	}

	/**
	 * Get the [auth_source_port] column value.
	 * 
	 * @return     int
	 */
	public function getAuthSourcePort()
	{

		return $this->auth_source_port;
	}

	/**
	 * Get the [auth_source_enabled_tls] column value.
	 * 
	 * @return     int
	 */
	public function getAuthSourceEnabledTls()
	{

		return $this->auth_source_enabled_tls;
	}

	/**
	 * Get the [auth_source_version] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceVersion()
	{

		return $this->auth_source_version;
	}

	/**
	 * Get the [auth_source_base_dn] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceBaseDn()
	{

		return $this->auth_source_base_dn;
	}

	/**
	 * Get the [auth_anonymous] column value.
	 * 
	 * @return     int
	 */
	public function getAuthAnonymous()
	{

		return $this->auth_anonymous;
	}

	/**
	 * Get the [auth_source_search_user] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceSearchUser()
	{

		return $this->auth_source_search_user;
	}

	/**
	 * Get the [auth_source_password] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourcePassword()
	{

		return $this->auth_source_password;
	}

	/**
	 * Get the [auth_source_attributes] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceAttributes()
	{

		return $this->auth_source_attributes;
	}

	/**
	 * Get the [auth_source_object_classes] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceObjectClasses()
	{

		return $this->auth_source_object_classes;
	}

	/**
	 * Get the [auth_source_data] column value.
	 * 
	 * @return     string
	 */
	public function getAuthSourceData()
	{

		return $this->auth_source_data;
	}

	/**
	 * Set the value of [auth_source_uid] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceUid($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_uid !== $v || $v === '') {
			$this->auth_source_uid = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_UID;
		}

	} // setAuthSourceUid()

	/**
	 * Set the value of [auth_source_name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_name !== $v || $v === '') {
			$this->auth_source_name = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_NAME;
		}

	} // setAuthSourceName()

	/**
	 * Set the value of [auth_source_provider] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceProvider($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_provider !== $v || $v === '') {
			$this->auth_source_provider = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER;
		}

	} // setAuthSourceProvider()

	/**
	 * Set the value of [auth_source_server_name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceServerName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_server_name !== $v || $v === '') {
			$this->auth_source_server_name = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME;
		}

	} // setAuthSourceServerName()

	/**
	 * Set the value of [auth_source_port] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAuthSourcePort($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->auth_source_port !== $v || $v === 389) {
			$this->auth_source_port = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_PORT;
		}

	} // setAuthSourcePort()

	/**
	 * Set the value of [auth_source_enabled_tls] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAuthSourceEnabledTls($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->auth_source_enabled_tls !== $v || $v === 0) {
			$this->auth_source_enabled_tls = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS;
		}

	} // setAuthSourceEnabledTls()

	/**
	 * Set the value of [auth_source_version] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceVersion($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_version !== $v || $v === '3') {
			$this->auth_source_version = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_VERSION;
		}

	} // setAuthSourceVersion()

	/**
	 * Set the value of [auth_source_base_dn] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceBaseDn($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_base_dn !== $v || $v === '') {
			$this->auth_source_base_dn = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN;
		}

	} // setAuthSourceBaseDn()

	/**
	 * Set the value of [auth_anonymous] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAuthAnonymous($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->auth_anonymous !== $v || $v === 0) {
			$this->auth_anonymous = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_ANONYMOUS;
		}

	} // setAuthAnonymous()

	/**
	 * Set the value of [auth_source_search_user] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceSearchUser($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_search_user !== $v || $v === '') {
			$this->auth_source_search_user = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER;
		}

	} // setAuthSourceSearchUser()

	/**
	 * Set the value of [auth_source_password] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourcePassword($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_password !== $v || $v === '') {
			$this->auth_source_password = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD;
		}

	} // setAuthSourcePassword()

	/**
	 * Set the value of [auth_source_attributes] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceAttributes($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_attributes !== $v || $v === '') {
			$this->auth_source_attributes = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES;
		}

	} // setAuthSourceAttributes()

	/**
	 * Set the value of [auth_source_object_classes] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceObjectClasses($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_object_classes !== $v || $v === '') {
			$this->auth_source_object_classes = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES;
		}

	} // setAuthSourceObjectClasses()

	/**
	 * Set the value of [auth_source_data] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthSourceData($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->auth_source_data !== $v) {
			$this->auth_source_data = $v;
			$this->modifiedColumns[] = AuthenticationSourcePeer::AUTH_SOURCE_DATA;
		}

	} // setAuthSourceData()

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

			$this->auth_source_uid = $rs->getString($startcol + 0);

			$this->auth_source_name = $rs->getString($startcol + 1);

			$this->auth_source_provider = $rs->getString($startcol + 2);

			$this->auth_source_server_name = $rs->getString($startcol + 3);

			$this->auth_source_port = $rs->getInt($startcol + 4);

			$this->auth_source_enabled_tls = $rs->getInt($startcol + 5);

			$this->auth_source_version = $rs->getString($startcol + 6);

			$this->auth_source_base_dn = $rs->getString($startcol + 7);

			$this->auth_anonymous = $rs->getInt($startcol + 8);

			$this->auth_source_search_user = $rs->getString($startcol + 9);

			$this->auth_source_password = $rs->getString($startcol + 10);

			$this->auth_source_attributes = $rs->getString($startcol + 11);

			$this->auth_source_object_classes = $rs->getString($startcol + 12);

			$this->auth_source_data = $rs->getString($startcol + 13);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 14; // 14 = AuthenticationSourcePeer::NUM_COLUMNS - AuthenticationSourcePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating AuthenticationSource object", $e);
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
			$con = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			AuthenticationSourcePeer::doDelete($this, $con);
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
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
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
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
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
					$pk = AuthenticationSourcePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += AuthenticationSourcePeer::doUpdate($this, $con);
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
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = AuthenticationSourcePeer::doValidate($this, $columns)) !== true) {
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
		$pos = AuthenticationSourcePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getAuthSourceUid();
				break;
			case 1:
				return $this->getAuthSourceName();
				break;
			case 2:
				return $this->getAuthSourceProvider();
				break;
			case 3:
				return $this->getAuthSourceServerName();
				break;
			case 4:
				return $this->getAuthSourcePort();
				break;
			case 5:
				return $this->getAuthSourceEnabledTls();
				break;
			case 6:
				return $this->getAuthSourceVersion();
				break;
			case 7:
				return $this->getAuthSourceBaseDn();
				break;
			case 8:
				return $this->getAuthAnonymous();
				break;
			case 9:
				return $this->getAuthSourceSearchUser();
				break;
			case 10:
				return $this->getAuthSourcePassword();
				break;
			case 11:
				return $this->getAuthSourceAttributes();
				break;
			case 12:
				return $this->getAuthSourceObjectClasses();
				break;
			case 13:
				return $this->getAuthSourceData();
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
		$keys = AuthenticationSourcePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getAuthSourceUid(),
			$keys[1] => $this->getAuthSourceName(),
			$keys[2] => $this->getAuthSourceProvider(),
			$keys[3] => $this->getAuthSourceServerName(),
			$keys[4] => $this->getAuthSourcePort(),
			$keys[5] => $this->getAuthSourceEnabledTls(),
			$keys[6] => $this->getAuthSourceVersion(),
			$keys[7] => $this->getAuthSourceBaseDn(),
			$keys[8] => $this->getAuthAnonymous(),
			$keys[9] => $this->getAuthSourceSearchUser(),
			$keys[10] => $this->getAuthSourcePassword(),
			$keys[11] => $this->getAuthSourceAttributes(),
			$keys[12] => $this->getAuthSourceObjectClasses(),
			$keys[13] => $this->getAuthSourceData(),
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
		$pos = AuthenticationSourcePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setAuthSourceUid($value);
				break;
			case 1:
				$this->setAuthSourceName($value);
				break;
			case 2:
				$this->setAuthSourceProvider($value);
				break;
			case 3:
				$this->setAuthSourceServerName($value);
				break;
			case 4:
				$this->setAuthSourcePort($value);
				break;
			case 5:
				$this->setAuthSourceEnabledTls($value);
				break;
			case 6:
				$this->setAuthSourceVersion($value);
				break;
			case 7:
				$this->setAuthSourceBaseDn($value);
				break;
			case 8:
				$this->setAuthAnonymous($value);
				break;
			case 9:
				$this->setAuthSourceSearchUser($value);
				break;
			case 10:
				$this->setAuthSourcePassword($value);
				break;
			case 11:
				$this->setAuthSourceAttributes($value);
				break;
			case 12:
				$this->setAuthSourceObjectClasses($value);
				break;
			case 13:
				$this->setAuthSourceData($value);
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
		$keys = AuthenticationSourcePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setAuthSourceUid($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setAuthSourceName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setAuthSourceProvider($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setAuthSourceServerName($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAuthSourcePort($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setAuthSourceEnabledTls($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAuthSourceVersion($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setAuthSourceBaseDn($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setAuthAnonymous($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setAuthSourceSearchUser($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setAuthSourcePassword($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setAuthSourceAttributes($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setAuthSourceObjectClasses($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setAuthSourceData($arr[$keys[13]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(AuthenticationSourcePeer::DATABASE_NAME);

		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_UID)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, $this->auth_source_uid);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_NAME)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_NAME, $this->auth_source_name);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER, $this->auth_source_provider);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_SERVER_NAME, $this->auth_source_server_name);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_PORT)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_PORT, $this->auth_source_port);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_ENABLED_TLS, $this->auth_source_enabled_tls);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_VERSION)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_VERSION, $this->auth_source_version);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_BASE_DN, $this->auth_source_base_dn);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_ANONYMOUS)) $criteria->add(AuthenticationSourcePeer::AUTH_ANONYMOUS, $this->auth_anonymous);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_SEARCH_USER, $this->auth_source_search_user);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_PASSWORD, $this->auth_source_password);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_ATTRIBUTES, $this->auth_source_attributes);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_OBJECT_CLASSES, $this->auth_source_object_classes);
		if ($this->isColumnModified(AuthenticationSourcePeer::AUTH_SOURCE_DATA)) $criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_DATA, $this->auth_source_data);

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
		$criteria = new Criteria(AuthenticationSourcePeer::DATABASE_NAME);

		$criteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, $this->auth_source_uid);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getAuthSourceUid();
	}

	/**
	 * Generic method to set the primary key (auth_source_uid column).
	 *
	 * @param      string $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setAuthSourceUid($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of AuthenticationSource (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setAuthSourceName($this->auth_source_name);

		$copyObj->setAuthSourceProvider($this->auth_source_provider);

		$copyObj->setAuthSourceServerName($this->auth_source_server_name);

		$copyObj->setAuthSourcePort($this->auth_source_port);

		$copyObj->setAuthSourceEnabledTls($this->auth_source_enabled_tls);

		$copyObj->setAuthSourceVersion($this->auth_source_version);

		$copyObj->setAuthSourceBaseDn($this->auth_source_base_dn);

		$copyObj->setAuthAnonymous($this->auth_anonymous);

		$copyObj->setAuthSourceSearchUser($this->auth_source_search_user);

		$copyObj->setAuthSourcePassword($this->auth_source_password);

		$copyObj->setAuthSourceAttributes($this->auth_source_attributes);

		$copyObj->setAuthSourceObjectClasses($this->auth_source_object_classes);

		$copyObj->setAuthSourceData($this->auth_source_data);


		$copyObj->setNew(true);

		$copyObj->setAuthSourceUid(''); // this is a pkey column, so set to default value

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
	 * @return     AuthenticationSource Clone of current object.
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
	 * @return     AuthenticationSourcePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new AuthenticationSourcePeer();
		}
		return self::$peer;
	}

} // BaseAuthenticationSource
