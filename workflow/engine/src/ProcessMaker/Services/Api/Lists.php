<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;


/**
 * Cases Api Controller
 *
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Lists extends Api
{
    /**
     * Get list Inbox
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET
     * @url GET /inbox
     */
    public function doGetListInbox(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = '',
        $action = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $dataList['action']   = $action;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('inbox', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Inbox
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /inbox/total
     * @url GET /total
     */
    public function doGetCountInbox(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('inbox', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get list Participated Last
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /participated
     * @url GET /participated-last
     */
    public function doGetListParticipatedLast(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('participated_last', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Participated Last
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /participated/total
     * @url GET /participated-last/total
     */
    public function doGetCountParticipatedLast(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('participated_last', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get list Participated History
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /participated-history
     */
    public function doGetListParticipatedHistory(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('participated_history', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Participated History
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /participated-history/total
     */
    public function doGetCountParticipatedHistory(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('participated_history', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }




    /**
     * Get list Paused
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /paused
     */
    public function doGetListPaused(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_PAUSED_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('paused', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Paused
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /paused/total
     */
    public function doGetCountPaused(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('paused', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }



    /**
     * Get list Canceled
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /canceled
     */
    public function doGetListCanceled(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_CANCELED_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('canceled', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Canceled
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /canceled/total
     */
    public function doGetCountCanceled(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('canceled', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }


    /**
     * Get List Completed
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /completed
     */
    public function doGetListCompleted(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('completed', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Participated History
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /completed/total
     */
    public function doGetCountCompleted(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('completed', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }


    /**
     * Get List Completed
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /my-inbox
     */
    public function doGetListMyInbox(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('completed', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Participated History
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /my-inbox/total
     */
    public function doGetCountListMyInbox(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('completed', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get list Unassigned
     *
     * @param string $count {@from path}
     * @param string $paged {@from path}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /unassigned
     */
    public function doGetListUnassigned(
        $count = true,
        $paged = true,
        $start = 0,
        $limit = 0,
        $sort  = 'APP_UPDATE_DATE',
        $dir   = 'DESC',
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = '',
        $usr_uid = ''
    ) {
        try {
            $dataList['userId'] = (empty($usr_uid)) ? $this->getUserId() : $usr_uid;
            $dataList['paged']  = $paged;
            $dataList['count']  = $count;

            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('unassigned', $dataList);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get count list Unassigned
     *
     * @param string $category {@from path}
     * @param string $process {@from path}
     * @param string $search {@from path}
     * @param string $filter {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /unassigned/total
     */
    public function doGetCountUnassigned(
        $category = '',
        $process = '',
        $search = '',
        $filter = '',
        $date_from = '',
        $date_to = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();

            $dataList['category'] = $category;
            $dataList['process']  = $process;
            $dataList['search']   = $search;
            $dataList['filter']   = $filter;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo']   = $date_to;

            $lists = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getList('participated_history', $dataList, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

