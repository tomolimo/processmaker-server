<?php
/**
 * processes_DownloadFile.php
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 */

if (! isset($_GET["file_hash"])) {
    throw new Exception("Invalid Request, param 'file_hash' was not sent.");
}

$httpStream = new \ProcessMaker\Util\IO\HttpStream();
$outputDir = PATH_DATA . "sites" . PATH_SEP . SYS_SYS . PATH_SEP . "files" . PATH_SEP . "output" . PATH_SEP;
$filename = base64_decode($_GET["file_hash"]);
$fileExtension = pathinfo($outputDir . $filename, PATHINFO_EXTENSION);

if (! file_exists($outputDir . $filename)) {
    throw new Exception("Error, couldn't find request file: $filename");
}

$httpStream->loadFromFile($outputDir . $filename);
$httpStream->setHeader("Content-Type", "application/$fileExtension");
$httpStream->send();

//  ************* DEPRECATED (it will be removed soon) *********************************
//add more security, and catch any error or exception
//$sFileName = $_GET['p'] . '.pm';
//$file = PATH_DOCUMENT . 'output' . PATH_SEP . $sFileName . 'tpm';
//$filex = PATH_DOCUMENT . 'output' . PATH_SEP . $sFileName;
//
//if (file_exists( $file )) {
//    rename( $file, $filex );
//}
//
//$realPath = PATH_DOCUMENT . 'output' . PATH_SEP . $sFileName;
//G::streamFile( $realPath, true );
