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
 * @version $$Id: Merchant.php 1549 2013-09-13 05:40:30Z anit $$
 */

App::uses('AppModel', 'Model');

/**
 * Merchant Model
 *
 */
class Merchant extends AppModel {

		public $useDbConfig = 'echecksRead';

		/**
		 * Use table
		 *
		 * @var mixed false or table name
		 */
		public $useTable = 'merchants';

		/**
		 * Primary key field
		 *
		 * @var string
		 */
		public $primaryKey = 'merchantId';

		/**
		 * Display field
		 *
		 * @var string
		 */
		public $displayField = 'name';
		//had some problems updating merchant data while using cache  so commented -- anit
		public $actsAs = array(
				'Containable',
						//'Autocache.Autocache'
		);
		public $hasOne = array(
				'BillingMerchantFee' => array('className' => 'BillingMerchantFee',
						'foreignKey' => 'merchantId',
//						'conditions' => array ('BillingMerchantFee.merchantId = Merchant.merchantId')
				),
				'NegfileVelocityParam' => array('className' => 'NegfileVelocityParam',
						'foreignKey' => 'merchantId',
//						'conditions' => array ('NegfileVelyParam.merchantId = Merchant.merchantId')
				),
				'BillingTieredValue' => array('className' => 'BillingTieredValue',
						'foreignKey' => 'merchantId',
//						'conditions' => array ('BillingTieredValue.merchantId = Merchant.merchantId')
				),
				'MerchantsAchParam' => array('className' => 'MerchantsAchParam',
						'foreignKey' => 'merchantId',
//						'conditions' => array ('MerchantsAchParam.merchantId = Merchant.merchantId'),
//						'dependent' => true
				),
				'MerchantStatus' => array('className' => 'MerchantStatus',
						'foreignKey' => 'merchantId',
//						'conditions' => array ('MerchantsAchParam.merchantId = Merchant.merchantId'),
//						'dependent' => true
				),
		);

		public $hasMany = array(
				'MerchantHistory' => array(
						'className' => 'MerchantHistory',
						'foreignKey' => 'merchantId',
						'order' => 'post_date DESC',
				),
				'MerchantSecRestriction' => array(
						'className' => 'MerchantSecRestriction',
						'foreignKey' => 'merchantId',
				),
		);
		public $validate = array(
				'interceptMerchId' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'interceptPIN' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'fedId' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'name' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Merchant Name.'
				),
				'name_short' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Name.'
				),
				'address1' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Address.'
				),
				'address2' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'message' => 'Please Enter a valid Address.'
				),
				'city' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'message' => 'Please Enter a valid Name.'
				),
				'zip' => array(
						'rule' => array('postal', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid ZIP Code.'
				),
				'phoneNum' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Phone Number.'
				),
				'support_phone' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Phone Number.'
				),
				'faxNum' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Fax Number.'
				),
				'contactName' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
				),
				'email' => array(
						'rule' => array('email', true),
						'message' => 'Please supply a valid email address.'
				),
				'email2' => 'email',
				'url' => array(
						'rule' => 'url',
						'message' => 'Invalid url.'
				),
				'principal_name' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Principal Name.'
				),
				'principal_phone' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Phone Number.'
				),
				'principal_email' => array(
						'rule' => array('email', true),
						'message' => 'Please supply a valid email address.'
				),
				'account_exec' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Name.'
				),
				'account_exec_phone' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Phone Number.'
				),
				'account_exec_email' => array(
						'rule' => array('email', true),
						'message' => 'Please supply a valid email address.'
				),
				'transaction_type' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Transaction Type.'
				),
				'industry' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'required' => true,
						'message' => 'Please Enter a valid Word.'
				),
				'products_services' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/'
						),
						'required' => true,
						'message' => 'Please Enter a valid Word.'
				),
				'SIC' => array(
						'rule' => 'alphaNumeric',
						'required' => true,
						'message' => 'Only Letters or Numbers.'
				),
				'NAICS' => array(
						'rule' => 'alphaNumeric',
						'required' => true,
						'message' => 'Only Letters or Numbers.'
				),
				'activation_charge' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'activation_charge_other' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'activation_charge_other_notes' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Note',
				),
				'bank_charge' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'bank_charge_other' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'bank_charge_other_notes' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Note',
				),
				'billingRoutingNumber' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
						),
						'required' => true,
				),
				'billingAccountNumber' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
						),
						'required' => true,
				),
				'billingEmail' => array(
						'rule' => array('email', true),
						'message' => 'Please supply a valid email address.'
				),
				'fileEditFee' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'dialConvGaurPct' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'dialConvFee' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'dialConvPct' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'redepRCKDays' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'redepRCKRetry' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'altMerchantId' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => array('maxlength', 15),
						),
						'message' => 'Can be only 15 character'
				),
				'usaepay_key' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Words.',
				),
				'yearsInBiz' => array(
						'rule' => array(
								'rule1' => 'numeric',
								'rule2' => array('maxlength', 11),
						),
						'message' => 'Can be only upto 11 character'
				),
				'transPerMonth' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'amtPerMonth' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'transLowAmt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'transAvgAmt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'transHighAmt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				//-------------------------------------------Block 10-------------------------------------------------------//
				'funding_change_reason' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Word',
				),
				'bankRouteNum' => array(
						'ifInValid' => array(
								'rule' => array('ifInValid'),
								'message' => "Please Enter the valid Routing Number and Confirm the number"
						),
						'identicalFieldValues' => array(
								'rule' => array('identicalFieldValues', 'bankRouteNum2'),
								'message' => "Please confirm this merchant's Routing Number twice so that the values match"
						),
				),
				'bankAcctNum' => array(
						'identicalFieldValues' => array(
								'rule' => array('identicalFieldValues', 'confirm_bankAcctNum'),
								'message' => "Please confirm this merchant's Account Number twice so that the values match"
						)
				),
				'feePostTrans' => array(
						'rule' => array('validateCheck'),
						'message' => "You may only select 'Fee included in base' OR 'Create Additional Fee Item', but not both."
				),
				'feeAlterTrans' => array(
						'rule' => array('validateCheck'),
						'message' => "You may only select 'Fee included in base' OR 'Create Additional Fee Item', but not both."
				),
				'bankName' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Name',
				),
				'bankAddress1' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Address',
				),
				'bankAddress2' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Address',
				),
				'bankCity' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Address',
				),
				'bankZip' => array(
						'rule' => array('postal', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid ZIP Code.'
				),
				'bankContact' => array(
						'rule' => array(
								'rule1' => 'alphaNumeric',
								'rule2' => '/([\w.-]+ )+[\w+.-]/',
						),
						'message' => 'Please Enter a valid Contact name.'
				),
				'bankPhoneNum' => array(
						'rule' => array('phone', null, 'us'),
						'required' => true,
						'message' => 'Please Enter a valid Phone Number.'
				),
				//-------------------------------------------Block 11-------------------------------------------------------//
				'feePostAmt' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'feePostDiscount' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'feeAddAmount' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
				'feeAddDiscount' => array(
						'rule' => 'numeric',
						'required' => true,
						'message' => 'Can Only Be Numbers'
				),
		);

		function ifInValid() {
				if ($this->data['Merchant']['bankRouteNum'] == null ||
								empty($this->data['Merchant']['bankRouteNum'])) {
						return false;
				}
				return true;
		}

		/**
		 * 
		 * Validation to check FeePostTras and fee AlterTrans
		 */
		function validateCheck() {
				if (($this->data['Merchant']['feePostTrans'] == 1) &&
								($this->data['Merchant']['feeAlterTrans'] == 1)) {
						return false;
				}
				return true;
		}

		/**
		 * 
		 * Rules to compare the two fileds
		 * @param first param $field
		 * @param second param $compare_field
		 */
		function identicalFieldValues($field = array(), $compare_field = null) {
				foreach ($field as $key => $value) {
						$v1 = $value;
						$v2 = $this->data[$this->name][$compare_field];
						if ($v1 !== $v2) {
								return false;
						} else {
								continue;
						}
				}
				return true;
		}

		/**
		 * 
		 *  
		 */
		public function getBilledMerchants() {
				$this->unbindModelAll();
			$merchantsList =
							$this->find('all', array(
					'fields' => '*',
					'joins' => array(
							array('table' => 'billing_merchant_fees',
									'alias' => 'BillingMerchantFee',
									'type' => 'left',
									'conditions' => array('BillingMerchantFee.merchantId=Merchant.merchantId')
							)
					),
					'conditions' => array(
							'BillingMerchantFee.auto_billing' => '1',
							'BillingMerchantFee.bill_iso' => '0',
							'Merchant.active' => '1',
							'Merchant.referenceNum' => 'USA E Pay'
					),
					'order' => array('Merchant.merchantId' => 'DESC')
				)
			);
			return $merchantsList;
		}

		public function getMerchMP($lastMP = null) {
				$this->unbindModelAll();
				$lastMP = $lastMP == null ? 0 : $lastMP;
				return $this->find('all', array('fields' => array('name', 'contactName', 'merchantId'),
										'conditions' => array('merchantId > ' => $lastMP)
				));
		}

		public function getAssignedMerchants($conditions = null) {
				$this->unbindModelAll();
				return $this->find('all', array('conditions' => array('OR' => array($conditions))));
		}

		public function getData($id = null) {
				$this->unbindModelAll();
				return $this->find('all', array('fields' => array('state', 'interceptEntryClass'),
										'conditions' => array('merchantId ' => $id)));
		}

		/**
		 * get all the details for the merchant.
		 * @param type $merchantId
		 * @return type
		 */
		public function getMerchantsDetails($merchantId = null) {
				$this->unbindModelAll();
				//check if the user has any transactions
				return $this->find(
												'all', array(
										'fields' => array(
												'Merchant.*',
												'BillingMerchantFee.*',
												'BillingTieredValue.*',
												'Iso.*',
												'MerchantStatus.*'
										),
										'joins' => array(
												array(
														'table' => 'billing_merchant_fees',
														'alias' => 'BillingMerchantFee',
														'type' => 'left',
														'conditions' => array(
																'BillingMerchantFee.merchantId=Merchant.merchantId')),
												array(
														'table' => 'billing_tiered_values',
														'alias' => 'BillingTieredValue',
														'type' => 'left',
														'conditions' => array(
																'BillingTieredValue.merchantId=Merchant.merchantId')),
												array(
														'table' => 'iso',
														'alias' => 'Iso',
														'type' => 'left',
														'conditions' => array(
																'BillingMerchantFee.merchantId=Merchant.merchantId')),
												array(
														'table' => 'merchant_status',
														'alias' => 'MerchantStatus',
														'type' => 'left',
														'conditions' => array(
																'MerchantStatus.merchantId=Merchant.merchantId')),
												array(
														'table' => 'negfile_velocity_params',
														'alias' => 'NegfileVelocityParam',
														'type' => 'left',
														'conditions' => array(
																'NegfileVelocityParam.merchantId=Merchant.merchantId')
												),
												array(
														'table' => 'merchants_ach_params',
														'alias' => 'MerchantsAchParam',
														'type' => 'left',
														'conditions' => array(
																'MerchantsAchParam.merchantId=Merchant.merchantId')),
										),
										'conditions' => array(
												'Merchant.merchantId ' => $merchantId
										),
										'group' => 'Merchant.merchantId',
										'order' => array('Merchant.merchantId' => 'ASC'),
												)
				);
		}

		public function getdetail($value) {
				$this->unbindModelAll();
				return $this->find(
												'first', array(
										'fields' => array(
												'merchantId',
												'funding_time'
										),
										'conditions' => array(
												'merchantId' => $value
										)
												)
				);
		}

		public function getActiveMerchant($isoNumber) {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => array(
												'COUNT(*) AS num_merchants'
										),
										'conditions' => array('isoNumber' => $isoNumber,
												'active' => '1'
										),
										'group' => 'isoNumber'
												)
				);
		}

		public function getActiveMerchantNew() {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'autocache' => false,
										'fields' => array(
												'isoNumber',
												'issueDate',
												'deactivation_date',
												'Iso.name',
												'Iso.phone_w',
												'active'
										),
										'joins' => array(
												array(
														'table' => 'iso',
														'alias' => 'Iso',
														'type' => 'left',
														'conditions' => 'Merchant.isoNumber = Iso.isoNumber'
												)
										),
										'order' => 'isoNumber',)
				);
		}

		public function getNewMerchant($isoNumber, $issueDate = null) {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => array(
												'COUNT(*) AS num_new_merchants'
										),
										'conditions' => array(
												'active' => '1',
												'issueDate >=' => $issueDate
										)
												)
				);
		}

		public function getclosedMerchant($isoNumber, $dactiveDate = null) {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => array(
												'COUNT(*) AS num_closed_merchants'
										),
										'conditions' => array('isoNumber' => $isoNumber,
												'active' => '1',
												'deactivation_date >= ' => $dactiveDate
										)
												)
				);
		}

		/**
		 * calculate total merchants on a monthly basis
		 * @param type $dateRange
		 * @return type Array
		 */
		public function getMercNumAndSum($dateRange = null) {
				//SELECT COUNT(merchants.merchantId) AS total_merchants,MONTH(issueDate) as current_month FROM echecks.merchants WHERE active = 1 AND issueDate >='2012-01-01 00:00:00' AND issueDate < '2013-04-01 00:00:00' GROUP BY YEAR(issueDate),MONTH(issueDate)
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => array(
												' COUNT(merchantId) AS TotalMerchants, DATE(issueDate) as ReportDate'
										),
										'conditions' => array(
												'active' => 1,
												'and' => array(
														'issueDate >=' => $dateRange['min'],
														'issueDate <' => $dateRange['max'])
										),
										'group' => 'YEAR(`issueDate`), MONTH(issueDate)',
										'order' => 'DATE(`issueDate`) ASC'
												)
				);
		}

		/**
		 * fetch merchants having ODFI code
		 * @param type $ODFI code
		 * @return type Array
		 */
		public function getRelatedData($code) {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => 'merchantId',
										'conditions' => array(
												'ODFI' => $code
										)
												)
				);
		}

		public function getRelatedDataNew() {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'autocache' => true,
										'fields' => array('merchantId', 'ODFI')
												)
				);
		}

		/**
		 * calculate total merchants on basis of conditions passed
		 * @param type $isonum for monitorActivities controller
		 * @return type Array
		 */
		public function getMerchants($conditions, $order, $dateRange) {
				$this->unbindModelAll();
				return $this->find('all', array('autocache' => true,
										'fields' => array(
												'merchantId',
												'name',
												'phoneNum',
												'email',
												'interceptPIN',
												'isoNumber',
												'COUNT(`Transaction`.`id`) AS numTransactions',
												'SUM(`Transaction`.`amount`) AS totalAmount'
										),
										'joins' => array(
												array('table' => 'transactions',
														'alias' => 'Transaction',
														'type' => 'left',
														'conditions' => array(
																'Merchant.merchantId = Transaction.merchantId',
																'Transaction.posted_date >=' => $dateRange['startDate'],
																'Transaction.posted_date <' => $dateRange['endDate']
														)
												)
										),
										'conditions' => $conditions,
										'group' => 'Merchant.merchantId',
										'order' => $order));
		}

		public function getIsoMerchants($conditions) {
				$this->unbindModelAll();
				return $this->find('all', array(
										'fields' => array(
												'merchantId',
												'name',
												'isoNumber'
										),
										'conditions' => $conditions,
												)
				);
		}

		/**
		 * 
		 * get last months sum and total of transaction with respectinve to merchants
		 * @param $conditions
		 * @param $dateRange
		 */
		public function getLastMonthMerchantActivity($conditions, $dateRange) {
				$this->unbindModelAll();
				return $this->find('all', array(
										'fields' => array(
												'merchantId',
												'name',
												'MonthlyMerchantSummary.originated_count_num',
												'MonthlyMerchantSummary.originated_sum_amount',
										),
										'joins' => array(
												array('table' => 'Warehouse.monthly_merchant_summaries',
														'alias' => 'MonthlyMerchantSummary',
														'type' => 'left',
														'conditions' => array(
																'Merchant.merchantId = Warehouse.MonthlyMerchantSummary.merchant_id',
																'Warehouse.MonthlyMerchantSummary.month >=' =>
																date('Ym', strtotime($dateRange['startDate'])),
																'Warehouse.MonthlyMerchantSummary.month <=' =>
																date('Ym', strtotime($dateRange['endDate'])),
														)
												)
										),
										'conditions' => $conditions,
												)
				);
		}

		/**
		 * fetch merchants
		 *
		 * @return type Array
		 */
		public function getMerchantName() {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'autocache' => true,
										'fields' => array('name', 'merchantId')
												)
				);
		}

		/**
		 * Validate and update merchant configuration information. 
		 * @param Array $data
		 */
		public function saveMerchantConfig($data) {
				$this->useDbConfig = 'echecksWrite';

				if (!empty($data)) {
						if ($this->validateAssociated($data)) {
								//Save MerchantsAchParam data.
								$this->request->data['MerchantsAchParam']['id'] = $this->MerchantsAchParam->id;
								$this->MerchantsAchParam->useDbConfig = 'echecksWrite';

								//Save NegfileVelocityParam data.
								$this->request->data['NegfileVelocityParam']['id'] = $this->NegfileVelocityParam->id;
								$this->NegfileVelocityParam->useDbConfig = 'echecksWrite';

								//Save BillingMerchantFee data.
//					$this->request->data['BillingMerchantFee']['id'] = $this->BillingMerchantFee->id;
								$this->BillingMerchantFee->useDbConfig = 'echecksWrite';

								//Save BillingMerchantFee data.
								$this->request->data['BillingTieredValue']['id'] = $this->BillingTieredValue->id;
								$this->BillingTieredValue->useDbConfig = 'echecksWrite';

								//Save MerchantStatus data.
								$this->request->data['MerchantStatus']['id'] = $this->MerchantStatus->id;
								$this->MerchantStatus->useDbConfig = 'echecksWrite';


								//Save MerchantSecRestriction data.
								$this->MerchantSecRestriction->useDbConfig = 'echecksWrite';
								$this->MerchantSecRestriction->deleteAll(array('merchantId' => $data['Merchant']['merchantId']), false);

								//Save MerchantHistory data.
								$this->MerchantHistory->useDbConfig = 'echecksWrite';

								//Save all associated data
								return $this->saveAssociated();
						}
				}
		}
		/**
		 * Get active or inactive merchants in ISO
		 * 
		 * @param boolean $activeStatus
		 * 
		 * @param array $condtn
		 */
		public function getActiveOrDeactveMerchsInISO($activeStatus, $condtn = null) {
				$this->unbindModelAll();
				return $this->find(
												'all', array(
										'fields' => array(
												'isoNumber as iso_number',
												'COUNT(*) AS num_merchants'
										),
										'conditions' => array('active' => $activeStatus, $condtn),
										'group' => 'isoNumber',
										'order' => 'isoNumber ASC'
												)
				);
		}

}