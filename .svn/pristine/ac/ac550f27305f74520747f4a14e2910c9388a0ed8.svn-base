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
 * @version $$Id: Item.php 1384 2013-08-22 11:05:53Z anit $$
 */

App::uses('Billing', 'Model/Billing');
/**
 * Item Model
 *
 * @property LineItem $LineItem
 */
class Item extends Billing {

	public $belongsTo = array(
		'LineItem' => array(
			'className' => 'LineItem',
			'foreignKey' => 'line_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function setItem($data, $lineItemId) {
		$this->useDbConfig = 'billingWrite';
		foreach ($data as $keyval) {
			$keyval['line_item_id'] = $lineItemId;
			$this->useDbConfig = 'billingWrite';
			$this->create();
			$this->save($keyval);
		}
	}

	/**
	 * Fetch items by line item id.
	 * 
	 * @param string $lineItemId
	 * @param object $cObject ItemsController Object
	 * @return array 
	 * @throws NotFoundException
	 */
	public function fetchItemsByLineItemId($lineItemId, $cObject) {
		if (!$this->LineItem->exists($lineItemId)) {
			throw new NotFoundException(__('Valid Line Items Id Is Required.'));
		}
		$return['table_data'] = $cObject->paginate($this,
						array('Item.line_item_id' => $lineItemId));
		$return['title'] = ""; 
		$return['sub_title'] = "";
		$return['display_field'] = array('Id' => 'Item.id',
														'Transaction' => 'Item.item_id',
														'Item Type' => 'Item.item_type',
														'Line Item' => 'LineItem.name',
														'Invoice Id' => 'LineItem.invoice_id');
		return $return;
	}
	
}
