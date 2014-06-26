<?php
App::uses('FedAchDir', 'Model');

/**
 * FedAchDir Test Case
 *
 */
class FedAchDirTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
//	public $fixtures = array(
//		'app.fed_ach_dir'
//	);

	public $import = array('table' => 'fed_ach_dir', 'records' => true);
	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FedAchDir = ClassRegistry::init('FedAchDir');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FedAchDir);

		parent::tearDown();
	}

	public function ptestreadAchFile() {
		$expected = array("0" => "011000015O0110000150020802000000000FEDERAL RESERVE BANK                1000 PEACHTREE ST N.E.              ATLANTA             GA303094470866234568111     ",
				'1' => '011000028O0110000151072811000000000STATE STREET BANK AND TRUST COMPANY JAB2NW                              N. QUINCY           MA021710000617664240011     ');
		$method = new ReflectionMethod(
					'FedAchDir', '__readAchFile'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->FedAchDir);
		$this->assertEquals($expected[0], $actual[0]);
	}

	public function ptestchangeACHFileToArray() {
		$method = new ReflectionMethod(
					'FedAchDir', '__changeACHFileToArray'
				);
		$method->setAccessible(true);
		$data = array("0" => "011000015O0110000150020802000000000FEDERAL RESERVE BANK                1000 PEACHTREE ST N.E.              ATLANTA             GA303094470866234568111     ",
				'1' => '011000028O0110000151072811000000000STATE STREET BANK AND TRUST COMPANY JAB2NW                              N. QUINCY           MA021710000617664240011     ');		$actual = $method->invoke($this->FedAchDir, $data);
		$count = count($actual);
		$this->assertGreaterThan(0, $count);
		$this->assertArrayHasKey('routing_number',
						$actual[0]);
		$this->assertFalse(empty($actual[0]));
	}

	public function ptestsaveFedAchDir() {
		$data = array('0' => "011000015O0110000150020802000000000FEDERAL RESERVE BANK                1000 PEACHTREE ST N.E.              ATLANTA             GA303094470866234568111     ",
				'1' => '011000028O0110000151072811000000000STATE STREET BANK AND TRUST COMPANY JAB2NW                              N. QUINCY           MA021710000617664240011     ',
				'2' => '011000138O0110000151101310000000000BANK OF AMERICA, N.A.               8001 VILLA PARK DRIVE               HENRICO             VA232280000800446013511     ');
		$method = new ReflectionMethod(
					'FedAchDir', '__changeACHFileToArray'
				);
		$method->setAccessible(true);
		$expected = $method->invoke($this->FedAchDir, $data);
		
		$method = new ReflectionMethod(
					'FedAchDir', '__saveFedAchDir'
				);
		$method->setAccessible(true);
		$actual = $method->invoke($this->FedAchDir, $expected);
	
		$count = count($actual);
		$this->assertGreaterThan(0, $count);
		$this->assertArrayHasKey('routing_number',
						$actual['FedAchDir']);
		$this->assertFalse(empty($actual['FedAchDir']));
	}

	public function ptestpopulateFedAchDir() {
		$actual = $this->FedAchDir->populateFedAchDir();
		$count = count($actual);
		$invalidFields = $this->FedAchDir->invalidFields();
		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
		
	}

	public function testgetRoutingNumberDoesNotExistsInRoutingNumberTable() {
			$actual = $this->FedAchDir->getRoutingNumberDoesNotExistsInRoutingNumberTable();
		$count = count($actual);
		$invalidFields = $this->FedAchDir->invalidFields();
		$this->assertFalse(empty($actual));
		$this->assertEmpty($invalidFields);
	}
}
