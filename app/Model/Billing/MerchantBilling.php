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
 * @version $$Id: MerchantBilling.php 1453 2013-09-03 09:55:25Z anit $$
 */

App::uses('BillingMain', '/Model/Billing');

/**
 *
 * Generate Merchants Invoice.
 *
 *
 */
class MerchantBilling extends BillingMain {

	private $__invoiceData;

	public function __construct($batch) {
		parent::__construct();
		$this->__error = false;
		$this->_batchDate = $batch['batch_date'];
		$this->_batchId = $batch['batch_id'];
		$this->_batch = $batch;
		$this->__invoiceData = array();
	}

/**
 * Generates invoice.
 * 
 */
	public function generateInvoice() {
			App::uses('Merchant', 'Model');
			$merchant = new Merchant();
			$merchantsList = $merchant->getBilledMerchants();
			App::uses('Invoices', '/Model/Billing');
			$invoices = new Invoices();
			$listOfFees = array(
					'PercentageTransactionsFee',
					'TransactionsFee',
					'CreditsFee',
					'StatementFee',
					'NotificationOfChangeFee',
					'TieredFee',
					'TieredPercentageFee',
					'ReturnsFee',
					'CreditsFee',
					'ChargeBacksFee',
					'SettlementFee');
			$index = '';
			$dottedLine = "\n ------------------------------------------- \n";
			$line = "\n";
			$cntMerhants = count($merchantsList);
			if ($cntMerhants > 0) {
				for ($i = 0; $i < $cntMerhants; $i++) {
				$msg = array('Message' => "Prepare Invoice Data For Merchant ID: "
						. $merchantsList[$i]['Merchant']['merchantId'] . " \r\n ");
				echo $dottedLine . $msg['Message'];
				foreach ($listOfFees as $fee) {
					App::uses($fee, '/Model/Billing');
					$feeObj = new $fee();
					$msg = array('Message' => "Processing " . $fee . " ");
					echo $line . $msg['Message'];
					if ($fee == 'TieredFee' || $fee == 'TieredPercentageFee') {
							$tierData = $feeObj->calculate($merchantsList[$i],
										$this->_batchDate);

							//$this->_log->logResultSet($tierData);

						$count = count($tierData);
						for ($j = 0; $j < $count; $j++) {
							$index = $j + 1;
							$this->__invoiceData[$fee . $index] = $tierData[$j + 1];
						}
					} else {
						$this->_log->logResultSet($this->__invoiceData[$fee]);

						$this->__invoiceData[$fee] = $feeObj->calculate(
									$merchantsList[$i], $this->_batchDate);
					}
					
				}
				$msg = array('Message' => "Generating Invoice. \r\n ");
				echo $line . $msg['Message'];
//				$this->_logObj->logResultSet($msg);
//				$this->_logObj->logInformation($merchant, false, false);
				$invoices->generateInovice($merchantsList[$i], 
								$this->__invoiceData, $this->_batch);
				$msg = array('Message' => "Invoice Generation Complete");
				echo $line . $msg["Message"];
//				$this->_logObj->logResultSet($msg);
			}
			}else {
				echo $cntMerhants. " merchants were found." . $line;
			}
			//Log all SQL
//			$this->_logObj->logInformation($merchant, false, false);
//		} catch(Exception $e) {
//			//set error status to 1
//			$error = $this->_batchId;
//			//log query in file and database
//			$this->_logObj->logInformation($e, $error, false);
//		}
	}
}
