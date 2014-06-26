<?php
App::uses('DeletedRoutingNumbers', 'Model');

/**
 * DeletedRoutingNumbers Test Case
 *
 */
class DeletedRoutingNumbersTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
//	public $fixtures = array(
//		'app.deleted_routing_number'
//	);

	public $import = array('table' => 'deleted_routing_numbers',
			'records' => true);
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DeletedRoutingNumbers = ClassRegistry::init('DeletedRoutingNumbers');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DeletedRoutingNumber);

		parent::tearDown();
	}

//	public function testgetDeletedRoutingNum() {
//		$result = $this->DeletedRoutingNumber->getDeletedRoutingNum();
//		$this->assertFalse(empty($result));
//	}
	
	public function testsaveRoutingNum() {
		$expected = array('DeletedRoutingNumber' => array(
			'routing_number' => '223456789',
			'comment' => 'deleted',
			'delete_status' => 0,
			'creation_date' => ''
		));

		$result = $this->DeletedRoutingNumbers->saveTransaction($expected);
		$invalidFields = $this->DeletedRoutingNumbers->invalidFields();

		$this->assertFalse(empty($result));
		$this->assertEmpty($invalidFields);
	}

	public function testupdateDeleteStatusToDeleted() {
		$expected = array('routing_number' => '223456789');

		$result = $this->DeletedRoutingNumbers->updateDeleteStatusToDeleted($expected);
		$invalidFields = $this->DeletedRoutingNumbers->invalidFields();

		$this->assertFalse(empty($result));
		$this->assertEmpty($invalidFields);
	}
	
}
