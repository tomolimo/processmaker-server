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

$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
$oCriteria->addSelectColumn("'".G::LoadTranslation('ID_ACTION_EXPORT')."' as 'CH_SCHEMA'");
$oCriteria->addSelectColumn("'".G::LoadTranslation('ID_ACTION_EXPORT')."' as 'CH_DATA'");

$uids = explode(',',$_GET['aUID']);

foreach ($uids as $UID){
	if (!isset($CC)){
  	   $CC = $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_UID, $UID ,Criteria::EQUAL);
	}else{
	   $CC->addOr($oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_UID, $UID ,Criteria::EQUAL));
	}
}
$oCriteria->add($CC);
$oCriteria->addAnd($oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_UID, '', Criteria::NOT_EQUAL));

$oDataset = AdditionalTablesPeer::doSelectRS ( $oCriteria );
$oDataset->setFetchmode ( ResultSet::FETCHMODE_ASSOC );

$addTables = Array();
while( $oDataset->next() ) {
    $addTables[] = $oDataset->getRow();
}
echo G::json_encode($addTables);