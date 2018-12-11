<?php

require_once 'classes/model/om/BaseListParticipatedHistory.php';


/**
 * Skeleton subclass for representing a row from the 'LIST_PARTICIPATED_HISTORY' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
// @codingStandardsIgnoreStart
class ListParticipatedHistory extends BaseListParticipatedHistory implements ListInterface
{
    use ListBaseTrait;

    // @codingStandardsIgnoreEnd
    /**
     * Create List Participated History Table
     *
     * @param type $data
     * @return type
     *
     */
    public function create($data)
    {
        if (!empty($data['PRO_UID']) && empty($data['PRO_ID'])) {
            $p = new Process();
            $data['PRO_ID'] =  $p->load($data['PRO_UID'])['PRO_ID'];
        }
        if (!empty($data['USR_UID'])) {
            $u = new Users();
            $data['USR_ID'] = $u->load($data['USR_UID'])['USR_ID'];
        }
        if (!empty($data['TAS_UID'])) {
            $t = new Task();
            $data['TAS_ID'] = $t->load($data['TAS_UID'])['TAS_ID'];
        }
        $con = Propel::getConnection(ListParticipatedHistoryPeer::DATABASE_NAME);
        try {
            $this->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $result = $this->save();
            } else {
                $e = new Exception("Failed Validation in class " . get_class($this) . ".");
                $e->aValidationFailures = $this->getValidationFailures();
                throw ($e);
            }
            $con->commit();
            return $result;
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     *  Update List Participated History Table
     *
     * @param type $data
     * @return type
     * @throws type
     */
    public function update($data)
    {
        if (!empty($data['USR_UID'])) {
            $u = new Users();
            $data['USR_ID'] = $u->load($data['USR_UID'])['USR_ID'];
        }
        if (!empty($data['TAS_UID'])) {
            $t = new Task();
            $data['TAS_ID'] = $t->load($data['TAS_UID'])['TAS_ID'];
        }
        $con = Propel::getConnection(ListParticipatedHistoryPeer::DATABASE_NAME);
        try {
            $con->begin();
            $this->setNew(false);
            $this->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $result = $this->save();
                $con->commit();
                return $result;
            } else {
                $con->rollback();
                throw (new Exception("Failed Validation in class " . get_class($this) . "."));
            }
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /**
     * Remove List Participated History
     *
     * @param type $seqName
     * @return type
     * @throws type
     *
     */
    public function remove($app_uid, $del_index)
    {
        $con = Propel::getConnection(ListParticipatedHistoryPeer::DATABASE_NAME);
        try {
            $this->setAppUid($app_uid);
            $this->setDelIndex($del_index);

            $con->begin();
            $this->delete();
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function loadFilters(&$criteria, $filters)
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
                    $criteria->add(ListParticipatedHistoryPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL);
                    break;
                case 'unread':
                    $criteria->add(ListParticipatedHistoryPeer::DEL_INIT_DATE, null, Criteria::ISNULL);
                    break;
            }
        }

        if ($search != '') {
            $criteria->add(
                $criteria->getNewCriterion('CON_APP.CON_VALUE', '%' . $search . '%', Criteria::LIKE)->addOr(
                    $criteria->getNewCriterion('CON_TAS.CON_VALUE', '%' . $search . '%', Criteria::LIKE)->addOr(
                        $criteria->getNewCriterion(ListParticipatedHistoryPeer::APP_UID, $search, Criteria::EQUAL)
                        ->addOr(
                            $criteria->getNewCriterion(
                                ListParticipatedHistoryPeer::APP_NUMBER,
                                $search,
                                Criteria::EQUAL
                            )
                        )
                    )
                )
            );
        }

        if ($process != '') {
            $criteria->add(ListParticipatedHistoryPeer::PRO_UID, $process, Criteria::EQUAL);
        }

        if ($category != '') {
            // INNER JOIN FOR TAS_TITLE
            $criteria->addSelectColumn(ProcessPeer::PRO_CATEGORY);
            $aConditions   = array();
            $aConditions[] = array(ListParticipatedHistoryPeer::PRO_UID, ProcessPeer::PRO_UID);
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

                $criteria->add(
                    $criteria->getNewCriterion(
                        ListParticipatedHistoryPeer::DEL_DELEGATE_DATE,
                        $dateFrom,
                        Criteria::GREATER_EQUAL
                    )->addAnd(
                        $criteria->getNewCriterion(
                            ListParticipatedHistoryPeer::DEL_DELEGATE_DATE,
                            $dateTo,
                            Criteria::LESS_EQUAL
                        )
                    )
                );
            } else {
                $dateFrom = $dateFrom . " 00:00:00";

                $criteria->add(ListParticipatedHistoryPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL);
            }
        } elseif ($dateTo != "") {
            $dateTo = $dateTo . " 23:59:59";

            $criteria->add(ListParticipatedHistoryPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL);
        }
    }

    public function loadList($usr_uid, $filters = array(), callable $callbackRecord = null)
    {
        $criteria = new Criteria();

        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_UID);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_INDEX);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::USR_UID);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::TAS_UID);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::PRO_UID);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_NUMBER);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_TITLE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_PRO_TITLE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_TAS_TITLE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::APP_TAS_TITLE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_PREVIOUS_USR_UID);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_PREVIOUS_USR_USERNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_PREVIOUS_USR_FIRSTNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_PREVIOUS_USR_LASTNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_CURRENT_USR_USERNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_CURRENT_USR_FIRSTNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_CURRENT_USR_LASTNAME);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_DELEGATE_DATE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_INIT_DATE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_DUE_DATE);
        $criteria->addSelectColumn(ListParticipatedHistoryPeer::DEL_PRIORITY);
        $criteria->add(ListParticipatedHistoryPeer::USR_UID, $usr_uid, Criteria::EQUAL);
        self::loadFilters($criteria, $filters);

        $sort  = (!empty($filters['sort'])) ? $filters['sort'] : "DEL_DELEGATE_DATE";
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
            $criteria->setLimit($limit);
            $criteria->setOffset($start);
        }

        $dataset = ListParticipatedHistoryPeer::doSelectRS($criteria, Propel::getDbConnection('workflow_ro'));
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $data = array();
        $aPriorities = array('1' => 'VL', '2' => 'L', '3' => 'N', '4' => 'H', '5' => 'VH');
        while ($dataset->next()) {
            $aRow = (is_null($callbackRecord))? $dataset->getRow() : $callbackRecord($dataset->getRow());

            $aRow['DEL_PRIORITY'] = G::LoadTranslation("ID_PRIORITY_{$aPriorities[$aRow['DEL_PRIORITY']]}");
            $data[] = $aRow;
        }

        return $data;
    }

    /**
     * Returns the number of cases of a user
     * @param $usrUid
     * @param array $filters
     * @return int
     */
    public function getCountList($usrUid, $filters = array())
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn('COUNT(*) AS TOTAL');
        $criteria->add(ListParticipatedHistoryPeer::USR_UID, $usrUid, Criteria::EQUAL);
        if (count($filters)) {
            self::loadFilters($criteria, $filters);
        }
        $dataset = ListParticipatedHistoryPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $aRow = $dataset->getRow();
        return (int)$aRow['TOTAL'];
    }
}
