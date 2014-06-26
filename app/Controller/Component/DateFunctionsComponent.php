<?php

class DateFunctionsComponent extends Component {


/**4/26/2012 deena
 * generates two date range
 * @return array: startdate and end date
 * @controller: Holidays Contoller vci-102, Activities Controller
 */

	public function getDateValues($timeRange){
		$thisMonthFirstDate = date("Y-m-01");
		$endDate = date("Y-m-01");
		$endDate = strtotime($endDate);
		$todaysMonth = date("n", $endDate);
		$todaysDay = date("j",$endDate);
		$todaysYear = date("y",$endDate);
		$currentDay = date("j");

		switch ($timeRange) {
			case 'twoYear': //get date range of two year used in Holidays Contoller
				$year = date('Y');
				$twoyearsAfter = date('Y') + 1;
				$startMonth = 1; //january
				$startDay = 1; //1st day
				$endMonth = 12; //december
				$endDay = 31; //december

				$startDate = mktime(0, 0, 0, $startMonth, $startDay, $year);
				$endDate = mktime(0, 0, 0, $endMonth, $endDay, $twoyearsAfter);

				$startDate = date("Y-m-d",$startDate);
				$endDate = date("Y-m-d",$endDate);
				break;
			case 'previous': //Activities Controller
				$daysInLastMonth = mktime(0, 0, 0, $todaysMonth, $todaysDay - 1, $todaysYear);
				$daysInLastMonth = date("j",$daysInLastMonth);

				$lastMonthsFirstDay = mktime(0, 0, 0, $todaysMonth - 1, $todaysDay, $todaysYear);
				if( $currentDay < $daysInLastMonth) {
					$lastMonthToDate = mktime(0, 0, 0, $todaysMonth - 1, $currentDay + 1, $todaysYear);
				} else {
					$lastMonthToDate = mktime(0, 0, 0, $todaysMonth - 1, $daysInLastMonth + 1, $todaysYear);
				}
				$startDate = date("Y-m-d",$lastMonthsFirstDay);
				$endDate = date("Y-m-d",$lastMonthToDate);
				break;
			case 'present': //Activities Controller
				$thisMonthStartDay = mktime(0, 0, 0, $todaysMonth, $todaysDay, $todaysYear);
				$nextMonthStartDay = mktime(0, 0, 0, $todaysMonth, $currentDay+1, $todaysYear);

				$startDate = date("Y-m-d",$thisMonthStartDay);
				$endDate = date("Y-m-d",$nextMonthStartDay);
				break;
			case 'lastyeartilldate': //Activities Controller
				$lastyearStartDay = mktime(0, 0, 0, 1, 1, $todaysYear-1);
				$lastyearDueDay = mktime(0, 0, 0, $todaysMonth, $currentDay+1, $todaysYear-1);

				$startDate = date("Y-m-d",$lastyearStartDay);
				$endDate = date("Y-m-d",$lastyearDueDay);
				break;
			case 'thisyeartilldate': //Activities Controller
				$lastyearStartDay = mktime(0, 0, 0, 1, 1, $todaysYear);
				$lastyearDueDay = mktime(0, 0, 0, $todaysMonth, $currentDay+1, $todaysYear);
				$startDate = date("Y-m-d",$lastyearStartDay);
				$endDate = date("Y-m-d",$lastyearDueDay);
				break;
			case 'previousMonth': // gives date range of whole last month
				$previousMonthStartDay = mktime(0, 0, 0, $todaysMonth - 1, 1, $todaysYear);
				$previousMonthDueDay = mktime(0, 0, 0, $todaysMonth, 1, $todaysYear);
				$startDate = date("Y-m-d",$previousMonthStartDay);
				$endDate = date("Y-m-d",$previousMonthDueDay);
				break;
		}
		return array('startDate' => $startDate,'endDate' => $endDate);
	}

	/*
	 * get last month and current year for calculation total transaction Amount and Qty
	 * used in Activity Projection Contoller
	 * @controller: ACtivity Projection controller
	 */

	public function makeDate() {
		$currentMonth = date("n");
		$tempCurrentMonth = $currentMonth;
		if ($tempCurrentMonth < 10) {
			$tempCurrentMonth = "0$tempCurrentMonth";
		}

		$tempStr = date("Y") . "-$tempCurrentMonth-01 00:00:00";
		$thisMonthStartDate = date($tempStr);
		$tempMonthStartDate = strtotime( $thisMonthStartDate);

		$currentMonth = date("m", $tempMonthStartDate);
		$currentDay = date("d",$tempMonthStartDate);
		$currentYear = date("Y",$tempMonthStartDate);

		$currentMonthNum = date("n", $tempMonthStartDate);
		$currentYearNum = date("Y", $tempMonthStartDate);

		$lastMonth_temp = mktime(0, 0, 0, $currentMonth-1, $currentDay, $currentYear);
		$lastMonth = date("m", $lastMonth_temp);

		$myCurrentYear = $currentYear;
		if( date("n", $lastMonth_temp) == "12" || date("n", $lastMonth_temp) == 12)
		{
			$myCurrentYear = $currentYear - 1;
		}
		return array('CurrentYear' => $myCurrentYear, "lastMonth" => $lastMonth);
	}

	public function getDateRange($dateType = null) {
		$dateRange = array();
		$lastYear = '';//"-1 year";
		$now = date("Y-m-d");
		$max = "";
		$min = "";
		switch($dateType) {
			case "last_month" :
				$max = date("Y-m-d", strtotime('last day of last month' . $lastYear));
				$min = date("Y-m-d", strtotime('first day of last month' . $lastYear));
				$dateRange = array('max' => $max, 'min' => $min);
				break;
			case "this_month" :
				$max = $now;
				$min = date("Y-m-d", strtotime('first day of this month' . $lastYear));
				$dateRange = array('max' => $max, 'min' => $min);
				break;
		}
		return $dateRange;
	}
}