<?php
namespace ProcessMaker\BusinessModel\Cases;

use \ProcessMaker\BusinessModel\Util\Attribute;

class Variable extends Attribute
{
    private $runningWorkflow = true;

    private $arrayFieldDefinition = [];

    private $arrayFieldNameForException = [];

    private $arrayVariableNameForException = [
        '$applicationUid',
        '$delIndex',
        '$variableName',
        '$filter',
        '$start',
        '$limit',
        '$arrayKey'
    ];

    /**
     * Constructor of the class
     *
     * @return void
     */
    public function __construct()
    {
        try {
            parent::__construct(
                $this->runningWorkflow, $this->arrayFieldDefinition, $this->arrayVariableNameForException
            );

            $this->arrayFieldNameForException    = $this->getArrayFieldNameForException();
            $this->arrayVariableNameForException = $this->getArrayVariableNameForException();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set runningWorkflow atributte
     *
     * @param bool $flag
     *
     * @return void
     */
    public function setRunningWorkflow($flag)
    {
        try {
            parent::setRunningWorkflow($flag);

            $this->runningWorkflow = $flag;

            $this->arrayFieldNameForException = $this->getArrayFieldNameForException();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set arrayVariableNameForException atributte by data
     *
     * @param array $arrayData
     *
     * @return void
     */
    public function setArrayVariableNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayVariableNameForException[$key] = $value;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Application, AppDelegation and Variable record by data
     *
     * @param string $applicationUid Unique id of Case
     * @param int    $delIndex       Delegation index
     * @param string $variableName   Variable name
     * @param bool   $throwException Flag to throw the exception (This only if the parameters are invalid)
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return mixed Returns array with Application, AppDelegation and Variable record,
     *               ThrowTheException/FALSE otherwise
     */
    private function __getApplicationAppDelegationAndVariableRecordByData(
        $applicationUid,
        $delIndex,
        $variableName,
        $throwException = true
    ) {
        try {
            $case = new \ProcessMaker\BusinessModel\Cases();

            $arrayApplicationData = $case->getApplicationRecordByPk(
                $applicationUid, $this->arrayVariableNameForException, $throwException
            );

            if ($arrayApplicationData === false) {
                return false;
            }

            $arrayAppDelegationData = $case->getAppDelegationRecordByPk(
                $applicationUid, $delIndex, $this->arrayVariableNameForException, $throwException
            );

            if ($arrayAppDelegationData === false) {
                return false;
            }

            $variable = new \ProcessMaker\BusinessModel\Variable();

            $arrayVariableData = $variable->getVariableRecordByName(
                $arrayApplicationData['PRO_UID'], $variableName, $this->arrayVariableNameForException, $throwException
            );

            if ($arrayVariableData === false) {
                return false;
            }

            $case = new \Cases();

            $arrayApplicationData['APP_DATA'] = $case->unserializeData($arrayApplicationData['APP_DATA']);

            //Return
            return [$arrayApplicationData, $arrayAppDelegationData, $arrayVariableData];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Fields of a Grid
     *
     * @param string $projectUid Unique id of Project
     * @param string $gridName   Grid name (Variable name)
     *
     * @return array Returns an array with Fields of a Grid
     */
    private function __getGridFieldDefinitions($projectUid, $gridName)
    {
        try {
            $arrayGridField = [];

            //Get data
            $criteria = new \Criteria('workflow');

            $criteria->addSelectColumn(\DynaformPeer::DYN_CONTENT);

            $criteria->add(\DynaformPeer::PRO_UID, $projectUid, \Criteria::EQUAL);
            $criteria->add(\DynaformPeer::DYN_CONTENT, '%' . $gridName . '%', \Criteria::LIKE);

            $rsCriteria = \DynaformPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $dynaFormContent = \G::json_decode($row['DYN_CONTENT']);

                foreach ($dynaFormContent->items[0]->items as $value) {
                    $arrayField = $value;

                    foreach ($arrayField as $value2) {
                        $fld = $value2;

                        if ($fld->type == 'grid' && $fld->variable == $gridName) {
                            foreach ($fld->columns as $value3) {
                                $col = $value3;

                                if (!isset($arrayGridField[$col->id])) {
                                    $arrayGridField[$col->id] = $col;
                                }
                            }
                            break 2;
                        }
                    }
                }
            }

            //Return
            return $arrayGridField;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate data
     *
     * @param array  $arrayData         Data
     * @param array  $arrayVariableData Variable data
     * @param bool   $throwException    Flag to throw the exception (This only if the parameters are invalid)
     *                                  (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return bool Returns TRUE when array data is valid, ThrowTheException/FALSE otherwise
     */
    private function __validateData(array $arrayData, array $arrayVariableData, $throwException = true)
    {
        try {
            if (empty($arrayData)) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation('ID_INVALID_DATA'));
                } else {
                    return false;
                }
            }

            if (isset($arrayVariableData['arrayGridField']) && empty($arrayVariableData['arrayGridField'])) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_GRID_DOES_NOT_HAVE_FIELDS',
                        [$this->arrayVariableNameForException['$variableName'], $arrayVariableData['VAR_NAME']]
                    ));
                } else {
                    return false;
                }
            }

            $msgException = '';

            switch ($arrayVariableData['VAR_FIELD_TYPE']) {
                case 'grid':
                    foreach ($arrayData as $key => $value) {
                        $row = $value;

                        if (is_array($row)) {
                            foreach ($arrayVariableData['arrayGridField'] as $value2) {
                                $field = $value2;

                                if (isset($row[$field->id])) {
                                    if (isset($row[$field->id . '_label'])) {
                                        unset($row[$field->id], $row[$field->id . '_label']);
                                    } else {
                                        $msgException = $key . ': ' . $field->id . '_label' . ' ' .
                                            \G::LoadTranslation('ID_DOES_NOT_EXIST');
                                        break 2;
                                    }
                                }
                            }

                            if (!empty($row)) {
                                $msgException = $key . ': ' . \G::LoadTranslation('ID_FIELD_INVALID') .
                                    ' (' . implode(', ', array_keys($row)) . ')';
                                break;
                            }
                        } else {
                            $msgException = $key . ': ' . \G::LoadTranslation('ID_INVALID_DATA');
                            break;
                        }
                    }
                    break;
                default:
                    $arrayFieldName = [
                        $arrayVariableData['VAR_NAME'],
                        $arrayVariableData['VAR_NAME'] . '_label'
                    ];

                    foreach ($arrayFieldName as $value) {
                        if (!isset($arrayData[$value])) {
                            $msgException = $value . ' ' . \G::LoadTranslation('ID_DOES_NOT_EXIST');
                            break;
                        }
                    }
                    break;
            }

            if ($msgException != '') {
                if ($throwException) {
                    throw new \Exception($msgException);
                } else {
                    return false;
                }
            }

            //Return
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Variable for the Case
     *
     * @param string $applicationUid Unique id of Case
     * @param int    $delIndex       Delegation index
     * @param string $variableName   Variable name
     * @param array  $arrayData      Data
     * @param bool   $throwException Flag to throw the exception (This only if the parameters are invalid)
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return array Returns data of the new Variable created, ThrowTheException/FALSE otherwise
     */
    public function create($applicationUid, $delIndex, $variableName, array $arrayData, $throwException = true)
    {
        try {
            //Verify data and Set variables
            $result = $this->__getApplicationAppDelegationAndVariableRecordByData(
                $applicationUid, $delIndex, $variableName, $throwException
            );

            if ($result === false) {
                return false;
            }

            $arrayApplicationData   = $result[0];
            $arrayAppDelegationData = $result[1];
            $arrayVariableData      = $result[2];

            if ($arrayVariableData['VAR_FIELD_TYPE'] != 'grid' &&
                isset($arrayApplicationData['APP_DATA'][$variableName])
            ) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_CASE_VARIABLE_ALREADY_EXISTS',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            if ($arrayVariableData['VAR_FIELD_TYPE'] == 'grid') {
                $arrayVariableData['arrayGridField'] = $this->__getGridFieldDefinitions(
                    $arrayVariableData['PRJ_UID'], $arrayVariableData['VAR_NAME']
                );
            }

            $result = $this->__validateData($arrayData, $arrayVariableData, $throwException);

            if ($result === false) {
                return false;
            }

            //Create
            $arrayVariable = [];

            switch ($arrayVariableData['VAR_FIELD_TYPE']) {
                case 'grid':
                    $arrayGridData = (isset($arrayApplicationData['APP_DATA'][$variableName]))?
                        $arrayApplicationData['APP_DATA'][$variableName] : [];

                    $i1 = $i2 = count($arrayGridData);

                    foreach ($arrayData as $value) {
                        $i1++;
                        $arrayGridData[$i1] = $value;
                    }

                    $arrayVariable = array_slice($arrayGridData, $i2, null, true);

                    $arrayApplicationData['APP_DATA'][$variableName] = $arrayGridData;
                    break;
                default:
                    $arrayVariable = [
                        $variableName            => $arrayData[$variableName],
                        $variableName . '_label' => $arrayData[$variableName . '_label']
                    ];

                    $arrayApplicationData['APP_DATA'] = array_merge($arrayApplicationData['APP_DATA'], $arrayVariable);
                    break;
            }

            $case = new \Cases();

            $result = $case->updateCase($applicationUid, $arrayApplicationData);

            //Return
            return $arrayVariable;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Variable for the Case
     *
     * @param string $applicationUid Unique id of Case
     * @param int    $delIndex       Delegation index
     * @param string $variableName   Variable name
     * @param array  $arrayData      Data
     * @param bool   $throwException Flag to throw the exception (This only if the parameters are invalid)
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return bool Returns TRUE when Variable is updated, ThrowTheException/FALSE otherwise
     */
    public function update($applicationUid, $delIndex, $variableName, array $arrayData, $throwException = true)
    {
        try {
            //Verify data and Set variables
            $result = $this->__getApplicationAppDelegationAndVariableRecordByData(
                $applicationUid, $delIndex, $variableName, $throwException
            );

            if ($result === false) {
                return false;
            }

            $arrayApplicationData   = $result[0];
            $arrayAppDelegationData = $result[1];
            $arrayVariableData      = $result[2];

            if (!isset($arrayApplicationData['APP_DATA'][$variableName])) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_CASE_VARIABLE_DOES_NOT_EXIST',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            if ($arrayVariableData['VAR_FIELD_TYPE'] == 'grid') {
                $arrayVariableData['arrayGridField'] = $this->__getGridFieldDefinitions(
                    $arrayVariableData['PRJ_UID'], $arrayVariableData['VAR_NAME']
                );
            }

            $result = $this->__validateData($arrayData, $arrayVariableData, $throwException);

            if ($result === false) {
                return false;
            }

            if ($arrayVariableData['VAR_FIELD_TYPE'] == 'grid') {
                foreach ($arrayData as $key => $value) {
                    if (!isset($arrayApplicationData['APP_DATA'][$variableName][$key])) {
                        if ($throwException) {
                            throw new \Exception($key . ': ' . \G::LoadTranslation('ID_NO_EXIST'));
                        } else {
                            return false;
                        }
                    }
                }
            }

            //Update
            switch ($arrayVariableData['VAR_FIELD_TYPE']) {
                case 'grid':
                    foreach ($arrayData as $key => $value) {
                        $arrayApplicationData['APP_DATA'][$variableName][$key] = array_merge(
                            $arrayApplicationData['APP_DATA'][$variableName][$key], $value
                        );
                    }
                    break;
                default:
                    $arrayApplicationData['APP_DATA'] = array_merge(
                        $arrayApplicationData['APP_DATA'],
                        [
                            $variableName            => $arrayData[$variableName],
                            $variableName . '_label' => $arrayData[$variableName . '_label']
                        ]
                    );
                    break;
            }

            $case = new \Cases();

            $result = $case->updateCase($applicationUid, $arrayApplicationData);

            //Return
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Variable of the Case
     *
     * @param string $applicationUid Unique id of Case
     * @param int    $delIndex       Delegation index
     * @param string $variableName   Variable name
     * @param array  $arrayKey       Keys to delete (Only for Grids)
     * @param bool   $throwException Flag to throw the exception (This only if the parameters are invalid)
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return bool Returns TRUE when Variable is deleted, ThrowTheException/FALSE otherwise
     */
    public function delete($applicationUid, $delIndex, $variableName, array $arrayKey = null, $throwException = true)
    {
        try {
            //Verify data and Set variables
            $result = $this->__getApplicationAppDelegationAndVariableRecordByData(
                $applicationUid, $delIndex, $variableName, $throwException
            );

            if ($result === false) {
                return false;
            }

            $arrayApplicationData   = $result[0];
            $arrayAppDelegationData = $result[1];
            $arrayVariableData      = $result[2];

            if (!isset($arrayApplicationData['APP_DATA'][$variableName])) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_CASE_VARIABLE_DOES_NOT_EXIST',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            if ($arrayVariableData['VAR_FIELD_TYPE'] == 'grid' && !is_null($arrayKey)) {
                $msgException = '';

                if (!empty($arrayKey)) {
                    foreach ($arrayKey as $value) {
                        $key = $value;

                        if (!isset($arrayApplicationData['APP_DATA'][$variableName][$key])) {
                            $msgException = $key . ': ' . \G::LoadTranslation('ID_NO_EXIST');
                            break;
                        }
                    }
                } else {
                    $msgException = \G::LoadTranslation(
                        'ID_INVALID_VALUE_CAN_NOT_BE_EMPTY', [$this->arrayVariableNameForException['$arrayKey']]
                    );
                }

                if ($msgException != '') {
                    if ($throwException) {
                        throw new \Exception($msgException);
                    } else {
                        return false;
                    }
                }
            }

            //Delete
            switch ($arrayVariableData['VAR_FIELD_TYPE']) {
                case 'grid':
                    if (!is_null($arrayKey)) {
                        //Delete keys
                        foreach ($arrayKey as $value) {
                            $key = $value;

                            unset($arrayApplicationData['APP_DATA'][$variableName][$key]);
                        }

                        //Reset keys
                        $arrayGridData = [];
                        $i = 1;

                        foreach ($arrayApplicationData['APP_DATA'][$variableName] as $value) {
                            $arrayGridData[$i] = $value;
                            $i++;
                        }

                        $arrayApplicationData['APP_DATA'][$variableName] = $arrayGridData;
                    } else {
                        unset($arrayApplicationData['APP_DATA'][$variableName]);
                    }
                    break;
                default:
                    unset(
                        $arrayApplicationData['APP_DATA'][$variableName],
                        $arrayApplicationData['APP_DATA'][$variableName . '_label']
                    );
                    break;
            }

            $case = new \Cases();

            $result = $case->updateCase($applicationUid, $arrayApplicationData);

            //Return
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Variable of a Case
     *
     * @param string $applicationUid Unique id of Case
     * @param int    $delIndex       Delegation index
     * @param string $variableName   Variable name
     * @param bool   $throwException Flag to throw the exception (This only if the parameters are invalid)
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return array Returns an array with Variable of a Case, ThrowTheException/FALSE otherwise
     */
    public function getVariableByName(
        $applicationUid,
        $delIndex,
        $variableName,
        $throwException = true
    ) {
        try {
            $arrayVariable = [];

            //Verify data and Set variables
            $result = $this->__getApplicationAppDelegationAndVariableRecordByData(
                $applicationUid, $delIndex, $variableName, $throwException
            );

            if ($result === false) {
                return false;
            }

            $arrayApplicationData   = $result[0];
            $arrayAppDelegationData = $result[1];
            $arrayVariableData      = $result[2];

            if (!isset($arrayApplicationData['APP_DATA'][$variableName])) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_CASE_VARIABLE_DOES_NOT_EXIST',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            //Get Variable
            switch ($arrayVariableData['VAR_FIELD_TYPE']) {
                case 'grid':
                    $arrayVariable = $arrayApplicationData['APP_DATA'][$variableName];
                    break;
                default:
                    $arrayVariable = [
                        $variableName            => $arrayApplicationData['APP_DATA'][$variableName],
                        $variableName . '_label' => isset($arrayApplicationData['APP_DATA'][$variableName . '_label'])?
                            $arrayApplicationData['APP_DATA'][$variableName . '_label'] : ''
                    ];
                    break;
            }

            //Return
            return $arrayVariable;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Variable of a Case (Only for Grids)
     *
     * @param string $applicationUid  Unique id of Case
     * @param int    $delIndex        Delegation index
     * @param string $variableName    Variable name
     * @param array  $arrayFilterData Data of the filters
     * @param int    $start           Start
     * @param int    $limit           Limit
     * @param bool   $throwException  Flag to throw the exception (This only if the parameters are invalid)
     *                                (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return array Returns an array with Variable of a Case, ThrowTheException/FALSE otherwise
     */
    public function getVariableByNamePaged(
        $applicationUid,
        $delIndex,
        $variableName,
        $arrayFilterData = null,
        $start = null,
        $limit = null,
        $throwException = true
    ) {
        try {
            $arrayVariable = [];

            $numRecTotal = 0;

            //Verify data and Set variables
            $flagFilter = !is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData['filter']);

            $result = \ProcessMaker\BusinessModel\Validator::validatePagerDataByPagerDefinition(
                [
                    $this->arrayVariableNameForException['$start'] => $start,
                    $this->arrayVariableNameForException['$limit'] => $limit
                ],
                $this->arrayVariableNameForException
            );

            if ($result !== true) {
                if ($throwException) {
                    throw new \Exception($result);
                } else {
                    return false;
                }
            }

            $result = $this->__getApplicationAppDelegationAndVariableRecordByData(
                $applicationUid, $delIndex, $variableName, $throwException
            );

            if ($result === false) {
                return false;
            }

            $arrayApplicationData   = $result[0];
            $arrayAppDelegationData = $result[1];
            $arrayVariableData      = $result[2];

            if (!isset($arrayApplicationData['APP_DATA'][$variableName])) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_CASE_VARIABLE_DOES_NOT_EXIST',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            if ($arrayVariableData['VAR_FIELD_TYPE'] != 'grid') {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_VARIABLE_NO_IS_GRID',
                        [$this->arrayVariableNameForException['$variableName'], $variableName]
                    ));
                } else {
                    return false;
                }
            }

            //Set variables
            $filterName = 'filter';

            if ($flagFilter) {
                $arrayAux = [
                    ''      => 'filter',
                    'LEFT'  => 'lfilter',
                    'RIGHT' => 'rfilter'
                ];

                $filterName = $arrayAux[
                    (isset($arrayFilterData['filterOption']))? $arrayFilterData['filterOption'] : ''
                ];
            }

            //Get Variable
            if (!is_null($limit) && (string)($limit) == '0') {
                return [
                    'total'     => $numRecTotal,
                    'start'     => (int)((!is_null($start))? $start : 0),
                    'limit'     => (int)((!is_null($limit))? $limit : 0),
                    $filterName => ($flagFilter)? $arrayFilterData['filter'] : '',
                    'data'      => $arrayVariable
                ];
            }

            $arraySearch = [
                ''      => '.*' . $arrayFilterData['filter'] . '.*',
                'LEFT'  => $arrayFilterData['filter'] . '.*',
                'RIGHT' => '.*' . $arrayFilterData['filter']
            ];

            $search = $arraySearch[
                (isset($arrayFilterData['filterOption']))? $arrayFilterData['filterOption'] : ''
            ];

            foreach ($arrayApplicationData['APP_DATA'][$variableName] as $key => $value) {
                if ($flagFilter && trim($arrayFilterData['filter']) != '') {
                    foreach ($value as $key2 => $value2) {
                        if (preg_match('/^' . $search . '$/i', $value2)) {
                            $arrayVariable[$key] = $value;
                            $numRecTotal++;
                            break;
                        }
                    }
                } else {
                    $arrayVariable[$key] = $value;
                    $numRecTotal++;
                }
            }

            $arrayVariable = array_slice(
                $arrayVariable,
                (!is_null($start))? (int)($start) : 0,
                (!is_null($limit))? (int)($limit) : null,
                true
            );

            //Return
            return [
                'total'     => $numRecTotal,
                'start'     => (int)((!is_null($start))? $start : 0),
                'limit'     => (int)((!is_null($limit))? $limit : 0),
                $filterName => ($flagFilter)? $arrayFilterData['filter'] : '',
                'data'      => $arrayVariable
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

