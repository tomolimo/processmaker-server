<?php
/**
 * Groupwf.php
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

require_once 'classes/model/om/BaseGroupwf.php';
require_once 'classes/model/Content.php';

/**
 * Skeleton subclass for representing a row from the 'GROUPWF' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    workflow.classes.model
 */
class Groupwf extends BaseGroupwf {
  /**
   * This value goes in the content table
   * @var        string
   */
  protected $grp_title = '';

  /**
   * Get the [grp_title] column value.
   * @return     string
   */
  public function getGrpTitle()
  {
    if ( $this->getGrpUid() == '' ) {
      throw ( new Exception( "Error in getGrpTitle, the GRP_UID can't be blank") );
    }
    $lang = defined ( 'SYS_LANG') ? SYS_LANG : 'en';
    $this->grp_title = Content::load ( 'GRP_TITLE', '', $this->getGrpUid(), $lang );
    return $this->grp_title;
  }

  /**
   * Set the [grp_title] column value.
   * 
   * @param      string $v new value
   * @return     void
   */
  public function setGrpTitle($v)
  {
    if ( $this->getGrpUid() == '' ) {
      throw ( new Exception( "Error in setGrpTitle, the GRP_UID can't be blank") );
    }
    // Since the native PHP type for this column is string,
    // we will cast the input to a string (if it is not).
    if ($v !== null && !is_string($v)) {
      $v = (string) $v; 
    }

    if ($this->grp_title !== $v || $v === '') {
      $this->grp_title = $v;
      $lang = defined ( 'SYS_LANG') ? SYS_LANG : 'en';
      $res = Content::addContent( 'GRP_TITLE', '', $this->getGrpUid(), $lang, $this->grp_title );
    }

  } // set()

  /**
   * Creates the Group
   * 
   * @param      array $aData  $oData is not necessary
   * @return     void
   */
  
  function create ($aData ) {
    //$oData is not necessary
    $con = Propel::getConnection( GroupwfPeer::DATABASE_NAME );
    try {
      if ( isset ( $aData['GRP_UID'] ) ) 
        $this->setGrpUid ( $aData['GRP_UID'] );
      else 
        $this->setGrpUid ( G::generateUniqueID() );
        
      if ( isset ( $aData['GRP_STATUS'] ) ) 
        $this->setGrpStatus ( $aData['GRP_STATUS'] );
      else 
        $this->setGrpStatus       ( 'ACTIVE' );
  
      if ( $this->validate() ) {
        $con->begin(); 
        $res = $this->save();
       
        if (isset ( $aData['GRP_TITLE'] ) )
          $this->setGrpTitle (  $aData['GRP_TITLE'] );
        else
          $this->setGrpTitle (  'Default Group Title' );
          
        $con->commit(); 
        return $this->getGrpUid();
      }
      else {
       $msg = '';
       foreach($this->getValidationFailures() as $objValidationFailure) 
         $msg .= $objValidationFailure->getMessage() . "<br/>";
         
       throw ( new PropelException ( 'The row cannot be created!', new PropelException ( $msg ) ) );      
      }

    }
    catch (Exception $e) {
      $con->rollback(); 
      throw ($e);
    }
  }

  /**
   * Load the Process row specified in [grp_id] column value.
   * 
   * @param      string $ProUid   the uid of the Prolication 
   * @return     array  $Fields   the fields 
   */
  
  function Load ( $ProUid ) {
    $con = Propel::getConnection(GroupwfPeer::DATABASE_NAME);
    try {
      $oPro = GroupwfPeer::retrieveByPk( $ProUid );
      if (is_object($oPro) && get_class ($oPro) == 'Groupwf' ) {
        $aFields = $oPro->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray ($aFields, BasePeer::TYPE_FIELDNAME );
        $aFields['GRP_TITLE']       = $oPro->getGrpTitle();
        $this->setGrpTitle (  $oPro->getGrpTitle() );
        return $aFields;
      }
      else {
        throw(new Exception( "The row '$ProUid' in table Group doesn't exist!" ));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  /**
   * Update the Group row
   * @param     array $aData
   * @return    variant
  **/
  
  public function update($aData)
  {
    $con = Propel::getConnection( GroupwfPeer::DATABASE_NAME );
    try {
      $con->begin(); 
      $oPro = GroupwfPeer::retrieveByPK( $aData['GRP_UID'] );
      if (is_object($oPro) && get_class ($oPro) == 'Groupwf' ) {
        $oPro->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
        if ($oPro->validate()) {
          if ( isset ( $aData['GRP_TITLE'] ) )
            $oPro->setGrpTitle( $aData['GRP_TITLE'] );
          $res = $oPro->save();
          $con->commit(); 
          return $res;
        }
        else {
         $msg = '';
         foreach($this->getValidationFailures() as $objValidationFailure) 
           $msg .= $objValidationFailure->getMessage() . "<br/>";
         
         throw ( new PropelException ( 'The row cannot be created!', new PropelException ( $msg ) ) );
        }
      }
      else {
        $con->rollback(); 
        throw(new Exception( "The row '" . $aData['GRP_UID'] . "' in table Group doesn't exist!" ));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  /**
   * Remove the Prolication document registry
   * @param     array $aData or string $ProUid 
   * @return    string
  **/
  public function remove($ProUid)
  {
    if ( is_array ( $ProUid ) ) {
      $ProUid = ( isset ( $ProUid['GRP_UID'] ) ? $ProUid['GRP_UID'] : '' );
    }
    try {
      $oPro = GroupwfPeer::retrieveByPK( $ProUid );
      if (!is_null($oPro))
      {
        Content::removeContent('GRP_TITLE', '',       $oPro->getGrpUid());
        Content::removeContent('GRP_DESCRIPTION', '', $oPro->getGrpUid());
        return $oPro->delete();
      }
      else {
        throw(new Exception( "The row '$ProUid' in table Group doesn't exist!" ));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }
  
  /**
   * verify if row specified in [GrpUid] exists.
   *
   * @param      string $sProUid   the uid of the Prolication
   */

  function GroupwfExists ( $GrpUid ) {
    $con = Propel::getConnection(GroupwfPeer::DATABASE_NAME);
    try {
      $oPro = GroupwfPeer::retrieveByPk( $GrpUid );
      if (is_object($oPro) && get_class ($oPro) == 'Groupwf' ) {
        return true;
      }
      else {
        return false;
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }
  
  function loadByGroupname ( $Groupname ) {       
    $c = new Criteria('workflow');    
    $del = DBAdapter::getStringDelimiter();

    $c->clearSelectColumns();
    $c->addSelectColumn( ContentPeer::CON_CATEGORY );
    $c->addSelectColumn( ContentPeer::CON_VALUE );
                    
    $c->add(ContentPeer::CON_CATEGORY,  'GRP_TITLE');
    $c->add(ContentPeer::CON_VALUE,  $Groupname);
    $c->add(ContentPeer::CON_LANG,  SYS_LANG );          
    return $c;   
  }
  
} // Groupwf
