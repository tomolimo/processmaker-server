<?php
/**
 * GroupUser.php
 *
 * @package workflow.engine.classes.model
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */

//require_once 'classes/model/om/BaseGroupUser.php';
//require_once 'classes/model/Content.php';
//require_once 'classes/model/Users.php';
//require_once 'classes/model/Groupwf.php';

/**
 * Skeleton subclass for representing a row from the 'GROUP_USER' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the input directory.
 *
 * @package workflow.engine.classes.model
 */
class GroupUser extends BaseGroupUser
{

    /**
     * Create the application document registry
     *
     * @param array $aData
     * @return string
     */
    public function create ($aData)
    {
        $oConnection = Propel::getConnection( GroupUserPeer::DATABASE_NAME );
        try {
            $oGroupUser = new GroupUser();
            $oGroupUser->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            if ($oGroupUser->validate()) {
                $oConnection->begin();
                $iResult = $oGroupUser->save();
                $oConnection->commit();
                return $iResult;
            } else {
                $sMessage = '';
                $aValidationFailures = $oGroupUser->getValidationFailures();
                foreach ($aValidationFailures as $oValidationFailure) {
                    $sMessage .= $oValidationFailure->getMessage() . '<br />';
                }
                throw (new Exception( 'The registry cannot be created!<br />' . $sMessage ));
            }
        } catch (Exception $oError) {
            $oConnection->rollback();
            throw ($oError);
        }
    }

    /**
     * Remove the application document registry
     *
     * @param string $sGrpUid
     * @param string $sUserUid
     * @return string
     */
    public function remove ($sGrpUid, $sUserUid)
    {
        $oConnection = Propel::getConnection( GroupUserPeer::DATABASE_NAME );
        try {
            $oGroupUser = GroupUserPeer::retrieveByPK( $sGrpUid, $sUserUid );
            if (! is_null( $oGroupUser )) {
                $oConnection->begin();
                $iResult = $oGroupUser->delete();
                $oConnection->commit();

                $oGrpwf = new Groupwf();
                $grpName = $oGrpwf->loadByGroupUid($sGrpUid);

                $oUsr = new Users();
                $usrName = $oUsr->load($sUserUid);
                
                G::auditLog("AssignUserToGroup", "Remove user: ".$usrName['USR_USERNAME'] ." (".$sUserUid.") from group ".$grpName['CON_VALUE']." (".$sGrpUid.") ");

                return $iResult;
            } else {
                throw (new Exception( 'This row doesn\'t exist!' ));
            }
        } catch (Exception $oError) {
            $oConnection->rollback();
            throw ($oError);
        }
    }

    public function getCountAllUsersByGroup ()
    {
        $oCriteria = new Criteria( 'workflow' );
        $oCriteria->addSelectColumn( GroupUserPeer::GRP_UID );
        $oCriteria->addSelectColumn( 'COUNT(' . GroupUserPeer::GRP_UID . ') AS CNT' );
        $oCriteria->addJoin( GroupUserPeer::USR_UID, UsersPeer::USR_UID, Criteria::INNER_JOIN );
        $oCriteria->add( UsersPeer::USR_STATUS, 'CLOSED', Criteria::NOT_EQUAL );
        $oCriteria->addGroupByColumn( GroupUserPeer::GRP_UID );
        $oDataset = GroupUserPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
        $aRows = Array ();
        while ($oDataset->next()) {
            $row = $oDataset->getRow();
            $aRows[$row['GRP_UID']] = $row['CNT'];
        }
        return $aRows;
    }

    public function getAllUserGroups ($usrUid)
    {
        $oCriteria = new Criteria( 'workflow' );
        $oCriteria->add( GroupUserPeer::USR_UID, $usrUid );
        //$oCriteria->addGroupByColumn(GroupUserPeer::GRP_UID);
        $oDataset = GroupUserPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );

        $rows = Array ();
        while ($oDataset->next()) {
            $row = $oDataset->getRow();
            $g = new Groupwf();
            try {
                $grpRow = $g->load( $row['GRP_UID'] );
                $row = array_merge( $row, $grpRow );
                $rows[] = $row;
            } catch (Exception $e) {
                continue;
            }
        }

        return $rows;
    }
    /**
     * Get all users assigned to Group
     *
     * @param string $gprUid
     * @return array $rows
     */
    public function getAllGroupUser ($gprUid)
    {
        $oCriteria = new Criteria( 'workflow' );
        $oCriteria->add( GroupUserPeer::GRP_UID, $gprUid );
        $oDataset = GroupUserPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );

        $rows = Array ();
        while ($oDataset->next()) {
            $row = $oDataset->getRow();
            $g = new Groupwf();
            try {
                $grpRow = $g->load( $row['GRP_UID'] );
                $row = array_merge( $row, $grpRow );
                $rows[] = $row;
            } catch (Exception $e) {
                continue;
            }
        }

        return $rows;
    }

    /**
     * This function check if the array have at least one UID valid
     * Ex. we need to check the data for self service value based assignment
     *
     * @param array $toValidate , this array contains uid of user or uid of groups
     * @param array $statusToCheck , this array must be have a valid status for users or groups, ACTIVE INACTIVE VACATION
     * @param string $tableReview , if you need to check uid for users or groups
     * @return boolean $rows
     */
    public function groupsUsersAvailable($toValidate, $statusToCheck = array('ACTIVE'), $tableReview = 'users')
    {
        //Define the batching value for the MySQL error related to max_allowed_packet
        $batching = 25000;
        $array = array_chunk($toValidate, $batching);
        foreach ($array as $key => $uidValues) {
            $oCriteria = new Criteria('workflow');
            switch ($tableReview) {
                case 'groups':
                    $oCriteria->add(GroupwfPeer::GRP_UID, $uidValues, Criteria::IN);
                    $oCriteria->add(GroupwfPeer::GRP_STATUS, $statusToCheck, Criteria::IN);
                    $oCriteria->setLimit(1);
                    $rsCriteria = GroupwfPeer::doSelectRS($oCriteria);
                    break;
                default:
                    $oCriteria->add(UsersPeer::USR_UID, $uidValues, Criteria::IN);
                    $oCriteria->add(UsersPeer::USR_STATUS, $statusToCheck, Criteria::IN);
                    $oCriteria->setLimit(1);
                    $rsCriteria = UsersPeer::doSelectRS($oCriteria);
                    break;
            }
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            if ($rsCriteria->next()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load All users by groupUid
     *
     * @param $groupUid
     * @param string $type
     * @param string $filter
     * @param string $sortField
     * @param string $sortDir
     * @param int $start
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getUsersbyGroup($groupUid, $type = 'USERS', $filter = '', $sortField = 'USR_USERNAME', $sortDir = 'ASC', $start = 0, $limit = null)
    {
        try {
            $validSorting = ['USR_UID', 'USR_USERNAME', 'USR_FIRSTNAME', 'USR_LASTNAME', 'USR_EMAIL', 'USR_STATUS'];
            $response = [
                'start' => !empty($start) ? $start : 0,
                'limit' => !empty($limit) ? $limit : 0,
                'filter' => !empty($filter) ? $filter : '',
                'data' => []
            ];


            $criteria = new Criteria('workflow');
            $criteria->add(UsersPeer::USR_STATUS, 'CLOSED', Criteria::NOT_EQUAL);
            if ($type === 'AVAILABLE-USERS') {
                $subQuery = 'SELECT ' . GroupUserPeer::USR_UID .
                    ' FROM ' . GroupUserPeer::TABLE_NAME .
                    ' WHERE ' . GroupUserPeer::GRP_UID . ' = "' . $groupUid . '" ' .
                    'UNION SELECT "' . RBAC::GUEST_USER_UID . '"';

                $criteria->add(UsersPeer::USR_UID, UsersPeer::USR_UID . " NOT IN ($subQuery)", Criteria::CUSTOM);
            } else {
                //USERS - SUPERVISOR
                $criteria->addJoin(GroupUserPeer::USR_UID, UsersPeer::USR_UID, Criteria::LEFT_JOIN);
                $criteria->add(GroupUserPeer::GRP_UID, $groupUid, Criteria::EQUAL);
            }

            if (!empty($filter)) {
                $criteria->add($criteria->getNewCriterion(UsersPeer::USR_USERNAME, '%' . $filter . '%', Criteria::LIKE)->
                addOr($criteria->getNewCriterion(UsersPeer::USR_FIRSTNAME, '%' . $filter . '%', Criteria::LIKE)->
                addOr($criteria->getNewCriterion(UsersPeer::USR_LASTNAME, '%' . $filter . '%', Criteria::LIKE))));
            }
            $response['total'] = UsersPeer::doCount($criteria);

            $criteria->addSelectColumn(UsersPeer::USR_UID);
            $criteria->addSelectColumn(UsersPeer::USR_USERNAME);
            $criteria->addSelectColumn(UsersPeer::USR_FIRSTNAME);
            $criteria->addSelectColumn(UsersPeer::USR_LASTNAME);
            $criteria->addSelectColumn(UsersPeer::USR_EMAIL);
            $criteria->addSelectColumn(UsersPeer::USR_STATUS);

            $sort = UsersPeer::USR_USERNAME;
            if (!empty($sortField) && in_array($sortField, $validSorting, true)) {
                $sort = UsersPeer::TABLE_NAME . '.' . $sortField;
            }

            if (!empty($sortDir) && strtoupper($sortDir) === 'DESC') {
                $criteria->addDescendingOrderByColumn($sort);
            } else {
                $criteria->addAscendingOrderByColumn($sort);
            }

            if (!empty($start)) {
                $criteria->setOffset((int)$start);
            }

            if (!empty($limit)) {
                $criteria->setLimit((int)$limit);
            }

            $dataSet = UsersPeer::doSelectRS($criteria);
            $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $userRbac = new RbacUsers();
            while ($dataSet->next()) {
                $row = $dataSet->getRow();
                if ($type === 'SUPERVISOR') {
                    if ($userRbac->verifyPermission($row['USR_UID'], 'PM_SUPERVISOR')) {
                        $response['data'][] = $row;
                    }
                } else {
                    $response['data'][] = $row;
                }
            }

            return $response;

        } catch (Exception $error) {
            throw $error;
        }
    }
}

