<?php

App::uses('CustomerTransaction', 'Model');

/**
 * CustomerTransaction Test Case
 *
 */
class CustomerTransactionTest extends CakeTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.customer_transaction'
	);
	public $autoFixtures = true;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
//		$this->CustomerTransaction = ClassRegistry::init('CustomerTransaction');

		$this->CustomerTransaction = ClassRegistry::init(
						array(
							'ds' => 'warehouseRead',
							'class' => 'CustomerTransaction',
							'table' => 'customer_transactions'));
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->CustomerTransaction);

		parent::tearDown();
	}

	public function ptestgetDate() {
		$expected = date('Y-m-d');
		$actual = $this->CustomerTransaction->getDate('1000001');
		pr($actual);
		//$this->assertFalse(empty($actual));
		$this->assertEquals($expected, $actual);
	}

	public function ptestcheckStatus() {
		$actual = $this->CustomerTransaction->checkStatus('1000001');
		$this->assertFalse(empty($actual));
	}

	public function ptestsaveData() {
		$expected = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'transaction_id' => null,
				'original_transaction_id' => null,
				'customer_name' => 'Sanda Gagon',
				'customer_name_mphone' => 'SNTKKN',
				'merchant_name_mphone' => null,
				'routing_number' => '081908833',
				'account_number' => '322886547325',
				'account_number_last_four_digits' => '7325',
				'account_type' => 'C',
				'amount' => '9431',
				'transaction_type' => 'debit',
				'originated_date' => null,
				'creation_date' => '2013-07-02 00:00:15',
				'effective_entry_date' => null,
				'origination_ideal_date' => null,
				'origination_scheduled_date' => null,
				'origination_actual_date' => '2013-07-02',
				'origination_odfi' => 'CO',
				'return_date' => null,
				'return_code' => null,
				'return_reason' => null,
				'merchant_id' => '1154',
				'standard_entry_class_code' => null,
				'company_entry_description' => 'PURCHASE',
				'company_discretionary_data' => '531-740-1858',
				'authorization_code' => 'A00001',
				'expiration_date' => '2015-07-02',
				'status' => 'R',
				'gateway' => null,
			)
		);
		$actual = $this->CustomerTransaction->saveData($expected);
		$this->assertFalse(empty($actual));
	}

	public function ptestupdateData() {

		$actual = $this->CustomerTransaction->saveData('1000001', date('Y-m-d'));
		$this->assertFalse(empty($actual));
	}

//-------------------------------------EOD STEP TEST------------------------------------------------------------//
//	EXAMPLE -//
//	public function ptestGetAllData() {
//		$this->CustomerTransaction->useDbConfig = 'warehouseRead';
//		
//		$data = $this->CustomerTransaction->getAllData();
////		$this->assertNotEmpty($data);
//		debug($data);
//	}

	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date.
	 * Good Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Good() {
		$data = $this->CustomerTransaction->AcceptedTransactionsExistsAfterCutOff('2013-11-20');
		$this->assertGreaterThan(0, count($data));
		$this->assertEqual($data[0]['CustomerTransaction']['status'], 'A');
	}

	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date.
	 * Bad Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Bad() {
		$data = $this->CustomerTransaction->AcceptedTransactionsExistsAfterCutOff('2013-11-23');
		$this->assertNotEmpty($data);
		$this->assertEqual($data[0]['CustomerTransaction']['status'], 'A');
	}

	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date.
	 * Corner Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Corner() {
		$data = $this->CustomerTransaction->AcceptedTransactionsExistsAfterCutOff('2013-11-21');
		$this->assertNotEmpty($data);
		$this->assertGreaterThan(0, count($data));
		$this->assertEqual($data[0]['CustomerTransaction']['status'], 'D');
	}

	/**
	 * Test whether the required data from warehouse.customer_transactions table has been fetched or not
	 * Good Case Scenario
	 */
	public function ptestGetOrigTransWithStatusA_Good() {
		$expectedData = array(
			array(
				'CustomerTransaction' => array('id' => '1000001'),
				'Merchant' => array('funding_time' => '3 Day')
			),
			array(
				'CustomerTransaction' => array('id' => '1000002'),
				'Merchant' => array('funding_time' => '3 Day')
			)
		);
		$data = $this->CustomerTransaction->getOrigTransWithStatusA('2013-11-21');
		$this->assertNotEmpty($data);
		$this->assertArrayHasKey('funding_time', $data[0]['Merchant']);
		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test whether the required data from warehouse.customer_transactions table has been fetched or not
	 * Bad Case Scenario
	 */
	public function ptestGetOrigTransWithStatusA_Bad() {
		$data = $this->CustomerTransaction->getOrigTransWithStatusA('2013-11-23');
		$this->assertNotEmpty($data);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Good Case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Good() {
		$expectedData = array(
			array('CustomerTransaction' =>
				array(
					'id' => '1000001',
					'status' => 'B',
					'origination_actual_date' => '2013-11-21',
					'effective_entry_date' => '2013-11-26'
				))
		);
		$testData = array(
			array(
				'CustomerTransaction' => array('id' => '1000001'),
				'Merchant' => array('funding_time' => '3 Day')
			),
			array(
				'CustomerTransaction' => array('id' => '1000002'),
				'Merchant' => array('funding_time' => '5 Day')
			)
		);
		$data - $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData);

		$checkData = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'status', 'origination_actual_date', 'effective_entry_date'),
			'conditions' => array('id' => '1000001')
		));

		$this->assertEquals(1, count($checkData));
		$this->assertEquals($expectedData, $checkData);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Bad Case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Bad() {
		$expectedData = array(
			array('CustomerTransaction' =>
				array(
					'id' => '1000001',
					'status' => 'B',
					'origination_actual_date' => '2013-11-21',
					'effective_entry_date' => '2013-11-26'
				))
		);
		$testData = array();

		$data - $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData);

		$checkData = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'status', 'origination_actual_date', 'effective_entry_date'),
			'conditions' => array('id' => '1000001')
		));

		$this->assertEquals(1, count($checkData));
		$this->assertEquals($expectedData, $checkData);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Corner case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Corner() {
		$expectedData = array(
			array('CustomerTransaction' =>
				array(
					'id' => '1000001',
					'status' => 'A',
					'origination_actual_date' => '0000-00-00',
					'effective_entry_date' => '0000-01-26'
				))
		);

		$testData = array(
			array(
				'CustomerTransaction' => array('id' => '1000001'),
				'Merchant' => array('funding_time' => '3 Day')
			),
			array(
				'CustomerTransaction' => array('id' => '1000002'),
				'Merchant' => array('funding_time' => '5 Day')
			)
		);

		$data - $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData);

		$checkData = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'status', 'origination_actual_date', 'effective_entry_date'),
			'conditions' => array('id' => '1000001')
		));

		$this->assertEquals(1, count($checkData));
		$this->assertEquals($expectedData, $checkData);
	}

	/**
	 * Test Returns all customerTransactions to originate ICL transactions
	 * i.e customer transactions of date '2013-11-21',
	 * standard entry class => 'ICL',  and status => "A"
	 */
	public function ptestgetOriginationScheduleAdjustmentICL() {
		$expectedResult = array(
			'id' => 1,
			'origination_scheduled_date' => '2013-11-21',
			'standard_entry_class_code' => 'ICL',
			'status' => 'A',
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOriginationScheduleAdjustmentICL(
				$date, 'ICL', 'A');
		$this->assertEquals($result[0]['CustomerTransaction'], $expectedResult);
	}

	/**
	 * Test Update CustomerTransaction.origination_scheduled_date to null
	 * to originate ICL transactions
	 */
	public function ptestupdateOrigScheduledDateforAdjICL() {
		$trans[]['CustomerTransaction'] = array(
			'id' => 1,
		);
		$trans[]['CustomerTransaction'] = array(
			'id' => 2,
		);

		$expectedResult[0]['CustomerTransaction'] = array(
					'id' => 1,
					'origination_scheduled_date' => '0000-00-00',
					'standard_entry_class_code' => 'ICL',
					'status' => 'A',
		);
		$expectedResult[1]['CustomerTransaction'] = array(
			'id' => 2,
			'origination_scheduled_date' => '0000-00-00',
			'standard_entry_class_code' => 'ICL',
			'status' => 'A',
		);

		$readBefore = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'origination_scheduled_date',
				'standard_entry_class_code', 'status')));
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'origination_scheduled_date',
				'standard_entry_class_code', 'status')));
		$this->assertEquals($expectedResult, $readAfter);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);

		$recordCompare = array_diff(
				$readBefore[0]['CustomerTransaction'], $readAfter[0]['CustomerTransaction']);
		$expectedArrayDiffResult = array('origination_scheduled_date' => '2013-11-21');
		$this->assertEquals($expectedArrayDiffResult, $recordCompare);
	}

	/**
	 * Good case test to get customer transactions if the merchant is on 'Origination hold'
	 */
	public function ptestgetOrigScheduleMerchantOrigHoldTrans_Good() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'A', '1');
//		debug($result);
		$this->assertEquals($result[0], $expectedResult);
	}

	/**
	 * Bad case test to get customer transactions 
	 * if the merchant is on 'Origination hold'
	 */
	public function ptestgetOrigScheduleMerchantOrigHoldTrans_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'B', '0');
		$this->assertEquals($result[0], $expectedResult);
	}

	/**
	 * Good test case to set origination_scheduled_date to null
	 * if the merchant is on ‘origination hold’.
	 */
	public function ptestupdateOrigScheDateforOrigHold_Good() {
		$date = '2013-11-21';
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '0000-00-00',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'A', '1');
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				'0000-00-00', 'A', '1');
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);
		$this->assertTrue($result);
	}

	/**
	 * Bad test case to set origination_scheduled_date to null
	 * if the merchant is on ‘origination hold’.
	 */
	public function ptestupdateOrigScheDateforOrigHold_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '0000-00-00',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$readBefore = array();
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($expectedResult);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				'0000-00-00', 'A', '1');
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertFalse($numRecordsBefore == $numRecordsAfter);
		$this->assertGreaterThan($numRecordsBefore, $numRecordsAfter);
		$this->assertFalse($result);
	}

	public function ptestUpdateOrigScheDateForOrigHold_corner() {
		
	}

	/**
	 * Good Test function to return all customer_transactions in A status 
	 * and origination_scheduled_date = today and merchants.active != 1
	 */
	public function testgetOrigSchedAdjInactiveMerchants_Good() {
		$expected = array('CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'
			),
			'Merchant' => array(
				'active' => '0'
		));
		$output = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '2013-11-21', '0'
		);
		$this->assertEquals($output[0], $expected);
	}

	/**
	 * Bad Test function to return all customer_transactions in A status 
	 * and origination_scheduled_date = today and merchants.active != 1
	 */
	public function ptestgetOrigSchedAdjInactiveMerchants_Bad() {
		$expected = array('CustomerTransaction' => array(
			'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'
			),
			'Merchant' => array(
				'active' => '0'
		));
		$output = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'B', '2013-11-21', '1'
		);
		$this->assertEmpty($output);
	}

	/**
	 * Good test case to set  origination_scheduled_date to null
	 * for all customer_transactions in A status and 
	 * origination_scheduled_date = today and merchants.active != 1,
	 */
	public function ptestupdateOrigSchDateforInactiveMerch_Good() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'active' => '0'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '2013-11-21', '0'
		);
//		debug($readBefore);
//		die;
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore[0]);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '0000-00-00', '0'
		);
//		debug($readAfter);
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);
		$this->assertTrue($result);
	}

	/**
	 * Bad test case to set  origination_scheduled_date to null
	 * for all customer_transactions in A status and 
	 * origination_scheduled_date = today and merchants.active != 1,
	 */
	public function ptestupdateOrigSchDateforInactiveMerch_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'active' => '0'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'B', '2013-11-21', '1'
		);
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore[0]);
		$readAfter = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '0000-00-00', '0'
		);
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertFalse($result);
	}
}