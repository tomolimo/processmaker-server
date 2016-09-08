<?php
namespace ProcessMaker\BusinessModel\DynaForm;

use \ProcessMaker\BusinessModel\Util\Attribute;

class Grid extends Attribute
{
    private $runningWorkflow = true;

    private $arrayFieldDefinition = [];

    private $arrayFieldNameForException = [];

    private $arrayVariableNameForException = [
        '$projectUid',
        '$dynaFormUid',
        '$gridName',
        '$fieldId'
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
     * Get Fields of a Grid
     *
     * @param string $dynaFormUid    Unique id of DynaForm
     * @param string $gridName       Grid name (Variable name)
     * @param bool   $throwException Flag to throw the exception if the main parameters are invalid or do not exist
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return array Returns an array with Fields of a Grid, ThrowTheException/FALSE otherwise
     */
    public function getGridFieldDefinitions($dynaFormUid, $gridName, $throwException = true)
    {
        try {
            //Verify data and Set variables
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();

            $arrayDynaFormData = $dynaForm->getDynaFormRecordByPk(
                $dynaFormUid, $this->arrayVariableNameForException, $throwException
            );

            if ($arrayDynaFormData === false) {
                return false;
            }

            //Get data
            $dynaFormContent = \G::json_decode($arrayDynaFormData['DYN_CONTENT']);

            $arrayGridField = [];
            $flagFound = false;

            foreach ($dynaFormContent->items[0]->items as $value) {
                $arrayField = $value;

                foreach ($arrayField as $value2) {
                    $fld = $value2;

                    if ($fld->type == 'grid' && $fld->variable == $gridName) {
                        $arrayGridField = $fld->columns;
                        $flagFound = true;
                        break 2;
                    }
                }
            }

            if (!$flagFound) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_GRID_DOES_NOT_EXIST_IN_DYNAFORM',
                        [$this->arrayVariableNameForException['$gridName'], $gridName]
                    ));
                } else {
                    return false;
                }
            }

            //Return
            return $arrayGridField;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Field of a Grid
     *
     * @param string $dynaFormUid    Unique id of DynaForm
     * @param string $gridName       Grid name (Variable name)
     * @param string $fieldId        Field id
     * @param bool   $throwException Flag to throw the exception if the main parameters are invalid or do not exist
     *                               (TRUE: throw the exception; FALSE: returns FALSE)
     *
     * @return array Returns an array with Field of a Grid, ThrowTheException/FALSE otherwise
     */
    public function getGridFieldDefinition($dynaFormUid, $gridName, $fieldId, $throwException = true)
    {
        try {
            //Verify data and Set variables
            $arrayGridField = $this->getGridFieldDefinitions($dynaFormUid, $gridName, $throwException);

            if ($arrayGridField === false) {
                return false;
            }

            //Get data
            $field = null;
            $flagFound = false;

            foreach ($arrayGridField as $value) {
                $fld = $value;

                if ($fld->id == $fieldId) {
                    $field = $fld;
                    $flagFound = true;
                    break;
                }
            }

            if (!$flagFound) {
                if ($throwException) {
                    throw new \Exception(\G::LoadTranslation(
                        'ID_GRID_FIELD_DOES_NOT_EXIST',
                        [$this->arrayVariableNameForException['$fieldId'], $fieldId]
                    ));
                } else {
                    return false;
                }
            }

            //Return
            return $field;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

