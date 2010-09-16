<?php
/**
 * class.headPublisher.php
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
 * Class headPublisher
 * @author David S. Callizaya S. <davidsantos@colosa.com>
 * @package gulliver.system
 * @access public
 * @dependenciesnone
 */
class headPublisher
{
  static private $instance = NULL;
  var $maborakFiles = array();
  var $maborakLoaderFiles = array();
  var $scriptFiles  = array();
  var $leimnudLoad  = array();

  var $leimnudInitString = '  var leimnud = new maborak();
  leimnud.make({
    zip:true,
    inGulliver:true,
    modules :"dom,abbr,rpc,drag,drop,app,panel,fx,grid,xmlform,validator,dashboard",
    files :""
  });';
  var $headerScript = '
  try{  
    leimnud.exec(leimnud.fix.memoryLeak);
    if(leimnud.browser.isIphone){
      leimnud.iphone.make();
    }
  }catch(e){}';
  var $disableHeaderScripts = false;
  var $title='';

  /**
   * Function headPublisher
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */

  private function __construct() {
    $this->addScriptFile("/js/maborak/core/maborak.js");
  }

  function &getSingleton() {
    if (self::$instance == NULL) {
      self::$instance = new headPublisher();
    }
    return self::$instance;
  }


  /**
   * Function setTitle
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string url
   * @parameter string LoadType
   * @return string
   */
  function setTitle( $title )
  {
    $this->title = $title;
  }

  /**
   * Function addMaborakFile
   * @access public
   * @parameter string filename
   * @parameter string loader;   false -> maborak files, true maborak.loader
   * @return string
   */
  function addMaborakFile( $filename, $loader = false)
  {
    if ( $loader )
      $this->maborakLoaderFiles[] = $filename;
    else
      $this->maborakFiles[] = $filename;
  }

  /**
   * Function addScriptFile
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string url
   * @parameter string LoadType
   * @return string
   */
  function addScriptFile($url, $LoadType=1)
  {
    if ($LoadType==1) $this->scriptFiles[$url]=$url;
    if ($LoadType==2) $this->leimnudLoad[$url]=$url;
  }

  /**
   * Function addInstanceModule
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string instance
   * @parameter string module
   * @return string
   */

  function addInstanceModule( $instance , $module )
  {
    $this->headerScript .= "leimnud.Package.Load('".$module."',{Instance:".$instance.",Type:'module'});\n";
  }

  /**
   * Function addClassModule
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string class
   * @parameter string module
   * @return string
   */
  function addClassModule( $class , $module )
  {
    $this->headerScript .= "leimnud.Package.Load('".$module."',{Class:".$class.",Type:'module'});\n";
  }

  /**
   * Function addScriptCode
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string script
   * @return string
   */
  function addScriptCode( $script )
  {
    $this->headerScript .= $script;
  }

  /**
   * Function printHeader
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  function printHeader()
  {
    $jslabel = 'labels/en.js';
    if ( defined( 'SYS_LANG' ) )  {
      $jslabel = 'labels' . PATH_SEP . SYS_LANG . '.js';
      if ( ! file_exists( PATH_CORE . 'js' . PATH_SEP . $jslabel ) )
        $jslabel = 'labels/en.js';
    }
    if ( file_exists( PATH_CORE . 'js' . PATH_SEP . $jslabel ) ) {
      $this->addScriptFile( '/jscore/' . $jslabel , 1 );
    }
    if ($this->disableHeaderScripts) return '';
    $this->addScriptFile("/js/widgets/jscalendar/lang/calendar-".SYS_LANG.".js");
  /*$this->addScriptFile("/js/widgets/calendar/pmcalendar.js");
  /*$this->addScriptFile("/js/widgets/calendar/dhtmlSuite-common.js");
  $this->addScriptFile("/js/widgets/calendar/dhtmlSuite-calendar.js");
  $this->addScriptFile("/js/widgets/calendar/dhtmlSuite-dragDropSimple.js");
  $this->addScriptFile("/js/widgets/calendar/neyek-abstractionCalendar.js");
  */
  
  
    $head = '';
    $head .= '<TITLE>'.$this->title . "</TITLE>\n";
    foreach($this->scriptFiles as $file)
      $head .= "<script type='text/javascript' src='" . $file . "'></script>\n";
    $head .= "<script type='text/javascript'>\n";
    $head .= $this->leimnudInitString;
    foreach($this->leimnudLoad as $file)
      $head .= "  leimnud.Package.Load(false, {Type: 'file', Path: '".$file."', Absolute : true});\n";
    $head .= $this->headerScript;
    $head .= "</script>\n";
    return $head;
  }

  /**
   * Function printRawHeader
   * Its prupose is to load el HEADs initialization javascript
   * into a single SCRIPT tag, it is usefull when it is needed
   * to load a page by leimnud floating panel of by another ajax
   * method. (See also RAW skin)
   *
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  function printRawHeader(){
    $jslabel = '/jscore/labels/en.js';
    if ( defined( 'SYS_LANG' ) )  {
      $jslabel1 = 'labels' . PATH_SEP . SYS_LANG . '.js';
      if ( ! file_exists( PATH_CORE . 'js' . PATH_SEP . $jslabel1 ) )
        $jslabel = '/jscore/labels/en.js';
    }
    $head = '';
    //$head .= "<script language='javascript'>\n";
    foreach($this->scriptFiles as $file) {
      if (($file != "/js/maborak/core/maborak.js") && ($file != $jslabel)) {
        $head .= "  eval(ajax_function('".$file."','',''));\n";
      }
    }
    foreach($this->leimnudLoad as $file)
      $head .= "  eval(ajax_function('".$file."','',''));\n";
    //Adapts the add events on load to simple javascript sentences.
    $this->headerScript = preg_replace('/\s*leimnud.event.add\s*\(\s*window\s*,\s*(?:\'|")load(?:\'|")\s*,\s*function\(\)\{(.+)\}\s*\)\s*;?/', '$1', $this->headerScript);
    $head .= $this->headerScript;
    //$head .= "</script>\n";
    return $head;
  }

  /**
   * Function clearScripts
   * Its prupose is to clear all the scripts of the header.
   *
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  function clearScripts(){
    $this->scriptFiles  = array();
    $this->leimnudLoad  = array();
    $this->leimnudInitString = '';
    $this->headerScript = '';
  }
}
