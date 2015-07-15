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
        // Action Validations
        if (!isset($_REQUEST['TEMPLATE'])) {
            $_REQUEST['TEMPLATE'] = '';
        }

        if ($_REQUEST['TEMPLATE'] == '') {
            throw new Exception('The TEMPLATE parameter is empty.');
        }

        $data = array(
            'CONTENT' => file_get_contents(PATH_DATA_MAILTEMPLATES . $_REQUEST['PRO_UID'] . PATH_SEP . $_REQUEST['TEMPLATE']),
            'TEMPLATE' => $_REQUEST['TEMPLATE'],
        );

        global $G_PUBLISH;

        $G_PUBLISH = new Publisher();
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'actionsByEmail/actionsByEmail_FileEdit', '', $data);

        G::RenderPage('publish', 'raw');
        die();
    }

    /**
     * 
     * @url PUT /updateTemplate
     */
    public function updateTemplate($params)
    {
        // Action Validations
        if (!isset($_REQUEST['TEMPLATE'])) {
            $_REQUEST['TEMPLATE'] = '';
        }

        if (!isset($_REQUEST['CONTENT'])) {
            $_REQUEST['CONTENT'] = '';
        }

        if ($_REQUEST['TEMPLATE'] == '') {
            throw new Exception('The TEMPLATE parameter is empty.');
        }

        $templateFile = fopen(PATH_DATA_MAILTEMPLATES . $_REQUEST['PRO_UID'] . PATH_SEP . $_REQUEST['TEMPLATE'], 'w');
        $content = stripslashes($_REQUEST['CONTENT']);
        $content = str_replace('@amp@', '&', $content);
        $content = base64_decode($content);

        fwrite($templateFile, $content);
        fclose($templateFile);
    }

    /**
     * 
     * @url GET /loadFields
     */
    public function loadFields($params)
    {
        if (!isset($_REQUEST['DYN_UID'])) {
            $_REQUEST['DYN_UID'] = '';
        }

        if (!isset($_REQUEST['PRO_UID'])) {
            $_REQUEST['PRO_UID'] = '';
        }

        $response->emailFields = array();
        $response->actionFields = array();

        if ($_REQUEST['PRO_UID'] != '' && $_REQUEST['DYN_UID']) {
            $dynaform = new Form($_REQUEST['PRO_UID'] . PATH_SEP . $_REQUEST['DYN_UID'], PATH_DYNAFORM, SYS_LANG, false);

            foreach ($dynaform->fields as $fieldName => $data) {
                switch ($data->type) {
                    case 'text':
                    case 'suggest':
                    case 'hidden':
                    case 'textarea':
                        $response->emailFields[] = array('value' => $data->name, 'label' => $data->label . ' (@@' . $data->name . ')');
                        break;
                    case 'dropdown':
                    case 'radiogroup':
                    case 'yesno':
                    case 'checkbox':
                        $response->actionFields[] = array('value' => $data->name, 'label' => $data->label . ' (@@' . $data->name . ')');
                        break;
                }
            }
        }
        return $response;
    }

    /**
     * 
     * @url PUT /saveConfiguration
     */
    public function saveConfiguration($params)
    {
        if (!isset($_REQUEST['ABE_UID'])) {
            $_REQUEST['ABE_UID'] = '';
        }

        if (!isset($_REQUEST['PRO_UID'])) {
            $_REQUEST['PRO_UID'] = '';
        }

        if (!isset($_REQUEST['TAS_UID'])) {
            $_REQUEST['TAS_UID'] = '';
        }

        if (!isset($_REQUEST['ABE_TYPE'])) {
            $_REQUEST['ABE_TYPE'] = '';
        }

        if (!isset($_REQUEST['ABE_TEMPLATE'])) {
            $_REQUEST['ABE_TEMPLATE'] = '';
        }

        if (!isset($_REQUEST['DYN_UID'])) {
            $_REQUEST['DYN_UID'] = '';
        }

        if (!isset($_REQUEST['ABE_EMAIL_FIELD'])) {
            $_REQUEST['ABE_EMAIL_FIELD'] = '';
        }

        if (!isset($_REQUEST['ABE_ACTION_FIELD'])) {
            $_REQUEST['ABE_ACTION_FIELD'] = '';
        }

        if (!isset($_REQUEST['ABE_CASE_NOTE_IN_RESPONSE'])) {
            $_REQUEST['ABE_CASE_NOTE_IN_RESPONSE'] = 0;
        }

        if ($_REQUEST['PRO_UID'] == '') {
            throw new Exception('The PRO_UID parameter is empty.');
        }

        if ($_REQUEST['TAS_UID'] == '') {
            throw new Exception('The TAS_UID parameter is empty.');
        }

        require_once 'classes/model/AbeConfiguration.php';

        $abeConfigurationInstance = new AbeConfiguration();

        if ($_REQUEST['ABE_TYPE'] != '') {
            if ($_REQUEST['DYN_UID'] == '') {
                throw new Exception('The DYN_UID parameter is empty.');
            }

            try {
                $response->ABE_UID = $abeConfigurationInstance->createOrUpdate($_REQUEST);
            } catch (Exception $error) {
                throw $error;
            }
        } else {
            try {
                $abeConfigurationInstance->deleteByTasUid($_REQUEST['TAS_UID']);
                $response->ABE_UID = '';
            } catch (Exception $error) {
                throw $error;
            }
        }
        return $response;
    }

    /**
     * 
     * @url GET /loadActionByEmail
     */
    public function loadActionByEmail($params)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn('COUNT(*)');

        $criteria->addJoin(AbeConfigurationPeer::ABE_UID, AbeRequestsPeer::ABE_UID);
        $criteria->addJoin(AppDelegationPeer::APP_UID, AbeRequestsPeer::APP_UID);
        $criteria->addJoin(AppDelegationPeer::DEL_INDEX, AbeRequestsPeer::DEL_INDEX);
        $result = AbeConfigurationPeer::doSelectRS($criteria);
        $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $result->next();
        $totalCount = $result->getRow();
        $totalCount = $totalCount['COUNT(*)'];

        $criteria = new Criteria();
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::PRO_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::TAS_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UPDATE_DATE);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_TEMPLATE);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_ACTION_FIELD);
        $criteria->addSelectColumn(AbeConfigurationPeer::DYN_UID);

        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::APP_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::DEL_INDEX);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SENT_TO);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_STATUS);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SUBJECT);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_ANSWERED);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_BODY);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_DATE);

        $criteria->addSelectColumn(ApplicationPeer::APP_NUMBER);

        $criteria->addSelectColumn(AppDelegationPeer::DEL_PREVIOUS);

        $criteria->addJoin(AbeConfigurationPeer::ABE_UID, AbeRequestsPeer::ABE_UID);
        $criteria->addJoin(ApplicationPeer::APP_UID, AbeRequestsPeer::APP_UID);

        $criteria->addJoin(AppDelegationPeer::APP_UID, AbeRequestsPeer::APP_UID);
        $criteria->addJoin(AppDelegationPeer::DEL_INDEX, AbeRequestsPeer::DEL_INDEX);
        $criteria->addDescendingOrderByColumn(AbeRequestsPeer::ABE_REQ_DATE);
        $criteria->setLimit($_REQUEST['limit']);
        $criteria->setOffset($_REQUEST['start']);
        $result = AbeConfigurationPeer::doSelectRS($criteria);
        $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $data = Array();
        $arrayPro = Array();
        $arrayTAS = Array();
        $index = 0;

        while ($result->next()) {
            $data[] = $result->getRow();
            $criteriaRes = new Criteria();

            $criteriaRes->addSelectColumn(AbeResponsesPeer::ABE_RES_UID);
            $criteriaRes->addSelectColumn(AbeResponsesPeer::ABE_RES_CLIENT_IP);
            $criteriaRes->addSelectColumn(AbeResponsesPeer::ABE_RES_DATA);
            $criteriaRes->addSelectColumn(AbeResponsesPeer::ABE_RES_STATUS);
            $criteriaRes->addSelectColumn(AbeResponsesPeer::ABE_RES_MESSAGE);

            $criteriaRes->add(AbeResponsesPeer::ABE_REQ_UID, $data[$index]['ABE_REQ_UID']);

            $resultRes = AbeResponsesPeer::doSelectRS($criteriaRes);
            $resultRes->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $resultRes->next();
            $dataRes = Array();

            if ($dataRes = $resultRes->getRow()) {
                $data[$index]['ABE_RES_UID'] = $dataRes['ABE_RES_UID'];
                $data[$index]['ABE_RES_CLIENT_IP'] = $dataRes['ABE_RES_CLIENT_IP'];
                $data[$index]['ABE_RES_DATA'] = $dataRes['ABE_RES_DATA'];
                $data[$index]['ABE_RES_STATUS'] = $dataRes['ABE_RES_STATUS'];
                $data[$index]['ABE_RES_MESSAGE'] = $dataRes['ABE_RES_MESSAGE'];
            } else {
                $data[$index]['ABE_RES_UID'] = '';
                $data[$index]['ABE_RES_CLIENT_IP'] = '';
                $data[$index]['ABE_RES_DATA'] = '';
                $data[$index]['ABE_RES_STATUS'] = '';
                $data[$index]['ABE_RES_MESSAGE'] = '';
            }

            $criteriaRes = new Criteria();

            $criteriaRes->addSelectColumn(AppDelegationPeer::USR_UID);
            $criteriaRes->addSelectColumn(UsersPeer::USR_FIRSTNAME);
            $criteriaRes->addSelectColumn(UsersPeer::USR_LASTNAME);

            $criteria->addJoin(AppDelegationPeer::APP_UID, $data[$index]['APP_UID']);
            $criteria->addJoin(AppDelegationPeer::DEL_INDEX, $data[$index]['DEL_PREVIOUS']);
            $criteria->addJoin(AppDelegationPeer::USR_UID, UsersPeer::USR_UID);
            $resultRes = AppDelegationPeer::doSelectRS($criteriaRes);
            $resultRes->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $resultRes->next();

            if ($dataRes = $resultRes->getRow()) {
                $data[$index]['USER'] = $dataRes['USR_FIRSTNAME'] . ' ' . $dataRes['USR_LASTNAME'];
            } else {
                $data[$index]['USER'] = '';
            }

            $data[$index]['ABE_REQ_ANSWERED'] = ($data[$index]['ABE_REQ_ANSWERED'] == 1) ? 'YES' : 'NO';
            $index++;
        }

        $response = array();
        $response['totalCount'] = $totalCount;
        $response['data'] = $data;
        return $response;
    }

    /**
     * 
     * @url POST /forwardMail
     */
    public function forwardMail($params)
    {
        if (!isset($_REQUEST['REQ_UID'])) {
            $_REQUEST['REQ_UID'] = '';
        }

        $criteria = new Criteria();
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::PRO_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::TAS_UID);

        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::APP_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::DEL_INDEX);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SENT_TO);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SUBJECT);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_BODY);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_ANSWERED);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_STATUS);

        $criteria->addSelectColumn(AppDelegationPeer::DEL_FINISH_DATE);

        $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $_REQUEST['REQ_UID']);
        $criteria->addJoin(AbeRequestsPeer::ABE_UID, AbeConfigurationPeer::ABE_UID);
        $criteria->addJoin(AppDelegationPeer::APP_UID, AbeRequestsPeer::APP_UID);
        $criteria->addJoin(AppDelegationPeer::DEL_INDEX, AbeRequestsPeer::DEL_INDEX);
        $resultRes = AbeRequestsPeer::doSelectRS($criteria);
        $resultRes->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $resultRes->next();
        $dataRes = Array();

        if ($dataRes = $resultRes->getRow()) {
            if (is_null($dataRes['DEL_FINISH_DATE'])) {
                require_once 'classes/model/Configuration.php';
                G::LoadClass('spool');

                $configuration = new Configuration();
                $sDelimiter = DBAdapter::getStringDelimiter();
                $criteria = new Criteria('workflow');
                $criteria->add(ConfigurationPeer::CFG_UID, 'Emails');
                $criteria->add(ConfigurationPeer::OBJ_UID, '');
                $criteria->add(ConfigurationPeer::PRO_UID, '');
                $criteria->add(ConfigurationPeer::USR_UID, '');
                $criteria->add(ConfigurationPeer::APP_UID, '');

                if (ConfigurationPeer::doCount($criteria) == 0) {
                    $configuration->create(array('CFG_UID' => 'Emails', 'OBJ_UID' => '', 'CFG_VALUE' => '', 'PRO_UID' => '', 'USR_UID' => '', 'APP_UID' => ''));
                    $newConfiguration = array();
                } else {
                    $newConfiguration = $configuration->load('Emails', '', '', '', '');

                    if ($newConfiguration['CFG_VALUE'] != '') {
                        $newConfiguration = unserialize($newConfiguration['CFG_VALUE']);
                    } else {
                        $newConfiguration = array();
                    }
                }

                $spool = new spoolRun();
                $spool->setConfig(array(
                    'MESS_ENGINE' => $newConfiguration['MESS_ENGINE'],
                    'MESS_SERVER' => $newConfiguration['MESS_SERVER'],
                    'MESS_PORT' => $newConfiguration['MESS_PORT'],
                    'MESS_ACCOUNT' => $newConfiguration['MESS_ACCOUNT'],
                    'MESS_PASSWORD' => $newConfiguration['MESS_PASSWORD'],
                    'SMTPAuth' => $newConfiguration['MESS_RAUTH']
                ));

                $spool->create(array(
                    'msg_uid' => '',
                    'app_uid' => $dataRes['APP_UID'],
                    'del_index' => $dataRes['DEL_INDEX'],
                    'app_msg_type' => 'TEST',
                    'app_msg_subject' => $dataRes['ABE_REQ_SUBJECT'],
                    'app_msg_from' => $newConfiguration['MESS_ACCOUNT'],
                    'app_msg_to' => $dataRes['ABE_REQ_SENT_TO'],
                    'app_msg_body' => $dataRes['ABE_REQ_BODY'],
                    'app_msg_cc' => '',
                    'app_msg_bcc' => '',
                    'app_msg_attach' => '',
                    'app_msg_template' => '',
                    'app_msg_status' => 'pending'
                ));

                if ($spool->sendMail()) {
                    $dataRes['ABE_REQ_STATUS'] = 'SENT';

                    $message = 'The email was resend to: ' . $dataRes['ABE_REQ_SENT_TO'];
                } else {
                    $dataRes['ABE_REQ_STATUS'] = 'ERROR';
                    $message = 'There was a problem sending the email to: ' . $dataRes['ABE_REQ_SENT_TO'] . ', please try later.';
                }

                try {
                    $abeRequestsInstance = new AbeRequests();
                    $abeRequestsInstance->createOrUpdate($dataRes);
                } catch (Exception $error) {
                    throw $error;
                }
            } else {
                $message = 'Unable to send email, the task is closed.';
            }
        } else {
            $message = 'An unexpected error occurred please try again later.';
        }

        return $message;
    }

    /**
     * 
     * @url GET /viewForm
     */
    public function viewForm($params)
    {
        //coment
        if (!isset($_REQUEST['REQ_UID'])) {
            $_REQUEST['REQ_UID'] = '';
        }

        $criteria = new Criteria();
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::PRO_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::TAS_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::DYN_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_ACTION_FIELD);

        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::APP_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::DEL_INDEX);

        $criteria->addSelectColumn(AbeResponsesPeer::ABE_RES_UID);
        $criteria->addSelectColumn(AbeResponsesPeer::ABE_RES_DATA);


        $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $_REQUEST['REQ_UID']);
        $criteria->addJoin(AbeRequestsPeer::ABE_UID, AbeConfigurationPeer::ABE_UID);
        $criteria->addJoin(AbeResponsesPeer::ABE_REQ_UID, AbeRequestsPeer::ABE_REQ_UID);
        $resultRes = AbeRequestsPeer::doSelectRS($criteria);
        $resultRes->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $resultRes->next();
        $dataRes = Array();
        $message = 'The user has not responded to this request.';

        if ($dataRes = $resultRes->getRow()) {
            $_SESSION['CURRENT_DYN_UID'] = trim($dataRes['DYN_UID']);
            $dynaform = new Form($dataRes['PRO_UID'] . PATH_SEP . trim($dataRes['DYN_UID']), PATH_DYNAFORM, SYS_LANG, false);
            $dynaform->mode = 'view';

            if ($dataRes['ABE_RES_DATA'] != '') {
                $value = unserialize($dataRes['ABE_RES_DATA']);

                if (is_array($value)) {
                    $dynaform->values = $value;

                    foreach ($dynaform->fields as $fieldName => $field) {
                        if ($field->type == 'submit') {
                            unset($dynaform->fields[$fieldName]);
                        }
                    }

                    $message = $dynaform->render(PATH_CORE . 'templates/xmlform.html', $scriptCode);
                } else {
                    $response = $dynaform->render(PATH_CORE . 'templates/xmlform.html', $scriptCode);
                    $field = $dynaform->fields[$dataRes['ABE_ACTION_FIELD']];
                    $message = '<b>Type:   </b>' . $field->type . '<br>';

                    switch ($field->type) {
                        case 'dropdown':
                        case 'radiogroup':
                            $message .=$field->label . ' - ';
                            $message .= $field->options[$value];
                            break;
                        case 'yesno':
                            $message .= '<b>' . $field->label . ' </b>- ';
                            $message .= ($value == 1) ? 'Yes' : 'No';
                            break;
                        case 'checkbox':
                            $message .= '<b>' . $field->label . '</b> - ';
                            $message .= ($value == 'On') ? 'Check' : 'Uncheck';
                            break;
                    }
                }
            }
        }
        return $message;
    }

    /**
     * 
     * @url GET /Templates/:proId
     */
    public function getTemplates($proId)
    {
        $templates = array();
        $path = PATH_DATA_MAILTEMPLATES . $proId . PATH_SEP;

        \G::verifyPath($path, true);

        if (defined('PARTNER_FLAG')) {
            if (!file_exists($path . 'actionsByEmailPartner.html')) {
                @copy(PATH_TPL . 'actionsByEmail' . PATH_SEP . 'actionsByEmailPartner.html', $path . 'actionsByEmail.html');
            }
        } else {
            if (!file_exists($path . 'actionsByEmail.html')) {
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
