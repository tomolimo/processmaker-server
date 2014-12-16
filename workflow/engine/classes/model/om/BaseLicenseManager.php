<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';

include_once 'propel/util/Criteria.php';

include_once 'classes/model/LicenseManagerPeer.php';

/**
 * Base class that represents a row from the 'LICENSE_MANAGER' table.
 *
 *
 * @package classes.model.om
 */
abstract class BaseLicenseManager extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * 
     * @var LicenseManagerPeer
     */
    protected static $peer;

    /**
     * The value for the license_uid field.
     * 
     * @var string
     */
    protected $license_uid = '';

    /**
     * The value for the license_user field.
     * 
     * @var string
     */
    protected $license_user = '0';

    /**
     * The value for the license_start field.
     * 
     * @var int
     */
    protected $license_start = 0;

    /**
     * The value for the license_end field.
     * 
     * @var int
     */
    protected $license_end = 0;

    /**
     * The value for the license_span field.
     * 
     * @var int
     */
    protected $license_span = 0;

    /**
     * The value for the license_status field.
     * 
     * @var string
     */
    protected $license_status = '';

    /**
     * The value for the license_data field.
     * 
     * @var string
     */
    protected $license_data;

    /**
     * The value for the license_path field.
     * 
     * @var string
     */
    protected $license_path = '0';

    /**
     * The value for the license_workspace field.
     * 
     * @var string
     */
    protected $license_workspace = '0';

    /**
     * The value for the license_type field.
     * 
     * @var string
     */
    protected $license_type = '0';

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * 
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * 
     * @var boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Get the [license_uid] column value.
     *
     * @return string
     */
    public function getLicenseUid ()
    {
        return $this->license_uid;
    }

    /**
     * Get the [license_user] column value.
     *
     * @return string
     */
    public function getLicenseUser ()
    {
        return $this->license_user;
    }

    /**
     * Get the [license_start] column value.
     *
     * @return int
     */
    public function getLicenseStart ()
    {
        return $this->license_start;
    }

    /**
     * Get the [license_end] column value.
     *
     * @return int
     */
    public function getLicenseEnd ()
    {
        return $this->license_end;
    }

    /**
     * Get the [license_span] column value.
     *
     * @return int
     */
    public function getLicenseSpan ()
    {
        return $this->license_span;
    }

    /**
     * Get the [license_status] column value.
     *
     * @return string
     */
    public function getLicenseStatus ()
    {
        return $this->license_status;
    }

    /**
     * Get the [license_data] column value.
     *
     * @return string
     */
    public function getLicenseData ()
    {
        return $this->license_data;
    }

    /**
     * Get the [license_path] column value.
     *
     * @return string
     */
    public function getLicensePath ()
    {
        return $this->license_path;
    }

    /**
     * Get the [license_workspace] column value.
     *
     * @return string
     */
    public function getLicenseWorkspace ()
    {
        return $this->license_workspace;
    }

    /**
     * Get the [license_type] column value.
     *
     * @return string
     */
    public function getLicenseType ()
    {
        return $this->license_type;
    }

    /**
     * Set the value of [license_uid] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseUid ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_uid !== $v || $v === '') {
            $this->license_uid = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_UID;
        }
    } // setLicenseUid()

    /**
     * Set the value of [license_user] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseUser ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_user !== $v || $v === '0') {
            $this->license_user = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_USER;
        }
    } // setLicenseUser()

    /**
     * Set the value of [license_start] column.
     *
     * @param int $v new value
     * @return void
     */
    public function setLicenseStart ($v)
    {
        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && ! is_int( $v ) && is_numeric( $v )) {
            $v = (int) $v;
        }
        if ($this->license_start !== $v || $v === 0) {
            $this->license_start = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_START;
        }
    } // setLicenseStart()

    /**
     * Set the value of [license_end] column.
     *
     * @param int $v new value
     * @return void
     */
    public function setLicenseEnd ($v)
    {
        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && ! is_int( $v ) && is_numeric( $v )) {
            $v = (int) $v;
        }

        if ($this->license_end !== $v || $v === 0) {
            $this->license_end = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_END;
        }

    } // setLicenseEnd()

    /**
     * Set the value of [license_span] column.
     *
     * @param int $v new value
     * @return void
     */
    public function setLicenseSpan ($v)
    {
        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && ! is_int( $v ) && is_numeric( $v )) {
            $v = (int) $v;
        }

        if ($this->license_span !== $v || $v === 0) {
            $this->license_span = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_SPAN;
        }
    } // setLicenseSpan()

    /**
     * Set the value of [license_status] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseStatus ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_status !== $v || $v === '') {
            $this->license_status = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_STATUS;
        }
    } // setLicenseStatus()

    /**
     * Set the value of [license_data] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseData ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_data !== $v) {
            $this->license_data = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_DATA;
        }

    } // setLicenseData()

    /**
     * Set the value of [license_path] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicensePath ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }
        if ($this->license_path !== $v || $v === '0') {
            $this->license_path = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_PATH;
        }
    } // setLicensePath()

    /**
     * Set the value of [license_workspace] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseWorkspace ($v)
    {
        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_workspace !== $v || $v === '0') {
            $this->license_workspace = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_WORKSPACE;
        }
    } // setLicenseWorkspace()

    /**
     * Set the value of [license_type] column.
     *
     * @param string $v new value
     * @return void
     */
    public function setLicenseType ($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && ! is_string( $v )) {
            $v = (string) $v;
        }

        if ($this->license_type !== $v || $v === '0') {
            $this->license_type = $v;
            $this->modifiedColumns[] = LicenseManagerPeer::LICENSE_TYPE;
        }
    } // setLicenseType()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (1-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows. This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
     * @param int $startcol 1-based offset column which indicates which restultset column to start with.
     * @return int next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate (ResultSet $rs, $startcol = 1)
    {
        try {

            $this->license_uid = $rs->getString( $startcol + 0 );

            $this->license_user = $rs->getString( $startcol + 1 );

            $this->license_start = $rs->getInt( $startcol + 2 );

            $this->license_end = $rs->getInt( $startcol + 3 );

            $this->license_span = $rs->getInt( $startcol + 4 );

            $this->license_status = $rs->getString( $startcol + 5 );

            $this->license_data = $rs->getString( $startcol + 6 );

            $this->license_path = $rs->getString( $startcol + 7 );

            $this->license_workspace = $rs->getString( $startcol + 8 );

            $this->license_type = $rs->getString( $startcol + 9 );

            $this->resetModified();

            $this->setNew( false );

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 10; // 10 = LicenseManagerPeer::NUM_COLUMNS - LicenseManagerPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException( "Error populating LicenseManager object", $e );
        }
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param Connection $con
     * @return void
     * @throws PropelException
     * @see BaseObject::setDeleted()
     * @see BaseObject::isDeleted()
     */
    public function delete ($con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException( "This object has already been deleted." );
        }

        if ($con === null) {
            $con = Propel::getConnection( LicenseManagerPeer::DATABASE_NAME );
        }

        try {
            $con->begin();
            LicenseManagerPeer::doDelete( $this, $con );
            $this->setDeleted( true );
            $con->commit();
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Stores the object in the database.
     * If the object is new,
     * it inserts it; otherwise an update is performed. This method
     * wraps the doSave() worker method in a transaction.
     *
     * @param Connection $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save ($con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException( "You cannot save an object that has been deleted." );
        }

        if ($con === null) {
            $con = Propel::getConnection( LicenseManagerPeer::DATABASE_NAME );
        }

        try {
            $con->begin();
            $affectedRows = $this->doSave( $con );
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
     * @param Connection $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave ($con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (! $this->alreadyInSave) {
            $this->alreadyInSave = true;

            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = LicenseManagerPeer::doInsert( $this, $con );
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                    // should always be true here (even though technically
                    // BasePeer::doInsert() can insert multiple rows).
                    $this->setNew( false );
                } else {
                    $affectedRows += LicenseManagerPeer::doUpdate( $this, $con );
                }
                $this->resetModified(); // [HL] After being saved an object is no longer 'modified'
            }
            $this->alreadyInSave = false;
        }
        return $affectedRows;
    } // doSave()

    /**
     * Array of ValidationFailed objects.
     * 
     * @var array ValidationFailed[]
     */
    protected $validationFailures = array ();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see validate()
     */
    public function getValidationFailures ()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see doValidate()
     * @see getValidationFailures()
     */
    public function validate ($columns = null)
    {
        $res = $this->doValidate( $columns );
        if ($res === true) {
            $this->validationFailures = array ();
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
     * also be validated. If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate ($columns = null)
    {
        if (! $this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array ();

            if (($retval = LicenseManagerPeer::doValidate( $this, $columns )) !== true) {
                $failureMap = array_merge( $failureMap, $retval );
            }

            $this->alreadyInValidation = false;
        }

        return (! empty( $failureMap ) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     * one of the class type constants TYPE_PHPNAME,
     * TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return mixed Value of field.
     */
    public function getByName ($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = LicenseManagerPeer::translateFieldName( $name, $type, BasePeer::TYPE_NUM );
        return $this->getByPosition( $pos );
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition ($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getLicenseUid();
                break;
            case 1:
                return $this->getLicenseUser();
                break;
            case 2:
                return $this->getLicenseStart();
                break;
            case 3:
                return $this->getLicenseEnd();
                break;
            case 4:
                return $this->getLicenseSpan();
                break;
            case 5:
                return $this->getLicenseStatus();
                break;
            case 6:
                return $this->getLicenseData();
                break;
            case 7:
                return $this->getLicensePath();
                break;
            case 8:
                return $this->getLicenseWorkspace();
                break;
            case 9:
                return $this->getLicenseType();
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType One of the class type constants TYPE_PHPNAME,
     * TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return an associative array containing the field names (as keys) and field values
     */
    public function toArray ($keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = LicenseManagerPeer::getFieldNames( $keyType );
        $result = array (
            $keys[0] => $this->getLicenseUid(),
            $keys[1] => $this->getLicenseUser(),
            $keys[2] => $this->getLicenseStart(),
            $keys[3] => $this->getLicenseEnd(),
            $keys[4] => $this->getLicenseSpan(),
            $keys[5] => $this->getLicenseStatus(),
            $keys[6] => $this->getLicenseData(),
            $keys[7] => $this->getLicensePath(),
            $keys[8] => $this->getLicenseWorkspace(),
            $keys[9] => $this->getLicenseType()
        );
        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     * one of the class type constants TYPE_PHPNAME,
     * TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
     * @return void
     */
    public function setByName ($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = LicenseManagerPeer::translateFieldName( $name, $type, BasePeer::TYPE_NUM );
        return $this->setByPosition( $pos, $value );
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition ($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setLicenseUid( $value );
                break;
            case 1:
                $this->setLicenseUser( $value );
                break;
            case 2:
                $this->setLicenseStart( $value );
                break;
            case 3:
                $this->setLicenseEnd( $value );
                break;
            case 4:
                $this->setLicenseSpan( $value );
                break;
            case 5:
                $this->setLicenseStatus( $value );
                break;
            case 6:
                $this->setLicenseData( $value );
                break;
            case 7:
                $this->setLicensePath( $value );
                break;
            case 8:
                $this->setLicenseWorkspace( $value );
                break;
            case 9:
                $this->setLicenseType( $value );
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST). This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
     * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray ($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = LicenseManagerPeer::getFieldNames( $keyType );

        if (array_key_exists( $keys[0], $arr )) {
            $this->setLicenseUid( $arr[$keys[0]] );
        }
        if (array_key_exists( $keys[1], $arr )) {
            $this->setLicenseUser( $arr[$keys[1]] );
        }
        if (array_key_exists( $keys[2], $arr )) {
            $this->setLicenseStart( $arr[$keys[2]] );
        }
        if (array_key_exists( $keys[3], $arr )) {
            $this->setLicenseEnd( $arr[$keys[3]] );
        }
        if (array_key_exists( $keys[4], $arr )) {
            $this->setLicenseSpan( $arr[$keys[4]] );
        }
        if (array_key_exists( $keys[5], $arr )) {
            $this->setLicenseStatus( $arr[$keys[5]] );
        }
        if (array_key_exists( $keys[6], $arr )) {
            $this->setLicenseData( $arr[$keys[6]] );
        }
        if (array_key_exists( $keys[7], $arr )) {
            $this->setLicensePath( $arr[$keys[7]] );
        }
        if (array_key_exists( $keys[8], $arr )) {
            $this->setLicenseWorkspace( $arr[$keys[8]] );
        }
        if (array_key_exists( $keys[9], $arr )) {
            $this->setLicenseType( $arr[$keys[9]] );
        }
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria ()
    {
        $criteria = new Criteria( LicenseManagerPeer::DATABASE_NAME );

        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_UID )) {
            $criteria->add( LicenseManagerPeer::LICENSE_UID, $this->license_uid );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_USER )) {
            $criteria->add( LicenseManagerPeer::LICENSE_USER, $this->license_user );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_START )) {
            $criteria->add( LicenseManagerPeer::LICENSE_START, $this->license_start );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_END )) {
            $criteria->add( LicenseManagerPeer::LICENSE_END, $this->license_end );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_SPAN )) {
            $criteria->add( LicenseManagerPeer::LICENSE_SPAN, $this->license_span );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_STATUS )) {
            $criteria->add( LicenseManagerPeer::LICENSE_STATUS, $this->license_status );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_DATA )) {
            $criteria->add( LicenseManagerPeer::LICENSE_DATA, $this->license_data );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_PATH )) {
            $criteria->add( LicenseManagerPeer::LICENSE_PATH, $this->license_path );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_WORKSPACE )) {
            $criteria->add( LicenseManagerPeer::LICENSE_WORKSPACE, $this->license_workspace );
        }
        if ($this->isColumnModified( LicenseManagerPeer::LICENSE_TYPE )) {
            $criteria->add( LicenseManagerPeer::LICENSE_TYPE, $this->license_type );
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria ()
    {
        $criteria = new Criteria( LicenseManagerPeer::DATABASE_NAME );

        $criteria->add( LicenseManagerPeer::LICENSE_UID, $this->license_uid );

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * 
     * @return string
     */
    public function getPrimaryKey ()
    {
        return $this->getLicenseUid();
    }

    /**
     * Generic method to set the primary key (license_uid column).
     *
     * @param string $key Primary key.
     * @return void
     */
    public function setPrimaryKey ($key)
    {
        $this->setLicenseUid( $key );
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of LicenseManager (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws PropelException
     */
    public function copyInto ($copyObj, $deepCopy = false)
    {

        $copyObj->setLicenseUser( $this->license_user );

        $copyObj->setLicenseStart( $this->license_start );

        $copyObj->setLicenseEnd( $this->license_end );

        $copyObj->setLicenseSpan( $this->license_span );

        $copyObj->setLicenseStatus( $this->license_status );

        $copyObj->setLicenseData( $this->license_data );

        $copyObj->setLicensePath( $this->license_path );

        $copyObj->setLicenseWorkspace( $this->license_workspace );

        $copyObj->setLicenseType( $this->license_type );

        $copyObj->setNew( true );

        $copyObj->setLicenseUid( '' ); // this is a pkey column, so set to default value

    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return LicenseManager Clone of current object.
     * @throws PropelException
     */
    public function copy ($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class( $this );
        $copyObj = new $clazz();
        $this->copyInto( $copyObj, $deepCopy );
        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return LicenseManagerPeer
     */
    public function getPeer ()
    {
        if (self::$peer === null) {
            self::$peer = new LicenseManagerPeer();
        }
        return self::$peer;
    }
}

