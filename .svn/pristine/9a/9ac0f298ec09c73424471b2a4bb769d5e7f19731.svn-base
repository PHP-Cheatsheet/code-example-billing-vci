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
 * @version $$Id: Logger.php 1457 2013-09-04 04:41:51Z anit $$
 */

App::uses('Log', '/Lib/Log');

class Logger extends Log {

	public function __construct() {
		parent::__construct();
	}
/**
 * (non-PHPdoc)
 * @see Log::logInformation()
 */
	public function logInformation($object, $error = null, $debug = null) {
		$logFormat = "yaml";
		$this->_logInformation($object, $error , $debug, $logFormat);
	}

/**
 * (non-PHPdoc)
 * @see Log::logResultSet()
 */
	public function logResultSet($data) {
		//log query in file and database
		$logFormat = "yaml";
		$this->_logData($data, $logFormat);
	}
}