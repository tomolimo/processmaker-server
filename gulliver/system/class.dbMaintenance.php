<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 *
 *
 * Database Maintenance class
 *
 *
 * @package gulliver.system
 */
class DataBaseMaintenance
{
    private $host = null;
    private $user = null;
    private $passwd = null;

    private $connect = null;
    private $dbName = null;
    public $result;
    protected $tmpDir = null;
    protected $outfile;
    protected $infile;
    protected $isWindows;

    /**
     * DataBaseMaintenance constructor.
     *
     * @param string $host
     * @param string $user
     * @param string $passwd
     *
     */
    public function __construct($host = null, $user = null, $passwd = null)
    {
        $this->tmpDir = './';
        $this->setConnection(null);
        $this->setDbName(null);
        $this->isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $this->setUser($user);
        $this->setHost($host);
        $this->setPasswd($passwd);
    }

    /**
     * setUser
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set Password
     *
     * @param string $passwd
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }

    /**
     * Set Host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Set TempDir
     *
     * @param string $tmpDir
     */
    public function setTempDir($tmpDir)
    {
        $this->tmpDir = $tmpDir;
        if (!file_exists($tmpDir)) {
            mkdir($this->tmpDir);
        }
    }

    /**
     * Set Db Name
     *
     * @param $dbName
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * Set Connection
     *
     * @param $name
     */
    public function setConnection($name)
    {
        $this->connect = 'DB_' . $name;
    }

    /**
     * Get User
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get Password
     * @return string
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Get Host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get Name Connection
     *
     * @return string
     */
    public function getConnect()
    {
        return $this->connect;
    }

    /**
     * get TempDir
     *
     * @return $this->tmpDir
     */
    public function getTempDir()
    {
        return $this->tmpDir;
    }

    /**
     * Get Name DB
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Connect to DB
     *
     * @param string $dbName
     *
     * @throws Exception
     */
    public function connect($dbName)
    {
        try {
            $this->setConnection($dbName);
            $this->setDbName($dbName);
            InstallerModule::setNewConnection(
                $this->getConnect(),
                $this->getHost(),
                $this->getUser(),
                $this->getPasswd(),
                $this->getDbName(),
                '');

            DB::connection($this->getConnect())
                ->statement("SET NAMES 'utf8'");
            DB::connection($this->getConnect())
                ->statement('SET FOREIGN_KEY_CHECKS=0');

        } catch (QueryException $exception) {
            throw new Exception("Couldn't connect to host {$this->getHost()} with user {$this->getUser()}" . $exception->getMessage());
        }
    }

    /**
     * Query
     *
     * @param string $sql
     *
     * @return array
     * @throws Exception
     */
    public function query($sql)
    {
        try {
            $result = DB::connection($this->getConnect())
                ->select($sql);

            return $result;
        } catch (QueryException $exception) {
            throw new Exception("Couldn't connect to host {$this->getHost()} with user {$this->getUser()}" . $exception->getMessage());
        }
    }

    /**
     * get Tables List
     *
     * @return array
     * @throws Exception
     */
    public function getTablesList()
    {
        return $this->query('SHOW TABLES');
    }

    /**
     * dumpData
     *
     * @param string $table
     *
     * @return boolean true or false
     */
    public function dumpData($table)
    {
        try {
            $this->outfile = $this->tmpDir . $table . '.dump';

            //if the file exists delete it
            if (is_file($this->outfile)) {
                @unlink($this->outfile);
            }

            $sql = "SELECT * INTO OUTFILE '{$this->outfile}' FIELDS TERMINATED BY '\t|\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\t\t\r\r\n' FROM $table";

            DB::connection($this->getConnect())->raw($sql);

            return true;
        } catch (QueryException $exception) {
            $ws = (!empty(config('system.workspace'))) ? config('system.workspace') : 'Undefined Workspace';
            Bootstrap::registerMonolog('MysqlCron', 400, $exception->getMessage(), ['sql' => $sql], $ws, 'processmaker.log');
            $varRes = $exception->getMessage() . "\n";
            G::outRes($varRes);
            return false;
        }
    }

    /**
     * restoreData
     *
     * @param string $backupFile
     *
     * @return boolean true or false
     */
    public function restoreData($backupFile)
    {
        try {
            $tableName = str_replace('.dump', '', basename($backupFile));
            $sql = "LOAD DATA INFILE '$backupFile' INTO TABLE $tableName FIELDS TERMINATED BY '\t|\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\t\t\r\r\n'";

            DB::connection($this->getConnect())->raw($sql);

            return true;
        } catch (QueryException $exception) {
            $ws = (!empty(config("system.workspace"))) ? config("system.workspace") : "Wokspace Undefined";
            Bootstrap::registerMonolog('MysqlCron', 400, $exception->getMessage(), ['sql' => $sql], $ws, 'processmaker.log');
            $varRes = $exception->getMessage() . "\n";
            G::outRes($varRes);
            return false;
        }
    }

    /**
     * restoreAllData
     *
     * @param string $type default value null
     *
     * @throws Exception
     */
    public function restoreAllData($type = null)
    {
        foreach ($this->getTablesList() as $table) {
            if (isset($type) && $type === 'sql') {
                $this->infile = $this->tmpDir . $table . '.sql';
                if (is_file($this->infile)) {
                    $queries = $this->restoreFromSql($this->infile, true);
                    if (!isset($queries)) {
                        $queries = 'unknown';
                    }
                    printf("%-59s%20s", "Restored table $table", "$queries queries\n");
                }
            } else {
                $this->infile = $this->tmpDir . $table . '.dump';
                if (is_file($this->infile)) {
                    $this->restoreData($this->infile);
                    printf("%20s %s %s\n", 'Restoring data from ', $this->infile, " in table $table");
                }
            }
        }
    }

    /**
     * Create DB
     *
     * @param string $dbname
     * @param boolean $drop
     *
     * @return bool
     * @throws Exception
     */
    public function createDb($dbname, $drop = false)
    {
        try {
            if ($drop) {
                DB::connection($this->getConnect())->statement("DROP DATABASE IF EXISTS $dbname");
            }

            DB::connection($this->getConnect())->statement("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

            return true;
        } catch (QueryException $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * backupDataBaseSchema
     *
     * @param string $outfile
     *
     * @return none
     */
    public function backupDataBase($outfile)
    {
        $password = escapeshellarg($this->getPasswd());

        //On Windows, escapeshellarg() instead replaces percent signs, exclamation
        //marks (delayed variable substitution) and double quotes with spaces and
        //adds double quotes around the string.
        //See: http://php.net/manual/en/function.escapeshellarg.php
        if ($this->isWindows) {
            $password = $this->escapeshellargCustom($this->getPasswd());
        }
        $aHost = explode(':', $this->getHost());
        $dbHost = $aHost[0];
        if (isset($aHost[1])) {
            $dbPort = $aHost[1];
            $command = 'mysqldump'
                . ' --user=' . $this->getUser()
                . ' --password=' . $password
                . ' --host=' . $dbHost
                . ' --port=' . $dbPort
                . ' --opt'
                . ' --skip-comments'
                . ' ' . $this->getDbName()
                . ' > ' . $outfile;
        } else {
            $command = 'mysqldump'
                . ' --host=' . $dbHost
                . ' --user=' . $this->getUser()
                . ' --opt'
                . ' --skip-comments'
                . ' --password=' . $password
                . ' ' . $this->getDbName()
                . ' > ' . $outfile;
        }
        shell_exec($command);
    }

    /**
     * string escapeshellargCustom ( string $arg , character $quotes)
     *
     * escapeshellarg() adds single quotes around a string and quotes/escapes any
     * existing single quotes allowing you to pass a string directly to a shell
     * function and having it be treated as a single safe argument. This function
     * should be used to escape individual arguments to shell functions coming
     * from user input. The shell functions include exec(), system() and the
     * backtick operator.
     *
     * On Windows, escapeshellarg() instead replaces percent signs, exclamation
     * marks (delayed variable substitution) and double quotes with spaces and
     * adds double quotes around the string.
     */
    private function escapeshellargCustom($string, $quotes = "")
    {
        if ($quotes === '') {
            $quotes = $this->isWindows ? "\"" : "'";
        }
        $n = strlen($string);
        $special = ["!", "%", "\""];
        $substring = '';
        $result1 = [];
        $result2 = [];
        for ($i = 0; $i < $n; $i++) {
            if (in_array($string[$i], $special, true)) {
                $result2[] = $string[$i];
                $result1[] = $substring;
                $substring = '';
            } else {
                $substring .= $string[$i];
            }
        }
        $result1[] = $substring;
        //Rebuild the password string
        $n = count($result1);
        for ($i = 0; $i < $n; $i++) {
            $result1[$i] = trim(escapeshellarg($result1[$i]), $quotes);
            if (isset($result2[$i])) {
                $result1[$i] .= $result2[$i];
            }
        }
        //add simple quotes, see escapeshellarg function
        $newString = $quotes . implode('', $result1) . $quotes;
        return $newString;
    }

    /**
     * Restore from sql
     *
     * @param string $sqlFile
     * @param string $type
     *
     * @return boolean false or true
     * @throws Exception
     */
    public function restoreFromSql($sqlFile, $type = 'file')
    {
        ini_set('memory_limit', '64M');
        if ($type == 'file' && !is_file($sqlFile)) {
            throw new Exception("the $sqlFile doesn't exist!");
        }

        $metaFile = str_replace('.sql', '.meta', $sqlFile);

        $queries = 0;

        if (is_file($metaFile)) {
            echo "Using $metaFile as metadata.\n";
            $fp = fopen($sqlFile, 'rb');
            $fpmd = fopen($metaFile, 'r');
            while ($offset = fgets($fpmd, 1024)) {
                $buffer = intval($offset); //reading the size of $oData
                $query = fread($fp, $buffer); //reading string $oData
                $queries++;

                try {
                    DB::connection($this->getConnect())->raw($query);
                } catch (QueryException $exception) {
                    $varRes = $exception->getMessage() . "\n";
                    G::outRes($varRes);
                    $varRes = "==>" . $query . "<==\n";
                    G::outRes($varRes);
                }
            }
        } else {
            $queries = null;
            try {

                if ($type === 'file') {
                    $query = file_get_contents($sqlFile);
                } elseif ($type === 'string') {
                    $query = $sqlFile;
                } else {
                    return false;
                }

                if (empty(trim($query))) {
                    return false;
                }

                try {
                    DB::connection($this->getConnect())->raw($query);
                } catch (QueryException $exception) {
                    throw new Exception($exception->getMessage());
                }

            } catch (Exception $e) {
                echo $query;
                $token = strtotime("now");
                PMException::registerErrorLog($e, $token);
                G::outRes(G::LoadTranslation("ID_EXCEPTION_LOG_INTERFAZ", array($token)));
            }
        }
        return $queries;
    }

    /**
     * getSchemaFromTable
     *
     * @param string $tablename
     *
     * @return string $tableSchema
     */
    public function getSchemaFromTable($tablename)
    {
        try {
            $tableSchema = '';
            $result = DB::connection($this->getConnect())->select("show create table `$tablename`");

            if ($result) {
                $tableSchema = $result['Create Table'] . ";\n\n";
            }

        } catch (QueryException $exception) {
            G::outRes($exception->getMessage());
        }

        return $tableSchema;
    }

    /**
     * removeCommentsIntoString
     *
     * @param string $str
     *
     * @return string $str
     */
    public function removeCommentsIntoString($str)
    {
        $str = preg_replace('/\/\*[\w\W]*\*\//', '', $str);
        $str = preg_replace("/--[\w\W]*\\n/", '', $str);
        $str = preg_replace("/\/\/[\w\W]*\\n/", '', $str);
        $str = preg_replace("/\#[\w\W]*\\n/", '', $str);
        return $str;
    }
}
