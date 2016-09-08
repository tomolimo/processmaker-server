<?php
namespace ProcessMaker\BusinessModel;

/**
 * Validator fields
 *
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Validator
{
    /**
     * Validate dep_uid
     * @var string $dep_uid. Uid for Departament
     * @var string $nameField. Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function depUid($dep_uid, $nameField = 'dep_uid')
    {
        $dep_uid = trim($dep_uid);
        if ($dep_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_DEPARTMENT_NOT_EXIST", array($nameField,''))));
        }
        $oDepartment = new \Department();
        if (!($oDepartment->existsDepartment($dep_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_DEPARTMENT_NOT_EXIST", array($nameField,$dep_uid))));
        }
        return $dep_uid;
    }

    /**
     * Validate dep_status
     * @var string $dep_uid. Uid for Departament
     * @var string $nameField. Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function depStatus($dep_status)
    {
        $dep_status = trim($dep_status);
        $values = array('ACTIVE', 'INACTIVE');
        if (!in_array($dep_status, $values)) {
            throw (new \Exception(\G::LoadTranslation("ID_DEPARTMENT_NOT_EXIST", array('dep_status',$dep_status))));
        }
        return $dep_status;
    }

    /**
     * Validate usr_uid
     *
     * @param string $usr_uid, Uid for user
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function usrUid($usr_uid, $nameField = 'usr_uid')
    {
        $usr_uid = trim($usr_uid);
        if ($usr_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_USER_NOT_EXIST", array($nameField,''))));
        }
        $oUsers = new \Users();
        if (!($oUsers->userExists($usr_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_USER_NOT_EXIST", array($nameField,$usr_uid))));
        }
        return $usr_uid;
    }

    /**
     * Validate app_uid
     *
     * @param string $app_uid, Uid for application
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function appUid($app_uid, $nameField = 'app_uid')
    {
        $app_uid = trim($app_uid);
        if ($app_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_APPLICATION_NOT_EXIST", array($nameField,''))));
        }
        $oApplication = new \Application();
        if (!($oApplication->exists($app_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_APPLICATION_NOT_EXIST", array($nameField,$app_uid))));
        }
        return $app_uid;
    }

    /**
     * Validate app_uid
     *
     * @param string $tri_uid, Uid for trigger
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function triUid($tri_uid, $nameField = 'tri_uid')
    {
        $tri_uid = trim($tri_uid);
        if ($tri_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_TRIGGER_NOT_EXIST", array($nameField,''))));
        }
        $oTriggers = new \Triggers();
        if (!($oTriggers->TriggerExists($tri_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_TRIGGER_NOT_EXIST", array($nameField,$tri_uid))));
        }
        return $tri_uid;
    }

    /**
     * Validate pro_uid
     *
     * @param string $pro_uid, Uid for process
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function proUid($pro_uid, $nameField = 'pro_uid')
    {
        $pro_uid = trim($pro_uid);
        if ($pro_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_PROCESS_NOT_EXIST", array($nameField,''))));
        }
        $oProcess = new \Process();
        if (!($oProcess->exists($pro_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_PROCESS_NOT_EXIST", array($nameField,$pro_uid))));
        }
        return $pro_uid;
    }

    /**
     * Validate cat_uid
     *
     * @param string $cat_uid, Uid for category
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function catUid($cat_uid, $nameField = 'cat_uid')
    {
        $cat_uid = trim($cat_uid);
        if ($cat_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_CATEGORY_NOT_EXIST", array($nameField,''))));
        }
        $oCategory = new \ProcessCategory();
        if (!($oCategory->exists($cat_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_CATEGORY_NOT_EXIST", array($nameField,$cat_uid))));
        }
        return $cat_uid;
    }

    /**
     * Validate date
     *
     * @param string $date, Date for validate
     * @param string $nameField . Name of field for message
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    static public function isDate($date, $format = 'Y-m-d H:i:s', $nameField = 'app_uid')
    {
        $date = trim($date);
        if ($date == '') {
            throw (new \Exception(\G::LoadTranslation("ID_DATE_NOT_VALID", array('',$format))));
        }
        $d = \DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) == $date)) {
            throw (new \Exception(\G::LoadTranslation("ID_DATE_NOT_VALID", array($date,$format))));
        }
        return $date;
    }

    /**
     * Validate is array
     * @var array $field. Field type array
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    static public function isArray($field, $nameField)
    {
        if (!is_array($field)) {
            throw (new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_ARRAY", array($nameField))));
        }
    }

    /**
     * Validate is string
     * @var array $field. Field type string
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    static public function isString($field, $nameField)
    {
        if (!is_string($field)) {
            throw (new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_STRING", array($nameField))));
        }
    }

    /**
     * Validate is integer
     * @var array $field. Field type integer
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    static public function isInteger($field, $nameField)
    {
        if (!is_integer($field)) {
            throw (new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_INTEGER", array($nameField))));
        }
    }

    /**
     * Validate is boolean
     * @var boolean $field. Field type boolean
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    static public function isBoolean($field, $nameField)
    {
        if (!is_bool($field)) {
            throw (new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_BOOLEAN", array($nameField))));
        }
    }

    /**
     * Validate is boolean
     * @var boolean $field. Field type boolean
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    static public function isNotEmpty($field, $nameField)
    {
        if (empty($field)) {
            throw (new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_IS_EMPTY", array($nameField))));
        }
    }

    /**
     * Verify if data is array
     *
     * @param string $data                 Data
     * @param string $dataNameForException Data name for the exception
     *
     * return void Throw exception if data is not array
     */
    public function throwExceptionIfDataIsNotArray($data, $dataNameForException)
    {
        try {
            if (!is_array($data)) {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_THIS_MUST_BE_ARRAY", array($dataNameForException)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if data is empty
     *
     * @param string $data                 Data
     * @param string $dataNameForException Data name for the exception
     *
     * return void Throw exception if data is empty
     */
    public function throwExceptionIfDataIsEmpty($data, $dataNameForException)
    {
        try {
            if (empty($data)) {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_CAN_NOT_BE_EMPTY", array($dataNameForException)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate data by ISO 8601 format
     *
     * @param mixed $data  Data
     * @param mixed $field Fields
     *
     * @return void Throw exception if data has an invalid value
     */
    public static function throwExceptionIfDataNotMetIso8601Format($data, $field = null)
    {
        try {
            if (!(isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__'])) {
                return;
            }

            $regexpDate = \ProcessMaker\Util\DateTime::REGEXPDATE;
            $regexpTime = \ProcessMaker\Util\DateTime::REGEXPTIME;

            $regexpIso8601 = $regexpDate . 'T' . $regexpTime . '[\+\-]\d{2}:\d{2}';

            switch (gettype($data)) {
                case 'string':
                    if (trim($data) != '' && !preg_match('/^' . $regexpIso8601 . '$/', $data)) {
                        throw new \Exception(\G::LoadTranslation('ID_ISO8601_INVALID_FORMAT', [(!is_null($field) && is_string($field))? $field : $data]));
                    }
                    break;
                case 'array':
                    if (!is_null($field) && is_array($field)) {
                        foreach ($field as $value) {
                            $fieldName = $value;

                            $fieldName = (isset($data[strtoupper($fieldName)]))? strtoupper($fieldName) : $fieldName;
                            $fieldName = (isset($data[strtolower($fieldName)]))? strtolower($fieldName) : $fieldName;

                            if (isset($data[$fieldName]) && trim($data[$fieldName]) != '' && !preg_match('/^' . $regexpIso8601 . '$/', $data[$fieldName])) {
                                throw new \Exception(\G::LoadTranslation('ID_ISO8601_INVALID_FORMAT', [$fieldName]));
                            }
                        }
                    }
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate pager data
     *
     * @param array $arrayData                     Data
     * @param array $arrayVariableNameForException Variable name for exception
     *
     * @return mixed Returns TRUE when pager data is valid, Message Error otherwise
     */
    public static function validatePagerDataByPagerDefinition($arrayPagerData, $arrayVariableNameForException)
    {
        try {
            foreach ($arrayPagerData as $key => $value) {
                $nameForException = (isset($arrayVariableNameForException[$key]))?
                    $arrayVariableNameForException[$key] : $key;

                if (!is_null($value) &&
                    (
                        (string)($value) == '' ||
                        !preg_match('/^(?:\+|\-)?(?:0|[1-9]\d*)$/', $value . '') ||
                        (int)($value) < 0
                    )
                ) {
                    return \G::LoadTranslation('ID_INVALID_VALUE_EXPECTING_POSITIVE_INTEGER', [$nameForException]);
                }
            }

            //Return
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

