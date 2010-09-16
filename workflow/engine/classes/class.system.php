<?php

/**
 * upgrade_System.php
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
 * class system for workflow mantanance routines
 * 
 * @uthor Erik A.O.<erik@colosa.com>
 * @date May 12th, 2010
 * 
 */

class System {
  
  var $sFilename;
  var $sFilesList;
  var $sUpgradeFileList;
  var $aErrors;
  var $aWorkspaces;
  var $sRevision;
  var $sPath;
  var $newSystemClass;
  
  /** 
  * This function checks files to do updated to pm
  *
  * 
  * @name verifyFileForUpgrade
  *
  * @param 
  * @return boolean
  */ 
  function verifyFileForUpgrade() 
  {
   $upgradeFilename = isset($_FILES['form']['name']['UPGRADE_FILENAME']) ? $_FILES['form']['name']['UPGRADE_FILENAME'] : '';
   $tempFilename    = isset($_FILES['form']['tmp_name']['UPGRADE_FILENAME']) ? $_FILES['form']['tmp_name']['UPGRADE_FILENAME'] : '';
   $this->sRevision = str_replace('.tar.gz', '', str_replace('pmos-patch-', '', $upgradeFilename));
   $sTemFilename    = $tempFilename;
   $this->sFilename = PATH_DATA . 'upgrade' . PATH_SEP . $upgradeFilename;
   $this->sPath     = dirname($this->sFilename) . PATH_SEP;
   G::mk_dir(PATH_DATA . 'upgrade');
   if( ! move_uploaded_file($sTemFilename, $this->sFilename) ) {
     return false;
   }
   return true;
  }
  
  /** 
  * This function gets files to do updated to pm
  *
  * 
  * @name getUpgradedFilesList
  *
  * @param 
  * @return void
  */
  function getUpgradedFilesList() 
  {
   G::LoadClass('archive');
   $this->sFilesList = new gzip_file($this->sFilename);
   $this->sFilesList->set_options(array (
     'basedir'   => dirname($this->sFilename), 
     'overwrite' => 1 
   ));
   $this->sFilesList->extract_files();
    if( count($this->sFilesList->error) > 0 ) {
      $msg = '';
      foreach( $this->sFilesList->error as $key => $val ) {
        $msg .= $val . "\n";
    }
     throw new Exception($msg);
   }
   if( count($this->sFilesList->files) == 0 ) {
     throw new Exception('The uploaded file is an invalid patch file.');
   }
  }
  
  /** 
  * This function checks to do updated for boot
  *
  * 
  * @name verifyForBootstrapUpgrade
  *
  * @param 
  * @return boolean
  */
  function verifyForBootstrapUpgrade()
  {
   foreach( $this->sFilesList->files as $sFile ) {
     if( basename($sFile) == 'schema.xml' ) {
       $this->newSystemClass = $sFile;
       return true;
     }
   }
   return false;
  }
  
  /** 
  * This function updates to the files
  *
  * 
  * @name upgrade
  *
  * @param 
  * @return array
  */
  function upgrade() 
  {
   //get special files
   $sListFile         = '';
   $sCheckListFile    = '';
   $sPatchVersionFile = '';
   $sPoFile           = '';
   $sSchemaFile       = '';
   $sSchemaRBACFile   = '';
   foreach( $this->sFilesList->files as $sFile ) {
    if( basename($sFile) == 'schema.xml' ) {
      if( strpos($sFile, '/rbac/engine/') === false ) {
        $sOldSchema  = '';
        $sSchemaFile = $sFile;
      } else {
        $sOldSchemaRBAC  = '';
        $sSchemaRBACFile = $sFile;
      }
    }
    
    //files.lst
    if( basename($sFile) == 'files.lst' ) {
      $this->sUpgradeFileList = $sFile;
    }
    
    //files.lst
    if( basename($sFile) == 'patch.version.txt' ) {
      $sPatchVersionFile = $sFile;
    }
    
    //files.rev.txt
    if( substr(basename($sFile), 0, 6) == 'files.' && substr(basename($sFile), - 4) == '.txt' ) {
      $sCheckListFile = $sFile;
    }
    
    //po files
    $sExtension = substr($sFile, strrpos($sFile, '.') + 1, strlen($sFile));
    if( $sExtension == 'po' ) {
      $sPoFile = $sFile;
    }
   }
   
   //now getting the current version of PM
   if( file_exists(PATH_METHODS . 'login/version-pmos.php') ) {
     include (PATH_METHODS . 'login/version-pmos.php');
   } else {
     define('PM_VERSION', '1.2-0-development');
   }
   
   $pmVersion = explode('-', PM_VERSION);
   array_shift($pmVersion);
   $patchVersion = explode('-', $this->sRevision);
   
   if( $sPatchVersionFile != '' && file_exists($sPatchVersionFile) ) {
    $this->sRevision = file_get_contents($sPatchVersionFile);
    $patchVersion    = explode('-', $this->sRevision);
   }
   
   if( ! file_exists(PATH_DATA . 'log' . PATH_SEP) ) {
     G::mk_dir(PATH_DATA . 'log' . PATH_SEP);
   }
   
   //empty query log
   $sqlLog = PATH_DATA . 'log' . PATH_SEP . "query.log";
   $fp     = fopen($sqlLog, "w+");
   fwrite($fp, "");
   fclose($fp);
   
   $aEnvironmentsUpdated = array ();
   $aEnvironmentsDiff    = array ();
   $aErrors              = array ();
   
   //now will verify each folder and file has permissions to write and add files.
   if( $this->sUpgradeFileList != '' ) {
    $bCopySchema = true;
    $oFile       = fopen($this->sUpgradeFileList, 'r');
    while( $sLine = trim(fgets($oFile)) ) {
     $sLine = substr($sLine, 1);
     $aAux  = explode(PATH_SEP, $sLine);
     array_shift($aAux);
     $sFilePath      = implode(PATH_SEP, $aAux);
     $targetFileName = PATH_TRUNK . $sFilePath;
     if( ! is_dir($this->sPath . 'processmaker' . PATH_SEP . $sFilePath) ) {
      //if we are updating or deleting a file
      if( file_exists($this->sPath . 'processmaker' . PATH_SEP . $sFilePath) ) {
       if( file_exists($targetFileName) ) {
        if( ! is_writable($targetFileName) ) {
          throw (new Exception("File $targetFileName is not writable."));
        }
       } else {
        //verify parent folder, and ask if that folder is writable
        $auxDir = explode('/', $targetFileName);
        array_pop($auxDir);
        $parentDir = implode('/', $auxDir);
        if( ! is_dir($parentDir) ) {
          //throw (new Exception("File $parentDir is an invalid directory."));
          G::mk_dir($parentDir);   
        }
        if( ! is_writable($parentDir) ) {
          throw (new Exception("Directory $parentDir is not writable."));
        }
       }
      } else { 
       //delete unused files
       if( file_exists($targetFileName) && ! is_writable($targetFileName) ) {
         throw (new Exception("File $targetFileName is not writable."));
       }
      }
     } else {
       $dirName = PATH_TRUNK . $sFilePath;
       if( $dirName[strlen($dirName) - 1] == '/' )
         $dirName = substr($dirName, 0, strlen($dirName) - 1);
       $auxDir = explode('/', $dirName);
       array_pop($auxDir);
       $parentDir = implode('/', $auxDir);
       if( file_exists($dirName) ) {
        if( is_writable($dirName) ) {
          //print "e. ok $dirName <br>";
        } else {
          throw (new Exception("$dirName  is not writable"));
        }
       } else {
         if( is_writable($parentDir) ) {
           mkdir($dirName, 0777);
         } else {
           throw (new Exception("$dirName not exists and parent folder $parentDir is not writable"));
         }
       }
     }
    }
   }
   
   //processing list file files.lst
   if( $this->sUpgradeFileList != '' ) {
     $bCopySchema = true;
     $oFile       = fopen($this->sUpgradeFileList, 'r');
     while( $sLine = trim(fgets($oFile)) ) {
       $action = substr($sLine, 0, 1);
       $sLine  = substr($sLine, 1);
       $aAux   = explode(PATH_SEP, $sLine);
       array_shift($aAux);
       $sFilePath      = implode(PATH_SEP, $aAux);
       $targetFileName = PATH_TRUNK . $sFilePath;
       if( strtoupper($action) != 'D'){
        if( ! is_dir($this->sPath . 'processmaker' . PATH_SEP . $sFilePath) ) {
         if( file_exists($this->sPath . 'processmaker' . PATH_SEP . $sFilePath) ) {
          if( strpos($sFilePath, 'schema.xml') !== false && $bCopySchema ) {
            $bCopySchema = false;
            $sOldSchema = str_replace('schema.xml', 'schema_' . date('Ymd') . '.xml', PATH_TRUNK . $sFilePath);
            $this->pm_copy(PATH_TRUNK . $sFilePath, $sOldSchema);
          }
          if( file_exists($targetFileName) ) {
           if( is_writable($targetFileName) ) {
             $this->pm_copy($this->sPath . 'processmaker' . PATH_SEP . $sFilePath, $targetFileName);
             @chmod($targetFileName, 0666);
           } else
             throw (new Exception("Failed to open file: Permission denied in $targetFileName."));
          } else {
            $this->pm_copy($this->sPath . 'processmaker' . PATH_SEP . $sFilePath, $targetFileName);
            @chmod($targetFileName, 0666);
          }
         } else { //delete unused files
           if( file_exists($targetFileName) ) {
             @unlink($targetFileName);
           }
         }
        } else {
          if( ! file_exists(PATH_TRUNK . $sFilePath) ) {
            mkdir(PATH_TRUNK . $sFilePath, 0777);
          }
        }
       } else if( file_exists(PATH_TRUNK . $sFilePath) && $sFilePath != 'workflow/engine/gulliver' ) {
         @unlink(PATH_TRUNK . $sFilePath);
       }
     }
   }
   
  //end files copied.
  $missedFiles   = '';
  $distinctFiles = '';
  $missed        = 0;
  $distinct      = 0;
  //checking files of this installation server with the files in Repository Code.
  if( $sCheckListFile != '' ) {
    $fp = fopen($sCheckListFile, 'r');
    while( ! feof($fp) ) {
      $line = explode(' ', fgets($fp));
      if( count($line) == 3 ) {
        $file = PATH_TRUNK . trim($line[2]);
        if( is_readable($file) ) {
          $size = sprintf("%07d", filesize($file));
          $checksum = sprintf("%010u", crc32(file_get_contents($file)));
          if( ! ($line[0] == $size && $line[1] == $checksum) && substr($file, - 4) != '.xml' ) {
            $distinctFiles .= $file . "\n";
            $distinct ++;
          }
        } else {
          $missedFiles .= $file . "\n";
          $missed ++;
        }
      }
    }
    fclose($fp);
  }
   
  if( $missed > 0 )
     $aErrors[] = "Warning: there are $missed missed files. ";
  $aErrors[] = $missedFiles;
   
  if( $distinct > 0 ) {
   $aErrors[] = "Warning: there are $distinct files with differences. ";
   $aErrors[] = $distinctFiles;
  }
   
  //now include the files and classes needed for upgrade databases, dont move this files, because we 
  //are getting the last files in this point.  Even the files was in the patch we will take the new ones.
  include PATH_METHODS . PATH_SEP . 'setup' . PATH_SEP . 'upgrade_RBAC.php';
  G::LoadClass('languages');
  G::LoadSystem('database_mysql');
  
  $bForceXml        = true;
  $bParseSchema     = true;
  $bParseSchemaRBAC = true;
  $oDirectory       = dir(PATH_DB);
  
  //count db.php files ( workspaces )
  $aWorkspaces = array ();
  while( ($sObject = $oDirectory->read()) ) {
    if( is_dir(PATH_DB . $sObject) && substr($sObject, 0, 1) != '.' && file_exists(PATH_DB . $sObject . PATH_SEP . 'db.php') ) {
      $aWorkspaces[] = $sObject;
    }
  }
  $aUpgradeData                    = array ();
  $aUpgradeData['workspaces']      = $aWorkspaces;
  $aUpgradeData['wsQuantity']      = count($aWorkspaces);
  $aUpgradeData['sPoFile']         = $sPoFile;
  $aUpgradeData['bForceXmlPoFile'] = true;
  $aUpgradeData['sSchemaFile']     = $sSchemaFile;
  $aUpgradeData['sSchemaRBACFile'] = $sSchemaRBACFile;
  
  file_put_contents(PATH_DATA . 'log' . PATH_SEP . "upgrade.data.bin", serialize($aUpgradeData));
  
  $sSchemaFile     = '';
  $sPoFile         = '';
  $sSchemaRBACFile = '';
  
  $oDirectory = dir(PATH_DB);
  while( ($sObject = $oDirectory->read()) ) {
    if( is_dir(PATH_DB . $sObject) && substr($sObject, 0, 1) != '.' ) {
      if( file_exists(PATH_DB . $sObject . PATH_SEP . 'db.php') ) {
        
        eval($this->getDatabaseCredentials(PATH_DB . $sObject . PATH_SEP . 'db.php'));
      
      }
      $aEnvironmentsUpdated[] = $sObject;
      $aEnvironmentsDiff[] = $sObject;
    }
  }
  $oDirectory->close();
  @unlink(PATH_CORE . 'config/_databases_.php');
  
  //clean up smarty directory
  $oDirectory = dir(PATH_SMARTY_C);
  while( $sFilename = $oDirectory->read() ) {
    if( ($sFilename != '.') && ($sFilename != '..') ) {
      @unlink(PATH_SMARTY_C . PATH_SEP . $sFilename);
    }
  }
  
  //clean up xmlform folders
  $sDir = PATH_C . 'xmlform';
  if( file_exists($sDir) && is_dir($sDir) ) {
    $oDirectory = dir($sDir);
    while( $sObjectName = $oDirectory->read() ) {
      if( ($sObjectName != '.') && ($sObjectName != '..') ) {
        if( is_dir($sDir . PATH_SEP . $sObjectName) ) {
          $this->rm_dir($sDir . PATH_SEP . $sObjectName);
        }
      }
    }
    $oDirectory->close();
  }
  
  //changing the PM_VERSION according the patch file name
  $oFile = fopen(PATH_METHODS . 'login/version-pmos.php', 'w+');
  if( isset($this->sRevision) && $this->sRevision != '' ) {
    fwrite($oFile, "<?\n  define ( 'PM_VERSION' , str_replace ( ' ','',  '1.2-" . $this->sRevision . "' ));\n?>");
  } else {
    fwrite($oFile, "<?\n  define ( 'PM_VERSION' , str_replace ( ' ','',  'unknow' ));\n?>");
  }
  fclose($oFile);
  $ver               = explode("-", $this->sRevision);
  $this->aErrors     = $aErrors;
  $this->aWorkspaces = $aWorkspaces;
  
  return $ver;
  }
  
  /** 
  * This function does to clean up to the upgrate directory
  *
  * 
  * @name cleanupUpgradeDirectory
  *
  * @param 
  * @return array
  */
  function cleanupUpgradeDirectory() 
  {
    $this->rm_dir(PATH_DATA . 'upgrade' . PATH_SEP . 'processmaker');
  }
 
  /** 
  * This function removes a directory
  *
  * 
  * @name rm_dir
  *
  * @param string $dirName
  * @return void
  */ 
  function rm_dir($dirName)
  {
   if( empty($dirName) ) {
     return;
   }
   if( file_exists($dirName) ) {
    if( ! is_readable($dirName) ) {
      throw (new Exception("directory '$dirName' is not readable"));
    }
    $dir = dir($dirName);
    while( $file = $dir->read() ) {
     if( $file != '.' && $file != '..' ) {
      if( is_dir($dirName . PATH_SEP . $file) ) {
        $this->rm_dir($dirName . PATH_SEP . $file);
      } else {
        //@unlink($dirName. PATH_SEP .$file) or die('File '.$dirName. PATH_SEP .$file.' couldn\'t be deleted!');
        @unlink($dirName . PATH_SEP . $file);
      }
     }
    }
    
    $folder = opendir($dirName . PATH_SEP . $file);
    closedir($folder);
    @rmdir($dirName . PATH_SEP . $file);
   } else {
     //
   }
  }
  
  /** 
  * This function creates a directory
  *
  * 
  * @name pm_copy
  *
  * @param string $source
  * @param string $target
  * @return void
  */
  function pm_copy($source, $target) 
  {
    if( ! is_dir(dirname($target)) ) {
      G::mk_dir(dirname($target));
    }
    if( ! copy($source, $target) ) {
      krumo($source);
      krumo($target);
    }
  }
  
  /** 
  * This function gets info about db
  *
  * 
  * @name getDatabaseCredentials
  *
  * @param string $dbFile
  * @return $sContent
  */
  function getDatabaseCredentials($dbFile) 
  {
    $sContent = file_get_contents($dbFile);
    $sContent = str_replace('<?php', '', $sContent);
    $sContent = str_replace('<?', '', $sContent);
    $sContent = str_replace('?>', '', $sContent);
    $sContent = str_replace('define', '', $sContent);
    $sContent = str_replace("('", '$', $sContent);
    $sContent = str_replace("',", '=', $sContent);
    $sContent = str_replace(");", ';', $sContent);
    return $sContent;
  }
}// end System class
