<?php
namespace ProcessMaker\Services\Api\Role;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Role\Permission Api Controller
 *
 * @protected
 */
class Permission extends Api
{
    private $rolePermission;

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            $this->rolePermission = new \ProcessMaker\BusinessModel\Role\Permission();

            $this->rolePermission->setFormatFieldNameInUppercase(false);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:rol_uid/permissions
     * @url GET /:rol_uid/available-permissions
     *
     * @param string $rol_uid {@min 32}{@max 32}
     */
    public function doGetPermissions($rol_uid, $filter = null, $start = null, $limit = null)
    {
        try {
            $response = $this->rolePermission->getPermissions($rol_uid, (preg_match("/^.*\/permissions$/", $this->restler->url))? "PERMISSIONS" : "AVAILABLE-PERMISSIONS", array("filter" => $filter), null, null, $start, $limit);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url POST /:rol_uid/permission
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     *
     * @param string $rol_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostPermission($rol_uid, array $request_data)
    {
        try {
            $arrayData = $this->rolePermission->create($rol_uid, $request_data);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:rol_uid/permission/:per_uid
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     *
     * @param string $rol_uid {@min 32}{@max 32}
     * @param string $per_uid {@min 32}{@max 32}
     *
     */
    public function doDeletePermission($rol_uid, $per_uid)
    {
        try {
            $this->rolePermission->delete($rol_uid, $per_uid);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}

