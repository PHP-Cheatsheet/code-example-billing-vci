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
 * @version $$Id: ChargeBacksFee.php 1369 2013-08-20 07:53:34Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');

class ChargeBacksFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->chargePerTransaction = 0.0;
	}

/**
 * 
 * @see LineItemFee::calculate()
 */
	public function calculate($merchant, $batchDate) {
		$this->_merchant = $merchant;
		$charge = $this->_merchant['BillingMerchantFee']['transacteeDeclTransFee'];
		if ($charge > 0.01) {
			$this->_chargePerTransaction = $charge;
		}
		App::uses('Transaction', 'Model');
		$transaction = new Transaction();
		$transactions = $transaction->getChargeBacks($this->_merchant['Merchant']['merchantId'], $batchDate);
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}

}
