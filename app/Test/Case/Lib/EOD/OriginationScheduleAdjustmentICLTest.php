<?php
App::uses('OriginationScheduleAdjustmentICL', 'lib/EOD');

/**
 * OriginationScheduleAdjustmentICL Test Case
 *
 */
class OriginationScheduleAdjustmentICLTest extends CakeTestCase {
	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationScheduleAdjustmentICL = new OriginationScheduleAdjustmentICL();
	}

	/**
	 * Set customer_transactions.origination_scheduled_date to null for all
	 * customer_transactions with status A,origination_scheduled_date = today and
	 * customer_transactions.standard_entry_class_code == ICL
	 * 
	 * @return boolean True if update sucessful else False
	 */
	public function testExecuteInternal_Good() {
		$this->OriginationScheduleAdjustmentICL->today = '2013-11-18';
		$this->OriginationScheduleAdjustmentICL->standardEntryClassCode = 'POP';
		$this->OriginationScheduleAdjustmentICL->status = 'A';
		$result = $this->OriginationScheduleAdjustmentICL->executeInternal();
		$this->assertTrue($result);
	}

	/**
	 * Bad test for Set customer_transactions.origination_scheduled_date to null for all
	 * customer_transactions with status A,origination_scheduled_date = today and
	 * customer_transactions.standard_entry_class_code == ICL
	 * 
	 * @return boolean True if update sucessful else False
	 */
	public function testExecuteInternal_Bad() {
		$this->OriginationScheduleAdjustmentICL->today = '2013-11-18';
		$this->OriginationScheduleAdjustmentICL->standardEntryClassCode = 'ICL';
		$this->OriginationScheduleAdjustmentICL->status = 'B';
		$result = $this->OriginationScheduleAdjustmentICL->executeInternal();
		$this->assertFalse($result);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationScheduleAdjustmentICL);
		parent::tearDown();
	}

	
}
?>
