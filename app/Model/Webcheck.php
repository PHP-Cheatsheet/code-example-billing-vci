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
 * @version $$Id: Webcheck.php 1404 2013-08-27 11:53:14Z deena $$
 */

App::uses('AppModel', 'Model');
/**
 * Webcheck Model
 *
 */
class Webcheck extends AppModel {

	public $useDbConfig = 'echecksWrite';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'merchantId' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'bankRouteNum' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'bankAccountNum' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'referenceNum' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'entered' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

//	public $actsAs = array( 
//		'Autocache.Autocache' => 
//		array('default_cache' => 'daily')
//	);
	
/**
 * 
 * @param String $merchantId
 * @param Array $batchDate
 * @todo Is this function being used? It doesn't return anything. It should be removed if it is not being used.
 *		If this function needs to be used then, do not use webchecks.interceptPost; use TransactionAuxiliaries. 
 *		Rewrite in Transactions with TransactionAuxiliaries joined.
 * 
 */
	public function getWebchecks($merchantId, $batchDate) {
		//pr($batchDate);
		//unbinf from all associaitn
		$this->unbindModelAll();
		//check if the user has any transactions
		/*
		orginal query
		echo '<br /> 721 : '.$query = "SELECT COUNT(id) AS num_transactions,SUM(amount) AS num_dollars
		FROM $database_myDB.webchecks
		WHERE merchantId='$merchantId'
		AND interceptPost BETWEEN '$last_month_first_date' AND '$this_month_first_date'
		AND (status='B' OR status='S' OR status='R')";
		*/
		//cakes AND OR BETWEEN does not work properly
		// the exact conditions are not implemented when used in find query

		$transactionsList = $this->find(
		'list',
		array('fields' => array(' COUNT(Webcheck.id) AS num_transactions', 'SUM(Webcheck.amount) AS num_dollars'),
				'conditions' => array(
				'merchantId' => $merchantId,
				'and' => array('interceptPost <= ' => $batchDate['this_month_first_date'],
				'interceptPost >= ' => $batchDate['last_month_first_date']
						),
						'status' => array('B', 'S', 'R')
			)
			)
		);

		//this is the executed query
		// Query : SELECT  COUNT(`Webcheck`.`id`) AS num_transactions, SUM(`Webcheck`.`amount`) AS num_dollars FROM `portal`.`webchecks` AS `Webcheck`   WHERE `merchantId` = '6534' AND ((`interceptPost` <= '2013-02-01') AND (`interceptPost` >= '2013-01-01')) AND `status` IN ('B', 'S', 'R')

		/*
		$transactionsList = $this->query('
		SELECT COUNT(id) AS num_transactions,SUM(amount) AS num_dollars
		FROM webchecks
		WHERE merchantId="'.$merchantId.
		'" AND interceptPost BETWEEN "'.$batchDate['last_month_first_date'].' 00:00:00" AND "'.$batchDate['this_month_first_date'].' 00:00:00" '.
		' AND (status="B" OR status="S" OR status="R")');
		*/
		//return $transactionsList[0][0];
	}

	/**
	 * @todo Remove unneccessary comments.
	 */
	public function getMinTransId($monthStartDate) {
		// SELECT MIN(id) AS min_transaction_id FROM $database_myDB.webchecks WHERE entered >= ' $this_month_start_date'
		//'fields' => array('MIN(id) as min_transaction_id'
		//echo $monthStartDate ; die;
		//$this->unbindModelAll();
		return $transList = $this->find('all',array('fields' => array('MIN(id) as minTransId'),
				'conditions' => array(
					'entered <' => $monthStartDate
					)
			)
		);
		//print_r($transList);
	}

/**
 * get total billed amount and qty and  with status B,R,S
 * @deprecated Use DailyIsoSummary->getTotalOriginated().
 */
	public function getTotalBillData($iso, $merchants, $startDate, $endDate) {
		//		$query = "SELECT COUNT(id) AS total_billed,SUM(amount) AS total__billed_dollars FROM $database_myDB.webchecks
		//WHERE interceptPost >= '$this_month_start_date' AND interceptPost < '$next_month_start_date' AND (status!='E' AND status!='D' AND status!='A' AND status!='V')";
		//	if( isset($isoNumber) && $isoNumber != NULL && $isoNumber != "") {
		//		$query = "SELECT COUNT(id) AS total_billed,SUM(amount) AS total__billed_dollars FROM $database_myDB.webchecks
		//LEFT JOIN $database_myDB.merchants ON (webchecks.merchantId=merchants.merchantId) WHERE merchants.isoNumber='$isoNumber
		//' AND interceptPost >= '$this_month_start_date' AND interceptPost < '$next_month_start_date' AND
		//(status!='E' AND status!='D' AND status!='A' AND status!='V')";
		//		}
		//	if( isset($merchantId) && $merchantId != NULL && $merchantId != "") {
		//		$query = "SELECT COUNT(id) AS total_billed,SUM(amount) AS total__billed_dollars FROM $database_myDB.webchecks WHERE merchantId='$merchantId' AND interceptPost >= '$this_month_start_date' AND interceptPost < '$next_month_start_date' AND (status!='E' AND status!='D' AND status!='A' AND status!='V')";
		//		}
		$this->unbindModelAll();
		$totalBilled = $this->find('all', array('fields' => array('COUNT(id) AS totalBilled', 'SUM(amount) AS totalBilledDollars'),
				'conditions' => array('AND' => array('interceptPost >= ' => $startDate,
													'interceptPost <= ' => $endDate),
													'status' => array('B', 'S', 'R')),
				array('autocache' => true)
		));
		return $totalBilled;
	}

/**
 * get webchecks data as per the conditions passed
 * // poorly named
 */
	public function getAssignedWebchecks($conditions = null) {
		$this->unbindModelAll();
		return $this->find('all',
			array('fields' => array('COUNT(*) AS numTransactions'),'conditions' => $conditions, array('autocache' => true)));
	}

	/**
	 * 
	 * @deprecated Use DailyIsoSummary->getTotalOriginated().
	 */
	public function getMonthlyTotal($date,$type) {
		$this->unbindModelAll();
		return $this->find(
		'all',
				array(
					'fields' => array(
									'count(Webcheck.id) as numTrans',
									'sum(Webcheck.amount) as sumAmount
									'
								),
					'joins' => array( array('table' => 'transactions',
											'alias' => 'Transaction',
											'type' => 'left',
											'conditions' => 'Transaction.transaction_id = cast(Webcheck.id as char)',
										)
								),
					'conditions' => array(
									'Webcheck.interceptPost >=' => $date['start'].' 00:00:00',
									'Webcheck.interceptPost <=' =>  $date['end'].'00:00:00',
									'Webcheck.status' => array('R', 'B', 'S'),
									'Transaction.transaction_type' => $type
									)
					)
		);
	}
	
	public function saveweb($data) {
		$this->useDbConfig = 'echecksWrite';
		return $this->save($data);
	}

}
