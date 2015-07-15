<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * Catalog Api Controller
 *
 * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Catalog extends Api
{
    /**
     * Get Catalog by cat_type
     * 
     * @param string $cat_type {@from path}
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:cat_type
     *
     */
    public function doGetCatalogByType($cat_type)
    {
        try {
            $Catalog = new \ProcessMaker\BusinessModel\Catalog();
            $response = $Catalog->getCatalogByType($cat_type);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST
     *
     * @param array $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @status 201
     */
    public function doPost($request_data)
    {
        try {
            $catalog = new \ProcessMaker\BusinessModel\Catalog();
            $arrayData = $catalog->create($request_data);

            $response = $arrayData;

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url PUT /:cat_uid/:cat_type
     *
     * @param string $cat_uid  {@min 32}{@max 32}
     * @param string $cat_type      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function doPut($cat_uid, $cat_type, $request_data)
    {
        try {
            $catalog = new \ProcessMaker\BusinessModel\Catalog();

            $arrayData = $catalog->update($cat_uid, $cat_type, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:cat_uid/:cat_type
     *
     * @param string $cat_uid  {@min 32}{@max 32}
     * @param string $cat_type      {@min 32}{@max 32}
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function doDelete($cat_uid, $cat_type)
    {
        try {
            $catalog = new \ProcessMaker\BusinessModel\Catalog();

            $arrayData = $catalog->delete($cat_uid, $cat_type);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

