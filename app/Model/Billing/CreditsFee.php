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
 * @version $$Id: CreditsFee.php 1364 2013-08-20 07:28:11Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');

class CreditsFee extends LineItemFee {

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

		if ( $this->_merchant['BillingMerchantFee']['transacteeDeclTransFee'] > .01) {
			$this->chargePerTransaction = $this->_merchant['BillingMerchantFee']['transacteeDeclTransFee'];
		}

		//import model
		App::uses('Transaction', 'Model');
		//instantiate object
		$transaction = new Transaction();
		//get number of returned checks
		$transactions = $transaction->getCredits($this->_merchant['Merchant']['merchantId'], $batchDate);

		//generate data for  line_item and item table
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}

}
