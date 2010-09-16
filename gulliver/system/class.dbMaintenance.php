<?php
/**
 * pakeGulliver.php
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
 * 
 * Database Maintenance class
 * 
 * @author Erik A. Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @date May 17th, 2010
 */

class DataBaseMaintenance 
{
  private   $host;
  private   $user;
  private   $passwd;
  
  private   $link;
  private   $dbName;
  public    $result;
  protected $tmpDir;
  protected $outfile;
  protected $infile;

/**
 * __construct
 *
 * @param  string $host is null
 * @param  string $user is null
 * @param  string $passwd is null
 *
 * @return none
 */   
  public function __construct($host = null, $user = null, $passwd = null) 
  {
    $this->tmpDir = './';
    $this->link   = null;
    $this->dbName = null;
    
    if( isset($host) && isset($user) && isset($passwd) ) {
      $this->host   = $host;
      $this->user   = $user;
      $this->passwd = $passwd;
    }
  }
  
/**
 * setUser
 *
 * @param  string $user 
 *
 * @return none
 */   
  public function setUser($user) 
  {
    $this->user = $user;
  }

/**
 * setPasswd
 *
 * @param  string $passwd
 *
 * @return none
 */   
  public function setPasswd($passwd) 
  {
    $this->passwd = $passwd;
  }
  
/**
 * setHost
 *
 * @param  string $host
 *
 * @return none
 */   
  public function setHost($host) 
  {
    $this->host = $host;
  }
  
/**
 * setTempDir
 *
 * @param  string $tmpDir
 *
 * @return none
 */   
  public function setTempDir($tmpDir) 
  {
    $this->tmpDir = $tmpDir;
    mkdir($this->tmpDir);
  }
  
/**
 * getTempDir
 *
 * @return $this->tmpDir
 */   
  public function getTempDir() 
  {
    return $this->tmpDir;
  }
  
/**
 * status
 *
 * @return $this->link
 */   
  public function status()
  {
      return $$this->link;
  }
  
  
/**
 * connect
 *
 * @param  string $dbname is null
 *
 * @return none
 */   
  public function connect($dbname = null) 
  {
    if( $this->link != null ) {
      mysql_close($this->link);
      $this->link = null;
    }
    if( isset($dbname) ) {
      $this->dbName = $dbname;
    }
    
    $this->link = mysql_connect($this->host, $this->user, $this->passwd);
    if( ! $this->link ) {
      throw new Exception("Could't connect to host {$this->host} with user {$this->user}");
    }
    
    if( $this->dbName != null ) {
      $this->selectDataBase($this->dbName);
    }
  }

/**
 * setDbName
 *
 * @param  string $dbname is null
 *
 * @return none
 */   
  public function setDbName($dbname) 
  {
    $this->dbName = $dbname;
  }
  
/**
 * selectDataBase
 *
 * @param  string $dbname 
 *
 * @return none
 */   
  public function selectDataBase($dbname) 
  {
    $this->setDbName($dbname);
    If( ! @mysql_select_db($this->dbName, $this->link) ) {
      throw new Exception("Could't select database $dbname");
    }
  }
  
/**
 * query
 *
 * @param  string $sql
 *
 * @return $aRows
 */   
  public function query($sql) 
  {
    $this->result = @mysql_query($sql);
    $aRows = Array();
    while( $aRow = @mysql_fetch_assoc($this->result) ) {
      array_push($aRows, $aRow);
    }
    return $aRows;
  }
  
/**
 * error
 *
 * @return @mysql_error()
 */   
  public function error()
  {
    return @mysql_error();
  }
  
/**
 * getTablesList
 *
 * @return $aRows
 */   
  public function getTablesList() 
  {
    $this->result = @mysql_query("SHOW TABLES;");
    $aRows = Array ();
    while( $aRow = mysql_fetch_row($this->result) ) {
      array_push($aRows, $aRow[0]);
    }
    return $aRows;
  }

/**
 * dumpData
 *
 * @param  string $table
 *
 * @return boolean true or false
 */   
  function dumpData($table) 
  {
    $this->outfile = $this->tmpDir . $table . '.dump';
    
    //if the file exists delete it
    if( is_file($this->outfile) ) {
      @unlink($this->outfile);
    }
    
    $sql = "SELECT * INTO OUTFILE '{$this->outfile}' FIELDS TERMINATED BY '\t|\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\t\t\r\r\n' FROM $table";
    mysql_escape_string("';");
    if( ! @mysql_query($sql) ) {
      echo mysql_error() . "\n";
      return false;
    }
    return true;
  }
  
/**
 * restoreData
 *
 * @param  string $backupFile
 *
 * @return boolean true or false
 */   
  function restoreData($backupFile) 
  {
    $tableName = str_replace('.dump', '', basename($backupFile));
    $sql = "LOAD DATA INFILE '$backupFile' INTO TABLE $tableName FIELDS TERMINATED BY '\t|\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\t\t\r\r\n'";
    if( ! @mysql_query($sql) ) {
      print mysql_error() . "\n";
      return false;
    }
    return true;
  }

/**
 * backupData
 *
 * @return boolean true or false
 */   
  function backupData() 
  {
    $aTables = $this->getTablesList();
    foreach( $aTables as $table ) {
      if( $this->dumpData($table) !== false ) {
        printf("%20s %s %s\n", 'Dump of table:', $table, " in file {$this->outfile}");
      } else {
        return false;
      }
    }
    return true;
  }
  
/**
 * backupSqlData
 *
 * @return boolean true or false
 */   
  function backupSqlData() 
  {
    $aTables = $this->getTablesList();      
    foreach( $aTables as $table ) {
      $fsize = $this->dumpSqlInserts($table);
      $file  = basename($this->outfile);
      echo "Dump of table: $table  in file $file | $fsize Bytes Saved\n";
    }
    
    echo "UNLOCK TABLES .....................";
    if( @mysql_query("UNLOCK TABLES;") )
      echo "[OK]\n";
    else
      echo "[FAILED]\n";
    return true;
  }
  
/**
 * restoreAllData
 *
 * @param  string $type default value null
 *
 * @return none
 */   
  function restoreAllData($type = null) 
  {
    
    $aTables = $this->getTablesList();
    
    foreach( $aTables as $table ) {
      if( isset($type) && $type == 'sql' ) {
        $this->infile = $this->tmpDir . $table . ".sql";
        if( is_file($this->infile) ) {
          $this->restoreFromSql($this->infile, true);
          printf("%20s %s %s\n", 'Restoring data from Sql file ', $this->infile, " in table $table");
        }
      } else {
        $this->infile = $this->tmpDir . $table . ".dump";
        if( is_file($this->infile) ) {
          $this->restoreData($this->infile);
          printf("%20s %s %s\n", 'Restoring data from ', $this->infile, " in table $table");
        }
      }
    }
  }
  
/**
 * createDb
 *
 * @param  string $dbname 
 * @param  string $drop default value false
 *
 * @return none
 */   
  function createDb($dbname, $drop = false) 
  {
    if( $drop ) {
      $sql = "DROP DATABASE IF EXISTS $dbname;";
      if( ! @mysql_query($sql) ) {
        throw new Exception(mysql_error());
      }
    }
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    if( ! @mysql_query($sql) ) {
      throw new Exception(mysql_error());
    }
  }
  
/**
 * restoreFromSql2
 *
 * @param  string $sqlfile
 *
 * @return none
 */   
  function restoreFromSql2($sqlfile) 
  {
    
    ini_set('memory_limit', '512M');
    if( ! is_file($sqlfile) ) {
      throw new Exception("the $sqlfile doesn't exist!");
    }
    $query  = file_get_contents($sqlfile);
    $mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->dbName);
    
    /* check connection */
    if( mysqli_connect_errno() ) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
    
    /* execute multi query */
    if( $mysqli->multi_query($query) ) {
      do {
        /* store first result set */
        if( $result = $mysqli->store_result() ) {
          while( $row = $result->fetch_row() ) {
          }
          $result->free();
        }
      
      } while( $mysqli->next_result() );
    }
    
    /* close connection */
    $mysqli->close();
  }
  
/**
 * dumpSqlInserts
 *
 * @param  string $table
 *
 * @return integer $bytesSaved;
 */   
  function dumpSqlInserts($table) 
  {
    
    $bytesSaved    = 0;
    $this->outfile = $this->tmpDir . $table . '.sql';
    $metadatafile  = $this->tmpDir . $table . '.meta';
    
    //if the file exists delete it
    if( is_file($this->outfile) ) {
      @unlink($this->outfile);
    }
    $fp = fopen($this->outfile, "wb");
    $fpmd = fopen($metadatafile, "wb");
    echo "LOCK TABLES $table READ.....................";
    if( @mysql_query("LOCK TABLES $table READ; ") )
      echo "[OK]\n";
    else
      echo "[FAILED] - ".mysql_error()."\n";
    $result = @mysql_query("SELECT * FROM `$table`");
    
    //echo "FLUSH TABLES WITH READ LOCK ................";
    /*if( @mysql_query("FLUSH TABLES WITH READ LOCK;") )
      echo "[OK]\n";
    else
      echo "[FAILED] - ".mysql_error()."\n";
      */
      
    $num_rows   = mysql_num_rows($result);
    $num_fields = mysql_num_fields($result);
    
    for( $i = 0; $i < $num_rows; $i ++ ) {
      
      $row  = mysql_fetch_object($result);
      $data = "INSERT INTO `$table` (";
      
      // Field names
      for( $x = 0; $x < $num_fields; $x ++ ) {
        $field_name = mysql_field_name($result, $x);
        $data .= "`{$field_name}`";
        $data .= ($x < ($num_fields - 1)) ? ", " : false;
      }
      
      $data .= ") VALUES (";
      
      // Values
      for( $x = 0; $x < $num_fields; $x ++ ) {
        $field_name = mysql_field_name($result, $x);
        
        $data .= ($row->$field_name === null) ? 'NULL' : "'" . mysql_real_escape_string($row->$field_name) . "'";
        $data .= ($x < ($num_fields - 1)) ? ", " : false;
      }
      
      $data .= ");\n";
      $fsData = sprintf("%09d", strlen($data));
      fwrite($fpmd, "$fsData\n");
      $bytesSaved += fwrite($fp, $data);
    }
    
    fclose($fp);
    fclose($fpmd);
    return $bytesSaved;
  }

/**
 * backupDataBaseSchema
 *
 * @param  string $outfile
 *
 * @return none
 */   
  function backupDataBaseSchema($outfile) 
  {
    $aTables = $this->getTablesList();
    $ws      = explode('_', $this->dbName);
    $ws      = isset($ws[1]) ? $ws[1] : $this->dbName;
    $schema  = "\n";
    $schema .= " -- Processmaker Data Base Maintenance Tool\n";
    $schema .= " --\n";
    $schema .= " -- Workspace: " . $ws . "\n";
    $schema .= " -- Data Base: " . $this->dbName . "\n";
    $schema .= " -- Tables:\t  " . (count($aTables)) . "\n";
    $schema .= " -- Date:\t\t  " . (date('l jS \of F Y h:i:s A')) . "\n";
    $schema .= " --\n\n";
    
    $metafile = str_replace('.sql', '.meta', $outfile);
    $fpmd     = fopen($metafile, "wb");
    
    $sx = strlen($schema);
    foreach( $aTables as $table ) {
      $tableSchema = $this->getSchemaFromTable($table);
      $schema .= $tableSchema;
      $fsData = sprintf("%09d", (strlen($tableSchema) + $sx));
      fwrite($fpmd, "$fsData\n");
      $sx = 0;
    }
    
    fclose($fpmd);
    file_put_contents($outfile, $schema);
  }

/**
 * restoreFromSql
 *
 * @param  string $sqlfile
 *
 * @return boolean false or true
 */   
  function restoreFromSql($sqlfile) 
  {
    ini_set('memory_limit', '64M');
    if( ! is_file($sqlfile) ) {
      throw new Exception("the $sqlfile doesn't exist!");
    }
    
    $metaFile = str_replace('.sql', '.meta', $sqlfile);
    
    if( is_file($metaFile) ) {
      echo "Using $metaFile metadata file.\n";
      $fp   = fopen($sqlfile, 'rb');
      $fpmd = fopen($metaFile, 'r');
      while( $offset = fgets($fpmd, 1024) ) {
        $buffer = intval($offset); //reading the size of $oData
        $query  = fread($fp, $buffer); //reading string $oData
        
        if( ! @mysql_query($query) ) {
          echo mysql_error() . "\n";
          echo "==>" . $query . "<==\n";
        }
      }
    
    } else {
      
      $mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->dbName);
      /* check connection */
      if( mysqli_connect_errno() ) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
      }
      
      $query = file_get_contents($sqlfile);
      if( trim($query) == "" )
        return false;
        
      /* execute multi query */
      if( $mysqli->multi_query($query) ) {
        do {
          /* store first result set */
          if( $result = $mysqli->store_result() ) {
            while( $row = $result->fetch_row() ) {
              //printf("%s\n", $row[0]);
            }
            $result->free();
          }
          /* print divider */
          if( $mysqli->more_results() ) {
            //printf("-----------------\n");
          }
        } while( $mysqli->next_result() );
      }
      
      /* close connection */
      $mysqli->close();
    }
  }
  
/**
 * getSchemaFromTable
 *
 * @param  string $tablename
 *
 * @return string $tableSchema
 */   
  function getSchemaFromTable($tablename) 
  {
    //$tableSchema = "/* Structure for table `$tablename` */\n";
    //$tableSchema .= "DROP TABLE IF EXISTS `$tablename`;\n\n";
    $tableSchema = "";
    $sql = "show create table `$tablename`; ";
    $result = @mysql_query($sql);
    if( $result ) {
      if( $row = mysql_fetch_assoc($result) ) {
        $tableSchema .= $row['Create Table'] . ";\n\n";
      }
      mysql_free_result($result);
    } else
      echo mysql_error();
    
    return $tableSchema;
  }
  
/**
 * removeCommentsIntoString
 *
 * @param  string $str
 *
 * @return string $str
 */   
  function removeCommentsIntoString($str) 
  {
    $str = preg_replace('/\/\*[\w\W]*\*\//', '', $str);
    $str = preg_replace("/--[\w\W]*\\n/", '', $str);
    $str = preg_replace("/\/\/[\w\W]*\\n/", '', $str);
    $str = preg_replace("/\#[\w\W]*\\n/", '', $str);
    return $str;
  }
}

/*
// Sample to use
$oDbMaintainer = new DataBaseMaintenance('localhost', 'root', 'atopml2005');
$oDbMaintainer->setTempDir('/home/erik/backs/');
$oDbMaintainer->setDbName('rb_os');
$oDbMaintainer->connect();
$oDbMaintainer->backupDataBaseSchema('/home/erik/backs/schema_os.sql');
$oDbMaintainer->backupSqlData();
$oDbMaintainer->createDb('neyek12', true);

$o2 = new DataBaseMaintenance('localhost', 'root', 'atopml2005');
$o2->setTempDir('/home/erik/backs/');
$o2->setDbName('neyek12');
$o2->connect();

$o2->restoreFromSql('/home/erik/backs/schema_os.sql');
$o2->restoreAllData('sql');
*/









