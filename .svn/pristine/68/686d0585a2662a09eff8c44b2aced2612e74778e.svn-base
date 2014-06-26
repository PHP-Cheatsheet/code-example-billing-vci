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
 * @version $$Id: TieredPercentageFee.php 1406 2013-08-28 02:58:01Z anit $$
 */

App::uses('LineItemFee', '/Model/Billing');

class TieredPercentageFee extends LineItemFee {

	public function __construct() {
		parent::__construct();
		$this->_chargePerTransaction = 0.50;
	}

	public function calculate($merchant, $batchDate) {
		$this->_merchant = $merchant;
		if ($this->_merchant['Merchant']['use_tiered_pricing'] == 1) {
			$transactions = '';
			App::uses('BillingTieredValue', 'Model');
			$billingTieredValue = new BillingTieredValue();
			$tieredBills = $billingTieredValue->getTiredBillsOfMerchant($this->_merchant['Merchant']['merchantId']);
			$tieredData = array();
			App::uses('Transaction', 'Model');
			$transaction = new Transaction();
			for ($count = 1; $count <= 3; $count++) {
				$transactions[$count] = $transaction->getTieredFees($tieredBills, $batchDate, $count);
				$this->chargePerTransaction = $tieredBills[0]['BillingTieredValue']['per_check_fee_percent_' . $count];
				$tieredData[$count] = $this->_prepareLineItemsData(__CLASS__ . $count, $transactions[$count]);
			}
			return $tieredData;
		}
	}

	protected function _prepareLineItemsData($feeName, $transactions) {
		$this->_numberOfTransactions = count($transactions);
		$tierDollars = 0;
		for ($i = 0; $i < $this->_numberOfTransactions; $i++) {
			$tierDollars += $transactions[0]['Webcheck']['amount'];
		}
		if ( $this->_chargePerTransaction > 0.001) {
			$this->_totalFee = ($this->_chargePerTransaction / 100) * $tierDollars;
		}
		$lineItem = array(
						'name' => $feeName,
						'description' => '',
						'quantity' => $tierDollars,
						'unit_price' => $this->_chargePerTransaction / 100,
						'total_price' => $this->_totalFee,
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