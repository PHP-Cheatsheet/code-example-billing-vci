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
 * @version $$Id: NegfileVelocityParam.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * NegfileVelocityParam Model
 *
 */
class NegfileVelocityParam extends AppModel {

	/**
	 * Use database config
	 *
	 * @var string
	 */
	public $useDbConfig = 'echecksRead';

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'id';

	public $validate = array(
					'merchant_daily_max_checks' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'merchant_daily_amount' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'merchant_days_in_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'merchant_max_checks_in_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'merchant_amount_per_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					
					'overall_daily_max_checks' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'overall_daily_amount' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'overall_days_in_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'overall_max_checks_in_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'overall_amount_per_period' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'return_dupe_check' => array(
						'rule' => array(
										'rule1' => 'numeric',
										'rule2' => array('maxlength',9),
								),
						'message' => 'Can be only upto 9 Number'
						
					),
					'return_max_checks_overall' => array(
						'rule' => array(
										'rule1' => 'numeric',
										'rule2' => array('maxlength',9),
								),
						'message' => 'Can be only upto 9 Number'
					),
					'return_max_checks_merchant' => array(
						'rule' => array(
										'rule1' => 'numeric',
										'rule2' => array('maxlength',9),
								),
						'message' => 'Can be only upto 9 Number'
					),
					'processing_monthly_base_fee' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'processing_transaction_fee' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					
					
	);				

	public function getMerchantsDefault($merchantId = null) {
		$existsID = $this->find(
						'all',
		array('fields' => array('*'),
									'conditions' => array('merchantId ' => $merchantId)
		));
		$condition = "";
		if (isset($existsID) && $existsID != null) {
			$condition = array('merchantId ' => $merchantId);
		} else {
			$condition = array('merchantId lIKE' => "default");
		}
		return $this->find(
						'all',
		array('fields' => array('*'),
									'conditions' => $condition
		));
	}


	/**
	 * insert or edit
	 * @param Pointer $controller UsersController Pointer
	 */
	public function updateConfig($saveData) {
		$this->useDbConfig = 'echecksWrite';
		$existsID = $this->find(
						'all', array('fields' => array('*'),
									'conditions' => array('merchantId ' => $saveData['merchantId'])
		));
		if (isset($existsID) && $existsID != null) {
			$saveData['id'] = $existsID[0]['NegfileVelocityParam']['id'];
			$this->save($saveData);
		} else {
			$this->create();
			$this->save($saveData);
		}
	}

}
