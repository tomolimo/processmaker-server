<?php

namespace ProcessMaker\Services\Api;

use Exception;
use G;
use ProcessMaker\BusinessModel\Files\FilesLogs;
use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Log Files Api Controller
 *
 * @protected
 */
class FileLogs extends Api
{
    /**
     * Get the list of the log files
     *
     * @url GET /list
     *
     * @param int $start {@from path}
     * @param int $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $filter {@from path}
     *
     * @return array
     * @throws RestException
     *
     * @access protected
     * @class AccessControl {@permission PM_SETUP_LOG_FILES}
     */
    public function doGetListFileLogs(
        $start = 0,
        $limit = 0,
        $sort = 'fileCreated',
        $dir = 'DESC',
        $filter = ''
    )
    {
        try {
            $file = new FilesLogs();
            return $file->getAllFiles($filter, $sort, $start, $limit, $dir);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Download file.
     *
     * @url POST /download
     *
     * @param array $request_data name of the files
     *
     * @throws RestException
     *
     * @access protected
     * @class AccessControl {@permission PM_SETUP_LOG_FILES}
     */
    public function doPostDownload($request_data)
    {
        try {
            $file = new FilesLogs();
            $file->download(G::json_decode($request_data['files']));
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

}
