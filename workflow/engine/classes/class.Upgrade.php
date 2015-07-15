<?php

G::LoadClass("system");
G::LoadClass("wsTools");

function ls_dir($dir, $basename = null)
{
    $files = array();
    //if (substr($dir, -1) != "/")
    //  $dir .= "/";
    if ($basename == null) {
        $basename = $dir;
    }
    foreach (glob("$dir/*") as $filename) {
        //var_dump(substr($filename, strlen($basename) + 1));
        if (is_dir($filename)) {
            $files = array_merge($files, ls_dir($filename, $basename));
        } else {
            $files[] = substr($filename, strlen($basename) + 1);
        }
    }
    return $files;
}

class Upgrade
{
    private $addon = null;

    public function __construct($addon)
    {
        $this->addon = $addon;
    }

    public function install()
    {
        G::LoadSystem('inputfilter');
        $filter = new InputFilter();
        //echo "Starting core installation...\n";
        $start = microtime(1);
        $filename = $this->addon->getDownloadFilename();
        $time = microtime(1);
        G::LoadThirdParty( 'pear/Archive','Tar');
        $archive = new Archive_Tar ($filename);
        //printf("Time to open archive: %f\n", microtime(1) - $time);
        $time = microtime(1);
        $extractDir = dirname($this->addon->getDownloadFilename()) . "/extract";
        $extractDir = $filter->xssFilterHard($extractDir);
        $backupDir = dirname($this->addon->getDownloadFilename()) . "/backup";
        $backupDir = $filter->xssFilterHard($backupDir);
        if (file_exists($extractDir)) {
            G::rm_dir($extractDir);
        }
        if (file_exists($backupDir)) {
            G::rm_dir($backupDir);
        }
        if (!is_dir($backupDir)) {
            mkdir($backupDir);
        }
        //printf("Time to remove old directory: %f\n", microtime(1) - $time);
        $time = microtime(1);
        echo "Extracting files...\n";
        $archive->extractModify($extractDir, 'processmaker');
        //printf("Time to extract all files: %f\n", microtime(1) - $time);
        //$time = microtime(1);
        //$files = $archive->listContent();
        //printf("Time to get list of contents: %f\n", microtime(1) - $time);
        /*$time = microtime(1);
        foreach ($files as $fileinfo)
          if (basename($fileinfo['filename']) == 'checksum.txt') {
            $checksumFile = $archive->extractInString($fileinfo['filename']);
            break;
          }
        printf("Time to get checksum.txt: %f\n", microtime(1) - $time);
        */
        $checksumFile = file_get_contents("$extractDir/checksum.txt");
        $time = microtime(1);
        $checksums = array();
        foreach (explode("\n", $checksumFile) as $line) {
            $checksums[trim(substr($line, 33))] = substr($line, 0, 32);
        }
        //printf("Time to assemble list of checksums: %f\n", microtime(1) - $time);
        $checksum = array();
        $changedFiles = array();
        $time = microtime(1);
        $files = ls_dir($extractDir);
        //printf("Time to list files: %f\n", microtime(1) - $time);
        echo "Updating ProcessMaker files...\n";
        $time = microtime(1);
        $checksumTime = 0;
        foreach ($checksums as $filename => $checksum) {
            if (is_dir("$extractDir/$filename")) {
                $filename = $filter->xssFilterHard($filename);
                print $filename;
                continue;
            }
            $installedFile = PATH_TRUNK . "/$filename";
            if (!file_exists($installedFile)) {
                $installedMD5 = "";
            } else {
                $time = microtime(1);
                $installedMD5 = G::encryptFileOld($installedFile);
                $checksumTime += microtime(1) - $time;
            }
            $archiveMD5 = $checksum;
            if (strcasecmp($archiveMD5, $installedMD5) != 0) {
                $changedFiles[] = $filename;
                if (!is_dir(dirname($backupDir.'/'.$filename))) {
                    mkdir(dirname($backupDir.'/'.$filename), 0777, true);
                }
                if (file_exists($installedFile) && is_file($installedFile)) {
                    copy($installedFile, $backupDir.'/'.$filename);
                }
                if (!is_dir(dirname($installedFile))) {
                    mkdir(dirname($installedFile), 0777, true);
                }
                if (!copy("$extractDir/$filename", $installedFile)) {
                    throw new Exception("Could not overwrite '$filename'");
                }
            }
        }
        //printf("Time to create all checksums: %f\n", $checksumTime);
        //printf("Time to copy files: %f\n", microtime(1) - $time);
        printf("Updated %d files\n", count($changedFiles));
        printf("Clearing cache...\n");
        if (defined('PATH_C')) {
            G::rm_dir(PATH_C);
            mkdir(PATH_C, 0777, true);
        }
        $workspaces = System::listWorkspaces();
        $count = count($workspaces);
        $first = true;
        $num = 0;
        foreach ($workspaces as $index => $workspace) {
            try {
                $num += 1;
                printf("Upgrading workspaces ($num/$count): {$workspace->name}\n");
                $workspace->upgrade($first);
                $workspace->close();
                $first = false;
            } catch (Exception $e) {
                printf("Errors upgrading workspace {$workspace->name}: {$e->getMessage()}\n");
                //$errors = true;
            }
        }
        //printf("Time to install: %f\n", microtime(1) - $start);
    }
}

