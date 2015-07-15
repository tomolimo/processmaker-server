<?php

/**

 * triggers_Save.php

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



if (($RBAC_Response = $RBAC->userCanAccess( "PM_FACTORY" )) != 1) {

    return $RBAC_Response;

}

require_once ('classes/model/Triggers.php');

require_once ('classes/model/Content.php');



if (isset( $_POST['function'] )) {

    $sfunction = $_POST['function']; //for old processmap

} elseif (isset( $_POST['functions'] )) {

    $sfunction = $_POST['functions']; //for extjs

}



if (isset( $sfunction ) && $sfunction == 'lookforNameTrigger') {

    $snameTrigger = urldecode( $_POST['NAMETRIGGER'] );

    $sPRO_UID = urldecode( $_POST['proUid'] );



    $oCriteria = new Criteria( 'workflow' );

    $oCriteria->addSelectColumn( TriggersPeer::TRI_UID );

    $oCriteria->add( TriggersPeer::PRO_UID, $sPRO_UID );

    $oDataset = TriggersPeer::doSelectRS( $oCriteria );

    $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );

    $flag = true;

    while ($oDataset->next() && $flag) {

        $aRow = $oDataset->getRow();



        $oCriteria1 = new Criteria( 'workflow' );

        $oCriteria1->addSelectColumn( 'COUNT(*) AS TRIGGERS' );

        $oCriteria1->add( ContentPeer::CON_CATEGORY, 'TRI_TITLE' );

        $oCriteria1->add( ContentPeer::CON_ID, $aRow['TRI_UID'] );

        $oCriteria1->add( ContentPeer::CON_VALUE, $snameTrigger );

        $oCriteria1->add( ContentPeer::CON_LANG, SYS_LANG );

        $oDataset1 = ContentPeer::doSelectRS( $oCriteria1 );

        $oDataset1->setFetchmode( ResultSet::FETCHMODE_ASSOC );

        $oDataset1->next();

        $aRow1 = $oDataset1->getRow();



        if ($aRow1['TRIGGERS']) {

            $flag = false;

        }



    }



    echo $flag;

} else {

    G::LoadClass("processMap");



    $response = array();



    try {

        $oTrigger = new Triggers();



        $oProcessMap = new processMap( new DBConnection() );

        if (isset( $_POST['form'] )) {

            $value = $_POST['form'];

        } else {

            $value = $_POST;

        }



        /*----------------------------------********---------------------------------*/



        $swCreate = true;

        if ($value['TRI_UID'] != '') {

            $oTrigger->load( $value['TRI_UID'] );

        } else {

            $oTrigger->create( $value );

            $value['TRI_UID'] = $oTrigger->getTriUid();

            $swCreate = false;

        }

        $oTrigger->update( $value );

        if($swCreate){

            //Add Audit Log

            $fields = $oTrigger->load( $value['TRI_UID'] );

            $description = "Trigger Name: ".$fields['TRI_TITLE'].", Trigger Uid: ".$value['TRI_UID'];

            if (isset ( $fields['TRI_DESCRIPTION'] )) {

               $description .= ", Description: ".$fields['TRI_DESCRIPTION'];

            }

            if (isset($value["TRI_WEBBOT"])) {

              $description .= ", [EDIT CODE]";

            }

            G::auditLog("UpdateTrigger", $description);

        }



        //if (! isset( $_POST['mode'] )) {

        //    $oProcessMap->triggersList( $value['PRO_UID'] );

        //}



        $response["success"] = true;

        $response["msg"] = G::LoadTranslation("ID_TRIGGERS_SAVED");

    } catch (Exception $e) {

        $response["success"] = false;

        $response["msg"] = $e->getMessage();

    }



    echo G::json_encode($response);

}


