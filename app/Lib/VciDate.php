<?php
/**
 * VERICHECK INC CONFIDENTIAL
 *
 * Vericheck Incorporated
 * All Rights Reserved.
 *
 * NOTICE:
 * All information contained herein is, and remains the property of
 * Vericheck Inc, if any.  The intellectual and technical concepts
 * contained herein are proprietary to Vericheck Inc and may be covered
 * by U.S. and Foreign Patents, patents in process, and are protected
 * by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Vericheck Inc.
 *
 * @copyright VeriCheck, Inc.
 * @version $$Id: VciDate.php 1405 2013-08-27 11:58:08Z deena $$
 */

App::uses('Holiday', 'Model');

/**
 * Some basic date utility functions related to Vericheck.
 *
 */
class VciDate {

	/**
	 * Get Date YYYYMM from date array.
	 *
	 * @param array $dateRange YYYY-MM-DD H:i:s or any of its variation.
	 * @return array $dateRange  array(key($dateRange) => YYYYMM)
	 */
	public function convertDateToYYYYMM($dateRange){
		$arrYYYYMM = array();
		foreach($dateRange as $key => $val) {
			$arrYYYYMM[$key] = str_replace("-",'', substr($val, 0, 7));
		}
		return $arrYYYYMM;
	}

	/**
	 * Calculate timestamp inclusive boundaries (start and end) for a single day.
	 *
	 * Example:
	 * Inputs: '2013-05-18', 'Y-m-d H:i:s'
	 * Output: array('start' => '2013-05-18 00:00:00', 'end' => '2013-05-18 23:59:59')
	 *
	 * @param string $day Format: YYYY-MM-DD
	 * @param false|string $format Default: false. If false, Unix timestamp is returned. If string, provide PHP date() format; example: 'Y-m-d H:i:s'
	 * @return array Associative array with keys: 'start' and 'end'. Format: ('start' => 'int|string', 'end' => 'int|string').
	 */
	public function getBoundariesForDay($day, $format = false) {
		$this->validateDateFormatAsMySQLDate($day);

		$start = strtotime(trim($day) . ' 00:00:00');
		$end = strtotime(trim($day) . ' 23:59:59');

		if ($format !== false) {
			$start = date($format, $start);
			$end = date($format, $end);
		}

		return array('start' => $start, 'end' => $end);
	}

	/**
	 * Calculate timestamp inclusive boundaries (start and end) for a single month.
	 *
	 * Example:
	 * Inputs: '2013-05', 'Y-m-d H:i:s'
	 * Output: array('start' => '2013-05-01 00:00:00', 'end' => '2013-05-31 23:59:59')
	 *
	 * @param string $month Format: YYYY-MM
	 * @param false|string $format Default: false. If false, Unix timestamp is returned. If string, provide PHP date() format; example: 'Y-m-d H:i:s'
	 * @return array Associative array with keys: 'start' and 'end'. Format: ('start' => 'int|string', 'end' => 'int|string').
	 */
	public function getBoundariesForMonth($month, $format = false) {

		$this->validateDateFormatAsMySQLDateMinusDay($month);

		$start = strtotime(trim($month) . '-01 00:00:00');
		//$start = date($month . '-01 00:00:00');

		// 't' means last day of month
		$endDateTime = date('Y-m-t 23:59:59', $start);

		//$end =  date($month . '-t 23:59:59');
		$end = strtotime($endDateTime);

		if ($format !== false) {
			$start = date($format, $start);
			$end = date($format, $end);
		}
		return array('start' => $start, 'end' => $end);

	}

	/**
	 * @todo DocBlock
	 */
	public function getDays($startDate, $endDate) {
		$date = strtotime(date('Y-m-01', strtotime($startDate)));
		$today = strtotime($endDate);
		$dates = array();
		while ($date < $today) {
			$year = date('Y', $date);
			$month = date('m', $date);
			array_push($dates, $date);

			$date = strtotime('+1 Day', $date);
		}

		return $dates;
	}

	/**
	 * Generate date according to given time.
	 *
	 * @param String $date Date Y-m-d
	 * @param String $timeSeries Time to add or reduce from $date
	 */
	public function getLowerDate($date = null, $timeSeries = null) {
		$dateToCompare = is_null($date)? date('Y-m-d'):$date;
		return date('Y-m-d', strtotime('-10 Days', strtotime($dateToCompare)));

	}

	/**
	 * Generate list of months.
	 *
	 * @param string $startDate YYYY-MM-DD; the starting date.
	 * @param string $endDate YYYY-MM-DD; the ending date.
	 * @return array All months within the start and end dates, inclusive. E.g. ((timestamp), ...), where timestamp is Unix timestamp for first day of month.
	 */
	public function getMonths($startDate, $endDate) {
		$date = strtotime(date('Y-m-01', strtotime($startDate)));
		$today = strtotime($endDate);

		$dates = array();

		while ($date < $today) {
			$year = date('Y', $date);
			$month = date('m', $date);
			array_push($dates, $date);

			$date = strtotime('+1 Month', $date);
		}

		return $dates;
	}

	/**
	 * Determine if provided date is a business day.
	 *
	 * @param int $date Timestamp.
	 * @return boolean True if date is a business date; false otherwise.
	 */
	public function isBusinessDate($date) {
		$result = false;
		$this->Holiday = new Holiday();

		$holidays = $this->Holiday->getHolidays();

		$day_of_week = date('w', $date);
		$date_ymd = date('Y-m-d', $date);

		if ($day_of_week != '0' &&
		$day_of_week != '6' &&
		in_array($date_ymd, $holidays) == false) {

			$result = true;
		}

		return $result;
	}

	/**
	 * Validates if $date is in $regex format and is an actual date in Gregorian
	 * calendar.
	 *
	 * @param string $date Input date string to validate.
	 * @param $regex Regular expression to check $date against.
	 * @param boolean $throwException If true, an Exception will be thrown if validation fails. Default, true.
	 * @return boolean True on success; false, otherwise.
	 * @throws Exception if $throwException is true and validation fails.
	 */
	public function validateDateFormat($date, $regex, $throwException = true) {
		$valid = false;

		$dateTimestamp = strtotime($date);

		if ((preg_match($regex, $date) === 1) && $dateTimestamp !== false) {
			$year = date('Y', $dateTimestamp);
			$month = date('m', $dateTimestamp);
			$day = date('d', $dateTimestamp);

			if (checkdate($month, $day, $year)) {
				$valid = true;
			}
		}

		if (($valid === false) && ($throwException)) {
			throw new Exception("Date $date is invalid format.");
		}

		return $valid;
	}

	/**
	 * Validates if $date is in MySQL Date format (YYYY-MM-DD) and is an actual
	 * date in Gregorian calendar.
	 *
	 * @param string $date Input date string to validate.
	 * @param boolean $throwException If true, an Exception will be thrown if validation fails. Default, true.
	 * @return boolean True on success; false, otherwise.
	 * @throws Exception if $throwException is true and validation fails.
	 */
	public function validateDateFormatAsMySQLDate($date, $throwException = true) {
		/*
			/ = start or end of regular expression
			^ = beginning of string
			\d = any digit character
			- = just the hyphen character (-)
			{2} = exactly two quantities of the preceding character
			$ = end of string
			*/
		$regex = '/^\d{4}-\d{2}-\d{2}$/';

		return $this->validateDateFormat($date, $regex, $throwException);
	}

	/**
	 * Validates if $date is in MySQL Date format minus the Day (YYYY-MM) and is
	 * an actual date in Gregorian calendar.
	 *
	 * @param string $date Input date string to validate.
	 * @param boolean $throwException If true, an Exception will be thrown if validation fails. Default, true.
	 * @return boolean True on success; false, otherwise.
	 * @throws Exception if $throwException is true and validation fails.
	 */
	public function validateDateFormatAsMySQLDateMinusDay($date, $throwException = true) {
		// Append '-01' to create full MySQL Date.
		$date .= '-01';

		return $this->validateDateFormatAsMySQLDate($date, $throwException);
	}

	/**
	 * Validates if $date is in MySQL DateTime format (YYYY-MM-DD HH:MM:SS) and is
	 * an actual date in Gregorian calendar.
	 *
	 * @param string $date Input date string to validate.
	 * @param boolean $throwException If true, an Exception will be thrown if validation fails. Default, true.
	 * @return boolean True on success; false, otherwise.
	 * @throws Exception if $throwException is true and validation fails.
	 */
	public function validateDateFormatAsMySQLDateTime($date, $throwException = true) {
		// See validateDateFormatAsMySQLDate() for regex explanation
		$regex = '/^\d{4}-\d{2}-\d{2}$/';

		return $this->validateDateFormat($date, $regex, $throwException);
	}

	/**
	 * Get 1st day of a given month and next month.
	 *
	 * Example
	 * Input: 2013, 05
	 * Output array(startdate => 2013-05-01 00:00:00, endDate => 2013-06-01 00:00:00)
	 *
	 * @param string $year Format: YYYY
	 * @param string $month Format: MM
	 * @return array (startDate => date, endDate => date) where date is in Format: YYYY-MM-DD HH:II:SS
	 */
	//	public function getDatesForData($year, $month) {
	//		if ($month == 12) {
	//			$newmonth = 01;
	//			$newyear = $year + 1;
	//		} else {
	//			$newmonth = $month + 1;
	//			$newyear = $year;
	//		}
	//		$startDate = date($year . '-' . $month . '-01 00:00:00');
	//		$endDate = date($newyear . '-' . $newmonth . '-01 00:00:00');
	//
	//		return array('startDate' => $startDate, 'endDate' => $endDate);
	//	}

	/**
	 * Get 1st day, last day of given month, 2nd day of next month and 15 day prior to given month .
	 *
	 * Example
	 * Input: 2013, 05
	 * Output array(startdate => 2013-05-01, endDate => 2013-05-31, upperBound =>2013-06-02 lowerBound => 2013-04-16)
	 *
	 * @param string $year Format: YYYY
	 * @param string $month Format: MM
	 * @return array (startDate => date,endDate => date,uperBound  => date and lowerBound  => date) where date is in Format: YYYY-MM-DD.
	 *
	 */
	public function getDates($year, $month) {
		$startDate = date($year . '-' . $month . '-01');
		$endDate = date($year . '-' . $month . '-t');
		if ($month == 12) {
			$upmonth = 01;
			$upyear = $year + 1;
		} else {
			$upmonth = $month + 1;
			$upyear = $year;
		}
		if ($month == 01) {
			$lowmonth = 12;
			$lowyear = $year - 1;
		} else {
			$lowmonth = $month - 1;
			$lowyear = $year;
		}

		$uperBound = date($upyear . '-' . $upmonth . '-d', strtotime('+1 Day', $startDate));
		$lowerBound = date($lowyear . '-' . $lowmonth . '-d', strtotime('-15 Day', $startDate));

		return array('startDate' => $startDate, 'endDate' => $endDate,
			'uperBound' => $uperBound, 'lowerBound' => $lowerBound);
	}

	/**
	 * Calculate the next business date.
	 *
	 * @param integer $refDate Unix timestamp of the reference date.
	 * @param integer $delta The number of day to progress or regress. Negative integer is for regression.
	 * @return string Unix timestamp of result date.
	 */
	public static function getBusinessDate($refDate, $delta = 1) {
		$currentDate = $refDate;
		$holiday = new Holiday();
		$holidays = $holiday->getHolidays();
		if (empty($holidays)) {
			throw new Exception('Holidays array is empty.');
		}

		$direction = '';
		if ($delta > 0) {
			$direction = '+1 day';
		}
		elseif ($delta < 0) {
			$direction = '1 days ago';
		}

		$delta = abs($delta);
		$_day = 0;
		while ($_day != $delta) {
			$currentDate = strtotime($direction, $currentDate);

			$day_of_week = date('w', $currentDate);
			$date = date('Y-m-d', $currentDate);

			if ($day_of_week != '0' &&
			$day_of_week != '6' &&
			in_array($date, $holidays) == false) {

				$_day++;
			}

		}

		return $currentDate;
	}
}
