<?php
/**
 * VERICHECK INC CONFIDENTIAL
 * 
 * Vericheck Incorporated 
 * All Rights Reserved.
 * 
 * NOTICE: 
 * All information contained herein is, and remains the property of 
 * Vericheck Inc, if any.  The intellectual and technical concepts 
 * contained herein are proprietary to Vericheck Inc and may be covered 
 * by U.S. and Foreign Patents, patents in process, and are protected 
 * by trade secret or copyright law. Dissemination of this information 
 * or reproduction of this material is strictly forbidden unless prior 
 * written permission is obtained from Vericheck Inc.
 *
 * @copyright VeriCheck, Inc. 
 * @version $$Id: BillingJournal.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * BillingJournal Model
 *
 */
class BillingJournal extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'echecksRead';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'billing_journal';
	
	public $actsAs = array( 
	'Containable', 
	'Autocache.Autocache' => array('default_cache' => 'daily')
	);

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';


	public function getHistory($date) {
		$this->unbindModelAll();
		return $this->find(
		'all',
				array(
					'autocache' => true,
					'conditions' => array(
										'and' => array('LEFT(post_time,4) >' => $date['endYear'],
												'LEFT(post_time,4) <' => $date['startYear']	)
									),
				)
		);
	}

}
