<?php
/**
 * dynaforms_Save.php
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
if (($RBAC_Response=$RBAC->userCanAccess("PM_FACTORY"))!=1) return $RBAC_Response;
  //G::genericForceLogin( 'WF_MYINFO' , 'login/noViewPage', $urlLogin = 'login/login' );

  require_once('classes/model/Dynaform.php');

  $dynaform = new dynaform();
  $oJSON = new Services_JSON();

  if(isset($_POST['form']))
  {
      $aData = $_POST['form'];             //For old process map form
       if ($aData['DYN_UID']==='') unset($aData['DYN_UID']);
  }
  else
  {
      $aData = $_POST;                    //For Extjs (Since we are not using form in ExtJS)
       if(isset($aData['FIELDS']))
       {
           //$test = '{"1":{"TESTID":"1223","PRO_VARIABLE":"saaa"},"2":{"TESTID":"420","PRO_VARIABLE":"sas"}}';
           $oData = $oJSON->decode($_POST['FIELDS']);
           $aData['FIELDS'] = '';
           for($i=0;$i<count($oData);$i++)
           {
                $aData['FIELDS'][$i+1] = (array)$oData[$i];
           }
       }
  }
  //if ($aData['DYN_UID']==='') unset($aData['DYN_UID']);

  if (isset($aData['DYN_UID']))
  {
    $dynaform->Save( $aData );
  }
  else
  {
    if (!isset($aData['ADD_TABLE'])||$aData['ADD_TABLE']==""){
        $aFields=$dynaform->create( $aData );
    } else {
        $aFields=$dynaform->createFromPMTable( $aData, $aData['ADD_TABLE']);
    }
    $aData['DYN_UID']=$dynaform->getDynUid();
    $dynaform->update( $aData );
  }
  echo $dynaform->getDynUid();
?>