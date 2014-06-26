<?php
App::uses('RoutingNumbersBlackList', 'Model');

/**
 * RoutingNumbersBlackList Test Case
 *
 */
class RoutingNumbersBlackListTest extends CakeTestCase {

	public $import = array('table' => 'routing_numbers_black_list', 'records' => true);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RoutingNumbersBlackList = ClassRegistry::init('RoutingNumbersBlackList');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RoutingNumbersBlackList);

		parent::tearDown();
	}

	public function ptestSaveBlackListedRoutingNum() {
		$expected = array(
				array('routing_number' => '011000015',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '011100481',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '021001208',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '031000040',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '031099996',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '041000014',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '042000437',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '043000300',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '051000033',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '051099992',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '052000278',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '053000206',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '061000146',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '062000190',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '063000199',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '064000101',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '065000210',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '066000109',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '071000301',
				'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '081000045',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '091000080',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '101000048',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '102000199',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '111000038',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '112000011',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '113000049',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '114000721',
						'reason' => 'FEDERAL RESERVE BANK'),
				array('routing_number' => '121000374',
						'reason' => 'FEDERAL RESERVE BANK'));
		$result = $this->RoutingNumbersBlackList->saveBlackListedRoutingNum($expected);
		$invalidFields = $this->RoutingNumbersBlackList->invalidFields();
		$this->assertFalse(empty($result));
		$this->assertEmpty($invalidFields);
	}

	public function testgetBlackListedRoutingNumbers() {
		$fields = array('routing_number');
		$result = $this->RoutingNumbersBlackList->getBlackListedRoutingNumbers($fields);
		$count = count($result);
		$this->assertGreaterThan(0, $count);
		$this->assertArrayHasKey('routing_number',
						$result[0]['RoutingNumbersBlackList']);
	}
}
