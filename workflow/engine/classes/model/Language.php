<?php
/**
 * Language.php
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

require_once 'classes/model/om/BaseLanguage.php';


/**
 * Skeleton subclass for representing a row from the 'LANGUAGE' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class Language extends BaseLanguage {
  function load($sLanUid) {
    try {
      $oRow = LanguagePeer::retrieveByPK($sLanUid);
      if (!is_null($oRow)) {
        $aFields = $oRow->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
        $this->setNew(false);
        return $aFields;
      }
      else {
        throw(new Exception('This row doesn\'t exists!'));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function update($aFields) {
    $oConnection = Propel::getConnection(LanguagePeer::DATABASE_NAME);
    try {
      $oConnection->begin();
      $this->load($aFields['LAN_ID']);
      $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
      if ($this->validate()) {
        $iResult = $this->save();
        $oConnection->commit();
        return $iResult;
      }
      else {
        $oConnection->rollback();
        throw(new Exception('Failed Validation in class ' . get_class($this) . '.'));
      }
    }
    catch(Exception $e) {
      $oConnection->rollback();
      throw($e);
    }
  }
  //SELECT LAN_ID, LAN_NAME FROM LANGUAGE WHERE LAN_ENABLED = '1' ORDER BY LAN_WEIGHT DESC
  function getActiveLanguages(){
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(LanguagePeer::LAN_ID);
    $oCriteria->addSelectColumn(LanguagePeer::LAN_NAME);
    $oCriteria->add(LanguagePeer::LAN_ENABLED , '1');
    $oCriteria->addDescendingOrderByColumn(LanguagePeer::LAN_WEIGHT);
    
    $oDataset = ContentPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    
    $oContent = new Content();
    $rows = Array();
    while ($oDataset->next()) 
      array_push($rows, $oDataset->getRow());
    
    return $rows;
  }
  
  function findById($LAN_ID){
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(LanguagePeer::LAN_NAME);
    $oCriteria->add(LanguagePeer::LAN_ID, $LAN_ID);
    $oDataset = LanguagePeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    return $oDataset->getRow();
  }
} // Language
