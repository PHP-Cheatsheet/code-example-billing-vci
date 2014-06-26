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
 * @version $$Id: BillingTieredValue.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * BillingTieredValue Model
 *
 */
class BillingTieredValue extends AppModel {

	public $useDbConfig = 'echecksRead';

/**
 * Primary key field
 *
 * @var string
 * PREVIOUS LOGIC
 *        
 */
	public $primaryKey = 'merchantId';

	public $validate = array(
					'tier_1_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_transaction_1' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'tier_2_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_transaction_2' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_transaction_3' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					
					'tier_1_check_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_check_fee_percent_1' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'tier_2_check_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_check_fee_percent_2' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'per_check_fee_percent_3' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					
					
	);				
/**
 * 
 * Enter description here ...
 * @param unknown_type $merchant_id
 * PREVIOUS LOGIC
 * //echo '<br />615 : '.$query8 = "SELECT * FROM $database_myDB.billing_tiered_values WHERE merchantId='$merchantId'"; 
 */
	public function getTiredBillsOfMerchant($merchantId) {
		$queryReturnAarray = $this->find(
		'all',
			array(
				'conditions' => array(
								'merchantId' => $merchantId
								)
			)
		);
		return $queryReturnAarray;
	}

	
	/**
	 * 
	 * Find the details of passed merchantId and edit or insert accordingly.
	 * @param array $merchID
	 */
	public function BillingTieredValueMerch($values) {
		$this->useDbConfig = 'echecksWrite';
		$this->save($values);
	}
}
