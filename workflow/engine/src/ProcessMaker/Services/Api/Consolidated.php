<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * Consolidated Api Controller
 *
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Consolidated extends Api
{
    /**
     * Get Consolidated
     *
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:tas_uid
     */
    public function doGet($tas_uid)
    {
        try {
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            return $consolidated->get($tas_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Post Consolidated
     *
     * @param array $request_data
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     */
    public function doPost($request_data)
    {
        try {
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            return $consolidated->post($request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get for Generate Consolidated
     *
     * @param string $pro_uid {@min 1} {@max 32}
     * @param string $tas_uid {@min 1} {@max 32}
     * @param string $dyn_uid {@min 1} {@max 32}
     * @return string
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /generate/:pro_uid/:tas_uid/:dyn_uid
     */
    public function doGetGenerateConsolidated($pro_uid, $tas_uid, $dyn_uid)
    {
        try {
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            return $consolidated->getDataGenerate($pro_uid, $tas_uid, $dyn_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get Cases Consolidated
     *
     * @param string $tas_uid {@min 1} {@max 32}
     * @param string $dyn_uid {@min 1} {@max 32}
     * @param string $pro_uid {@min 1} {@max 32}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $search {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /cases/:tas_uid/:dyn_uid/:pro_uid
     */
    public function doGetCasesConsolidated($tas_uid, $dyn_uid, $pro_uid, $start = '', $limit = '', $search = '')
    {
        try {
            $usr_uid = $this->getUserId();
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            $consolidated->getDataGrid($tas_uid, $dyn_uid, $pro_uid, $usr_uid, $start, $limit, $search);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get Cases Consolidated
     *
     * @param string $tas_uid {@min 1} {@max 32}
     * @param string $dyn_uid {@min 1} {@max 32}
     * @param string $pro_uid {@min 1} {@max 32}
     * @param array $request_data
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT /cases/:tas_uid/:dyn_uid/:pro_uid
     */
    public function doPutCasesConsolidated($tas_uid, $dyn_uid, $pro_uid, $request_data)
    {
        try {
            $usr_uid = $this->getUserId();
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            return $consolidated->putDataGrid($tas_uid, $dyn_uid, $pro_uid, $usr_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Post Derivate
     *
     * @param string $app_uid {@min 1} {@max 32}
     * @param string $app_number
     * @param string $del_index
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /derivate/:app_uid/:app_number/:del_index
     */
    public function doPostDerivate($app_uid, $app_number, $del_index)
    {
        try {
            $usr_uid = $this->getUserId();
            $consolidated = new \ProcessMaker\BusinessModel\Consolidated();
            return $consolidated->postDerivate($app_uid, $app_number, $del_index, $usr_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}
