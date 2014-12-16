<?php
namespace ProcessMaker\Services\Api\Group;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Group\User Api Controller
 *
 * @protected
 */
class User extends Api
{
    /**
     * @url POST /:grp_uid/user
     *
     * @param string $grp_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostUser($grp_uid, $request_data)
    {
        try {
            $groupUser = new \ProcessMaker\BusinessModel\Group\User();
            $groupUser->setFormatFieldNameInUppercase(false);

            $arrayData = $groupUser->create($grp_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:grp_uid/user/:usr_uid
     *
     * @param string $grp_uid {@min 32}{@max 32}
     * @param string $usr_uid {@min 32}{@max 32}
     */
    public function doDeleteUser($grp_uid, $usr_uid)
    {
        try {
            $groupUser = new \ProcessMaker\BusinessModel\Group\User();
            $groupUser->setFormatFieldNameInUppercase(false);

            $groupUser->delete($grp_uid, $usr_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

