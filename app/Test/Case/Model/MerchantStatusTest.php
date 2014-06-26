<?php
App::uses('MerchantStatus', 'Model');

/**
 * MerchantStatus Test Case
 *
 */
class MerchantStatusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.merchant_status'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MerchantStatus = ClassRegistry::init('MerchantStatus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MerchantStatus);

		parent::tearDown();
	}

}
