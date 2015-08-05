<?php
try {
    //Set variables
    $osIsLinux = strtoupper(substr(PHP_OS, 0, 3)) != "WIN";

    //Defines constants
    define("PATH_SEP", ($osIsLinux)? "/" : "\\");

    $arrayPathToCron = array();
    $flagPathToCron = false;

    //Path to CRON by __FILE__
    $arrayAux = explode(PATH_SEP, str_replace("engine" . PATH_SEP . "bin", "", dirname(__FILE__)));

    array_pop($arrayAux);

    if (count($arrayAux) > 0 && $arrayAux[count($arrayAux) - 1] == "workflow") {
        $arrayPathToCron = $arrayAux;
        $flagPathToCron = true;
    }

    if (!$flagPathToCron) {
        throw new Exception("Error: Unable to execute the Timer-Event CRON, the path is incorrect");
    }

    $pathHome = implode(PATH_SEP, $arrayPathToCron) . PATH_SEP;

    array_pop($arrayPathToCron);

    $pathTrunk = implode(PATH_SEP, $arrayPathToCron) . PATH_SEP;

    array_pop($arrayPathToCron);

    $pathOutTrunk = implode(PATH_SEP, $arrayPathToCron) . PATH_SEP;

    define("PATH_HOME",     $pathHome);
    define("PATH_TRUNK",    $pathTrunk);
    define("PATH_OUTTRUNK", $pathOutTrunk);

    //Include files
    require_once(PATH_HOME . "engine" . PATH_SEP . "config" . PATH_SEP . "paths.php");

    G::LoadClass("system");

    $config = System::getSystemConfiguration();

    G::LoadSystem('inputfilter');
    $filter = new InputFilter();
    $config['time_zone'] = $filter->validateInput($config['time_zone']);

    ini_set("date.timezone", $config["time_zone"]);

    //CRON command options
    $arrayCommandOption = array(
        "force" => "+force"
    );

    //CRON status
    $flagIsRunning = false;
    $lastExecution = "";
    $processcTimeProcess = 0;
    $processcTimeStart   = 0;

    $force = in_array($arrayCommandOption["force"], $argv);

    if (!$force && file_exists(PATH_DATA . "timereventcron")) {
        //Windows flag
        //Get data of CRON file
        $arrayCron = unserialize(trim(file_get_contents(PATH_DATA . "timereventcron")));

        $flagIsRunning = (boolean)($arrayCron["flagIsRunning"]);
        $lastExecution = $arrayCron["lastExecution"];
        $processcTimeProcess = (isset($arrayCron["processcTimeProcess"]))? (int)($arrayCron["processcTimeProcess"]) : 10; //Minutes
        $processcTimeStart   = (isset($arrayCron["processcTimeStart"]))? $arrayCron["processcTimeStart"] : 0;
    }

    if (!$force && $osIsLinux) {
        //Linux flag
        //Check if CRON it's running
        exec("ps -fea | grep timereventcron.php | grep -v grep", $arrayOutput);

        if (count($arrayOutput) > 1) {
            $flagIsRunning = true;
        }
    }

    if ($force || !$flagIsRunning) {
        //Start CRON
        $arrayCron = array("flagIsRunning" => "1", "lastExecution" => date("Y-m-d H:i:s"));
        file_put_contents(PATH_DATA . "timereventcron", serialize($arrayCron));

        try {
            $messageEventCronSinglePath = PATH_CORE . "bin" . PATH_SEP . "timereventcron_single.php";

            $workspace = "";

            for ($i = 1; $i <= count($argv) - 1; $i++) {
                if (preg_match("/^\+w(.+)$/", $argv[$i], $arrayMatch)) {
                    $workspace = $arrayMatch[1];
                    break;
                }
            }

            $countw = 0;

            if ($workspace == "") {
                $d = dir(PATH_DB);

                while (($entry = $d->read()) !== false) {
                    if ($entry != "" && $entry != "." && $entry != "..") {
                        if (is_dir(PATH_DB . $entry)) {
                            if (file_exists(PATH_DB . $entry . PATH_SEP . "db.php")) {
                                $countw++;

                                passthru("php -f \"$messageEventCronSinglePath\" $entry \"" . base64_encode(PATH_HOME) . "\" \"" . base64_encode(PATH_TRUNK) . "\" \"" . base64_encode(PATH_OUTTRUNK) . "\"");
                            }
                        }
                    }
                }
            } else {
                if (!is_dir(PATH_DB . $workspace) || !file_exists(PATH_DB . $workspace . PATH_SEP . "db.php")) {
                    throw new Exception("Error: The workspace \"$workspace\" does not exist");
                }

                $countw++;

                passthru("php -f \"$messageEventCronSinglePath\" $workspace \"" . base64_encode(PATH_HOME) . "\" \"" . base64_encode(PATH_TRUNK) . "\" \"" . base64_encode(PATH_OUTTRUNK) . "\"");
            }

            eprintln("Finished $countw workspaces processed");
        } catch (Exception $e) {
            throw $e;
        }

        //End CRON
        $arrayCron = array("flagIsRunning" => "0", "lastExecution" => date("Y-m-d H:i:s"));
        file_put_contents(PATH_DATA . "timereventcron", serialize($arrayCron));
    } else {
        eprintln("The Timer-Event CRON is running, please wait for it to finish\nStarted in $lastExecution");
        eprintln("If do you want force the execution use the option \"" . $arrayCommandOption["force"] . "\", example: php -f timereventcron.php +wworkflow " . $arrayCommandOption["force"] ,"green");
    }

    echo "Done!\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

