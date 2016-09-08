<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * GmailIntegration Api Controller
 *
 *
 * @protected
 */
class GmailIntegration extends Api
{
    /**
     * Get User by usr_gmail
     * 
     * @param string $usr_gmail {@from path}
     *
     *
     * @url GET /userexist/:usr_gmail
     *
     */
    public function doGetUserbyEmail($usr_gmail)
    {
        try {
            $Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
            $response = $Pmgmail->getUserByEmail($usr_gmail);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
 
     /**
     * Get Application by app_uid
     * 
     * @param string $app_uid {@from path}
     *
     *
     * @url GET /application/:app_uid
     *
     */
    public function doGetApplication($app_uid)
    {
        try {
            $Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
            $response = $Pmgmail->getDraftApp($app_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
    
    /**
     * Send Email
     *
     * @param string $app_uid {@from path}
     * @param string $mail {@from path}
     * @param string $index {@from path}
     *
     *
     * @url POST /sendEmail/:app_uid/to/:mail/index/:index
     *
     */
    public function doPostSendMail($app_uid, $mail, $index)
    {
    	try {
    		$Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
    		$response = $Pmgmail->sendEmail($app_uid, $mail, $index);
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }   
    
    /**
     * Get if the license has the gmail integration feature
     *
     *
     * @url GET /verifyGmailfeature
     *
     */
    public function doGetVerifyGmailFeature()
    {
    	try {
    		$Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
    		$response = $Pmgmail->hasGmailFeature();
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }


    /**
     * Get the default 'email from account' that is used to send emails in the server email in PM
     *
     *
     * @url GET /current-email-account
     *
     */
    public function doGetEmailAccount()
    {
    	try {
    		$Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
    		$response = $Pmgmail->emailAccount();
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }
    
    /**
     * End Point to delete Labels in an uninstalation of the extension
     *
     * @param string $mail {@from path}
     *
     *
     * @url POST /deleteLabels/:mail
     *
     */
    public function doPostDeleteLabels($mail)
    {
    	try {
    		$Pmgmail = new \ProcessMaker\BusinessModel\Pmgmail();
    		$response = $Pmgmail->deleteLabels($mail);
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }

}


