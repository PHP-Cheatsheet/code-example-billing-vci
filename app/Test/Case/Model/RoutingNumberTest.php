<?php
App::uses('RoutingNumber', 'Model');

/**
 * RoutingNumber Test Case
 *
 */
class RoutingNumberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
//	public $fixtures = array(
//		'app.routing_number'
//	);
//
//	public $autoFixtures = false;

	public $import = array('table' => 'routing_numbers', 'records' => true);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RoutingNumber = ClassRegistry::init('RoutingNumber');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RoutingNumber);

		parent::tearDown();
	}
/*
	public function testGetRoutingNum() {
		$myClass = $this->generate($this->RoutingNumber);
		$class = new ReflectionClass($myClass);
		$method = $class->getMethod('myPrivateFunc');
		$method->setAccessible(true);

		$actual = $method->invoke($myClass,
						array('id' => 2));
		$expected = 'admin';
		$this->assertEquals($expected, $actual);
		}
 * 
 */

	public function ptestGetRoutingNumbersWithoutFields() {
		$actual = $this->RoutingNumber->getRoutingNumbers();
		$expected = array(
									'id' => '1',
									'routing_number' => '655060042',
									'new_routing_number' => '000000000',
									'name' => 'SOCIAL SECURITY ADMINISTRATION');
		$this->assertEquals($expected['RoutingNumber']['id'], 
						$actual['RoutingNumber']['id']);

	}

	public function ptestsaveRoutingNum() {
		$expected = array( 
				'RoutingNumber' => array(
					'routing_number' => '223456789',
					'new_routing_number' => '000000000',
					'name' => 'MAC FEDERAL CREDIT UNION')
				);
		$actual = $this->RoutingNumber->saveRoutingNum($expected);
		$invalidFields = $this->RoutingNumber->invalidFields();
		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function ptestDeleteConfirmDeletedRoutingNum() {
		$routingNumber = '7223456789';
		$actual = $this->RoutingNumber->deleteConfirmDeletedRoutingNum($routingNumber);
		$this->assertFalse(empty($actual));
	}

	public function ptestGetIdFromRoutingNum() {
		$routingNumber = '223456789';
		$expected = 2;
		$actual = $this->RoutingNumber->getIdFromRoutingNum($routingNumber);
		$this->assertEquals($expected, $actual);
	}

	public function ptestgetRoutingNumbers() {
		$expected = '';
		$fields = array('fields' => 
				'routing_number, new_routing_number, name');
		$actual = $this->RoutingNumber->getRoutingNumbers($fields);
		$count = count($actual);
		$this->assertGreaterThan(0, $count);
		$this->assertArrayHasKey('routing_number',
						$actual[0]['RoutingNumber']);
		$this->assertArrayHasKey('new_routing_number',
						$actual[0]['RoutingNumber']);
		$this->assertArrayHasKey('name',
						$actual[0]['RoutingNumber']);
	}

	public function ptestgetFedAchThatExistsInRoutingNumberTable() {
		$method = new ReflectionMethod(
					'RoutingNumber', '__getFedAchThatExistsInRoutingNumberTable'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->RoutingNumber);
		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function ptestupdateNewRoutingNumbersField() {
		$method = new ReflectionMethod(
					'RoutingNumber', '__updateNewRoutingNumbersField'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->RoutingNumber);

		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function ptestinsertNewRoutingNumbers() {
		$method = new ReflectionMethod(
					'RoutingNumber', '__insertNewRoutingNumbers'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->RoutingNumber);

		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function ptestcheckRoutingNumNotInFedAchDir() {
		$actual = $this->RoutingNumber->deletedRoutingNumNotInFedAchDir();
		$invalidFields = $this->RoutingNumber->invalidFields();
		
		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function ptestcheckRoutingNumExistsInBlackList() {
		$actual = $this->RoutingNumber->checkRoutingNumExistsInBlackList();
		$invalidFields = $this->RoutingNumber->invalidFields();

		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}

	public function testtruncateTableFedAchDir() {
		$method = new ReflectionMethod(
					'RoutingNumber', '_truncateTable'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->RoutingNumber);
//		$actual = $this->RoutingNumber->_truncateTable();

		$this->assertFalse(empty($actual));
	}

}
