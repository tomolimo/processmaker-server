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
}

