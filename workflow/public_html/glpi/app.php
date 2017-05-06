<?php

 /**
   * Summary of getCommonDomain
   * @param mixed $url1
   * @param mixed $url2
   * @return string the common domain part of the given urls
   */
function glpi_getCommonDomain($url1, $url2) {
   $domain = '';
   try {
      $glpi = explode(".", parse_url($url1, PHP_URL_HOST));
      $pm = explode( ".", parse_url($url2, PHP_URL_HOST));
      $cglpi = array_pop( $glpi );
      $cpm = array_pop( $pm );
      while( $cglpi && $cpm && $cglpi == $cpm ) {
         $domain = $cglpi.($domain==''?'':'.'.$domain) ;
         $cglpi = array_pop( $glpi ) ;
         $cpm = array_pop( $pm ) ;
      }
      if( $domain != '' ) {
         return $domain ;
      }
   } catch(Exception $e) {}
   return '';
}

/**
 * Summary of glpi_isHTML
 * @return boolean true if content-type is HTML
 */
function glpi_isHTML( ) {
   $needle = 'content-type' ;
   $type = 'text/html' ;
   foreach( headers_list() as $val ) {
      $parts = explode( ":", $val);
      if( strcasecmp( $parts[0], $needle ) == 0 ) {
         $subparts = explode( ";", $parts[1] ) ;
         if( strcasecmp( trim($subparts[0]), $type ) != 0 ){
            return false ;
         }
      }
   }
   return true ;
}

/**
 * Summary of glpi_ob_handler
 * returns output buffer with document.domain when needed
 * @param string $buffer
 * @return string
 */
function glpi_ob_handler(  $buffer ){
   //global $Fields;

   if( glpi_isHTML() ) {
      $matches = array();

      // try to get GLPI_DOMAIN from current case
      if( preg_match("/(?'start'.*?<script)(?'end'.*)/sm", $buffer,  $matches ) ) {
         if( !isset($_SESSION['GLPI_DOMAIN']) ) {
            $domain = glpi_getCommonDomain( $_SERVER['HTTP_REFERER'], 'http://'.$_SERVER['HTTP_HOST'] ) ;
            $_SESSION['GLPI_DOMAIN'] = $domain;
         }
         $domain = $_SESSION['GLPI_DOMAIN'];

         // add our domain to script list
         $buffer = $matches['start']." type='text/javascript'>";
         if( $domain != '' ) {
            $buffer .= "document.domain='$domain';" ;
         }
         $buffer .= "</script><script type='text/javascript' src='/glpi/glpi_helpers.js'></script>";
         $buffer .= "<script type='text/javascript'>
            window.addEventListener('load',
               function() {
                  //debugger;
                  glpi.setClassAttribute( 'panel_modal___processmaker', 'background-color', 'rgb(170, 170, 170)') ;
                  glpi.setClassAttribute( 'panel_modal___processmaker', 'opacity', '0.3') ;
               }
            );
            </script>
         ";
         $buffer .= "<script ".$matches['end'];
      }

      if( false ) {
         if( preg_match('@(?\'start\'.*(?>href\s*=\s*"/lib/css/mafe-[0-9a-z]+-[0-9a-z]+\.css"\s*/>))(?\'end\'.*)@sm', $buffer, $matches ) ) {
            // add our fixfilter.js file
            // this file will empty .rotateText class
            // to be compatible with IE9
            $buffer = $matches['start']." <script type='text/javascript' src='/glpi/fixfilter.js'></script> ".$matches['end'];
         }

         if( preg_match('@(?\'start\'.*(?=src\s*=\s*"/lib/js/mafe-[0-9a-z]+-[0-9a-z]+\.js"\s*>))(?\'end\'.*)@sm', $buffer, $matches ) ) {
            // add our classlist.js and object-watch.js files
            // classlist.js adds classList to HTMLElement prototype which doesn't exist in IE9 and earlier
            // and object-watch.js fixes an error in function enviromentVariables() (needed in IE10 and after?)
            $buffer = $matches['start']." src='/glpi/classlist.js'></script>";
            $buffer .= "<script type='text/javascript' src='/glpi/object-watch.js'></script>";
            $buffer .= "<script type='text/javascript' ".$matches['end'];
         }
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

   $addToSessionRegEx = '@^(https{0,1}://[^/]+){0,1}/sys[^/]*/[^/]+/([^/]+)/@i' ;
   $addToSessionRegEx2 = '@^/sys[^/]*/[^/]+/([^/]+)/@i' ;
   if( !isset( $_SERVER['HTTP_REFERER'] ) || preg_match($addToSessionRegEx,  $_SERVER['HTTP_REFERER'], $matches) == 0 ) {
      preg_match($addToSessionRegEx2,  $_SERVER['REQUEST_URI'], $matches) ;
   }

   $skin=array_pop($matches);

   session_name("pm_".md5($rootDir)."_".$skin) ;

   return $skin ;
}

if( stripos( glpi_session_name(), 'glpi_' ) === 0 ) {
   ob_start( "glpi_ob_handler" ) ;
}

// back to PM normal app.php
include '../app.php' ;