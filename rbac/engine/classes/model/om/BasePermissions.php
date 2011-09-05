<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/PermissionsPeer.php';

/**
 * Base class that represents a row from the 'PERMISSIONS' table.
 *
 * 
 *
 * @package  rbac-classes-model
 */
abstract class BasePermissions extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PermissionsPeer
	 */
	protected static $peer;


	/**
	 * The value for the per_uid field.
	 * @var        string
	 */
	protected $per_uid = '';


	/**
	 * The value for the per_code field.
	 * @var        string
	 */
	protected $per_code = '';


	/**
	 * The value for the per_create_date field.
	 * @var        int
	 */
	protected $per_create_date;


	/**
	 * The value for the per_update_date field.
	 * @var        int
	 */
	protected $per_update_date;


	/**
	 * The value for the per_status field.
	 * @var        int
	 */
	protected $per_status = 1;


	/**
	 * The value for the per_system field.
	 * @var        string
	 */
	protected $per_system = '00000000000000000000000000000002';

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
	 * Get the [per_uid] column value.
	 * 
	 * @return     string
	 */
	public function getPerUid()
	{

		return $this->per_uid;
	}

	/**
	 * Get the [per_code] column value.
	 * 
	 * @return     string
	 */
	public function getPerCode()
	{

		return $this->per_code;
	}

	/**
	 * Get the [optionally formatted] [per_create_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getPerCreateDate($format = 'Y-m-d H:i:s')
	{

		if ($this->per_create_date === null || $this->per_create_date === '') {
			return null;
		} elseif (!is_int($this->per_create_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->per_create_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [per_create_date] as date/time value: " . var_export($this->per_create_date, true));
			}
		} else {
			$ts = $this->per_create_date;
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
	 * Get the [optionally formatted] [per_update_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getPerUpdateDate($format = 'Y-m-d H:i:s')
	{

		if ($this->per_update_date === null || $this->per_update_date === '') {
			return null;
		} elseif (!is_int($this->per_update_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->per_update_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [per_update_date] as date/time value: " . var_export($this->per_update_date, true));
			}
		} else {
			$ts = $this->per_update_date;
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
	 * Get the [per_status] column value.
	 * 
	 * @return     int
	 */
	public function getPerStatus()
	{

		return $this->per_status;
	}

	/**
	 * Get the [per_system] column value.
	 * 
	 * @return     string
	 */
	public function getPerSystem()
	{

		return $this->per_system;
	}

	/**
	 * Set the value of [per_uid] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPerUid($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->per_uid !== $v || $v === '') {
			$this->per_uid = $v;
			$this->modifiedColumns[] = PermissionsPeer::PER_UID;
		}

	} // setPerUid()

	/**
	 * Set the value of [per_code] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPerCode($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->per_code !== $v || $v === '') {
			$this->per_code = $v;
			$this->modifiedColumns[] = PermissionsPeer::PER_CODE;
		}

	} // setPerCode()

	/**
	 * Set the value of [per_create_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setPerCreateDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [per_create_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->per_create_date !== $ts) {
			$this->per_create_date = $ts;
			$this->modifiedColumns[] = PermissionsPeer::PER_CREATE_DATE;
		}

	} // setPerCreateDate()

	/**
	 * Set the value of [per_update_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setPerUpdateDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [per_update_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->per_update_date !== $ts) {
			$this->per_update_date = $ts;
			$this->modifiedColumns[] = PermissionsPeer::PER_UPDATE_DATE;
		}

	} // setPerUpdateDate()

	/**
	 * Set the value of [per_status] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setPerStatus($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->per_status !== $v || $v === 1) {
			$this->per_status = $v;
			$this->modifiedColumns[] = PermissionsPeer::PER_STATUS;
		}

	} // setPerStatus()

	/**
	 * Set the value of [per_system] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPerSystem($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->per_system !== $v || $v === '00000000000000000000000000000002') {
			$this->per_system = $v;
			$this->modifiedColumns[] = PermissionsPeer::PER_SYSTEM;
		}

	} // setPerSystem()

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

			$this->per_uid = $rs->getString($startcol + 0);

			$this->per_code = $rs->getString($startcol + 1);

			$this->per_create_date = $rs->getTimestamp($startcol + 2, null);

			$this->per_update_date = $rs->getTimestamp($startcol + 3, null);

			$this->per_status = $rs->getInt($startcol + 4);

			$this->per_system = $rs->getString($startcol + 5);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 6; // 6 = PermissionsPeer::NUM_COLUMNS - PermissionsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Permissions object", $e);
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
			$con = Propel::getConnection(PermissionsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			PermissionsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(PermissionsPeer::DATABASE_NAME);
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
					$pk = PermissionsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += PermissionsPeer::doUpdate($this, $con);
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


			if (($retval = PermissionsPeer::doValidate($this, $columns)) !== true) {
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
		$pos = PermissionsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPerUid();
				break;
			case 1:
				return $this->getPerCode();
				break;
			case 2:
				return $this->getPerCreateDate();
				break;
			case 3:
				return $this->getPerUpdateDate();
				break;
			case 4:
				return $this->getPerStatus();
				break;
			case 5:
				return $this->getPerSystem();
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
		$keys = PermissionsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPerUid(),
			$keys[1] => $this->getPerCode(),
			$keys[2] => $this->getPerCreateDate(),
			$keys[3] => $this->getPerUpdateDate(),
			$keys[4] => $this->getPerStatus(),
			$keys[5] => $this->getPerSystem(),
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
		$pos = PermissionsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPerUid($value);
				break;
			case 1:
				$this->setPerCode($value);
				break;
			case 2:
				$this->setPerCreateDate($value);
				break;
			case 3:
				$this->setPerUpdateDate($value);
				break;
			case 4:
				$this->setPerStatus($value);
				break;
			case 5:
				$this->setPerSystem($value);
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
		$keys = PermissionsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPerUid($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPerCode($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPerCreateDate($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPerUpdateDate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPerStatus($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPerSystem($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PermissionsPeer::DATABASE_NAME);

		if ($this->isColumnModified(PermissionsPeer::PER_UID)) $criteria->add(PermissionsPeer::PER_UID, $this->per_uid);
		if ($this->isColumnModified(PermissionsPeer::PER_CODE)) $criteria->add(PermissionsPeer::PER_CODE, $this->per_code);
		if ($this->isColumnModified(PermissionsPeer::PER_CREATE_DATE)) $criteria->add(PermissionsPeer::PER_CREATE_DATE, $this->per_create_date);
		if ($this->isColumnModified(PermissionsPeer::PER_UPDATE_DATE)) $criteria->add(PermissionsPeer::PER_UPDATE_DATE, $this->per_update_date);
		if ($this->isColumnModified(PermissionsPeer::PER_STATUS)) $criteria->add(PermissionsPeer::PER_STATUS, $this->per_status);
		if ($this->isColumnModified(PermissionsPeer::PER_SYSTEM)) $criteria->add(PermissionsPeer::PER_SYSTEM, $this->per_system);

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
		$criteria = new Criteria(PermissionsPeer::DATABASE_NAME);

		$criteria->add(PermissionsPeer::PER_UID, $this->per_uid);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getPerUid();
	}

	/**
	 * Generic method to set the primary key (per_uid column).
	 *
	 * @param      string $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPerUid($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Permissions (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPerCode($this->per_code);

		$copyObj->setPerCreateDate($this->per_create_date);

		$copyObj->setPerUpdateDate($this->per_update_date);

		$copyObj->setPerStatus($this->per_status);

		$copyObj->setPerSystem($this->per_system);


		$copyObj->setNew(true);

		$copyObj->setPerUid(''); // this is a pkey column, so set to default value

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
	 * @return     Permissions Clone of current object.
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
	 * @return     PermissionsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PermissionsPeer();
		}
		return self::$peer;
	}

} // BasePermissions
