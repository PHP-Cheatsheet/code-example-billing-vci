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
 * @version $$Id: BillingMain.php 1364 2013-08-20 07:28:11Z anit $$
 */
App::uses('Logger', '/Lib/Log/');

abstract class BillingMain {

	protected $_batchDate;

	protected $_batchId;

	protected $_batch;

	protected $_log;

	public function __construct() {
		$this->_invoices = array();
		$this->_batchDate = array();
		$this->_batchId = 0;
		$this->_batch = array();
		$this->_log = new Logger();
	}
}
