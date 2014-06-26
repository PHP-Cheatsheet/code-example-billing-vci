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
 * @version $$Id: Iso.php 1682 2013-09-26 04:14:11Z sisir $$
 */

App::uses('AppModel', 'Model');

/**
 * Iso Model
 *
 */
class Iso extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';

	public $useDbConfig = 'echecksRead';

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'iso';

//	public $actsAs = array( 
//	'Containable', 
//	'Autocache.Autocache' => array('default_cache' => 'daily')
//	);


	public $validate = array(
		'isoNumber' => array(
				'isNumeric' => array(
					'rule' => 'numeric',
					'message' => 'Please Enter a valid ISO Number. Must be only Numbers'
				),
				'isNonNegative' => array(
					'rule' => '@^(?:[1-9][0-9]*|0)$@',
					'message' => 'Please Enter a valid ISO Number. Must be Non negative Numbers'
				)
			),
		'name' => array(
				'rule' => array(
						'rule1' => 'alphaNumeric',
						'rule2' => '/([\w.-]+ )+[\w+.-]/'
				),
				'required' => true,
				'message' => 'Please Enter a valid ISO Name.'
		),
		'isoPIN' => array(
				'isInteger' => array(
						'rule' => '@^(?:[1-9][0-9]*|0)$@',
						'message' => 'Only Integer.'),
				'allowedCharacters'=>  array(
						'rule' => '/^[0-9]{1,5}$/i',
						'message' => 'Minimum 1 digit to Maximum 5 digits'),
				'required' => true,
		),
		'fedid' => array(
				'rule' => 'alphaNumeric',
				'required' => true,
				'message' => 'Only Letters or Numbers.'
		),
		'address' => array(
				'rule' => array(
						'rule1' => 'alphaNumeric',
						'rule2' => '/([\w.-]+ )+[\w+.-]/'
				),
				'required' => true,
				'message' => 'Please Enter a valid Address.'
		),
		'address2' => array(
				'rule' => array(
						'rule1' => 'alphaNumeric',
						'rule2' => '/([\w.-]+ )+[\w+.-]/'
				),
				'message' => 'Please Enter a valid Address.'
		),
		'city' => array(
				'rule' => array(
						'rule1' => 'alphaNumeric',
						'rule2' => '/([\w.-]+ )+[\w+.-]/'
				),
				'message' => 'Please Enter a valid Name.'
		),
		'zip' => array(
				'rule' => array('postal', null, 'us'),
				'required' => true,
				'message' => 'Please Enter a valid ZIP Code.'
		),
		'phone_w' => array(
				'rule' => array('phone', null, 'us'),
				'required' => true,
				'message' => 'Please Enter a valid Phone Number.'
		),
		'phone_c' => array(
				'rule' => array('phone', null, 'us'),
				'required' => true,
				'message' => 'Please Enter a valid Cell Number.'
		),
		'phone_f' => array(
				'rule' => array('phone', null, 'us'),
				'required' => true,
				'message' => 'Please Enter a valid Fax Number.'
		),
		'contact' => array(
				'rule' => array(
						'rule1' => 'alphaNumeric',
						'rule2' => '/([\w.-]+ )+[\w+.-]/'
				),
				'required' => true,
				'message' => 'Please Enter a valid Contact Name.'
		),
		'email' => array(
				'rule' => array('email'),
				'message' => 'Please supply a valid email address.'
		),
		'email2' => 'email',
		'email3' => 'email',
		'funding_change_email' => 'email',
		'website' => array(
				'rule' => 'url',
				'message' => 'Invalid Web Address.'
		),
		'bank_change_fee' => array(
				'rule' => 'numeric',
				'required' => true,
				'message' => 'Can Only Be Numbers'
		),
		'bankRouteNum' => array(
				'rule' => 'alphaNumeric',
				'required' => true,
				'message' => 'Only Letters or Numbers.'
		),
		'bankAcctNum' => array(
				'rule' => 'alphaNumeric',
				'required' => true,
				'message' => 'Only Letters or Numbers.'
		),
	);

	public function editIso($data) {
		$this->useDbConfig = 'echecksWrite';

		return $this->save($data);
	}

	public function getAssignedIsos($conditions = null) {
			$this->unbindModelAll();
			return $this->find(
					'all', array('conditions' => array('OR' => array($conditions))));
	}

	/**
	 * Get all ISOs
	 * @return array
	 */
	public function getIso() {
			$this->unbindModelAll();
			return $this->find(
					'all', array('order'=>'isoNumber ASC','autocache' => false));
	}

	public function getIsoMP($lastMP = null) {
			$this->unbindModelAll();
			$lastMP = $lastMP == null ? 0 : $lastMP;
			return $this->find(
					'all', array('fields' => array('name', 'contact', 'id'),
					'conditions' => array('id > ' => $lastMP)
			));
	}

	public function getnewId($num = null) {
			$this->unbindModelAll();
			$data = $this->find(
					'first', array(
							'fields' => 'id', 
							'conditions' => array('isoNumber = ' . $num)));
			return $data;
	}

	public function getnewIso($num = null) {
			$this->unbindModelAll();
			$data = $this->find(
					'first', array(
							'fields' => 'isoNumber', 
							'conditions' => array('id = ' . $num)));
			return $data;
	}

}
