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

App::uses('SequentialWorkflow', 'Lib');
App::uses('EODStepImplementation', 'Test/Case/Lib/EOD');
App::uses('OriginationChangeAtoBStep', 'Lib/EOD');

class SequentialWorkflowTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SequentialWorkflow = new SequentialWorkflow();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SequentialWorkflow);
		parent::tearDown();
	}

	/**
	 * Test SequentialWorkflow->enqueue only enques 
	 * Steps Implementations and return null.
	 */
	public function ptestEnqueue() {

		$objEODStepImplementation = new EODStepImplementation();
		$actual = $this->SequentialWorkflow->enqueue($objEODStepImplementation);
		$this->assertEmpty($actual);

		$queue = $this->SequentialWorkflow->getQueue();
		$this->assertInstanceOf('Step', $queue[0]);
	}

	/**
	 * Test SequentialWorkflow->enqueue throws exception
	 * while passing other than subclass object of Step
	 */
	public function ptestEnqueueThrowsErrorAssigningOtherThanObject() {
		$objEODStepImplementation = new OriginationChangeAtoBStep();
		$actual = $this->SequentialWorkflow->enqueue($objEODStepImplementation);
		$this->assertFalse(empty($queue));

		$queue = $this->SequentialWorkflow->getQueue();
		$this->assertEmpty($queue);
	}

	/**
	 * Test SequentialWorkflow->start()
	 */
	public function testStart() {
		$objEODStepImplementation = new EODStepImplementation();
		$actual = 
						$this->SequentialWorkflow->enqueue($objEODStepImplementation);
		$actual = $this->SequentialWorkflow->start();

		$this->assertTrue(empty($actual));
	}

}