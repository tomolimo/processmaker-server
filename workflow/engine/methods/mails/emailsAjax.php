<?php

use ProcessMaker\Plugins\PluginRegistry;
use ProcessMaker\Exception\RBACException;

$req = (isset($_REQUEST['request']) ? $_REQUEST['request'] : '');

/** @var RBAC $RBAC */
global $RBAC;
switch ($RBAC->userCanAccess('PM_LOGIN')) {
    case -2:
        throw new RBACException('ID_USER_HAVENT_RIGHTS_SYSTEM', -2);
        break;
    case -1:
        throw new RBACException('ID_USER_HAVENT_RIGHTS_PAGE', -1);
        break;
}
$RBAC->allows(basename(__FILE__), $req);

switch ($req) {
    case 'MessageList':
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : '0';
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : '25';
        $proId = (isset($_REQUEST['process'])) ? $_REQUEST['process'] : '';
        $eventype = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
        $msgStatusId = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
        $sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '';
        $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'ASC';
        $dateFrom = isset($_POST["dateFrom"]) ? substr($_POST["dateFrom"], 0, 10) : "";
        $dateTo = isset($_POST["dateTo"]) ? substr($_POST["dateTo"], 0, 10) : "";
        $filterBy = (isset($_REQUEST['filterBy'])) ? $_REQUEST['filterBy'] : 'ALL';

        $criteria = new Criteria();
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_UID);
        $criteria->addSelectColumn(AppMessagePeer::APP_UID);
        $criteria->addSelectColumn(AppMessagePeer::DEL_INDEX);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_TYPE);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_SUBJECT);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_FROM);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_TO);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_BODY);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_STATUS);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_DATE);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_SEND_DATE);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_SHOW_MESSAGE);
        $criteria->addSelectColumn(AppMessagePeer::APP_MSG_ERROR);
        $criteria->addSelectColumn(ApplicationPeer::PRO_UID);
        $criteria->addSelectColumn(ApplicationPeer::APP_TITLE);
        $criteria->addSelectColumn(ApplicationPeer::APP_NUMBER);
        $criteria->addSelectColumn(ProcessPeer::PRO_TITLE);
        $criteria->addSelectColumn(TaskPeer::TAS_TITLE);
        $criteria->addJoin(AppMessagePeer::APP_UID, ApplicationPeer::APP_UID, Criteria::LEFT_JOIN);
        $criteria->addJoin(AppMessagePeer::TAS_ID, TaskPeer::TAS_ID, Criteria::LEFT_JOIN);
        $criteria->addJoin(AppMessagePeer::PRO_ID, ProcessPeer::PRO_ID, Criteria::LEFT_JOIN);

        //Status can be: All, Participated, Pending, Failed
        if (!empty($msgStatusId)) {
            $criteria->add(AppMessagePeer::APP_MSG_STATUS_ID, $msgStatusId);
        }
        //Process uid
        if (!empty($proId)) {
            $criteria->add(AppMessagePeer::PRO_ID, $proId);
        }
        //Filter by can be: All, Cases, Test
        switch ($filterBy) {
            case 'CASES': //TRIGGER and DERIVATION
                $criteria->add(AppMessagePeer::APP_MSG_TYPE_ID, [AppMessage::TYPE_TRIGGER, AppMessage::TYPE_DERIVATION], Criteria::IN);
                break;
            case 'TEST':
                $criteria->add(AppMessagePeer::APP_MSG_TYPE_ID, AppMessage::TYPE_TEST, Criteria::EQUAL);
                break;
            case 'EXTERNAL-REGISTRATION':
                $criteria->add(AppMessagePeer::APP_MSG_TYPE_ID, AppMessage::TYPE_EXTERNAL_REGISTRATION, Criteria::EQUAL);
                break;
            default:
                //Review the External Registration
                $pluginRegistry = PluginRegistry::loadSingleton();
                if (!$pluginRegistry->isEnable('externalRegistration')) {
                    $criteria->add(AppMessagePeer::APP_MSG_TYPE_ID, AppMessage::TYPE_EXTERNAL_REGISTRATION, Criteria::NOT_EQUAL);
                }
                break;
        }
        //Date from and to
        if (!empty($dateFrom) && !empty($dateTo)) {
            $dateTo = $dateTo . " 23:59:59";
            $criteria->add($criteria->getNewCriterion(AppMessagePeer::APP_MSG_DATE, $dateFrom, Criteria::GREATER_EQUAL)->addAnd($criteria->getNewCriterion(AppMessagePeer::APP_MSG_DATE, $dateTo, Criteria::LESS_EQUAL)));
        } else {
            if (!empty($dateFrom)) {
                $criteria->add(AppMessagePeer::APP_MSG_DATE, $dateFrom, Criteria::GREATER_EQUAL);
            }
            if (!empty($dateTo)) {
                $dateTo = $dateTo . " 23:59:59";
                $criteria->add(AppMessagePeer::APP_MSG_DATE, $dateTo, Criteria::LESS_EQUAL);
            }
        }

        //Number records total
        $criteriaCount = clone $criteria;
        $criteriaCount->clearSelectColumns();
        $criteriaCount->addSelectColumn('COUNT(' . AppMessagePeer::APP_MSG_UID . ') AS NUM_REC');
        $rsCriteriaCount = AppMessagePeer::doSelectRS($criteriaCount);
        $rsCriteriaCount->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $resultCount = $rsCriteriaCount->next();
        $rowCount = $rsCriteriaCount->getRow();
        $totalCount = (int)($rowCount['NUM_REC']);

        if (!empty($sort)) {
            if (!in_array($sort, AppMessagePeer::getFieldNames(BasePeer::TYPE_FIELDNAME))) {
                throw new Exception(G::LoadTranslation('ID_INVALID_VALUE_FOR', array('$sort')));
            }
            if ($dir == 'ASC') {
                $criteria->addAscendingOrderByColumn($sort);
            } else {
                $criteria->addDescendingOrderByColumn($sort);
            }
        } else {
            $oCriteria->addDescendingOrderByColumn(AppMessagePeer::APP_MSG_SEND_DATE);
        }
        if (!empty($limit)) {
            $criteria->setLimit($limit);
            $criteria->setOffset($start);
        }

        $result = AppMessagePeer::doSelectRS($criteria);
        $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $data = Array();
        $dataPro = array();
        $index = 1;
        $content = new Content();
        $tasTitleDefault = G::LoadTranslation('ID_TASK_NOT_RELATED');
        while ($result->next()) {
            $row = $result->getRow();
            $row['APP_MSG_FROM'] = htmlentities($row['APP_MSG_FROM'], ENT_QUOTES, "UTF-8");
            $row['APP_MSG_STATUS'] = ucfirst($row['APP_MSG_STATUS']);

            switch ($filterBy) {
                case 'CASES':
                    if ($row['DEL_INDEX'] != 0) {
                        $index = $row['DEL_INDEX'];
                    }
                    if ($row['DEL_INDEX'] == 0) {
                        $row['TAS_TITLE'] = $tasTitleDefault;
                    }
                    break;
            }

            $data[] = $row;
        }
        $response = [];
        $response['totalCount'] = $totalCount;
        $response['data'] = $data;
        die(G::json_encode($response));
        break;
    case 'updateStatusMessage':
        if (isset($_REQUEST['APP_MSG_UID']) && isset($_REQUEST['APP_MSG_STATUS_ID'])) {
            $message = new AppMessage();
            $result = $message->updateStatus($_REQUEST['APP_MSG_UID'], $_REQUEST['APP_MSG_STATUS_ID']);
        }
        break;
}

