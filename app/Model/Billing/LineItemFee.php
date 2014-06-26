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
 * @version $$Id: LineItemFee.php 1406 2013-08-28 02:58:01Z anit $$
 */

App::uses('Logger', '/Model/Billing');

abstract class LineItemFee {

	protected $_merchant;

	protected $_chargePerTransaction;

	protected $_numberOfTransactions;

	protected $_totalFee;

	protected $_item;

	protected $_lineItem;

	protected $_logObj;

	public function __construct() {
		$this->_invoices = array();
		$this->_item = array();
		$this->_lineItem = array();
		$this->_logObj = new Logger();
		$this->_numberOfTransactions = 0;
		$this->_totalFee = 0;
	}
	
	/**
	 * 
	 * @abstract
	 */
	abstract public function calculate($merchant, $batchDate);

/**
 * Generate line_items and items data
 * 
 * @param string $feeName
 * @param array $transactions
 * return array array('line_item' => $lineItem, 'item' => $item);
 */
	protected function _prepareLineItemsData($feeName, $transactions) {
		$this->_numberOfTransactions = count($transactions);
		$this->_totalFee = $this->_chargePerTransaction *
						$this->_numberOfTransactions;
		$lineItem = array(
						'name' => $feeName,
						'description' => 'Merchant Invoice Line Item.',
						'quantity' => $this->_numberOfTransactions,
						'unit_price' => $this->_chargePerTransaction,
						'total_price' => $this->_totalFee,
						'creation_date' => date("Y-m-d H:i:s")
						);
		$item = array();
		if ($this->_numberOfTransactions > 0 && $feeName!='StatementFee') {
			$item = $this->_prepareItemsData($transactions);
		}
		$transactionsData = array( 'line_item' => $lineItem, 'item' => $item);
		return $transactionsData;
	}

/**
 * Prepares data for items table.
 * 
 * @param array $transaction  * 
 * @return array (item_id, item_type, creation_date) 
 */
	protected function _prepareItemsData($transactions) {
		$count = count($transactions);
		if ($count > 0) {
			$itemType = $this->__itemType($transactions[0]);
			for ($i = 0; $i < $count; $i++) {
				$this->_item[$i]['item_id'] = $transactions[$i][key($transactions[0])]['transaction_id'];
				$this->_item[$i]['item_type'] = $itemType;
				$this->_item[$i]['creation_date'] = date("Y-m-d H:i:s");
			}
			return $this->_item;
		}
		return false;
	}
/**
 *
 * Prepare item.item_type data = database.table
 * 
 * @param string $model
 * @return string FORMAT : database.table
 */
	private function __itemType($model) {
		$table = key($model);
		App::uses($table, 'Model');
		$modelOBJ = new $table();
		$database = $modelOBJ->getDataSource()->config['database'];
		App::uses('Inflector', 'Utility');
		$table = Inflector::tableize($table);
		return $database . '.' . $table;
	}
}