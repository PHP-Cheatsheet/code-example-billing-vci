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
 * @version $$Id: MerchantsAchParam.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * MerchantsAchParam Model
 *
 */
class MerchantsAchParam extends AppModel {

	/**
	 * Use database config
	 *
	 * @var string
	 */
	public $useDbConfig = 'echecksRead';

	public $validate = array(
					'warn_pct_over_monthly_trans_vol' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'warn_pct_over_monthly_trans_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'warn_pct_over_trans_high' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'warn_pct_under_trans_low' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'warn_pct_trans_avg_variance' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					
					'decline_pct_over_monthly_trans_vol' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'decline_pct_over_monthly_trans_amt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'decline_pct_over_trans_high' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'decline_pct_under_trans_low' => array(
						'rule' => 'numeric',
						'required' => true,
						'message'  => 'Can Only Be Numbers'
					),
					'decline_pct_trans_avg_variance' => array(
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
			$condition = array('merchantId LIKE' => "%default%");
		}
		return $this->find(
						'all',
		array('fields' => array('*'),
			'conditions' => $condition
		));
	}

	public function updateConfig($id,$saveData) {
		$this->useDbConfig = 'echecksWrite';
		$existsID = $this->find(
						'all', array('fields' => array('*'),
									'conditions' => array('merchantId ' => $id)
		));
		if (isset($existsID) && $existsID != null) {
			$saveData['id'] = $existsID[0]['MerchantsAchParam']['id'];
			$this->save($saveData);
		} else {
			$saveData['merchantId'] = $id;
			$this->create();
			$this->save($saveData);
		}
	}
}
