<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/RolesPeer.php';

/**
 * Base class that represents a row from the 'ROLES' table.
 *
 * 
 *
 * @package  rbac-classes-model
 */
abstract class BaseRoles extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        RolesPeer
	 */
	protected static $peer;


	/**
	 * The value for the rol_uid field.
	 * @var        string
	 */
	protected $rol_uid = '';


	/**
	 * The value for the rol_parent field.
	 * @var        string
	 */
	protected $rol_parent = '';


	/**
	 * The value for the rol_system field.
	 * @var        string
	 */
	protected $rol_system = '';


	/**
	 * The value for the rol_code field.
	 * @var        string
	 */
	protected $rol_code = '';


	/**
	 * The value for the rol_create_date field.
	 * @var        int
	 */
	protected $rol_create_date;


	/**
	 * The value for the rol_update_date field.
	 * @var        int
	 */
	protected $rol_update_date;


	/**
	 * The value for the rol_status field.
	 * @var        int
	 */
	protected $rol_status = 1;

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
	 * Get the [rol_uid] column value.
	 * 
	 * @return     string
	 */
	public function getRolUid()
	{

		return $this->rol_uid;
	}

	/**
	 * Get the [rol_parent] column value.
	 * 
	 * @return     string
	 */
	public function getRolParent()
	{

		return $this->rol_parent;
	}

	/**
	 * Get the [rol_system] column value.
	 * 
	 * @return     string
	 */
	public function getRolSystem()
	{

		return $this->rol_system;
	}

	/**
	 * Get the [rol_code] column value.
	 * 
	 * @return     string
	 */
	public function getRolCode()
	{

		return $this->rol_code;
	}

	/**
	 * Get the [optionally formatted] [rol_create_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getRolCreateDate($format = 'Y-m-d H:i:s')
	{

		if ($this->rol_create_date === null || $this->rol_create_date === '') {
			return null;
		} elseif (!is_int($this->rol_create_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->rol_create_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [rol_create_date] as date/time value: " . var_export($this->rol_create_date, true));
			}
		} else {
			$ts = $this->rol_create_date;
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
	 * Get the [optionally formatted] [rol_update_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getRolUpdateDate($format = 'Y-m-d H:i:s')
	{

		if ($this->rol_update_date === null || $this->rol_update_date === '') {
			return null;
		} elseif (!is_int($this->rol_update_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->rol_update_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [rol_update_date] as date/time value: " . var_export($this->rol_update_date, true));
			}
		} else {
			$ts = $this->rol_update_date;
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
	 * Get the [rol_status] column value.
	 * 
	 * @return     int
	 */
	public function getRolStatus()
	{

		return $this->rol_status;
	}

	/**
	 * Set the value of [rol_uid] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRolUid($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->rol_uid !== $v || $v === '') {
			$this->rol_uid = $v;
			$this->modifiedColumns[] = RolesPeer::ROL_UID;
		}

	} // setRolUid()

	/**
	 * Set the value of [rol_parent] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRolParent($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->rol_parent !== $v || $v === '') {
			$this->rol_parent = $v;
			$this->modifiedColumns[] = RolesPeer::ROL_PARENT;
		}

	} // setRolParent()

	/**
	 * Set the value of [rol_system] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRolSystem($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->rol_system !== $v || $v === '') {
			$this->rol_system = $v;
			$this->modifiedColumns[] = RolesPeer::ROL_SYSTEM;
		}

	} // setRolSystem()

	/**
	 * Set the value of [rol_code] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRolCode($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->rol_code !== $v || $v === '') {
			$this->rol_code = $v;
			$this->modifiedColumns[] = RolesPeer::ROL_CODE;
		}

	} // setRolCode()

	/**
	 * Set the value of [rol_create_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setRolCreateDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [rol_create_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->rol_create_date !== $ts) {
			$this->rol_create_date = $ts;
			$this->modifiedColumns[] = RolesPeer::ROL_CREATE_DATE;
		}

	} // setRolCreateDate()

	/**
	 * Set the value of [rol_update_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setRolUpdateDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [rol_update_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->rol_update_date !== $ts) {
			$this->rol_update_date = $ts;
			$this->modifiedColumns[] = RolesPeer::ROL_UPDATE_DATE;
		}

	} // setRolUpdateDate()

	/**
	 * Set the value of [rol_status] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setRolStatus($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->rol_status !== $v || $v === 1) {
			$this->rol_status = $v;
			$this->modifiedColumns[] = RolesPeer::ROL_STATUS;
		}

	} // setRolStatus()

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

			$this->rol_uid = $rs->getString($startcol + 0);

			$this->rol_parent = $rs->getString($startcol + 1);

			$this->rol_system = $rs->getString($startcol + 2);

			$this->rol_code = $rs->getString($startcol + 3);

			$this->rol_create_date = $rs->getTimestamp($startcol + 4, null);

			$this->rol_update_date = $rs->getTimestamp($startcol + 5, null);

			$this->rol_status = $rs->getInt($startcol + 6);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = RolesPeer::NUM_COLUMNS - RolesPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Roles object", $e);
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
			$con = Propel::getConnection(RolesPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			RolesPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(RolesPeer::DATABASE_NAME);
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
					$pk = RolesPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += RolesPeer::doUpdate($this, $con);
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


			if (($retval = RolesPeer::doValidate($this, $columns)) !== true) {
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
		$pos = RolesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getRolUid();
				break;
			case 1:
				return $this->getRolParent();
				break;
			case 2:
				return $this->getRolSystem();
				break;
			case 3:
				return $this->getRolCode();
				break;
			case 4:
				return $this->getRolCreateDate();
				break;
			case 5:
				return $this->getRolUpdateDate();
				break;
			case 6:
				return $this->getRolStatus();
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
		$keys = RolesPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getRolUid(),
			$keys[1] => $this->getRolParent(),
			$keys[2] => $this->getRolSystem(),
			$keys[3] => $this->getRolCode(),
			$keys[4] => $this->getRolCreateDate(),
			$keys[5] => $this->getRolUpdateDate(),
			$keys[6] => $this->getRolStatus(),
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
		$pos = RolesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setRolUid($value);
				break;
			case 1:
				$this->setRolParent($value);
				break;
			case 2:
				$this->setRolSystem($value);
				break;
			case 3:
				$this->setRolCode($value);
				break;
			case 4:
				$this->setRolCreateDate($value);
				break;
			case 5:
				$this->setRolUpdateDate($value);
				break;
			case 6:
				$this->setRolStatus($value);
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
		$keys = RolesPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setRolUid($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setRolParent($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setRolSystem($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setRolCode($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setRolCreateDate($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setRolUpdateDate($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setRolStatus($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RolesPeer::DATABASE_NAME);

		if ($this->isColumnModified(RolesPeer::ROL_UID)) $criteria->add(RolesPeer::ROL_UID, $this->rol_uid);
		if ($this->isColumnModified(RolesPeer::ROL_PARENT)) $criteria->add(RolesPeer::ROL_PARENT, $this->rol_parent);
		if ($this->isColumnModified(RolesPeer::ROL_SYSTEM)) $criteria->add(RolesPeer::ROL_SYSTEM, $this->rol_system);
		if ($this->isColumnModified(RolesPeer::ROL_CODE)) $criteria->add(RolesPeer::ROL_CODE, $this->rol_code);
		if ($this->isColumnModified(RolesPeer::ROL_CREATE_DATE)) $criteria->add(RolesPeer::ROL_CREATE_DATE, $this->rol_create_date);
		if ($this->isColumnModified(RolesPeer::ROL_UPDATE_DATE)) $criteria->add(RolesPeer::ROL_UPDATE_DATE, $this->rol_update_date);
		if ($this->isColumnModified(RolesPeer::ROL_STATUS)) $criteria->add(RolesPeer::ROL_STATUS, $this->rol_status);

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
		$criteria = new Criteria(RolesPeer::DATABASE_NAME);

		$criteria->add(RolesPeer::ROL_UID, $this->rol_uid);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getRolUid();
	}

	/**
	 * Generic method to set the primary key (rol_uid column).
	 *
	 * @param      string $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setRolUid($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Roles (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRolParent($this->rol_parent);

		$copyObj->setRolSystem($this->rol_system);

		$copyObj->setRolCode($this->rol_code);

		$copyObj->setRolCreateDate($this->rol_create_date);

		$copyObj->setRolUpdateDate($this->rol_update_date);

		$copyObj->setRolStatus($this->rol_status);


		$copyObj->setNew(true);

		$copyObj->setRolUid(''); // this is a pkey column, so set to default value

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
	 * @return     Roles Clone of current object.
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
	 * @return     RolesPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RolesPeer();
		}
		return self::$peer;
	}

} // BaseRoles
