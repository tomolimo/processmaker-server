<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * Dashboard Api Controller
 *
 * @author Jenny Murillo <jennylee@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Dashboard extends Api
{
    /**
     * Get dashboards UID by user_uid
     * 
     * @param string $usr_uid {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /owner/:usr_uid
     *
     */
    public function doGetDashboardsUidByUser($usr_uid)
    {
        try {
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getDashboardsUidByUser($usr_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
    
    /**
     * Get dashboards data by user_uid
     *
     * @param string $usr_uid {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /ownerData/:usr_uid
     *
     */
    public function doGetDashboardsDataByUser($usr_uid)
    {
        try {
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getDashboardDataByUser($usr_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
    
    /**
     * Get users by dashboards uid
     *
     * @param string $das_uid {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /users/:das_uid
     *
     */
    public function doGetDashboardUsers($das_uid)
    {
        try {
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getUsersOfDashboard($das_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get dashboards data by uid
     *
     * @param string $das_uid {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:das_uid
     *
     */
    public function doGetDashboardData($das_uid)
    {
        try {
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getDashboard($das_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get dashboards indicator by dasInd_uid
     *
     * @param string $dasInd_uid {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /indicator/:dasInd_uid
     *
     */
    public function doGetDashboardIndicator($dasInd_uid)
    {
        try {
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getIndicator($dasInd_uid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get dashboards indicators by dashboardUid
     *
     * @param string $das_uid {@from path}
     * @param string $dateIni {@from path}
     * @param string $dateFin {@from path}
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:das_uid/indicator
     *
     */
    public function doGetIndicatorsbyDasUid($das_uid, $dateIni="", $dateFin="")
    {
        try {
        	$dateIni = ($dateIni=="") ? date("Y/m/d") : $dateIni;
        	$dateFin = ($dateFin=="") ? date("Y/m/d") : $dateFin;

        	$usrUid = $this->getUserId();
            $Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $Dashboard->getIndicatorsByDasUid($das_uid, $dateIni, $dateFin, $usrUid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get list Dashboards
     *
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $search {@from path}
     * @return array
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET
     */
    public function doGetListDashboards(
        $start = 0,
        $limit = 0,
        $sort = 'DASHBOARD.DAS_TITLE',
        $dir = 'DESC',
        $search = ''
    ) {
        try {
            $options['start'] = $start;
            $options['limit'] = $limit;
            $options['sort'] = $sort;
            $options['dir'] = $dir;
            $options['search'] = $search;
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $dashboard->getListDashboards($options);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get Owners by das_uid
     *
     * @param string $das_uid {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $search {@from path}
     *
     * @return array
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:das_uid/owners
     *
     */
    public function doGetOwnersByDasUid(
        $das_uid, 
        $start = 0,
        $limit = 0,
        $search = '')
    {
        try {
            $options['das_uid'] = $das_uid;
            $options['start'] = $start;
            $options['limit'] = $limit;
            $options['search'] = $search;
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $dashboard->getOwnerByDasUid($options);
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
    public function doPostDashboard($request_data)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->createDashboard($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Put dashboards configuration
     *
     * @param array $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT 
     *
     */
    public function doPutDashboard($request_data)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->createDashboard($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:das_uid
     *
     * @param string $das_uid  {@min 32}{@max 32}
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function doDeleteDashboard($das_uid)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->deletedashboard($das_uid, $this->getUserId());
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param array $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /owner
     *
     * @status 201
     */
    public function doPostOwner($request_data)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->createOwner($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:das_uid/owner/:owner_uid
     *
     * @param string $das_uid  {@min 32}{@max 32}
     * @param string $owner_uid  {@min 32}{@max 32}
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function doDeleteDashboardOwner($das_uid, $owner_uid)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $dashboard->deleteDashboardOwner($das_uid, $owner_uid, $this->getUserId());
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param array $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /indicator
     *
     * @status 201
     */
    public function doPostIndicator($request_data)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->createIndicator($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Put Indicator
     *
     * @param array $request_data
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT /indicator
     *
     */
    public function doPutIndicator($request_data)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $request_data['USR_UID'] = $this->getUserId();
            $response = $dashboard->createIndicator($request_data);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /indicator/:ind_uid
     *
     * @param string $ind_uid  {@min 32}{@max 32}
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function doDeleteIndicator($ind_uid)
    {
        try {
            $dashboard = new \ProcessMaker\BusinessModel\Dashboard();
            $response = $dashboard->delete($ind_uid, $this->getUserId());
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Post dashboards configuration by userUid
     *
     * @param array $request_data
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /config/
     *
     */
    public function doPostDashboardConfigByUsrUid($request_data)
    {
    	try {
    		$usrUid = $this->getUserId();
    
    		$ConfigDashboards = new \ProcessMaker\BusinessModel\Dashboard();
    		$response = $ConfigDashboards->postConfigByUsr($request_data, $usrUid);
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }
    
    /**
     * Get dashboards configuration by usr_uid
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /config/
     *
     */
    public function doGetDashboardConfigActualUsr()
    {
    	try {
    		$usrUid = $this->getUserId();
    
    		$Dashboard = new \ProcessMaker\BusinessModel\Dashboard();
    		$response = $Dashboard->getConfig($usrUid);
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }
    
    /**
     * Put dashboards configuration by usr_uid
     *
     * @param array $request_data
     *
     * @author Jenny Murillo <jennylee@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT /config
     *
     */
    public function doPutDashboardConfigByUsrUid($request_data)
    {
    	try {
    		$usrUid = $this->getUserId();
    
    		$ConfigDashboards = new \ProcessMaker\BusinessModel\Dashboard();
    		$response = $ConfigDashboards->putConfigByUsr($request_data, $usrUid);
    		return $response;
    	} catch (\Exception $e) {
    		throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
    	}
    }
    
}

