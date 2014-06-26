<?php
App::uses('MerchantHistory', 'Model');

/**
 * MerchantHistory Test Case
 *
 */
class MerchantHistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.merchant_history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MerchantHistory = ClassRegistry::init('MerchantHistory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MerchantHistory);

		parent::tearDown();
	}

}
