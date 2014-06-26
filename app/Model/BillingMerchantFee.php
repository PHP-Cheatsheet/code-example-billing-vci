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
 * @version $$Id: BillingMerchantFee.php 1410 2013-08-28 08:25:31Z sisir $$
 */

App::uses('AppModel', 'Model');
App::uses('Merchant', 'Model');

/**
 * BillingMerchantFee Model
 *
 * @property Merchant $Merchant
 */
class BillingMerchantFee extends AppModel {

	public $useDbConfig = 'echecksRead';

	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'merchantId';
	
	/**
	* Primary key field
	*
	* @var string
	*/
	public $primaryKey = 'merchantId';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array(
		'Merchant' => array(
			'className' => 'Merchant',
			'foreignKey' => 'merchantId',
			'fields' => 'Merchant.*',
			'conditions' => array ()));

	public $validate = array(
		'webConvGaurFee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'webConvGaurPct' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'webConvFee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'webConvPct' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'minimum_fee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'stmtFee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
//		'dialConvGaurFee' => array(
//			'rule' => 'numeric',
//			'required' => true,
//			'message'  => 'Can Only Be Numbers'
//		),
		'merchDeclTransFee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
		'transacteeDeclTransFee' => array(
			'rule' => 'numeric',
			'required' => true,
			'message'  => 'Can Only Be Numbers'
		),
	);

	/**
	 * Default value from BillingMerchantFee Table
	 * 
	 */
	public function getMerchantsDefault($merchantId = null) {
		$condition = array('BillingMerchantFee.merchantId LIKE' => "%default%");
		return $this->find(
						'all',
		array('fields' => array('*'),
			'conditions' => $condition
		));
	}

	/**
	 * Find the details of passed merchantId and edit or insert accordingly.
	 * @param array $merchID
	 */
	public function saveMerchantEdit($merchID,$bilData){
			$this->useDbConfig = 'echecksWrite';
			$this->save($bilData);
	}

	/**
	 * Fetch the data from echecks.merchants table and Insert data into echecks.billing_merchant_fees table.
	 */
	public function generateAndinsertData() {
		$merchant = new Merchant();

		//$query['limit'] = '5';

		$merchData = $merchant->find('all', $query);
		foreach($merchData as $datum) {
			$data[] = $this->__generateData($datum);
		}
		$saveData = $this->__checkData($data);
	
		if(!empty($saveData['savingData'])) {
			$this->__insertData($saveData['savingData']);
		}
		if(!empty($saveData['exceptionData'])) {
			$count = count($saveData['exceptionData']);
			echo  "\n" . $count  .' rows already exist ' ;
		} 

	}

	/**
	 * Fetch the data from echecks.merchants table for the same field that exist in the echecks.billing_merchant_fees table.
	 * 
	 * @param array $data : data for single merchant, fetched from merchants and its associated table.
	 */
	private function __generateData($data) {
		$fieldsBillingMerchantFee = array_keys($data['BillingMerchantFee']);
		
		foreach ($data['Merchant'] as $keys => $vals) {
				if (in_array($keys,$fieldsBillingMerchantFee)) {
					$fetchedData[$keys] = $vals;
				} 
			} 
		$fetchedData['auto_billing'] = 1;
		$fetchedData['bill_iso'] = 0;
		$insertData = $this->__orderFetchData($fetchedData, $fieldsBillingMerchantFee);
		
		return $insertData;
	}

	/**
	 * Ordering the data in sequential order to insert in database table
	 * 
	 * @param array $data : required data for the echecks.billing_merchant_fees table.
	 * @param array $billdata : fields name of the table in order.
	 */
	private function __orderFetchData($data, $billdata) {
			foreach($billdata as $billdatum) {
				$returnData[$billdatum] = $data[$billdatum];
			}

			return $returnData;
	}

	/**
	 * Insert the data into table.
	 *
	 * @param array $data (All Fetched data)
	 */
	private function __insertData($data) {
		$this->useDbConfig = 'echecksWrite';
		$this->saveMany($data);
		echo count($data).' rows inserted';
	}

	/**
	 * Check if the data already exist in the echecks.billing_merchant_fees table.
	 *
	 * @param array $data (All Fetched data)
	 */
	private function __checkData($data) {
		$query['fields'] = 'merchantId';

		$query['order'] = 'merchantId DESC';

		$billMerchData = $this->find('first', $query);

		foreach ($data as $datum) {
			if($datum['merchantId'] > $billMerchData['BillingMerchantFee']['merchantId']) {
				$savingData[] = $datum;
			} else {
				$exceptionData[] = $datum['merchantId']; 
			}
		}

		return array('savingData' => $savingData, 'exceptionData' => $exceptionData);
	}

}