<?php
/**
 * class.configuration.php
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
//
// It works with the table CONFIGURATION in a WF dataBase
//
// Copyright (C) 2007 COLOSA
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

/**
 * ProcessConfiguration - ProcessConfiguration class
 * @package ProcessMaker
 * @author  David S. Callizaya S.
 * @copyright 2007 COLOSA
 */

require_once 'classes/model/Configuration.php';


/**
 * Extends Configuration
 *
 *
 * @copyright  2007 COLOSA
 * @version    Release: @package_version@
 */ 
class Configurations // extends Configuration
{
  var $aConfig           = array();
  private $Configuration = null;
  
  /**
   * Set Configurations
   * @return void
   */ 
  function Configurations()
  {
    $this->Configuration = new Configuration();
  }
  
  /**
   * arrayClone
   *
   * @param  array &$object      Source array
   * @param  array &$cloneObject Array duplicate
   * @return void
   */ 
  function arrayClone( &$object, &$cloneObject )
  {
    if (is_array($object)) {
      foreach($object as $k => $v ) {
        $cloneObject[$k] = NULL;
        $this->arrayClone( $object[$k], $cloneObject[$k] );
      }
    } else {
        if (is_object($object)) {
        } else {
          $cloneObject=NULL;
        }
    }
  }
  
  /**
   * configObject
   *
   * @param  object   &$object  
   * @param  array    &$from
   * @return void
   */ 
  function configObject( &$object, &$from )
  {
    if (!(is_object($object) || is_array($object))) 
      return;
    if (!isset($from)) 
      $from = &$this->aConfig;
    foreach($from as $k => $v ) {
      if (isset($v) && array_key_exists($k,$object)) {
        if (is_object($v)) 
          throw new Exception( 'Object is not permited inside configuration array.' );
        if (is_object($object)) {
          if (is_array($v)) 
            $this->configObject($object->{$k}, $v);
          else 
            $object->{$k} = $v;
        } else { 
          if (is_array($object)) {
            if (is_array($v)) 
              $this->configObject($object[$k], $v);
            else 
              $object[$k] = $v;
          }
        }
      }
    }
  }
  
  /**
   * loadConfig
   *
   * @param  object   &$object  
   * @param  string   $cfg 
   * @param  object   $obj 
   * @param  string   $pro 
   * @param  string   $usr 
   * @param  string   $app 
   * @return void
   */ 
  function loadConfig(&$object, $cfg, $obj, $pro = '', $usr = '', $app = '')
  {
    $this->Fields = array();
    if ($this->Configuration->exists( $cfg, $obj, $pro, $usr, $app ))
      $this->Fields = $this->Configuration->load( $cfg, $obj, $pro, $usr, $app );
    $aConfig = $this->aConfig;
    if (isset($this->Fields['CFG_VALUE']))
      $aConfig = unserialize($this->Fields['CFG_VALUE']);
    if (!is_array($aConfig)) 
      $aConfig = $this->aConfig;
    $this->aConfig = $aConfig;
    $this->configObject($object,$this->aConfig);
  }
  
  /**
   * saveConfig
   *
   * @param  object   &$object  
   * @param  array    &$from
   * @return void
   */   
  function saveConfig($cfg,$obj,$pro='',$usr='',$app='')
  {
    $aFields = array(
      'CFG_UID'   => $cfg,
      'OBJ_UID'   => $obj,
      'PRO_UID'   => $pro,
      'USR_UID'   => $usr,
      'APP_UID'   => $app,
      'CFG_VALUE' => serialize($this->aConfig)
    );
    if ($this->Configuration->exists($cfg,$obj,$pro,$usr,$app)) {
      $this->Configuration->update($aFields);
    } else {
      $this->Configuration->create($aFields);
      $this->Configuration->update($aFields);
    }
  }
  
  /**
   * saveObject
   *
   * @param  object   &$object  
   * @param  array    &$from
   * @return void
   */   
  function saveObject(&$object,$cfg,$obj,$pro='',$usr='',$app='')
  {
    $aFields = array(
      'CFG_UID'   => $cfg,
      'OBJ_UID'   => $obj,
      'PRO_UID'   => $pro,
      'USR_UID'   => $usr,
      'APP_UID'   => $app,
      'CFG_VALUE' => serialize(array(&$object))
    );
    if ($this->Configuration->exists($cfg,$obj,$pro,$usr,$app)) {
      $this->Configuration->update($aFields);
    } else {
      $this->Configuration->create($aFields);
      $this->Configuration->update($aFields);
    }
  }

  /**
   * loadObject
   * this function is deprecated, we dont know why return an object, use the function getConfiguration below
   *
   * @param  string    $cfg  
   * @param  object    $obj
   * @param  string    $pro
   * @param  string    $usr
   * @param  string    $app
   * @return void
   */   
  function loadObject($cfg, $obj, $pro = '', $usr = '', $app = '')
  {
    $objectContainer=array((object) array());
    $this->Fields = array();
    if ($this->Configuration->exists( $cfg, $obj, $pro, $usr, $app ))
      $this->Fields = $this->Configuration->load( $cfg, $obj, $pro, $usr, $app );
    else
      return $objectContainer[0]; 
      
    if (isset($this->Fields['CFG_VALUE']))
      $objectContainer = unserialize($this->Fields['CFG_VALUE']);
    if (!is_array($objectContainer)||sizeof($objectContainer)!=1) 
      return (object) array();
    else
      return $objectContainer[0];
  }
  
  /**
   * getConfiguration
   *
   * @param  string    $cfg  
   * @param  object    $obj
   * @param  string    $pro
   * @param  string    $usr
   * @param  string    $app
   * @return void
   */   
  function getConfiguration($cfg, $obj, $pro = '', $usr = '', $app = '')
  {
    try {
      $oCfg = ConfigurationPeer::retrieveByPK( $cfg, $obj, $pro, $usr, $app );
      if (!is_null($oCfg)) {
        $row  = $oCfg->toArray(BasePeer::TYPE_FIELDNAME);
        $result = unserialize($row['CFG_VALUE']);
        if ( is_array($result) && sizeof($result)==1 ) {
          return $result[0];
        } else {
          return $result;
        }
      }
      else {
        return null;
      }
    }
    catch (Exception $oError) {
      return null;
    }
  }
    
  /**
   * setConfig
   *
   * @param  string   $route  
   * @param  object   &$object
   * @param  object   &$to
   * @return void
   */   
  function setConfig( $route , &$object , &$to )
  {
    if (!isset($to)) 
      $to = &$this->aConfig;
    $routes = explode(',',$route);
    foreach($routes as $r) {
      $ro = explode('/',$r);
      if (count($ro)>1) {
        $rou = $ro;
        unset($rou[0]);
        if ($ro[0]==='*') {
          foreach($object as $k => $v ) {
            if (is_object($object)) {
              if (!isset($to[$k]))
                $to[$k] = array();
              $this->setConfig(implode('/',$rou),$object->{$k},$to[$k]);
            } else {
              if (is_array($object)) {
                if (!isset($to[$k]))
                  $to[$k] = array();
                $this->setConfig(implode('/',$rou),$object[$k],$to[$k]);
              }
            }
          }
        } else {
          if (is_object($object)) {
            if (!isset($to[$ro[0]])) 
              $to[$ro[0]] = array();
            $this->setConfig(implode('/',$rou),$object->{$ro[0]},$to[$ro[0]]);
          } else {
            if (is_array($object)) {
              if (!isset($to[$ro[0]])) 
                $to[$ro[0]] = array();
              $this->setConfig(implode('/',$rou),$object[$ro[0]],$to[$ro[0]]);
            } else {
              $to = $object;
            }
          } 

        }
      } else {
        if ($ro[0]==='*') {
          foreach($object as $k => $v ) {
            if (is_object($object)) {
              if (!isset($to[$k]))
                $to[$k] = array();
              $to[$k] = $object->{$k};
            } else {
              if (is_array($object)) {
                if (!isset($to[$k]))
                  $to[$k] = array();
                $to[$k] = $object[$k];
              }
            }
          }
        } else {
          if (!isset($to[$r]))
            $to[$r] = array();
          if (is_object($object)) {
            $to[$r] = $object->{$r};
          } elseif (is_array($object)) {
            $to[$r] = $object[$r];
          } else {
            $to[$r] = $object;
          }
        }
      }
    }
  }
}
?>