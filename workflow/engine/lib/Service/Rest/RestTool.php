<?php

class Service_Rest_RestTool
{
    protected $configFile = 'rest-config.ini';
    protected $config = array();
    protected $dbXmlSchemaFile = '';
    protected $dbInfo = array();

    public function __construct()
    {
        $this->dbXmlSchemaFile = PATH_CONFIG . 'schema.xml';
    }

    protected function loadConfig()
    {
        if (file_exists(PATH_CONFIG . $this->configFile)) {
            $this->config = @parse_ini_file(PATH_CONFIG . $this->configFile, true);
        }
    }

    protected function loadDbXmlSchema($dbXmlSchemaFile = '')
    {
        if (! empty($dbXmlSchemaFile)) {
            $this->dbXmlSchemaFile = $dbXmlSchemaFile;
        }

        if (! file_exists($this->dbXmlSchemaFile)) {
            throw new Exception("Xml Schema file: '{$this->dbXmlSchemaFile}' does not exist!");
        }

        $doc = new Xml_DOMDocumentExtended();
        $doc->load($this->dbXmlSchemaFile);
        $data = $doc->toArray();
        $tables = $data['database']['table'];

        foreach ($tables as $table) {
            $this->dbInfo[$table['@name']]['pKeys'] = array();
            $this->dbInfo[$table['@name']]['columns'] = array();
            $this->dbInfo[$table['@name']]['required_columns'] = array();

            foreach ($table['column'] as $column) {
                $this->dbInfo[$table['@name']]['columns'][] = $column['@name'];

                if (array_key_exists('@primaryKey', $column) && self::cast($column['@primaryKey'])) {
                    $this->dbInfo[$table['@name']]['pKeys'][] = $column['@name'];
                }

                if (array_key_exists('@required', $column) && self::cast($column['@required'])) {
                    $this->dbInfo[$table['@name']]['required_columns'][] = $column['@name'];
                }
            }
        }
    }

    public function buildConfigIni($filename = '')
    {
        $this->loadDbXmlSchema();

        $this->configFile = empty($filename) ? PATH_CONFIG . $this->configFile : $filename;
        $configIniStr  = "; -= ProcessMaker RestFul services configuration =-\n";
        $configIniStr .= "\n";
        $configIniStr .= "; On this configuration you file can customize all crud rest api.\n";
        $configIniStr .= "; With what methods (GET,POST,PUT,DELETE) you need that PM serve.\n";
        $configIniStr .= "; And for each table/method what columns you can expose.\n";
        $configIniStr .= "\n";

        foreach ($this->dbInfo as $table => $columns) {
            $strColumns    = implode(' ', $columns['columns']);
            $configIniStr .= ";Rest Api for table $table with (".count($columns['columns']).") columns.\n";
            $configIniStr .= "[$table]\n";
            $configIniStr .= "  ; Param to set the allowed methods (separeted by a single space), complete sample: ALLOW_METHODS = GET POST PUT DELETE\n";
            $configIniStr .= "  ALLOW_METHODS = GET\n";
            $configIniStr .= "  ; Params to set what columns should be exposed, you can use wildcard '*' to speccify all columns \n";
            $configIniStr .= "  EXPOSE_COLUMNS_GET  = *\n";
            $configIniStr .= "  EXPOSE_COLUMNS_POST = ".$strColumns."\n";
            $configIniStr .= "  EXPOSE_COLUMNS_PUT  = ".$strColumns."\n";
            $configIniStr .= "\n";
        }

        file_put_contents($this->configFile, $configIniStr);

        return $this->configFile;
    }

    public function buildApi()
    {
        /**
         * load configuration from /engine/config/rest-config.ini and
         * load database schemda from /engine/config/schema.xml
         */
        $this->loadConfig();
        $this->loadDbXmlSchema();

        Haanga::configure(array(
            'template_dir' => dirname(__FILE__) . '/templates/',
            'cache_dir' => sys_get_temp_dir() . '/haanga_cache/',
            'compiler' => array(
                'compiler' => array( /* opts for the tpl compiler */
                    'if_empty' => FALSE,
                    'autoescape' => FALSE,
                    'strip_whitespace' => TRUE,
                    'allow_exec'  => TRUE,
                    'global' => array('globals', 'current_user'),
                ),

            )
        ));

        foreach ($this->config as $table => $conf) {
            $classname = self::camelize($table, 'class');
            $allowedMethods = explode(' ', $conf['ALLOW_METHODS']);
            $methods = '';
            //$allowedMethods = array('DELETE');

            foreach ($allowedMethods as $method) {
                // validation for a valid method
                if (! in_array($method, array('GET', 'POST', 'PUT', 'DELETE'))) {
                    throw new Exception("Invalid method '$method'.");
                }

                $method = strtoupper($method);
                $exposedColumns = array();
                $params = array();
                $paramsStr = array();
                $primaryKeys = array();

                // get columns to expose
                if (array_key_exists('EXPOSE_COLUMNS_'.$method, $conf)) {
                    if ($conf['EXPOSE_COLUMNS_'.$method] == '*') {
                        $exposedColumns = $this->dbInfo[$table]['columns'];
                    } else {
                        $exposedColumns = explode(' ', $conf['EXPOSE_COLUMNS_'.$method]);

                        // validation for valid columns
                        $tableColumns = $this->dbInfo[$table]['columns'];

                        foreach ($exposedColumns as $column) {
                            if (! in_array($column, $tableColumns)) {
                                throw new Exception("Invalid column '$column' for table $table, it does not exist!.");
                            }
                        }

                        // validate that all required columns are in exposed columns array
                        if ($method == 'POST') {
                            // intersect required columns with exposed columns
                            // to verify is all required columns are exposed
                            $intersect = array_intersect($this->dbInfo[$table]['required_columns'], $exposedColumns);

                            // the diff should be empty
                            $diff = array_diff($this->dbInfo[$table]['required_columns'], $intersect);

                            if (! empty($diff)) {
                                throw new Exception(sprintf(
                                    "Error: All required columns for table '%s' must be exposed for POST method.\n" .
                                    "PLease add all required columns on rule 'EXPOSE_COLUMNS_POST' or select all " .
                                    "with '*' selector.\n\n" .
                                    "Missing (%s) required fields for [%s] table:\n" .
                                    implode("\n", $diff),
                                    $table, count($diff), $table
                                ));
                            }
                        }
                    }
                }

                switch ($method) {
                    case 'GET':
                        foreach ($this->dbInfo[$table]['pKeys'] as $i => $pk) {
                            $paramsStr[$i] = "\$".self::camelize($pk).'=null';
                            $params[$i] = self::camelize($pk);
                        }
                        break;

                    case 'PUT':
                        foreach ($exposedColumns as $i => $column) {
                            $paramsStr[$i] = "\$".self::camelize($column);

                            if (! in_array($column, $this->dbInfo[$table]['pKeys'])) {
                                $params[$i] = self::camelize($column);
                            }
                        }
                        break;

                    case 'POST':
                        foreach ($exposedColumns as $i => $column) {
                            $paramsStr[$i] = "\$".self::camelize($column);
                            $params[$i] = self::camelize($column);
                        }
                        break;
                }
                $paramsStr = implode(', ', $paramsStr);

                // formatting primary keys for template
                foreach ($this->dbInfo[$table]['pKeys'] as $i => $pk) {
                    $primaryKeys[$i] = "\$".self::camelize($pk);
                }
                $primaryKeys = implode(', ', $primaryKeys);

                $methods .= Haanga::Load(
                    'method'.self::camelize($method, 'class').'.tpl',
                    array(
                        'params'      => $params,
                        'paramsStr'   => $paramsStr,
                        'primaryKeys' => $primaryKeys,
                        'columns'     => $exposedColumns,
                        'classname'   => $classname,
                    ),
                    true
                );

                $methods .= "\n";
            }

            $classContent = Haanga::Load('class.tpl', array(
                'classname' => $classname,
                'methods' => $methods
            ), true);

            echo "saving $classname.php\n";
            file_put_contents(PATH_CORE."services/rest/crud/$classname.php", $classContent);
        }
    }

    protected static function camelize($str, $type = 'var')
    {
        if (is_array($str)) {
            foreach ($str as $i => $value) {
                $str[$i] = self::camelize($value);
            }
        } elseif (is_string($str)) {
            $str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($str))));
        }

        if ($type == 'var') {
            $str = substr(strtolower($str), 0, 1) . substr($str, 1);
        }

        return $str;
    }

    protected static function cast($value)
    {
        if ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        } elseif (is_numeric($value)) {
            return $value * 1;
        }

        return $value;
    }
}


