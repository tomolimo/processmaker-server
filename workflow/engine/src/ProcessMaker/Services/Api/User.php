<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * User Api Controller
 *
 * @protected
 */
class User extends Api
{
    /**
     * @url GET
     * @param string $filter
     * @param int $start
     * @param int $limit
     */
    public function doGetUsers($filter = '', $start = null, $limit = null)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();
            $response = $user->getUsers($filter, $start, $limit);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /:usr_uid
     *
     * @param string $usr_uid {@min 32}{@max 32}
     */
    public function doGetUser($usr_uid)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();
            $response = $user->getUser($usr_uid);
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
     * @status 201
     */
    public function doPostUser($request_data)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();
            $arrayData = $user->create($request_data);
            $response = $arrayData;
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url PUT /:usr_uid
     *
     * @param string $usr_uid      {@min 32}{@max 32}
     * @param array  $request_data
     */
    public function doPutUser($usr_uid, $request_data)
    {
        try {
            $userLoggedUid = $this->getUserId();
            $user = new \ProcessMaker\BusinessModel\User();
            $arrayData = $user->update($usr_uid, $request_data, $userLoggedUid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:usr_uid
     *
     * @param string $usr_uid {@min 32}{@max 32}
     */
    public function doDeleteUser($usr_uid)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();
            $user->delete($usr_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $usr_uid {@min 32} {@max 32}
     *
     * @url POST /:usr_uid/image-upload
     */
    public function doPostUserImageUpload($usr_uid)
    {
        try {
            $user = new \ProcessMaker\BusinessModel\User();
            $user->uploadImage($usr_uid);
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}
