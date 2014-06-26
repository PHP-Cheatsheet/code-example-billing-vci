<?php
App::uses('Odfi', 'Model');

/**
 * Odfi Test Case
 *
 */
class OdfiTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.odfi'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Odfi = ClassRegistry::init('Odfi');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Odfi);

		parent::tearDown();
	}

}
