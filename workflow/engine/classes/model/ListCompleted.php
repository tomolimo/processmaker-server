<?php

require_once 'classes/model/om/BaseListCompleted.php';


/**
 * Skeleton subclass for representing a row from the 'LIST_COMPLETED' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class ListCompleted extends BaseListCompleted
{
    /**
     * Create List Completed Table
     *
     * @param type $data
     * @return type
     *
     */
    public function create($data)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn(ListCompletedPeer::APP_UID);
        $criteria->add( ListCompletedPeer::APP_UID, $data['APP_UID'], Criteria::EQUAL );
        $dataset = ListCompletedPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        if ($dataset->next()) {
            return 1;
        }

        $criteria = new Criteria();
        $criteria->addSelectColumn(ContentPeer::CON_VALUE);
        $criteria->add( ContentPeer::CON_ID, $data['APP_UID'], Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_CATEGORY, 'APP_TITLE', Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_LANG, SYS_LANG, Criteria::EQUAL );
        $dataset = ContentPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        $data['APP_TITLE'] = $aRow['CON_VALUE'];


        $criteria = new Criteria();
        $criteria->addSelectColumn(ContentPeer::CON_VALUE);
        $criteria->add( ContentPeer::CON_ID, $data['PRO_UID'], Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_CATEGORY, 'PRO_TITLE', Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_LANG, SYS_LANG, Criteria::EQUAL );
        $dataset = ContentPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        $data['APP_PRO_TITLE'] = $aRow['CON_VALUE'];


        $criteria = new Criteria();
        $criteria->addSelectColumn(ContentPeer::CON_VALUE);
        $criteria->add( ContentPeer::CON_ID, $data['TAS_UID'], Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_CATEGORY, 'TAS_TITLE', Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_LANG, SYS_LANG, Criteria::EQUAL );
        $dataset = ContentPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        $data['APP_TAS_TITLE'] = $aRow['CON_VALUE'];


        $criteria = new Criteria();
        $criteria->addSelectColumn(UsersPeer::USR_USERNAME);
        $criteria->addSelectColumn(UsersPeer::USR_FIRSTNAME);
        $criteria->addSelectColumn(UsersPeer::USR_LASTNAME);
        $criteria->add( UsersPeer::USR_UID, $data['USR_UID'], Criteria::EQUAL );
        $dataset = UsersPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        $data['DEL_CURRENT_USR_USERNAME']  = $aRow['USR_USERNAME'];
        $data['DEL_CURRENT_USR_FIRSTNAME'] = $aRow['USR_FIRSTNAME'];
        $data['DEL_CURRENT_USR_LASTNAME']  = $aRow['USR_LASTNAME'];
        $data['DEL_PREVIOUS'] = isset($data['DEL_PREVIOUS']) ? $data['DEL_PREVIOUS'] : "";

        if ($data['DEL_PREVIOUS'] != 0) {
            $criteria = new Criteria();
            $criteria->addSelectColumn(AppDelegationPeer::USR_UID);
            $criteria->add( AppDelegationPeer::DEL_INDEX, $data['DEL_PREVIOUS'], Criteria::EQUAL );
            $dataset = UsersPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataset->next();
            $aRow = $dataset->getRow();
            $data['DEL_PREVIOUS_USR_UID']  = $aRow['USR_UID'];
        }

        //Update - WHERE
        $criteriaWhere = new Criteria("workflow");
        $criteriaWhere->add(ListParticipatedLastPeer::APP_UID, $data["APP_UID"], Criteria::EQUAL);
        //Update - SET
        $criteriaSet = new Criteria("workflow");
        $criteriaSet->add(ListParticipatedLastPeer::APP_STATUS, 'COMPLETED');
        BasePeer::doUpdate($criteriaWhere, $criteriaSet, Propel::getConnection("workflow"));

        $users = new Users();
        if($data['USR_UID'] != ''){
            $users->refreshTotal($data['USR_UID'], 'add', 'completed');
        }
        if ($data['DEL_PREVIOUS'] != 0) {
            $criteria = new Criteria();
            $criteria->addSelectColumn(TaskPeer::TAS_TYPE);
            $criteria->add( TaskPeer::TAS_UID, $data['TAS_UID'], Criteria::EQUAL );
            $dataset = TaskPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataset->next();
            $aRow = $dataset->getRow();
            if ($aRow['TAS_TYPE'] != 'SUBPROCESS') {
                $users->refreshTotal($data['USR_UID'], 'remove', 'inbox');
            }
        } else {
            $criteria = new Criteria();
            $criteria->addSelectColumn(SubApplicationPeer::APP_UID);
            $criteria->add( SubApplicationPeer::APP_UID, $data['APP_UID'], Criteria::EQUAL );
            $dataset = SubApplicationPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            if ($dataset->next()) {
                $users->refreshTotal($data['USR_UID'], 'remove', 'inbox');
            } else {
                $users->refreshTotal($data['USR_UID'], 'remove', 'draft');
            }
        }

        $con = Propel::getConnection( ListCompletedPeer::DATABASE_NAME );
        try {
            $this->fromArray( $data, BasePeer::TYPE_FIELDNAME );
            if ($this->validate()) {
                $result = $this->save();
            } else {
                $e = new Exception( "Failed Validation in class " . get_class( $this ) . "." );
                $e->aValidationFailures = $this->getValidationFailures();
                throw ($e);
            }
            $con->commit();
            return $result;
        } catch(Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     *  Update List Completed Table
     *
     * @param type $data
     * @return type
     * @throws type
     */
    public function update($data)
    {
        $con = Propel::getConnection( ListCompletedPeer::DATABASE_NAME );
        try {
            $con->begin();
            $this->setNew( false );
            $this->fromArray( $data, BasePeer::TYPE_FIELDNAME );
            if ($this->validate()) {
                $result = $this->save();
                $con->commit();
                return $result;
            } else {
                $con->rollback();
                throw (new Exception( "Failed Validation in class " . get_class( $this ) . "." ));
            }
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     * Remove List Completed
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function remove ($app_uid)
    {
        $con = Propel::getConnection( ListCompletedPeer::DATABASE_NAME );
        try {
            $this->setAppUid($app_uid);
            $con->begin();
            $this->delete();
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function loadFilters (&$criteria, $filters)
    {
        $filter = isset($filters['filter']) ? $filters['filter'] : "";
        $search = isset($filters['search']) ? $filters['search'] : "";
        $process = isset($filters['process']) ? $filters['process'] : "";
        $category = isset($filters['category']) ? $filters['category'] : "";
        $dateFrom = isset($filters['dateFrom']) ? $filters['dateFrom'] : "";
        $dateTo = isset($filters['dateTo']) ? $filters['dateTo'] : "";

        if ($filter != '') {
            switch ($filter) {
                case 'read':
                    $criteria->add( ListCompletedPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL );
                    break;
                case 'unread':
                    $criteria->add( ListCompletedPeer::DEL_INIT_DATE, null, Criteria::ISNULL );
                    break;
            }
        }

        if ($search != '') {
            $criteria->add(
                $criteria->getNewCriterion( 'CON_APP.CON_VALUE', '%' . $search . '%', Criteria::LIKE )->
                    addOr( $criteria->getNewCriterion( 'CON_TAS.CON_VALUE', '%' . $search . '%', Criteria::LIKE )->
                        addOr( $criteria->getNewCriterion( ListCompletedPeer::APP_NUMBER, $search, Criteria::LIKE ) ) ) );
        }

        if ($process != '') {
            $criteria->add( ListCompletedPeer::PRO_UID, $process, Criteria::EQUAL);
        }

        if ($category != '') {
            // INNER JOIN FOR TAS_TITLE
            $criteria->addSelectColumn(ProcessPeer::PRO_CATEGORY);
            $aConditions   = array();
            $aConditions[] = array(ListCompletedPeer::PRO_UID, ProcessPeer::PRO_UID);
            $aConditions[] = array(ProcessPeer::PRO_CATEGORY, "'" . $category . "'");
            $criteria->addJoinMC($aConditions, Criteria::INNER_JOIN);
        }

        if ($dateFrom != "") {
            if ($dateTo != "") {
                if ($dateFrom == $dateTo) {
                    $dateSame = $dateFrom;
                    $dateFrom = $dateSame . " 00:00:00";
                    $dateTo = $dateSame . " 23:59:59";
                } else {
                    $dateFrom = $dateFrom . " 00:00:00";
                    $dateTo = $dateTo . " 23:59:59";
                }

                $criteria->add( $criteria->getNewCriterion( ListCompletedPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL )->
                    addAnd( $criteria->getNewCriterion( ListCompletedPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL ) ) );
            } else {
                $dateFrom = $dateFrom . " 00:00:00";

                $criteria->add( ListCompletedPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL );
            }
        } elseif ($dateTo != "") {
            $dateTo = $dateTo . " 23:59:59";

            $criteria->add( ListCompletedPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL );
        }
    }

    public function countTotal ($usr_uid, $filters = array())
    {
        $criteria = new Criteria();
        $criteria->add( ListCompletedPeer::USR_UID, $usr_uid, Criteria::EQUAL );
        self::loadFilters($criteria, $filters);
        $total = ListCompletedPeer::doCount( $criteria );
        return (int)$total;
    }

    public function loadList($usr_uid, $filters = array(), $callbackRecord = null)
    {
        $resp = array();
        $criteria = new Criteria();

        $criteria->addSelectColumn(ListCompletedPeer::APP_UID);
        $criteria->addSelectColumn(ListCompletedPeer::USR_UID);
        $criteria->addSelectColumn(ListCompletedPeer::TAS_UID);
        $criteria->addSelectColumn(ListCompletedPeer::PRO_UID);
        $criteria->addSelectColumn(ListCompletedPeer::APP_NUMBER);
        $criteria->addSelectColumn(ListCompletedPeer::APP_TITLE);
        $criteria->addSelectColumn(ListCompletedPeer::APP_PRO_TITLE);
        $criteria->addSelectColumn(ListCompletedPeer::APP_TAS_TITLE);
        $criteria->addSelectColumn(ListCompletedPeer::APP_CREATE_DATE);
        $criteria->addSelectColumn(ListCompletedPeer::APP_FINISH_DATE);
        $criteria->addSelectColumn(ListCompletedPeer::DEL_INDEX);
        $criteria->addSelectColumn(ListCompletedPeer::DEL_PREVIOUS_USR_UID);
        $criteria->addSelectColumn(ListCompletedPeer::DEL_CURRENT_USR_FIRSTNAME);
        $criteria->addSelectColumn(ListCompletedPeer::DEL_CURRENT_USR_LASTNAME);
        $criteria->addSelectColumn(ListCompletedPeer::DEL_CURRENT_USR_USERNAME);
        $criteria->add( ListCompletedPeer::USR_UID, $usr_uid, Criteria::EQUAL );
        self::loadFilters($criteria, $filters);

        $sort  = (!empty($filters['sort'])) ? $filters['sort'] : "APP_FINISH_DATE";
        $dir   = isset($filters['dir']) ? $filters['dir'] : "ASC";
        $start = isset($filters['start']) ? $filters['start'] : "0";
        $limit = isset($filters['limit']) ? $filters['limit'] : "25";
        $paged = isset($filters['paged']) ? $filters['paged'] : 1;

        if ($dir == "DESC") {
            $criteria->addDescendingOrderByColumn($sort);
        } else {
            $criteria->addAscendingOrderByColumn($sort);
        }

        if ($paged == 1) {
            $criteria->setLimit( $limit );
            $criteria->setOffset( $start );
        }

        $dataset = ListCompletedPeer::doSelectRS($criteria, Propel::getDbConnection('workflow_ro') );
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $data = array();
        while ($dataset->next()) {
            $aRow = (is_null($callbackRecord))? $dataset->getRow() : $callbackRecord($dataset->getRow());

            $data[] = $aRow;
        }

        return $data;
    }
} // ListCompleted

