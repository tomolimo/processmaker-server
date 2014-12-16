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
     * Validate dep_title
     * @var string $dep_title. Name or Title for Departament
     * @var string $dep_uid. Uid for Departament
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url GET
     */
    static public function depTitle($dep_title, $dep_uid = '')
    {
        $dep_title = trim($dep_title);
        if ($dep_title == '') {
            throw (new \Exception(\G::LoadTranslation("ID_DEPARTMENT_NOT_EXIST", array('dep_title',''))));
        }

        $oCriteria = new \Criteria( 'workflow' );
        $oCriteria->clearSelectColumns();
        $oCriteria->addSelectColumn( \ContentPeer::CON_CATEGORY );
        $oCriteria->addSelectColumn( \ContentPeer::CON_VALUE );
        $oCriteria->addSelectColumn( \DepartmentPeer::DEP_PARENT );
        $oCriteria->add( \ContentPeer::CON_CATEGORY, 'DEPO_TITLE' );
        $oCriteria->addJoin( \ContentPeer::CON_ID, \DepartmentPeer::DEP_UID, \Criteria::LEFT_JOIN );
        $oCriteria->add( \ContentPeer::CON_VALUE, $dep_title );
        $oCriteria->add( \ContentPeer::CON_LANG, SYS_LANG );
        if ($dep_uid != '') {
            $oCriteria->add( \ContentPeer::CON_ID, $dep_uid, \Criteria::NOT_EQUAL );
        }

        $oDataset = \DepartmentPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
        if ($oDataset->next()) {
            throw (new \Exception(\G::LoadTranslation("ID_DEPARTMENT_NOT_EXIST", array('dep_title',$dep_title))));
        }
        return $dep_title;
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
}

