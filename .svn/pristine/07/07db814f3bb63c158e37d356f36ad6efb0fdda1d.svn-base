<?php
App::uses('CustomerTransPopulatedCheckStep', 'Lib/EOD');

class CustomerTransPopulatedCheckStepTest extends CakeTestCase {

	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomerTransPopulatedCheckStep =  new CustomerTransPopulatedCheckStep();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomerTransPopulatedCheckStep);
		parent::tearDown();
	}

	public function testExecuteInternal() {
		//$this->CustomerTransPopulatedCheckStep->date = '2001-01-01';
		$actual = $this->CustomerTransPopulatedCheckStep->executeInternal();
		$this->assertTrue($actual);
		//$this->assertFalse($actual);
	}
	
	
}
