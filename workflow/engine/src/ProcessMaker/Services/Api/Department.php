<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * Department Api Controller
 *
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Department extends Api
{
    /**
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url GET
     */
    public function doGetDepartments()
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->getDepartments();
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url GET /:dep_uid/assigned-user
     */
    public function doGetAssignedUser($dep_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->getAssignedUser($dep_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url GET /:dep_uid/available-user
     */
    public function doGetAvailableUser($dep_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->getAvailableUser($dep_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     * @param string $usr_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url PUT /:dep_uid/assign-user/:usr_uid
     */
    public function doPutAssignUser($dep_uid, $usr_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->assignUser($dep_uid, $usr_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     * @param string $usr_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url PUT /:dep_uid/unassign-user/:usr_uid
     */
    public function doPutUnassignUser($dep_uid, $usr_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->unassignUser($dep_uid, $usr_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     * @param string $usr_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url PUT /:dep_uid/set-manager/:usr_uid
     */
    public function doPutSetManager($dep_uid, $usr_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->setManagerUser($dep_uid, $usr_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url GET /:dep_uid
     */
    public function doGetDepartment($dep_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->getDepartment($dep_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param array $request_data
     * @param string $dep_title {@from body} {@min 1}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url POST
     * @status 201
     */
    public function doPost($request_data, $dep_title)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->saveDepartment($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     *
     * @param array $request_data
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url PUT /:dep_uid
     */
    public function doPut($dep_uid, $request_data)
    {
        try {
            $request_data['dep_uid'] = $dep_uid;
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $response = $oDepartment->saveDepartment($request_data, false);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $dep_uid {@min 1}{@max 32}
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     *
     * @url DELETE /:dep_uid
     */
    public function doDelete($dep_uid)
    {
        try {
            $oDepartment = new \ProcessMaker\BusinessModel\Department();
            $oDepartment->deleteDepartment($dep_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

