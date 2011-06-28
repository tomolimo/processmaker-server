<?php
/**
 * data_additionalTablesList.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */

require_once 'classes/model/AdditionalTables.php';
G::LoadClass('configuration');
$co = new Configurations();
$config = $co->getConfiguration('additionalTablesList', 'pageSize','',$_SESSION['USER_LOGGED']);
$env = $co->getConfiguration('ENVIRONMENT_SETTINGS', '');
$limit_size = isset($config['pageSize']) ? $config['pageSize'] : 20;
$start   = isset($_REQUEST['start'])  ? $_REQUEST['start'] : 0;
$limit   = isset($_REQUEST['limit'])  ? $_REQUEST['limit'] : $limit_size; 
$filter = isset($_REQUEST['textFilter']) ? $_REQUEST['textFilter'] : '';

$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
$oCriteria->add(AdditionalTablesPeer::PRO_UID, '', Criteria::EQUAL);
if ($filter!=''){
	$oCriteria->add(
	  $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_NAME, '%'.$filter.'%',Criteria::LIKE)->addOr(
	  $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_DESCRIPTION, '%'.$filter.'%',Criteria::LIKE)));
}
$total_tables = AdditionalTablesPeer::doCount($oCriteria);
//$oDataset = AdditionalTablesPeer::doSelectRS ( $oCriteria );
//$oDataset->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
//$oDataset->next();
//$row = $oDataset->getRow();
//$total_tables = $row['CNT'];

$oCriteria->clear();
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
$oCriteria->add(AdditionalTablesPeer::ADD_TAB_UID, '', Criteria::NOT_EQUAL);
$oCriteria->add(AdditionalTablesPeer::PRO_UID, '', Criteria::EQUAL);
if ($filter!=''){
	$oCriteria->add(
	  $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_NAME, '%'.$filter.'%',Criteria::LIKE)->addOr(
	  $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_DESCRIPTION, '%'.$filter.'%',Criteria::LIKE)));
}

$oCriteria->setLimit($limit);
$oCriteria->setOffset($start);

$oDataset = AdditionalTablesPeer::doSelectRS ( $oCriteria );
$oDataset->setFetchmode ( ResultSet::FETCHMODE_ASSOC );

$addTables = Array();
while( $oDataset->next() ) {
    $addTables[] = $oDataset->getRow(); 
}
echo '{tables: '.G::json_encode($addTables).', total_tables: '.$total_tables.'}';