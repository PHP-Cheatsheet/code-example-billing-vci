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
 * @version $$Id: ReturnedCheck.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * ReturnedCheck Model
 *
 */
class ReturnedCheck extends AppModel {

	public $useDbConfig = 'echecksRead';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'returned_checks';

/**
	* 
	* to list all the returned checks with in certain time frame
	* @param String $merchant_id
	* @param Array $batch_date
	* @return Array
	*/
	public function getReturnedCheck($merchantId, $batchDate) {
		$this->unbindModelAll();
		//check if the user has any transactions
		//SELECT COUNT(*) AS change_notifcations FROM $database_myDB.returned_checks
		//WHERE reason = 'C' AND merchantId='$merchantId'
		//AND return_date BETWEEN '$last_month_first_date' AND '$this_month_first_date'"
		/*
		$returnedcheck_list = $this->query('
		SELECT * FROM returned_checks
		WHERE merchantId="'.$merchant_id.'"
		AND reason="C"
		AND return_date BETWEEN "'. $batch_date['last_month_first_date'] .'" AND "'.$batch_date['this_month_first_date'].'"');
		*/
		$queryReturnArray = $this->find(
		'all',
		array(
			'fields' => array('transaction_id' => 'transaction_id',
							'amount' => 'amount'),
			'conditions' => array(
			'ReturnedCheck.merchantId' => $merchantId,
			'ReturnedCheck.reason' => 'C',
			'and' => array('ReturnedCheck.return_date < ' => $batchDate['this_month_first_date'],
					'ReturnedCheck.return_date >= ' => $batchDate['last_month_first_date']
						)
			)
			)
		);
		return $queryReturnArray;
	}
}
