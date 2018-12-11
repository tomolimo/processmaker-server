<?php

global $DB_ADAPTER;
global $DB_HOST;
global $DB_USER;
global $DB_PASS;
global $DB_NAME;
set_time_limit(0);

$id = '';
if (isset($_POST['id']))
    $id = $_POST['id'];

$upgradeData = unserialize(file_get_contents(PATH_DATA . 'log' . PATH_SEP . "upgrade.data.bin"));
$workspaces = $upgradeData['workspaces'];

if (is_array($workspaces) && count($workspaces) > 0) {
    $workspace = array_shift($upgradeData['workspaces']);

    eval(getDatabaseCredentials(PATH_DB . $workspace . PATH_SEP . 'db.php'));
    $database = new database($DB_ADAPTER, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $database->iFetchType = MYSQLI_NUM;

    //processing .po file
    if (!empty($upgradeData['sPoFile'])) {
        $oLanguages = new languages();
        $oLanguages->importLanguage($upgradeData['sPoFile'], $upgradeData['bForceXmlPoFile']);
        $upgradeData['bForceXmlPoFile'] = false;
    }

    if ($upgradeData['sSchemaFile'] != '')
        processMasterSchemaFile($upgradeData['sSchemaFile']);

    //draw a gauge control indicating the progress in workspaces
    $gauge = intval((($upgradeData['wsQuantity'] - count($workspaces) + 1) / $upgradeData['wsQuantity']) * 301);
    print "<table cellpadding=0><tr><td><img src='/images/ajax-loader.gif' border=0/></td><td>";
    print "<div style='border-style:solid;border-width:1px; border-color: #A1C868; width:300px; height:19px;' >";
    print "<div style='color:#FFF; height:16px; text-align:center; padding-top:3px; background-image: url(/skins/green/images/bm.jpg); background-repeat: repeat-x; width: {$gauge}px' ></div> </div>";
    print "</td><td width='40%'><font color=black> Upgrading the workspace </font><b><font color=green>" . $filter->xssFilterHard($workspace) . "</font></b></td><td width=250 align=right>| " . $filter->xssFilterHard($id) . " Remaining</td></tr></table>";
    file_put_contents(PATH_DATA . 'log' . PATH_SEP . "upgrade.data.bin", serialize($upgradeData));
} else {
    print "<table cellpadding=0><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>";
    print "<div style='border-style:solid;border-width:1px; border-color: #A1C868; width:300px; height:19px;' >";
    print "<div style='color:#FFF; height:16px; text-align:center; padding-top:3px; background-image: url(/skins/green/images/bm.jpg); background-repeat: repeat-x; width: 301px' ></div> </div>";
    print "</td><td> Finished! All workspaces were upgraded successfully.</td></tr></table>";
}

die();

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

function processMasterSchemaFile($schemaFile)
{
    global $DB_ADAPTER;
    global $DB_HOST;
    global $DB_USER;
    global $DB_PASS;
    global $DB_NAME;
    global $upgradeData;

    //convert newSchema to array
    if (isset($upgradeData['aNewSchema'])) {
        $newSchema = $upgradeData['aNewSchema'];
    } else {
        $newSchema = schemaToArray($schemaFile);
        $upgradeData['aNewSchema'] = $newSchema;
    }
    $oldSchema = processSchemaFile();
    if (is_null($oldSchema)) {
        return;
    }
    $changes = obtainChanges($oldSchema, $newSchema);

    $database = new database($DB_ADAPTER, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$database->isConnected()) {
        return;
    }
    $database->iFetchType = MYSQLI_NUM;

    $database->logQuery(count($changes));

    foreach ($changes['tablesToAdd'] as $table => $columns) {
        $database->executeQuery($database->generateCreateTableSQL($table, $columns));
        if (isset($changes['tablesToAdd'][$table]['INDEXES'])) {
            foreach ($changes['tablesToAdd'][$table]['INDEXES'] as $indexName => $attribute) {
                $database->executeQuery($database->generateAddKeysSQL($table, $indexName, $attribute));
            }
        }
    }

    foreach ($changes['tablesToAlter'] as $table => $actions) {
        foreach ($actions as $key => $action) {
            foreach ($action as $column => $data) {
                switch ($key) {
                    case 'DROP':
                        $database->executeQuery($database->generateDropColumnSQL($table, $data));
                        break;
                    case 'ADD':
                        $database->executeQuery($database->generateAddColumnSQL($table, $column, $data));
                        break;
                    case 'CHANGE':
                        $database->executeQuery($database->generateChangeColumnSQL($table, $column, $data));
                        break;
                }
            }
        }
    }

    foreach ($changes['tablesWithNewIndex'] as $table => $index) {
        foreach ($index as $indexName => $indexFields) {
            $database->executeQuery($database->generateAddKeysSQL($table, $indexName, $indexFields));
        }
    }

    foreach ($changes['tablesToAlterIndex'] as $table => $index) {
        foreach ($index as $indexName => $indexFields) {
            $database->executeQuery($database->generateDropKeySQL($table, $indexName));
            $database->executeQuery($database->generateAddKeysSQL($table, $indexName, $indexFields));
        }
    }
    $database->close();
}

function processSchemaFile()
{
    global $DB_ADAPTER;
    global $DB_HOST;
    global $DB_USER;
    global $DB_PASS;
    global $DB_NAME;

    try {
        $oldSchema = [];
        $database = new database($DB_ADAPTER, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

        if (!$database->isConnected()) {
            $database->logQuery(G::LoadTranslation('ID_DOES_NOT_EXIST_AVAILABLE_CONNECTION'));
            return null;
        }

        $database->iFetchType = MYSQLI_NUM;
        $result = $database->executeQuery($database->generateShowTablesSQL());

    } catch (Exception $e) {
        $database->logQuery($e->getmessage());
        return null;
    }

    //going thru all tables in current WF_ database
    foreach ($result as $table) {
        $table = strtoupper($table);

        //get description of each table, ( column and primary keys )
        $database->iFetchType = MYSQLI_ASSOC;
        $description = $database->executeQuery($database->generateDescTableSQL($table));
        $oldSchema[$table] = [];
        foreach ($description as $field) {
            $oldSchema[$table][$field['Field']]['Field'] = $field['Field'];
            $oldSchema[$table][$field['Field']]['Type'] = $field['Type'];
            $oldSchema[$table][$field['Field']]['Null'] = $field['Null'];
            $oldSchema[$table][$field['Field']]['Default'] = $field['Default'];
        }

        //get indexes of each table  SHOW INDEX FROM `ADDITIONAL_TABLES`;   -- WHERE Key_name <> 'PRIMARY'
        $description = $database->executeQuery($database->generateTableIndexSQL($table));
        foreach ($description as $field) {
            if (!isset($oldSchema[$table]['INDEXES'])) {
                $oldSchema[$table]['INDEXES'] = [];
            }
            if (!isset($oldSchema[$table]['INDEXES'][$field['Key_name']])) {
                $oldSchema[$table]['INDEXES'][$field['Key_name']] = [];
            }
            $oldSchema[$table]['INDEXES'][$field['Key_name']][] = $field['Column_name'];
        }
    }
    //finally return the array with old schema obtained from the Database
    if (count($oldSchema) === 0) {
        $oldSchema = null;
    }
    return $oldSchema;
}

//process the schema file in the patch file, and obtain an array
function schemaToArray($schemaFile)
{
    try {
        $schema = [];
        $xml = new DomDocument();
        $xml->load($schemaFile);
        $tables = $xml->getElementsByTagName('table');
        foreach ($tables as $table) {
            $primaryKeys = [];
            $tableName = $table->getAttribute('name');
            $schema[$tableName] = [];
            $columns = $table->getElementsByTagName('column');
            foreach ($columns as $column) {
                $columnName = $column->getAttribute('name');
                $schema[$tableName][$columnName] = [];
                $vendors = $column->getElementsByTagName('vendor');
                foreach ($vendors as $vendor) {
                    if ($vendor->getAttribute('type') == config('connections.driver')) {
                        break;
                    }
                }
                $parameters = $column->getElementsByTagName('parameter');
                foreach ($parameters as $oParameter) {
                    $parameterName = ucwords($oParameter->getAttribute('name'));
                    if ($parameterName == 'Key' && strtoupper($oParameter->getAttribute('value')) == 'PRI') {
                        $primaryKeys[] = $column->getAttribute('name');
                    }

                    if (in_array($parameterName, ['Field', 'Type', 'Null', 'Default'])) {
                        $schema[$tableName][$columnName][$parameterName] = $oParameter->getAttribute('value');
                    }
                }
            }

            if (is_array($primaryKeys) && count($primaryKeys) > 0) {
                $schema[$tableName]['INDEXES']['PRIMARY'] = $primaryKeys;
            }
            $index = $table->getElementsByTagName('index');
            foreach ($index as $fieldIndex) {
                $attribute = [];
                $aIndexesColumns = $fieldIndex->getElementsByTagName('index-column');
                foreach ($aIndexesColumns as $oIndexColumn) {
                    $attribute[] = $oIndexColumn->getAttribute('name');
                }
                $schema[$tableName]['INDEXES'][$fieldIndex->getAttribute('name')] = $attribute;
            }
        }
        return $schema;
    } catch (Exception $oError) {
        throw $oError;
    }
}

function obtainChanges($oldSchema, $newSchema)
{

    $changes = ['tablesToAdd' => [], 'tablesToAlter' => [], 'tablesWithNewIndex' => [], 'tablesToAlterIndex' => []];

    //new tables  to create and alter
    foreach ($newSchema as $tableName => $columns) {
        if (!isset($oldSchema[$tableName])) {
            $changes['tablesToAdd'][$tableName] = $columns;
        } else {
            //drop old columns
            foreach ($oldSchema[$tableName] as $columnName => $parameters) {
                if (!isset($newSchema[$tableName][$columnName])) {
                    if (!isset($changes['tablesToAlter'][$tableName])) {
                        $changes['tablesToAlter'][$tableName] = ['DROP' => [], 'ADD' => [], 'CHANGE' => []];
                    }
                    $changes['tablesToAlter'][$tableName]['DROP'][$columnName] = $columnName;
                }
            }

            //create new columns
            foreach ($columns as $columnName => $parameters) {
                if ($columnName != 'INDEXES') {
                    if (!isset($oldSchema[$tableName][$columnName])) { //this column doesnt exist in oldschema
                        if (!isset($changes['tablesToAlter'][$tableName])) {
                            $changes['tablesToAlter'][$tableName] = ['DROP' => [], 'ADD' => [], 'CHANGE' => []];
                        }
                        $changes['tablesToAlter'][$tableName]['ADD'][$columnName] = $parameters;
                    } else { //the column exists
                        $newField = $newSchema[$tableName][$columnName];
                        $oldField = $oldSchema[$tableName][$columnName];
                        //both are null, no change is required
                        if (!isset($newField['Default']) && !isset($oldField['Default']))
                            $changeDefaultAttr = false;
                        //one of them is null, change IS required
                        if (!isset($newField['Default']) && isset($oldField['Default']) && $oldField['Default'] != '')
                            $changeDefaultAttr = true;
                        if (isset($newField['Default']) && !isset($oldField['Default']))
                            $changeDefaultAttr = true;
                        //both are defined and they are different.
                        if (isset($newField['Default']) && isset($oldField['Default'])) {
                            $changeDefaultAttr = false;
                            if ($newField['Default'] != $oldField['Default']) {
                                $changeDefaultAttr = true;
                            }
                        }
                        //special cases
                        // BLOB and TEXT columns cannot have DEFAULT values.  http://dev.mysql.com/doc/refman/5.0/en/blob.html
                        if (in_array(strtolower($newField['Type']), ['text', 'mediumtext']))
                            $changeDefaultAttr = false;

                        //#1067 - Invalid default value for datetime field
                        if (in_array($newField['Type'], ['datetime']) && isset($newField['Default']) && $newField['Default'] == '') {
                            $changeDefaultAttr = false;
                        }

                        //#1067 - Invalid default value for int field
                        if (substr($newField['Type'], 0, 3) && isset($newField['Default']) && $newField['Default'] == '') {
                            $changeDefaultAttr = false;
                        }

                        //if any difference exists, then insert the difference in aChanges
                        if ($newField['Field'] != $oldField['Field'] || $newField['Type'] != $oldField['Type'] || $newField['Null'] != $oldField['Null'] || $changeDefaultAttr) {
                            if (!isset($changes['tablesToAlter'][$tableName])) {
                                $changes['tablesToAlter'][$tableName] = ['DROP' => [], 'ADD' => [], 'CHANGE' => []];
                            }
                            $changes['tablesToAlter'][$tableName]['CHANGE'][$columnName]['Field'] = $newField['Field'];
                            $changes['tablesToAlter'][$tableName]['CHANGE'][$columnName]['Type'] = $newField['Type'];
                            $changes['tablesToAlter'][$tableName]['CHANGE'][$columnName]['Null'] = $newField['Null'];
                            if (isset($newField['Default'])) {
                                $changes['tablesToAlter'][$tableName]['CHANGE'][$columnName]['Default'] = $newField['Default'];
                            } else {
                                $changes['tablesToAlter'][$tableName]['CHANGE'][$columnName]['Default'] = null;
                            }
                        }
                    }
                } //only columns, no the indexes column
            } //foreach $columns


            //now check the indexes of table
            if (isset($newSchema[$tableName]['INDEXES'])) {
                foreach ($newSchema[$tableName]['INDEXES'] as $indexName => $indexFields) {
                    if (!isset($oldSchema[$tableName]['INDEXES'][$indexName])) {
                        if (!isset($changes['tablesWithNewIndex'][$tableName])) {
                            $changes['tablesWithNewIndex'][$tableName] = [];
                        }
                        $changes['tablesWithNewIndex'][$tableName][$indexName] = $indexFields;
                    } else {
                        if ($oldSchema[$tableName]['INDEXES'][$indexName] != $indexFields) {
                            if (!isset($changes['tablesToAlterIndex'][$tableName])) {
                                $changes['tablesToAlterIndex'][$tableName] = [];
                            }
                            $changes['tablesToAlterIndex'][$tableName][$indexName] = $indexFields;
                        }
                    }
                }
            }
        } //for-else table exists
    } //for new schema
    return $changes;
}

