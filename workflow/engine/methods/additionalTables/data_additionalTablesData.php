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
$config = $co->getConfiguration('additionalTablesData', 'pageSize','',$_SESSION['USER_LOGGED']);
$limit_size = isset($config['pageSize']) ? $config['pageSize'] : 20;
$start   = isset($_REQUEST['start'])  ? $_REQUEST['start'] : 0;
$limit   = isset($_REQUEST['limit'])  ? $_REQUEST['limit'] : $limit_size; 

$oAdditionalTables = new AdditionalTables();
$oAdditionalTables->createXmlList($_GET['sUID']);

$ocaux = $oAdditionalTables->getDataCriteria($_GET['sUID']);
$rsc = AdditionalTablesPeer::doSelectRS($ocaux);
$rsc->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$total_rows = 0;
while ($rsc->next()){
	$total_rows++;
}

$ocaux1 = $oAdditionalTables->getDataCriteria($_GET['sUID']);
$ocaux1->setLimit($limit);
$ocaux1->setOffset($start);

$rs = AdditionalTablesPeer::DoSelectRs ($ocaux1);
$rs->setFetchmode (ResultSet::FETCHMODE_ASSOC);

$rows = Array();
while($rs->next()){
	$rows[] = $rs->getRow();
}
echo '{rows: '.G::json_encode($rows).', total_rows: '.$total_rows.'}';