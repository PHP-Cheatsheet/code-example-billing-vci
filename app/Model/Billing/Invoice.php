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
 * @version $$Id: Invoice.php 1355 2013-08-20 05:11:50Z anit $$
 */

App::uses('Billing', 'Model/Billing');
/**
 * Invoice Model
 *
 * @property Merchant $Merchant
 * @property Batch $Batch
 * @property LineItem $LineItem
 */
class Invoice extends Billing {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Merchant' => array(
			'className' => 'Merchant',
			'foreignKey' => 'merchant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Batch' => array(
			'className' => 'Batch',
			'foreignKey' => 'batch_id',
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
		'LineItem' => array(
			'className' => 'LineItem',
			'foreignKey' => 'invoice_id',
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

	public function setInvoice($data) {
		$this->unbindModelAll();
		$this->useDbConfig = 'billingWrite';
		$this->create();
		$this->save($data);
		return $this->getLastInsertId();
	}

	public function updateInvoice($id) {
		$this->unbindModelAll();
		return $this->find('all',array('conditions' => array('id' => $id)));
	}
	
	/**
	 * Get all invoice by batch id.
	 * 
	 * @param int $id Batch Id
	 * @return type
	 */
	public function getAllInvoice($id) {
		$this->unbindModelAll();
		return $this->find(
		'all',
			array(
				'fields' => array(
					'Invoice.batch_id',
					'Invoice.id',
					'Invoice.merchant_id',
					'Merchant.name',
					'Invoice.subtotal',
					'Invoice.minimum_total',
					'Invoice.total',
					'LineItem.id as line_item_id',
					'LineItem.name',
					'LineItem.quantity',
					'LineItem.unit_price',
					'LineItem.total_price'
					
				),
				'joins' => array(
					array(
						'table' => 'line_items',
						'alias' => 'LineItem',
						'type' => 'LEFT',
						'conditions' => array('Invoice.id = LineItem.invoice_id')
					),
					array(
						'table' => 'echecks.merchants',
						'alias' => 'Merchant',
						'type' => 'LEFT',
						'conditions' => array('cast(Invoice.merchant_id as char character set latin1) = Merchant.merchantId')
					)
				),
				'conditions' => array('Invoice.batch_id' => $id)));
	}
}
