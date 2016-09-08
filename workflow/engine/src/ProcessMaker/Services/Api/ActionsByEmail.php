<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;
use \G;
use \Smarty;
use \Criteria;
use \ResultSet;
use \DynaformPeer;
use \ContentPeer;

require_once 'classes/model/AbeConfiguration.php';
require_once 'classes/model/AbeRequests.php';
require_once 'classes/model/AbeResponses.php';
require_once 'classes/model/AppDelegation.php';
require_once 'classes/model/Application.php';
require_once 'classes/model/Users.php';

/**
 * Class ActionsByEmail
 *
 * @author gustavo cruz <gustavo.cruz@colosa.com>
 */
class ActionsByEmail extends Api
{
    private $actionsByEmail;

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            $this->actionsByEmail = new \ProcessMaker\BusinessModel\ActionsByEmail();
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET
     */
    public function getABEList()
    {
        try {
            $projects = array('status' => 200, 'message' => 'Hello');
            return $projects;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     *
     * @url GET /editTemplate
     */
    public function editTemplate($params)
    {
        $this->actionsByEmail->editTemplate($_REQUEST);
    }

    /**
     *
     * @url PUT /updateTemplate
     */
    public function updateTemplate($params)
    {
        $this->actionsByEmail->updateTemplate($_REQUEST);
    }

    /**
     *
     * @url GET /loadFields
     */
    public function loadFields($params)
    {
        return $this->actionsByEmail->loadFields($_REQUEST);
    }

    /**
     *
     * @url PUT /saveConfiguration
     */
    public function saveConfiguration($params)
    {
        return $this->actionsByEmail->saveConfiguration2($_REQUEST);
    }

    /**
     *
     * @url GET /loadActionByEmail
     */
    public function loadActionByEmail($params)
    {
        return $this->actionsByEmail->loadActionByEmail($_REQUEST);
    }

    /**
     *
     * @url POST /forwardMail
     */
    public function forwardMail($params)
    {
        return $this->actionsByEmail->forwardMail($_REQUEST);
    }

    /**
     *
     * @url GET /viewForm
     */
    public function viewForm($params)
    {
        return $this->actionsByEmail->viewForm($_REQUEST);
    }

    /**
     *
     * @url GET /Templates/:proId
     */
    public function getTemplates($proId)
    {
        $templates = array();
        $path = PATH_DATA_MAILTEMPLATES . $proId . PATH_SEP;
        $userUid = $this->getUserId();
        $filesManager = new \ProcessMaker\BusinessModel\FilesManager();
        \G::verifyPath($path, true);

        if (defined('PARTNER_FLAG')) {
            if (!file_exists($path . 'actionsByEmailPartner.html')) {
                $data = array('prf_content' => '', 'prf_filename' => 'actionsByEmailPartner.html', 'prf_path' => 'templates');
                $arrayData = $filesManager->addProcessFilesManager($proId, $userUid, $data);
                @copy(PATH_TPL . 'actionsByEmail' . PATH_SEP . 'actionsByEmailPartner.html', $path . 'actionsByEmail.html');
            }
        } else {
            if (!file_exists($path . 'actionsByEmail.html')) {
                $data = array('prf_content' => '', 'prf_filename' => 'actionsByEmail.html', 'prf_path' => 'templates');
                $arrayData = $filesManager->addProcessFilesManager($proId, $userUid, $data);
                @copy(PATH_TPL . 'actionsByEmail' . PATH_SEP . 'actionsByEmail.html', $path . 'actionsByEmail.html');
            }
        }

        $directory = dir($path);

        while ($object = $directory->read()) {
            if (($object !== '.') && ($object !== '..') && ($object !== 'alert_message.html')) {
                $templates[] = array('FILE' => $object, 'NAME' => $object);
            }
        }
        return $templates;
    }

    /**
     *
     * @url GET /Dynaforms/:proUid
     */
    public function getDynaforms($proUid)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn(DynaformPeer::DYN_UID);
        $criteria->addSelectColumn(ContentPeer::CON_VALUE);
        $criteria->addJoin( DynaformPeer::DYN_UID, ContentPeer::CON_ID, Criteria::LEFT_JOIN );
        $criteria->add( DynaformPeer::PRO_UID, $proUid, Criteria::EQUAL );
        $criteria->add( DynaformPeer::DYN_TYPE, 'xmlform', Criteria::EQUAL );
        $criteria->add( ContentPeer::CON_CATEGORY, 'DYN_TITLE');
        $criteria->add( ContentPeer::CON_LANG, SYS_LANG);
        $dataset = DynaformPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dynaform = array();
        while ($dataset->next()) {
            $aRow = $dataset->getRow();
            $dynaform[] = array('DYN_UID' => $aRow['DYN_UID'], 'DYN_NAME' => $aRow['CON_VALUE']);
        }
        return $dynaform;
    }
}
