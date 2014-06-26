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
 * @version $$Id: SettlementTransaction.php 1395 2013-08-26 02:50:18Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * SettlementTransaction Model
 *
 */
class SettlementTransaction extends AppModel {

	public $useDbConfig = 'echecksRead';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'transaction_id';
/**
	* List Settlement Transactions between two date limits
	* 
	* @param String $merchant_id
	* @param Array $batch_date
	* @return Array
	*/
	public function getSettlements($merchantId, $batchDate) {
		$this->unbindModelAll();
		$queryReturnArray = $this->find(
		'all',
		array(
			'fields' => array('transaction_id' => 'transaction_id',
							'amount' => 'amount'),
			'conditions' => array(
			'SettlementTransaction.MID' => $merchantId,
			'and' => array('SettlementTransaction.posted_date < ' =>
					$batchDate['this_month_first_date'],
				'SettlementTransaction.posted_date >= ' =>
					$batchDate['last_month_first_date']
						)
			)
			)
		);
		return $queryReturnArray;
	}

}
