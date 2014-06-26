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

App::uses('EODStepImplementation', 'Test/Case/Lib/EOD');

class EODStepImplementationTest extends CakeTestCase {

		public $import = array('table' => 'workflow_eod',
				'records' => true);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EODStepImplementation = new EODStepImplementation();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EODStepImplementation);
		parent::tearDown();
	}

	/**
	 * Test EODStepImplementation->executedSuccessfully()
	 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->EODStepImplementation->executedSuccessfully();

		$this->assertFalse(empty($actual));
		$this->assertEqual($actual, 'success');
	}

	/**
	 * Test EODStepImplementation->execute();
	 */
	public function pTestExecute() {
		$actual = $this->EODStepImplementation->execute();

		$this->assertEmpty($actual);
	}

	/**
	 * Test EODStepImplementation->executeInternal();
	 */
	public function ptestExecuteInternal() {
		$actual = $this->EODStepImplementation->executeInternal();

		$this->assertEmpty($actual);
		$this->assertEqual(false, $actual);
	}
}