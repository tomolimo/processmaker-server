<?php
namespace ProcessMaker\BusinessModel;

use \G;

class ReportingIndicators
{
//    /**et

    /**
     * Lists tasks of a process and it's statistics (efficiency, average times, etc.)
     *
     * @param array $processList array with the list of processes to filter the results.
     * @param DateTime $initDate date from the index will be calculated
     * @param DateTime $endDate date until the index will be calculated
     * @param string $language language for the names (en, es, etc.)
     *
     * return decimal  value
     */
    public function getPeiCompleteData($indicatorUid,  $compareDate, $measureDate, $language)
    {
        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $processes = $calculator->peiProcesses($indicatorUid, $measureDate, $measureDate, $language);
        $arr = $calculator->indicatorData($indicatorUid);
        $indicator = $arr[0];
		$processesId = $indicator['DAS_UID_PROCESS'];
		$peiValue = current(reset($calculator-> peiHistoric($processesId, $measureDate, $measureDate, \ReportingPeriodicityEnum::NONE)));
		$peiCost = current(reset($calculator->peiCostHistoric($processesId, $measureDate, $measureDate, \ReportingPeriodicityEnum::NONE)));
		$peiCompare = current(reset($calculator->peiHistoric($processesId, $compareDate, $compareDate, \ReportingPeriodicityEnum::NONE)));

		$retval = array(
						"id" => $indicatorUid,
						"efficiencyIndex" => $peiValue,
						"efficiencyIndexToCompare" => $peiCompare,
						"efficiencyVariation" => ($peiValue-$peiCompare),
						"inefficiencyCost" => $peiCost,
						"data"=>$processes);
        return $retval;
    }

    /**
     * Lists tasks of a employee and it's statistics (efficiency, average times, etc.)
     *
     * @param array $employeeList array with the list of employeees to filter the results.
     * @param DateTime $initDate date from the index will be calculated
     * @param DateTime $endDate date until the index will be calculated
     * @param string $language language for the names (en, es, etc.)
     *
     * return decimal  value
     */
    public function getUeiCompleteData($indicatorUid, $compareDate, $measureDate,$language)
    {
        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $groups = $calculator->ueiUserGroups($indicatorUid, $measureDate, $measureDate, $language);

        //TODO think what if each indicators has a group or user subset assigned. Now are all
        $ueiValue = current(reset($calculator->ueiHistoric(null, $measureDate, $measureDate, \ReportingPeriodicityEnum::NONE)));
        $arrCost = $calculator->ueiUserGroups($indicatorUid, $measureDate, $measureDate, $language);

		$ueiCost = current(reset($calculator->ueiCostHistoric(null, $measureDate, $measureDate, \ReportingPeriodicityEnum::NONE)));
        $ueiCompare = current(reset($calculator->ueiHistoric(null, $compareDate, $compareDate, \ReportingPeriodicityEnum::NONE)));

		$retval = array(
						"id" => $indicatorUid,
						"efficiencyIndex" => $ueiValue,
                        "efficiencyVariation" => ($ueiValue-$ueiCompare),
                        "inefficiencyCost" => $ueiCost,
                        "efficiencyIndexToCompare" => $ueiCompare,
                        "data"=>$groups);
        return $retval;
    }

    /**
     * Lists tasks of a employee and it's statistics (efficiency, average times, etc.)
     *
     * @param array $employeeList array with the list of employeees to filter the results.
     * @param DateTime $initDate date from the index will be calculated
     * @param DateTime $endDate date until the index will be calculated
     * @param string $language language for the names (en, es, etc.)
     *
     * return decimal  value
     */
    public function getUeiGroupsStatistics($groupId, $initDate, $endDate, $language)
    {
        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $retval = $calculator->groupEmployeesData($groupId, $initDate, $endDate, $language);
        return $retval;
    }

    /**
     * Lists tasks of a process and it's statistics (efficiency, average times, etc.)
     *
     * @param array $processList array with the list of processes to filter the results.
     * @param DateTime $initDate date from the index will be calculated
     * @param DateTime $endDate date until the index will be calculated
     * @param string $language language for the names (en, es, etc.)
     *
     * return decimal  value
     */
    public function getPeiTasksStatistics($processList, $initDate, $endDate, $language)
    {
        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $retval = $calculator->peiTasks($processList, $initDate, $endDate, $language);
        return $retval;
    }

//    /**
//     * Lists tasks of a employee and it's statistics (efficiency, average times, etc.)
//     *
//     * @param array $employeeList array with the list of employeees to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getEmployeeTasksInfoList($employeeList, $initDate, $endDate, $language)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->employeeTasksInfoList($employeeList, $initDate, $endDate, $language);
//        return $retval;
//    }
//
//    /**
//     * Returns the percent of Cases with Overdue time
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentOverdueCasesByProcess($processList, $initDate, $endDate)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->percentOverdueCasesByProcess($processList, $initDate, $endDate);
//        return $retval;
//    }
//
//    /**
//     * Returns the percent of Cases with Overdue by period (month, semester, etc.)
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentOverdueCasesByProcessHistory($processList, $initDate, $endDate, $periodicity)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->percentOverdueCasesByProcessList($processList, $initDate, $endDate, \ReportingPeriodicityEnum::fromValue($periodicity));
//        return $retval;
//    }
//
//    /**
//     * Returns the number of new Cases
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentNewCasesByProcess($processList, $initDate, $endDate)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->totalNewCasesByProcess($processList, $initDate, $endDate);
//        return $retval;
//    }
//
//    /**
//     * Returns the total of new Cases historically
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentNewCasesByProcessHistory($processList, $initDate, $endDate, $periodicity)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->totalNewCasesByProcessList($processList, $initDate, $endDate, \ReportingPeriodicityEnum::fromValue($periodicity));
//        return $retval;
//    }
//
//
//
//
//    /**
//     * Returns the number of completed Cases
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentCompletedCasesByProcess($processList, $initDate, $endDate)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->totalCompletedCasesByProcess($processList, $initDate, $endDate);
//        return $retval;
//    }
//
//    /**
//     * Returns the total of completed Cases historically
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getPercentCompletedCasesByProcessHistory($processList, $initDate, $endDate, $periodicity)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//        $retval = $calculator->totalCompletedCasesByProcessList($processList, $initDate, $endDate, \ReportingPeriodicityEnum::fromValue($periodicity));
//        return $retval;
//    }

//    /**
//     *
//     *
//     * @param array $processList array with the list of processes to filter the results.
//     * @param DateTime $initDate date from the index will be calculated
//     * @param DateTime $endDate date until the index will be calculated
//     *
//     * return decimal  value
//     */
//    public function getProcessEfficiencyIndexData($processId, $initDate, $endDate)
//    {
//        G::loadClass('IndicatorsCalculator');
//        $calculator = new \IndicatorsCalculator();
//		$indexValue = $calculator->processEfficiencyIndex ($processId, $initDate, $endDate);
//		$costValue = $calculator->processEfficiencyCost ($processId, $initDate, $endDate);
//        $retval = $calculator->totalCompletedCasesByProcessList($processId, $initDate, $endDate);
//        return $retval;
//    }
    /**
     * Lists tasks of a process and it's statistics (efficiency, average times, etc.)
     *
     * @param $indicatorId
     * @param DateTime $initDate date from the index will be calculated
     * @param DateTime $endDate date until the index will be calculated
     * @param string $language language for the names (en, es, etc.)
     *
     * return decimal  value
     * @return array
     */
    public function getGeneralIndicatorStatistics($indicatorId, $initDate, $endDate, $periodicity)
    {
        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $arr = $calculator->generalIndicatorData($indicatorId, $initDate, $endDate, \ReportingPeriodicityEnum::NONE);
        $value = $arr[0]['value'];
        $dataList1 = $calculator->
			generalIndicatorData($indicatorId, 
				$initDate, $endDate, 
				\ReportingPeriodicityEnum::fromValue($arr[0]['frequency1Type']));

        $dataList2 = $calculator->
			generalIndicatorData($indicatorId, 
				$initDate, $endDate, 
				\ReportingPeriodicityEnum::fromValue($arr[0]['frequency2Type']));

        $returnValue = array("index" => $value,
			"graph1XLabel"=>$arr[0]['graph1XLabel'],  
			"graph1YLabel"=>$arr[0]['graph1YLabel'], 
			"graph2XLabel"=>$arr[0]['graph2XLabel'],  
			"graph2YLabel"=>$arr[0]['graph2YLabel'], 
			"graph1Type"=>$arr[0]['graph1Type'],
			"graph2Type"=>$arr[0]['graph2Type'],
			"frequency1Type"=>$arr[0]['frequency1Type'],
			"frequency2Type"=>$arr[0]['frequency2Type'],
			"graph1Data"=>$dataList1,
			"graph2Data"=>$dataList2
		);
        return $returnValue;
    }

    /**
     * Get list status indicator
     *
     * @access public
     * @param array $options, Data for list
     * @return array
     *
     * @author Marco Antonio Nina <marco.antonio.nina@colosa.com>
     * @copyright Colosa - Bolivia
     */
    public function getStatusIndicator($options = array())
    {
        Validator::isArray($options, '$options');

        $usrUid = isset( $options["usrUid"] ) ? $options["usrUid"] : "";

        G::loadClass('indicatorsCalculator');
        $calculator = new \IndicatorsCalculator();
        $result = $calculator->statusIndicator($usrUid);
        return $result;
    }
}

