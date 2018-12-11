<?php

use Illuminate\Support\Facades\DB;

class database extends database_base
{
    public $iFetchType = MYSQLI_ASSOC;

    /**
     * Name connection eloquent
     * @var string
     */
    private $nameConnection;

    /**
     * Expression regex validate version mysql.
     * @var string
     */
    private $regexVersionMysql = '@[0-9]+\.[0-9]+\.[0-9]+@';

    /**
     * class database constructor.
     *
     * @param string $type adapter type
     * @param string $server server
     * @param string $user db user
     * @param string $pass db user password
     * @param string $database Database name
     */
    public function __construct($type = null, $server = null, $user = null, $pass = null, $database = null)
    {
        if ($type === null) {
            $type = config('connections.driver');
        }
        if ($server === null) {
            $server = config('connections.workflow.host');
        }
        if ($user === null) {
            $user = config('connections.workflow.username');
        }
        if ($pass === null) {
            $pass = config('connections.workflow.password');
        }
        if ($database === null) {
            $database = config('connections.workflow.database');
        }
        $this->sType = $type;
        $this->sServer = $server;
        $this->sUser = $user;
        $this->sPass = $pass;
        $this->sDataBase = $database;
        $this->sQuoteCharacter = '`';
        $this->nullString = 'null';
        try {
            $this->setNameConnection('workflow');
            if ($type !== config('connections.driver') ||
                $server !== config('connections.workflow.host') ||
                $user !== config('connections.workflow.username') ||
                $pass !== config('connections.workflow.password') ||
                $database !== config('connections.workflow.database')) {
                $this->setNameConnection('DATABASE_' . $database);
                InstallerModule::setNewConnection($this->getNameConnection(), $server, $user, $pass, $database, '');
            }

            $this->oConnection = true;
        } catch (Exception $exception) {
            $this->oConnection = false;
        }
    }

    /**
     * @return string
     */
    public function getNameConnection()
    {
        return $this->nameConnection;
    }

    /**
     * @param string $nameConnection
     */
    public function setNameConnection($nameConnection)
    {
        $this->nameConnection = $nameConnection;
    }

    /**
     * @return string
     */
    public function getRegexVersionMysql()
    {
        return $this->regexVersionMysql;
    }

    /**
     * @param string $regexVersionMysql
     */
    public function setRegexVersionMysql($regexVersionMysql)
    {
        $this->regexVersionMysql = $regexVersionMysql;
    }

    /**
     * generate the sql sentence to create a table
     *
     * @param string $table table name
     * @param array $columns array of columns
     * @return string $sql the sql sentence
     */
    public function generateCreateTableSQL($table, $columns)
    {
        $keys = '';
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . '(';

        foreach ($columns as $columnName => $parameters) {
            if ($columnName !== 'INDEXES') {
                if (!empty($columnName) && isset($parameters['Type']) && !empty($parameters['Type'])) {
                    $sql .= $this->sQuoteCharacter . $columnName . $this->sQuoteCharacter . ' ' . $parameters['Type'];

                    if (isset($parameters['Null']) && $parameters['Null'] === 'YES') {
                        $sql .= ' NULL';
                    } else {
                        $sql .= ' NOT NULL';
                    }
                    if (isset($parameters['AutoIncrement']) && $parameters['AutoIncrement']) {
                        $sql .= ' AUTO_INCREMENT PRIMARY KEY';
                    }
                    if (isset($parameters['Key']) && $parameters['Key'] == 'PRI') {
                        $keys .= $this->sQuoteCharacter . $columnName . $this->sQuoteCharacter . ',';
                    }

                    if (isset($parameters['Default'])) {
                        $sql .= " DEFAULT '" . trim($parameters['Default']) . "'";
                    }

                    $sql .= ',';
                }
            }
        }
        $sql = substr($sql, 0, -1);
        if ($keys != '') {
            $sql .= ',PRIMARY KEY(' . substr($keys, 0, -1) . ')';
        }
        $sql .= ')ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

        return $sql;
    }

    /**
     * generate a drop table sentence
     *
     * @param string $table table name
     * @return string sql sentence string
     */
    public function generateDropTableSQL($table)
    {
        return 'DROP TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter;
    }

    /**
     * generate rename table sentence
     *
     * @param string $sTableOld old table name
     * @return string $sql sql sentence
     */
    public function generateRenameTableSQL($sTableOld)
    {
        $sql = 'ALTER TABLE ' . $sTableOld . ' RENAME TO RBAC_' . $sTableOld;
        return $sql;
    }

    /**
     * generate drop column sentence
     *
     * @param string $table table name
     * @param string $column column name
     * @return string $sql sql sentence
     */
    public function generateDropColumnSQL($table, $column)
    {
        $sql = 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' DROP COLUMN ' . $this->sQuoteCharacter . $column . $this->sQuoteCharacter;
        return $sql;
    }

    /**
     * This method has to refactor
     * @param string $table
     * @param string $column
     * @param string $parameters
     * @return string
     */
    public function generateCheckAddColumnSQL($table, $column, $parameters)
    {
        return 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' DROP PRIMARY KEY ';
    }

    /**
     * This method has to refactor
     * @param string $table
     * @param string $column
     * @param string $parameters
     * @return string
     */
    public function deleteAllIndexesIntable($table, $column = null, $parameters = null)
    {
        return 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' DROP INDEX indexLoginLog ';
    }

    /**
     * This method is used exclusively to verify if it was made changes in the DB to solve the HOR-1787 issue, later
     * a generic method which covers all the possible similar problems found in the HOR-1787 issue will be generated.
     * @param string $table
     * @param string $column
     * @param array $parameters
     * @return bool
     */
    public function checkPatchHor1787($table, $column = null, $parameters = [])
    {
        if (isset($parameters['AutoIncrement']) && $parameters['AutoIncrement'] && $table == 'LOGIN_LOG') {
            return true;
        }
        return false;
    }


    /**
     * generate an add column sentence
     *
     * @param string $table table name
     * @param string $column column name
     * @param array $parameters parameters of field like typo or if it can be null
     * @return string $sql sql sentence
     */
    public function generateAddColumnSQL($table, $column, $parameters)
    {
        $sql = '';
        if (isset($parameters['Type']) && isset($parameters['Null'])) {
            $sql = 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' ADD COLUMN ' . $this->sQuoteCharacter . $column . $this->sQuoteCharacter . ' ' . $parameters['Type'];
            if ($parameters['Null'] == 'YES') {
                $sql .= ' NULL';
            } else {
                $sql .= ' NOT NULL';
            }
        }
        if (isset($parameters['AutoIncrement']) && $parameters['AutoIncrement']) {
            $sql .= ' AUTO_INCREMENT';
        }
        if (isset($parameters['PrimaryKey']) && $parameters['PrimaryKey']) {
            $sql .= ' PRIMARY KEY';
        }
        if (isset($parameters['Unique']) && $parameters['Unique']) {
            $sql .= ' UNIQUE';
        }

        //we need to check the property AI
        if (isset($parameters['AI'])) {
            if ($parameters['AI'] == 1) {
                $sql .= ' AUTO_INCREMENT';
            } else {
                if ($parameters['Default'] != '') {
                    $sql .= " DEFAULT '" . $parameters['Default'] . "'";
                }
            }
        } else {
            if (isset($parameters['Default'])) {
                $sql .= " DEFAULT '" . $parameters['Default'] . "'";
            }
        }
        return $sql;
    }

    /**
     * generate a change column sentence
     *
     * @param string $table table name
     * @param string $column column name
     * @param array $parameters parameters of field like typo or if it can be null
     * @param string $columnNewName column new name
     *
     * @return string $sql sql sentence
     */
    public function generateChangeColumnSQL($table, $column, $parameters, $columnNewName = '')
    {
        $sql = 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' CHANGE COLUMN ' . $this->sQuoteCharacter . ($columnNewName != '' ? $columnNewName : $column) . $this->sQuoteCharacter . ' ' . $this->sQuoteCharacter . $column . $this->sQuoteCharacter;
        if (isset($parameters['Type'])) {
            $sql .= ' ' . $parameters['Type'];
        }
        if (isset($parameters['Null'])) {
            if ($parameters['Null'] === 'YES') {
                $sql .= ' NULL';
            } else {
                $sql .= ' NOT NULL';
            }
        }

        if (isset($parameters['Default'])) {
            if (empty(trim($parameters['Default'])) && $parameters['Type'] === 'datetime') {
                //do nothing
            } else {
                $sql .= " DEFAULT '" . $parameters['Default'] . "'";
            }
        }
        if (!isset($parameters['Default']) && isset($parameters['Null']) && $parameters['Null'] === 'YES') {
            $sql .= ' DEFAULT NULL ';
        }
        return $sql;
    }

    /**
     * Generate and get the primary key in a sentence
     *
     * @param string $table table name
     * @return string  $sql sql sentence
     * @throws Exception
     */
    public function generateGetPrimaryKeysSQL($table)
    {
        try {
            if (empty($table)) {
                throw new Exception('The table name cannot be empty!');
            }
            return 'SHOW INDEX FROM  ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' WHERE Seq_in_index = 1';
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to drop the primary key
     *
     * @param string $table table name
     * @return string sql sentence
     * @throws Exception
     */
    public function generateDropPrimaryKeysSQL($table)
    {
        try {
            if (empty($table)) {
                throw new Exception('The table name cannot be empty!');
            }
            return 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' DROP PRIMARY KEY';
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to add multiple primary keys
     *
     * @param string $table table name
     * @param array $primaryKeys array of primary keys
     * @return string sql sentence
     * @throws Exception
     */
    public function generateAddPrimaryKeysSQL($table, $primaryKeys)
    {
        try {
            if (empty($table)) {
                throw new Exception('The table name cannot be empty!');
            }
            $sql = 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' ADD PRIMARY KEY (';
            foreach ($primaryKeys as $key) {
                $sql .= $this->sQuoteCharacter . $key . $this->sQuoteCharacter . ',';
            }
            $sql = substr($sql, 0, -1) . ')';
            return $sql;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to drop an index
     *
     * @param string $table table name
     * @param string $indexName index name
     * @return string sql sentence
     * @throws Exception
     */
    public function generateDropKeySQL($table, $indexName)
    {
        try {
            if (empty($table)) {
                throw new Exception('The table name cannot be empty!');
            }
            if (empty($indexName)) {
                throw new Exception('The column name cannot be empty!');
            }
            return 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' DROP INDEX ' . $this->sQuoteCharacter . $indexName . $this->sQuoteCharacter;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to add indexes or primary keys
     *
     * @param string $table table name
     * @param string $indexName index name
     * @param array $keys array of keys
     * @return string sql sentence
     * @throws Exception
     */

    public function generateAddKeysSQL($table, $indexName, $keys)
    {
        try {
            $indexType = 'INDEX';
            if ($indexName === 'primaryKey' || $indexName === 'PRIMARY') {
                $indexType = 'PRIMARY';
                $indexName = 'KEY';
            }
            $sql = 'ALTER TABLE ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' ADD ' . $indexType . ' ' . $indexName . ' (';
            foreach ($keys as $key) {
                $sql .= $this->sQuoteCharacter . $key . $this->sQuoteCharacter . ', ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= ')';
            return $sql;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to show the tables
     *
     * @return sql sentence
     */
    public function generateShowTablesSQL()
    {
        return 'SHOW TABLES';
    }

    /**
     * generate a sentence to show the tables with a like sentence
     *
     * @return string sql sentence
     */
    public function generateShowTablesLikeSQL($table)
    {
        return "SHOW TABLES LIKE '" . $table . "'";
    }

    /**
     * generate a sentence to show the tables with a like sentence
     *
     * @param string $table table name
     * @return string sql sentence
     * @throws Exception
     */
    public function generateDescTableSQL($table)
    {
        try {
            if (empty($table)) {
                throw new Exception('The table name cannot be empty!');
            }
            return 'DESC ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * generate a sentence to show some table indexes
     *
     * @param string $table table name
     * @return string sql sentence
     */
    public function generateTableIndexSQL($table)
    {
        return 'SHOW INDEX FROM ' . $this->sQuoteCharacter . $table . $this->sQuoteCharacter . ' ';
    }

    /**
     * execute a sentence to check if there is connection
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->oConnection;
    }

    /**
     * generate a sentence to show the tables with a like sentence
     *
     * @param string $query sql query string
     */
    public function logQuery($query)
    {
        try {
            $found = false;
            if (substr($query, 0, 6) === 'SELECT') {
                $found = true;
            } else {
                $option = substr($query, 0, 4);
                $options = ['SHOW', 'DESC', 'USE '];
                if (in_array($option, $options, true)) {
                    $found = true;
                }
            }
            if (!$found) {
                $logDir = PATH_DATA . 'log';
                if (!file_exists($logDir)) {
                    if (!mkdir($logDir)) {
                        return;
                    }
                }
                $logFile = "$logDir/query.log";
                $fp = fopen($logFile, 'a+');
                if ($fp !== false) {
                    fwrite($fp, date('Y-m-d H:i:s') . ' ' . $this->sDataBase . ' ' . $query . "\n");
                    fclose($fp);
                }
            }
        } catch (Exception $exception) {
        }
    }

    /**
     * execute a sql query
     *
     * @param string $query
     * @return array
     * @throws Exception
     */
    public function executeQuery($query)
    {
        $this->logQuery($query);

        try {
            if (!$this->oConnection) {
                throw new Exception('invalid connection to database ' . $this->sDataBase);
            }
            $result = DB::connection($this->getNameConnection())
                ->select($query);
            $result = array_map(function ($value) {
                $data = (array)$value;
                if ($this->iFetchType === 2) {
                    $data = $data[key($data)];
                }
                return $data;
            }, $result);
            return $result;
        } catch (Exception $exception) {
            $this->logQuery($exception->getMessage());
            return [];
        }
    }

    /**
     * close the current connection
     *
     * @return void
     */
    public function close()
    {
        if ($this->getNameConnection() !== 'workflow') {
            DB::disconnect($this->getNameConnection());
        }
    }

    /**
     * Generate sql insert
     *
     * @param string $table
     * @param array $data
     * @return string
     */
    public function generateInsertSQL($table, $data)
    {
        $fields = [];
        $values = [];
        foreach ($data as $field) {
            $fields[] = $field['field'];
            if (!is_null($field['value'])) {
                switch ($field['type']) {
                    case 'text':
                    case 'date':
                        $values[] = "'" . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']) . "'";
                        break;
                    case 'int':
                    default:
                        $values[] = DB::connection($this->getNameConnection())->getPdo()->quote($field['value']);
                        break;
                }
            } else {
                $values[] = $this->nullString;
            }
        }
        $fields = array_map([$this, 'putQuotes'], $fields);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->putQuotes($table), implode(', ', $fields), implode(', ', $values));
        return $sql;
    }

    /**
     * Generate update sql
     *
     * @param string $table
     * @param array $keys
     * @param array $data
     * @return string
     */
    public function generateUpdateSQL($table, $keys, $data)
    {
        $fields = [];
        $where = [];
        foreach ($data as $field) {
            if (!is_null($field['value'])) {
                switch ($field['type']) {
                    case 'text':
                    case 'date':
                        $fields[] = $this->putQuotes($field['field']) . " = '" . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']) . "'";
                        break;
                    case 'int':
                    default:
                        $fields[] = $this->putQuotes($field['field']) . " = " . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']);
                        break;
                }
            } else {
                $values[] = $this->nullString;
            }
            if (in_array($field['field'], $keys)) {
                $where[] = $fields[count($fields) - 1];
            }
        }
        $sql = sprintf("UPDATE %s SET %s WHERE %s", $this->putQuotes($table), implode(', ', $fields), implode(', ', $where));
        return $sql;
    }

    /**
     * Generate delete table
     *
     * @param string $table
     * @param array $keys
     * @param array $data
     * @return string
     */
    public function generateDeleteSQL($table, $keys, $data)
    {
        $fields = [];
        $where = [];
        foreach ($data as $field) {
            if (in_array($field['field'], $keys)) {
                if (!is_null($field['value'])) {
                    switch ($field['type']) {
                        case 'text':
                        case 'date':
                            $where[] = $this->putQuotes($field['field']) . " = '" . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']) . "'";
                            break;
                        case 'int':
                        default:
                            $where[] = $this->putQuotes($field['field']) . " = " . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']);
                            break;
                    }
                } else {
                    $values[] = $this->nullString;
                }
            }
        }
        $sql = sprintf("DELETE FROM %s WHERE %s", $this->putQuotes($table), implode(', ', $where));
        return $sql;
    }

    /**
     * Generate sql select
     *
     * @param string $table
     * @param array $keys
     * @param array $data
     * @return string
     */
    public function generateSelectSQL($table, $keys, $data)
    {
        $fields = [];
        $where = [];
        foreach ($data as $field) {
            if (in_array($field['field'], $keys)) {
                if (!is_null($field['value'])) {
                    switch ($field['type']) {
                        case 'text':
                        case 'date':
                            $where[] = $this->putQuotes($field['field']) . " = '" . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']) . "'";
                            break;
                        case 'int':
                        default:
                            $where[] = $this->putQuotes($field['field']) . " = " . DB::connection($this->getNameConnection())->getPdo()->quote($field['value']);
                            break;
                    }
                } else {
                    $values[] = $this->nullString;
                }
            }
        }
        $sql = sprintf("SELECT * FROM %s WHERE %s", $this->putQuotes($table), implode(', ', $where));
        return $sql;
    }

    private function putQuotes($element)
    {
        return $this->sQuoteCharacter . $element . $this->sQuoteCharacter;
    }

    /*=================================================================================================*/
    /**
     * concatString
     * Generates a string equivalent to the chosen database.
     *
     * author Hector Cortez <hector@gmail.com>
     * date 2010-08-04
     *
     * @return string $concat
     */
    public function concatString()
    {
        $nums = func_num_args();
        $vars = func_get_args();

        $concat = ' CONCAT(';
        for ($i = 0; $i < $nums; $i++) {
            if (isset($vars[$i])) {
                $concat .= $vars[$i];
                if (($i + 1) < $nums) {
                    $concat .= ', ';
                }
            }
        }
        $concat .= ')';

        return $concat;
    }

    /*
     * query functions for class class.case.php
     *
     */
    /**
     * concatString
     * Generates a string equivalent to the case when
     *
     * author Hector Cortez <hector@gmail.com>
     * date 2010-08-04
     *
     * @return string $sCompare
     */
    public function getCaseWhen($compareValue, $trueResult, $falseResult)
    {
        return 'IF(' . $compareValue . ', ' . $trueResult . ', ' . $falseResult . ') ';
    }

    /**
     * Generates a string equivalent to create table ObjectPermission
     *
     * class.case.php
     * function verifyTable()
     *
     * @return string $sql
     */
    public function createTableObjectPermission()
    {
        return "CREATE TABLE IF NOT EXISTS `OBJECT_PERMISSION` (
                   `OP_UID` varchar(32) NOT NULL,
                   `PRO_UID` varchar(32) NOT NULL,
                   `TAS_UID` varchar(32) NOT NULL,
                   `USR_UID` varchar(32) NOT NULL,
                   `OP_USER_RELATION` int(1) NOT NULL default '1',
                   `OP_TASK_SOURCE` varchar(32) NOT NULL,
                   `OP_PARTICIPATE` int(1) NOT NULL default '1',
                   `OP_OBJ_TYPE` varchar(15) NOT NULL default 'ANY',
                   `OP_OBJ_UID` varchar(32) NOT NULL,
                   `OP_ACTION` varchar(10) NOT NULL default 'VIEW',
                   KEY `PRO_UID` (`PRO_UID`,`TAS_UID`,`USR_UID`,`OP_TASK_SOURCE`,`OP_OBJ_UID`)
                   )ENGINE=InnoDB DEFAULT CHARSET=latin1";
    }

    /*
     * query functions for class class.report.php
     *
     */
    /**
     * Generates a string query
     *
     * class.report.php
     * function generatedReport4()
     *
     * @return string $sql
     */
    public function getSelectReport4()
    {
        $sqlConcat = " CONCAT(U.USR_LASTNAME,' ',USR_FIRSTNAME) AS USER ";
        $sqlGroupBy = " USER ";

        $sql = "SELECT " . $sqlConcat . ", " . "  COUNT(*) AS CANTCASES,
                MIN(AD.DEL_DURATION) AS MIN,
                MAX(AD.DEL_DURATION) AS MAX,
                SUM(AD.DEL_DURATION) AS TOTALDUR,
                AVG(AD.DEL_DURATION) AS PROMEDIO
                FROM APPLICATION AS A
                LEFT JOIN APP_DELEGATION AS AD ON(A.APP_UID = AD.APP_UID AND AD.DEL_INDEX=1)
                LEFT JOIN USERS AS U ON(U.USR_UID = A.APP_INIT_USER)
                WHERE A.APP_UID<>''
                GROUP BY " . $sqlGroupBy;

        return $sql;
    }

    /**
     * Generates a string query
     *
     * class.report.php
     * function generatedReport4_filter()
     *
     * @return string $sql
     */
    public function getSelectReport4Filter($var)
    {
        $sqlConcat = " CONCAT(U.USR_LASTNAME,' ',USR_FIRSTNAME) AS USER ";
        $sqlGroupBy = " USER ";

        $sql = " SELECT " . $sqlConcat . ", " . " COUNT(*) AS CANTCASES,
             MIN(AD.DEL_DURATION) AS MIN,
             MAX(AD.DEL_DURATION) AS MAX,
             SUM(AD.DEL_DURATION) AS TOTALDUR,
             AVG(AD.DEL_DURATION) AS PROMEDIO
             FROM APPLICATION AS A
             LEFT JOIN APP_DELEGATION AS AD ON(A.APP_UID = AD.APP_UID AND AD.DEL_INDEX=1)
             LEFT JOIN USERS AS U ON(U.USR_UID = A.APP_INIT_USER)
             " . $var . "
             GROUP BY " . $sqlGroupBy;

        return $sql;
    }

    /**
     * Generates a string query
     *
     * class.report.php
     * function generatedReport5()
     *
     * @return string $sql
     */
    public function getSelectReport5()
    {
        $sqlConcat = " CONCAT(U.USR_LASTNAME,' ',USR_FIRSTNAME) AS USER ";
        $sqlGroupBy = " USER ";

        $sql = " SELECT " . $sqlConcat . ", " . " COUNT(*) AS CANTCASES,
              MIN(AD.DEL_DURATION) AS MIN,
              MAX(AD.DEL_DURATION) AS MAX,
              SUM(AD.DEL_DURATION) AS TOTALDUR,
              AVG(AD.DEL_DURATION) AS PROMEDIO
              FROM APP_DELEGATION AS AD
              LEFT JOIN PROCESS AS P ON (P.PRO_UID = AD.PRO_UID)
              LEFT JOIN USERS AS U ON(U.USR_UID = AD.USR_UID)
              WHERE AD.APP_UID<>'' AND AD.DEL_FINISH_DATE IS NULL
              GROUP BY " . $sqlGroupBy;

        return $sql;
    }

    /**
     * Generates a string query
     *
     * class.report.php
     * function generatedReport5_filter()
     *
     * @return string $sql
     */
    public function getSelectReport5Filter($var)
    {
        $sqlConcat = " CONCAT(U.USR_LASTNAME,' ',USR_FIRSTNAME) AS USER ";
        $sqlGroupBy = " USER ";

        $sql = "SELECT " . $sqlConcat . ", " . "  COUNT(*) AS CANTCASES,
              MIN(AD.DEL_DURATION) AS MIN,
              MAX(AD.DEL_DURATION) AS MAX,
              SUM(AD.DEL_DURATION) AS TOTALDUR,
              AVG(AD.DEL_DURATION) AS PROMEDIO
              FROM APP_DELEGATION AS AD
              LEFT JOIN PROCESS AS P ON (P.PRO_UID = AD.PRO_UID)
              LEFT JOIN USERS AS U ON(U.USR_UID = AD.USR_UID)
              " . $var . "
              GROUP BY " . $sqlGroupBy;

        return $sql;
    }

    /*
     * query functions for class class.net.php
     *
     */
    /**
     * Version mysql
     *
     * @param string $driver
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $pass
     * @param string $database
     * @return string version mysql
     * @throws Exception
     */
    public function getServerVersion($driver, $host, $port, $user, $pass, $database)
    {
        try {
            $connection = 'TEST_VERSION';
            InstallerModule::setNewConnection($connection, $host, $user, $pass, $database, $port);

            $results = DB::connection($connection)
                ->select(DB::raw('select version()'));

            preg_match($this->getRegexVersionMysql(), $results[0]->{'version()'}, $version);

            DB::disconnect($connection);

            return $version[0];

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /*
     * query functions for class class.net.php, class.reportTables.php
     *
     */

    /**
     * Generate drop table
     *
     * @param string $tableName
     * @return string sql
     */
    public function getDropTable($tableName)
    {
        return 'DROP TABLE IF EXISTS `' . $tableName . '`';
    }

    /**
     * Generate Description table
     *
     * @param string $tableName
     * @return string sql
     */
    public function getTableDescription($tableName)
    {
        return 'DESC ' . $tableName;
    }

    /**
     * @return string
     */
    public function getFieldNull()
    {
        return 'Null';
    }

    /**
     * @param $validate
     * @return mixed
     */
    public function getValidate($validate)
    {
        return $validate;
    }

    /**
     * Determines whether a table exists
     * It is part of class.reportTables.php
     */
    public function reportTableExist()
    {
        $result = DB::select("show tables like 'REPORT_TABLE'");
        return count($result) > 0;
    }

    /**
     * It is part of class.pagedTable.php
     */

    /**
     * Generate limit sql
     *
     * @param int $currentPage
     * @param int $rowsPerPage
     * @return string
     */
    public function getLimitRenderTable($currentPage, $rowsPerPage)
    {
        return ' LIMIT ' . (($currentPage - 1) * $rowsPerPage) . ', ' . $rowsPerPage;
    }

    /**
     * Determining the existence of a table
     *
     * @param string $tableName
     * @param string $database
     *
     * @return bool
     */
    public function tableExists($tableName, $database)
    {
        try {
            $result = DB::connect($this->getNameConnection())
                ->select("show tables like '$tableName'");
            $flag = count($result) > 0;

        } catch (\Illuminate\Database\QueryException $exception) {
            $flag = false;
        }
        return $flag;
    }
}
