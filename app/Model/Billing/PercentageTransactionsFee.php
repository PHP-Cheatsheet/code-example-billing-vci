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
 * @version $$ $$
 */

App::uses('LineItemFee', '/Model/Billing');

class PercentageTransactionsFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->_chargePerTransaction = 0.50;
	}

	public function calculate($merchant, $batchDate) {
		$this->_merchant = $merchant;
		$this->_chargePerTransaction =
						$this->_merchant['BillingMerchantFee']['webConvGaurPct'];
		if ($this->_chargePerTransaction < .01) {
			$this->_chargePerTransaction = 
							$this->_merchant['BillingMerchantFee']['webConvPct'];
		}
		App::uses('Transaction', 'Model');
		$transaction = new Transaction();
		$transactions = $transaction->getTransactions(
						$this->_merchant['Merchant']['merchantId'], 
						$batchDate);
		return $this->_prepareLineItemsData(__CLASS__, $transactions);
	}

	protected function _prepareLineItemsData($feeName, $transactions) {
		$this->_numberOfTransactions = count($transactions);
		$sumAmount = 0;
	
		for ($i = 0; $i < $this->_numberOfTransactions; $i++) {
			$sumAmount += $transactions[0]['Transaction']['amount'];
		}
	
		if ( $this->_chargePerTransaction > 0.001) {
			$this->_totalFee = ($this->_chargePerTransaction / 100) * $sumAmount;
		}

		$lineItem = array(
						'name' => $feeName,
						'description' => '',
						'quantity' => $this->_numberOfTransactions,
						'unit_price' => $this->_chargePerTransaction,
						'total_price' => $sumAmount,
						'creation_date' => date("Y-m-d H:i:s")
						);
		$item = array();
		if ($this->_numberOfTransactions > 0 ) {
			$item = $this->_prepareItemsData($transactions);
		}
		$transactionsData = array('line_item' => $lineItem, 'item' => $item);
		return $transactionsData;
	}
}