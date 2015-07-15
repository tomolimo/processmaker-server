<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Pmtable Api Controller
 *
 * @protected
 */
class System extends Api
{
    /**
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /db-engines
     */
    public function doGetDataBaseEngines()
    {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->getDbEngines();
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count for all lists
     *
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /counters-lists
     */
    public function doGetCountersLists()
    {
        try {
            $userId   = $this->getUserId();
            $lists    = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getCounters($userId);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @return array
     *
     * @author Gustavo Cruz <gustavo.cruz@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /enabled-features
     */
    public function doGetEnabledFeatures()
    {
        try {
            $enabledFeatures = array();
            /*----------------------------------********---------------------------------*/
            $keys = array ('zLhSk5TeEQrNFI2RXFEVktyUGpnczV1WEJNWVp6cjYxbTU3R29mVXVZNWhZQT0=');
            foreach ($keys as $key) {
                if (\PMLicensedFeatures
                    ::getSingleton()
                    ->verifyfeature($key)) {
                    $enabledFeatures[] = $key;
                }
            }
            /*----------------------------------********---------------------------------*/
            return $enabledFeatures;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}
