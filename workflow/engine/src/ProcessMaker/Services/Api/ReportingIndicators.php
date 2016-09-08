<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;
use \ProcessMaker\Util\DateTime;


/**
 * Calendar Api Controller
 *
 * @protected
 */
class ReportingIndicators extends Api
{
//   /**
//     * Returns the aggregate Efficiency of a process or set of precesses
//     *
//     * @param string $process_list {@from path}
//     * @param string $init_date {@from path}
//     * @param string $end_date {@from path}
//     * @return array
//     *
//     * @url GET /process-efficiency-index
//     */
//
//    public function doGetProcessEfficiencyIndex($process_list, $init_date, $end_date)
//    {
//        try {
//            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
//            $listArray = (strlen($process_list) > 1)
//                                ? $listArray = explode(',', $process_list)
//                                : null;
//
//            $response = $indicatorsObj->getProcessEfficiencyIndex($listArray,
//                            new \DateTime($init_date),
//                            new \DateTime($end_date));
//
//            return $response;
//        } catch (\Exception $e) {
//            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
//        }
//    }


    /**
     * @param string $date
     *
     * @return \DateTime
     */
    private function convertDateTimeToUtc ($date)
    {
        if ($date == "") {
            $date = new \DateTime("now", new \DateTimeZone('UTC'));
        } else {
            $dateTimezone = new \DateTime($date, new \DateTimeZone('UTC'));
            $toUtcTime = DateTime::convertDataToUtc($dateTimezone);
            if (!(isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__'])) {
                $date = $toUtcTime;
            }
            else {
                $date = (new \DateTime($toUtcTime->date));
            }
        }

        return $date;
    }

    /**
     * Lists tasks of a process and it's statistics (efficiency, average times, etc.)
     *
     * @param string $process_list {@from path}
     * @param string $init_date {@from path}
     * @param string $end_date {@from path}
     * @param string $language {@from path}
     * @return array
     *
     * @url GET /process-tasks
     */
    public function doGetProcessTasksInfo($process_list, $init_date, $end_date, $language)
    {

        if ($process_list == null || strlen($process_list) <= 1)
            throw new InvalidArgumentException ('process_list must have at least a value', 0);

        try {
            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $listArray =  $listArray = explode(',', $process_list);
            $response = $indicatorsObj->getPeiTasksStatistics(
                $listArray,
                $this->convertDateTimeToUtc($init_date),
                $this->convertDateTimeToUtc($end_date),
                $language);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

     /**
     * Returns the total of Cases with Completed time with the selected periodicity
     *
     * @param string $indicator_uid {@from path}
     * @param string $measure_date {@from path}
     * @param string $compare_date {@from path}
     * @param string $language {@from path}
     * @return array
     *
     * @url GET /process-efficiency-data
     */
    public function doGetProcessEficciencyData($indicator_uid, $compare_date, $measure_date, $language)
    {
        try {

            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getPeiCompleteData(
                $indicator_uid,
                $this->convertDateTimeToUtc($compare_date),
                $this->convertDateTimeToUtc($measure_date),
                $language);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
    /**
    * Returns the total of Cases with Completed time with the selected periodicity
    *
    * @param string $indicator_uid {@from path}
    * @param string $measure_date {@from path}
    * @param string $compare_date {@from path}
    * @param string $language {@from path}
    * @return array
    *
    * @url GET /employee-efficiency-data
    */
    public function doGetEmployeeEficciencyData($indicator_uid, $compare_date, $measure_date, $language)
    {
        try {
            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getUeiCompleteData (
                $indicator_uid,
                $this->convertDateTimeToUtc($compare_date),
                $this->convertDateTimeToUtc($measure_date),
                $language );
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Returns the total of Cases with Completed time with the selected periodicity
     *
     * @param string $group_uid {@from path}
     * @param string $init_date {@from path}
     * @param string $end_date {@from path}
     * @param string $language {@from path}
     * @return array
     *
     * @url GET /group-employee-data
     */

    public function doGetGroupEmployeesData($group_uid, $init_date, $end_date, $language)
    {
        try {
            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getUeiGroupsStatistics(
                $group_uid,
                $this->convertDateTimeToUtc($init_date),
                $this->convertDateTimeToUtc($end_date),
                $language);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Returns the total of Cases with Completed time with the selected periodicity
     *
     * @param string $indicator_uid {@from path}
     * @param string $measure_date {@from path}
     * @param string $compare_date {@from path}
     * @param string $language {@from path}
     * @return array
     *
     * @url GET /general-indicator-data
     */
    public function doGetGeneralIndicatorData ($indicator_uid, $init_date, $end_date, $language)
    {
        try {
            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getGeneralIndicatorStatistics(
                $indicator_uid,
                $this->convertDateTimeToUtc($init_date),
                $this->convertDateTimeToUtc($end_date),
                $language);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get list Status indicator
     *
     * @return array
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /status-indicator
     */
    public function doGetStatusIndicator() {
        try {
            $options['usrUid'] = $this->getUserId();

            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getStatusIndicator($options);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * Get historic data of an indicator
     *
     * @return array
     *
     * @author Dante Loayza
     * @copyright Colosa - Bolivia
     *
     * @url GET /indicator-historic-data
     */
    public function doGetHistoricDataFromIndicator($indicator_uid, $init_date, $end_date, $periodicity, $language) {
        try {
            $indicatorsObj = new \ProcessMaker\BusinessModel\ReportingIndicators();
            $response = $indicatorsObj->getHistoricData (
                $indicator_uid,
                $this->convertDateTimeToUtc($init_date),
                $this->convertDateTimeToUtc($end_date),
                $periodicity,
                $language);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

}




