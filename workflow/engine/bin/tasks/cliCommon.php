<?php
/**
 * From http://www.php.net/manual/en/function.getopt.php#83414
 * Parses $args for parameters and assigns them to an array.
 *
 * Supports:
 * -e
 * -e <value>
 * --long-param
 * --long-param=<value>
 * --long-param <value>
 * <value>
 *
 * @param array $noopt List of parameters without values
 */
function parse_args($args, $noopt = array()) {
  $result = array();
  while (list($tmp, $p) = each($args)) {
      if ($p{0} == '-') {
          $pname = substr($p, 1);
          $value = true;
          if ($pname{0} == '-') {
              // long-opt (--<param>)
              $pname = substr($pname, 1);
              if (strpos($p, '=') !== false) {
                  // value specified inline (--<param>=<value>)
                  list($pname, $value) = explode('=', substr($p, 2), 2);
              }
          }
          // check if next parameter is a descriptor or a value
          $nextparm = current($args);
          if (!in_array($pname, $noopt) && $value === true && $nextparm !== false && $nextparm{0} != '-') list($tmp, $value) = each($args);
          $result[$pname] = $value;
      } else {
          // param doesn't belong to any option
          $result[] = $p;
      }
  }
  return $result;
}

function info($message) {
  return pakeColor::colorize($message, "INFO");
}

function error($message) {
  return pakeColor::colorize($message, "ERROR");
}

function logging($message, $filename = NULL) {
  static $log_file = NULL;
  if (isset($filename)) {
    $log_file = fopen($filename, "a");
    fwrite($log_file, " -- " . date("c") . " " . $message . " --\n");
  } else {
    if (isset($log_file))
      fwrite($log_file, $message);
    echo $message;
  }
}

function get_workspaces_from_args($args, $includeAll = true) {
  $opts = parse_args($args);
  $workspaces = array();
  foreach ($opts as $opt => $arg) {
    if (is_int($opt)) {
      $workspaces[] = new workspaceTools($arg);
    }
  }
  if (empty($workspaces) && $includeAll) {
    $workspaces = System::listWorkspaces();
  }
  return $workspaces;
}

?>
