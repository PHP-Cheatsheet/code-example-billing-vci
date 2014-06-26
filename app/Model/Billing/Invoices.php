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
 * @version $$Id: Invoices.php 1450 2013-09-03 07:04:26Z sisir $$
 */

App::uses('Invoice', 'Model/Billing');

class Invoices {

	private $__batch;

	private $__transactions;

	private $__merchant;

	private $__invoice;

	private $__numTransactions;

	private $__numDollars;

	public function __construct() {
		$this->__transactions = array();
		$this->__merchant = array();
		$this->__numTransactions = 0;
		$this->__numDollars = 0.0;
		$this->__invoice = new Invoice();
		$this->_batch = array();
	}

	

/**
 * Prepare invoice data
 * 
 * $subtotal = $transaction_charges + $percent_of_dollars_charges + $returns_charges + $chargeback_charges + $credit_charges + $settlement_transaction_charges + $change_notifcations_charges;
 * 
 * @param string $isoNumber
 * @param string $statementFee
 * @param string $minimumFee
 * @return array $invoiceData
 */
	private function __getInvoiceData($isoNumber, $statementFee, $minimumFee) {
		$subtotal = 0;
		foreach ($this->__transactions as $key => $val) {
			if($val != null) {
				if (key($val) == 'line_item') {
					$subtotal += $val['line_item']['total_price'];
				}
			}
		}
		/*
		if( $use_tiered_pricing != 1) {
			$subtotal = $transaction_charges + $percent_of_dollars_charges;
		}
		*/
		// No statement fees for ISO xxxxxxxx
		if ($isoNumber == "xxxxxxxx") {
			$total = $subtotal;
		} else {
			$total = $subtotal + $statementFee;
		}
		if ($total < $minimumFee && $minimumFee > 0 && $isoNumber != "xxxxxxxx") {
			$total = $minimumFee;
		}
		$invoiceData['batch_id'] = $this->_batch['batch_id'];
		$invoiceData['merchant_id'] = $this->__merchant['Merchant']['merchantId'];
		$invoiceData['subtotal'] = $subtotal;
		$invoiceData['minimum_total'] = $minimumFee;
		$invoiceData['total'] = $total;
		$invoiceData['total_paid'] = '';
		$invoiceData['creation_date'] = date("Y-m-d H:i:s");
		//$data['Invoice']['paid'] = '';
		//$data['Invoice']['emailed'] = '';
		return $invoiceData;
	}

	/**
	 * Set Line item Percentage
	 * 
	 * @param type $percentageCharged
	 */
	private function __setLineItemPercentageTransaction($percentageCharged) {
		$defPercentCharge = 0.0;
		$perPercentageCharge = $percentageCharged / 100;
		if ($percentageCharged > 0.001) {
			$defPercentCharge = $perPercentageCharge * $this->__numDollars;
		}
		$this->__transactions = array_merge($this->__transactions,
					array("PercentageTransactions" => array(
						"line_item" =>
							array(
								'name' => 'PercentageTransaction',
								'description' => '',
								'quantity' => $this->__numDollars,
								'unit_price' => $perPercentageCharge,
								'total_price' => $defPercentCharge,
								'created_date' => date("Y-m-d H:i:s")
								),
						"item" => array())));
	}

	/**
	 * 
	 * @param array $chargePerTransaction
	 * @return void
	 */
	private function __setLineItemTransaction($chargePerTransaction) {
		$transactionCharges = $chargePerTransaction * $this->__numTransactions;
		$this->__transactions = array_merge($this->__transactions,
									array("Transactions" =>
										array('line_item' => array(
											'name' => 'Transaction',
											'description' => '',
											'quantity' => $this->__numTransactions,
											'unit_price' => $chargePerTransaction,
											'total_price' => $transactionCharges,
											'creation_date' => date("Y-m-d H:i:s")
											),
											"item" => array()
											)
										)
									);
	}

	/**
	 * 
	 * @param type $useTieredPricing
	 */
	private function __sumTransactionsAndDollars($useTieredPricing = null) {
		if ($useTieredPricing == 1) {
			//$transaction_charges = $tier1_charges + $tier2_charges + $tier3_charges;
			//$percent_of_dollars_charges  = $tier1_percent_charges + $tier2_percent_charges + $tier3_percent_charges;
			foreach ($this->__transactions as $key => $val) {
				//search only if the key index is a TieredFee
				if (preg_match("/^TieredFee[1-3]{1}$/", $key)) {
					//$num__transactions = $tier1_num__transactions + $tier2_num__transactions + $tier3_num__transactions;
					$this->__numTransactions += $val['line_item']['quantity'];
					//$num_dollars = $tier2_dollars + $tier3_dollars + $tier3_dollars;
					$this->__numDollars += $val['line_item']['total_price'];
				}
			}
		} else {
			App::uses('Transaction', 'Model');
			$transaction = new Transaction();
			$transactionsArray = $transaction->getTransactions($this->__merchant['Merchant']['merchantId'], $this->_batch['batch_date']);
			$this->__numTransactions = count($transactionsArray);
			foreach ($transactionsArray as $key => $val) {
				$this->__numDollars += $val["Transaction"]["amount"];
			}
		}
	}

/**
 * 
 * Genetrate individual merchant invoice.
 * 
 * @param string $merchant Merchant id.
 * @param array $transactions
 * @param string $batch Batch id.
 */
	public function generateInovice($merchant, $transactions, $batch) {
		$this->__transactions = $transactions;
		$this->__merchant = $merchant;
		$this->_batch = $batch;
		$defStatementFee = 0.0;
		$defChargePerTrans = 0.0;
		$statementFee = $this->__merchant['Merchant']['stmtFee'];
		$isoNumber = $this->__merchant['Merchant']['isoNumber'];
		$useTieredPricing = $this->__merchant['Merchant']['use_tiered_pricing'];
		$percentageCharged = $this->__merchant['BillingMerchantFee']['webConvGaurPct'];
		$chargePerTrans = $this->__merchant['BillingMerchantFee']['webConvGaurFee'];
		$statementFee = $this->__merchant['BillingMerchantFee']['stmtFee'];
		$setupFee = $this->__merchant['BillingMerchantFee']['setupFee'];
		$minFee = $this->__merchant['BillingMerchantFee']['minimum_fee'];
		if ($percentageCharged < .01) {
			$percentageCharged = $this->__merchant['BillingMerchantFee']['webConvPct'];
		}
		if ($chargePerTrans < .01) {
			$chargePerTrans = $this->__merchant['BillingMerchantFee']['webConvFee'];
		}
		if ($chargePerTrans < .01 && $percentageCharged < .01) {
			$chargePerTrans = $defChargePerTrans;
		}
		if ($setupFee < 0.01) {
			$setupFee = $defStatementFee;
		}
		if ($minFee < 0.00) {
			$minFee = 0.00;
		}
		$this->__sumTransactionsAndDollars($useTieredPricing);
//		$this->__setLineItemTransaction($chargePerTrans);
//		$this->__setLineItemPercentageTransaction($percentageCharged);
		$invoiceData = $this->__getInvoiceData($isoNumber, $statementFee, $minFee);
//		pr($invoiceData); die;
		$invoiceID = $this->__invoice->setInvoice($invoiceData);
		$this->setLineItemAndItem($invoiceID);
	}

/**
 * 
 * @param type $invoiceID
 */
	public function setLineItemAndItem($invoiceID) {
		App::uses('LineItem', 'Model/Billing');
		$lineitem = new LineItem();
		App::uses('Item', 'Model/Billing');
		$item = new Item();
		foreach ($this->__transactions as $keyval) {
			$lineItemId = $lineitem->setLineItem($keyval['line_item'] , $invoiceID);
			if (count($keyval['item']) > 0) {
				$item->setItem($keyval['item'] , $lineItemId);
			}
		}
	}
}