<?php
/**
 * VERICHECK INC CONFIDENTIAL
 * 
 * Vericheck Incorporated 
 * All Rights Reserved.
 * 
 * NOTICE: 
 * All information contained herein is, and remainsa the property of 
 * Vericheck Inc, if any.  The intellectual and technical concepts 
 * contained herein are proprietary to Vericheck Inc and may be covered 
 * by U.S. and Foreign Patents, patents in process, and are protected 
 * by trade secret or copyright law. Dissemination of this information 
 * or reproduction of this material is strictly forbidden unless prior 
 * written permission is obtained from Vericheck Inc.
 *
 * @copyright VeriCheck, Inc. 
 * @version $$Id: AppModel.php 1694 2013-09-26 09:26:01Z anit $$
 */

App::uses('Model', 'Model');

App::uses('Logger', '/Lib/Log');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package yapp.Model
 */
class AppModel extends Model {

	// comment -anit
	// this is giving error while executing Billing
	// uncomment for use.
	/*public $actsAs = array( 'Table' => array('userModel' => 'User', 'userKey' => 'user_id',
	'change' => 'list', // options are 'list' or 'full'
	'description_ids' => true // options are TRUE or FALSE
	));*/

	protected $_log;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		/*
		 * While Uncommenting the object instance
		 * generates an error "Cannot create instance
		 * of log, as it is abstract or is an interface".
		 * This should not be an error as the implemetation
		 * does not have any flaws. It only occurs when
		 * called from controller.
		 */
//		$this->_log = new Logger();
	}

	

	/**
	 *
	 * filter the relation to be unbinded according to filter parameters
	 * @param $exceptions parameter to be avoided
	 * @param $relations realtion array
	 */
	private function __filterRelations($exceptions, $relations) {
		foreach ($exceptions as $exception) {
				foreach ($relations as $relation => $model) {
					//$modelNum = count(array_keys($model));
					$keyString = array_search($exception, $model);
					if ($keyString !== false) {
						$keyReln = array_search($model, $relations);
						unset($relations[$keyReln]);
					}
				}
			}
			return $relations;
	}

	/**
	 * 
	 * @throws Exception
	 */
	private function __switchDataSourceToRead() {
		$database = "";
		if (isset($this->getDataSource()->config['database'])) {
			$database = $this->getDataSource()->config['database'];
		} elseif (isset($this->schemaName)) {
			$database = $this->schemaName;
		} else {
			throw new Exception("No datasource found");
		}

		$this->setDataSource($database . 'Read');
	}

	/**
	 * 
	 * @throws Exception
	 */
	private function __switchDataSourceToWrite() {
		$database = "";
		if (isset($this->getDataSource()->config['database'])) {
			$database = $this->getDataSource()->config['database'];
		} elseif (isset($this->schemaName)) {
			$database = $this->schemaName;
		} else {
			throw new Exception("No datasource found");
		}

		$this->setDataSource($database . 'Write');
	}

	public function delete($data = null, $validate = true, $fieldList = array()) {
		$this->__switchDataSourceToWrite();
		$updated = parent::delete($data, $validate, $fieldList);
		$this->__switchDataSourceToRead();
		return $updated;
	}

	public function deleteAll($conditions = null, $cascade = true,
					$callbacks = false) {
			$this->__switchDataSourceToWrite();
			$updated = parent::deleteAll($conditions, $cascade, $callbacks);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	public function save($data = null, $validate = true, $fieldList = array()) {
		$this->__switchDataSourceToWrite();
		$updated = parent::save($data, $validate, $fieldList);
		$this->__switchDataSourceToRead();
		return $updated;
	}

	public function saveAll($data = null, $options = array()) {
			$this->__switchDataSourceToWrite();
			$updated = parent::saveAll($data, $options);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	public function saveAssociated($data = null, $options = array()) {
			$this->__switchDataSourceToWrite();
			$updated = parent::saveAssociated($data, $options);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	public function saveField($fieldName = null, $fieldValue = null, $validate = false) {
			$this->__switchDataSourceToWrite();
			$updated = parent::saveField($fieldName, $fieldValue, $validate);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	public function saveMany($data = null, $options = array()) {
			$this->__switchDataSourceToWrite();
			$updated = parent::saveMany($data, $options);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	/**
	 * to unbind all the relations
	 * @param Array $exceptions It will determine which relations not to delete or keep it bind
	 */
	public function unbindModelAll($exceptions = null) {
		$relations = array(
					'hasOne' => array_keys($this->hasOne),
					'hasMany' => array_keys($this->hasMany),
					'belongsTo' => array_keys($this->belongsTo),
					'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany)
		);
		if ($exceptions != null) {
				$relations = $this->__filterRelations($exceptions,$relations);
		}
		foreach ($relations as $relation => $model) {
			$this->unbindModel(array($relation => $model), true);
		}
	}

	public function updateAll($fields = array(), $conditions = array()) {
			$this->__switchDataSourceToWrite();
			$updated = parent::updateAll($fields, $conditions);
			$this->__switchDataSourceToRead();
			return $updated;
	}

	
}
