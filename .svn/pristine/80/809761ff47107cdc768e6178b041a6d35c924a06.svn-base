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
 * @version $$Id: LineItem.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('Billing', 'Model/Billing');
/**
 * LineItem Model
 *
 * @property Invoice $Invoice
 * @property Item $Item
 */
class LineItem extends Billing {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $belongsTo = array(
		'Invoice' => array(
			'className' => 'Invoice',
			'foreignKey' => 'invoice_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'line_item_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function setLineItem($data, $invoiceId) {
		$this->unbindModelAll();
		$this->useDbConfig = 'billingWrite';
		$data['invoice_id'] = $invoiceId;
		$this->create();
		$this->save($data);
		return $this->getLastInsertId();
	}
}
