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
 * @version $$Id: BillingShell.php 2750 2014-01-23 06:29:06Z sisir $$
 */

App::uses('AppShell', '/Console/Command');
/**
 * 
 * Generates billing information of merchants transactions.
 * @author anit.shrestha@sourceopia.com
 *
 */
class BillingShell extends AppShell {

	/**
 * Contains arguments parsed from the command line.
 *
 * @var array
 */
	public $args;

	private $__batchId;

	private $__thisMonthFirstDate;

	private $__lastMonthFirstDate;

	public function  main() {
		$this->out($this->OptionParser->help());
	}

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$type = array(
			//'choices' => array('YYYY-MM', 'MM'),
			'required' => false,
			'help' => __d('cake_console', 'YYYY-MM | MM')
		);
		$parser->description(
			__d('cake_console', 'A console tool for managing the Billing Application.')
			)->addSubcommand('generateMerchantsInvoice', array(
				'help' => __d('cake_console',
								'Generates all merchants invoice.'),
				'parser' => array(
					'description' => __d('cake_console',
									'Date can either be year-month or month of the current year.'),
					'epilog' => __d('cake_console', 
									'When no argument passed, by default it will generate invoice of previous month.'),
					'arguments' => array('Date' => $type)
				)
			));
		return $parser;
	}

/**
 * Generates invoices for merchant billing.
 * 
 * @param string date . Format YYYY-MM or YYYY
 * @return void
 */
	public function generateMerchantsInvoice() {
		$this->out(__d('cake_console',
						'Generating Batch Information...'));
		App::uses('Batch', 'Model/Billing');
		$batch = new Batch();
		if ($return = $batch->generateBatchInfo($this->args[0])) {
				$batchInfo = array( 'batch_date' =>
				array('this_month_first_date' => $return['upperLimit'],
							'last_month_first_date' => $return['lowerLimit']),
							'batch_id' => $return['batch_id']);
			$this->out(__d('cake_console',
							'Batch Date : ' . $return['lowerLimit'] . ' To ' . $return['upperLimit']));
			$this->out(__d('cake_console', 'Batch ID : ' . $return['batch_id'])); 
			$this->out(__d('cake_console', 'Batch Information Generation Complete.'));
			$this->out(__d('cake_console',
						'Generating Merchant Invoice...'));
			App::uses('MerchantBilling', '/Model/Billing');
			$merchantbilling = new MerchantBilling($batchInfo);
			$status = $merchantbilling->generateInvoice();
			if($status);
			$this->out(__d('cake_console',
						'Merchant Invoice Generation Complete.'));
		} else {
			$this->error(__d('cake_console',
							"Please Enter A Valid Date."));
		}
	}

}