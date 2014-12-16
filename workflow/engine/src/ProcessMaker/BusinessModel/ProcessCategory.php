<?php
namespace ProcessMaker\BusinessModel;

class ProcessCategory
{
    private $arrayFieldDefinition = array(
        "CAT_UID"    => array("fieldName" => "CATEGORY_UID",    "type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "categoryUid"),

        "CAT_PARENT" => array("fieldName" => "CATEGORY_PARENT", "type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "categoryParent"),
        "CAT_NAME"   => array("fieldName" => "CATEGORY_NAME",   "type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "categoryName"),
        "CAT_ICON"   => array("fieldName" => "CATEGORY_ICON",   "type" => "string", "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "categoryIcon")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array(
        "filter" => "FILTER",
        "start"  => "START",
        "limit"  => "LIMIT"
    );

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            foreach ($this->arrayFieldDefinition as $key => $value) {
                $this->arrayFieldNameForException[$value["fieldNameAux"]] = $key;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the format of the fields name (uppercase, lowercase)
     *
     * @param bool $flag Value that set the format
     *
     * return void
     */
    public function setFormatFieldNameInUppercase($flag)
    {
        try {
            $this->formatFieldNameInUppercase = $flag;

            $this->setArrayFieldNameForException($this->arrayFieldNameForException);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set exception messages for fields
     *
     * @param array $arrayData Data with the fields
     *
     * return void
     */
    public function setArrayFieldNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the name of the field according to the format
     *
     * @param string $fieldName Field name
     *
     * return string Return the field name according the format
     */
    public function getFieldNameByFormatFieldName($fieldName)
    {
        try {
            return ($this->formatFieldNameInUppercase)? strtoupper($fieldName) : strtolower($fieldName);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the name of a Category
     *
     * @param string $categoryName       Name
     * @param string $categoryUidExclude Unique id of Category to exclude
     *
     * return bool Return true if exists the name of a Category, false otherwise
     */
    public function existsName($categoryName, $categoryUidExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\ProcessCategoryPeer::CATEGORY_UID);

            if ($categoryUidExclude != "") {
                $criteria->add(\ProcessCategoryPeer::CATEGORY_UID, $categoryUidExclude, \Criteria::NOT_EQUAL);
            }

            $criteria->add(\ProcessCategoryPeer::CATEGORY_NAME, $categoryName, \Criteria::EQUAL);

            $rsCriteria = \ProcessCategoryPeer::doSelectRS($criteria);

            if ($rsCriteria->next()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exist the Category in table PROCESS_CATEGORY
     *
     * @param string $categoryUid           Unique id of Category
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if does not exist the Category in table PROCESS_CATEGORY
     */
    public function throwExceptionIfNotExistsCategory($categoryUid, $fieldNameForException)
    {
        try {
            $obj = \ProcessCategoryPeer::retrieveByPK($categoryUid);

            if (is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_CATEGORY_NOT_EXIST", array($fieldNameForException, $categoryUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the name of a Category
     *
     * @param string $categoryName          Name
     * @param string $fieldNameForException Field name for the exception
     * @param string $categoryUidExclude    Unique id of Category to exclude
     *
     * return void Throw exception if exists the name of a Category
     */
    public function throwExceptionIfExistsName($categoryName, $fieldNameForException, $categoryUidExclude = "")
    {
        try {
            if ($this->existsName($categoryName, $categoryUidExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_CATEGORY_NAME_ALREADY_EXISTS", array($fieldNameForException, $categoryName)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $categoryUid Unique id of Category
     * @param array  $arrayData   Data
     *
     * return void Throw exception if data has an invalid value
     */
    public function throwExceptionIfDataIsInvalid($categoryUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayCategoryData = ($categoryUid == "")? array() : $this->getCategory($categoryUid, true);
            $flagInsert        = ($categoryUid == "")? true : false;

            $arrayDataMain = array_merge($arrayCategoryData, $arrayData);

            //Verify data - Field definition
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);

            //Verify data
            if (isset($arrayData["CAT_NAME"])) {
                $this->throwExceptionIfExistsName($arrayData["CAT_NAME"], $this->arrayFieldNameForException["categoryName"], $categoryUid);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Category
     *
     * @param array $arrayData Data
     *
     * return array Return data of the new Category created
     */
    public function create(array $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["CAT_UID"]);

            //Verify data
            $this->throwExceptionIfDataIsInvalid("", $arrayData);

            //Create
            $category = new \ProcessCategory();

            $categoryUid = \ProcessMaker\Util\Common::generateUID();

            $category->setNew(true);
            $category->setCategoryUid($categoryUid);
            $category->setCategoryName($arrayData["CAT_NAME"]);

            $result = $category->save();

            //Return
            return $this->getCategory($categoryUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Category
     *
     * @param string $categoryUid Unique id of Category
     * @param array  $arrayData   Data
     *
     * return array Return data of the Category updated
     */
    public function update($categoryUid, array $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);
            $arrayDataBackup = $arrayData;

            //Verify data
            $this->throwExceptionIfNotExistsCategory($categoryUid, $this->arrayFieldNameForException["categoryUid"]);

            $this->throwExceptionIfDataIsInvalid($categoryUid, $arrayData);

            //Update
            $category = new \ProcessCategory();

            $category->setNew(false);
            $category->setCategoryUid($categoryUid);

            if (isset($arrayData["CAT_NAME"])) {
                $category->setCategoryName($arrayData["CAT_NAME"]);
            }

            $result = $category->save();

            $arrayData = $arrayDataBackup;

            //Return
            if (!$this->formatFieldNameInUppercase) {
                $arrayData = array_change_key_case($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Category
     *
     * @param string $categoryUid Unique id of Category
     *
     * return void
     */
    public function delete($categoryUid)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsCategory($categoryUid, $this->arrayFieldNameForException["categoryUid"]);

            $process = new \Process();

            $arrayTotalProcessesByCategory = $process->getAllProcessesByCategory();

            if (isset($arrayTotalProcessesByCategory[$categoryUid]) && (int)($arrayTotalProcessesByCategory[$categoryUid])> 0) {
                throw new \Exception(\G::LoadTranslation("ID_MSG_CANNOT_DELETE_CATEGORY"));
            }

            //Delete
            $category = new \ProcessCategory();

            $category->setCategoryUid($categoryUid);
            $category->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Category
     *
     * return object
     */
    public function getCategoryCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\ProcessCategoryPeer::CATEGORY_UID);
            $criteria->addSelectColumn(\ProcessCategoryPeer::CATEGORY_PARENT);
            $criteria->addSelectColumn(\ProcessCategoryPeer::CATEGORY_NAME);
            $criteria->addSelectColumn(\ProcessCategoryPeer::CATEGORY_ICON);
            $criteria->add(\ProcessCategoryPeer::CATEGORY_UID, "", \Criteria::NOT_EQUAL);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Category from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Category
     */
    public function getCategoryDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("CAT_UID")             => $record["CATEGORY_UID"],
                $this->getFieldNameByFormatFieldName("CAT_NAME")            => $record["CATEGORY_NAME"],
                $this->getFieldNameByFormatFieldName("CAT_TOTAL_PROCESSES") => $record["CATEGORY_TOTAL_PROCESSES"]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Categories
     *
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Categories
     */
    public function getCategories(array $arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayCategory = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayCategory;
            }

            //Set variables
            $process = new \Process();

            $arrayTotalProcessesByCategory = $process->getAllProcessesByCategory();

            //SQL
            $criteria = $this->getCategoryCriteria();

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(\ProcessCategoryPeer::CATEGORY_NAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE);
            }

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);
                $sortField = (isset($this->arrayFieldDefinition[$sortField]["fieldName"]))? $this->arrayFieldDefinition[$sortField]["fieldName"] : $sortField;

                if (in_array($sortField, array("CATEGORY_UID", "CATEGORY_PARENT", "CATEGORY_NAME", "CATEGORY_ICON"))) {
                    $sortField = \ProcessCategoryPeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = \ProcessCategoryPeer::CATEGORY_NAME;
                }
            } else {
                $sortField = \ProcessCategoryPeer::CATEGORY_NAME;
            }

            if (!is_null($sortDir) && trim($sortDir) != "" && strtoupper($sortDir) == "DESC") {
                $criteria->addDescendingOrderByColumn($sortField);
            } else {
                $criteria->addAscendingOrderByColumn($sortField);
            }

            if (!is_null($start)) {
                $criteria->setOffset((int)($start));
            }

            if (!is_null($limit)) {
                $criteria->setLimit((int)($limit));
            }

            $rsCriteria = \ProcessCategoryPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $row["CATEGORY_TOTAL_PROCESSES"] = (isset($arrayTotalProcessesByCategory[$row["CATEGORY_UID"]]))? (int)($arrayTotalProcessesByCategory[$row["CATEGORY_UID"]]) : 0;

                $arrayCategory[] = $this->getCategoryDataFromRecord($row);
            }

            //Return
            return $arrayCategory;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Category
     *
     * @param string $categoryUid   Unique id of Category
     * @param bool   $flagGetRecord Value that set the getting
     *
     * return array Return an array with data of a Category
     */
    public function getCategory($categoryUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsCategory($categoryUid, $this->arrayFieldNameForException["categoryUid"]);

            //Set variables
            if (!$flagGetRecord) {
                $process = new \Process();

                $arrayTotalProcessesByCategory = $process->getAllProcessesByCategory();
            }

            //Get data
            //SQL
            $criteria = $this->getCategoryCriteria();

            $criteria->add(\ProcessCategoryPeer::CATEGORY_UID, $categoryUid, \Criteria::EQUAL);

            $rsCriteria = \ProcessCategoryPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            if (!$flagGetRecord) {
                $row["CATEGORY_TOTAL_PROCESSES"] = (isset($arrayTotalProcessesByCategory[$row["CATEGORY_UID"]]))? (int)($arrayTotalProcessesByCategory[$row["CATEGORY_UID"]]) : 0;
            }

            //Return
            return (!$flagGetRecord)? $this->getCategoryDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

