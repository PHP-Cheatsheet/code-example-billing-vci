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
 * @version $$Id: StatementFee.php 1406 2013-08-28 02:58:01Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');
App::uses('SettlementTransaction', 'Model');

class StatementFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->_chargePerTransaction = 0.0;
	}

/**
 * 
 * @see LineItemFee::calculate()
 */
	public function calculate($merchant, $batchDate = null) {
		$this->_merchant = $merchant;
		$this->_chargePerTransaction = 
						$this->_merchant['BillingMerchantFee']['stmtFee'];
		$transactions = 1;
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}
}