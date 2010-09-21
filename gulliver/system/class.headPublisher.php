<?php
/**
 * class.headPublisher.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
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
 * @dependencies none
 */
class headPublisher {
  private static $instance = NULL;
  var $maborakFiles = array ();
  var $maborakLoaderFiles = array ();
  var $scriptFiles = array ();
  var $leimnudLoad = array ();
  
  /* extJsSkin  store the current skin for the ExtJs*/
  var $extJsSkin = '';
  
  /* extJsScript Array, to store the file to be include  */
  var $extJsScript = array ();
  
  /* extJsLibrary Array, to store extended ExtJs lybraries  */
  var $extJsLibrary = array ();
  
  /* extJsContent Array, to store the file to be include in the skin content  */
  var $extJsContent = array ();
  
  /* extVariable array, to store the variables generated in PHP, and used in JavaScript */
  var $extVariable = array ();
  
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
  var $title = '';
  
  /**
   * Function headPublisher
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  
  private function __construct() {
    $this->addScriptFile ( "/js/maborak/core/maborak.js" );
  }
  
  function &getSingleton() {
    if (self::$instance == NULL) {
      self::$instance = new headPublisher ( );
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
  function setTitle($title) {
    $this->title = $title;
  }
  
  /**
   * Function addMaborakFile
   * @access public
   * @parameter string filename
   * @parameter string loader;   false -> maborak files, true maborak.loader
   * @return string
   */
  function addMaborakFile($filename, $loader = false) {
    if ($loader)
      $this->maborakLoaderFiles [] = $filename;
    else
      $this->maborakFiles [] = $filename;
  }
  
  /**
   * Function addScriptFile
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string url
   * @parameter string LoadType
   * @return string
   */
  function addScriptFile($url, $LoadType = 1) {
    if ($LoadType == 1)
      $this->scriptFiles [$url] = $url;
    if ($LoadType == 2)
      $this->leimnudLoad [$url] = $url;
  }
  
  /**
   * Function addInstanceModule
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string instance
   * @parameter string module
   * @return string
   */
  
  function addInstanceModule($instance, $module) {
    $this->headerScript .= "leimnud.Package.Load('" . $module . "',{Instance:" . $instance . ",Type:'module'});\n";
  }
  
  /**
   * Function addClassModule
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string class
   * @parameter string module
   * @return string
   */
  function addClassModule($class, $module) {
    $this->headerScript .= "leimnud.Package.Load('" . $module . "',{Class:" . $class . ",Type:'module'});\n";
  }
  
  /**
   * Function addScriptCode
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string script
   * @return string
   */
  function addScriptCode($script) {
    $this->headerScript .= $script;
  }
  
  /**
   * Function printHeader
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  function printHeader() {
    $jslabel = 'labels/en.js';
    if (defined ( 'SYS_LANG' )) {
      $jslabel = 'labels' . PATH_SEP . SYS_LANG . '.js';
      if (! file_exists ( PATH_CORE . 'js' . PATH_SEP . $jslabel ))
        $jslabel = 'labels/en.js';
    }
    if (file_exists ( PATH_CORE . 'js' . PATH_SEP . $jslabel )) {
      $this->addScriptFile ( '/jscore/' . $jslabel, 1 );
    }
    if ($this->disableHeaderScripts)
      return '';
    $this->addScriptFile ( "/js/widgets/jscalendar/lang/calendar-" . SYS_LANG . ".js" );
    /*$this->addScriptFile("/js/widgets/calendar/pmcalendar.js");
  /*$this->addScriptFile("/js/widgets/calendar/dhtmlSuite-common.js");
  $this->addScriptFile("/js/widgets/calendar/dhtmlSuite-calendar.js");
  $this->addScriptFile("/js/widgets/calendar/dhtmlSuite-dragDropSimple.js");
  $this->addScriptFile("/js/widgets/calendar/neyek-abstractionCalendar.js");
  */
    
    $head = '';
    $head .= '<TITLE>' . $this->title . "</TITLE>\n";
    foreach ( $this->scriptFiles as $file )
      $head .= "<script type='text/javascript' src='" . $file . "'></script>\n";
    $head .= "<script type='text/javascript'>\n";
    $head .= $this->leimnudInitString;
    foreach ( $this->leimnudLoad as $file )
      $head .= "  leimnud.Package.Load(false, {Type: 'file', Path: '" . $file . "', Absolute : true});\n";
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
  function printRawHeader() {
    $jslabel = '/jscore/labels/en.js';
    if (defined ( 'SYS_LANG' )) {
      $jslabel1 = 'labels' . PATH_SEP . SYS_LANG . '.js';
      if (! file_exists ( PATH_CORE . 'js' . PATH_SEP . $jslabel1 ))
        $jslabel = '/jscore/labels/en.js';
    }
    $head = '';
    //$head .= "<script language='javascript'>\n";
    foreach ( $this->scriptFiles as $file ) {
      if (($file != "/js/maborak/core/maborak.js") && ($file != $jslabel)) {
        $head .= "  eval(ajax_function('" . $file . "','',''));\n";
      }
    }
    foreach ( $this->leimnudLoad as $file )
      $head .= "  eval(ajax_function('" . $file . "','',''));\n";
      //Adapts the add events on load to simple javascript sentences.
    $this->headerScript = preg_replace ( '/\s*leimnud.event.add\s*\(\s*window\s*,\s*(?:\'|")load(?:\'|")\s*,\s*function\(\)\{(.+)\}\s*\)\s*;?/', '$1', $this->headerScript );
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
  function clearScripts() {
    $this->scriptFiles = array ();
    $this->leimnudLoad = array ();
    $this->leimnudInitString = '';
    $this->headerScript = '';
  }
  
  /**
   * Function includeExtJs
   * with this function we are using the ExtJs library, this library is not compatible with 
   * previous libraries, for that reason oHeadPublisher will clear previous libraries like maborak
   * we need to check if we need the language file
   * this function returns the header needed to render a page using ExtJs
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function includeExtJs() {
    $this->clearScripts ();
    $head = '';
    $head .= "  <script type='text/javascript' src='/js/ext/ext-base.js'></script>\n";
    $head .= "  <script type='text/javascript' src='/js/ext/ext-all.js'></script>\n";
    
    if (isset ( $this->extJsLibrary ) && is_array ( $this->extJsLibrary )) {
      foreach ( $this->extJsLibrary as $file ) {
        $head .= "  <script type='text/javascript' src='/js/ext/" . $file . ".js'></script>\n";
      }
    }
    
    $head .= "  <script type='text/javascript' src='/js/ext/wz_jsgraphics.js'></script>\n";
    $head .= "  <script type='text/javascript' src='/js/ext/mootools.js'></script>\n";
    $head .= "  <script type='text/javascript' src='/js/ext/moocanvas.js'></script>\n";
    $head .= "  <script type='text/javascript' src='/js/ext/draw2d.js'></script>\n";
    
    if (! isset ( $this->extJsSkin ) || $this->extJsSkin == '') {
      $this->extJsSkin = 'xtheme-gray';
      //$this->extJsSkin = 'gtheme';
    }
    
    $head .= "  <link rel='stylesheet' type='text/css' href='/skins/ext/ext-all-notheme.css' />\n";
    $head .= "  <link rel='stylesheet' type='text/css' href='/skins/ext/" . $this->extJsSkin . ".css' />\n";
    if (file_exists ( PATH_HTML . 'skins' . PATH_SEP . 'ext' . PATH_SEP . 'pmos-' . $this->extJsSkin . '.css' )) {
      $head .= "  <link rel='stylesheet' type='text/css' href='/skins/ext/pmos-" . $this->extJsSkin . ".css' />\n";
    }
    
    if (isset ( $this->extJsScript ) && is_array ( $this->extJsScript )) {
      foreach ( $this->extJsScript as $key => $file ) {
        $head .= "  <script type='text/javascript' src='" . $file . ".js'></script>\n";
      }
    }
    if (count ( $this->extVariable ) > 0) {
      $head .= "<script language='javascript'>\n";
      foreach ( $this->extVariable as $key => $val ) {
        $name = $val ['name'];
        $value = $val ['value'];
        if ($val ['type'] == 'number')
          $head .= "  var $name = $value;\n";
        else
          $head .= "  var $name = '$value';\n";
      }
      $head .= "</script>\n";
    
    }
    return $head;
  }
  
  /**
   * add a ExtJS extended library
   *
   * @author Erik A. Ortiz <erik@colosa.com>
   * @access public
   * @param (String) http js path library
   * @return none
   */
  function usingExtJs($library) {
    if (! is_string ( $library )) {
      throw new Exception ( 'headPublisher::usingExt->ERROR - the parameter should be a js path string' );
    }
    array_push ( $this->extJsLibrary, $library );
  }
  
  /**
   * Function setExtSkin
   * with this function we are using the ExtJs library, this library is not compatible with 
   * previous libraries, for that reason oHeadPublisher will clear previous libraries like maborak
   * we need to check if we need the language file
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function setExtSkin($skin) {
    $this->extJsSkin = $skin;
  }
  
  /**
   * Function addExtJsScript
   * adding a javascript file  .js
   * add a js file in the extension Javascript Array,
   * later, when we use the includeExtJs function, all the files in this array will be included in the output
   * if the second argument is true, the file will not be minified, this is useful for debug purposes.
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function addExtJsScript($filename, $debug = false) {
    
    $sPath = PATH_TPL;
    //if the template  file doesn't exists, then try with the plugins folders
    

    if (! is_file ( $sPath . $filename . ".js" )) {
      $aux = explode ( PATH_SEP, $filename );
      //check if G_PLUGIN_CLASS is defined, because publisher can be called without an environment
      if (count ( $aux ) == 2 && defined ( 'G_PLUGIN_CLASS' )) {
        $oPluginRegistry = & PMPluginRegistry::getSingleton ();
        if ($oPluginRegistry->isRegisteredFolder ( $aux [0] )) {
          $sPath = PATH_PLUGINS;
        }
      }
    }
    
    $jsFilename = $sPath . $filename . '.js';
    
    if (! file_exists ( $jsFilename )) {
      return;
    }
    
    $mtime = filemtime ( $jsFilename );
    G::mk_dir ( PATH_C . 'ExtJs' );
    if ($debug) {
      $cacheName = str_replace ( '/', '_', $filename );
      $cacheFilename = PATH_C . 'ExtJs' . PATH_SEP . $cacheName . '.js';
      file_put_contents ( $cacheFilename, file_get_contents ( $jsFilename ) );
    } else {
      $cacheName = md5 ( $mtime . $jsFilename );
      $cacheFilename = PATH_C . 'ExtJs' . PATH_SEP . $cacheName . '.js';
      
      if (! file_exists ( $cacheFilename )) {
        require_once (PATH_THIRDPARTY . 'jsmin/jsmin.php');
        $content = JSMin::minify ( file_get_contents ( $jsFilename ) );
        file_put_contents ( $cacheFilename, $content );
      }
    }
    
    $this->extJsScript [] = '/extjs/' . $cacheName;
  }
  
  /**
   * Function AddContent
   * adding a html file  .html.
   * the main idea for this function, is to be a replacement to homonymous function in Publisher class.
   * with this function you are adding Content to the output, the class HeadPublisher will maintain a list of
   * files to render in the body of the output page
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function AddContent($templateHtml) {
    $this->extJsContent [] = $templateHtml;
  }
  
  /**
   * Function assign
   * assign a STRING value to a JS variable
   * use this function to send from PHP variables to be used in JavaScript
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function Assign($variable, $value) {
    $this->extVariable [] = array ('name' => $variable, 'value' => $value, 'type' => 'string' );
  }
  
  /**
   * Function assignNumber
   * assign a Number value to a JS variable
   * use this function to send from PHP variables to be used in JavaScript
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function AssignNumber($variable, $value) {
    $this->extVariable [] = array ('name' => $variable, 'value' => $value, 'type' => 'number' );
  }
  /**
   * Function renderExtJs
   * this function returns the content rendered using ExtJs
   * extJsContent have an array, and we iterate this array to draw the content
   *
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @access public
   * @return string
   */
  function renderExtJs() {
    $body = '';
    if (isset ( $this->extJsContent ) && is_array ( $this->extJsContent )) {
      foreach ( $this->extJsContent as $key => $file ) {
        $sPath = PATH_TPL;
        //if the template  file doesn't exists, then try with the plugins folders    	
        if (! is_file ( $sPath . $file . ".html" )) {
          $aux = explode ( PATH_SEP, $file );
          //check if G_PLUGIN_CLASS is defined, because publisher can be called without an environment
          if (count ( $aux ) == 2 && defined ( 'G_PLUGIN_CLASS' )) {
            $oPluginRegistry = & PMPluginRegistry::getSingleton ();
            if ($oPluginRegistry->isRegisteredFolder ( $aux [0] )) {
              $sPath = PATH_PLUGINS;
            }
          }
        }
        
        $template = new TemplatePower ( $sPath . $file . '.html' );
        $template->prepare ();
        $body .= $template->getOutputContent ();
      }
    }
    return $body;
  }

}
