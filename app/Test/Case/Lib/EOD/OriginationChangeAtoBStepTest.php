<?php
App::uses('OriginationChangeAToBStep', 'Lib/EOD');

class OriginationChangeAToB extends CakeTestCase {

	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationChangeAToBStep =  new OriginationChangeAToBStep();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationChangeAToBStep);
		parent::tearDown();
	}

	public function testExecuteInternal() {
		//$this->OriginationChangeAToB->date = '2001-01-01';
		$actual = $this->OriginationChangeAToBStep->executeInternal();
		$this->assertTrue($actual);
		//$this->assertFalse($actual);
	}
	
	
}
