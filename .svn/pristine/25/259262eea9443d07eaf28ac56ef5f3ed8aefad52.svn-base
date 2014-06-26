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
 * @version $$Id: TransactionsFee.php 1406 2013-08-28 02:58:01Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');

class TransactionsFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->_chargePerTransaction = 0.0;
	}

	public function calculate($merchant, $batchDate) {
	$this->_merchant = $merchant;
	$this->_chargePerTransaction =
						$this->__merchant['BillingMerchantFee']['webConvFee'];
		$percentageCharged =
						$this->__merchant['BillingMerchantFee']['webConvGaurPct'];
	if ($this->_chargePerTransaction < .01 && $percentageCharged < .01) {
			$this->_chargePerTransaction = 0.0;
		}
	App::uses('Transaction', 'Model');
	$transaction = new Transaction();
	$transactions = $transaction->getTransactions(
					$this->_merchant['Merchant']['merchantId'], $batchDate);
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}
}