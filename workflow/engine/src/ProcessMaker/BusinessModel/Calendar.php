<?php
namespace ProcessMaker\BusinessModel;

class Calendar
{
    private $arrayFieldDefinition = array(
        "CAL_UID"         => array("fieldName" => "CALENDAR_UID",         "type" => "string",   "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "calendarUid"),

        "CAL_NAME"        => array("fieldName" => "CALENDAR_NAME",        "type" => "string",   "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "calendarName"),
        "CAL_DESCRIPTION" => array("fieldName" => "CALENDAR_DESCRIPTION", "type" => "string",   "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "calendarDescription"),
        "CAL_WORK_DAYS"   => array("fieldName" => "CALENDAR_WORK_DAYS",   "type" => "array",    "required" => true,  "empty" => false, "defaultValues" => array(1, 2, 3, 4, 5, 6, 7), "fieldNameAux" => "calendarWorkDays"),
        "CAL_STATUS"      => array("fieldName" => "CALENDAR_STATUS",      "type" => "string",   "required" => false, "empty" => false, "defaultValues" => array("ACTIVE", "INACTIVE"), "fieldNameAux" => "calendarStatus"),
        "CAL_WORK_HOUR"   => array("fieldName" => "CALENDAR_WORK_HOUR",   "type" => "array",    "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "calendarWorkHour"),
        "CAL_HOLIDAY"     => array("fieldName" => "CALENDAR_HOLIDAY",     "type" => "array",    "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "calendarHoliday"),

        "CAL_CREATE_DATE" => array("fieldName" => "CALENDAR_CREATE_DATE", "type" => "datetime", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "calendarCreateDate"),
        "CAL_UPDATE_DATE" => array("fieldName" => "CALENDAR_UPDATE_DATE", "type" => "datetime", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "calendarUpdateDate")
    );

    private $arrayWorkHourFieldDefinition = array(
        "DAY"        => array("type" => "int",  "required" => true, "empty" => false, "defaultValues" => array(0, 1, 2, 3, 4, 5, 6, 7), "fieldNameAux" => "day"),
        "HOUR_START" => array("type" => "hour", "required" => true, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "hourStart"),
        "HOUR_END"   => array("type" => "hour", "required" => true, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "hourEnd")
    );

    private $arrayHolidayFieldDefinition = array(
        "NAME"       => array("type" => "string", "required" => true, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "name"),
        "DATE_START" => array("type" => "date",   "required" => true, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "dateStart"),
        "DATE_END"   => array("type" => "date",   "required" => true, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "dateEnd")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array(
        "filter" => "FILTER",
        "start"  => "START",
        "limit"  => "LIMIT"
    );
    private $arrayWorkHourFieldNameForException = array();
    private $arrayHolidayFieldNameForException = array();

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

            foreach ($this->arrayWorkHourFieldDefinition as $key => $value) {
                $this->arrayWorkHourFieldNameForException[$value["fieldNameAux"]] = $key;
            }

            foreach ($this->arrayHolidayFieldDefinition as $key => $value) {
                $this->arrayHolidayFieldNameForException[$value["fieldNameAux"]] = $key;
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
            $this->setArrayWorkHourFieldNameForException($this->arrayWorkHourFieldNameForException);
            $this->setArrayHolidayFieldNameForException($this->arrayHolidayFieldNameForException);
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
    public function setArrayFieldNameForException($arrayData)
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
     * Set exception messages for fields
     *
     * @param array $arrayData Data with the fields
     *
     * return void
     */
    public function setArrayWorkHourFieldNameForException($arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayWorkHourFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
            }
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
    public function setArrayHolidayFieldNameForException($arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayHolidayFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
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
     * Verify if exists the name of a Calendar
     *
     * @param string $calendarName       Name
     * @param string $calendarUidExclude Unique id of Calendar to exclude
     *
     * return bool Return true if exists the name of a Calendar, false otherwise
     */
    public function existsName($calendarName, $calendarUidExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_UID);
            $criteria->add(\CalendarDefinitionPeer::CALENDAR_STATUS, "DELETED", \Criteria::NOT_EQUAL);

            if ($calendarUidExclude != "") {
                $criteria->add(\CalendarDefinitionPeer::CALENDAR_UID, $calendarUidExclude, \Criteria::NOT_EQUAL);
            }

            $criteria->add(\CalendarDefinitionPeer::CALENDAR_NAME, $calendarName, \Criteria::EQUAL);

            $rsCriteria = \CalendarDefinitionPeer::doSelectRS($criteria);

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
     * Transform Work Days
     *
     * @param mixed $workDays Work days
     * @param bool  $toDb     If is true transform data to represent it according to database, do the reverse otherwise
     *
     * return mixed Return Work days
     */
    public function workDaysTransformData($workDays, $toDb = true)
    {
        try {
            $arrayDayName = array("SUN", "ALL");
            $arrayDay     = array(7, 0);
            $arrayDayDb   = array(0, 7);

            $data = (is_string($workDays) && preg_match("/\|/", $workDays))? explode("|", $workDays) : $workDays;
            $type = "int";

            if (is_array($data)) {
                $data = implode("|", $data);
                $type = "array";
            }

            if ($toDb) {
                $data = str_replace($arrayDay, $arrayDayName, $data);
                $data = str_replace($arrayDayName, $arrayDayDb, $data);
            } else {
                $data = str_replace($arrayDayDb, $arrayDayName, $data);
                $data = str_replace($arrayDayName, $arrayDay, $data);
            }

            switch ($type) {
                case "int":
                    $data = (int)($data);
                    break;
                case "array":
                    $data = explode("|", $data);

                    foreach ($data as $key => $value) {
                        $data[$key] = (int)($value);
                    }
                    break;
            }

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if doesn't exists the Calendar in table CALENDAR_DEFINITION
     *
     * @param string $calendarUid           Unique id of Calendar
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if doesn't exists the Calendar in table CALENDAR_DEFINITION
     */
    public function throwExceptionIfNotExistsCalendar($calendarUid, $fieldNameForException)
    {
        try {
            $obj = \CalendarDefinitionPeer::retrieveByPK($calendarUid);

            if (!(is_object($obj) && get_class($obj) == "CalendarDefinition")) {
                throw new \Exception(\G::LoadTranslation("ID_CALENDAR_DOES_NOT_EXIST", array($fieldNameForException, $calendarUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the name of a Calendar
     *
     * @param string $calendarName          Name
     * @param string $fieldNameForException Field name for the exception
     * @param string $calendarUidExclude    Unique id of Calendar to exclude
     *
     * return void Throw exception if exists the name of a Calendar
     */
    public function throwExceptionIfExistsName($calendarName, $fieldNameForException, $calendarUidExclude = "")
    {
        try {
            if ($this->existsName($calendarName, $calendarUidExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_CALENDAR_NAME_ALREADY_EXISTS", array($fieldNameForException, $calendarName)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Calendar
     *
     * @param array $arrayData Data
     *
     * return array Return data of the new Calendar created
     */
    public function create($arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = \G::array_change_key_case2($arrayData, CASE_UPPER);

            unset($arrayData["CAL_UID"]);

            //Verify data
            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, true);

            $this->throwExceptionIfExistsName($arrayData["CAL_NAME"], $this->arrayFieldNameForException["calendarName"]);

            if (isset($arrayData["CAL_WORK_DAYS"]) && count($arrayData["CAL_WORK_DAYS"]) < 3) {
                throw (new \Exception(\G::LoadTranslation("ID_MOST_AT_LEAST_3_DAY")));
            }

            if (isset($arrayData["CAL_WORK_HOUR"])) {
                foreach ($arrayData["CAL_WORK_HOUR"] as $value) {
                    $process->throwExceptionIfDataNotMetFieldDefinition($value, $this->arrayWorkHourFieldDefinition, $this->arrayWorkHourFieldNameForException, true);
                }
            }

            if (isset($arrayData["CAL_HOLIDAY"])) {
                foreach ($arrayData["CAL_HOLIDAY"] as $value) {
                    $process->throwExceptionIfDataNotMetFieldDefinition($value, $this->arrayHolidayFieldDefinition, $this->arrayHolidayFieldNameForException, true);
                }
            }

            //Set variables
            $arrayCalendarWorkHour = array();

            if (isset($arrayData["CAL_WORK_HOUR"])) {
                foreach ($arrayData["CAL_WORK_HOUR"] as $value) {
                    if ($value["DAY"] != 0 && !in_array($value["DAY"], $arrayData["CAL_WORK_DAYS"], true)) {
                        throw new \Exception(\G::LoadTranslation("ID_VALUE_SPECIFIED_DOES_NOT_EXIST", array($this->arrayWorkHourFieldNameForException["day"], $this->arrayFieldNameForException["calendarWorkDays"])));
                    }

                    $arrayCalendarWorkHour[] = array(
                        "CALENDAR_BUSINESS_DAY"   => $this->workDaysTransformData($value["DAY"]),
                        "CALENDAR_BUSINESS_START" => $value["HOUR_START"],
                        "CALENDAR_BUSINESS_END"   => $value["HOUR_END"]
                    );
                }
            }

            $arrayCalendarHoliday = array();

            if (isset($arrayData["CAL_HOLIDAY"])) {
                foreach ($arrayData["CAL_HOLIDAY"] as $value) {
                    $arrayCalendarHoliday[] = array(
                        "CALENDAR_HOLIDAY_NAME"  => $value["NAME"],
                        "CALENDAR_HOLIDAY_START" => $value["DATE_START"],
                        "CALENDAR_HOLIDAY_END"   => $value["DATE_END"]
                    );
                }
            }

            $arrayDataAux = array();
            $arrayDataAux["CALENDAR_UID"] = \G::generateUniqueID();
            $arrayDataAux["CALENDAR_NAME"] = $arrayData["CAL_NAME"];
            $arrayDataAux["CALENDAR_DESCRIPTION"] = (isset($arrayData["CAL_DESCRIPTION"]))? $arrayData["CAL_DESCRIPTION"] : "";
            $arrayDataAux["CALENDAR_WORK_DAYS"] = $this->workDaysTransformData($arrayData["CAL_WORK_DAYS"]);
            $arrayDataAux["CALENDAR_STATUS"] = (isset($arrayData["CAL_STATUS"]))? $arrayData["CAL_STATUS"] : "ACTIVE";

            $arrayDataAux["BUSINESS_DAY"] = $arrayCalendarWorkHour;
            $arrayDataAux["HOLIDAY"] = $arrayCalendarHoliday;

            //Create
            $calendarDefinition = new \CalendarDefinition();

            $calendarDefinition->saveCalendarInfo($arrayDataAux);

            //Return
            $arrayData = array_merge(array("CAL_UID" => $arrayDataAux["CALENDAR_UID"]), $arrayData);

            if (!$this->formatFieldNameInUppercase) {
                $arrayData = \G::array_change_key_case2($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Calendar
     *
     * @param string $calendarUid Unique id of Calendar
     * @param array  $arrayData   Data
     *
     * return array Return data of the Calendar updated
     */
    public function update($calendarUid, $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = \G::array_change_key_case2($arrayData, CASE_UPPER);

            //Verify data
            $this->throwExceptionIfNotExistsCalendar($calendarUid, $this->arrayFieldNameForException["calendarUid"]);

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, false);

            if (isset($arrayData["CAL_NAME"])) {
                $this->throwExceptionIfExistsName($arrayData["CAL_NAME"], $this->arrayFieldNameForException["calendarName"], $calendarUid);
            }

            if (isset($arrayData["CAL_WORK_DAYS"]) && count($arrayData["CAL_WORK_DAYS"]) < 3) {
                throw (new \Exception(\G::LoadTranslation("ID_MOST_AT_LEAST_3_DAY")));
            }

            if (isset($arrayData["CAL_WORK_HOUR"])) {
                foreach ($arrayData["CAL_WORK_HOUR"] as $value) {
                    $process->throwExceptionIfDataNotMetFieldDefinition($value, $this->arrayWorkHourFieldDefinition, $this->arrayWorkHourFieldNameForException, true);
                }
            }

            if (isset($arrayData["CAL_HOLIDAY"])) {
                foreach ($arrayData["CAL_HOLIDAY"] as $value) {
                    $process->throwExceptionIfDataNotMetFieldDefinition($value, $this->arrayHolidayFieldDefinition, $this->arrayHolidayFieldNameForException, true);
                }
            }

            //Set variables
            $arrayCalendarData = \G::array_change_key_case2($this->getCalendar($calendarUid), CASE_UPPER);

            $calendarWorkDays = (isset($arrayData["CAL_WORK_DAYS"]))? $arrayData["CAL_WORK_DAYS"] : array_keys($arrayCalendarData["CAL_WORK_DAYS"]);

            $arrayCalendarWorkHour = array();
            $arrayAux = (isset($arrayData["CAL_WORK_HOUR"]))? $arrayData["CAL_WORK_HOUR"] : $arrayCalendarData["CAL_WORK_HOUR"];

            foreach ($arrayAux as $value) {
                if (isset($arrayData["CAL_WORK_HOUR"]) && $value["DAY"] != 0 && !in_array($value["DAY"], $calendarWorkDays, true)) {
                    throw new \Exception(\G::LoadTranslation("ID_VALUE_SPECIFIED_DOES_NOT_EXIST", array($this->arrayWorkHourFieldNameForException["day"], $this->arrayFieldNameForException["calendarWorkDays"])));
                }

                $arrayCalendarWorkHour[] = array(
                    "CALENDAR_BUSINESS_DAY"   => $this->workDaysTransformData($value["DAY"]),
                    "CALENDAR_BUSINESS_START" => $value["HOUR_START"],
                    "CALENDAR_BUSINESS_END"   => $value["HOUR_END"]
                );
            }

            $arrayCalendarHoliday = array();
            $arrayAux = (isset($arrayData["CAL_HOLIDAY"]))? $arrayData["CAL_HOLIDAY"] : $arrayCalendarData["CAL_HOLIDAY"];

            foreach ($arrayAux as $value) {
                $arrayCalendarHoliday[] = array(
                    "CALENDAR_HOLIDAY_NAME"  => $value["NAME"],
                    "CALENDAR_HOLIDAY_START" => $value["DATE_START"],
                    "CALENDAR_HOLIDAY_END"   => $value["DATE_END"]
                );
            }

            $arrayDataAux = array();
            $arrayDataAux["CALENDAR_UID"] = $calendarUid;
            $arrayDataAux["CALENDAR_NAME"] = (isset($arrayData["CAL_NAME"]))? $arrayData["CAL_NAME"] : $arrayCalendarData["CAL_NAME"];
            $arrayDataAux["CALENDAR_DESCRIPTION"] = (isset($arrayData["CAL_DESCRIPTION"]))? $arrayData["CAL_DESCRIPTION"] : $arrayCalendarData["CAL_DESCRIPTION"];
            $arrayDataAux["CALENDAR_WORK_DAYS"] = $this->workDaysTransformData($calendarWorkDays);
            $arrayDataAux["CALENDAR_STATUS"] = (isset($arrayData["CAL_STATUS"]))? $arrayData["CAL_STATUS"] : $arrayCalendarData["CAL_STATUS"];

            $arrayDataAux["BUSINESS_DAY"] = $arrayCalendarWorkHour;
            $arrayDataAux["HOLIDAY"] = $arrayCalendarHoliday;

            //Update
            $calendarDefinition = new \CalendarDefinition();

            $calendarDefinition->saveCalendarInfo($arrayDataAux);

            //Return
            if (!$this->formatFieldNameInUppercase) {
                $arrayData = \G::array_change_key_case2($arrayData, CASE_LOWER);
            }

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Calendar
     *
     * @param string $calendarUid Unique id of Calendar
     *
     * return void
     */
    public function delete($calendarUid)
    {
        try {
            //Verify data
            $calendarDefinition = new \CalendarDefinition();

            $this->throwExceptionIfNotExistsCalendar($calendarUid, $this->arrayFieldNameForException["calendarUid"]);

            $arrayAux = $calendarDefinition->getAllCounterByCalendar("USER");
            $nU = (isset($arrayAux[$calendarUid]))? $arrayAux[$calendarUid] : 0;
            $arrayAux = $calendarDefinition->getAllCounterByCalendar("TASK");
            $nT = (isset($arrayAux[$calendarUid]))? $arrayAux[$calendarUid] : 0;
            $arrayAux = $calendarDefinition->getAllCounterByCalendar("PROCESS");
            $nP = (isset($arrayAux[$calendarUid]))? $arrayAux[$calendarUid] : 0;

            if ($nU + $nT + $nP > 0) {
                throw (new \Exception(\G::LoadTranslation("ID_MSG_CANNOT_DELETE_CALENDAR")));
            }

            //Delete
            $calendarDefinition->deleteCalendar($calendarUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Calendar
     *
     * return object
     */
    public function getCalendarCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_UID);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_NAME);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_DESCRIPTION);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_WORK_DAYS);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_STATUS);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_CREATE_DATE);
            $criteria->addSelectColumn(\CalendarDefinitionPeer::CALENDAR_UPDATE_DATE);
            $criteria->add(\CalendarDefinitionPeer::CALENDAR_STATUS, "DELETED", \Criteria::NOT_EQUAL);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Calendar from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Calendar
     */
    public function getCalendarDataFromRecord($record)
    {
        try {
            $calendarBusinessHours = new \CalendarBusinessHours();
            $calendarHolidays = new \CalendarHolidays();

            $arrayCalendarWorkHour = array();
            $arrayData = $calendarBusinessHours->getCalendarBusinessHours($record["CALENDAR_UID"]);

            foreach ($arrayData as $value) {
                $arrayCalendarWorkHour[] = array(
                    $this->getFieldNameByFormatFieldName("DAY")        => $this->workDaysTransformData($value["CALENDAR_BUSINESS_DAY"] . "", false),
                    $this->getFieldNameByFormatFieldName("HOUR_START") => $value["CALENDAR_BUSINESS_START"] . "",
                    $this->getFieldNameByFormatFieldName("HOUR_END")   => $value["CALENDAR_BUSINESS_END"] . "",
                );
            }

            $arrayCalendarHoliday = array();
            $arrayData = $calendarHolidays->getCalendarHolidays($record["CALENDAR_UID"]);

            foreach ($arrayData as $value) {
                $arrayCalendarHoliday[] = array(
                    $this->getFieldNameByFormatFieldName("NAME")       => $value["CALENDAR_HOLIDAY_NAME"] . "",
                    $this->getFieldNameByFormatFieldName("DATE_START") => $value["CALENDAR_HOLIDAY_START"] . "",
                    $this->getFieldNameByFormatFieldName("DATE_END")   => $value["CALENDAR_HOLIDAY_END"] . "",
                );
            }

            $conf = new \Configurations();
            $confEnvSetting = $conf->getFormats();

            $dateTime = new \DateTime($record["CALENDAR_CREATE_DATE"]);
            $dateCreate = $dateTime->format($confEnvSetting["dateFormat"]);
            $dateTime = new \DateTime($record["CALENDAR_UPDATE_DATE"]);
            $dateUpdate = $dateTime->format($confEnvSetting["dateFormat"]);

            $arrayCalendarWorkDays = array();

            foreach ($this->workDaysTransformData($record["CALENDAR_WORK_DAYS"] . "", false) as $value) {
                $arrayCalendarWorkDays[$value] = \G::LoadTranslation("ID_WEEKDAY_" . (($value != 7)? $value : 0));
            }

            return array(
                $this->getFieldNameByFormatFieldName("CAL_UID")             => $record["CALENDAR_UID"],
                $this->getFieldNameByFormatFieldName("CAL_NAME")            => $record["CALENDAR_NAME"],
                $this->getFieldNameByFormatFieldName("CAL_DESCRIPTION")     => $record["CALENDAR_DESCRIPTION"] . "",
                $this->getFieldNameByFormatFieldName("CAL_WORK_DAYS")       => $arrayCalendarWorkDays,
                $this->getFieldNameByFormatFieldName("CAL_STATUS")          => $record["CALENDAR_STATUS"],
                $this->getFieldNameByFormatFieldName("CAL_WORK_HOUR")       => $arrayCalendarWorkHour,
                $this->getFieldNameByFormatFieldName("CAL_HOLIDAY")         => $arrayCalendarHoliday,
                $this->getFieldNameByFormatFieldName("CAL_CREATE_DATE")     => $dateCreate,
                $this->getFieldNameByFormatFieldName("CAL_UPDATE_DATE")     => $dateUpdate,
                $this->getFieldNameByFormatFieldName("CAL_TOTAL_USERS")     => (int)($record["CALENDAR_TOTAL_USERS"]),
                $this->getFieldNameByFormatFieldName("CAL_TOTAL_PROCESSES") => (int)($record["CALENDAR_TOTAL_PROCESSES"]),
                $this->getFieldNameByFormatFieldName("CAL_TOTAL_TASKS")     => (int)($record["CALENDAR_TOTAL_TASKS"])
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Calendars
     *
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Calendars
     */
    public function getCalendars($arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayCalendar = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayCalendar;
            }

            //Set variables
            $calendar = new \CalendarDefinition();

            $arrayTotalUsersByCalendar = $calendar->getAllCounterByCalendar("USER");
            $arrayTotalProcessesByCalendar = $calendar->getAllCounterByCalendar("PROCESS");
            $arrayTotalTasksByCalendar = $calendar->getAllCounterByCalendar("TASK");

            //SQL
            $criteria = $this->getCalendarCriteria();

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(
                    $criteria->getNewCriterion(\CalendarDefinitionPeer::CALENDAR_NAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE)->addOr(
                    $criteria->getNewCriterion(\CalendarDefinitionPeer::CALENDAR_DESCRIPTION, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE))
                );
            }

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);
                $sortField = (isset($this->arrayFieldDefinition[$sortField]["fieldName"]))? $this->arrayFieldDefinition[$sortField]["fieldName"] : $sortField;

                switch ($sortField) {
                    case "CALENDAR_UID":
                    case "CALENDAR_NAME":
                    case "CALENDAR_DESCRIPTION":
                    case "CALENDAR_WORK_DAYS":
                    case "CALENDAR_STATUS":
                    case "CALENDAR_CREATE_DATE":
                    case "CALENDAR_UPDATE_DATE":
                        $sortField = \CalendarDefinitionPeer::TABLE_NAME . "." . $sortField;
                        break;
                    default:
                        $sortField = \CalendarDefinitionPeer::CALENDAR_NAME;
                        break;
                }
            } else {
                $sortField = \CalendarDefinitionPeer::CALENDAR_NAME;
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

            $rsCriteria = \CalendarDefinitionPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $row["CALENDAR_TOTAL_USERS"] = (isset($arrayTotalUsersByCalendar[$row["CALENDAR_UID"]]))? $arrayTotalUsersByCalendar[$row["CALENDAR_UID"]] : 0;
                $row["CALENDAR_TOTAL_PROCESSES"] = (isset($arrayTotalProcessesByCalendar[$row["CALENDAR_UID"]]))? $arrayTotalProcessesByCalendar[$row["CALENDAR_UID"]] : 0;
                $row["CALENDAR_TOTAL_TASKS"] = (isset($arrayTotalTasksByCalendar[$row["CALENDAR_UID"]]))? $arrayTotalTasksByCalendar[$row["CALENDAR_UID"]] : 0;

                $arrayCalendar[] = $this->getCalendarDataFromRecord($row);
            }

            //Return
            return $arrayCalendar;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Calendar
     *
     * @param string $calendarUid Unique id of Calendar
     *
     * return array Return an array with data of a Calendar
     */
    public function getCalendar($calendarUid)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsCalendar($calendarUid, $this->arrayFieldNameForException["calendarUid"]);

            //Get data
            //Set variables
            $calendar = new \CalendarDefinition();

            $arrayTotalUsersByCalendar = $calendar->getAllCounterByCalendar("USER");
            $arrayTotalProcessesByCalendar = $calendar->getAllCounterByCalendar("PROCESS");
            $arrayTotalTasksByCalendar = $calendar->getAllCounterByCalendar("TASK");

            //SQL
            $criteria = $this->getCalendarCriteria();

            $criteria->add(\CalendarDefinitionPeer::CALENDAR_UID, $calendarUid, \Criteria::EQUAL);

            $rsCriteria = \CalendarDefinitionPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            $row["CALENDAR_TOTAL_USERS"] = (isset($arrayTotalUsersByCalendar[$calendarUid]))? $arrayTotalUsersByCalendar[$calendarUid] : 0;
            $row["CALENDAR_TOTAL_PROCESSES"] = (isset($arrayTotalProcessesByCalendar[$calendarUid]))? $arrayTotalProcessesByCalendar[$calendarUid] : 0;
            $row["CALENDAR_TOTAL_TASKS"] = (isset($arrayTotalTasksByCalendar[$calendarUid]))? $arrayTotalTasksByCalendar[$calendarUid] : 0;

            //Return
            return $this->getCalendarDataFromRecord($row);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

