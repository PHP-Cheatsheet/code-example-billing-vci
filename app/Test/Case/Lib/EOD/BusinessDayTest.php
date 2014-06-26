<?php

App::uses('BusinessDay', 'lib/EOD');

Class BusinessDayTest extends CakeTestCase {
	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BusinessDay = new BusinessDay();
	}

	/**
	 * Good test case to test business date for normal day
	 */
	public function testExecuteInternal_Good() {
		$this->BusinessDay->date = '2013-11-22';
		$result = $this->BusinessDay->executeInternal();
		$this->assertTrue($result);
		
	}

	/**
	 * Good test case to test business date for normal day
	 */
	public function testExecuteInternal_bad() {
		$this->BusinessDay->date = '2013-11-17';
		$result = $this->BusinessDay->executeInternal();
		$this->assertFalse($result);
		
	}
	
	/**
	 * Corner test case to test business date for holiday
	 */
	public function testExecuteInternal_corner() {
		$this->BusinessDay->date = '2013-11-28';
		$result = $this->BusinessDay->executeInternal();
		$this->assertFalse($result);
		
	}
}
?>
