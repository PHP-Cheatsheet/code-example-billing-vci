<?php
App::uses('SequentialWorkflow', 'Lib/EOD');

class SequentialWorkflowTest extends CakeTestCase {

		public $import = array('table' => 'workflow_eod',
				'records' => true);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SequentialWorkflow = new SequentialWorkflow();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SequentialWorkflow);
		parent::tearDown();
	}

	/**
	 * 
	 */
	public function testExecutedSuccessfully() {
		$this->SequentialWorkflow->queue = array('orgination',
				'settlement');

		$actual = $this->SequentialWorkflow->start();

		$this->assertFalse(empty($actual));
		$this->assertEqual($actual, 'success');
	}

}