<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnDiagramPeer.php';

/**
 * Base class that represents a row from the 'BPMN_DIAGRAM' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnDiagram extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnDiagramPeer
    */
    protected static $peer;

    /**
     * The value for the dia_uid field.
     * @var        string
     */
    protected $dia_uid = '';

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid;

    /**
     * The value for the dia_name field.
     * @var        string
     */
    protected $dia_name;

    /**
     * The value for the dia_is_closable field.
     * @var        int
     */
    protected $dia_is_closable = 0;

    /**
     * @var        BpmnProject
     */
    protected $aBpmnProject;

    /**
     * Collection to store aggregation of collBpmnBounds.
     * @var        array
     */
    protected $collBpmnBounds;

    /**
     * The criteria used to select the current contents of collBpmnBounds.
     * @var        Criteria
     */
    protected $lastBpmnBoundCriteria = null;

    /**
     * Collection to store aggregation of collBpmnFlows.
     * @var        array
     */
    protected $collBpmnFlows;

    /**
     * The criteria used to select the current contents of collBpmnFlows.
     * @var        Criteria
     */
    protected $lastBpmnFlowCriteria = null;

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
     * Get the [dia_uid] column value.
     * 
     * @return     string
     */
    public function getDiaUid()
    {

        return $this->dia_uid;
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
     * Get the [dia_name] column value.
     * 
     * @return     string
     */
    public function getDiaName()
    {

        return $this->dia_name;
    }

    /**
     * Get the [dia_is_closable] column value.
     * 
     * @return     int
     */
    public function getDiaIsClosable()
    {

        return $this->dia_is_closable;
    }

    /**
     * Set the value of [dia_uid] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDiaUid($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dia_uid !== $v || $v === '') {
            $this->dia_uid = $v;
            $this->modifiedColumns[] = BpmnDiagramPeer::DIA_UID;
        }

    } // setDiaUid()

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
            $this->modifiedColumns[] = BpmnDiagramPeer::PRJ_UID;
        }

        if ($this->aBpmnProject !== null && $this->aBpmnProject->getPrjUid() !== $v) {
            $this->aBpmnProject = null;
        }

    } // setPrjUid()

    /**
     * Set the value of [dia_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setDiaName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->dia_name !== $v) {
            $this->dia_name = $v;
            $this->modifiedColumns[] = BpmnDiagramPeer::DIA_NAME;
        }

    } // setDiaName()

    /**
     * Set the value of [dia_is_closable] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setDiaIsClosable($v)
    {

        // Since the native PHP type for this column is integer,
        // we will cast the input value to an int (if it is not).
        if ($v !== null && !is_int($v) && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->dia_is_closable !== $v || $v === 0) {
            $this->dia_is_closable = $v;
            $this->modifiedColumns[] = BpmnDiagramPeer::DIA_IS_CLOSABLE;
        }

    } // setDiaIsClosable()

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

            $this->dia_uid = $rs->getString($startcol + 0);

            $this->prj_uid = $rs->getString($startcol + 1);

            $this->dia_name = $rs->getString($startcol + 2);

            $this->dia_is_closable = $rs->getInt($startcol + 3);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 4; // 4 = BpmnDiagramPeer::NUM_COLUMNS - BpmnDiagramPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnDiagram object", $e);
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
            $con = Propel::getConnection(BpmnDiagramPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnDiagramPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnDiagramPeer::DATABASE_NAME);
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


            // If this object has been modified, then save it to the database.
            if ($this->isModified()) {
                if ($this->isNew()) {
                    $pk = BpmnDiagramPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnDiagramPeer::doUpdate($this, $con);
                }
                $this->resetModified(); // [HL] After being saved an object is no longer 'modified'
            }

            if ($this->collBpmnBounds !== null) {
                foreach($this->collBpmnBounds as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnFlows !== null) {
                foreach($this->collBpmnFlows as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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


            if (($retval = BpmnDiagramPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBpmnBounds !== null) {
                    foreach($this->collBpmnBounds as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnFlows !== null) {
                    foreach($this->collBpmnFlows as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $pos = BpmnDiagramPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDiaUid();
                break;
            case 1:
                return $this->getPrjUid();
                break;
            case 2:
                return $this->getDiaName();
                break;
            case 3:
                return $this->getDiaIsClosable();
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
        $keys = BpmnDiagramPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDiaUid(),
            $keys[1] => $this->getPrjUid(),
            $keys[2] => $this->getDiaName(),
            $keys[3] => $this->getDiaIsClosable(),
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
        $pos = BpmnDiagramPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setDiaUid($value);
                break;
            case 1:
                $this->setPrjUid($value);
                break;
            case 2:
                $this->setDiaName($value);
                break;
            case 3:
                $this->setDiaIsClosable($value);
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
        $keys = BpmnDiagramPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setDiaUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjUid($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setDiaName($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setDiaIsClosable($arr[$keys[3]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnDiagramPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnDiagramPeer::DIA_UID)) {
            $criteria->add(BpmnDiagramPeer::DIA_UID, $this->dia_uid);
        }

        if ($this->isColumnModified(BpmnDiagramPeer::PRJ_UID)) {
            $criteria->add(BpmnDiagramPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnDiagramPeer::DIA_NAME)) {
            $criteria->add(BpmnDiagramPeer::DIA_NAME, $this->dia_name);
        }

        if ($this->isColumnModified(BpmnDiagramPeer::DIA_IS_CLOSABLE)) {
            $criteria->add(BpmnDiagramPeer::DIA_IS_CLOSABLE, $this->dia_is_closable);
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
        $criteria = new Criteria(BpmnDiagramPeer::DATABASE_NAME);

        $criteria->add(BpmnDiagramPeer::DIA_UID, $this->dia_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getDiaUid();
    }

    /**
     * Generic method to set the primary key (dia_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setDiaUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnDiagram (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjUid($this->prj_uid);

        $copyObj->setDiaName($this->dia_name);

        $copyObj->setDiaIsClosable($this->dia_is_closable);


        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach($this->getBpmnBounds() as $relObj) {
                $copyObj->addBpmnBound($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnFlows() as $relObj) {
                $copyObj->addBpmnFlow($relObj->copy($deepCopy));
            }

        } // if ($deepCopy)


        $copyObj->setNew(true);

        $copyObj->setDiaUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnDiagram Clone of current object.
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
     * @return     BpmnDiagramPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnDiagramPeer();
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
     * Temporary storage of collBpmnBounds to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnBounds()
    {
        if ($this->collBpmnBounds === null) {
            $this->collBpmnBounds = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnDiagram has previously
     * been saved, it will retrieve related BpmnBounds from storage.
     * If this BpmnDiagram is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnBounds($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnBoundPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnBounds === null) {
            if ($this->isNew()) {
               $this->collBpmnBounds = array();
            } else {

                $criteria->add(BpmnBoundPeer::DIA_UID, $this->getDiaUid());

                BpmnBoundPeer::addSelectColumns($criteria);
                $this->collBpmnBounds = BpmnBoundPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnBoundPeer::DIA_UID, $this->getDiaUid());

                BpmnBoundPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnBoundCriteria) || !$this->lastBpmnBoundCriteria->equals($criteria)) {
                    $this->collBpmnBounds = BpmnBoundPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnBoundCriteria = $criteria;
        return $this->collBpmnBounds;
    }

    /**
     * Returns the number of related BpmnBounds.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnBounds($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnBoundPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnBoundPeer::DIA_UID, $this->getDiaUid());

        return BpmnBoundPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnBound object to this object
     * through the BpmnBound foreign key attribute
     *
     * @param      BpmnBound $l BpmnBound
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnBound(BpmnBound $l)
    {
        $this->collBpmnBounds[] = $l;
        $l->setBpmnDiagram($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnDiagram is new, it will return
     * an empty collection; or if this BpmnDiagram has previously
     * been saved, it will retrieve related BpmnBounds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnDiagram.
     */
    public function getBpmnBoundsJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnBoundPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnBounds === null) {
            if ($this->isNew()) {
                $this->collBpmnBounds = array();
            } else {

                $criteria->add(BpmnBoundPeer::DIA_UID, $this->getDiaUid());

                $this->collBpmnBounds = BpmnBoundPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnBoundPeer::DIA_UID, $this->getDiaUid());

            if (!isset($this->lastBpmnBoundCriteria) || !$this->lastBpmnBoundCriteria->equals($criteria)) {
                $this->collBpmnBounds = BpmnBoundPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnBoundCriteria = $criteria;

        return $this->collBpmnBounds;
    }

    /**
     * Temporary storage of collBpmnFlows to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnFlows()
    {
        if ($this->collBpmnFlows === null) {
            $this->collBpmnFlows = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnDiagram has previously
     * been saved, it will retrieve related BpmnFlows from storage.
     * If this BpmnDiagram is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnFlows($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnFlowPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnFlows === null) {
            if ($this->isNew()) {
               $this->collBpmnFlows = array();
            } else {

                $criteria->add(BpmnFlowPeer::DIA_UID, $this->getDiaUid());

                BpmnFlowPeer::addSelectColumns($criteria);
                $this->collBpmnFlows = BpmnFlowPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnFlowPeer::DIA_UID, $this->getDiaUid());

                BpmnFlowPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnFlowCriteria) || !$this->lastBpmnFlowCriteria->equals($criteria)) {
                    $this->collBpmnFlows = BpmnFlowPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnFlowCriteria = $criteria;
        return $this->collBpmnFlows;
    }

    /**
     * Returns the number of related BpmnFlows.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnFlows($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnFlowPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnFlowPeer::DIA_UID, $this->getDiaUid());

        return BpmnFlowPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnFlow object to this object
     * through the BpmnFlow foreign key attribute
     *
     * @param      BpmnFlow $l BpmnFlow
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnFlow(BpmnFlow $l)
    {
        $this->collBpmnFlows[] = $l;
        $l->setBpmnDiagram($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnDiagram is new, it will return
     * an empty collection; or if this BpmnDiagram has previously
     * been saved, it will retrieve related BpmnFlows from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnDiagram.
     */
    public function getBpmnFlowsJoinBpmnProject($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnFlowPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnFlows === null) {
            if ($this->isNew()) {
                $this->collBpmnFlows = array();
            } else {

                $criteria->add(BpmnFlowPeer::DIA_UID, $this->getDiaUid());

                $this->collBpmnFlows = BpmnFlowPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnFlowPeer::DIA_UID, $this->getDiaUid());

            if (!isset($this->lastBpmnFlowCriteria) || !$this->lastBpmnFlowCriteria->equals($criteria)) {
                $this->collBpmnFlows = BpmnFlowPeer::doSelectJoinBpmnProject($criteria, $con);
            }
        }
        $this->lastBpmnFlowCriteria = $criteria;

        return $this->collBpmnFlows;
    }
}

