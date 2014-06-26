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
 * @version $$Id: Transaction.php 1676 2013-09-26 03:18:35Z sisir $$
 */

App::uses('AppModel', 'Model');

/**
 * Transaction Model
 * This class contains common functions used throughout the application
 *
 */
class Transaction extends AppModel {

	public $useDbConfig = 'echecksRead';

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = '';
	public $belongsTo = array(
		'Merchant' => array(
			'className' => 'Merchant',
			'foreignKey' => 'merchantId',
			'conditions' => array('Merchant.merchantId = Transaction.merchantId')),
//		'Webcheck' => array(
//			'className' => 'Webcheck',
//			'foreignKey' => false,
//			'conditions' => 'Transaction.transaction_id = Webchecks.id ')
	);
	public $hasOne = array(
		'SettlementWarehouse' => array(
			'className' => 'SettlementWarehouse',
			'foreignKey' => false,
			'conditions' => array(
				'SettlementWarehouse.origination_transaction_id = Transaction.transaction_id',
				'SettlementWarehouse.is_embedded_fee' => 'no')
	),
	);

	/**
	 * List Returns Transactions between two date limits
	 * @param String $merchant_id
	 * @param Array $batch_date
	 * @return Array
	 */
	public function getReturns($merchantId, $batchDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'transaction_id' => 'transaction_id',
						'amount' => 'amount'
						),
					'conditions' => array(
						'Transaction.merchantId' => $merchantId,
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.settle_date < ' =>
								$batchDate['this_month_first_date'],
							'Transaction.settle_date >= ' =>
								$batchDate['last_month_first_date'])
						))
						);
	}

	public function getChargeBacks($merchantId, $batchDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'transaction_id' => 'transaction_id',
						'amount' => 'amount'),
					'conditions' => array(
						'Transaction.merchantId' => $merchantId,
						'Transaction.response_status' => 'R',
						'Transaction.settle_date < ' => $batchDate['this_month_first_date'],
						'Transaction.settle_date >= ' => $batchDate['last_month_first_date'],
						'LEFT(Transaction.reason,3)' => array('R05', 'R07', 'R08', 'R10', 'R29'),
		array('if(Transaction.isonumber in ("4300", "30000"),
							LEFT(Transaction.reason,3) <> "R08", 1=1)')
		)
		)
		);
	}

	public function getChargeBackforReport($startDate, $endDate, $chargebackCodes) {
		$this->unbindModelAll();
		return $this->find(
						'all', array('fields' =>
		array('COUNT(id) AS totalChargebacks',
						'SUM(amount) AS chargebackDollars'),
					'conditions' => array(
						'and' => array('Transaction.response_status' => 'R',
							'Transaction.settle_date >= ' => $startDate,
							'Transaction.settle_date <= ' => $endDate
		, $chargebackCodes
		)
		)));
	}

	public function getReturnsReport($startDate, $endDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array('COUNT(id) AS totalReturns',
						'SUM(amount) AS totalReturnDollars',
						'LEFT(reason,3) as reasonCode'),
					'conditions' => array(
						'and' => array('Transaction.response_status' => 'R',
							'Transaction.settle_date >= ' => $startDate,
							'Transaction.settle_date <= ' => $endDate)),
					'group' => 'Transaction.reason'
					));
	}

	public function getReversalsReport($startDate, $endDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'COUNT(Transaction.id) AS totalReversals',
						'SUM(Transaction.amount) AS reversalDollars',
						'Transaction.transaction_type',
						'Transaction.original_transaction_id'),
					'use' => 'transaction_type',
					'joins' => array(array(
							'table' => 'webchecks',
							'alias' => 'Webchecks',
							'type' => 'LEFT',
							'conditions' => 'Transaction.transaction_id = cast(Webchecks.id as char)')),
					'conditions' => array(
						'Transaction.transaction_type' => '2',
						'Transaction.original_transaction_id !=' => NULL,
						'and' => array(
							'Webchecks.interceptPost >= ' => $startDate,
							'Webchecks.interceptPost <= ' => $endDate),
		),
					'group' => array('Transaction.transaction_type',
						'Transaction.original_transaction_id')
		));
	}

	/**
	 *
	 * List Credits Transactions between two date limits
	 * @param String $merchant_id
	 * @param Array $batch_date
	 * @return Array
	 */
	public function getCredits($merchantId, $batchDate) {
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array('fields' => array(
						'transaction_id' => 'transaction_id',
						'amount' => 'amount'),
					'conditions' => array(
						'Transaction.merchantId' => $merchantId,
						'and' => array(
							'Transaction.settle_date <= ' => $batchDate['this_month_first_date'],
							'Transaction.settle_date >= ' => $batchDate['last_month_first_date']
		),
						'Transaction.description' => 'CREDIT')
		)
		);
	}

	/**
	 * List Tiered Fee Transactions between two date limits
	 * @param String $merchant_id
	 * @param Array $batch_date
	 * @return Array
	 */
	public function getTieredFees($tieredfee, $batchDate, $tier) {
		$this->unbindModelAll();
		$condition = '';
		//import Model
		App::uses('Webcheck', 'Model');
		//instantiate object
		$webcheck = new Webcheck();
		switch ($tier) {
			case 1:
				$condition = array(
					'Webcheck.amount <= ' => $tieredfee[0]['BillingTieredValue']['tier_1_amt']);
				break;
			case 2:
				$condition = array(
					'and' => array(
						'Webcheck.amount > ' => $tieredfee[0]['BillingTieredValue']['tier_1_amt'],
						'Webcheck.amount <= ' => $tieredfee[0]['BillingTieredValue']['tier_2_amt']
				)
				);
				break;
			case 3:
				$condition = array('Webcheck.amount > ' => $tieredfee[0]['BillingTieredValue']['tier_2_amt']);
				break;
		}
		return $webcheck->find(
						'all', array(
					'fields' => array('id AS transaction_id',
						'amount'),
					'conditions' => array(
						'Webcheck.merchantId' => $tieredfee[0]['BillingTieredValue']['merchantId'],
						'and' => array('Webcheck.interceptPost < ' => $batchDate['this_month_first_date'],
							'Webcheck.interceptPost >= ' => $batchDate['last_month_first_date']
		),
						'Webcheck.status' => array('B', 'S', 'R'),
		$condition
		),
					'order' => array('Webcheck.merchantId' => 'DESC')
		, 'limit' => '2'
		)
		);
	}

/**
 * Get all transactions.
 * 
 * @param string $merchantId
 * @param array $batchDate array('this_month_first_date' =>'Y-m-d',
 *															'last_month_first_date' => 'Y-m-d')
 * @return array array(n=>Transaction)
 */
	public function getTransactions($merchantId, $batchDate) {
		$this->unbindModelAll();
		$return = $this->find(
					'all', 
					array(
							'fields' => array('transaction_id' => 'transaction_id',
							'amount' => 'amount'),
							'conditions' => array(
									'Transaction.merchantId' => $merchantId,
									'and' => array('Transaction.settle_date < ' =>
											$batchDate['this_month_first_date'],
																'Transaction.settle_date >= ' =>
											$batchDate['last_month_first_date']
														),
									'Transaction.response_status' => array('B', 'S', 'R')
								)));
		return $return;
		}

	/**
	 * List records necessary for filling Transaction auxiliaries 
	 * (Warehouse Table)
	 *
	 * @param array $lastMP
	 */
	public function getTansMP($lastMP = null, $limit = '1000') {
		$this->unbindModelAll();
		$lastMP = $lastMP == null ? 0 : $lastMP;
		return $this->find(
						'all', array(
					'fields' => array(
						'customer_name',
						'merchant_name',
						'cust_account_number',
						'transaction_id'),
					'conditions' => array('transaction_id > ' => $lastMP),
					'limit' => "$limit",
					'order' => array('transaction_id' => 'asc')
		)
		);
	}

	public function getChargebackOffenders($merchantId, $batchDate) {
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'fields' => array('transaction_id' => 'transaction_id',
						'amount' => 'amount'),
					'conditions' => array(
						'Transaction.merchantId' => $merchantId,
						'and' => array('Transaction.settle_date <= ' => $batchDate['this_month_first_date'],
							'Transaction.settle_date >= ' => $batchDate['last_month_first_date']
		),
						'Transaction.response_status' => array('B', 'S', 'R')
		)
		)
		);
	}

	public function getAssignedTransactions($conditions = null) {
		$this->unbindModelAll();
		return $this->find('all', array('conditions' => array('OR' => array($conditions))));
	}

	public function getReturnReports($merchantId, $batchDate) {
		// unbind other association
		$this->unbindModelAll();
		//check if the user has any transactions
	}

	public function getReportChargeBacks($batchDate) {
		//we dont  need Merchants all detail info so unbind it form the association
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' => array('isoNumber',
						'merchantId',
						'SUM(Transaction.amount) AS chargeback_dollars',
						'Merchant.name',
						'Merchant.funding_time',
						'COUNT(Transaction.id) AS num_chargebacks',
		),
					'joins' => array(
		array('table' => 'merchants',
							'alias' => 'Merchant',
							'type' => 'left',
							'conditions' => array(
								'Transaction.merchantId=Merchant.merchantId')
		)
		),
					'conditions' => array(
						'Transaction.merchantId >' => 0,
						'Transaction.response_status' => 'R',
						'	and' => array(
							'Transaction.settle_date >= ' => $batchDate['startDate'],
							'Transaction.settle_date <= ' => $batchDate['endDate'],
		array('LEFT(Transaction.reason,3)="R05"
			OR LEFT(Transaction.reason,3)="R07"
				OR LEFT(Transaction.reason,3)="R08"
				OR LEFT(Transaction.reason,3)="R10"
				OR LEFT(Transaction.reason,3)="R29" '),
		//array('if(Transaction.isonumber in ("4300", "30000"), LEFT(Transaction.reason,3) <> "R08", 1=1)')
		),),
					'group' => 'merchantId',
					'order' => array('num_chargebacks' => 'DESC'),
		)
		);
	}

	public function getReportReturns($batchDate) {
		//pr($batchDate);
		// unbind other association
		//echo $merchantId;
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' => array('Transaction.merchantId',
						'SUM(Transaction.amount) AS returns_dollars',
						'COUNT(Transaction.id) AS num_returns'),
					'conditions' => array(
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.settle_date >= ' => $batchDate['startDate'],
							'Transaction.settle_date <= ' => $batchDate['endDate']
		),
		),
					'group' => 'Transaction.merchantId'
					)
					);
	}

	public function getReportTransactions($batchDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' =>
		array(
						'Transaction.merchantId', 'COUNT(id) AS num_transactions'
						),
					'conditions' => array(
						'and' => array(
							'Transaction.settle_date >= ' => $batchDate['startDate'],
							'Transaction.settle_date <= ' => $batchDate['endDate']
						),
						'Transaction.response_status' => array('B', 'S', 'R')
						),
					'group' => 'Transaction.merchantId'
					)
					);
	}

	public function getReportIsoActivity($isoNumber, $startDate, $endDate) {
		//we dont  need Merchants all detail info so unbind it form the association
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'fields' => array('COUNT(*) AS num_transactions',
						'SUM(Transaction.amount) as total_amount',
		),
					'joins' => array(
		array('table' => 'merchants',
							'alias' => 'Merchant',
							'type' => 'left',
							'conditions' => array('Transaction.merchantId=Merchant.merchantId')
		)
		),
					'conditions' => array(
						'Transaction.isoNumber' => $isoNumber,
						'and' => array('Transaction.posted_date >= ' => $startDate,
							'Transaction.posted_date <= ' => $endDate,
		array('Transaction.response_status="A"
				OR Transaction.response_status="B"
				OR Transaction.response_status="R"
				OR Transaction.response_status="S"'),
		),),
		)
		);
	}

	public function getTotal($isoNumber, $startDate, $endDate) {
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'fields' => array('COUNT(*) AS num_transactions',
						'SUM(Transaction.amount) as total_amount',
		),
					'conditions' => array(
						'Transaction.isoNumber' => $isoNumber,
						'and' => array(
							'Transaction.posted_date >= ' => $startDate,
							'Transaction.posted_date <= ' => $endDate
		))
		));
	}

	public function getReportIsoActivityNew($date = null) {
		//we dont  need Merchants all detail info so unbind it form the association
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'autocache' => false,
					'fields' => array('Transaction.isoNumber',
						'Transaction.amount',
		),
					'joins' => array(
		array('table' => 'merchants',
							'alias' => 'Merchant',
							'type' => 'left',
							'conditions' => array(
								'Transaction.merchantId=Merchant.merchantId')
		)
		),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >= ' => $date['min'],
							'Transaction.posted_date <= ' => $date['max'],
		array('Transaction.response_status = "A"
			OR Transaction.response_status = "B"
			OR Transaction.response_status = "R"
			OR Transaction.response_status = "S"'),
		),),
					'order' => 'isoNumber',
		)
		);
	}

	public function getTotalNew($date) {
		try {
			$this->unbindModelAll();
			return $this->find(
							'all', array(
						'autocache' => true,
						'fields' => array('Transaction.isoNumber',
							'Transaction.amount',
			),
						'conditions' => array(
							'and' => array(
								'Transaction.posted_date >= ' => $date['min'],
								'Transaction.posted_date <= ' => $date['max'])),
						'order' => 'isoNumber',
			));
		} catch (Exception $exec) {
			echo $exec->getMessage();
		}
	}

	/**
	 * calculate transactions total sum and amount on a monthly basis
	 * @param type $dateRange
	 * @return type
	 */
	public function getTransNumAndSum($dateRange = null) {
		//remove all relations to the model
		$this->unbindModelAll();

		return $this->find(
						'all', array(
					'fields' => array(
						"COUNT(`id`) AS 'TotalTransactions'",
						"SUM(`amount`) AS 'TotalAmount'",
						"DATE(`settle_date`) AS 'ReportDate'"
						),
					'conditions' => array(
						'and' => array(
							'Transaction.settle_date >=' => $dateRange['min'],
							'Transaction.settle_date <' => $dateRange['max'])
						),
					'group' => 'YEAR(`settle_date`), MONTH(`settle_date`)',
					'order' => 'DATE(`settle_date`) ASC',
						array('autocache' => true)
						));
	}

	public function getOdfiTotal($merchantId, $date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array('COUNT(Transaction.id) AS total_settled',
						'SUM(Transaction.amount) as total_dollars',
		),
					'conditions' => array(
						'Transaction.merchantId' => $merchantId,
						'and' => array('Transaction.settle_date >= ' => $date['startDate'],
							'Transaction.settle_date < ' => $date['endDate']),
						'Transaction.response_status' => array('B', 'S', 'R'))
		));
	}

	public function getOdfiTotalNew($date) {

		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' => array('Transaction.id AS total_settled',
						'Transaction.amount as total_dollars',
						'Transaction.merchantId'
						),
					'conditions' => array(
						'and' => array('Transaction.settle_date >= ' => $date['startDate'],
							'Transaction.settle_date < ' => $date['endDate'])),
					'Transaction.response_status' => array('B', 'S', 'R')
						));
	}

	/**
	 * fetch the customer name
	 * @param type $date
	 * @return type array
	 */
	public function getCustName($date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'customer_name',
						'COUNT(customer_name) AS num_names'
						),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >= ' => $date['startDate'],
							'Transaction.posted_date <= ' => $date['endDate']),
						'Transaction.response_status' => array('A', 'B', 'S')
						),
					'group' => "REPLACE(customer_name,'','')",
					'order' => 'num_names DESC'
					)
					);
	}

	public function getCustNameNew($date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'Transaction.cust_account_number',
						'customer_name',
						'merchantId',
						'transaction_id',
						'posted_date',
						'cust_routing_number',
						'amount',
		),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >= ' => $date['startDate'],
							'Transaction.posted_date <= ' => $date['endDate']),
						'Transaction.response_status' => array('A', 'B', 'S')
		),
					'group' => array('Transaction.cust_account_number'),
					'order' => 'customer_name DESC'
					)
					);
	}

	/**
	 * fetch the mercahntId
	 * @param type $date ,$name
	 * @return type Array
	 */
	public function getCustId($date, $name) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'merchantId',
						'COUNT( DISTINCT cust_account_number) as num_accounts'
						),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >= ' => $date['startDate'],
							'Transaction.posted_date <= ' => $date['endDate']),
						'Transaction.response_status' => array('A', 'B', 'S'),
						array("REPLACE(customer_name,'','')= '" . $name . "'")
						),
					'group' => 'merchantId',
					'order' => 'num_accounts DESC'
					)
					);
	}

	/**
	 * fetch the data from transaction table
	 * @param type $date ,$name,$merchantId
	 * @return type Array
	 */
	public function getCustData($date, $name, $merchantId) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'transaction_id',
						'posted_date',
						'cust_routing_number',
						'cust_account_number',
						'amount',
						'customer_name'
						),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >= ' => $date['startDate'],
							'Transaction.posted_date <= ' => $date['endDate']),
						'Transaction.response_status' => array('A', 'B', 'S'),
						array("REPLACE(customer_name,'','')= '" . $name . "'"),
						'merchantId' => $merchantId
						),
					'order' => 'transaction_id DESC'
					)
					);
	}

	/**
	 * Determine the unique ISOs which had transactions originate on the given dates.
	 *
	 * @param string $startDate YYYY-MM-DD
	 * @param string $endDate YYYY-MM-DD
	 */
	public function getDistinctOriginatingIsos($startDate, $endDate) {
		$this->unbindModelAll();

		// The actual start date should be 6AM because there were times when
		// the eod cut off was delayed. So we do not want to use data from 1 AM,
		// which actually may have belonged to the previous day.
		$startDate_actual = $startDate . " 06:00:00";

		// Same reasoning as start date 6AM.
		$endDate_ts = strtotime($endDate);
		$dayAfterEndDate_ts = strtotime('+1 Days', $endDate_ts);
		$dayAfterEndDate = date('Y-m-d 06:00:00', $dayAfterEndDate_ts);

		return $this->find(
						'all', array(
					'fields' => array('DISTINCT isoNumber'),
					'joins' => array(array(
							'table' => 'webchecks',
							'alias' => 'Webchecks',
							'type' => 'LEFT',
							'conditions' => 'Transaction.transaction_id = cast(Webchecks.id as char)')),
					'conditions' => array(
						'Webchecks.interceptPost between ? and ?' => array($startDate_actual, $dayAfterEndDate),
						'Transaction.response_status' => array('S', 'B', 'R')),
					'recursive' => 0
		)
		);
	}

	public function getReturnChecksId($date) {
		//echo $date;
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' => array(
						'ReturnedChecks.merchantId',
						'COUNT(ReturnedChecks.id) AS code_num_returns_this_month'
						),
					'joins' => array(array(
							'table' => 'returned_checks',
							'alias' => 'ReturnedChecks',
							'type' => 'left',
							'conditions' => array(
								'ReturnedChecks.transaction_id = Transaction.transaction_id'
								)
								)),
					'conditions' => array(
						'Transaction.transaction_type !=' => '2',
						'and' => array('Transaction.settle_date >= ' => $date),
								),
					'group' => 'ReturnedChecks.merchantId',
					'order' => 'code_num_returns_this_month DESC'
					)
					);
	}

	/**
	 * fetch the merchantId
	 * @param type $date , $code
	 * @return type
	 */
	public function getReturnChecksIdReason($date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => true,
					'fields' => array(
						'ReturnedChecks.merchantId',
						'COUNT(ReturnedChecks.id) AS code_num_returns_this_month',
						'ReturnedChecks.reason',
		),
					'joins' => array(
		array(
							'table' => 'returned_checks',
							'alias' => 'ReturnedChecks',
							'conditions' => array(
								'ReturnedChecks.transaction_id = Transaction.transaction_id',
		)
		)),
					'conditions' => array(
						'Transaction.transaction_type !=' => '2',
						'Transaction.settle_date >= ' => $date,
		),
					'group' => 'ReturnedChecks.merchantId',
					'order' => 'code_num_returns_this_month DESC'
					)
					);
	}

	/**
	 * get Returned Transactions
	 *
	 */
	public function getReturnTrans($dateRange = null) {
		//remove all relations to the model
		$this->unbindModelAll();

		return $this->find(
						'all', array(
					'fields' => array(
						"COUNT(`id`) AS 'TotalTransactions'",
						"SUM(`amount`) AS 'TotalAmount'",
						"DATE(`settle_date`) AS 'ReportDate'"
						),
					'conditions' => array(
						'and' => array(
							'Transaction.settle_date >=' => $dateRange['min'],
							'Transaction.settle_date <' => $dateRange['max'])
						),
					'group' => 'YEAR(`settle_date`), MONTH(`settle_date`)',
					'order' => 'DATE(`settle_date`) ASC'
					));
	}

	public function getTotalTrans($dateRange) {
		//remove all relations to the model
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						"COUNT(`id`) AS 'TotalTransactions'",
		),
					'conditions' => array(
						'and' => array(
							'Transaction.posted_date >=' => $dateRange['min'],
							'Transaction.posted_date <=' => $dateRange['max'])
		), array('autocache' => true)
		));
	}

	/**
	 * Count returned transactions that exist in returned checks table.
	 * @param type Array $dateRange (max, min)
	 * @return type Array
	 */
	public function getReturnedCheckTrans($dateRange = null) {
		//remove all relations to the model
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'COUNT(ReturnedChecks.id) AS TotalReturns'
						),
					'joins' => array(
						array(
							'table' => 'returned_checks',
							'alias' => 'ReturnedChecks',
							'type' => 'left',
							'conditions' => array(
								'ReturnedChecks.transaction_id' => 'Transaction.transaction_id'
								)
								),
								),
					'conditions' => array(
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.posted_date >=' => $dateRange['min'],
							'Transaction.posted_date <=' => $dateRange['max'])
								)
								)
								);
	}

	/**
	 * calculate transactions total sum and amount on a passed date range
	 * @param type $dateRange
	 * @return type Array
	 */
	public function getTotalVal($dateRange, $merchantID) {
		$this->unbindModelAll();
		$values = $this->find(
				'all', array(
			'fields' => array(
				'COUNT(Transaction.id) AS numTransactions',
				'SUM(Transaction.amount) AS totalAmount'
				),
			'joins' => array(
				array('table' => 'merchants',
					'alias' => 'Merchant',
					'type' => 'left',
					'conditions' => array(
						'Transaction.merchantId' => $merchantID,
						'Transaction.merchantId = Merchant.merchantId')
				)),
			'conditions' => array(
				'Transaction.posted_date >=' => $dateRange['startDate'],
				'Transaction.posted_date <' => $dateRange['endDate'])
				));
				return $values;
	}

	/**
	 *
	 * @param type $dateRange (max, min)
	 * @return type
	 */
	public function getRTransRCodeDetail($dateRange = null) {
		//remove all relations to the model
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'COUNT(Transaction.transaction_id) AS total_returns',
						'ReturnCode.return_code AS return_code',
						'ReturnCode.description AS return_code_description',
						'ReturnCode.type AS return_code_type'
						),
					'joins' => array(
						array(
							'table' => 'returned_checks',
							'alias' => 'ReturnedChecks',
							'type' => 'left',
							'conditions' => array(
								'ReturnedChecks.transaction_id = Transaction.transaction_id'
								)
								),
								array(
							'table' => 'return_codes',
							'alias' => 'ReturnCode',
							'type' => 'left',
							'conditions' => array(
								'ReturnCode.return_code = LEFT(Transaction.reason,3)'
								)
								)
								),
					'conditions' => array(
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.posted_date >=' => $dateRange['min'],
							'Transaction.posted_date <=' => $dateRange['max']),
						''
						),
					'group' => 'ReturnCode.return_code',
					'order' => 'ReturnCode.return_code DESC'
					)
					);
	}

	public function getTransData($fields, $conditions) {
		$this->unbindModelAll();
		return $this->find('all', array('fields' => $fields,
					'conditions' => $conditions,
					'order' => $order));
	}

	/**
	 * calculate the monthly return
	 * @param type $value Either merchantId or isoNumber, $type = field name (either merchantId or isoNumber)
	 * @return type: array
	 */
	public function getMonthsReturn($value, $type, $date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'DISTINCT LEFT(reason,3) AS return_code',
		),
					'conditions' => array(
		$type => $value,
						'and' => array('response_status="R"'),
						'and' => array(
							'Transaction.settle_date <=' => $date),
		),
					'order' => 'return_code',
		)
		);
	}

	/**
	 * calculate this month return
	 * @return type: array
	 */
	public function getMonthsReturnTotal($date) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'fields' => array(
						'DISTINCT LEFT(reason,3) AS return_code',
		),
					'conditions' => array(
						'and' => array('response_status="R"'),
						'and' => array(
							'Transaction.settle_date <=' => $date),
		),
					'order' => 'return_code',
		)
		);
	}

	/**
	 *
	 * Returns transactions amount and sum for funding report according to ODFI code
	 * @param string $fundingTime number of days
	 * @param array $transactionRange two start and end transaction id for range
	 */
	public function getFundingType($fundingTime, $transactionRange) {
		$this->unbindModelAll();
		return $this->find(
						'all', array('fields' =>
		array(
						'sum(Transaction.amount) as Codesum',
						'SettlementWarehouse.odfi_origination'),
					'joins' => array(
		array(
							'table' => 'settlement_warehouse',
							'alias' => 'SettlementWarehouse',
							'type' => 'left',
							'conditions' =>
							'SettlementWarehouse.origination_transaction_id = Transaction.transaction_id'
							),
							array('table' => 'odfi',
							'alias' => 'ODFI',
							'type' => 'left',
							'conditions' =>
							'SettlementWarehouse.odfi_origination = ODFI.ODFI_Code',
							)
							),
					'conditions' => array(
						'Transaction.funding_time' => $fundingTime,
						'Transaction.transaction_id >=' => $transactionRange['startID'],
						'Transaction.transaction_id <=' => $transactionRange['endID'],
						'SettlementWarehouse.has_settled' => 'no',
						'ODFI.Active' => 'True'
						),
					'group' => 'SettlementWarehouse.odfi_origination',
						));
	}

	/**
	 * Returns transactions amount and sum for funding report.
	 * @param array $transactionRange two start and end transaction id for range
	 */
	public function getFundingTest($transactionRange) {
		$this->unbindModelAll();
		return $this->find('all', array(
					'fields' => array(
						'sum(Transaction.amount) as Typesum',
						'count(Transaction.id) as bcount'
						, 'Transaction.funding_time as Days'),
					'joins' => array(
						array('table' => 'settlement_warehouse',
							'alias' => 'SettlementWarehouse',
							'type' => 'left',
							'conditions' => 'SettlementWarehouse.origination_transaction_id = Transaction.transaction_id'
							),
							array('table' => 'odfi',
							'alias' => 'ODFI',
							'type' => 'left',
							'conditions' => 'SettlementWarehouse.odfi_origination = ODFI.ODFI_Code',
							)
							),
					'conditions' => array(
						'Transaction.transaction_id >=' => $transactionRange['startID'],
						'Transaction.transaction_id <=' => $transactionRange['endID'],
						'SettlementWarehouse.has_settled' => 'no',
						'ODFI.Active' => 'True'),
					'group' => 'Transaction.funding_time'
					));
	}

	/**
	 * CakePHP default paginateCount() overridden to add where condition.
	 * It is called automatically when Controller calls paginate().
	 * @return Array
	 */
	public function paginateCount() {
		$params = Configure::read('paginate.params');
		$conditionArray = $params['conditions'];
		$modelUsed = $this->__ModelUsed($conditionArray);
		$this->unbindModelAll($modelUsed);
		//		unset($params['conditions']);

		unset($params['fields']);
		unset($params['order']);
		unset($params['limit']);
		return $this->find('count', $params);
	}

	/**
	 *
	 * to get models used in filter condition
	 * @param $conditionArray array list of conditions passed for filter
	 */
	private function __ModelUsed($conditionArray) {
		$modelUsed = array();
		foreach ($conditionArray as $val) {
			if ($val != '' && array_key_exists('and', $val)) {
				if ($val['and'] != null) { //this condition is given in date filters where and is given in condition
					$keySeparated = array_keys($val['and'][0]);
					$getModel = explode(".", $keySeparated[0]);
				}
			} else {
				$cntVal = count($val);
				if ($cntVal > 1) {
					for ($cnt = 0; $cnt < $cntVal; $cnt++) {
						$values[] = $val[$cnt];
					}
				} else {
					$getModel = explode(".", $val[0]);
				}
			}
			if (!in_array($getModel[0], $modelUsed, true)) {
				$modelUsed[] = $getModel[0];
			}
		}
		return $modelUsed;
	}

	/**
	 *
	 * Returns List of high dollar Transactions according to status
	 * @param array $conditions Transaction.response_status, posted_date and amount
	 * @param array $order Transaction.posted_date DESC
	 * @param string $limit 25
	 */
	public function getHighDollor($conditions = null, $order = null, $limit = null) {
		$this->unbindModelAll();
		return $this->find('all', array("fields" => array('Transaction.transaction_id', 'Transaction.cust_routing_number', 'Transaction.cust_account_number', 'Transaction.amount', 'Transaction.customer_name', 'Transaction.response_status', 'Transaction.posted_date',
						'Transaction.settle_date', 'Transaction.description', 'Transaction.reason', 'Transaction.original_transaction_id',
						'Merchant.merchantId', 'Merchant.name', 'Merchant.isoNumber',
						'Webcheck.checkNum'
						),
					'joins' => array(array('table' => 'merchants',
							'alias' => 'Merchant',
							'type' => 'left',
							'conditions' => 'Merchant.merchantId  = Transaction.merchantId',
						),
						array('table' => 'webchecks',
							'alias' => 'Webcheck',
							'type' => 'left',
							'conditions' => 'Webcheck.id = Transaction.transaction_id',
						)
						),
					'conditions' => $conditions,
					'order' => $order
						, 'limit' => $limit)
						);
	}

	public function getMonthlyTotal($date, $type) {

		$this->unbindModelAll();

		if ($date['start'] < date('Y-n-01')) {
			$result = Cache::read('newDate[' . $date['start'] . ']');
			if ($result == false) {
				$result = $this->find(
						'all', array(
					'fields' => array(
						'count(Transaction.transaction_id) as numTrans',
						'sum(Transaction.amount) as sumAmount
						'
						),
					'joins' => array(
						array('table' => 'webchecks',
							'alias' => 'Webcheck',
							'type' => 'left',
							'conditions' => 'cast(Webcheck.id as char) = Transaction.transaction_id',
						)
						),
					'conditions' => array(
						'Webcheck.interceptPost >=' => $date['start'] . ' 00:00:00',
						'Webcheck.interceptPost <=' => $date['end'] . ' 00:00:00',
						'Transaction.response_status' => array('R', 'B', 'S'),
						'Transaction.transaction_type' => $type
						)
						)
						);

						Cache::write('newDate[' . $date['start'] . ']', $result);
			}
		} else {
			$result = $this->find(
					'all', array(
				'fields' => array(
					'count(Transaction.transaction_id) as numTrans',
					'sum(Transaction.amount) as sumAmount
					'
					),
				'joins' => array(
					array('table' => 'webchecks',
						'alias' => 'Webcheck',
						'type' => 'left',
						'conditions' => 'cast(Webcheck.id as char) = Transaction.transaction_id',
					)
					),
				'conditions' => array(
					'Webcheck.interceptPost >=' => $date['start'] . ' 00:00:00',
					'Webcheck.interceptPost <=' => $date['end'] . ' 00:00:00',
					'Transaction.response_status' => array('R', 'B', 'S'),
					'Transaction.transaction_type' => $type
					)
					)
					);
		}
		return $result;
	}

	public function setPinNum() {
		$this->useDbConfig = 'echecksWrite';
		$this->create();
		$per['Transaction']['id'] = "12906196";
		$per['Transaction']['amount'] = '1600';
		$this->save($per);
	}

	public function saveTransaction($data) {
		$this->unbindModelAll();
		$this->useDbConfig = 'echecksWrite';
		$tranData = $this->find('first', array('conditions' => array('transaction_id' => $data['transaction_id'])));
		$tranData['Transaction']['response_status'] = $data['response_status'];
		return $this->save($tranData);
	}

	/**
	 * @param Array $batchDate; array('startDate'=>"YYYY-MM-DD", 'endDate'=> "YYYY-MM-DD")
	 * @return Array
	 */
	public function getReportChargeBacksIndexed($batchDate) {
		//we dont  need Merchants all detail info so unbind it form the association
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => false,
					'fields' => array(
						'isoNumber',
						'merchantId',
						'COUNT(*) as merch_trans_count',
						'SUM(amount) AS merch_trans_amt',
						'Merchant.name',
						'Merchant.funding_time'
						),
					'use' => 'rstat_sdt_resn',
					'joins' => array(
						array('table' => 'merchants',
							'alias' => 'Merchant',
							'type' => 'left',
							'conditions' => array(
								'Transaction.merchantId=Merchant.merchantId')
						),
						),
					'conditions' => array(
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.settle_date BETWEEN ? AND ?' => array(
						$batchDate['startDate'], $batchDate['endDate']),
						array('LEFT(Transaction.reason,3)="R05"
								OR LEFT(Transaction.reason,3)="R07"
								OR LEFT(Transaction.reason,3)="R08"
								OR LEFT(Transaction.reason,3)="R10"
								OR LEFT(Transaction.reason,3)="R29"')
						),
						),
					'group' => 'merchantId',
					'order' => array('merch_trans_count' => 'DESC'),
						)
						);
	}

	/**
	 *
	 * @param Array $batchDate; array('startDate'=>"YYYY-MM-DD", 'endDate'=> "YYYY-MM-DD")
	 * @return Array
	 */
	public function getReportReturnsIndexed($batchDate) {
		// unbind other association
		$this->unbindModelAll();
		//check if the user has any transactions
		return $this->find(
						'all', array(
					'autocache' => false,
					'fields' => array('Transaction.merchantId',
						'SUM(Transaction.amount) AS returns_dollars',
						'COUNT(Transaction.id) AS num_returns'),
					'use' => 'rstat_sdt_resn',
					'conditions' => array(
						'Transaction.response_status' => 'R',
						'and' => array(
							'Transaction.settle_date BETWEEN ? AND ?' => array(
		$batchDate['startDate'], $batchDate['endDate']
		),
		),
		),
					'group' => 'Transaction.merchantId'
					)
					);
	}

	/**
	 *
	 * @param Array $batchDate; array('startDate'=>"YYYY-MM-DD", 'endDate'=> "YYYY-MM-DD")
	 * @return Array
	 */
	public function getReportTransactionsIndexed($batchDate) {
		$this->unbindModelAll();
		return $this->find(
						'all', array(
					'autocache' => false,
					'fields' => array(
						'Transaction.merchantId', 'COUNT(id) AS num_transactions'
						),
					'use' => 'rstat_sdt_resn',
					'conditions' => array(
						'Transaction.response_status' => array('B', 'S', 'R'),
						'and' => array(
							'Transaction.settle_date BETWEEN ? and ?' => array(
						$batchDate['startDate'], $batchDate['endDate']
						),
						),
						),
					'group' => 'Transaction.merchantId'
					)
					);
	}

	/**
	 *
	 * to get the transactions data to be filled in submitted monthly summaries
	 * @param array $startDate
	 * @param array $endDate
	 */
	public function getTransactionDailySummary($startDate, $endDate, $transType) {
		$this->unbindModelAll();
		return $this->find('all', array(
					'fields' => array(
						'sum(Transaction.amount) as TotalAmount',
						'count(*) as CountID',
						'Transaction.transaction_type',
						'Transaction.original_transaction_id'
						),
					'joins' => array(
						array('table' => 'webchecks',
							'alias' => 'Webcheck',
							'type' => 'left',
							'conditions' => array('cast(Webcheck.id as char)
						= Transaction.transaction_id'),
						)
						),
					'conditions' => array(
						'Transaction.transaction_type' => $transType,
						'Transaction.response_status' => array("R", "B", "S"),
						'Webcheck.interceptPost >=' => $startDate . ' 06:00:00',
						'Webcheck.interceptPost <' => $endDate . ' 06:00:00',
						),
					'group' => array('Transaction.transaction_type', 'Transaction.original_transaction_id')
						));
	}

	/**
	 *
	 * @param array $batchDate;
	 * $batchDate['startDate'] Date(Y-m)
	 * $batchDate['endDate'] Date(Y-m)
	 * $postedDate Date(Y-m-d)
	 *
	 */
	public function merchTotTransNumAndSum($batchDate = null, $postedDate = null) {
		$this->unbindModelAll();
		return $this->find('all', array(
					'fields' => array(
						'Transaction.merchantId as merchant_id',
						'COUNT(*) AS num_transactions',
		),
					'use' => 's_pd',
					'joins' => array(
		array(
							'table' => 'warehouse.transaction_auxiliaries',
							'alias' => 'TransactionAuxiliary',
							'type' => 'LEFT',
							'conditions' => 'TransactionAuxiliary.transaction_id = Transaction.transaction_id'
							)
							),
					'conditions' => array(
						'Transaction.response_status' => array("B", "S", "R"),
						'Transaction.posted_date >' => $postedDate,
						'TransactionAuxiliary.id IS NOT NULL',
						'TransactionAuxiliary.originated_date BETWEEN ? and ?' => array(
							$batchDate['startDate'], $batchDate['endDate']
							)
							),
					'group' => 'Transaction.merchantId',
					'recursive' => 2
							)
							);
	}

	/**
	 * Retrieve a Min Transaction Id and Max Transaction Id for specified 
	 * dateRange and Status.
	 * 
	 * @param string $startDateTime
	 * @param string $endDateTime
	 * @param array $statuses
	 * @return array Minimum and Maximum Transaction Id
	 */
	public function getMinMaxTransID($startDateTime, $endDateTime, $statuses) {
		$this->unbindModelAll();
		$data = $this->find(
		'all',
		array('fields' =>
			array('min(Transaction.transaction_id) as startTransId',
				'max(Transaction.transaction_id) as endTransId'),
				'conditions' => array(
					'posted_date between ? and ?' => array($endDateTime,$startDateTime),
					'response_status' => $statuses
		)
		));
		return $data;
	}

	/**
	 * Retrieve a list of transaction IDs for specified $odfi, and $statuses
	 *
	 * @param string $startDateTime Starting posted date (inclusive).
	 * Format: MySQL DateTime.
	 * @param string $endDateTime Ending posted date (inclusive).
	 * Format: MySQL DateTime.
	 * @param array $odfis List of ODFIs. Example, array('BC', 'CO').
	 * @param array $statuses List of statuses. Example, array('B', 'S', 'R').
	 * @return array Find('all') results.
	 */
	public function getRandomTransPostedBetweenDates($limit, $startDateTime,
					$endDateTime, $odfis = null, $statuses = null) {
		$this->unbindModelAll();
		$conditions = array(
			'posted_date between ? and ?' => array($startDateTime, $endDateTime),
		);

		if ($odfis !== null) {
			//$conditions['Merchant.ODFI'] = $odfis;
		}

		if ($statuses !== null) {
			$conditions['response_status'] = $statuses;
		}

		$fields = array(
			'id',
			'transaction_id',
			'merchantId',
			'customer_name',
			'cust_routing_number',
			'cust_account_number',
			'account_type',
			'transaction_type',
			'amount',
			'response_status',
		//			'Merchant.isoNumber'
		);

		$query = array();
		$query['fields'] = $fields;
		$query['conditions'] = $conditions;
		$query['order'] = 'rand()';
		$query['limit'] = $limit;

		$result = $this->find('all', $query);
		return $result;
		exit();
	}

}
