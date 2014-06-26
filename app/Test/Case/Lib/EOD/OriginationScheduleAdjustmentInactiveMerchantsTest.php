<?php

App::uses('OriginationScheduleAdjustmentInactiveMerchants', 'lib/EOD');

Class OriginationScheduleAdjustmentInactiveMerchantsTest extends CakeTestCase {
	
	/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationScheduleAdjustmentInactiveMerchants = new OriginationScheduleAdjustmentInactiveMerchants();
	}

	/**
	 * Good test Case to set set origination_scheduled_date to null for all 
	 * customer_transactions in A status and origination_scheduled_date = today
	 *  and merchants.active != 1,
	 * 
	 * @return boolean true if updated 
	 */
	public function testExecuteInternal_Good() {
		$this->OriginationScheduleAdjustmentInactiveMerchants->status = 'A';
		$this->OriginationScheduleAdjustmentInactiveMerchants->origSchDate = date('Y-m-d');
		$this->OriginationScheduleAdjustmentInactiveMerchants->merchantActive = '1';
		$result = $this->OriginationScheduleAdjustmentInactiveMerchants->executeInternal();
		$this->assertTrue($result);
	}

	/**
	 * Bad test Case to set set origination_scheduled_date to null for all 
	 * customer_transactions in A status and origination_scheduled_date = today
	 *  and merchants.active != 1,
	 * 
	 * @return boolean true if updated 
	 */
	public function testExecuteInternal_Bad() {
		$this->OriginationScheduleAdjustmentInactiveMerchants->status = 'B';
		$this->OriginationScheduleAdjustmentInactiveMerchants->origSchDate = date('Y-m-d');
		$this->OriginationScheduleAdjustmentInactiveMerchants->merchantActive = '0';
		$result = $this->OriginationScheduleAdjustmentInactiveMerchants->executeInternal();
		$this->assertFalse($result);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationScheduleAdjustmentInactiveMerchants);
		parent::tearDown();
	}
}

?>
