<?php
App::uses('VciDate', 'Lib');

class VciDateTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VciDate = new VciDate();
	}

	
	public function testgetBoundariesForMonth() {
		
		$expData = array('start' => strtotime('2013-01-01 00:00:00'),'end' => strtotime('2013-01-31 23:59:59'));
		$data = $this->VciDate->getBoundariesForMonth('2013-01');
//		$start = date('Y-m-d 00:00:00',$retData['start']);
//		$end = date('Y-m-d 00:00:00',$retData['end']);
//		$data = array('start' => $start, 'end' => $end);
		$this->assertEquals($expData,$data);
	}

	public function testisBusinessDate() {
		$Data = $this->VciDate->isBusinessDate('2012-01-02');
		$this->assertEqual($Data, false);
	}

//	public function 

	/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VciDate);
		parent::tearDown();
	}
}
