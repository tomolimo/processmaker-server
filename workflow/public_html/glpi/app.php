<?php

require_once './api/vendor/autoload.php';

/**
 * Summary of glpi_isHTML
 * @return boolean true if content-type is HTML
 */
function glpi_isHTML() {
   $needle = 'content-type';
   $type = 'text/html';
   foreach (headers_list() as $val) {
      $parts = explode( ":", $val);
      if (strcasecmp( $parts[0], $needle ) == 0) {
         $subparts = explode( ";", $parts[1] );
         if (strcasecmp( trim($subparts[0]), $type ) != 0) {
            return false;
         }
      }
   }
   return true;
}

/**
 * Summary of glpi_ob_handler
 * returns output buffer with document.domain when needed
 * @param string $buffer
 * @return string
 */
function glpi_ob_handler($buffer) {
   //global $RBAC;

   if (isset($_SERVER['HTTP_ORIGIN'])) {
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header("Access-Control-Allow-Credentials: true");
   }

   if (glpi_isHTML()) { // if payload is empty will pass

      // to prevent error message about ProcessMaker existing in another tab
      setcookie("PM-TabPrimary", '101010010', time() + (24 * 60 * 60), '/');

      $matches = [];
      if (preg_match("/(?'start'.*?<script)(?'end'.*)/sm", $buffer, $matches)
          && !preg_match('@/glpi/glpi_helpers.js\?v=@i', $buffer)) {

         $buffer = $matches['start']." type='text/javascript'>";

         // add GLPI data
         if (isset($_REQUEST['glpi_data'])) {
            $glpi_data = json_decode($_REQUEST['glpi_data'], true);
            $glpi_data['pm_current_step_position'] = 1;
            if (isset($_SESSION['STEP_POSITION'])) {
                $glpi_data['pm_current_step_position'] = $_SESSION['STEP_POSITION'];
            }
            $_REQUEST['glpi_data'] = json_encode($glpi_data);
            $buffer .= "var GLPI_DATA = ".$_REQUEST['glpi_data'];
         }
         $buffer .= "</script>";

         // add default glpi.js and glpi_helpers.js
         $buffer .= "<script type='text/javascript' src='/glpi/glpi.js?v=" . $_SESSION['PM_VERSION'] . "'></script>";
         $buffer .= "<script type='text/javascript' src='/glpi/glpi_helpers.js?v=" . $_SESSION['PM_VERSION'] . "'></script>";

         // add some stuff to change CSS
         $buffer .= "<script type='text/javascript'>
            window.addEventListener('load',
               function() {
                  //debugger;
                  glpi.setClassAttribute('panel_modal___processmaker', 'background-color', 'rgb(170, 170, 170)');
                  glpi.setClassAttribute('panel_modal___processmaker', 'opacity', '0.3');
               }
            );
            </script>
         ";

         $buffer .= "<script ".$matches['end'];
      }
   }
   return $buffer;
}

/**
 * Summary of glpi_session_name
 * will prepare unique name for session
 * to prevent mix of sessions when used
 * in and out of GLPI
 * @return string the current skin
 */
function glpi_session_name() {

   ini_set("session.cookie_httponly", 1);

   if (isset($_SERVER['UNENCODED_URL'])) {
      $_SERVER['REQUEST_URI'] = $_SERVER['UNENCODED_URL'];
   }

   $rootDir = realpath(__DIR__ . "/../../") . DIRECTORY_SEPARATOR;

   $addToSessionRegEx = '@^(https{0,1}://[^/]+){0,1}/sys(?\'workspace\'[^/]*)/[^/]+/(?\'skin\'[^/]+)/@i';
   $addToSessionRegEx2 = '@^/sys(?\'workspace\'[^/]*)/[^/]+/(?\'skin\'[^/]+)/@i';
   if (!isset( $_SERVER['HTTP_REFERER'] ) || preg_match($addToSessionRegEx, $_SERVER['HTTP_REFERER'], $matches) == 0) {
      preg_match($addToSessionRegEx2, $_SERVER['REQUEST_URI'], $matches);
   }

   $ret = '';
   if (is_array($matches) && isset($matches['workspace'])) {
      if ($matches['workspace'] == '') {
         if (isset($_REQUEST['form']['USER_ENV'])) {
            $matches['workspace'] = $_REQUEST['form']['USER_ENV'];
         } elseif (isset($_COOKIE['pm_sys_sys'])) {
            $decode = json_decode($_COOKIE['pm_sys_sys'], true);
            $matches['workspace'] = $decode['sys_sys'];
            $matches['skin'] = $_COOKIE['workspaceSkin'];
         }
      }

      session_name("pm_" . md5($rootDir) . "_" . $matches['workspace'] . "_" . $matches['skin']);
      $ret = $matches['skin'];

   } else {

      $ret=array_pop($matches);
      session_name("pm_".md5($rootDir)."_".$ret);
   }


   return $ret;
}


$sesssion_name = glpi_session_name();
if (stripos($sesssion_name, 'glpi_') === 0) {
   // we have been called by GLPI
   ob_start( "glpi_ob_handler" );
   ob_start( "glpi_ob_handler" ); // seems like there are too much ob_clean() in PM source code

   if (isset($_SERVER['HTTP_REFERER'])
      && preg_match("@/designer@i", $_SERVER['HTTP_REFERER'])
      && $_SERVER['REQUEST_METHOD'] == 'PUT') {
      // then must cancel this PUT call
      // to prevent saving of the map with extra text
      die();
   }
}

// clean session of _DBArray availableUsers record
session_start();
if (isset( $_SERVER['HTTP_REFERER'] ) && preg_match( "@/cases/main_init$@i", $_SERVER['HTTP_REFERER'] )) {
   unset($_SESSION['_DBArray']['availableUsers']);
}

if (!defined('PM_VERSION')) {
   if (file_exists("../../engine/methods/login/version-pmos.php")) {
      include_once("../../engine/methods/login/version-pmos.php");
   }
   $_SESSION['PM_VERSION'] = PM_VERSION;
}

session_write_close();
// back to PM normal app.php
include '../app.php';