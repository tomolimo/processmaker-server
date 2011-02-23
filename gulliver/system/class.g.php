<?php
/**
 * class.g.php
 * @package gulliver.system 
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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
 * @package gulliver.system
 */

class G
{

  /**
   * is_https
   * @return void
   */   
  function is_https()
  {
    if(isset($_SERVER['HTTPS']))
    {   
      if($_SERVER['HTTPS']=='on')
        return true;
      else
        return false;
    }
    else
      return false;
  }
  
  /**
   * Fill array values (recursive)
   * @author maborak <maborak@maborak.com>
   * @access public
   * @param  Array $arr
   * @param  Void  $value
   * @param  Boolean $recursive
   * @return Array
   */
  function array_fill_value($arr = Array(),$value = '',$recursive = false)
  {
    if(is_array($arr)) {
      foreach($arr as $key=>$val) {
        if(is_array($arr[$key])) {
          $arr[$key] = ($recursive===true)?G::array_fill_value($arr[$key],$value,true):$val;
        }
        else {
          $arr[$key] = $value;
        }
      }
    }
    else {
      $arr = Array();
    }
    return $arr;
  }
  
  /**
   * Generate Password Random
   * @author maborak <maborak@maborak.com>
   * @access public
   * @param  Int
   * @return String
   */
  function generate_password($length = 8)
  {
    $password = "";
    $possible = "0123456789bcdfghjkmnpqrstvwxyz";
    $i        = 0;
    while($i<$length) {
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      if(!strstr($password, $char)) {
        $password .= $char;
        $i++;
      }
    }
    return $password;
  }
  
  /**
   * Array concat
   * array_concat(ArrayToConcat,ArrayOriginal);
   * @author maborak <maborak@maborak.com>
   * @access public
   * @param  Array
   * @return Array
   */
  function array_concat()
  {
    $nums = func_num_args();
    $vars = func_get_args();
    $ret  = Array();
    for($i = 0;$i < $nums; $i++)
    {
      if(is_array($vars[$i])) {
        foreach($vars[$i] as $key=>$value) {
          $ret[$key] = $value;
        }
      }
    }
    return $ret;
  }

  /**
   * Compare Variables
   * var_compare(value,[var1,var2,varN]);
   * @author maborak <maborak@maborak.com>
   * @access public
   * @param  void $value
   * @param  void $var1-N
   * @return Boolean
   */
  function var_compare($value=true,$varN)
  {
    $nums = func_num_args();
    if($nums<2){return true;}
    $vars = func_get_args();
    $ret  = Array();
    for($i=1;$i<$nums;$i++) {
      if($vars[$i]!==$value) {
        return false;
      }
    }
    return true;
  }
  /**
   * Emulate variable selector
   * @author maborak <maborak@maborak.com>
   * @access public
   * @param  void
   * @return void
   */
  function var_probe()
  {
    //return (!$variable)?
    $nums = func_num_args();
    $vars = func_get_args();
    for($i=0;$i<$nums;$i++) {
      if($vars[$i]) {
        return $vars[$i];
      }
    }
    return 1;
  }

  /**
   * Get the current version of gulliver classes
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @return string
   */
  /*public static*/ function &getVersion(  )
  {
    //majorVersion.minorVersion-SvnRevision
    return '3.0-1';
  }

  /**
   * getIpAddress
   * @return string $ip
   */   
  /*public static*/ function getIpAddress () 
  {
    if (getenv('HTTP_CLIENT_IP')) {
      $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif(getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    else {
      $ip = getenv('REMOTE_ADDR');
    }
    return $ip;
  }
  
  /**
   * getMacAddress
   * @return string $mac
   */   
  function getMacAddress() 
  {
    if ( strstr ( getenv ( 'OS' ), 'Windows' ) ) {
      $ipconfig = `ipconfig /all`;
      preg_match('/[\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}/i',$ipconfig,$mac);
    } else {
      $ifconfig = `/sbin/ifconfig`;
      preg_match('/[\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}[\:-][\dA-Z]{2,2}/i',$ifconfig,$mac);
    }
    return isset($mac[0])? $mac[0]:'00:00:00:00:00:00';
  }

  /**
   * microtime_float
   * @return array_sum(explode(' ',microtime()))
   */   
  /*public static*/ function microtime_float() {
    return array_sum(explode(' ',microtime()));
  }
  /* custom error functions */

  /**
   * &setFatalErrorHandler
   *
   * @param  string $newFatalErrorHandler default value null
   *
   * @return boolean true
   */   
  /*public static*/
  function &setFatalErrorHandler( $newFatalErrorHandler = null )
     {
     if ( isset ( $newFatalErrorHandler ) ) {
       set_error_handler( $newFatalErrorHandler );
     }
     else {
       ob_start( array ( 'G', 'fatalErrorHandler' ) );
     }
     return true;
     }

  /**
   * setErrorHandler
   * @param  string setErrorHandler
   * @param  object $newCustomErrorHandler 
   *
   * @return boolean true
   */   
  /*public static*/
  function setErrorHandler( $newCustomErrorHandler = null )
    {
    if ( isset ( $newCustomErrorHandler ) ) {
      set_error_handler( $newCustomErrorHandler );
    }
    else {
      set_error_handler( array("G", "customErrorHandler"));
    }
    return true;
    }

  /**
   * fatalErrorHandler
   *
   * @param  string $buffer
   *
   * @return string $errorBox or $buffer
   */   
  /*public static*/ function fatalErrorHandler($buffer) {
    if (ereg("(error</b>:)(.+)(<br)", $buffer, $regs) ) {
      $err = preg_replace("/<.*?>/","",$regs[2]);
      G::customErrorLog('FATAL', $err,  '', 0, '');
      $ip_addr  = G::getIpAddress();
      $errorBox = "<table cellpadding=1 cellspacing=0 border=0 bgcolor=#808080 width=250><tr><td >" .
                  "<table cellpadding=2 cellspacing=0 border=0 bgcolor=white width=100%>" .
                  "<tr bgcolor=#d04040><td colspan=2 nowrap><font color=#ffffaa><code> ERROR CAUGHT check log file</code></font></td></tr>" .
                  "<tr ><td colspan=2 nowrap><font color=black><code>IP address: $ip_addr</code></font></td></tr> " .
                  "</table></td></tr></table>";
      return $errorBox;
    }
    return $buffer;
  }

  /**
   * customErrorHandler
   *
   * @param  string $errno
   * @param  string $msg
   * @param  string $file
   * @param  string $line
   * @param  string $context
   *
   * @return void
   */   
  /*public static*/
  function customErrorHandler ( $errno, $msg, $file, $line, $context) {
    switch ($errno) {
      case E_ERROR:
      case E_USER_ERROR:
            $type = "FATAL";
            G::customErrorLog ($type, $msg, $file, $line);
            G::verboseError ($type, $errno, $msg, $file, $line, $context);
            if (defined ("ERROR_SHOW_SOURCE_CODE") && ERROR_SHOW_SOURCE_CODE)
              G::showErrorSource ($type, $msg, $file, $line, "#c00000");
            die ();
            break;
      case E_WARNING:
      case E_USER_WARNING:
            $type = "WARNING";
            G::customErrorLog ($type, $msg, $file, $line);
            break;
      case E_NOTICE:
      case E_USER_NOTICE:
            $type = "NOTICE";
            if (defined ("ERROR_LOG_NOTICE_ERROR") && ERROR_LOG_NOTICE_ERROR)
            G::customErrorLog ($type, $msg, $file, $line);
            break;
      case E_STRICT:
            $type = "STRICT"; //dont show STRICT Errors
            //if (defined ("ERROR_LOG_NOTICE_ERROR") && ERROR_LOG_NOTICE_ERROR)
            //  G::customErrorLog ($type, $msg, $file, $line);
            break;
      default:
            $type = "ERROR ($errno)";
            G::customErrorLog ($type, $msg, $file, $line);
            break;
    }
  
    if (defined ("ERROR_SHOW_SOURCE_CODE") && ERROR_SHOW_SOURCE_CODE && $errno <> E_STRICT  )
    G::showErrorSource ($type, $msg, $file, $line);
  }
  
  /**
   * Function showErrorSource
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string type
   * @parameter string msg
   * @parameter string file
   * @parameter string line
   * @return string
   */
  function showErrorSource($type, $msg, $file, $line)
  {
    global $__src_array;
    $line_offset = 3;

    if (! isset ($__src_array[$file]))
      $__src_array[$file] = @file ($file);

    if (!$__src_array[$file])
      return;

    if ($line - $line_offset < 1)
      $start = 1;
    else
      $start = $line - $line_offset;

    if ($line + $line_offset > count ($__src_array[$file]))
      $end = count ($__src_array[$file]);
    else
      $end = $line + $line_offset;

    print "<table cellpadding=1 cellspacing=0 border=0 bgcolor=#808080 width=80%><tr><td >";
    print "<table cellpadding=2 cellspacing=0 border=0 bgcolor=white width=100%>";
    print "<tr bgcolor=#d04040>
          <td colspan=2 nowrap><font color=#ffffaa><code> $type: $msg</code></font></td></tr>
          <tr >
          <td colspan=2 nowrap><font color=gray>File: $file</font></td></tr>
          ";
    for ($i = $start; $i <= $end; $i++) {
      $str  = @highlight_string ("<?php" . $__src_array[$file][$i-1] . "?>", TRUE);

      $pos1 = strpos ($str,"&lt;?");
      $pos2 = strrpos ($str,"?&gt;");

      $str  = substr ($str, 0, $pos1) .
      substr ($str, $pos1+5, $pos2-($pos1+5)) .
      substr ($str, $pos2+5);

      ($i == $line) ? $bgcolor = "bgcolor=#ffccaa" : $bgcolor = "bgcolor=#ffffff";
      print "<tr><td bgcolor=#d0d0d0 width=15 align=right><code>$i</code></td>
            <td $bgcolor>$str</td></tr>";
    }

    print "</table></td></tr></table><p>";
  }

  /**
   * customErrorLog
   *
   * @param  string $type 
   * @param  string $msg 
   * @param  string $file 
   * @param  string $line
   *
   * @return void
   */   
  /*public static*/
  function customErrorLog ($type, $msg, $file, $line)
    {
    global $HTTP_X_FORWARDED_FOR, $REMOTE_ADDR, $HTTP_USER_AGENT, $REQUEST_URI;

    $ip_addr = G::getIpAddress();

    if (defined ('APPLICATION_CODE'))
      $name = APPLICATION_CODE;
    else
      $name = "php";

    if ( $file != '') 
      $msg .= " in $file:$line ";

    $date            = date ( 'Y-m-d H:i:s');
    $REQUEST_URI     = getenv ( 'REQUEST_URI' );
    $HTTP_USER_AGENT = getenv ( 'HTTP_USER_AGENT' );
    error_log ("[$date] [$ip_addr] [$name] $type: $msg [$HTTP_USER_AGENT] URI: $REQUEST_URI", 0);
    }

  /**
   * verboseError
   *
   * @param  string $type 
   * @param  string $errno 
   * @param  string $msg 
   * @param  string $file 
   * @param  string $line 
   * @param  string $context
   *
   * @return void
   */   
  /*public static*/ function verboseError ($type, $errno, $msg, $file, $line, $context) {
    global $SERVER_ADMIN;
  
    print "<h1>Error!</h1>";
    print "An error occurred while executing this script. Please
          contact the <a href=mailto:$SERVER_ADMIN>$SERVER_ADMIN</a> to
          report this error.";
    print "<p>";
    print "Here is the information provided by the script:";
    print "<hr><pre>";
    print "Error type: $type (code: $errno)<br>";
    print "Error message: $msg<br>";
    print "Script name and line number of error: $file:$line<br>";
    print "Variable context when error occurred: <br>";
    print_r ($context);
    print "</pre><hr>";
  }

  /*** Encrypt and decrypt functions ****/
  /**
   * Encrypt string
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $string
   * @param  string $key
   * @return string
   */
  function encrypt($string, $key) 
    {
    //print $string;
    //    if ( defined ( 'ENABLE_ENCRYPT' ) && ENABLE_ENCRYPT == 'yes' ) {
    if (strpos($string, '|', 0) !== false) return $string;
    $result = '';
    for($i = 0; $i<strlen($string); $i++) {
      $char    = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char    = chr(ord($char)+ord($keychar));
      $result .= $char;
     }
    //echo $result . '<br>';
    $result = base64_encode($result);
    $result = str_replace ( '/' , '°' , $result);
    $result = str_replace ( '=' , '' , $result);
    //  }
    // else
    //  $result = $string;

    return $result;
    }

  /**
   * Decrypt string
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $string
   * @param  string $key
   * @return string
   */
  /*public static*/ function decrypt($string, $key) {

    //   if ( defined ( 'ENABLE_ENCRYPT' ) && ENABLE_ENCRYPT == 'yes' ) {

    //if (strpos($string, '|', 0) !== false) return $string;
    $result     = '';
    $string     = str_replace ( '°', '/' , $string);
    $string_jhl = explode("?",$string);
    $string     = base64_decode($string);
    $string     = base64_decode($string_jhl[0]);

    for($i=0; $i<strlen($string); $i++) {
      $char     = substr($string, $i, 1);
      $keychar  = substr($key, ($i % strlen($key))-1, 1);
      $char     = chr(ord($char)-ord($keychar));
      $result  .= $char;
    }
    if (!empty($string_jhl[1])) 
      $result .= '?' . $string_jhl[1];
    // }
    // else
    // $result = $string;
    return $result;
  }

  /**
   * Look up an IP address direction
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $target
   * @return  void
   */
  function lookup($target)
  {
    //if( eregi("[a-zA-Z]", $target) )
    if( preg_match("[a-zA-Z]", $target) )//Made compatible to PHP 5.3
      $ntarget = gethostbyname($target);
    else
      $ntarget = gethostbyaddr($target);
    return($ntarget);
  }

  /***************  path functions *****************/
  
  /*public static*/ function mk_dir( $strPath, $rights = 0777)
  {
    $folder_path = array($strPath);
    $oldumask    = umask(0);
    while(!@is_dir(dirname(end($folder_path)))
          && dirname(end($folder_path)) != '/'
          && dirname(end($folder_path)) != '.'
          && dirname(end($folder_path)) != '')
      array_push($folder_path, dirname(end($folder_path))); //var_dump($folder_path); die;
      
    while($parent_folder_path = array_pop($folder_path))
      if(!@is_dir($parent_folder_path))
        if(!@mkdir($parent_folder_path, $rights))
    //trigger_error ("Can't create folder \"$parent_folder_path\".", E_USER_WARNING);
    umask($oldumask);
  }

  /**
   * rm_dir
   *
   * @param  string $dirName
   *
   * @return void
   */   
  function rm_dir($dirName) {
    if(empty($dirName)) {
      return;
    }
    if(file_exists($dirName)) {
      $dir = dir($dirName);
      while($file = $dir->read()) {
        if($file != '.' && $file != '..') {
          if(is_dir($dirName.'/'.$file)) {
            G::rm_dir($dirName.'/'.$file);
          } else {
            @unlink($dirName.'/'.$file) or die('File '.$dirName.'/'.$file.' couldn\'t be deleted!');
          }
        }
      }
      $folder = opendir($dirName. PATH_SEP .$file);
      closedir($folder);
      @rmdir($dirName.'/'.$file) or die('Folder '.$dirName.'/'.$file.' couldn\'t be deleted!');
    } else {
      echo 'Folder "<b>'.$dirName.'</b>" doesn\'t exist.';
    }
  }

  /**
   * verify path
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string  $strPath      path
   * @param  boolean $createPath   if true this function will create the path
   * @return boolean
   */
  /*public static*/ function verifyPath( $strPath , $createPath = false )
  {
    $folder_path = strstr($strPath, '.') ? dirname($strPath) : $strPath;

    if ( file_exists($strPath ) || @is_dir( $strPath )) {
      return true;
    }
    else {
      if ( $createPath ) {
        //TODO:: Define Environment constants: Devel (0777), Production (0770), ...
        G::mk_dir ( $strPath , 0777 );
      }
      else
      return false;
    }
    return false;
  }

  /**
   * Expand the path using the path constants
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strPath
   * @return string
   */
  function expandPath( $strPath = '' )
  {
    $res = "";
    $res = PATH_CORE;
    if( $strPath != "" )
    {
      $res .= $strPath . "/";
    }
    return $res;
  }

  /**
   * Load Gulliver Classes
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strClass
   * @return void
   */
  function LoadSystem( $strClass )
  {
    require_once( PATH_GULLIVER . 'class.' . $strClass . '.php' );
  }

  function LoadSystemExist($strClass)
  {
    if (file_exists (PATH_GULLIVER . 'class.' . $strClass . '.php') ) 
      return true;
    else 
      return false;
  }

  /**
   * Render Page
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  object $objContent
   * @param  string $strTemplate
   * @param  string $strSkin
   * @return void
   */
  function RenderPage( $strTemplate = "default", $strSkin = SYS_SKIN , $objContent = NULL )
  {
    global $G_CONTENT;
    global $G_TEMPLATE;
    $G_CONTENT  = $objContent;
    $G_TEMPLATE = $strTemplate;
    try {
      G::LoadSkin( $strSkin );
    }
    catch ( Exception $e ) {
      $aMessage['MESSAGE'] = $e->getMessage();
      global $G_PUBLISH;
      global $G_MAIN_MENU;
      global $G_SUB_MENU;
      $G_MAIN_MENU = '';
      $G_SUB_MENU  = '';
      //$G_PUBLISH          = new Publisher;

      //remove the login.js script
      global $oHeadPublisher;
      if ( count ( $G_PUBLISH->Parts ) == 1 )
      array_shift ( $G_PUBLISH->Parts );
      $leimnudInitString = $oHeadPublisher->leimnudInitString;
      //restart the oHeadPublisher
      $oHeadPublisher->clearScripts();
      //add the missing components, and go on.
      $oHeadPublisher->leimnudInitString = $leimnudInitString;
      $oHeadPublisher->addScriptFile("/js/maborak/core/maborak.js");

      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', null, $aMessage );
      G::LoadSkin( 'green' );
      die;
    }
  }

  /**
   * Load a skin
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strSkinName
   * @return void
   */
  function LoadSkin( $strSkinName )
  {
    //print $strSkinName;
    //now, we are using the skin, a skin is a file in engine/skin directory
    $file = G::ExpandPath( "skins" ) . $strSkinName. ".php";
    if (file_exists ($file) ) {
      require_once( $file );
      return;
    }
    else {
      if (file_exists ( PATH_HTML . 'errors/error703.php') ) {
        header ( 'location: /errors/error703.php' );
        die;
      }
      else   {
        $text = "The Skin $file does not exist, please review the Skin Definition";
        throw ( new Exception ( $text)  );
      }
    }

  }

  /**
   * Include javascript files
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strInclude
   * @return void
   */
  function LoadInclude( $strInclude )
  {
    $incfile = G::ExpandPath( "includes" ) . 'inc.' . $strInclude . '.php';
    if ( !file_exists( $incfile )) {
      $incfile = PATH_GULLIVER_HOME . 'includes' . PATH_SEP . 'inc.' . $strInclude . '.php';
    }

    if ( file_exists( $incfile )) {
      require_once( $incfile  );
      return true;
    }
    else {
      return false;
    }
  }

  /**
   * Include all model files
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strInclude
   * @return void
   */
  function LoadAllModelClasses( )
  {
    $baseDir = PATH_CORE . 'classes' . PATH_SEP . 'model';
    if ($handle = opendir( $baseDir  )) {
      while ( false !== ($file = readdir($handle))) {
        if ( strpos($file, '.php',1) && !strpos($file, 'Peer.php',1) ) {
          require_once ( $baseDir . PATH_SEP . $file );
        }
      }
    }
  }

  /**
   * Include all model plugin files
   *
   * LoadAllPluginModelClasses
   * @author Hugo Loza <hugo@colosa.com>
   * @access public
   * @return void
   */
  function LoadAllPluginModelClasses(){
    //Get the current Include path, where the plugins directories should be
    if ( !defined('PATH_SEPARATOR') ) {
      define('PATH_SEPARATOR', ( substr(PHP_OS, 0, 3) == 'WIN' ) ? ';' : ':');
    }
    $path=explode(PATH_SEPARATOR,get_include_path());


    foreach($path as $possiblePath){
      if(strstr($possiblePath,"plugins")){
        $baseDir = $possiblePath . 'classes' . PATH_SEP . 'model';
        if(file_exists($baseDir)){
          if ($handle = opendir( $baseDir  )) {
            while ( false !== ($file = readdir($handle))) {
              if ( strpos($file, '.php',1) && !strpos($file, 'Peer.php',1) ) {
                require_once ( $baseDir . PATH_SEP . $file );
              }
            }
          }
          //Include also the extendGulliverClass that could have some new definitions for fields
          if(file_exists($possiblePath . 'classes' . PATH_SEP.'class.extendGulliver.php')){
            include_once $possiblePath . 'classes' . PATH_SEP.'class.extendGulliver.php';
          }
        }
      }
    }
  }

  /**
   * Load a template
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strTemplateName
   * @return void
   */
  function LoadTemplate( $strTemplateName )
  {
    if ( $strTemplateName == '' ) return;
    $temp = $strTemplateName . ".php";
    $file = G::ExpandPath( 'templates' ) . $temp;
    // Check if its a user template
    if ( file_exists($file) ) {
      //require_once( $file );
      include( $file );
    } else {
      // Try to get the global system template
      $file = PATH_TEMPLATE . PATH_SEP . $temp;
      //require_once( $file );
      if ( file_exists($file) )
        include( $file );
    }
  }
  
  /**
   * Function LoadClassRBAC
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string strClass
   * @return string
   */
  function LoadClassRBAC( $strClass )
  {
    $classfile = PATH_RBAC . "class.$strClass"  . '.php';
    require_once( $classfile );
  }
  /**
   * If the class is not defined by the aplication, it
   * attempt to load the class from gulliver.system
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>, David S. Callizaya
   * @access public
   * @param  string $strClass
   * @return void
   */
  function LoadClass( $strClass )
  {
    $classfile = G::ExpandPath( "classes" ) . 'class.' . $strClass . '.php';
    if (!file_exists( $classfile )) {
      if (file_exists( PATH_GULLIVER . 'class.' . $strClass . '.php' ))
        return require_once( PATH_GULLIVER . 'class.' . $strClass . '.php' );
      else
        return false;
    } else {
      return require_once( $classfile );
    }
  }
  
  /**
   * Loads a Class. If the class is not defined by the aplication, it
   * attempt to load the class from gulliver.system
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>, David S. Callizaya
   * @access public
   * @param  string $strClass
   * @return void
   */
  function LoadThirdParty( $sPath , $sFile )
  {
    $classfile = PATH_THIRDPARTY . $sPath .'/'. $sFile .
                ( (substr($sFile,0,-4)!=='.php')? '.php': '' );
    return require_once( $classfile );
  }

  /**
   * Encrypt URL
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $urlLink
   * @return string
   */
  function encryptlink($url)
  {
    if ( defined ( 'ENABLE_ENCRYPT' ) && ENABLE_ENCRYPT == 'yes' )
      return urlencode( G::encrypt( $url ,URL_KEY ) );
    else
      return $url;
  }

  /**
   * Parsing the URI
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $urlLink
   * @return string
   */
  function parseURI( $uri )
  {
    $aRequestUri = explode('/', $uri );
    if ( substr ( $aRequestUri[1], 0, 3 ) == 'sys' ) {
      define( 'SYS_TEMP', substr ( $aRequestUri[1], 3 ) );
    }
    else {
      define("ENABLE_ENCRYPT", 'yes' );

      define( 'SYS_TEMP', $aRequestUri[1] );

      $plain = '/sys' . SYS_TEMP;

      for ($i = 2 ; $i < count($aRequestUri); $i++ ) {
        $decoded = G::decrypt ( urldecode($aRequestUri[$i]) , URL_KEY );
        if ( $decoded == 'sWì›' ) $decoded = $VARS[$i]; //this is for the string  "../"
        $plain .= '/' . $decoded;
      }
      $_SERVER["REQUEST_URI"] = $plain;
    }

    $CURRENT_PAGE = $_SERVER["REQUEST_URI"];

    $work = explode('?', $CURRENT_PAGE);
    if ( count($work) > 1 )
      define( 'SYS_CURRENT_PARMS', $work[1]);
    else
      define( 'SYS_CURRENT_PARMS', '');
    define( 'SYS_CURRENT_URI'  , $work[0]);

    if (!defined('SYS_CURRENT_PARMS'))
      define('SYS_CURRENT_PARMS', $work[1]);
    $preArray = explode('&', SYS_CURRENT_PARMS);
    $buffer = explode( '.', $work[0] );
    if ( count($buffer) == 1 ) $buffer[1]='';

    //request type
    define('REQUEST_TYPE', ($buffer[1] != "" ?$buffer[1] : 'html'));

    $toparse  = substr($buffer[0], 1, strlen($buffer[0]) - 1);
    $URL = "";
    $URI_VARS = explode('/', $toparse);
    for ( $i=3; $i < count( $URI_VARS) ; $i++)
      $URL .= $URI_VARS[$i].'/';

    $URI_VARS = explode('/', $toparse);

    unset($work);
    unset($buffer);
    unset($toparse);

    array_shift($URI_VARS);
    define("SYS_LANG", array_shift($URI_VARS));
    define("SYS_SKIN", array_shift($URI_VARS));

    $SYS_COLLECTION = array_shift($URI_VARS);
    $SYS_TARGET     = array_shift($URI_VARS);

    //to enable more than 2 directories...in the methods structure
    $exit = 0;
    while ( count ( $URI_VARS ) > 0 && $exit == 0) {
      $SYS_TARGET .= '/' . array_shift($URI_VARS);
    }
    define('SYS_COLLECTION',   $SYS_COLLECTION    );
    define('SYS_TARGET',       $SYS_TARGET    );

    if ( $SYS_COLLECTION == 'js2' ) {
      print "ERROR"; die;
    }
  }

  /**
   * streaming a file
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $file
   * @param  boolean $download
   * @param  string $downloadFileName
   * @return string
   */
  function streamFile( $file, $download = false, $downloadFileName = '' )
  {
    require_once (PATH_THIRDPARTY . 'jsmin/jsmin.php');

    $typearray = explode ( '.', $file );
    $typefile  = $typearray[ count($typearray) -1 ];
    $namearray = explode ( '/', $typearray[0] );
    $filename  = $file;
    
    //trick to generate the translation.language.js file , merging two files and then minified the content.
    if ( strtolower ($typefile ) == 'js' && $namearray[ count($namearray) -1 ] == 'translation' ) {
      header('Content-Type: text/javascript');

      //if userAgent (BROWSER) is MSIE we need special headers to avoid MSIE behaivor.
      $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
      if ( file_exists($filename) )
        $mtime = filemtime($filename);
      else
        $mtime = date('U');
      $gmt_mtime = gmdate("D, d M Y H:i:s", $mtime ) . " GMT";
      header('Pragma: cache');
      header('ETag: "' . md5 ($mtime . $filename ) . '"' );
      header("Last-Modified: " . $gmt_mtime );
      header('Cache-Control: public');
      header("Expires: " . gmdate("D, d M Y H:i:s", time () + 30*60*60*24 ) . " GMT"); //1 month
      if( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) {
        if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime) {
          header('HTTP/1.1 304 Not Modified');
          exit();
        }
      }

      if ( isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
        if ( str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5( $mtime . $filename))  {
          header("HTTP/1.1 304 Not Modified");
          exit();
        }
      }
      
      $output = '';
      $locale = $typearray[1]; //here we have the language , for example translation.en.js
      G::LoadTranslationObject($locale);
      global $translation;
      $output .= JSMin::minify ( 'var TRANSLATIONS = ' . G::json_encode($translation) . ';' );
      print $output;
      return;
    }

    if ( file_exists ( $filename ) ) {
      switch ( strtolower ($typefile ) ) {
        case 'swf' :
          G::sendHeaders ( $filename , 'application/x-shockwave-flash', $download, $downloadFileName ); break;
        case 'js' :
          G::sendHeaders ( $filename , 'text/javascript', $download, $downloadFileName ); break;
        case 'htm' :
        case 'html' :
          G::sendHeaders ( $filename , 'text/html', $download, $downloadFileName ); break;
        case 'htc' :
          G::sendHeaders ( $filename , 'text/plain', $download, $downloadFileName ); break;
        case 'json' :
          G::sendHeaders ( $filename , 'text/plain', $download, $downloadFileName ); break;
        case 'gif' :
          G::sendHeaders ( $filename , 'image/gif', $download, $downloadFileName ); break;
        case 'png' :
          G::sendHeaders ( $filename , 'image/png', $download, $downloadFileName ); break;
        case 'jpg' :
          G::sendHeaders ( $filename , 'image/jpg', $download, $downloadFileName ); break;
        case 'css' :
          G::sendHeaders ( $filename , 'text/css', $download, $downloadFileName ); break;
        case 'css' :
          G::sendHeaders ( $filename , 'text/css', $download, $downloadFileName ); break;
        case 'xml' :
          G::sendHeaders ( $filename , 'text/xml', $download, $downloadFileName ); break;
        case 'txt' :
          G::sendHeaders ( $filename , 'text/html', $download, $downloadFileName ); break;
        case 'doc' :
        case 'pdf' :
        case 'pm'  :
        case 'po'  :
          G::sendHeaders ( $filename , 'application/octet-stream', $download, $downloadFileName ); break;
        case 'php' :
          if ($download) {
            G::sendHeaders ( $filename , 'text/plain', $download, $downloadFileName );
          }
          else {
            require_once( $filename  );
            return;
          }
          break;
        default :
          //throw new Exception ( "Unknown type of file '$file'. " );
          G::sendHeaders ( $filename , 'application/octet-stream', $download, $downloadFileName ); break;
          break;
      }
    }
    else {
      if( strpos($file, 'gulliver') !== false ){
        list($path, $filename) = explode('gulliver', $file);
      }
      
      $_SESSION['phpFileNotFound'] = $file;
      G::header("location: /errors/error404.php?p=$filename");
    }

    switch ( strtolower($typefile ) ) {
      case "js" :
        $paths  = explode ( '/', $filename);
        $jsName = $paths[ count ($paths) -1 ];
        $output = '';
        switch ( $jsName ) {
          case 'draw2d.js' :
            $pathJs = PATH_GULLIVER_HOME . PATH_SEP . 'js' . PATH_SEP;
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/wz_jsgraphics.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/mootools.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/moocanvas.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/draw2d.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/pmos-common.js' ) );            
            break;
          case 'ext-all.js' :
            $pathJs = PATH_GULLIVER_HOME . PATH_SEP . 'js' . PATH_SEP;
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ext-all.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux/ux-all.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux/miframe.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux.locationbar/Ext.ux.LocationBar.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux.statusbar/ext-statusbar.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux.treefilterx/Ext.ux.tree.TreeFilterX.js' ) );
            $output .= JSMin::minify ( file_get_contents ( $pathJs . 'ext/ux.treefilterx/Ext.ux.tree.TreeFilterX.js' ) );
            break;

          case 'maborak.js' :
            $oHeadPublisher =& headPublisher::getSingleton();
            foreach ( $oHeadPublisher->maborakFiles as $fileJS ) {
              //$output .= JSMin::minify ( file_get_contents ( $fileJS ) );
              $output .= G::trimSourceCodeFile ($fileJS );
            }
            break;
          case 'maborak.loader.js':
            $oHeadPublisher =& headPublisher::getSingleton();
            foreach ( $oHeadPublisher->maborakLoaderFiles as $fileJS ) {
              $output .= JSMin::minify ( file_get_contents ( $fileJS ) );
              //$output .= G::trimSourceCodeFile ($fileJS );
            }
            break;
          default :
            $output = JSMin::minify ( file_get_contents ( $filename ) );
            //$output = G::trimSourceCodeFile ($filename );
        }
        print $output;
        break;
      case 'css' :
        //$output = JSMin::minify ( file_get_contents ( $filename) );
        print G::trimSourceCodeFile ($filename );
        break;
      default :
        readfile($filename);
    }
  }

  /**
   * trimSourceCodeFile
   *
   * @param  string $filename 
   *
   * @return string $output
   */   
  function trimSourceCodeFile ( $filename ) {
    $handle    = fopen ($filename, "r");
    $lastChar  = '';
    $firstChar = '';
    $content   = '';
    $line      = '';

    //no optimizing code
    if ($handle) {
      while (!feof($handle)) {
        //$line = trim( fgets($handle, 16096) ) . "\n" ;
        $line = fgets($handle, 16096);
        $content .= $line;
      }
      fclose($handle);
    }
    return $content;
    //end NO optimizing code
    //begin optimizing code
    /*
     if ($handle) {
     while (!feof($handle)) {
     $lastChar = ( strlen ( $line ) > 5 ) ? $line[strlen($line)-1] : '';

     $line = trim( fgets($handle, 16096) ) ;
     if ( substr ($line,0,2 ) == '//' )  $line = '';
     $firstChar = ( strlen ( $line ) > 6 ) ? strtolower($line[0]) : '';
     if ( ord( $firstChar ) > 96 && ord($firstChar) < 122 && $lastChar == ';')
     $content .= '';
     else
     $content .= "\n";
     //          $content .= '('.$firstChar . $lastChar . ord( $firstChar ).'-'. ord( $lastChar ) . ")\n";

     $content .= $line;
     }
     fclose($handle);
     }
     */
    //end optimizing code

    $index  = 0;
    $output = '';
    while ( $index < strlen ($content) ) {
      $car = $content[$index];
      $index++;
      if ( $car == '/' && isset($content[$index]) && $content[$index] == '*' ) {
        $endComment = false;
        $index ++;
        while ( $endComment == false && $index < strlen ($content) ) {
          if ($content[$index] == '*' && isset($content[$index+1]) && $content[$index+1] == '/' ) {
            $endComment = true; $index ++;
          }
          $index ++;
        }
        $car = '';
      }
      $output .= $car;
    }
    return $output;
  }

  /**
   * sendHeaders
   *
   * @param  string  $filename 
   * @param  string  $contentType default value ''
   * @param  boolean $download default value false
   * @param  string  $downloadFileName default value '' 
   *
   * @return void
   */   
  function sendHeaders ( $filename , $contentType = '', $download = false, $downloadFileName = '' )
  {
    if ($download) {
      if ($downloadFileName == '') {
        $aAux = explode('/', $filename);
        $downloadFileName = $aAux[count($aAux) - 1];
      }
      header('Content-Disposition: attachment; filename="' . $downloadFileName . '"');
    }
    header('Content-Type: ' . $contentType);

    //if userAgent (BROWSER) is MSIE we need special headers to avoid MSIE behaivor.
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ( preg_match("/msie/i", $userAgent)) {
    //if ( ereg("msie", $userAgent)) {
      header('Pragma: cache');

      $mtime = filemtime($filename);
      $gmt_mtime = gmdate("D, d M Y H:i:s", $mtime ) . " GMT";
      header('ETag: "' . md5 ($mtime . $filename ) . '"' );
      header("Last-Modified: " . $gmt_mtime );
      header('Cache-Control: public');
      header("Expires: " . gmdate("D, d M Y H:i:s", time () + 60*10 ) . " GMT"); //ten minutes
      return;
    }
    
    if (!$download) {

      header('Pragma: cache');

      if ( file_exists($filename) )
        $mtime = filemtime($filename);
      else
        $mtime = date('U');
      $gmt_mtime = gmdate("D, d M Y H:i:s", $mtime ) . " GMT";
      header('ETag: "' . md5 ($mtime . $filename ) . '"' );
      header("Last-Modified: " . $gmt_mtime );
      header('Cache-Control: public');
      header("Expires: " . gmdate("D, d M Y H:i:s", time () + 90*60*60*24 ) . " GMT");
      if( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) {
        if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime) {
          header('HTTP/1.1 304 Not Modified');
          exit();
        }
      }

      if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
        if ( str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5( $mtime . $filename))  {
          header("HTTP/1.1 304 Not Modified");
          exit();
        }
      }
    }
  }

  /**
   * Transform a public URL into a local path.
   *
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param  string $url
   * @param  string $corvertionTable
   * @param  string $realPath = local path
   * @return boolean
   */
  function virtualURI( $url , $convertionTable , &$realPath )
  {
    foreach($convertionTable as $urlPattern => $localPath ) {
      //      $urlPattern = addcslashes( $urlPattern , '/');
      $urlPattern = addcslashes( $urlPattern , './');
      $urlPattern = '/^' . str_replace(
      array('*','?'),
      array('.*','.?'),
      $urlPattern) . '$/';
      if (preg_match($urlPattern , $url, $match)) {
        if ($localPath === FALSE) {
          $realPath = $url;
          return false;
        }
        if ( $localPath != 'jsMethod' )
          $realPath = $localPath . $match[1];
        else
          $realPath = $localPath;
        return true;
      }
    }
    $realPath = $url;
    return false;
  }

  /**
   * Create an encrypted unique identifier based on $id and the selected scope id.
   *
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param  string $scope
   * @param  string $id
   * @return string
   */
  function createUID( $scope, $id )
  {
    $e = $scope . $id;
    $e=G::encrypt( $e , URL_KEY );
    $e=str_replace(array('+','/','='),array('__','_','___'),base64_encode($e));
    return $e;
  }
  
  /**
   * (Create an encrypted unique identificator based on $id and the selected scope id.) ^-1
   * getUIDName
   * 
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param  string $id
   * @param  string $scope
   * @return string
   */
  function getUIDName( $uid , $scope = '' )
  {
    $e=str_replace(array('=','+','/'),array('___','__','_'),$uid);
    $e=base64_decode($e);
    $e=G::decrypt( $e , URL_KEY );
    $e=substr( $e , strlen($scope) );
    return $e;
  }
  
  /* formatNumber
   *
   * @author David Callizaya <calidavidx21@yahoo.com.ar>
   * @param  int/string $num
   * @return string number
   */
  function formatNumber($num, $language='latin')
  {
    switch($language)
    {
      default:
        $snum=$num;
    }
    return $snum;
  }
  
  /* Returns a date formatted according to the given format string
   * @author David Callizaya <calidavidx21@hotmail.com>
   * @param string $format     The format of the outputted date string
   * @param string $datetime   Date in the format YYYY-MM-DD HH:MM:SS
   */
  function formatDate($datetime, $format='Y-m-d', $lang='')
  {
    if ($lang==='') $lang=defined(SYS_LANG)?SYS_LANG:'en';
    $aux     = explode (' ', $datetime);  //para dividir la fecha del dia
    $date    = explode ('-', isset ( $aux[0] ) ? $aux[0] : '00-00-00' );   //para obtener los dias, el mes, y el año.
    $time    = explode (':', isset ( $aux[1] ) ? $aux[1] : '00:00:00' );   //para obtener las horas, minutos, segundos.
    $date[0] = (int)((isset($date[0]))?$date[0]:'0');
    $date[1] = (int)((isset($date[1]))?$date[1]:'0');
    $date[2] = (int)((isset($date[2]))?$date[2]:'0');
    $time[0] = (int)((isset($time[0]))?$time[0]:'0');
    $time[1] = (int)((isset($time[1]))?$time[1]:'0');
    $time[2] = (int)((isset($time[2]))?$time[2]:'0');
    // Spanish months
    $ARR_MONTHS['es'] = array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    // English months
    $ARR_MONTHS['en'] = array("January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December");
    
    
    // Spanish days
    $ARR_WEEKDAYS['es'] = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    // English days
    $ARR_WEEKDAYS['en'] = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    
    

    if ($lang=='fa') 
      $number='persian'; 
    else 
      $number='latin';
    $d = '0'.$date[2];$d=G::formatNumber(substr($d,strlen($d)-2,2),$number);
    $j = G::formatNumber($date[2],$number);
    $F = isset ( $ARR_MONTHS[$lang][$date[1]-1] ) ? $ARR_MONTHS[$lang][$date[1]-1] : '';
    $m = '0'.$date[1];$m=G::formatNumber(substr($m,strlen($m)-2,2),$number);
    $n = G::formatNumber($date[1],$number);
    $y = G::formatNumber(substr($date[0],strlen($date[0])-2,2),$number);
    $Y = '0000'.$date[0];$Y=G::formatNumber(substr($Y,strlen($Y)-4,4),$number);
    $g = ($time[0] % 12);if ($g===0)$g=12;
    $G = $time[0];
    $h = '0'.$g;$h=G::formatNumber(substr($h,strlen($h)-2,2),$number);
    $H = '0'.$G;$H=G::formatNumber(substr($H,strlen($H)-2,2),$number);
    $i = '0'.$time[1];$i=G::formatNumber(substr($i,strlen($i)-2,2),$number);
    $s = '0'.$time[2];$s=G::formatNumber(substr($s,strlen($s)-2,2),$number);
    $names=array('d','j','F','m','n','y','Y','g','G','h','H','i','s');
    $values=array($d, $j, $F, $m, $n, $y, $Y, $g, $G, $h, $H, $i, $s);
    $_formatedDate = str_replace( $names, $values, $format );
    return $_formatedDate;
  }

  /**
   * getformatedDate
   *
   * @param  date   $date 
   * @param  string $format default value 'yyyy-mm-dd', 
   * @param  string $lang default value ''
   *
   * @return string $ret
   */   
  function getformatedDate($date, $format = 'yyyy-mm-dd', $lang = '')
  {
    /********************************************************************************************************
     * if the year is 2008 and the format is yy  then -> 08
     * if the year is 2008 and the format is yyyy  then -> 2008
     *
     * if the month is 05 and the format is mm  then -> 05
     * if the month is 05 and the format is m and the month is less than 10 then -> 5 else digit normal
     * if the month is 05 and the format is MM or M then -> May
     *
     * if the day is 5 and the format is dd  then -> 05
     * if the day is 5 and the format is d and the day is less than 10 then -> 5 else digit normal
     * if the day is 5 and the format is DD or D then -> five
     *********************************************************************************************************/

    //scape the literal
    switch($lang) {
      case 'es':
        $format = str_replace(' de ', '[of]', $format);
        break;
    }

    //first we must formatted the string
    $format  = str_replace('yyyy', '{YEAR}', $format);
    $format  = str_replace('yy', '{year}', $format);

    $format  = str_replace('mm', '{YONTH}', $format);
    $format  = str_replace('m', '{month}', $format);
    $format  = str_replace('M', '{XONTH}', $format);

    $format  = str_replace('dd', '{DAY}', $format);
    $format  = str_replace('d', '{day}', $format);

    if ($lang==='') $lang=defined(SYS_LANG)?SYS_LANG:'en';

    $aux     = explode (' ', $date);  //para dividir la fecha del dia
    $date    = explode ('-', isset ( $aux[0] ) ? $aux[0] : '00-00-00' );   //para obtener los dias, el mes, y el año.
    $time    = explode (':', isset ( $aux[1] ) ? $aux[1] : '00:00:00' );   //para obtener las horas, minutos, segundos.

    $year    = (int)((isset($date[0]))?$date[0]:'0'); //year
    $month   = (int)((isset($date[1]))?$date[1]:'0'); //month
    $day     = (int)((isset($date[2]))?$date[2]:'0'); //day

    $time[0] = (int)((isset($time[0]))?$time[0]:'0'); //hour
    $time[1] = (int)((isset($time[1]))?$time[1]:'0'); //minute
    $time[2] = (int)((isset($time[2]))?$time[2]:'0'); //second

    $MONTHS  = Array();
    for($i=1; $i<=12; $i++){
      $MONTHS[$i] =   G::LoadTranslation("ID_MONTH_$i", $lang);
    }

    $d  = (int)$day;
    $dd = G::complete_field($day, 2, 1);

    //missing D

    $M      = $MONTHS[$month];
    $m      = (int)$month;
    $mm     = G::complete_field($month, 2, 1);

    $yy     = substr($year,strlen($year)-2,2);
    $yyyy   = $year;

    $names  = array('{day}', '{DAY}', '{month}', '{YONTH}', '{XONTH}', '{year}', '{YEAR}');
    $values = array($d, $dd, $m, $mm, $M, $yy, $yyyy);

    $ret    = str_replace( $names, $values, $format );

    //recovering the original literal
    switch($lang){
      case 'es':
        $ret = str_replace('[of]', ' de ', $ret);
        break;
    }

    return $ret;
  }

  /**
   *  By <erik@colosa.com>
   *  Here's a little wrapper for array_diff - I found myself needing
   *  to iterate through the edited array, and I didn't need to original keys for anything.
   */
  function arrayDiff($array1, $array2) {
    // This wrapper for array_diff rekeys the array returned
    $valid_array = array_diff($array1,$array2);

    // reinstantiate $array1 variable
    $array1 = array();

    // loop through the validated array and move elements to $array1
    // this is necessary because the array_diff function returns arrays that retain their original keys
    foreach ($valid_array as $valid){
      $array1[] = $valid;
    }
    return $array1;
  }

  /**
   * @author Erik Amaru Ortiz <erik@colosa.com>
   * @name complete_field($string, $lenght, $type={1:number/2:string/3:float})
   */
  function complete_field($campo, $long, $tipo)
  {
    $campo = trim($campo);
    switch($tipo)
    {
      case 1: //number
        $long = $long-strlen($campo);
        for($i=1; $i<=$long; $i++) {
          $campo = "0".$campo;
        }
        break;

      case 2: //string
        $long = $long-strlen($campo);
        for($i=1; $i<=$long; $i++) {
          $campo = " ".$campo;
        }
        break;

      case 3: //float
        if($campo!="0") {
          $vals = explode(".",$long);
          $ints = $vals[0];

          $decs = $vals[1];

          $valscampo = explode(".",$campo);

          $intscampo = $valscampo[0];
          $decscampo = $valscampo[1];

          $ints = $ints - strlen($intscampo);

          for($i=1; $i<=$ints; $i++) {
            $intscampo = "0".$intscampo;
          }

          //los decimales pueden ser 0 uno o dos
          $decs = $decs - strlen($decscampo);
          for($i=1; $i<=$decs; $i++) {
            $decscampo = $decscampo."0";
          }

          $campo = $intscampo.".".$decscampo;
        } else {
          $vals  = explode(".",$long);
          $ints  = $vals[0];
          $decs  = $vals[1];

          $campo = "";
          for($i=1; $i<=$ints; $i++) {
            $campo = "0".$campo;
          }
          $campod = "";
          for($i=1; $i<=$decs; $i++) {
            $campod = "0".$campod;
          }

          $campo = $campo.".".$campod;
        }
        break;
    }
    return $campo;
  }

  /* Escapes special characters in a string for use in a SQL statement
   * @author David Callizaya <calidavidx21@hotmail.com>
   * @param string $sqlString  The string to be escaped
   * @param string $DBEngine   Target DBMS
   */
  function sqlEscape( $sqlString, $DBEngine = DB_ADAPTER )
  {
    $DBEngine = DB_ADAPTER;
    switch($DBEngine){
      case 'mysql':
        $con = Propel::getConnection('workflow') ;
        return mysql_real_escape_string(stripslashes($sqlString), $con->getResource() );
      case 'myxml':
        $sqlString = str_replace('"', '""', $sqlString);
        return str_replace("'", "''", $sqlString);
        //return str_replace(array('"',"'"),array('""',"''"),stripslashes($sqlString));
      default:
        return addslashes(stripslashes($sqlString));
    }
  }
  
  /**
   * Function MySQLSintaxis
   * @access public
   * @return Boolean
   **/
  function MySQLSintaxis()
  {
    $DBEngine = DB_ADAPTER;
    switch($DBEngine){
      case 'mysql' :
        return TRUE;
        break;
      case 'mssql' :
      default:
        return FALSE;
        break;
    }
  }
  /* Returns a sql string with @@parameters replaced with its values defined
   * in array $result using the next notation:
   * NOTATION:
   *     @@  Quoted parameter acording to the SYSTEM's Database
   *     @Q  Double quoted parameter \\  \"
   *     @q  Single quoted parameter \\  \'
   *     @%  URL string
   *     @#  Non-quoted parameter
   *     @!  Evaluate string : Replace the parameters in value and then in the sql string
   *     @fn()  Evaluate string with the function "fn"
   * @author David Callizaya <calidavidx21@hotmail.com>
   */
  function replaceDataField( $sqlString, $result, $DBEngine = 'mysql' )
  {
    if (!is_array($result)) {
      $result = array();
    }
    $result      = $result + G::getSystemConstants();
    $__textoEval = "";
    $u           = 0;
    //$count=preg_match_all('/\@(?:([\@\%\#\!Qq])([a-zA-Z\_]\w*)|([a-zA-Z\_][\w\-\>\:]*)\(((?:[^\\\\\)]*(?:[\\\\][\w\W])?)*)\))/',$sqlString,$match,PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);
    $count = preg_match_all('/\@(?:([\@\%\#\!Qq])([a-zA-Z\_]\w*)|([a-zA-Z\_][\w\-\>\:]*)\(((?:[^\\\\\)]*?)*)\))/',$sqlString,$match,PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

    if ($count) {
      for($r=0;$r<$count;$r++) {
        if (!isset($result[$match[2][$r][0]])) 
          $result[$match[2][$r][0]] = '';
        if (!is_array($result[$match[2][$r][0]])) {
          $__textoEval.=substr($sqlString,$u,$match[0][$r][1]-$u);
          $u = $match[0][$r][1]+strlen($match[0][$r][0]);
          //Mysql quotes scape
          if (($match[1][$r][0]=='@')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.="\"". G::sqlEscape($result[$match[2][$r][0]],$DBEngine) ."\"";continue;
          }
          //URL encode
          if (($match[1][$r][0]=='%')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.=urlencode($result[$match[2][$r][0]]);continue;
          }
          //Double quoted parameter
          if (($match[1][$r][0]=='Q')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.='"'.addcslashes($result[$match[2][$r][0]],'\\"').'"';continue;
          }
          //Single quoted parameter
          if (($match[1][$r][0]=='q')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.="'".addcslashes($result[$match[2][$r][0]],'\\\'')."'";continue;
          }
          //Substring (Sub replaceDataField)
          if (($match[1][$r][0]=='!')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.=G::replaceDataField($result[$match[2][$r][0]],$result);continue;
          }
          //Call function
          if (($match[1][$r][0]==='')&&($match[2][$r][0]==='')&&($match[3][$r][0]!=='')) {
            eval('$__textoEval.='.$match[3][$r][0].'(\''.addcslashes(G::replaceDataField(stripslashes($match[4][$r][0]),$result),'\\\'').'\');');continue;
          }
          //Non-quoted
          if (($match[1][$r][0]=='#')&&(isset($result[$match[2][$r][0]]))) {
            $__textoEval.=G::replaceDataField($result[$match[2][$r][0]],$result);continue;
          }
        }
      }
    }
    $__textoEval.=substr($sqlString,$u);
    return $__textoEval;
  }

  /* Load strings from a XMLFile.
   * @author David Callizaya <davidsantos@colosa.com>
   * @parameter $languageFile An xml language file.
   * @parameter $languageId   (es|en|...).
   * @parameter $forceParse   Force to read and parse the xml file.
   */
  function loadLanguageFile ( $filename , $languageId = '', $forceParse = false )
  {
    global $arrayXmlMessages;
    if ($languageId==='') $languageId = defined('SYS_LANG') ? SYS_LANG : 'en';
    $languageFile = basename( $filename , '.xml' );
    $cacheFile = substr( $filename , 0 ,-3 ) . $languageId;
    if (($forceParse) || (!file_exists($cacheFile)) ||
        ( filemtime($filename) > filemtime($cacheFile))
    //|| ( filemtime(__FILE__) > filemtime($cacheFile))
    ) {
      $languageDocument = new Xml_document();
      $languageDocument->parseXmlFile( $filename );
      if (!is_array($arrayXmlMessages)) $arrayXmlMessages = array();
      $arrayXmlMessages[ $languageFile ] = array();
      for($r=0 ; $r < sizeof($languageDocument->children[0]->children) ; $r++ ) {
        $n = $languageDocument->children[0]->children[$r]->findNode($languageId);
        if ($n) {
          $k = $languageDocument->children[0]->children[$r]->name;
          $arrayXmlMessages[ $languageFile ][ $k ] = $n->value;
        }
      }
      $f = fopen( $cacheFile , 'w');
      fwrite( $f , "<?php\n" );
      fwrite( $f , '$arrayXmlMessages[\'' . $languageFile . '\']=' . 'unserialize(\'' .
              addcslashes( serialize ( $arrayXmlMessages[ $languageFile ] ), '\\\'' ) .
                  "');\n");
      fwrite( $f , "?>" );
      fclose( $f );
    } else {
      require( $cacheFile );
    }
  }
  /* Funcion auxiliar Temporal:
   *   Registra en la base de datos los labels xml usados en el sistema
   * @author David Callizaya <calidavidx21@hotmail.com>
   */
  function registerLabel( $id , $label )
  {
    return 1;
    $dbc = new DBConnection();
    $ses = new DBSession($dbc);
    $ses->Execute(G::replaceDataField(
      'REPLACE INTO `TRANSLATION` (`TRN_CATEGORY`, `TRN_ID`, `TRN_LANG`, `TRN_VALUE`) VALUES
      ("LABEL", @@ID, "'.SYS_LANG.'", @@LABEL);',array('ID'=>$id,'LABEL'=>($label !== null ? $label : ''))));
  }
  /**
   * Function LoadMenuXml
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string msgID
   * @return string
   */
  function LoadMenuXml( $msgID )
  {
    global $arrayXmlMessages;
    if (!isset($arrayXmlMessages['menus']))
    G::loadLanguageFile( G::ExpandPath('content') . 'languages/menus.xml' );
    G::registerLabel($msgID,$arrayXmlMessages['menus'][$msgID]);
    return $arrayXmlMessages['menus'][$msgID];
  }
  /**
   * Function SendMessageXml
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string msgID
   * @parameter string strType
   * @parameter string file
   * @return string
   */
  function SendMessageXml( $msgID, $strType , $file="labels")
  {
    global $arrayXmlMessages;
    if (!isset($arrayXmlMessages[$file]))
    G::loadLanguageFile( G::ExpandPath('content') . 'languages/' . $file . '.xml' );
    $_SESSION['G_MESSAGE_TYPE'] = $strType;
    G::registerLabel($msgID,$arrayXmlMessages[$file][$msgID]);
    $_SESSION['G_MESSAGE'] = nl2br ($arrayXmlMessages[$file][$msgID]);
  }
  
  /**
   * SendTemporalMessage
   *
   * @param  string  $msgID 
   * @param  string  $strType
   * @param  string  $sType default value 'LABEL'
   * @param  date    $time default value null
   * @param  integer $width default value null
   * @param  string  $customLabels default value null
   *
   * @return void
   */   
  function SendTemporalMessage($msgID, $strType, $sType='LABEL', $time=null, $width=null, $customLabels= null)
  {
    if( isset($width) ){
      $_SESSION['G_MESSAGE_WIDTH'] = $width;
    }
    if( isset($time) ){
      $_SESSION['G_MESSAGE_TIME'] = $time;
    }
    switch(strtolower($sType)){
      case 'label':
      case 'labels':
        $_SESSION['G_MESSAGE_TYPE'] = $strType;
        $_SESSION['G_MESSAGE'] = nl2br(G::LoadTranslation($msgID));
        break;
      case 'string':
        $_SESSION['G_MESSAGE_TYPE'] = $strType;
        $_SESSION['G_MESSAGE'] = nl2br($msgID);
        break;
    }
    if ( $customLabels != null ) {
      $message = $_SESSION['G_MESSAGE'];
      foreach ( $customLabels as $key=>$val ) {
        $message = str_replace ( '{' . nl2br($key) . '}' , nl2br($val), $message );
      }
      $_SESSION['G_MESSAGE'] = $message;
    }
  }

  /**
   * SendMessage
   *
   * @param  string $msgID 
   * @param  string $strType 
   * @param  string $file default value "labels"
   *
   * @return void
   */   
  function SendMessage( $msgID, $strType , $file="labels")
  {
    global $arrayXmlMessages;
    $_SESSION['G_MESSAGE_TYPE'] = $strType;
    $_SESSION['G_MESSAGE']      = nl2br (G::LoadTranslation($msgID));
  }

  /**
   * SendMessageText
   * Just put the $text in the message text 
   *
   * @param  string $text
   * @param  string $strType
   *
   * @return void
   */   
  function SendMessageText( $text, $strType)
  {
    global $arrayXmlMessages;
    $_SESSION['G_MESSAGE_TYPE'] = $strType;
    $_SESSION['G_MESSAGE']      = nl2br ( $text );
  }

  /**
   * Render message from XML file
   * 
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $msgID
   * @return void
   */
  function LoadMessage( $msgID, $file = "messages" ) 
  {
    global $_SESSION;
    global $arrayXmlMessages;

    if ( !is_array ($arrayXmlMessages) )
    $arrayXmlMessages = G::LoadArrayFile( G::ExpandPath( 'content' ) . $file . "." . SYS_LANG );

    $aux = $arrayXmlMessages[$msgID];
    $msg = "";
    for ($i = 0; $i < strlen($aux); $i++) {
      if ( $aux[$i] == "$") {
        $token = ""; $i++;
        while ($i < strlen ($aux) && $aux[$i]!=" " && $aux[$i]!="."  && $aux[$i]!="'" && $aux[$i]!='"')
          $token.= $aux[$i++];
        eval ( "\$msg.= \$_SESSION['".$token."'] ; ");
        $msg .= $aux[$i];
      }
      else
        $msg = $msg . $aux[$i];
    }
    return $msg;
  }
  /**
   * Function LoadXmlLabel
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string file
   * @parameter string msgID
   * @return string
   */
  function LoadXmlLabel( $msgID , $file = 'labels' )
  {
    return 'xxxxxx';
    global $arrayXmlMessages;
    if (!isset($arrayXmlMessages[$file]))
      G::loadLanguageFile( G::ExpandPath('content') . 'languages/' . $file . '.xml' );
    G::registerLabel($msgID,$arrayXmlMessages[$file][$msgID]);
    return $arrayXmlMessages[$file][$msgID];
  }
  /**
   * Function LoadMessageXml
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @parameter string msgID
   * @parameter string file
   * @return string
   */
  function LoadMessageXml( $msgID , $file ='labels' )
  {
    global $arrayXmlMessages;
    if ( !isset($arrayXmlMessages[$file]) )
      G::loadLanguageFile( G::ExpandPath('content') . 'languages/' . $file . '.xml' );
    if ( isset($arrayXmlMessages[$file][$msgID]) ) {
      G::registerLabel( $msgID, $arrayXmlMessages[$file][$msgID] );
      return $arrayXmlMessages[$file][$msgID];
    }
    else {
      G::registerLabel($msgID,'');
      return NULL;
    }
  }
  /**
   * Function LoadTranslationObject
   * It generates a global Translation variable that will be used in all the system. Per script
   * @author Hugo Loza. <hugo@colosa.com>
   * @access public
   * @parameter string lang    
   * @return void
   */
  function LoadTranslationObject($lang = SYS_LANG){
    $defaultTranslations = Array();
    $foreignTranslations = Array();
    
    //if the default translations table doesn't exist we can't proceed
    if( ! is_file(PATH_LANGUAGECONT . 'translation.en') )
      return NULL;
    
    //load the translations table
    require_once ( PATH_LANGUAGECONT . 'translation.en' );
    $defaultTranslations = $translation;
    
    //if some foreign language was requested and its translation file exists
    if( $lang != 'en' && file_exists(PATH_LANGUAGECONT . 'translation.' . $lang) ){
      require_once ( PATH_LANGUAGECONT . 'translation.' . $lang ); //load the foreign translations table
      $foreignTranslations = $translation;
    }
    
    global $translation;
    if( defined("SHOW_UNTRANSLATED_AS_TAG") && SHOW_UNTRANSLATED_AS_TAG != 0 ) 
      $translation = $foreignTranslations;
    else
      $translation = array_merge($defaultTranslations, $foreignTranslations);
  }
  
  /**
   * Function LoadTranslation
   * @author Aldo Mauricio Veliz Valenzuela. <mauricio@colosa.com>
   * @access public
   * @parameter string msgID
   * @parameter string file
   * @parameter array data // erik: associative array within data input to replace for formatted string i.e "any messsage {replaced_label} that contains a replace label"
   * @return string
   */
  function LoadTranslation( $msgID , $lang = SYS_LANG, $data = null)
  {
    global $translation;      
    
    if ( isset ( $translation[$msgID] ) ){
      $translationString = preg_replace("[\n|\r|\n\r]", ' ', $translation[$msgID]);
    
      if( isset($data) && is_array($data) ) {
        foreach($data as $label=>$value) {
          $translationString = str_replace('{'.$label.'}', $value, $translationString);
        }
      }
      
      return $translationString;
    } else {
      if( defined("UNTRANSLATED_MARK") ) {
        $untranslatedMark = strip_tags(UNTRANSLATED_MARK);
      } else {
        $untranslatedMark = "**";
      }
      return $untranslatedMark . $msgID . $untranslatedMark;
    }

  }

  /**
   * Function getTranslations
   * @author Erik Amaru O. <erik@colosa.com>
   * @access public
   * @parameter array msgIDs
   * @parameter string file
   * @return string
   */
  function getTranslations($msgIDs , $lang = SYS_LANG)
  {
    if ( ! is_array($msgIDs) ) return null;

    $translations = Array();
    foreach( $msgIDs as $mID ) {
      $translations[$mID] = self::LoadTranslation($mID , $lang);
    }
    
    return $translations;
  }
  /**
   * Load an array File Content
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $strFile
   * @return void
   */
  function LoadArrayFile( $strFile = '' )
  {
    $res = NULL;
    if ( $strFile != '' )
    {
      $src = file( $strFile );
      if( is_array( $src ) )
      {
        foreach( $src as $key => $val )
        {
          $res[$key] = trim( $val );
        }
      }
    }
    unset( $src );
    return $res;
  }

  /**
   * Expand an uri based in the current URI
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $methodPage the method directory and the page
   * @return the expanded uri, later, will encryt the uri...
   */
  function expandUri ( $methodPage ) {
    $uri    = explode ( '/', getenv ( 'REQUEST_URI' ) );
    $sw     = 0;
    $newUri = '';
    if ( !defined ( 'SYS_SKIN' ) ) {
      for ( $i = 0; $i < count( $uri) ; $i++ ) {
        if ( $sw == 0 ) $newUri .= $uri[ $i ] . PATH_SEP ;
        if ( $uri[ $i ] == SYS_SKIN ) $sw = 1;
      }
    }
    else {
      for ( $i =0; $i < 4 ; $i++ ) {
        if ( $sw == 0 ) $newUri .= $uri[ $i ] . PATH_SEP ;
        if ( $uri[ $i ] == SYS_SKIN ) $sw = 1;
      }
    }
    $newUri .= $methodPage;
    return $newUri;
  }

  /**
   * Forces login for generic applications
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $userid
   * @param  string $permission
   * @param  string $urlNoAccess
   * @return void
   */
  function genericForceLogin( $permission , $urlNoAccess, $urlLogin = 'login/login' )  {
    global $RBAC;

    //the session is expired, go to login page,
    //the login page is login/login.html
    if ( ! isset ( $_SESSION ) ) {
      header ( 'location: ' . G::expandUri ( $urlLogin ) );
      die ();
    }

    //$permission is an array, we'll verify all permission to allow access.
    if ( is_array($permission) )
      $aux = $permission;
    else
      $aux[0] = $permission;

    $sw = 0;
    for ($i = 0; $i < count ($aux); $i++ ) {
      $res = $RBAC->userCanAccess($aux[$i]);
      if ($res == 1) $sw = 1;
    }

    //you don't have access to this page
    if ($sw == 0) {
      header ( 'location: ' . G::expandUri ( $urlNoAccess ) );
      die;
    }
  }
  
  /**
   * capitalize
   *
   * @param  string $string
   *
   * @return string $string
   */   
  function capitalize($string)
  {
    $capitalized = '';
    $singleWords = preg_split( "/\W+/m" , $string );
    for($r=0; $r < sizeof($singleWords) ; $r++ ) {
      @$string = substr($string , 0 , $singleWords[$r][1]) .
      strtoupper( substr($singleWords[$r][0], 0,1) ) .
      strtolower( substr($singleWords[$r][0], 1) ) .
      substr( $string , $singleWords[$r][1] + strlen($singleWords[$r][0]) );
    }
    return $string;
  }

  /**
   * toUpper
   *
   * @param  string $sText
   *
   * @return string strtoupper($sText)
   */   
  function toUpper($sText)
  {
    return strtoupper($sText);
  }
  
  /**
   * toLower
   *
   * @param  string $sText
   * @return string strtolower($sText)
   */   
  function toLower($sText)
  {
    return strtolower($sText);
  }
  
  /**
   * http_build_query
   *
   * @param  string $formdata, 
   * @param  string $numeric_prefix default value null, 
   * @param  string $key default value null
   *
   * @return array  $res
   */   
  function http_build_query( $formdata, $numeric_prefix = null, $key = null )
  {
    $res = array();
    foreach ((array)$formdata as $k=>$v) {
      $tmp_key = rawurlencode(is_int($k) ? $numeric_prefix.$k : $k);
      if ($key) $tmp_key = $key.'['.$tmp_key.']';
      if ( is_array($v) || is_object($v) ) {
        $res[] = G::http_build_query($v, null /* or $numeric_prefix if you want to add numeric_prefix to all indexes in array*/, $tmp_key);
      } else {
        $res[] = $tmp_key."=".rawurlencode($v);
      }
      /*
       If you want, you can write this as one string:
       $res[] = ( ( is_array($v) || is_object($v) ) ? G::http_build_query($v, null, $tmp_key) : $tmp_key."=".urlencode($v) );
       */
    }
    $separator = ini_get('arg_separator.output');
    return implode($separator, $res);
  }
  /**
   * Redirect URL
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $parameter
   * @return string
   */
  function header( $parameter ) {
    if ( defined ('ENABLE_ENCRYPT' ) && (ENABLE_ENCRYPT == 'yes') && (substr ( $parameter, 0, 9) == 'location:')) {
      $url = G::encryptUrl ( substr( $parameter, 10) , URL_KEY );
      header ( 'location:' . $url );
    }
    else
      header ( $parameter );
    return ;
  }

  /**
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $permission
   * @param  string $urlNoAccess
   * @return void
   */
  function forceLogin( $permission = "", $urlNoAccess = "" )  {
    global $RBAC;

    if ( isset(  $_SESSION['USER_LOGGED'] ) && $_SESSION['USER_LOGGED'] == '' ) {
      $sys        = (ENABLE_ENCRYPT=='yes'?SYS_SYS :"sys".SYS_SYS);
      $lang       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_LANG) , URL_KEY ):SYS_LANG);
      $skin       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_SKIN) , URL_KEY ):SYS_SKIN);
      $login      = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode('login') , URL_KEY ):'login');
      $loginhtml  = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode('login.html') , URL_KEY ):'login.html');
      $direction  = "/$sys/$lang/$skin/$login/$loginhtml";
      die;
      header ("location: $direction");
      die;
      return;
    }

    $Connection     = new DBConnection;
    $ses            = new DBSession($Connection);
    $stQry          = "SELECT LOG_STATUS FROM LOGIN WHERE LOG_SID = '" . session_id() . "'";
    $dset           = $ses->Execute  ( $stQry );
    $row            = $dset->read();
    $sessionPc      = defined ( 'SESSION_PC' ) ? SESSION_PC  : '' ;
    $sessionBrowser = defined ( 'SESSION_BROWSER' ) ? SESSION_BROWSER  : '' ;
    if (($sessionPc == "1" ) or ( $sessionBrowser == "1"))
    if($row['LOG_STATUS'] == 'X'){
      $sys        = (ENABLE_ENCRYPT=='yes'?SYS_SYS :"sys".SYS_SYS);
      $lang       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_LANG) , URL_KEY ):SYS_LANG);
      $skin       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_SKIN) , URL_KEY ):SYS_SKIN);
      $login      = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode('login') , URL_KEY ):'login');
      $loginhtml  = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode('login.html') , URL_KEY ):'login.html');
      $direction  = "/$sys/$lang/$skin/$login/$loginhtml";
      G::SendMessageXml ('ID_CLOSE_SESSION', "warning");
      header ("location: $direction");
      die;
      return;
    }

    if ( defined( 'SIN_COMPATIBILIDAD_RBAC')  and SIN_COMPATIBILIDAD_RBAC == 1 )
      return;

    if ( $permission == "" ) {
      return;
    }

    if ( is_array($permission) )
      $aux = $permission;
    else
      $aux[0] = $permission;


    $sw = 0;
    for ($i = 0; $i < count ($aux); $i++ ) {
      $res = $RBAC->userCanAccess($aux[$i]);
      if ($res == 1) $sw = 1;
      //print " $aux[$i]  $res $sw <br>";
    }

    if ($sw == 0 && $urlNoAccess != "") {
      $aux        = explode ( '/', $urlNoAccess );
      $sys        = (ENABLE_ENCRYPT=='yes'?SYS_SYS :"/sys".SYS_LANG);
      $lang       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_LANG) , URL_KEY ):SYS_LANG);
      $skin       = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode(SYS_SKIN) , URL_KEY ):SYS_SKIN);
      $login      = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode($aux[0]) , URL_KEY ):$aux[0]);
      $loginhtml  = (ENABLE_ENCRYPT=='yes'?G::encrypt ( urldecode($aux[1]) , URL_KEY ):$aux[1]);
      //header ("location: /$sys/$lang/$skin/$login/$loginhtml");
      header ("location: /fluid/mNE/o9A/mNGm1aLiop3V4qU/dtij4J°gmaLPwKDU3qNn2qXanw");
      die;
    }


    if ($sw == 0) {
      header ("location: /fluid/mNE/o9A/mNGm1aLiop3V4qU/dtij4J°gmaLPwKDU3qNn2qXanw");
      //header ( "location: /sys/" . SYS_LANG . "/" . SYS_SKIN . "/login/noViewPage.html" );
      die;
    }
  }
  /**
   * Add slashes to a string
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $val_old
   * @return string
   */
  function add_slashes($val_old) {

    if (!is_string ($val_old)) $val_old ="$val_old";

    $tamano_cadena   = strlen ($val_old);
    $contador_cadena = 0;
    $new_val         = "";

    for ($contador_cadena=0; $contador_cadena< $tamano_cadena; $contador_cadena ++)
    {
      $car  = $val_old[$contador_cadena];

      if ( $car != chr(34) && $car != chr(39) && $car != chr(92))
      {
        $new_val .= $car;
      }
      else
      {
        if ($car2 != chr (92) )
        {
          //print " xmlvar: $new_val -- $car -- $car2 <br>";
          $new_val .= chr(92) . $car;
        }
        else
        $new_val .= $car;
      }
    }
    return $new_val;
  }
  /**
   * Upload a file and then copy to path+ nameToSave
   *
   * @author Mauricio Veliz <mauricio@colosa.com>
   * @access public
   * @param  string $file
   * @param  string $path
   * @param  string $nameToSave
   * @param  integer $permission
   * @return void
   */
  function uploadFile($file, $path ,$nameToSave, $permission = 0666) 
  {
    try {
      if ($file == '') {
        throw new Exception('The filename is empty!');
      }
      if (filesize($file) > ((((ini_get('upload_max_filesize') + 0)) * 1024) * 1024)) {
        throw new Exception('The size of upload file exceeds the allowed by the server!');
      }
      $oldumask = umask(0);
      if (!is_dir($path)) {
        G::verifyPath($path, true);
      }
      move_uploaded_file($file , $path . "/" . $nameToSave);
      chmod($path . "/" . $nameToSave , $permission);
      umask($oldumask);
    }
    catch (Exception $oException) {
      throw $oException;
    }
  }

  /**
   * resizeImage
   *
   * @param  string $path, 
   * @param  string $resWidth 
   * @param  string $resHeight 
   * @param  string $saveTo default value null
   *
   * @return void
   */   
  function resizeImage($path, $resWidth, $resHeight, $saveTo=null) 
  {
    $imageInfo = @getimagesize($path);

    if (!$imageInfo)
      throw new Exception("Could not get image information");

    list($width, $height) = $imageInfo;
    $percentHeight        = $resHeight / $height;
    $percentWidth         = $resWidth / $width;
    $percent              = ($percentWidth < $percentHeight) ? $percentWidth : $percentHeight;
    $resWidth             = $width * $percent;
    $resHeight            = $height * $percent;

    // Resample
    $image_p = imagecreatetruecolor($resWidth, $resHeight);
    imagealphablending($image_p, false);
    imagesavealpha($image_p, true);

    $background = imagecolorallocate($image_p, 0, 0, 0);
    ImageColorTransparent($image_p, $background); // make the new temp image all transparent

    //Assume 3 channels if we can't find that information
    if (!array_key_exists("channels", $imageInfo))
      $imageInfo["channels"] = 3;
    $memoryNeeded = Round( ($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] + Pow(2, 16)) * 1.95) / (1024*1024);
    if ( $memoryNeeded < 80 ) $memoryNeeded = 80;
    ini_set('memory_limit', intval($memoryNeeded) . 'M');

    $functions = array(
      IMAGETYPE_GIF => array('imagecreatefromgif', 'imagegif'),
      IMAGETYPE_JPEG => array('imagecreatefromjpeg', 'imagejpeg'),
      IMAGETYPE_PNG => array('imagecreatefrompng', 'imagepng'),
    );

    if (!array_key_exists($imageInfo[2], $functions))
      throw new Exception("Image format not supported");

    list($inputFn, $outputFn) = $functions[$imageInfo[2]];

    $image = $inputFn($path);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $resWidth, $resHeight, $width, $height);
    $outputFn($image_p, $saveTo);

    chmod($saveTo, 0666);
  }

  /**
   * Merge 2 arrays
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @return array
   */
  function array_merges() {
    $array  = array();
    $arrays =& func_get_args();
    foreach ($arrays as $array_i) {
      if (is_array($array_i)) {
        G::array_merge_2($array, $array_i);
      }
    }
    return $array;
  }

  /**
   * Merge 2 arrays
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @param  string $array
   * @param  string $array_i
   * @return array
   */
  function array_merge_2(&$array, &$array_i) {
    foreach ($array_i as $k => $v) {
      if (is_array($v)) {
        if (!isset($array[$k])) {
          $array[$k] = array();
        }
        G::array_merge_2($array[$k], $v);
      } else {
        if (isset($array[$k]) && is_array($array[$k])) {
          $array[$k][0] = $v;
        } else {
          if (isset($array) && !is_array($array)) {
            $temp     = $array;
            $array    = array();
            $array[0] = $temp;
          }
          $array[$k]  = $v;
        }
      }
    }
  }

  /**
   * Generate random number
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @return int
   */
  function generateUniqueID() {
    do {
      $sUID = str_replace('.', '0', uniqid(rand(0, 999999999), true));
    } while (strlen($sUID) != 32);
    return $sUID;
    //return strtoupper(substr(uniqid(rand(0, 9), false),0,14));
  }


  /**
   * Generate a numeric or alphanumeric code
   *
   * @author Julio Cesar Laura Avenda𭞼juliocesar@colosa.com>
   * @access public
   * @return string
   */
  function generateCode($iDigits = 4, $sType = 'NUMERIC') {
    if (($iDigits < 4) || ($iDigits > 50)) {
      $iDigits = 4;
    }
    if (($sType != 'NUMERIC') && ($sType != 'ALPHA') && ($sType != 'ALPHANUMERIC')) {
      $sType = 'NUMERIC';
    }
    $aValidCharacters = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
      'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
      'U', 'V', 'W', 'X', 'Y', 'Z');
    switch ($sType) {
      case 'NUMERIC':
        $iMin = 0;
        $iMax = 9;
        break;
      case 'ALPHA':
        $iMin = 10;
        $iMax = 35;
        break;
      case 'ALPHANUMERIC':
        $iMin = 0;
        $iMax = 35;
        break;
    }
    $sCode = '';
    for ($i = 0; $i < $iDigits; $i++) {
      $sCode .= $aValidCharacters[rand($iMin, $iMax)];
    }
    return $sCode;
  }

  /**
   * Verify if the input string is a valid UID
   *
   * @author David Callizaya <davidsantos@colosa.com>
   * @access public
   * @return int
   */
  function verifyUniqueID( $uid ) {
    return (bool) preg_match('/^[0-9A-Za-z]{14,}/',$uid);
  }

  /**
   * is_utf8
   *
   * @param  string $string
   *
   * @return string utf8_encode()
   */   
  function is_utf8($string)
  {
    if (is_array($string))
    {
      $enc = implode('', $string);
      return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
    }
    else
    {
      return (utf8_encode(utf8_decode($string)) == $string);
    }
  }


  /**
   * Return date in Y-m-d format
   *
   * @author Fernando Ontiveros Lira <fernando@colosa.com>
   * @access public
   * @return void
   */
  function CurDate($sFormat = '')
  {
    $sFormat = ( $sFormat != '' )? $sFormat: 'Y-m-d H:i:s';
    return date($sFormat);
  }

  /*
   * Return the System defined constants and Application variables
   *   Constants: SYS_*
   *   Sessions : USER_* , URS_*
   */
  function getSystemConstants($params=NULL)
  {
    $t1 = G::microtime_float();
    $sysCon = array();
    if ( defined('SYS_LANG' )) $sysCon['SYS_LANG'] = SYS_LANG;
    if ( defined('SYS_SKIN' )) $sysCon['SYS_SKIN'] = SYS_SKIN;
    if ( defined('SYS_SYS' ) ) $sysCon['SYS_SYS']  = SYS_SYS;

    if (isset($_SESSION['APPLICATION']) ) $sysCon['APPLICATION'] = $_SESSION['APPLICATION'];
    if (isset($_SESSION['PROCESS'])     ) $sysCon['PROCESS']     = $_SESSION['PROCESS'];
    if (isset($_SESSION['TASK'])        ) $sysCon['TASK']        = $_SESSION['TASK'];
    if (isset($_SESSION['INDEX'])       ) $sysCon['INDEX']       = $_SESSION['INDEX'];
    if (isset($_SESSION['USER_LOGGED']) ) $sysCon['USER_LOGGED'] = $_SESSION['USER_LOGGED'];
    if (isset($_SESSION['USR_USERNAME'])) $sysCon['USR_USERNAME']= $_SESSION['USR_USERNAME'];
    
    ################################################################################################
    # Added for compatibility betweek aplication called from web Entry that uses just WS functions
    ################################################################################################
    
    if( $params != NULL ){
        
      switch($params->option){
        case 'STORED SESSION':
          if( isset($params->SID) ){
            G::LoadClass('sessions');
            $oSessions = new Sessions($params->SID);
            $sysCon = array_merge($sysCon, $oSessions->getGlobals());
          }  
          break;
      }
    }
              
    return $sysCon;
  }


  /*
   * Return the Friendly Title for a string, capitalize every word and remove spaces
   *   param : text string
   */
  function capitalizeWords( $text )
  {
    /*$result = '';
     $space = true;
     for ( $i = 0; $i < strlen ( $text); $i++ ) {
     $car = strtolower ( $text[$i] );
     if ( strpos( "abcdefghijklmnopqrstuvwxyz1234567890", $car ) !== false ) {
     if ($space ) $car = strtoupper ( $car );
     $result .= $car;
     $space  = false;
     }
     else
     $space = true;
     }
     return $result;*/
    if (function_exists('mb_detect_encoding')) {
      if (strtoupper(mb_detect_encoding($text)) == 'UTF-8') {
        $text = utf8_encode($text);
      }
    }
    if(function_exists('mb_ucwords')) {
      return mb_ucwords($text);
    }
    else {
      return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
    }
  }

  /**
   * unhtmlentities
   *
   * @param  string $string
   *
   * @return string substring 
   */   
  function unhtmlentities ($string)
  {
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    foreach($trans_tbl as $k => $v)
    {
      $ttr[$v] = utf8_encode($k);
    }
    return strtr ($string, $ttr);
  }

  /*************************************** init **********************************************
   * Xml parse collection functions
   * Returns a associative array within the xml structure and data
   *
   * @author Erik Amaru Ortiz <erik@colosa.com>
   */
  function xmlParser(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);

    $mnary = array();
    $ary   =&$mnary;
    foreach ($vals as $r) {
      $t=$r['tag'];
      if ($r['type']=='open') {
        if (isset($ary[$t])) {
          if (isset($ary[$t][0])) 
            $ary[$t][]=array();
          else 
            $ary[$t]=array($ary[$t], array());
          $cv=&$ary[$t][count($ary[$t])-1];
        } else $cv=&$ary[$t];
        if (isset($r['attributes'])) {
          foreach ($r['attributes'] as $k=>$v) $cv['__ATTRIBUTES__'][$k]=$v;
        }
        // note by gustavo cruz gustavo[at]colosa[dot]com
        // minor adjustments to validate if an open node have a value attribute.
        // for example a dropdown has many childs, but also can have a value attribute.
        if (isset($r['value']) && trim($r['value'])!=''){
          $cv['__VALUE__'] = $r['value'];
        }
        // end added code
        $cv['__CONTENT__']       = array();
        $cv['__CONTENT__']['_p'] =&$ary;
        $ary                     =&$cv['__CONTENT__'];

      } elseif ($r['type']=='complete') {
        if (isset($ary[$t])) { // same as open
          if (isset($ary[$t][0])) $ary[$t][]=array();
          else $ary[$t]=array($ary[$t], array());
          $cv=&$ary[$t][count($ary[$t])-1];
        } else $cv=&$ary[$t];
        if (isset($r['attributes'])) {
          foreach ($r['attributes'] as $k=>$v) $cv['__ATTRIBUTES__'][$k]=$v;
        }
        $cv['__VALUE__']=(isset($r['value']) ? $r['value'] : '');

      } elseif ($r['type']=='close') {
        $ary=&$ary['_p'];
      }
    }

    self::_del_p($mnary);

    $obj_resp->code    = xml_get_error_code($parser);
    $obj_resp->message = xml_error_string($obj_resp->code);
    $obj_resp->result  = $mnary;
    xml_parser_free($parser);

    return $obj_resp;
  }

  /**
   * _del_p
   *
   * @param  string &$ary
   *
   * @return void
   */    
  // _Internal: Remove recursion in result array
  function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
      if ($k==='_p') unset($ary[$k]);
      elseif (is_array($ary[$k])) self::_del_p($ary[$k]);
    }
  }

  /**
   * ary2xml
   *
   * Array to XML
   *
   * @param  string $cary 
   * @param  string $d=0 
   * @param  string $forcetag default value ''
   *
   * @return void
   */    
  // Array to XML
  function ary2xml($cary, $d=0, $forcetag='') {
    $res = array();
    foreach ($cary as $tag=>$r) {
      if (isset($r[0])) {
        $res[]=self::ary2xml($r, $d, $tag);
      } else {
        if ($forcetag) $tag=$forcetag;
        $sp    = str_repeat("\t", $d);
        $res[] = "$sp<$tag";
        if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
        $res[] = ">".((isset($r['_c'])) ? "\n" : '');
        if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
        elseif (isset($r['_v'])) $res[]=$r['_v'];
        $res[] = (isset($r['_c']) ? $sp : '')."</$tag>\n";
      }

    }
    return implode('', $res);
  }

  /**
   * ins2ary
   *
   * Insert element into array
   *
   * @param  string &$ary
   * @param  string $element
   * @param  string $pos
   *
   * @return void
   */  
  // Insert element into array
  function ins2ary(&$ary, $element, $pos) 
  {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
  }

  /*
   * Xml parse collection functions
   *************************************** end **********************************************/


  /**
   * evalJScript
   *
   * @param  string $c
   *
   * @return void
   */   
  function evalJScript($c){
    print("<script language=\"javascript\">{$c}</script>");
  }


  /**
   *  Inflects a string with accented characters and other characteres not suitable for file names, by defaul replace with undescore
   *
   *  @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gamil.com>
   *  @param (string) string to convert
   *  @param (string) character for replace
   *  @param (array) additional characteres map
   *
   */
  function inflect($string, $replacement = '_', $map = array()) {
    if (is_array($replacement)) {
      $map = $replacement;
      $replacement = '_';
    }

    $quotedReplacement = preg_quote($replacement, '/');

    $default = array(
                '/à|á|å|â/' => 'a',
                '/è|é|ê|ẽ|ë/' => 'e',
                '/ì|í|î/' => 'i',
                '/ò|ó|ô|ø/' => 'o',
                '/ù|ú|ů|û/' => 'u',
                '/ç/' => 'c',
                '/ñ/' => 'n',
                '/ä|æ/' => 'ae',
                '/ö/' => 'oe',
                '/ü/' => 'ue',
                '/Ä/' => 'Ae',
                '/Ü/' => 'Ue',
                '/Ö/' => 'Oe',
                '/ß/' => 'ss',
                '/\.|\,|\:|\-|\\|\//' =>  " ",
                '/\\s+/' => $replacement
              );

    $map = array_merge($default, $map);
    return preg_replace(array_keys($map), array_values($map), $string);
  }

  /**
   * pr
   *
   * @param  string $var
   *
   * @return void
   */   
  function pr($var)
  {
    print("<pre>");
    print_r($var);
    print("</pre>");
  }

  /**
   * dump
   *
   * @param  string $var
   *
   * @return void
   */   
  function dump($var){
    print("<pre>");
    var_dump($var);
    print("</pre>");
  }

  /**
   * stripCDATA
   *
   * @param  string $string
   *
   * @return string str_replace
   */   
  function stripCDATA($string){
    preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $string, $matches);
    return str_replace($matches[0], $matches[1], $string);
  }
  
  /**
   * Get the temporal directory path on differents O.S.  i.e. /temp -> linux, C:/Temp -> win 
   * @author <erik@colosa.com> 
   */
  function getSysTemDir() {
    if ( !function_exists('sys_get_temp_dir') ){
      // Based on http://www.phpit.net/
      // article/creating-zip-tar-archives-dynamically-php/2/
      // Try to get from environment variable
      if ( !empty($_ENV['TMP']) ){
        return realpath( $_ENV['TMP'] );
      } else if ( !empty($_ENV['TMPDIR']) ){
        return realpath( $_ENV['TMPDIR'] );
      } else if ( !empty($_ENV['TEMP']) ){
        return realpath( $_ENV['TEMP'] );
      } else {// Detect by creating a temporary file
        // Try to use system's temporary directory
        // as random name shouldn't exist
        $temp_file = tempnam( md5(uniqid(rand(), TRUE)), '' );
        if ( $temp_file ){
          $temp_dir = realpath( dirname($temp_file) );
          unlink( $temp_file );
          return $temp_dir;
        } else {
          return FALSE;
        }
      }
    } else {
      return sys_get_temp_dir();
    }
  }
  
  /**
   * Get the content of a compose pmos web service response
   * Returns an array when has a valid reponse, if the response is invalid returns an object containing a status_code and message properties.
   *   
   * @author <erik@colosa.com> 
   */
  function PMWSCompositeResponse($oResp, $prop) {
    $Resp = new stdClass();
    
    if( is_object($oResp) && isset($oResp->{$prop}) ){
      $list = $oResp->{$prop};
      
      if( is_object($list) ){
        $aList[0] = $list; 
      } else {
        $aList = $list;
      }
      
      $result = true;
      if( is_array($aList) ){
        foreach($aList as $item){
          if( !isset($item->guid) ){
            $result = false;
            break;
          }
        }
      } else {
        $Resp->status_code = -1;
        $Resp->message = "Bad respose type for ({$prop})";
      }
      
      if( $result ){
        //verifing if the response has a composite response into a guid value of the first row.
        $tmp = explode(' ', trim($aList[0]->guid));
        if( sizeof($tmp) >= 2 ){ //the guid can't has a space, so this should be a ws response
          $Resp->status_code = $tmp[0];
          $Resp->message = substr($aList[0]->guid, strpos($aList[0]->guid, ' ') + 1);
        } else {
          return $aList;
        }
           
      } else {
        $Resp->status_code = -2;
        $Resp->message = "Bad respose, the response has not a uniform struct.";
      }
    } else if( is_object($oResp) ){
      return Array();
    } else {
      $Resp->status_code = -1;
      $Resp->message = "1 Bad respose type for ({$prop})";
    }
    return $Resp;
  }
    
  /**
   * Validate and emai address in complete forms, 
   * 
   * @author Erik A.O. <erik@gmail.com, aortiz.erik@gmail.com>
   * i.e. if the param. is 'erik a.o. <erik@colosa.com>' 
   *      -> returns a object within $o->email => erik@colosa.com and $o->name => erik A.O. in other case returns false  
   *  
   */
  function emailAddress($sEmail){
    $o = new stdClass();
    if( strpos($sEmail, '<') !== false ) {
      preg_match('/([\"\w@\.-_\s]*\s*)?(<(\w+[\.-]?\w+]*@\w+([\.-]?\w+)*\.\w{2,3})+>)/', $sEmail, $matches);
      g::pr($matches);
      if( isset($matches[1]) && $matches[3]){
        $o->email = $matches[3];
        $o->name = $matches[1];
        return $o; 
      }
      return false;
    } else {
      preg_match('/\w+[\.-]?\w+]*@\w+([\.-]?\w+)*\.\w{2,3}+/', $sEmail, $matches);
      if( isset($matches[0]) ){
        $o->email = $matches[0];
        $o->name = '';
        return $o; 
      }
      return false; 
    }
  }
  
  /**
   * JSON encode 
   * 
   * @author Erik A.O. <erik@gmail.com, aortiz.erik@gmail.com>
   */
  function json_encode($Json){
    if( function_exists('json_encode') ){
      return json_encode($Json);
    } else {
      G::LoadThirdParty('pear/json', 'class.json');
      $oJSON = new Services_JSON();
      return $oJSON->encode($Json);
    }
  }
  
  /**
   * JSON decode 
   * 
   * @author Erik A.O. <erik@gmail.com, aortiz.erik@gmail.com>
   */
  function json_decode($Json){
    if( function_exists('json_decode') ){
      return json_decode($Json);
    } else {
      G::LoadThirdParty('pear/json', 'class.json');
      $oJSON = new Services_JSON();
      return $oJSON->decode($Json);
    }
  }  
  
  /**
   * isHttpRequest
   *
   * @return boolean true or false
   */   
  function isHttpRequest(){
    if( isset($_SERVER['SERVER_SOFTWARE']) && strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache') !== false ){
      return true;
    }
    return false;
  }
    
  /**
   * Get the type of a variable
   * Returns the type of the PHP variable var. 
   *
   * @author Erik A. Ortiz. <erik@colosa.com>
   * @return (string) type of variable
   */ 
  public function gettype($var) {
      switch ($var) {
        case is_null($var):
          $type='NULL';
          break;
           
        case is_bool($var):
          $type='boolean';
          break;

        case is_float($var):
          $type='double';
          break;

        case is_int($var):
          $type='integer';
          break;

        case is_string($var):
          $type='string';
          break;

        case is_array($var):
          $type='array';
          break;

        case is_object($var):
          $type='object';
          break;

        case is_resource($var):
          $type='resource';
          break;

        default:
          $type='unknown type';
          break;
      }

      return $type;
    }

  function removeComments($buffer)
  {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
  }

  function getMemoryUsage(){
    $size = memory_get_usage(true);
    $unit=array('B','Kb','Mb','Gb','Tb','Pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
  }

  function getFormatUserList($format, $aUserInfo){
  	
   	switch($format){
     case '@firstName @lastName':
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $format);
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $infoUser);
     break;
     case '@firstName @lastName (@userName)':
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $format);
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $infoUser);
     $infoUser = str_replace('@userName', $aUserInfo['USR_USERNAME'], $infoUser);
     break;
     case '@userName':
     $infoUser = str_replace('@userName', $aUserInfo['USR_USERNAME'], $format);
     break;
     case '@userName (@firstName @lastName)':
     $infoUser = str_replace('@userName', $aUserInfo['USR_USERNAME'], $format);
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $infoUser);
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $infoUser);
     break;
     case '@lastName @firstName':
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $format);
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $infoUser);
     break;
     case '@lastName, @firstName':	
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $format);
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $infoUser);
     break;
     case '@lastName, @firstName (@userName)':
     $infoUser = str_replace('@lastName', $aUserInfo['USR_LASTNAME'], $format);
     $infoUser = str_replace('@firstName', $aUserInfo['USR_FIRSTNAME'], $infoUser);
     $infoUser = str_replace('@userName', $aUserInfo['USR_USERNAME'], $infoUser);
     break;
     default :
     $infoUser = str_replace('@userName', $aUserInfo['USR_USERNAME'], '@userName');
     break;
     }
  	return $infoUser;
  }

  function getModel($model){
    require_once "classes/model/$model.php";
    return new $model();
  }
  
  /**
   * Recursive Is writeable function
   * 
   * @author Erik Amaru Ortiz <erik@colosa.com>
   * 
   * @param $path path to scan recursively the write permission
   * @param $pattern pattern to filter some especified files
   * @return <boolean> if the $path, assuming that is a directory -> all files in it are writeables or not 
   */
  function is_rwritable($path, $pattern='*')
  {
    $files = G::rglob($pattern, 0, $path);
    foreach ($files as $file) {
      if( ! is_writable($file) )
        return false;
    }
    return true;
  }
  
  /**
   * Recursive version of glob php standard function
   * 
   * @author Erik Amaru Ortiz <erik@colosa.com>
   * 
   * @param $path path to scan recursively the write permission
   * @param $flags to notive glob function
   * @param $pattern pattern to filter some especified files
   * @return <array> array containing the recursive glob results 
   */
  function rglob($pattern='*', $flags = 0, $path='')
  {
    $paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files = glob($path.$pattern, $flags);
    foreach ($paths as $path) { 
      $files = array_merge($files, G::rglob($pattern, $flags, $path)); 
    }
    return $files;
  }
};

/**
 * eprint
 *
 * @param  string $s default value ''
 * @param  string $c default value null
 *
 * @return void
 */   
function eprint($s = "", $c = null){
  if( G::isHttpRequest() ){
    if(isset($c)){
      echo "<pre style='color:$c'>$s</pre>";
    } else 
      echo "<pre>$s</pre>";
  } else {
    if(isset($c)){
      switch($c){
        case 'green':
          printf("\033[0;35;32m$s\033[0m");
          return;
        case 'red':
          printf("\033[0;35;31m$s\033[0m");
          return;
        case 'blue':
          printf("\033[0;35;34m$s\033[0m");
          return;
        default: print "$s";
      }
    } else 
      print "$s";
  }
}

/**
 * println
 *
 * @param  string $s
 *
 * @return eprintln($s)
 */   
function println($s){
  return eprintln($s);
}

/**
 * eprintln
 *
 * @param  string $s
 * @param  string $c
 *
 * @return void
 */   
function eprintln($s="", $c=null){
  if( G::isHttpRequest() ){
    if(isset($c)){
      echo "<pre style='color:$c'>$s</pre>";
    } else 
      echo "<pre>$s</pre>";
  } else {
    if(isset($c) && (PHP_OS != 'WINNT')){
      switch($c){
        case 'green':
          printf("\033[0;35;32m$s\033[0m\n");
          return;
        case 'red':
          printf("\033[0;35;31m$s\033[0m\n");
          return;
        case 'blue':
          printf("\033[0;35;34m$s\033[0m\n");
          return;
      }
    }
    print "$s\n";
  }
}
