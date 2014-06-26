<?php
App::uses('OriginationScheduleMerchantOrigHold', 'lib/EOD');

class OriginationScheduleMerchantOrigHoldTest  extends CakeTestCase {
	
	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->OriginationScheduleMerchantOrigHold = new OriginationScheduleMerchantOrigHold();
	}

	/**
	 * Good test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 * @return boolean true if set origination_scheduled_date to null
	 */
	public function testExecuteInternal_Good() {
		$this->OriginationScheduleMerchantOrigHold->today = '2013-11-21';
		$this->OriginationScheduleMerchantOrigHold->status = 'A';
		$this->OriginationScheduleMerchantOrigHold->merchOrigTransHold = '1';
		$result = $this->OriginationScheduleMerchantOrigHold->executeInternal();
		$this->assertTrue($result);
	}

	/**
	 * Bad test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 * @return boolean true if set origination_scheduled_date to null
	 */
	public function testExecuteInternal_Bad() {
		$this->OriginationScheduleMerchantOrigHold->today = '0000-11-18';
		$this->OriginationScheduleMerchantOrigHold->status = 'B';
		$this->OriginationScheduleMerchantOrigHold->merchOrigTransHold = '0';
		$result = $this->OriginationScheduleMerchantOrigHold->executeInternal();
		$this->assertFalse($result);
	}

	/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationScheduleMerchantOrigHold);
		parent::tearDown();
	}
}

?>
