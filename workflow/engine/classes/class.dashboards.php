<?php
/**
 * class.dashboards.php
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

/**
 * Dashboards - Dashboard class
 * @package ProcessMaker
 * @author Julio Cesar Laura Avendaño
 * @copyright 2007 COLOSA
 */

class Dashboards 
{
 /**
  * This function is used for get configuration of the dashboards
  * @param string $sUserUID
  * @return array configuration of the dashboard
  */
  function getConfiguration($sUserUID) 
  {
    require_once 'classes/model/Configuration.php';
    $oConfiguration = new Configuration();
    $sDelimiter     = DBAdapter::getStringDelimiter();
    $oCriteria      = new Criteria('workflow');
    $oCriteria->add(ConfigurationPeer::CFG_UID, 'Dashboards');
    $oCriteria->add(ConfigurationPeer::OBJ_UID, '');
    $oCriteria->add(ConfigurationPeer::PRO_UID, '');
    $oCriteria->add(ConfigurationPeer::USR_UID, $sUserUID);
    $oCriteria->add(ConfigurationPeer::APP_UID, '');
    if (ConfigurationPeer::doCount($oCriteria) == 0) {
      $oConfiguration->create(array('CFG_UID' => 'Dashboards', 'OBJ_UID' => '', 'CFG_VALUE' => '', 'PRO_UID' => '', 'USR_UID' => $sUserUID, 'APP_UID' => ''));
      $aConfiguration = array();
    }
    else {
      $aConfiguration = $oConfiguration->load('Dashboards', '', '', $sUserUID, '');
      if ($aConfiguration['CFG_VALUE'] != '') {
        $aConfiguration = unserialize($aConfiguration['CFG_VALUE']);
      }
      else {
        $aConfiguration = array();
      }
    }
    return $aConfiguration;
  }
 /**
  * This function is used for save configuration of the dashboards
  * @param string $sUserUID
  * @param array $aConfiguration
  */
  function saveConfiguration($sUserUID, $aConfiguration) 
  {
    require_once 'classes/model/Configuration.php';
    $oConfiguration = new Configuration();
    $oConfiguration->update(array('CFG_UID'   => 'Dashboards',
                                  'OBJ_UID'   => '',
                                  'CFG_VALUE' => serialize($aConfiguration),
                                  'PRO_UID'   => '',
                                  'USR_UID'   => $sUserUID,
                                  'APP_UID'   => ''));
  }
 /**
  * This function is used for get objects of the dashboards
  * @param string $sUserUID
  * @return object Services_JSON
  */
  function getDashboardsObject($sUserUID) 
  {
    $aConfiguration       = $this->getConfiguration($sUserUID);
    $oPluginRegistry      = &PMPluginRegistry::getSingleton();
    $aAvailableDashboards = $oPluginRegistry->getDashboards();
    $aLeftColumn          = array ();
    $aRightColumn         = array ();
    $iColumn              = 0;
    G::LoadClass('report');
    $oReport  = new Report();
    $aReports = $oReport->getAvailableReports();
    foreach ($aConfiguration as $aDashboard) {
      if ($aDashboard['class'] == 'PM_Reports') {
        if (!isset($aDashboard['element'])) $aDashboard['element'] = '';
          foreach ($aReports as $sReport) {
            $bFree = false;
            if (($aDashboard['class'] == 'PM_Reports') && ($aDashboard['element'] == $sReport)) {
              $bFree = true;
            }
            if ($bFree) {
              $oElement          = $aDashboard['object'];
              $oElement->class   = $aDashboard['class'];
              $oElement->type    = $aDashboard['type'];
              $oElement->element = $aDashboard['element'];
              if ($iColumn === 0) {
                $aLeftColumn[] = $oElement;
              }
              else {
                $aRightColumn[] = $oElement;
              }
              $iColumn = 1 - $iColumn;
            }
          }
      }
      else {
        require_once PATH_PLUGINS. $aDashboard['class']  . PATH_SEP . 'class.' . $aDashboard['class'] . '.php';
        $sClassName = $aDashboard['class'] . 'Class';
        $oInstance  = new $sClassName();
        $aCharts    = $oInstance->getAvailableCharts();
        foreach ($aCharts as $sChart) {
          $bFree = false;
          foreach ($aAvailableDashboards as $sDashboardClass) {
            if (($aDashboard['class'] == $sDashboardClass) && ($aDashboard['element'] == $sChart)) {
              $bFree = true;
            }
          }
          if ($bFree) {
            $oElement          = $oInstance->getChart($sChart);
            $oElement->class   = $aDashboard['class'];
            $oElement->type    = $aDashboard['type'];
            $oElement->element = $aDashboard['element'];
            if ($iColumn === 0) {
              $aLeftColumn[] = $oElement;
            }
            else {
              $aRightColumn[] = $oElement;
            }
            $iColumn = 1 - $iColumn;
          }
        }
        if (method_exists($oInstance, 'getAvailablePages')) {
          $aPages = $oInstance->getAvailablePages();
          foreach ($aPages as $sPage) {
            $bFree = false;
            foreach ($aAvailableDashboards as $sDashboardClass) {
              if (($aDashboard['class'] == $sDashboardClass) && ($aDashboard['element'] == $sPage)) {
                $bFree = true;
              }
            }
            if ($bFree) {
              $oElement          = $oInstance->getPage($sPage);
              $oElement->class   = $aDashboard['class'];
              $oElement->type    = $aDashboard['type'];
              $oElement->element = $aDashboard['element'];
              if ($iColumn === 0) {
                $aLeftColumn[] = $oElement;
              }
              else {
                $aRightColumn[] = $oElement;
              }
              $iColumn = 1 - $iColumn;
            }
          }
        }
      }
    }
    $aDashboards = array($aLeftColumn, $aRightColumn);
    $oJSON       = new Services_JSON();
    return $oJSON->encode($aDashboards);
  }
}