<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';


include_once 'propel/util/Criteria.php';

include_once 'classes/model/BpmnProjectPeer.php';

/**
 * Base class that represents a row from the 'BPMN_PROJECT' table.
 *
 * 
 *
 * @package    workflow.classes.model.om
 */
abstract class BaseBpmnProject extends BaseObject implements Persistent
{

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BpmnProjectPeer
    */
    protected static $peer;

    /**
     * The value for the prj_uid field.
     * @var        string
     */
    protected $prj_uid = '';

    /**
     * The value for the prj_name field.
     * @var        string
     */
    protected $prj_name = '';

    /**
     * The value for the prj_description field.
     * @var        string
     */
    protected $prj_description;

    /**
     * The value for the prj_target_namespace field.
     * @var        string
     */
    protected $prj_target_namespace;

    /**
     * The value for the prj_expresion_language field.
     * @var        string
     */
    protected $prj_expresion_language;

    /**
     * The value for the prj_type_language field.
     * @var        string
     */
    protected $prj_type_language;

    /**
     * The value for the prj_exporter field.
     * @var        string
     */
    protected $prj_exporter;

    /**
     * The value for the prj_exporter_version field.
     * @var        string
     */
    protected $prj_exporter_version;

    /**
     * The value for the prj_create_date field.
     * @var        int
     */
    protected $prj_create_date;

    /**
     * The value for the prj_update_date field.
     * @var        int
     */
    protected $prj_update_date;

    /**
     * The value for the prj_author field.
     * @var        string
     */
    protected $prj_author;

    /**
     * The value for the prj_author_version field.
     * @var        string
     */
    protected $prj_author_version;

    /**
     * The value for the prj_original_source field.
     * @var        string
     */
    protected $prj_original_source;

    /**
     * Collection to store aggregation of collBpmnProcesss.
     * @var        array
     */
    protected $collBpmnProcesss;

    /**
     * The criteria used to select the current contents of collBpmnProcesss.
     * @var        Criteria
     */
    protected $lastBpmnProcessCriteria = null;

    /**
     * Collection to store aggregation of collBpmnActivitys.
     * @var        array
     */
    protected $collBpmnActivitys;

    /**
     * The criteria used to select the current contents of collBpmnActivitys.
     * @var        Criteria
     */
    protected $lastBpmnActivityCriteria = null;

    /**
     * Collection to store aggregation of collBpmnArtifacts.
     * @var        array
     */
    protected $collBpmnArtifacts;

    /**
     * The criteria used to select the current contents of collBpmnArtifacts.
     * @var        Criteria
     */
    protected $lastBpmnArtifactCriteria = null;

    /**
     * Collection to store aggregation of collBpmnDiagrams.
     * @var        array
     */
    protected $collBpmnDiagrams;

    /**
     * The criteria used to select the current contents of collBpmnDiagrams.
     * @var        Criteria
     */
    protected $lastBpmnDiagramCriteria = null;

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
     * Collection to store aggregation of collBpmnDatas.
     * @var        array
     */
    protected $collBpmnDatas;

    /**
     * The criteria used to select the current contents of collBpmnDatas.
     * @var        Criteria
     */
    protected $lastBpmnDataCriteria = null;

    /**
     * Collection to store aggregation of collBpmnEvents.
     * @var        array
     */
    protected $collBpmnEvents;

    /**
     * The criteria used to select the current contents of collBpmnEvents.
     * @var        Criteria
     */
    protected $lastBpmnEventCriteria = null;

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
     * Collection to store aggregation of collBpmnGateways.
     * @var        array
     */
    protected $collBpmnGateways;

    /**
     * The criteria used to select the current contents of collBpmnGateways.
     * @var        Criteria
     */
    protected $lastBpmnGatewayCriteria = null;

    /**
     * Collection to store aggregation of collBpmnLanesets.
     * @var        array
     */
    protected $collBpmnLanesets;

    /**
     * The criteria used to select the current contents of collBpmnLanesets.
     * @var        Criteria
     */
    protected $lastBpmnLanesetCriteria = null;

    /**
     * Collection to store aggregation of collBpmnLanes.
     * @var        array
     */
    protected $collBpmnLanes;

    /**
     * The criteria used to select the current contents of collBpmnLanes.
     * @var        Criteria
     */
    protected $lastBpmnLaneCriteria = null;

    /**
     * Collection to store aggregation of collBpmnParticipants.
     * @var        array
     */
    protected $collBpmnParticipants;

    /**
     * The criteria used to select the current contents of collBpmnParticipants.
     * @var        Criteria
     */
    protected $lastBpmnParticipantCriteria = null;

    /**
     * Collection to store aggregation of collBpmnExtensions.
     * @var        array
     */
    protected $collBpmnExtensions;

    /**
     * The criteria used to select the current contents of collBpmnExtensions.
     * @var        Criteria
     */
    protected $lastBpmnExtensionCriteria = null;

    /**
     * Collection to store aggregation of collBpmnDocumentations.
     * @var        array
     */
    protected $collBpmnDocumentations;

    /**
     * The criteria used to select the current contents of collBpmnDocumentations.
     * @var        Criteria
     */
    protected $lastBpmnDocumentationCriteria = null;

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
     * Get the [prj_uid] column value.
     * 
     * @return     string
     */
    public function getPrjUid()
    {

        return $this->prj_uid;
    }

    /**
     * Get the [prj_name] column value.
     * 
     * @return     string
     */
    public function getPrjName()
    {

        return $this->prj_name;
    }

    /**
     * Get the [prj_description] column value.
     * 
     * @return     string
     */
    public function getPrjDescription()
    {

        return $this->prj_description;
    }

    /**
     * Get the [prj_target_namespace] column value.
     * 
     * @return     string
     */
    public function getPrjTargetNamespace()
    {

        return $this->prj_target_namespace;
    }

    /**
     * Get the [prj_expresion_language] column value.
     * 
     * @return     string
     */
    public function getPrjExpresionLanguage()
    {

        return $this->prj_expresion_language;
    }

    /**
     * Get the [prj_type_language] column value.
     * 
     * @return     string
     */
    public function getPrjTypeLanguage()
    {

        return $this->prj_type_language;
    }

    /**
     * Get the [prj_exporter] column value.
     * 
     * @return     string
     */
    public function getPrjExporter()
    {

        return $this->prj_exporter;
    }

    /**
     * Get the [prj_exporter_version] column value.
     * 
     * @return     string
     */
    public function getPrjExporterVersion()
    {

        return $this->prj_exporter_version;
    }

    /**
     * Get the [optionally formatted] [prj_create_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getPrjCreateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->prj_create_date === null || $this->prj_create_date === '') {
            return null;
        } elseif (!is_int($this->prj_create_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->prj_create_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [prj_create_date] as date/time value: " .
                    var_export($this->prj_create_date, true));
            }
        } else {
            $ts = $this->prj_create_date;
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
     * Get the [optionally formatted] [prj_update_date] column value.
     * 
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                          If format is NULL, then the integer unix timestamp will be returned.
     * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
     * @throws     PropelException - if unable to convert the date/time to timestamp.
     */
    public function getPrjUpdateDate($format = 'Y-m-d H:i:s')
    {

        if ($this->prj_update_date === null || $this->prj_update_date === '') {
            return null;
        } elseif (!is_int($this->prj_update_date)) {
            // a non-timestamp value was set externally, so we convert it
            $ts = strtotime($this->prj_update_date);
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [prj_update_date] as date/time value: " .
                    var_export($this->prj_update_date, true));
            }
        } else {
            $ts = $this->prj_update_date;
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
     * Get the [prj_author] column value.
     * 
     * @return     string
     */
    public function getPrjAuthor()
    {

        return $this->prj_author;
    }

    /**
     * Get the [prj_author_version] column value.
     * 
     * @return     string
     */
    public function getPrjAuthorVersion()
    {

        return $this->prj_author_version;
    }

    /**
     * Get the [prj_original_source] column value.
     * 
     * @return     string
     */
    public function getPrjOriginalSource()
    {

        return $this->prj_original_source;
    }

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

        if ($this->prj_uid !== $v || $v === '') {
            $this->prj_uid = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_UID;
        }

    } // setPrjUid()

    /**
     * Set the value of [prj_name] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjName($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_name !== $v || $v === '') {
            $this->prj_name = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_NAME;
        }

    } // setPrjName()

    /**
     * Set the value of [prj_description] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjDescription($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_description !== $v) {
            $this->prj_description = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_DESCRIPTION;
        }

    } // setPrjDescription()

    /**
     * Set the value of [prj_target_namespace] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjTargetNamespace($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_target_namespace !== $v) {
            $this->prj_target_namespace = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_TARGET_NAMESPACE;
        }

    } // setPrjTargetNamespace()

    /**
     * Set the value of [prj_expresion_language] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjExpresionLanguage($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_expresion_language !== $v) {
            $this->prj_expresion_language = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_EXPRESION_LANGUAGE;
        }

    } // setPrjExpresionLanguage()

    /**
     * Set the value of [prj_type_language] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjTypeLanguage($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_type_language !== $v) {
            $this->prj_type_language = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_TYPE_LANGUAGE;
        }

    } // setPrjTypeLanguage()

    /**
     * Set the value of [prj_exporter] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjExporter($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_exporter !== $v) {
            $this->prj_exporter = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_EXPORTER;
        }

    } // setPrjExporter()

    /**
     * Set the value of [prj_exporter_version] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjExporterVersion($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_exporter_version !== $v) {
            $this->prj_exporter_version = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_EXPORTER_VERSION;
        }

    } // setPrjExporterVersion()

    /**
     * Set the value of [prj_create_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setPrjCreateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [prj_create_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->prj_create_date !== $ts) {
            $this->prj_create_date = $ts;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_CREATE_DATE;
        }

    } // setPrjCreateDate()

    /**
     * Set the value of [prj_update_date] column.
     * 
     * @param      int $v new value
     * @return     void
     */
    public function setPrjUpdateDate($v)
    {

        if ($v !== null && !is_int($v)) {
            $ts = strtotime($v);
            //Date/time accepts null values
            if ($v == '') {
                $ts = null;
            }
            if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [prj_update_date] from input: " .
                    var_export($v, true));
            }
        } else {
            $ts = $v;
        }
        if ($this->prj_update_date !== $ts) {
            $this->prj_update_date = $ts;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_UPDATE_DATE;
        }

    } // setPrjUpdateDate()

    /**
     * Set the value of [prj_author] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjAuthor($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_author !== $v) {
            $this->prj_author = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_AUTHOR;
        }

    } // setPrjAuthor()

    /**
     * Set the value of [prj_author_version] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjAuthorVersion($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_author_version !== $v) {
            $this->prj_author_version = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_AUTHOR_VERSION;
        }

    } // setPrjAuthorVersion()

    /**
     * Set the value of [prj_original_source] column.
     * 
     * @param      string $v new value
     * @return     void
     */
    public function setPrjOriginalSource($v)
    {

        // Since the native PHP type for this column is string,
        // we will cast the input to a string (if it is not).
        if ($v !== null && !is_string($v)) {
            $v = (string) $v;
        }

        if ($this->prj_original_source !== $v) {
            $this->prj_original_source = $v;
            $this->modifiedColumns[] = BpmnProjectPeer::PRJ_ORIGINAL_SOURCE;
        }

    } // setPrjOriginalSource()

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

            $this->prj_uid = $rs->getString($startcol + 0);

            $this->prj_name = $rs->getString($startcol + 1);

            $this->prj_description = $rs->getString($startcol + 2);

            $this->prj_target_namespace = $rs->getString($startcol + 3);

            $this->prj_expresion_language = $rs->getString($startcol + 4);

            $this->prj_type_language = $rs->getString($startcol + 5);

            $this->prj_exporter = $rs->getString($startcol + 6);

            $this->prj_exporter_version = $rs->getString($startcol + 7);

            $this->prj_create_date = $rs->getTimestamp($startcol + 8, null);

            $this->prj_update_date = $rs->getTimestamp($startcol + 9, null);

            $this->prj_author = $rs->getString($startcol + 10);

            $this->prj_author_version = $rs->getString($startcol + 11);

            $this->prj_original_source = $rs->getString($startcol + 12);

            $this->resetModified();

            $this->setNew(false);

            // FIXME - using NUM_COLUMNS may be clearer.
            return $startcol + 13; // 13 = BpmnProjectPeer::NUM_COLUMNS - BpmnProjectPeer::NUM_LAZY_LOAD_COLUMNS).

        } catch (Exception $e) {
            throw new PropelException("Error populating BpmnProject object", $e);
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
            $con = Propel::getConnection(BpmnProjectPeer::DATABASE_NAME);
        }

        try {
            $con->begin();
            BpmnProjectPeer::doDelete($this, $con);
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
            $con = Propel::getConnection(BpmnProjectPeer::DATABASE_NAME);
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
                    $pk = BpmnProjectPeer::doInsert($this, $con);
                    $affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
                                         // should always be true here (even though technically
                                         // BasePeer::doInsert() can insert multiple rows).

                    $this->setNew(false);
                } else {
                    $affectedRows += BpmnProjectPeer::doUpdate($this, $con);
                }
                $this->resetModified(); // [HL] After being saved an object is no longer 'modified'
            }

            if ($this->collBpmnProcesss !== null) {
                foreach($this->collBpmnProcesss as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnActivitys !== null) {
                foreach($this->collBpmnActivitys as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnArtifacts !== null) {
                foreach($this->collBpmnArtifacts as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnDiagrams !== null) {
                foreach($this->collBpmnDiagrams as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnBounds !== null) {
                foreach($this->collBpmnBounds as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnDatas !== null) {
                foreach($this->collBpmnDatas as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnEvents !== null) {
                foreach($this->collBpmnEvents as $referrerFK) {
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

            if ($this->collBpmnGateways !== null) {
                foreach($this->collBpmnGateways as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnLanesets !== null) {
                foreach($this->collBpmnLanesets as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnLanes !== null) {
                foreach($this->collBpmnLanes as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnParticipants !== null) {
                foreach($this->collBpmnParticipants as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnExtensions !== null) {
                foreach($this->collBpmnExtensions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collBpmnDocumentations !== null) {
                foreach($this->collBpmnDocumentations as $referrerFK) {
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


            if (($retval = BpmnProjectPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBpmnProcesss !== null) {
                    foreach($this->collBpmnProcesss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnActivitys !== null) {
                    foreach($this->collBpmnActivitys as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnArtifacts !== null) {
                    foreach($this->collBpmnArtifacts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnDiagrams !== null) {
                    foreach($this->collBpmnDiagrams as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnBounds !== null) {
                    foreach($this->collBpmnBounds as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnDatas !== null) {
                    foreach($this->collBpmnDatas as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnEvents !== null) {
                    foreach($this->collBpmnEvents as $referrerFK) {
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

                if ($this->collBpmnGateways !== null) {
                    foreach($this->collBpmnGateways as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnLanesets !== null) {
                    foreach($this->collBpmnLanesets as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnLanes !== null) {
                    foreach($this->collBpmnLanes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnParticipants !== null) {
                    foreach($this->collBpmnParticipants as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnExtensions !== null) {
                    foreach($this->collBpmnExtensions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBpmnDocumentations !== null) {
                    foreach($this->collBpmnDocumentations as $referrerFK) {
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
        $pos = BpmnProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPrjUid();
                break;
            case 1:
                return $this->getPrjName();
                break;
            case 2:
                return $this->getPrjDescription();
                break;
            case 3:
                return $this->getPrjTargetNamespace();
                break;
            case 4:
                return $this->getPrjExpresionLanguage();
                break;
            case 5:
                return $this->getPrjTypeLanguage();
                break;
            case 6:
                return $this->getPrjExporter();
                break;
            case 7:
                return $this->getPrjExporterVersion();
                break;
            case 8:
                return $this->getPrjCreateDate();
                break;
            case 9:
                return $this->getPrjUpdateDate();
                break;
            case 10:
                return $this->getPrjAuthor();
                break;
            case 11:
                return $this->getPrjAuthorVersion();
                break;
            case 12:
                return $this->getPrjOriginalSource();
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
        $keys = BpmnProjectPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPrjUid(),
            $keys[1] => $this->getPrjName(),
            $keys[2] => $this->getPrjDescription(),
            $keys[3] => $this->getPrjTargetNamespace(),
            $keys[4] => $this->getPrjExpresionLanguage(),
            $keys[5] => $this->getPrjTypeLanguage(),
            $keys[6] => $this->getPrjExporter(),
            $keys[7] => $this->getPrjExporterVersion(),
            $keys[8] => $this->getPrjCreateDate(),
            $keys[9] => $this->getPrjUpdateDate(),
            $keys[10] => $this->getPrjAuthor(),
            $keys[11] => $this->getPrjAuthorVersion(),
            $keys[12] => $this->getPrjOriginalSource(),
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
        $pos = BpmnProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                $this->setPrjUid($value);
                break;
            case 1:
                $this->setPrjName($value);
                break;
            case 2:
                $this->setPrjDescription($value);
                break;
            case 3:
                $this->setPrjTargetNamespace($value);
                break;
            case 4:
                $this->setPrjExpresionLanguage($value);
                break;
            case 5:
                $this->setPrjTypeLanguage($value);
                break;
            case 6:
                $this->setPrjExporter($value);
                break;
            case 7:
                $this->setPrjExporterVersion($value);
                break;
            case 8:
                $this->setPrjCreateDate($value);
                break;
            case 9:
                $this->setPrjUpdateDate($value);
                break;
            case 10:
                $this->setPrjAuthor($value);
                break;
            case 11:
                $this->setPrjAuthorVersion($value);
                break;
            case 12:
                $this->setPrjOriginalSource($value);
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
        $keys = BpmnProjectPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPrjUid($arr[$keys[0]]);
        }

        if (array_key_exists($keys[1], $arr)) {
            $this->setPrjName($arr[$keys[1]]);
        }

        if (array_key_exists($keys[2], $arr)) {
            $this->setPrjDescription($arr[$keys[2]]);
        }

        if (array_key_exists($keys[3], $arr)) {
            $this->setPrjTargetNamespace($arr[$keys[3]]);
        }

        if (array_key_exists($keys[4], $arr)) {
            $this->setPrjExpresionLanguage($arr[$keys[4]]);
        }

        if (array_key_exists($keys[5], $arr)) {
            $this->setPrjTypeLanguage($arr[$keys[5]]);
        }

        if (array_key_exists($keys[6], $arr)) {
            $this->setPrjExporter($arr[$keys[6]]);
        }

        if (array_key_exists($keys[7], $arr)) {
            $this->setPrjExporterVersion($arr[$keys[7]]);
        }

        if (array_key_exists($keys[8], $arr)) {
            $this->setPrjCreateDate($arr[$keys[8]]);
        }

        if (array_key_exists($keys[9], $arr)) {
            $this->setPrjUpdateDate($arr[$keys[9]]);
        }

        if (array_key_exists($keys[10], $arr)) {
            $this->setPrjAuthor($arr[$keys[10]]);
        }

        if (array_key_exists($keys[11], $arr)) {
            $this->setPrjAuthorVersion($arr[$keys[11]]);
        }

        if (array_key_exists($keys[12], $arr)) {
            $this->setPrjOriginalSource($arr[$keys[12]]);
        }

    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return     Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BpmnProjectPeer::DATABASE_NAME);

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_UID)) {
            $criteria->add(BpmnProjectPeer::PRJ_UID, $this->prj_uid);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_NAME)) {
            $criteria->add(BpmnProjectPeer::PRJ_NAME, $this->prj_name);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_DESCRIPTION)) {
            $criteria->add(BpmnProjectPeer::PRJ_DESCRIPTION, $this->prj_description);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_TARGET_NAMESPACE)) {
            $criteria->add(BpmnProjectPeer::PRJ_TARGET_NAMESPACE, $this->prj_target_namespace);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_EXPRESION_LANGUAGE)) {
            $criteria->add(BpmnProjectPeer::PRJ_EXPRESION_LANGUAGE, $this->prj_expresion_language);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_TYPE_LANGUAGE)) {
            $criteria->add(BpmnProjectPeer::PRJ_TYPE_LANGUAGE, $this->prj_type_language);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_EXPORTER)) {
            $criteria->add(BpmnProjectPeer::PRJ_EXPORTER, $this->prj_exporter);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_EXPORTER_VERSION)) {
            $criteria->add(BpmnProjectPeer::PRJ_EXPORTER_VERSION, $this->prj_exporter_version);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_CREATE_DATE)) {
            $criteria->add(BpmnProjectPeer::PRJ_CREATE_DATE, $this->prj_create_date);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_UPDATE_DATE)) {
            $criteria->add(BpmnProjectPeer::PRJ_UPDATE_DATE, $this->prj_update_date);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_AUTHOR)) {
            $criteria->add(BpmnProjectPeer::PRJ_AUTHOR, $this->prj_author);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_AUTHOR_VERSION)) {
            $criteria->add(BpmnProjectPeer::PRJ_AUTHOR_VERSION, $this->prj_author_version);
        }

        if ($this->isColumnModified(BpmnProjectPeer::PRJ_ORIGINAL_SOURCE)) {
            $criteria->add(BpmnProjectPeer::PRJ_ORIGINAL_SOURCE, $this->prj_original_source);
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
        $criteria = new Criteria(BpmnProjectPeer::DATABASE_NAME);

        $criteria->add(BpmnProjectPeer::PRJ_UID, $this->prj_uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return     string
     */
    public function getPrimaryKey()
    {
        return $this->getPrjUid();
    }

    /**
     * Generic method to set the primary key (prj_uid column).
     *
     * @param      string $key Primary key.
     * @return     void
     */
    public function setPrimaryKey($key)
    {
        $this->setPrjUid($key);
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of BpmnProject (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @throws     PropelException
     */
    public function copyInto($copyObj, $deepCopy = false)
    {

        $copyObj->setPrjName($this->prj_name);

        $copyObj->setPrjDescription($this->prj_description);

        $copyObj->setPrjTargetNamespace($this->prj_target_namespace);

        $copyObj->setPrjExpresionLanguage($this->prj_expresion_language);

        $copyObj->setPrjTypeLanguage($this->prj_type_language);

        $copyObj->setPrjExporter($this->prj_exporter);

        $copyObj->setPrjExporterVersion($this->prj_exporter_version);

        $copyObj->setPrjCreateDate($this->prj_create_date);

        $copyObj->setPrjUpdateDate($this->prj_update_date);

        $copyObj->setPrjAuthor($this->prj_author);

        $copyObj->setPrjAuthorVersion($this->prj_author_version);

        $copyObj->setPrjOriginalSource($this->prj_original_source);


        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach($this->getBpmnProcesss() as $relObj) {
                $copyObj->addBpmnProcess($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnActivitys() as $relObj) {
                $copyObj->addBpmnActivity($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnArtifacts() as $relObj) {
                $copyObj->addBpmnArtifact($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnDiagrams() as $relObj) {
                $copyObj->addBpmnDiagram($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnBounds() as $relObj) {
                $copyObj->addBpmnBound($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnDatas() as $relObj) {
                $copyObj->addBpmnData($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnEvents() as $relObj) {
                $copyObj->addBpmnEvent($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnFlows() as $relObj) {
                $copyObj->addBpmnFlow($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnGateways() as $relObj) {
                $copyObj->addBpmnGateway($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnLanesets() as $relObj) {
                $copyObj->addBpmnLaneset($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnLanes() as $relObj) {
                $copyObj->addBpmnLane($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnParticipants() as $relObj) {
                $copyObj->addBpmnParticipant($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnExtensions() as $relObj) {
                $copyObj->addBpmnExtension($relObj->copy($deepCopy));
            }

            foreach($this->getBpmnDocumentations() as $relObj) {
                $copyObj->addBpmnDocumentation($relObj->copy($deepCopy));
            }

        } // if ($deepCopy)


        $copyObj->setNew(true);

        $copyObj->setPrjUid(''); // this is a pkey column, so set to default value

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
     * @return     BpmnProject Clone of current object.
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
     * @return     BpmnProjectPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BpmnProjectPeer();
        }
        return self::$peer;
    }

    /**
     * Temporary storage of collBpmnProcesss to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnProcesss()
    {
        if ($this->collBpmnProcesss === null) {
            $this->collBpmnProcesss = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnProcesss from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnProcesss($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnProcessPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnProcesss === null) {
            if ($this->isNew()) {
               $this->collBpmnProcesss = array();
            } else {

                $criteria->add(BpmnProcessPeer::PRJ_UID, $this->getPrjUid());

                BpmnProcessPeer::addSelectColumns($criteria);
                $this->collBpmnProcesss = BpmnProcessPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnProcessPeer::PRJ_UID, $this->getPrjUid());

                BpmnProcessPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnProcessCriteria) || !$this->lastBpmnProcessCriteria->equals($criteria)) {
                    $this->collBpmnProcesss = BpmnProcessPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnProcessCriteria = $criteria;
        return $this->collBpmnProcesss;
    }

    /**
     * Returns the number of related BpmnProcesss.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnProcesss($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnProcessPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnProcessPeer::PRJ_UID, $this->getPrjUid());

        return BpmnProcessPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnProcess object to this object
     * through the BpmnProcess foreign key attribute
     *
     * @param      BpmnProcess $l BpmnProcess
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnProcess(BpmnProcess $l)
    {
        $this->collBpmnProcesss[] = $l;
        $l->setBpmnProject($this);
    }

    /**
     * Temporary storage of collBpmnActivitys to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnActivitys()
    {
        if ($this->collBpmnActivitys === null) {
            $this->collBpmnActivitys = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnActivitys from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnActivitys($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnActivitys === null) {
            if ($this->isNew()) {
               $this->collBpmnActivitys = array();
            } else {

                $criteria->add(BpmnActivityPeer::PRJ_UID, $this->getPrjUid());

                BpmnActivityPeer::addSelectColumns($criteria);
                $this->collBpmnActivitys = BpmnActivityPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnActivityPeer::PRJ_UID, $this->getPrjUid());

                BpmnActivityPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnActivityCriteria) || !$this->lastBpmnActivityCriteria->equals($criteria)) {
                    $this->collBpmnActivitys = BpmnActivityPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnActivityCriteria = $criteria;
        return $this->collBpmnActivitys;
    }

    /**
     * Returns the number of related BpmnActivitys.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnActivitys($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnActivityPeer::PRJ_UID, $this->getPrjUid());

        return BpmnActivityPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnActivity object to this object
     * through the BpmnActivity foreign key attribute
     *
     * @param      BpmnActivity $l BpmnActivity
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnActivity(BpmnActivity $l)
    {
        $this->collBpmnActivitys[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnActivitys from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnActivitysJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnActivityPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnActivitys === null) {
            if ($this->isNew()) {
                $this->collBpmnActivitys = array();
            } else {

                $criteria->add(BpmnActivityPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnActivitys = BpmnActivityPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnActivityPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnActivityCriteria) || !$this->lastBpmnActivityCriteria->equals($criteria)) {
                $this->collBpmnActivitys = BpmnActivityPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnActivityCriteria = $criteria;

        return $this->collBpmnActivitys;
    }

    /**
     * Temporary storage of collBpmnArtifacts to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnArtifacts()
    {
        if ($this->collBpmnArtifacts === null) {
            $this->collBpmnArtifacts = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnArtifacts from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnArtifacts($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnArtifacts === null) {
            if ($this->isNew()) {
               $this->collBpmnArtifacts = array();
            } else {

                $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->getPrjUid());

                BpmnArtifactPeer::addSelectColumns($criteria);
                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->getPrjUid());

                BpmnArtifactPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnArtifactCriteria) || !$this->lastBpmnArtifactCriteria->equals($criteria)) {
                    $this->collBpmnArtifacts = BpmnArtifactPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnArtifactCriteria = $criteria;
        return $this->collBpmnArtifacts;
    }

    /**
     * Returns the number of related BpmnArtifacts.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnArtifacts($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->getPrjUid());

        return BpmnArtifactPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnArtifact object to this object
     * through the BpmnArtifact foreign key attribute
     *
     * @param      BpmnArtifact $l BpmnArtifact
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnArtifact(BpmnArtifact $l)
    {
        $this->collBpmnArtifacts[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnArtifacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnArtifactsJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnArtifactPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnArtifacts === null) {
            if ($this->isNew()) {
                $this->collBpmnArtifacts = array();
            } else {

                $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnArtifactPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnArtifactCriteria) || !$this->lastBpmnArtifactCriteria->equals($criteria)) {
                $this->collBpmnArtifacts = BpmnArtifactPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnArtifactCriteria = $criteria;

        return $this->collBpmnArtifacts;
    }

    /**
     * Temporary storage of collBpmnDiagrams to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnDiagrams()
    {
        if ($this->collBpmnDiagrams === null) {
            $this->collBpmnDiagrams = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnDiagrams from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnDiagrams($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDiagramPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDiagrams === null) {
            if ($this->isNew()) {
               $this->collBpmnDiagrams = array();
            } else {

                $criteria->add(BpmnDiagramPeer::PRJ_UID, $this->getPrjUid());

                BpmnDiagramPeer::addSelectColumns($criteria);
                $this->collBpmnDiagrams = BpmnDiagramPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnDiagramPeer::PRJ_UID, $this->getPrjUid());

                BpmnDiagramPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnDiagramCriteria) || !$this->lastBpmnDiagramCriteria->equals($criteria)) {
                    $this->collBpmnDiagrams = BpmnDiagramPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnDiagramCriteria = $criteria;
        return $this->collBpmnDiagrams;
    }

    /**
     * Returns the number of related BpmnDiagrams.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnDiagrams($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDiagramPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnDiagramPeer::PRJ_UID, $this->getPrjUid());

        return BpmnDiagramPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnDiagram object to this object
     * through the BpmnDiagram foreign key attribute
     *
     * @param      BpmnDiagram $l BpmnDiagram
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnDiagram(BpmnDiagram $l)
    {
        $this->collBpmnDiagrams[] = $l;
        $l->setBpmnProject($this);
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
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnBounds from storage.
     * If this BpmnProject is new, it will return
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

                $criteria->add(BpmnBoundPeer::PRJ_UID, $this->getPrjUid());

                BpmnBoundPeer::addSelectColumns($criteria);
                $this->collBpmnBounds = BpmnBoundPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnBoundPeer::PRJ_UID, $this->getPrjUid());

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

        $criteria->add(BpmnBoundPeer::PRJ_UID, $this->getPrjUid());

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
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnBounds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnBoundsJoinBpmnDiagram($criteria = null, $con = null)
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

                $criteria->add(BpmnBoundPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnBounds = BpmnBoundPeer::doSelectJoinBpmnDiagram($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnBoundPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnBoundCriteria) || !$this->lastBpmnBoundCriteria->equals($criteria)) {
                $this->collBpmnBounds = BpmnBoundPeer::doSelectJoinBpmnDiagram($criteria, $con);
            }
        }
        $this->lastBpmnBoundCriteria = $criteria;

        return $this->collBpmnBounds;
    }

    /**
     * Temporary storage of collBpmnDatas to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnDatas()
    {
        if ($this->collBpmnDatas === null) {
            $this->collBpmnDatas = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnDatas from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnDatas($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDatas === null) {
            if ($this->isNew()) {
               $this->collBpmnDatas = array();
            } else {

                $criteria->add(BpmnDataPeer::PRJ_UID, $this->getPrjUid());

                BpmnDataPeer::addSelectColumns($criteria);
                $this->collBpmnDatas = BpmnDataPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnDataPeer::PRJ_UID, $this->getPrjUid());

                BpmnDataPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnDataCriteria) || !$this->lastBpmnDataCriteria->equals($criteria)) {
                    $this->collBpmnDatas = BpmnDataPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnDataCriteria = $criteria;
        return $this->collBpmnDatas;
    }

    /**
     * Returns the number of related BpmnDatas.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnDatas($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnDataPeer::PRJ_UID, $this->getPrjUid());

        return BpmnDataPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnData object to this object
     * through the BpmnData foreign key attribute
     *
     * @param      BpmnData $l BpmnData
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnData(BpmnData $l)
    {
        $this->collBpmnDatas[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnDatas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnDatasJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDataPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDatas === null) {
            if ($this->isNew()) {
                $this->collBpmnDatas = array();
            } else {

                $criteria->add(BpmnDataPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnDatas = BpmnDataPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnDataPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnDataCriteria) || !$this->lastBpmnDataCriteria->equals($criteria)) {
                $this->collBpmnDatas = BpmnDataPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnDataCriteria = $criteria;

        return $this->collBpmnDatas;
    }

    /**
     * Temporary storage of collBpmnEvents to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnEvents()
    {
        if ($this->collBpmnEvents === null) {
            $this->collBpmnEvents = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnEvents from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnEvents($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnEvents === null) {
            if ($this->isNew()) {
               $this->collBpmnEvents = array();
            } else {

                $criteria->add(BpmnEventPeer::PRJ_UID, $this->getPrjUid());

                BpmnEventPeer::addSelectColumns($criteria);
                $this->collBpmnEvents = BpmnEventPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnEventPeer::PRJ_UID, $this->getPrjUid());

                BpmnEventPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnEventCriteria) || !$this->lastBpmnEventCriteria->equals($criteria)) {
                    $this->collBpmnEvents = BpmnEventPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnEventCriteria = $criteria;
        return $this->collBpmnEvents;
    }

    /**
     * Returns the number of related BpmnEvents.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnEvents($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnEventPeer::PRJ_UID, $this->getPrjUid());

        return BpmnEventPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnEvent object to this object
     * through the BpmnEvent foreign key attribute
     *
     * @param      BpmnEvent $l BpmnEvent
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnEvent(BpmnEvent $l)
    {
        $this->collBpmnEvents[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnEvents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnEventsJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnEventPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnEvents === null) {
            if ($this->isNew()) {
                $this->collBpmnEvents = array();
            } else {

                $criteria->add(BpmnEventPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnEvents = BpmnEventPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnEventPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnEventCriteria) || !$this->lastBpmnEventCriteria->equals($criteria)) {
                $this->collBpmnEvents = BpmnEventPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnEventCriteria = $criteria;

        return $this->collBpmnEvents;
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
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnFlows from storage.
     * If this BpmnProject is new, it will return
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

                $criteria->add(BpmnFlowPeer::PRJ_UID, $this->getPrjUid());

                BpmnFlowPeer::addSelectColumns($criteria);
                $this->collBpmnFlows = BpmnFlowPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnFlowPeer::PRJ_UID, $this->getPrjUid());

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

        $criteria->add(BpmnFlowPeer::PRJ_UID, $this->getPrjUid());

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
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnFlows from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnFlowsJoinBpmnDiagram($criteria = null, $con = null)
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

                $criteria->add(BpmnFlowPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnFlows = BpmnFlowPeer::doSelectJoinBpmnDiagram($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnFlowPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnFlowCriteria) || !$this->lastBpmnFlowCriteria->equals($criteria)) {
                $this->collBpmnFlows = BpmnFlowPeer::doSelectJoinBpmnDiagram($criteria, $con);
            }
        }
        $this->lastBpmnFlowCriteria = $criteria;

        return $this->collBpmnFlows;
    }

    /**
     * Temporary storage of collBpmnGateways to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnGateways()
    {
        if ($this->collBpmnGateways === null) {
            $this->collBpmnGateways = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnGateways from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnGateways($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnGateways === null) {
            if ($this->isNew()) {
               $this->collBpmnGateways = array();
            } else {

                $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->getPrjUid());

                BpmnGatewayPeer::addSelectColumns($criteria);
                $this->collBpmnGateways = BpmnGatewayPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->getPrjUid());

                BpmnGatewayPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnGatewayCriteria) || !$this->lastBpmnGatewayCriteria->equals($criteria)) {
                    $this->collBpmnGateways = BpmnGatewayPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnGatewayCriteria = $criteria;
        return $this->collBpmnGateways;
    }

    /**
     * Returns the number of related BpmnGateways.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnGateways($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->getPrjUid());

        return BpmnGatewayPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnGateway object to this object
     * through the BpmnGateway foreign key attribute
     *
     * @param      BpmnGateway $l BpmnGateway
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnGateway(BpmnGateway $l)
    {
        $this->collBpmnGateways[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnGateways from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnGatewaysJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnGatewayPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnGateways === null) {
            if ($this->isNew()) {
                $this->collBpmnGateways = array();
            } else {

                $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnGateways = BpmnGatewayPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnGatewayPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnGatewayCriteria) || !$this->lastBpmnGatewayCriteria->equals($criteria)) {
                $this->collBpmnGateways = BpmnGatewayPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnGatewayCriteria = $criteria;

        return $this->collBpmnGateways;
    }

    /**
     * Temporary storage of collBpmnLanesets to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnLanesets()
    {
        if ($this->collBpmnLanesets === null) {
            $this->collBpmnLanesets = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnLanesets from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnLanesets($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnLanesets === null) {
            if ($this->isNew()) {
               $this->collBpmnLanesets = array();
            } else {

                $criteria->add(BpmnLanesetPeer::PRJ_UID, $this->getPrjUid());

                BpmnLanesetPeer::addSelectColumns($criteria);
                $this->collBpmnLanesets = BpmnLanesetPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnLanesetPeer::PRJ_UID, $this->getPrjUid());

                BpmnLanesetPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnLanesetCriteria) || !$this->lastBpmnLanesetCriteria->equals($criteria)) {
                    $this->collBpmnLanesets = BpmnLanesetPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnLanesetCriteria = $criteria;
        return $this->collBpmnLanesets;
    }

    /**
     * Returns the number of related BpmnLanesets.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnLanesets($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnLanesetPeer::PRJ_UID, $this->getPrjUid());

        return BpmnLanesetPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnLaneset object to this object
     * through the BpmnLaneset foreign key attribute
     *
     * @param      BpmnLaneset $l BpmnLaneset
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnLaneset(BpmnLaneset $l)
    {
        $this->collBpmnLanesets[] = $l;
        $l->setBpmnProject($this);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject is new, it will return
     * an empty collection; or if this BpmnProject has previously
     * been saved, it will retrieve related BpmnLanesets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BpmnProject.
     */
    public function getBpmnLanesetsJoinBpmnProcess($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanesetPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnLanesets === null) {
            if ($this->isNew()) {
                $this->collBpmnLanesets = array();
            } else {

                $criteria->add(BpmnLanesetPeer::PRJ_UID, $this->getPrjUid());

                $this->collBpmnLanesets = BpmnLanesetPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        } else {
            // the following code is to determine if a new query is
            // called for.  If the criteria is the same as the last
            // one, just return the collection.

            $criteria->add(BpmnLanesetPeer::PRJ_UID, $this->getPrjUid());

            if (!isset($this->lastBpmnLanesetCriteria) || !$this->lastBpmnLanesetCriteria->equals($criteria)) {
                $this->collBpmnLanesets = BpmnLanesetPeer::doSelectJoinBpmnProcess($criteria, $con);
            }
        }
        $this->lastBpmnLanesetCriteria = $criteria;

        return $this->collBpmnLanesets;
    }

    /**
     * Temporary storage of collBpmnLanes to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnLanes()
    {
        if ($this->collBpmnLanes === null) {
            $this->collBpmnLanes = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnLanes from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnLanes($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanePeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnLanes === null) {
            if ($this->isNew()) {
               $this->collBpmnLanes = array();
            } else {

                $criteria->add(BpmnLanePeer::PRJ_UID, $this->getPrjUid());

                BpmnLanePeer::addSelectColumns($criteria);
                $this->collBpmnLanes = BpmnLanePeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnLanePeer::PRJ_UID, $this->getPrjUid());

                BpmnLanePeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnLaneCriteria) || !$this->lastBpmnLaneCriteria->equals($criteria)) {
                    $this->collBpmnLanes = BpmnLanePeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnLaneCriteria = $criteria;
        return $this->collBpmnLanes;
    }

    /**
     * Returns the number of related BpmnLanes.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnLanes($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnLanePeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnLanePeer::PRJ_UID, $this->getPrjUid());

        return BpmnLanePeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnLane object to this object
     * through the BpmnLane foreign key attribute
     *
     * @param      BpmnLane $l BpmnLane
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnLane(BpmnLane $l)
    {
        $this->collBpmnLanes[] = $l;
        $l->setBpmnProject($this);
    }

    /**
     * Temporary storage of collBpmnParticipants to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnParticipants()
    {
        if ($this->collBpmnParticipants === null) {
            $this->collBpmnParticipants = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnParticipants from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnParticipants($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnParticipantPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnParticipants === null) {
            if ($this->isNew()) {
               $this->collBpmnParticipants = array();
            } else {

                $criteria->add(BpmnParticipantPeer::PRJ_UID, $this->getPrjUid());

                BpmnParticipantPeer::addSelectColumns($criteria);
                $this->collBpmnParticipants = BpmnParticipantPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnParticipantPeer::PRJ_UID, $this->getPrjUid());

                BpmnParticipantPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnParticipantCriteria) || !$this->lastBpmnParticipantCriteria->equals($criteria)) {
                    $this->collBpmnParticipants = BpmnParticipantPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnParticipantCriteria = $criteria;
        return $this->collBpmnParticipants;
    }

    /**
     * Returns the number of related BpmnParticipants.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnParticipants($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnParticipantPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnParticipantPeer::PRJ_UID, $this->getPrjUid());

        return BpmnParticipantPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnParticipant object to this object
     * through the BpmnParticipant foreign key attribute
     *
     * @param      BpmnParticipant $l BpmnParticipant
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnParticipant(BpmnParticipant $l)
    {
        $this->collBpmnParticipants[] = $l;
        $l->setBpmnProject($this);
    }

    /**
     * Temporary storage of collBpmnExtensions to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnExtensions()
    {
        if ($this->collBpmnExtensions === null) {
            $this->collBpmnExtensions = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnExtensions from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnExtensions($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnExtensionPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnExtensions === null) {
            if ($this->isNew()) {
               $this->collBpmnExtensions = array();
            } else {

                $criteria->add(BpmnExtensionPeer::PRJ_UID, $this->getPrjUid());

                BpmnExtensionPeer::addSelectColumns($criteria);
                $this->collBpmnExtensions = BpmnExtensionPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnExtensionPeer::PRJ_UID, $this->getPrjUid());

                BpmnExtensionPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnExtensionCriteria) || !$this->lastBpmnExtensionCriteria->equals($criteria)) {
                    $this->collBpmnExtensions = BpmnExtensionPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnExtensionCriteria = $criteria;
        return $this->collBpmnExtensions;
    }

    /**
     * Returns the number of related BpmnExtensions.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnExtensions($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnExtensionPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnExtensionPeer::PRJ_UID, $this->getPrjUid());

        return BpmnExtensionPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnExtension object to this object
     * through the BpmnExtension foreign key attribute
     *
     * @param      BpmnExtension $l BpmnExtension
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnExtension(BpmnExtension $l)
    {
        $this->collBpmnExtensions[] = $l;
        $l->setBpmnProject($this);
    }

    /**
     * Temporary storage of collBpmnDocumentations to save a possible db hit in
     * the event objects are add to the collection, but the
     * complete collection is never requested.
     * @return     void
     */
    public function initBpmnDocumentations()
    {
        if ($this->collBpmnDocumentations === null) {
            $this->collBpmnDocumentations = array();
        }
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BpmnProject has previously
     * been saved, it will retrieve related BpmnDocumentations from storage.
     * If this BpmnProject is new, it will return
     * an empty collection or the current collection, the criteria
     * is ignored on a new object.
     *
     * @param      Connection $con
     * @param      Criteria $criteria
     * @throws     PropelException
     */
    public function getBpmnDocumentations($criteria = null, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDocumentationPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        if ($this->collBpmnDocumentations === null) {
            if ($this->isNew()) {
               $this->collBpmnDocumentations = array();
            } else {

                $criteria->add(BpmnDocumentationPeer::PRJ_UID, $this->getPrjUid());

                BpmnDocumentationPeer::addSelectColumns($criteria);
                $this->collBpmnDocumentations = BpmnDocumentationPeer::doSelect($criteria, $con);
            }
        } else {
            // criteria has no effect for a new object
            if (!$this->isNew()) {
                // the following code is to determine if a new query is
                // called for.  If the criteria is the same as the last
                // one, just return the collection.


                $criteria->add(BpmnDocumentationPeer::PRJ_UID, $this->getPrjUid());

                BpmnDocumentationPeer::addSelectColumns($criteria);
                if (!isset($this->lastBpmnDocumentationCriteria) || !$this->lastBpmnDocumentationCriteria->equals($criteria)) {
                    $this->collBpmnDocumentations = BpmnDocumentationPeer::doSelect($criteria, $con);
                }
            }
        }
        $this->lastBpmnDocumentationCriteria = $criteria;
        return $this->collBpmnDocumentations;
    }

    /**
     * Returns the number of related BpmnDocumentations.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      Connection $con
     * @throws     PropelException
     */
    public function countBpmnDocumentations($criteria = null, $distinct = false, $con = null)
    {
        // include the Peer class
        include_once 'classes/model/om/BaseBpmnDocumentationPeer.php';
        if ($criteria === null) {
            $criteria = new Criteria();
        }
        elseif ($criteria instanceof Criteria)
        {
            $criteria = clone $criteria;
        }

        $criteria->add(BpmnDocumentationPeer::PRJ_UID, $this->getPrjUid());

        return BpmnDocumentationPeer::doCount($criteria, $distinct, $con);
    }

    /**
     * Method called to associate a BpmnDocumentation object to this object
     * through the BpmnDocumentation foreign key attribute
     *
     * @param      BpmnDocumentation $l BpmnDocumentation
     * @return     void
     * @throws     PropelException
     */
    public function addBpmnDocumentation(BpmnDocumentation $l)
    {
        $this->collBpmnDocumentations[] = $l;
        $l->setBpmnProject($this);
    }
}

