<?php
/**
 * processCategory_Ajax.php
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 */

if (isset( $_REQUEST['action'] )) {
    switch ($_REQUEST['action']) {
        case 'processCategoryList':
            require_once 'classes/model/ProcessCategory.php';
            require_once 'classes/model/Process.php';
            G::LoadClass( 'configuration' );
            $co = new Configurations();
            $config = $co->getConfiguration( 'processCategoryList', 'pageSize', '', $_SESSION['USER_LOGGED'] );
            $limit_size = isset( $config['pageSize'] ) ? $config['pageSize'] : 20;

            $start = isset( $_POST['start'] ) ? $_POST['start'] : 0;
            $limit = isset( $_POST['limit'] ) ? $_POST['limit'] : $limit_size;
            $filter = isset( $_REQUEST['textFilter'] ) ? $_REQUEST['textFilter'] : '';
            $dir = isset( $_POST['dir'] ) ? $_POST['dir'] : 'ASC';
            $sort = isset( $_POST['sort'] ) ? $_POST['sort'] : 'CATEGORY_NAME';

            $oCriteria = new Criteria( 'workflow' );
            $oCriteria->addSelectColumn( 'COUNT(*) AS CNT' );
            $oCriteria->add( ProcessCategoryPeer::CATEGORY_UID, '', Criteria::NOT_EQUAL );
            if ($filter != '') {
                $oCriteria->add( ProcessCategoryPeer::CATEGORY_NAME, '%' . $filter . '%', Criteria::LIKE );
            }
            $oDat = ProcessCategoryPeer::doSelectRS( $oCriteria );
            $oDat->setFetchmode( ResultSet::FETCHMODE_ASSOC );
            $oDat->next();
            $row = $oDat->getRow();
            $total_categories = $row['CNT'];

            $oCriteria->clear();
            $oCriteria->addSelectColumn( ProcessCategoryPeer::CATEGORY_UID );
            $oCriteria->addSelectColumn( ProcessCategoryPeer::CATEGORY_NAME );
            $oCriteria->add( ProcessCategoryPeer::CATEGORY_UID, '', Criteria::NOT_EQUAL );
            if ($filter != '') {
                $oCriteria->add( ProcessCategoryPeer::CATEGORY_NAME, '%' . $filter . '%', Criteria::LIKE );
            }
            
            if ($dir == "DESC") {
                $oCriteria->addDescendingOrderByColumn($sort);
            } else {
                $oCriteria->addAscendingOrderByColumn($sort);
            }

            $oCriteria->setLimit( $limit );
            $oCriteria->setOffset( $start );
            $oDataset = ProcessCategoryPeer::doSelectRS( $oCriteria );
            $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );

            $proc = new Process();
            $aProcess = $proc->getAllProcessesByCategory();
            $result = "";
            $aCat = array ();
            while ($oDataset->next()) {
                $aCat[] = $oDataset->getRow();
                $index = sizeof( $aCat ) - 1;
                $aCat[$index]['TOTAL_PROCESSES'] = isset( $aProcess[$aCat[$index]['CATEGORY_UID']] ) ? $aProcess[$aCat[$index]['CATEGORY_UID']] : 0;
            }
            $result['data'] = $aCat;
            $result['totalCount'] = $total_categories;
            echo G::json_encode( $result );
            break;
        case 'updatePageSize':
            G::LoadClass( 'configuration' );
            $c = new Configurations();
            $arr['pageSize'] = $_REQUEST['size'];
            $arr['dateSave'] = date( 'Y-m-d H:i:s' );
            $config = Array ();
            $config[] = $arr;
            $c->aConfig = $config;
            $c->saveConfig( 'processCategoryList', 'pageSize', '', $_SESSION['USER_LOGGED'] );
            echo '{success: true}';
            break;
        case 'checkCategoryName':
            require_once 'classes/model/ProcessCategory.php';
            $catName = $_REQUEST['cat_name'];
            $oCriteria = new Criteria( 'workflow' );
            $oCriteria->addSelectColumn( ProcessCategoryPeer::CATEGORY_NAME );
            $oCriteria->add( ProcessCategoryPeer::CATEGORY_NAME, $catName );
            $oDataset = ProcessCategoryPeer::doSelectRS( $oCriteria );
            $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
            $oDataset->next();
            $row = $oDataset->getRow();
            $response = isset( $row['CATEGORY_NAME'] ) ? 'false' : 'true';
            echo $response;
            break;
        case 'saveNewCategory':
            try {
                require_once 'classes/model/ProcessCategory.php';
                $catName = trim( $_REQUEST['category'] );
                $pcat = new ProcessCategory();
                $pcat->setNew( true );
                $pcat->setCategoryUid( G::GenerateUniqueID() );
                $pcat->setCategoryName( $catName );
                $pcat->save();
                G::auditLog("CreateCategory", "Category Name: ".$catName);
                echo '{success: true}';
            } catch (Exception $ex) {
                echo '{success: false, error: ' . $ex->getMessage() . '}';
            }
            break;
        case 'checkEditCategoryName':
            require_once 'classes/model/ProcessCategory.php';
            $catUID = $_REQUEST['cat_uid'];
            $catName = $_REQUEST['cat_name'];
            $oCriteria = new Criteria( 'workflow' );
            $oCriteria->addSelectColumn( ProcessCategoryPeer::CATEGORY_NAME );
            $oCriteria->add( ProcessCategoryPeer::CATEGORY_NAME, $catName );
            $oCriteria->add( ProcessCategoryPeer::CATEGORY_UID, $catUID, Criteria::NOT_EQUAL );
            $oDataset = ProcessCategoryPeer::doSelectRS( $oCriteria );
            $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
            $oDataset->next();
            $row = $oDataset->getRow();
            $response = isset( $row['CATEGORY_NAME'] ) ? 'false' : 'true';
            echo $response;
            break;
        case 'updateCategory':
            try {
                require_once 'classes/model/ProcessCategory.php';
                $catUID = $_REQUEST['cat_uid'];
                $catName = trim( $_REQUEST['category'] );
                $pcat = new ProcessCategory();
                $pcat->setNew( false );
                $pcat->setCategoryUid( $catUID );
                $pcat->setCategoryName( $catName );
                $pcat->save();
                g::auditLog("UpdateCategory", "Category Name: ".$catName." Category ID: (".$catUID.") ");
                echo '{success: true}';
            } catch (Exception $ex) {
                echo '{success: false, error: ' . $ex->getMessage() . '}';
            }
            break;
        case 'canDeleteCategory':
            require_once 'classes/model/Process.php';
            $proc = new Process();
            $aProcess = $proc->getAllProcessesByCategory();
            $catUID = $_REQUEST['CAT_UID'];
            $response = isset( $aProcess[$catUID] ) ? 'false' : 'true';
            echo $response;
            break;
        case 'deleteCategory':
            try {
                require_once 'classes/model/ProcessCategory.php';
                $catUID = $_REQUEST['cat_uid'];
                $cat = new ProcessCategory();
                $cat->setCategoryUid( $catUID );
                $catName = $cat->loadByCategoryId( $catUID );
                $cat->delete();
                G::auditLog("DeleteCategory", "Category Name: ".$catName." Category ID: (".$catUID.") ");
                echo '{success: true}';
            } catch (Exception $ex) {
                echo '{success: false, error: ' . $ex->getMessage() . '}';
            }
            break;
        default:
            echo 'default';
    }
}