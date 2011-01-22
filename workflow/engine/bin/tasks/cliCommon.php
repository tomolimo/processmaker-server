<?php
/**
 * @package workflow-engine-bin-tasks
 **/

/* Get the size of the terminal (only works on Linux, on Windows it's always 80) */
preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);
if(sizeof($output) == 3) {
  define("COLUMNS", $output[2][0]);
} else {
  define("COLUMNS", 80);
}

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

function prompt($message) {
  echo "$message";
  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  return $line;
}

function question($message) {
  $input = strtolower(prompt("$message [Y/n] "));
  return (array_search(trim($input), array("y", "")) !== false);
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

function progress($done, $total, $size=30) {

    static $start_time;

    // if we go over our bound, just ignore it
    if($done > $total) return;

    if(empty($start_time)) $start_time=time();
    $now = time();

    $perc=(double)($done/$total);

    $bar=floor($perc*$size);

    $status_bar="\r[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
        $status_bar.=">";
        $status_bar.=str_repeat(" ", $size-$bar);
    } else {
        $status_bar.="=";
    }

    $disp=number_format($perc*100, 0);

    $status_bar.="] $disp%  $done/$total";

    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $start_time;

    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

    echo "$status_bar  ";

    flush();

    // when done, send a newline
    if($done == $total) {
        echo "\n";
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
