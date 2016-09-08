<?php
namespace ProcessMaker\BusinessModel\Util;

class Attribute
{
    private $runningWorkflow = true;

    private $arrayFieldDefinition = [];

    private $arrayFieldNameForException = [];

    private $arrayVariableNameForException = [];

    /**
     * Constructor of the class
     *
     * @param bool  $runningWorkflow
     * @param array $arrayFieldDefinition
     * @param array $arrayVariableNameForException
     *
     * @return void
     */
    public function __construct($runningWorkflow, array $arrayFieldDefinition, array $arrayVariableNameForException)
    {
        try {
            $this->runningWorkflow = $runningWorkflow;
            $this->arrayFieldDefinition = $arrayFieldDefinition;

            foreach ($arrayFieldDefinition as $key => $value) {
                if (isset($value['fieldNameAux'])) {
                    $this->arrayFieldNameForException[$value['fieldNameAux']] = $key;
                }
            }

            foreach ($arrayVariableNameForException as $value) {
                $this->arrayVariableNameForException[$value] = $value;
            }
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
            $this->runningWorkflow = $flag;

            $this->setArrayFieldNameForException($this->arrayFieldNameForException);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set arrayFieldNameForException atributte by data
     *
     * @param array $arrayData
     *
     * @return void
     */
    public function setArrayFieldNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayFieldNameForException[$key] = $this->convertFieldNameByRunningWorkflow($value);
            }
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
     * Get arrayFieldNameForException atributte
     *
     * @return array
     */
    public function getArrayFieldNameForException()
    {
        try {
            return $this->arrayFieldNameForException;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get arrayVariableNameForException atributte
     *
     * @return array
     */
    public function getArrayVariableNameForException()
    {
        try {
            return $this->arrayVariableNameForException;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert field name by runningWorkflow
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function convertFieldNameByRunningWorkflow($fieldName)
    {
        try {
            return ($this->runningWorkflow)? strtoupper($fieldName) : strtolower($fieldName);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

