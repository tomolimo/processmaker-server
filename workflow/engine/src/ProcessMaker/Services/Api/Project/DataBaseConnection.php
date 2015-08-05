<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\DataBaseConnection Api Controller
 *
 * @author Brayan Pereyra <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class DataBaseConnection extends Api
{
    /**
     * @param string $prj_uid {@min 1} {@max 32}
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     * @return array
     *
     * @url GET /:prj_uid/database-connections
     */
    public function doGetDataBaseConnections($prj_uid)
    {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->getDataBaseConnections($prj_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $prj_uid {@min 1} {@max 32}
     * @param string $dbs_uid {@min 1} {@max 32}
     * @return array
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:prj_uid/database-connection/:dbs_uid
     */
    public function doGetDataBaseConnection($prj_uid, $dbs_uid)
    {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->getDataBaseConnection($prj_uid, $dbs_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $prj_uid {@min 1} {@max 32}
     * @param array $request_data
     *
     * @param string $dbs_type {@from body} {@required true}
     * @param string $dbs_server {@from body} {@required true}
     * @param string $dbs_database_name {@from body} {@required true}
     * @param string $dbs_username {@from body}
     * @param string $dbs_encode {@from body} {@required true}
     * @param string $dbs_password {@from body}
     * @param string $dbs_description {@from body}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /:prj_uid/database-connection/test
     */
    public function doPostTestDataBaseConnection(
        $prj_uid,
        $request_data,
        $dbs_type,
        $dbs_server,
        $dbs_database_name,
        $dbs_username,
        $dbs_encode,
        $dbs_password = '',
        $dbs_description = ''
    ) {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $request_data['pro_uid'] = $prj_uid;
            $response = $oDBConnection->testConnection($request_data, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $prj_uid {@min 1} {@max 32}
     * @param array $request_data
     *
     * @param string $dbs_type {@from body} {@required true}
     * @param string $dbs_server {@from body} {@required true}
     * @param string $dbs_database_name {@from body} {@required true}
     * @param string $dbs_username {@from body}
     * @param string $dbs_encode {@from body} {@required true}
     * @param string $dbs_password {@from body}
     * @param string $dbs_description {@from body}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /:prj_uid/database-connection
     * @status 201
     */
    public function doPostDataBaseConnection(
        $prj_uid,
        $request_data,
        $dbs_type,
        $dbs_server,
        $dbs_database_name,
        $dbs_username,
        $dbs_encode,
        $dbs_password = '',
        $dbs_description = ''
    ) {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->saveDataBaseConnection($prj_uid, $request_data, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $prj_uid {@min 1} {@max 32}
     * @param string $dbs_uid {@min 1} {@max 32}
     * @param array $request_data
     *
     * @param string $dbs_type {@from body} {@required true}
     * @param string $dbs_server {@from body} {@required true}
     * @param string $dbs_database_name {@from body} {@required true}
     * @param string $dbs_username {@from body}
     * @param string $dbs_encode {@from body} {@required true}
     * @param string $dbs_password {@from body}
     * @param string $dbs_description {@from body}
     * @return void
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT /:prj_uid/database-connection/:dbs_uid
     */
    public function doPutDataBaseConnection(
        $prj_uid,
        $dbs_uid,
        $request_data,
        $dbs_type,
        $dbs_server,
        $dbs_database_name,
        $dbs_username,
        $dbs_encode,
        $dbs_password = '',
        $dbs_description = ''
    ) {
        try {
            $request_data['dbs_uid'] = $dbs_uid;
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->saveDataBaseConnection($prj_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $prj_uid {@min 1} {@max 32}
     * @param string $dbs_uid {@min 1} {@max 32}
     * @return void
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url DELETE /:prj_uid/database-connection/:dbs_uid
     */
    public function doDeleteDataBaseConnection($prj_uid, $dbs_uid)
    {
        try {
            $oDBConnection = new \ProcessMaker\BusinessModel\DataBaseConnection();
            $response = $oDBConnection->deleteDataBaseConnection($prj_uid, $dbs_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

