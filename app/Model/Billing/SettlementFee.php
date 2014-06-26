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
 * @version $$Id: SettlementFee.php 1395 2013-08-26 02:50:18Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');
App::uses('SettlementTransaction', 'Model');

class SettlementFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->_chargePerTransaction = 0.0;
	}

/**
 * 
 * @see LineItemFee::calculate()
 */
	public function calculate($merchant, $batchDate) {
		$this->_merchant = $merchant;
		$this->_chargePerTransaction =
						$this->_merchant['BillingMerchantFee']['per_settlement_fee'];
		$transaction = new SettlementTransaction();
		$transactions = $transaction->getSettlements($this->_merchant['Merchant']['merchantId'], $batchDate);
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}
}