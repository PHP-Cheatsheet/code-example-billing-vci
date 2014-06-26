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
 * @version $$Id: Batch.php 1479 2013-09-06 07:18:42Z anit $$
 */

App::uses('Billing', 'Model/Billing');
App::uses('CakeTime', 'Utility');
App::uses('VciDate', 'Lib');


/**
 * Batch Model
 *
 * @property Invoice $Invoice
 */
class Batch extends Billing {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Invoice' => array(
			'className' => 'Invoice',
			'foreignKey' => 'batch_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	
/**
	 * Genrate invoice date
	 * 
	 * TODO :: check the date is valid or not
	 */
	private function __generateBatchDates($date = null) {
		$year = (isset($date['year']) && key_exists("year", $date)) 
						? $date['year'] : 'Y';
		$month = (isset($date['month']) && key_exists("month", $date)) 
						? $date['month'] : 'm';
		$thisMonthFirstDate = date("{$year}-{$month}-01");
		$endDate = date("{$year}-{$month}-01");
		$todaysMonth = date("n", strtotime($endDate));
		$todaysDay = date("j", strtotime($endDate));
		$todaysYear = date("y", strtotime($endDate));
		$lastMonthsDate = mktime(0, 0, 0, $todaysMonth + 1,
						$todaysDay, $todaysYear);
		//$lastMonthName = date("F", $lastMonthsDate);
		//$lastMonthYear = date("Y", $lastMonthsDate);
		$lastMonthFirstDate = date("Y-m-d", 
						$lastMonthsDate);
		return array('upperLimit' => $lastMonthFirstDate,
				'lowerLimit' => $thisMonthFirstDate);
	}

	/**
	 * Check if the month if less than current month.
	 * 
	 * @param string $month MM
	 * @return boolean true/false
	 */
	private function __isValidMonth($month) {
		$todaysMonth = date("m");
		return ($month <= $todaysMonth) ? true : false;
	}

	
/**
	 * 
	 * @param type $dataArray
	 * @return type
	 */
	private function __prepareDataForOverview($dataArray) {
		$renderView = array();
		foreach($dataArray as $data) {
			$key = $data['Invoice']['merchant_id'];
			$name = $data['LineItem']['name'];
			if (!array_key_exists($key, $renderView)) {
				$renderView[$key]['batch_id'] = 
						$data['Invoice']['batch_id'];
				$renderView[$key]['invoice_id'] = 
						$data['Invoice']['id'];
				$renderView[$key]['merchant_id'] = 
						$data['Invoice']['merchant_id'];
				$renderView[$key]['subtotal'] = 
						$data['Invoice']['subtotal'];
				$renderView[$key]['minimum_total'] = 
						$data['Invoice']['minimum_total'];
				$renderView[$key]['total'] = 
						$data['Invoice']['total'];
				$renderView[$key]['name'] = 
						$data['Merchant']['name'];
				if($name == 'StatementFee'){
					$renderView[$key]['statementFee'] = 
						$data['LineItem']['total_price'];
				} else {
					$renderView[$key]['statementFee'] = 0;
					$renderView[$key]['lineitem'][$name]['quantity'] = 
							$data['LineItem']['quantity'];
					$renderView[$key]['lineitem'][$name]['unit_price'] = 
							$data['LineItem']['unit_price'];
					$renderView[$key]['lineitem'][$name]['total_price'] = 
							$data['LineItem']['total_price'];
				}
			} else {
				if($renderView[$key]['merchant_id'] == 
						$data['Invoice']['merchant_id']) {
				$renderView[$key]['lineitem'][$name]['quantity'] = 
						$data['LineItem']['quantity'];
				$renderView[$key]['lineitem'][$name]['unit_price'] = 
						$data['LineItem']['unit_price'];
				$renderView[$key]['lineitem'][$name]['total_price'] = 
						$data['LineItem']['total_price'];
				$renderView[$key]['lineitem'][$name]['line_item_id'] = 
						$data['LineItem']['line_item_id'];
					
				}
			}
			
		}
		
		return $renderView;
	}

	/**
	 * Validate whether the passed input is a valid date or not.
	 * 
	 * @param string $dateIn Date (YYYY-MM or MM)
	 * @return bool true or false
	 */
	private function __validateDateFormat($dateIn) {
		if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])$/', $dateIn) ||
						preg_match('/^(0[1-9]|1[0-2])$/', $dateIn)) { 
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Validate date.
	 * 
	 * @param string $dateStrin Date YY-MM or MM
	 * @return string New batch id.
	 */
	private function __validateBatchDate($dateString = null) {
		$dateRange = array();
		if(isset($dateString)) {
			if($this->__validateDateFormat($dateString)) {
				
				if(preg_match('/-/', $dateString)) {
					$dateArr = explode('-', $dateString);
					$dateRange['year'] = $dateArr[0];
					$dateRange['month'] = $dateArr[1];
				} else {
					if($this->__isValidMonth($dateString)) {
						$dateRange['month'] = $dateString;
					}else {
						return false;
					}
				}
			} else {
				return false;
			}
		}
		return $this->__generateBatchDates($dateRange);
	}

	

	/**
	 * Generates new batch ID, date range.
	 * 
	 * @param string Date format YYYY-MM || MM 
	 * @return boolead True or False 
	 */
	public function generateBatchInfo($dateString = null) { 
		//debug($this); die;
		if($dateRange = $this->__validateBatchDate($dateString)) {
			//$this->useDbConfig = 'billingWrite';
			$this->create();
			$data['description'] = 'Merchant Billing';
			$data['source'] = $_SERVER['QUERY_STRING'];
			$data['creation_date'] = date("Y-m-d H:i:s");
			$vciDate = new VciDate();
			$dateYYYYMM = $vciDate->convertDateToYYYYMM(array($dateRange['lowerLimit']));
			$data['billing_month'] = $dateYYYYMM[0];
			$this->save($data);
			return array_merge($dateRange,
							array('batch_id' => $this->getLastInsertId()));
		} else {
			return false;
		}
	}

	/**
	 * Generate batch overview data.
	 * 
	 * @param string $id  Batch id
	 * @return array 
	 *			'Invoice.batch_id',
					'Invoice.id',
					'Invoice.merchant_id',
					'Merchant.name',
					'Invoice.subtotal',
					'Invoice.minimum_total',
					'Invoice.total',
					'LineItem.id as line_item_id',
					'LineItem.name',
					'LineItem.quantity',
					'LineItem.unit_price',
					'LineItem.total_price'
	 * @throws NotFoundException
	 */
	public function generateOverviewData($id) {
		$overViewInfo = '';
		if (!$this->exists($id)) {
			throw new NotFoundException(__('Invalid Batch Id.'));
		}
		$generateData = $this->Invoice->getAllInvoice($id);
		if (!empty($generateData)) {
			$overViewInfo = $this->__prepareDataForOverview($generateData);
		}
		return $overViewInfo;
	}
}
