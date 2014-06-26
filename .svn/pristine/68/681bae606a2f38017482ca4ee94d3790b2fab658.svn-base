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
 * @version $$Id: $$
 */

App::uses('Step', 'Lib');

class EODStepImplementation extends Step {

	public function __construct() {
		$this->idempotent = true;
		$this->atomicDbOperationModel = new EodWorkflow();
		$this->implementatedClassTableMappingField = 'is_business_day';
	}

	public function atomicDbOperation() {
		$date = '2013-11-22';
		$this->atomicDbOperationModel->setIsBusinessDayToYes($date);
	}

	public function executeInternal() {
		//Call function that will execute BL
		if (true) {
			$this->atomicDbOperation();
		}
	}

	/**
	 * Check if the FIELDNAME content is success
	 * 
	 * @return boolean
	 */
	public function executedSuccessfully() {
		$objWorkflowEod = new EodWorkflow();
		$contentOfEodWorkflowField = 
						$objWorkflowEod->getFieldContent(
										$this->implementatedClassTableMappingField);

		if($stepExecutionStatus['EodWorkflow'][
				$this->implementatedClassTableMappingField] == 'yes') {
			return true;
		}

		return false;
	}

}