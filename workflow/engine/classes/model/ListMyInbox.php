<?php

require_once 'classes/model/om/BaseListMyInbox.php';


/**
 * Skeleton subclass for representing a row from the 'LIST_MY_INBOX' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class ListMyInbox extends BaseListMyInbox
{
    /**
     * Create List My Inbox Table
     *
     * @param type $data
     * @return type
     *
     */
    public function create($data)
    {
        $con = Propel::getConnection( ListMyInboxPeer::DATABASE_NAME );
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
     *  Update List My Inbox Table
     *
     * @param type $data
     * @return type
     * @throws type
     */
    public function update($data)
    {
        $con = Propel::getConnection( ListMyInboxPeer::DATABASE_NAME );
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
     * Remove List My Inbox
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function remove ($app_uid, $usr_uid)
    {
        $con = Propel::getConnection( ListMyInboxPeer::DATABASE_NAME );
        try {
            $this->setAppUid($app_uid);
            $this->setUsrUid($usr_uid);

            $con->begin();
            $this->delete();
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     * Refresh List My Inbox
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function refresh ($data)
    {
        $data['APP_STATUS'] = (empty($data['APP_STATUS'])) ? 'TO_DO' : $data['APP_STATUS'];

        $criteria = new Criteria();
        $criteria->addSelectColumn(UsersPeer::USR_USERNAME);
        $criteria->addSelectColumn(UsersPeer::USR_FIRSTNAME);
        $criteria->addSelectColumn(UsersPeer::USR_LASTNAME);
        $criteria->add( UsersPeer::USR_UID, $data['USR_UID'], Criteria::EQUAL );
        $dataset = UsersPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        $data['DEL_CURRENT_USR_UID'] = $data['USR_UID'];
        $data['DEL_CURRENT_USR_USERNAME']  = $aRow['USR_USERNAME'];
        $data['DEL_CURRENT_USR_FIRSTNAME'] = $aRow['USR_FIRSTNAME'];
        $data['DEL_CURRENT_USR_LASTNAME']  = $aRow['USR_LASTNAME'];

        if ($data['DEL_INDEX'] == 1 && $data['APP_STATUS'] == 'TO_DO') {
            $data['APP_CREATE_DATE'] = $data['APP_UPDATE_DATE'];
            $oCriteria = new Criteria('workflow');
            $oCriteria->add(ListMyInboxPeer::APP_UID, $data['APP_UID']);
            $oCriteria->add(ListMyInboxPeer::USR_UID, $data['USR_UID']);
            ListMyInboxPeer::doDelete($oCriteria);
            $this->create($data);
        } else {
            unset($data['USR_UID']);
            $this->update($data);
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
                    $criteria->add( ListMyInboxPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL );
                    break;
                case 'unread':
                    $criteria->add( ListMyInboxPeer::DEL_INIT_DATE, null, Criteria::ISNULL );
                    break;
            }
        }

        if ($search != '') {
            $criteria->add(
                $criteria->getNewCriterion( 'CON_APP.CON_VALUE', '%' . $search . '%', Criteria::LIKE )->
                    addOr( $criteria->getNewCriterion( 'CON_TAS.CON_VALUE', '%' . $search . '%', Criteria::LIKE )->
                        addOr( $criteria->getNewCriterion( ListMyInboxPeer::APP_NUMBER, $search, Criteria::LIKE ) ) ) );
        }

        if ($process != '') {
            $criteria->add( ListMyInboxPeer::PRO_UID, $process, Criteria::EQUAL);
        }

        if ($category != '') {
            // INNER JOIN FOR TAS_TITLE
            $criteria->addSelectColumn(ProcessPeer::PRO_CATEGORY);
            $aConditions   = array();
            $aConditions[] = array(ListMyInboxPeer::PRO_UID, ProcessPeer::PRO_UID);
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

                $criteria->add( $criteria->getNewCriterion( ListMyInboxPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL )->
                    addAnd( $criteria->getNewCriterion( ListMyInboxPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL ) ) );
            } else {
                $dateFrom = $dateFrom . " 00:00:00";

                $criteria->add( ListMyInboxPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL );
            }
        } elseif ($dateTo != "") {
            $dateTo = $dateTo . " 23:59:59";

            $criteria->add( ListMyInboxPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL );
        }
    }

    public function countTotal ($usr_uid, $filters = array())
    {
        $criteria = new Criteria();
        $criteria->add( ListMyInboxPeer::USR_UID, $usr_uid, Criteria::EQUAL );
        self::loadFilters($criteria, $filters);
        $total = ListMyInboxPeer::doCount( $criteria );
        return (int)$total;
    }

    public function loadList($usr_uid, $filters = array(), $callbackRecord = null)
    {
        $criteria = new Criteria();

        $criteria->addSelectColumn(ListMyInboxPeer::APP_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::USR_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::TAS_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::PRO_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_NUMBER);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_TITLE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_PRO_TITLE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_TAS_TITLE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_CREATE_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_UPDATE_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_FINISH_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::APP_STATUS);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_INDEX);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_FIRSTNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_LASTNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_CURRENT_USR_USERNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_UID);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_USERNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_FIRSTNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PREVIOUS_USR_LASTNAME);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_DELEGATE_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_INIT_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_DUE_DATE);
        $criteria->addSelectColumn(ListMyInboxPeer::DEL_PRIORITY);
        $criteria->add( ListMyInboxPeer::USR_UID, $usr_uid, Criteria::EQUAL );
        self::loadFilters($criteria, $filters);

        $sort  = (!empty($filters['sort'])) ? $filters['sort'] : "APP_UPDATE_DATE";
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

        $dataset = ListMyInboxPeer::doSelectRS($criteria, Propel::getDbConnection('workflow_ro') );
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $data = array();
        $aPriorities = array ('1' => 'VL','2' => 'L','3' => 'N','4' => 'H','5' => 'VH');
        while ($dataset->next()) {
            $aRow = (is_null($callbackRecord))? $dataset->getRow() : $callbackRecord($dataset->getRow());

            $aRow['DEL_PRIORITY'] = G::LoadTranslation( "ID_PRIORITY_{$aPriorities[$aRow['DEL_PRIORITY']]}" );
            $data[] = $aRow;
        }

        return $data;
    }
} // ListMyInbox

