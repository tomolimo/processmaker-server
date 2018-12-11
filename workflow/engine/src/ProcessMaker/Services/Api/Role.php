<?php

namespace ProcessMaker\Services\Api;

use Exception;
use G;
use Luracast\Restler\RestException;
use ProcessMaker\BusinessModel\Role as BmRole;
use ProcessMaker\BusinessModel\User;
use ProcessMaker\Services\Api;
use ProcessMaker\Util\DateTime;

/**
 * Role Api Controller
 *
 * @protected
 */
class Role extends Api
{
    private $role;

    private $arrayFieldIso8601 = [
        'rol_create_date',
        'rol_update_date'
    ];

    /**
     * Role constructor.
     *
     * @throws RestException
     */
    public function __construct()
    {
        try {
            $user = new User();

            $usrUid = $this->getUserId();

            if (!$user->checkPermission($usrUid, 'PM_USERS')) {
                throw new Exception(G::LoadTranslation('ID_USER_NOT_HAVE_PERMISSION', [$usrUid]));
            }

            $this->role = new BmRole();
            $this->role->setFormatFieldNameInUppercase(false);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Load all roles
     *
     * @url GET
     *
     * @param string $filter
     * @param int $start
     * @param int $limit
     *
     * @return mixed
     * @throws RestException
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     */
    public function index($filter = null, $start = null, $limit = null)
    {
        try {
            $response = $this->role->getRoles(['filter' => $filter], null, null, $start, $limit);

            return DateTime::convertUtcToIso8601($response, $this->arrayFieldIso8601);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * load information role
     *
     * @url GET /:rol_uid
     *
     * @param string $rol_uid {@min 32}{@max 32}
     *
     * @return mixed
     * @throws RestException
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     */
    public function doGet($rol_uid)
    {
        try {
            $response = $this->role->getRole($rol_uid);

            return DateTime::convertUtcToIso8601($response, $this->arrayFieldIso8601);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Create rol
     *
     * @url POST
     * @status 201
     *
     * @param array $request_data
     *
     * @return array
     * @throws RestException
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     */
    public function doPost(array $request_data)
    {
        try {
            return $this->role->create($request_data);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Update a role.
     *
     * @url PUT /:rol_uid
     *
     * @param string $rol_uid {@min 32}{@max 32}
     * @param array $request_data
     *
     * @throws RestException
     *
     * @access protected
     * @class AccessControl {@permission PM_USERS}
     */
    public function doPut($rol_uid, array $request_data)
    {
        try {
            $this->role->update($rol_uid, $request_data);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Delete role
     *
     * @url DELETE /:rol_uid
     *
     * @param string $rol_uid {@min 32}{@max 32}
     *
     * @throws RestException
     *
     * @access protected
     * @class  AccessControl {@permission PM_USERS}
     */
    public function doDelete($rol_uid)
    {
        try {
            $this->role->delete($rol_uid);
        } catch (Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}

