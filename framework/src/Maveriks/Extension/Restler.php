<?php
namespace Maveriks\Extension;

use Luracast\Restler\Defaults;
use Luracast\Restler\Format\UrlEncodedFormat;
use ProcessMaker\Services\Api;
use Luracast\Restler\RestException;

/**
 * Class Restler
 * Extension Restler class to implement in ProcessMaker
 *
 * @package Maveriks\Extension
 */
class Restler extends \Luracast\Restler\Restler
{
    public $flagMultipart = false;
    public $responseMultipart = array();
    public $inputExecute = '';

    protected $workspace;

    public function __construct($productionMode = false, $refreshCache = false)
    {
        parent::__construct($productionMode, $refreshCache);
    }

    /**
     * This method to set the value flag Multipart
     *
     * @param boolean $multipart. flag Multipart
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function setFlagMultipart($multipart = false)
    {
        $this->flagMultipart = ($multipart === true) ? true : false;
    }

    /**
     * This method to print or save the api response
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    protected function respond()
    {
        $this->dispatch('respond');
        //handle throttling
        if (Defaults::$throttle) {
            $elapsed = time() - $this->startTime;
            if (Defaults::$throttle / 1e3 > $elapsed) {
                usleep(1e6 * (Defaults::$throttle / 1e3 - $elapsed));
            }
        }
        if ($this->flagMultipart === true) {
            $responseTemp['status'] = $this->responseCode;
            $responseTemp['response'] = json_decode($this->responseData);
            $this->responseMultipart = $responseTemp;
        } else {
            echo $this->responseData;
        }
        $this->dispatch('complete');
    }

    /**
     * This method to set request data
     *
     * @param boolean $includeQueryParameters.
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function getRequestData($includeQueryParameters = true)
    {
        $get = UrlEncodedFormat::decoderTypeFix($_GET);
        if ($this->requestMethod == 'PUT'
            || $this->requestMethod == 'PATCH'
            || $this->requestMethod == 'POST'
        ) {
            if (!empty($this->requestData)) {
                return $includeQueryParameters
                    ? $this->requestData + $get
                    : $this->requestData;
            }

            if ($this->flagMultipart === false) {
                $r = file_get_contents('php://input');
                if (is_null($r)) {
                    return array(); //no body
                }
            } else {
                $r = $this->inputExecute;
            }

            $r = $this->requestFormat->decode($r);
            $r = is_array($r)
                ? array_merge($r, array(Defaults::$fullRequestDataName => $r))
                : array(Defaults::$fullRequestDataName => $r);
            return $includeQueryParameters
                ? $r + $get
                : $r;
        }
        return $includeQueryParameters ? $get : array(); //no body
    }

    /**
     * This method call the function message
     *
     * @param RestException $e. Exception the error
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function setMessage(RestException $e)
    {
        $this->message($e);
    }

    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }

    public function getWorkspace()
    {
        return $this->workspace;
    }
}