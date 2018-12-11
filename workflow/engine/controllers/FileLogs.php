<?php

use ProcessMaker\Core\System;

/**
 * File Logs controller
 * @inherits Controller
 *
 * @access public
 */
class FileLogs extends Controller
{

    // Class properties
    private $urlProxy;
    private $credentials;

    const version = '1.0';

    // Class constructor
    public function __construct()
    {
        global $RBAC;

        if ($RBAC->userCanAccess('PM_SETUP_LOG_FILES') !== 1) {
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
            exit(0);
        }

        $designer = new Designer();
        $this->setCredentials(base64_encode(G::json_encode($designer->getCredentials())));
        $this->setUrlProxy(System::getHttpServerHostnameRequestsFrontEnd() . '/api/' . self::version . '/' . config('system.workspace') . '/');
    }

    /**
     * Return server host
     *
     * @return string
     */
    public function getUrlProxy()
    {
        return $this->urlProxy;
    }

    /**
     * Set server host
     *
     * @param string $urlProxy
     */
    public function setUrlProxy($urlProxy)
    {
        $this->urlProxy = $urlProxy;
    }

    /**
     * Get credential oauth
     *
     * @return string
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Set Credentials
     *
     * @param string $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Render list file logs
     */
    public function fileList()
    {
        try {
            global $RBAC;
            if (isset($_SESSION['__FILE_LOGS_ERROR__'])) {
                $this->setJSVar('__FILE_LOGS_ERROR__', $_SESSION['__FILE_LOGS_ERROR__']);
                unset($_SESSION['__FILE_LOGS_ERROR__']);
            }

            $this->setView('fileLogs/list');

            $c = new Configurations();
            $configPage = $c->getConfiguration('usersList', 'pageSize', null, $RBAC->aUserInfo['USER_INFO']['USR_UID']);

            $config = [];
            $config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;

            $this->setJSVar('urlProxy', $this->getUrlProxy());
            $this->setJSVar('credentials', $this->getCredentials());
            $this->setJSVar('CONFIG', $config);
            $this->includeExtJS('fileLogs/list');
            G::RenderPage('publish', 'extJs');

        } catch (Exception $error) {
            $_SESSION['__FILE_LOGS_ERROR__'] = $error->getMessage();
            die();
        }
    }

}
