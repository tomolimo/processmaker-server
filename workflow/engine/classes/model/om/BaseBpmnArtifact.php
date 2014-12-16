<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnArtifactPeer.php';

/**
 * Base class that represents a row from the 'BPMN_ARTIFACT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnArtifact extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnArtifactPeer
    */
    protected static $peer;

    /**
     * The value for the art_uid field.
     * @var        string
     */
    protected $art_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the pro_uid field.
     * @var        string
     */
    protected $pro_uid = '';

    /**
     * The value for the art_type field.
     * @var        string
     */
    protected $art_type;

    /**
     * The value for the art_name field.
     * @var        string
     */
    protected $art_name;

    /**
     * The value for the art_category_ref field.
     * @var        string
     */
    protected $art_category_ref;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * @var        BpmnProcess
     */
    protected $aBpmnProcess;

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
     * Get the [art_uid] column value.
     * 
     * @return     string
     */
    public function getArtUid()
    {

        return $this->art_uid;
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
     * Get the [pro_uid] column value.
     * 
     * @return     string
     */
    public function getProUid()
    {

        return $this->pro_uid;
    }

    /**
     * Get the [art_type] column value.
     * 
     * @return     string
     */
    public function getArtType()
    {

        return $this->art_type;
    }

    /**
     * Get the [art_name] column value.
     * 
     * @return     string
     */
    public function getArtName()
    {

        return $this->art_name;
    }

    /**
     * Get the [art_category_ref] column value.
     * 
     * @return     string
     */
    public function getArtCategoryRef()
    {

        return $this->art_category_ref;
    }

    /**
     * Set the value of [art_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setArtUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->art_uid !== $v || $v === '') {
            $this->art_uid = $v;
            $this->modifiedColumns[] = BpmnArtifactPeer::ART_UID;
        }

    } // setArtUid()

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
            $this->modifiedColumns[] = BpmnArtifactPeer::PRJ_UID;
        }

        if ($this->aBpmnProject !== null && $this->aBpmnProject->getPrjUid() !== $v) {
            $this->aBpmnProject = null;
        }

    } // setPrjUid()

    /**
     * Set the value of [pro_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setProUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->pro_uid !== $v || $v === '') {
            $this->pro_uid = $v;
            $this->modifiedColumns[] = BpmnArtifactPeer::PRO_UID;
        }

        if ($this->aBpmnProcess !== null && $this->aBpmnProcess->getProUid() !== $v) {
            $this->aBpmnProcess = null;
        }

    } // setProUid()

    /**
     * Set the value of [art_type] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setArtType($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->art_type !== $v) {
            $this->art_type = $v;
            $this->modifiedColumns[] = BpmnArtifactPeer::ART_TYPE;
        }

    } // setArtType()

    /**
     * Set the value of [art_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setArtName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->art_name !== $v) {
            $this->art_name = $v;
            $this->modifiedColumns[] = BpmnArtifactPeer::ART_NAME;
        }

    } // setArtName()

    /**
     * Set the value of [art_category_ref] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setArtCategoryRef($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->art_category_ref !== $v) {
            $this->art_category_ref = $v;
            $this->modifiedColumns[] = BpmnArtifactPeer::ART_CATEGORY_REF;
        }

    } // setArtCategoryRef()

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

            $this->art_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->pro_uid = $rs->getString($startcol + 2);

            $this->art_type = $rs->getString($startcol + 3);

            $this->art_name = $rs->getString($startcol + 4);

            $this->art_category_ref = $rs->getString($startcol + 5);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 6; // 6 = BpmnArtifactPeer::NUM_COLUMNS - BpmnArtifactPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnArtifact object", $e);
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
            $con = Propel::getConnection(BpmnArtifactPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnArtifactPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnArtifactPeer::DATABASE_NAME);
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


            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if ($this->aBpmnProject->isModified()) {
                    $affectedRows += $this->aBpmnProject->save($con);
                }
                $this->setBpmnProject($this->aBpmnProject);
            }

            if ($this->aBpmnProcess !== null) {
                if ($this->aBpmnProcess->isModified()) {
                    $affectedRows += $this->aBpmnProcess->save($con);
                }
                $this->setBpmnProcess($this->aBpmnProcess);
            }


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnArtifactPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnArtifactPeer::doUpdate($this, $con);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBpmnProject !== null) {
                if (!$this->aBpmnProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnProject->getValidationFailures());
                }
            }

            if ($this->aBpmnProcess !== null) {
                if (!$this->aBpmnProcess->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBpmnProcess->getValidationFailures());
                }
            }


            if (($retval = BpmnArtifactPeer::doValidate($this, $columns)) !== true) {
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
        $pos = BpmnArtifactPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getArtUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getProUid();
                break;
            case 3:
                return $this->getArtType();
                break;
            case 4:
                return $this->getArtName();
                break;
            case 5:
                return $this->getArtCategoryRef();
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
        $keys = BpmnArtifactPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getArtUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getProUid(),
            $keys[3] => $this->getArtType(),
            $keys[4] => $this->getArtName(),
            $keys[5] => $this->getArtCategoryRef(),
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
        $pos = BpmnArtifactPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setArtUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setProUid($value);
                break;
            case 3:
                $this->setArtType($value);
                break;
            case 4:
                $this->setArtName($value);
                break;
            case 5:
                $this->setArtCategoryRef($value);
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
        $keys = BpmnArtifactPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setArtUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setProUid($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setArtType($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setArtName($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setArtCategoryRef($arr[$keys[5]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnArtifactPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnArtifactPeer::ART_UID)) {
            $criteria->add(BpmnArtifactPeer::ART_UID, $this->art_uid);
        }

        if ($this->isColumnModified(BpmnArtifactPeer::PRJ_UID)) {
            $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnArtifactPeer::PRO_UID)) {
            $criteria->add(BpmnArtifactPeer::PRO_UID, $this->pro_uid);
        }

        if ($this->isColumnModified(BpmnArtifactPeer::ART_TYPE)) {
            $criteria->add(BpmnArtifactPeer::ART_TYPE, $this->art_type);
        }

        if ($this->isColumnModified(BpmnArtifactPeer::ART_NAME)) {
            $criteria->add(BpmnArtifactPeer::ART_NAME, $this->art_name);
        }

        if ($this->isColumnModified(BpmnArtifactPeer::ART_CATEGORY_REF)) {
            $criteria->add(BpmnArtifactPeer::ART_CATEGORY_REF, $this->art_category_ref);
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
        $criteria = new Criteria(BpmnArtifactPeer::DATABASE_NAME);

        $criteria->add(BpmnArtifactPeer::ART_UID, $this->art_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getArtUid();
    }

    /**
     * Generic method to set the primary key (art_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setArtUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnArtifact (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setProUid($this->pro_uid);

        $copyObj->setArtType($this->art_type);

        $copyObj->setArtName($this->art_name);

        $copyObj->setArtCategoryRef($this->art_category_ref);


        $copyObj->setNew(true);

        $copyObj->setArtUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnArtifact Clone of current object.
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
     * @return     BpmnArtifactPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnArtifactPeer();
        }
        return self::$peer;
    }

    /**
     * Declares an association between this object and a BpmnProject object.
     *
     * @param      BpmnProject $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnProject($v)
    {


        if ($v === null) {
            $this->setPrjUid(NULL);
        } else {
            $this->setPrjUid($v->getPrjUid());
        }


        $this->aBpmnProject = $v;
    }


    /**
     * Get the associated BpmnProject object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnProject The associated BpmnProject object.
     * @throws     PropelException
     */
    public function getBpmnProject($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnProjectPeer.php';

        if ($this->aBpmnProject === null && (($this->prj_uid !== "" && $this->prj_uid !== null))) {

            $this->aBpmnProject = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnProjectPeer::retrieveByPK($this->prj_uid, $con);
               $obj->addBpmnProjects($this);
             */
        }
        return $this->aBpmnProject;
    }

    /**
     * Declares an association between this object and a BpmnProcess object.
     *
     * @param      BpmnProcess $v
     * @return     void
     * @throws     PropelException
     */
    public function setBpmnProcess($v)
    {


        if ($v === null) {
            $this->setProUid('');
        } else {
            $this->setProUid($v->getProUid());
        }


        $this->aBpmnProcess = $v;
    }


    /**
     * Get the associated BpmnProcess object
     *
     * @param      Connection Optional Connection object.
     * @return     BpmnProcess The associated BpmnProcess object.
     * @throws     PropelException
     */
    public function getBpmnProcess($con = null)
    {
        // include the related Peer class
        include_once 'classes/model/om/BaseBpmnProcessPeer.php';

        if ($this->aBpmnProcess === null && (($this->pro_uid !== "" && $this->pro_uid !== null))) {

            $this->aBpmnProcess = BpmnProcessPeer::retrieveByPK($this->pro_uid, $con);

            /* The following can be used instead of the line above to
               guarantee the related object contains a reference
               to this object, but this level of coupling
               may be undesirable in many circumstances.
               As it can lead to a db query with many results that may
               never be used.
               $obj = BpmnProcessPeer::retrieveByPK($this->pro_uid, $con);
               $obj->addBpmnProcesss($this);
             */
        }
        return $this->aBpmnProcess;
    }
}

