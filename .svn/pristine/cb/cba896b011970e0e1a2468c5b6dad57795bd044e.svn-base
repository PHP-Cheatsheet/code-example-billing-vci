<?php

/**
 * log add/edit and deletes of any model
 *
 * - actsAs = array("Logable"); on models that should be logged
 */

class TableBehavior extends ModelBehavior {

	public $user = null;

	public $UserModel = false;

	public $settings = array();

	public $defaults = array(
			'enabled' => true,
			'userModel' => 'User',
			'userKey' => 'userId',
			'field_name' => 'list',
			'description_ids' => true,
			'skip' => array(),
			'ignore' => array(),
			'classField' => 'table',
			'foreignKey' => 'table_primary_id');

	public $schema = array();

/**
 * Cake called intializer
 * Config options are :
 * userModel 		: 'User'. Class name of the user model you want to use (User by default), if you want to save User in log
 * userKey   		: 'userId'. The field for saving the user to (userId by default).
 * change    		: 'list' > [name, age]. Set to 'full' for [name (alek) => (Alek), age (28) => (29)]
 * description_ids 	: true. Set to false to not include model id and user id in the title field
 * skip  			: array(). String array of actions to not log
 * @param Object $Model
 * @param array $config
 */
	public function setup(&$Model, $config = array()) {
		if (!is_array($config)) {
			$config = array();
		}
		$this->settings[$Model->alias] = array_merge($this->defaults, $config);
		$this->settings[$Model->alias]['ignore'][] = $Model->primaryKey;
		$this->TableChange = & ClassRegistry::init('TableChange');
		if ($this->settings[$Model->alias]['userModel'] != $Model->alias) {
			$this->UserModel = & ClassRegistry::init($this->settings[$Model->alias]['userModel']);
		} else {
			$this->UserModel = $Model;
		}
		$this->schema = $this->TableChange->schema();
		App::import('Component', 'Auth');
		$this->user[$this->settings[$Model->alias]['userModel']] = AuthComponent::user();
	}

	public function settings(&$Model) {
		return $this->settings[$Model->alias];
	}

	public function enableLog(&$Model, $enable = null) {
		if ($enable !== null) {
			$this->settings[$Model->alias]['enabled'] = $enable;
		}
		return $this->settings[$Model->alias]['enabled'];
	}

/**
 * Useful for getting logs for a model, takes params to narrow find.
 * This method can actually also be used to find logs for all models or
 * even another model. Using no params will return all activities for
 * the models it is called from.
 *
 * Possible params :
 * 'model' 		: mixed  (null) String with className, null to get current or false to get everything
 * 'action' 	: string (null) String with action (add/edit/delete), null gets all
 * 'order' 		: string ('created DESC') String with custom order
 * 'conditions  : array  (array()) Add custom conditions
 * 'model_id'	: int	 (null) Add a int
 *
 * (remember to use your own user key if you're not using 'userId')
 * 'userId' 	: int 	 (null) Defaults to all users, supply id if you want for only one User
 *
 * @param Object $Model
 * @param array $params
 * @return array
 */
	public function findLog(&$Model, $params = array()) {
		$defaults = array(
		$this->settings[$Model->alias]['classField'] => null,
				'action' => null,
				'order' => 'created DESC',
		$this->settings[$Model->alias]['userKey'] => null,
				'conditions' => array(),
		$this->settings[$Model->alias]['foreignKey'] => null,
				'fields' => array(),
				'limit' => 50);
		$params = array_merge($defaults, $params);
		$options = array(
				'order' => $params['order'],
				'conditions' => $params['conditions'],
				'fields' => $params['fields'],
				'limit' => $params['limit']);
		if ($params[$this->settings[$Model->alias]['classField']] === null) {
			$params[$this->settings[$Model->alias]['classField']] = $Model->alias;
		}
		if ($params[$this->settings[$Model->alias]['classField']]) {
			if (isset($this->schema[$this->settings[$Model->alias]['classField']])) {
				$options['conditions'][$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
			} elseif (isset($this->schema['description'])) {
				$options['conditions']['description LIKE '] = $params[$this->settings[$Model->alias]['classField']] . '%';
			} else {
				return false;
			}
		}
		if ($params['action'] && isset($this->schema['action'])) {
			$options['conditions']['action'] = $params['action'];
		}
		if ($params[$this->settings[$Model->alias]['userKey']] && $this->UserModel && is_numeric($params[$this->settings[$Model->alias]['userKey']])) {
			$options['conditions'][$this->settings[$Model->alias]['userKey']] = $params[$this->settings[$Model->alias]['userKey']];
		}
		if ($params[$this->settings[$Model->alias]['foreignKey']] && is_numeric($params[$this->settings[$Model->alias]['foreignKey']])) {
			$options['conditions'][$this->settings[$Model->alias]['foreignKey']] = $params[$this->settings[$Model->alias]['foreignKey']];
		}
		return $this->TableChange->find('all', $options);
	}

/**
 * Get list of actions for one user.
 * Params for getting (one line) activity descriptions
 * and/or for just one model
 *
 * @example $this->Model->findUserActions(301,array('model' => 'BookTest'));
 * @example $this->Model->findUserActions(301,array('events' => true));
 * @example $this->Model->findUserActions(301,array('fields' => array('id','model'),'model' => 'BookTest');
 * @param Object $Model
 * @param int $userId
 * @param array $params
 * @return array
 */
	public function findUserActions(&$Model, $userId, $params = array()) {
		if (!$this->UserModel) {
			return null;
		}
		// if logged in user is asking for her own log, use the data we allready have
		if (isset($this->user) && isset($this->user[$this->UserModel->alias][$this->UserModel->primaryKey]) && $userId == $this->user[$this->UserModel->alias][$this->UserModel->primaryKey] && isset($this->user[$this->UserModel->alias][$this->UserModel->displayField])) {
			$username = $this->user[$this->UserModel->alias][$this->UserModel->displayField];
		} else {
			$this->UserModel->recursive = -1;
			$user = $this->UserModel->find(array(
			$this->UserModel->primaryKey => $userId));
			$username = $user[$this->UserModel->alias][$this->UserModel->displayField];
		}
		$fields = array();
		if (isset($params['fields'])) {
			if (is_array($params['fields'])) {
				$fields = $params['fields'];
			} else {
				$fields = array(
				$params['fields']);
			}
		}
		$conditions = array(
		$this->settings[$Model->alias]['userKey'] => $userId);
		if (isset($params[$this->settings[$Model->alias]['classField']])) {
			$conditions[$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
		}
		$data = $this->TableChange->find('all', array(
				'conditions' => $conditions,
				'recursive' => -1,
				'fields' => $fields));
		if (!isset($params['events']) || ( isset($params['events']) && $params['events'] == false )) {
			return $data;
		}
		$result = array();
		foreach ($data as $key => $row) {
			$one = $row['TableChange'];
			$result[$key]['TableChange']['id'] = $one['id'];
			$result[$key]['TableChange']['event'] = $username;
			// have all the detail models and change as list :
			if (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one['change']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) {
				if ($one['action'] == 'edit') {
					$result[$key]['TableChange']['event'] .= ' edited ' . $one['change'] . ' of ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';

					//	' at '.$one['created'];
				} elseif ($one['action'] == 'add') {
					$result[$key]['TableChange']['event'] .= ' added a ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'delete') {
					$result[$key]['TableChange']['event'] .= ' deleted the ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				}

			} elseif (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) { // have model,model_id and action
				if ($one['action'] == 'edit') {
					$result[$key]['TableChange']['event'] .= ' edited ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';

					//	' at '.$one['created'];
				} elseif ($one['action'] == 'add') {
					$result[$key]['TableChange']['event'] .= ' added a ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'delete') {
					$result[$key]['TableChange']['event'] .= ' deleted the ' . strtolower($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				}
			} else { // only description field exist
				$result[$key]['TableChange']['event'] = $one['description'];
			}
		}
		return $result;
	}

/**
 * Use this to supply a model with the data of the logged in User.
 * Intended to be called in AppController::beforeFilter like this :
 *
 * if ($this->{$this->modelClass}->Behaviors->attached('Logable')) {
 * $this->{$this->modelClass}->setUserData($activeUser);/
 * }
 *
 * The $userData array is expected to look like the result of a
 * User::find(array('id'=>123));
 *
 * @param Object $Model
 * @param array $userData
 */
	public function setUserData(&$Model, $userData = null) {
		if ($userData) {
			$this->user = $userData;
		}
	}

/**
 * Used for logging custom actions that arent crud, like login or download.
 *
 * @example $this->Boat->customLog('ship', 66, array('title' => 'Titanic heads out'));
 * @param Object $Model
 * @param string $action name of action that is taking place (dont use the crud ones)
 * @param int $id  id of the logged item (ie model_id in logs table)
 * @param array $values optional other values for your logs table
 */
	public function customLog(&$Model, $action, $id, $values = array()) {
		$logData['TableChange'] = $values;
		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']]) && is_numeric($id)) {
			$logData['TableChange'][$this->settings[$Model->alias]['foreignKey']] = $id;
		}
		$title = null;
		if (isset($values['title'])) {
			$title = $values['title'];
			unset($logData['TableChange']['title']);
		}
		$logData['TableChange']['action'] = $action;
		$this->saveLog($Model, $logData, $title);
	}

	public function clearUserData(&$Model) {
		$this->user = null;
	}

	public function setUserIp(&$Model, $userIP = null) {
		$this->userIP = $userIP;
	}

	public function beforeDelete(&$Model) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$Model->recursive = -1;
		$Model->read();
		return true;
	}

	public function afterDelete(&$Model) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$logData = array();
		if (isset($this->schema['description'])) {
			$logData['TableChange']['description'] = $Model->alias;
			if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
				$logData['TableChange']['description'] .= ' "' . $Model->data[$Model->alias][$Model->displayField] . '"';
			}
			if ($this->settings[$Model->alias]['description_ids']) {
				$logData['TableChange']['description'] .= ' (' . $Model->id . ') ';
			}
			$logData['TableChange']['description'] .= __('deleted');
		}
		$logData['TableChange']['action'] = 'delete';

		//$this->aftersave($Model, null);
		if (isset($this->schema['field_name'])) {
			//			$logData['TableChange']['change'] = '';
			$dbFields = array_keys($Model->schema());
			$changedFields = array();

			foreach ($Model->data[$Model->alias] as $key => $value) {
				//if (isset($Model->data[$Model->alias][$Model->primaryKey]) && !empty($this->old) && isset($this->old[$Model->alias][$key])) {
				$old = $Model->data[$Model->alias][$key];
				$changedFields[] = $key . ' (' . $old . ') => (' . $value . ')';
				$logData['TableChange']['field_name'] = $key;

				$logData['TableChange']['old_value'] = $value;
				$logData['TableChange']['new_value'] = '';

				$this->saveLog($Model, $logData);
			}
		}
	}

	public function beforeSave(&$Model) {
		if (isset($this->schema['field_name']) && $Model->id) {
			$this->old = $Model->find('first', array(
					'conditions' => array(
			$Model->alias . '.' . $Model->primaryKey => $Model->id),
					'recursive' => -1));
		}
		return true;
	}

	public function afterSave(&$Model, $created) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['add']) && $this->settings[$Model->alias]['skip']['add'] && $created) {
			return true;
		} elseif (isset($this->settings[$Model->alias]['skip']['edit']) && $this->settings[$Model->alias]['skip']['edit'] && !$created) {
			return true;
		}
		$keys = array_keys($Model->data[$Model->alias]);
		$diff = array_diff($keys, $this->settings[$Model->alias]['ignore']);
		if (count($diff) == 0 && empty($Model->logableAction)) {
			return false;
		}
		if ($Model->id) {
			$id = $Model->id;
		} elseif ($Model->insertId) {
			$id = $Model->insertId;
		}
		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']])) {
			$logData['TableChange'][$this->settings[$Model->alias]['foreignKey']] = $id;
		}
		if (isset($this->schema['description'])) {
			$logData['TableChange']['description'] = $Model->alias . ' ';
			if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
				$logData['TableChange']['description'] .= '"' . $Model->data[$Model->alias][$Model->displayField] . '" ';
			}

			if ($this->settings[$Model->alias]['description_ids']) {
				$logData['TableChange']['description'] .= '(' . $id . ') ';
			}

			if ($created) {
				$logData['TableChange']['description'] .= __('added');
			} else {
				$logData['TableChange']['description'] .= __('updated');
			}
		}
		if (isset($this->schema['action'])) {
			if ($created) {
				$logData['TableChange']['action'] = 'add';
			} else {
				$logData['TableChange']['action'] = 'edit';
			}
		}

		if (isset($this->schema['field_name'])) {
			//			$logData['TableChange']['change'] = '';
			$dbFields = array_keys($Model->schema());
			$changedFields = array();
			foreach ($Model->data[$Model->alias] as $key => $value) {

				if (isset($Model->data[$Model->alias][$Model->primaryKey]) && !empty($this->old) && isset($this->old[$Model->alias][$key])) {
					$old = $this->old[$Model->alias][$key];
				} else {
					$old = '';
				}
				if ($key != 'modified' && !in_array($key, $this->settings[$Model->alias]['ignore']) && $value != $old && in_array($key, $dbFields)) {
					if ($this->settings[$Model->alias]['change'] == 'full') {
						$changedFields[] = $key . ' (' . $old . ') => (' . $value . ')';

					} else if ($this->settings[$Model->alias]['change'] == 'serialize') {
						$changedFields[$key] = array(
								'old' => $old,
								'value' => $value);
					} else {
						$changedFields[] = $key . ' (' . $old . ') => (' . $value . ')';
						$logData['TableChange']['field_name'] = $key;
						$logData['TableChange']['old_value'] = $old;
						$logData['TableChange']['new_value'] = $value;

						$this->saveLog($Model, $logData);
					}
				}
			}
		}
	}

/**
 * Does the actual saving of the Log model. Also adds the special field if possible.
 *
 * If model field in table, add the Model->alias
 * If action field is NOT in table, remove it from dataset
 * If the userKey field in table, add it to dataset
 * If userData is supplied to model, add it to the title
 *
 * @param Object $Model
 * @param array $logData
 */
	public function saveLog(&$Model, $logData, $title = null) {
		$logData['TableChange']['timestamp'] = date("Y-m-d H:i:s");
		if ($title !== null) {
			$logData['TableChange']['title'] = $title;
		} elseif ($Model->displayField == $Model->primaryKey) {
			$logData['TableChange']['title'] = $Model->alias . ' (' . $Model->id . ')';
		} elseif (isset($Model->data[$Model->alias][$Model->displayField])) {
			$logData['TableChange']['title'] = $Model->data[$Model->alias][$Model->displayField];
		} else {
			$logData['TableChange']['title'] = $Model->field($Model->displayField);
		}

		if (isset($this->schema[$this->settings[$Model->alias]['classField']])) {
			// by miha nahtigal
			$logData['TableChange'][$this->settings[$Model->alias]['classField']] = $Model->name;
		}

		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']]) && !isset($logData['TableChange'][$this->settings[$Model->alias]['foreignKey']])) {
			if ($Model->id) {
				$logData['TableChange'][$this->settings[$Model->alias]['foreignKey']] = $Model->id;
			} elseif ($Model->insertId) {
				$logData['TableChange'][$this->settings[$Model->alias]['foreignKey']] = $Model->insertId;
			}
		}

		if (!isset($this->schema['action'])) {
			unset($logData['TableChange']['action']);
		} elseif (isset($Model->logableAction) && !empty($Model->logableAction)) {
			$logData['TableChange']['action'] = implode(',', $Model->logableAction); // . ' ' . $logData['Log']['action'];
			unset($Model->logableAction);
		}

		if (isset($this->schema['versionID']) && isset($Model->versionID)) {
			$logData['TableChange']['versionID'] = $Model->versionID;
			unset($Model->versionID);
		}

		if (isset($this->schema['ip']) && $this->userIP) {
			$logData['TableChange']['ip'] = $this->userIP;
		}

		if (isset($this->schema[$this->settings[$Model->alias]['userKey']]) && $this->user) {
			$logData['TableChange'][$this->settings[$Model->alias]['userKey']] = $this->user[$this->UserModel->primaryKey];
		}

		if (isset($this->schema['description'])) {
			if ($this->user && $this->UserModel) {
				$logData['TableChange']['description'] .= ' by ' . $this->settings[$Model->alias]['userModel'] . ' "' . $this->user[$this->UserModel->displayField] . '"';
				if ($this->settings[$Model->alias]['description_ids']) {
					$logData['TableChange']['description'] .= ' (' . $this->user[$this->UserModel->primaryKey] . ')';
				}

			} else {
				// UserModel is active, but the data hasnt been set. Assume system action.
				$logData['TableChange']['description'] .= ' by System';
			}
			$logData['TableChange']['description'] .= '.';
		}

		$this->TableChange->create($logData);
		$this->TableChange->save(null, array(
				'validate' => false,
				'callbacks' => false));
	}
/**
 * creates  Activities . Also adds the special field in db according to the change made by user
 * saves every fields in table user activity.
 */
	public function saveActivities(&$Model, $userData = null) {
		//if user is logged in
		if ($userData['id']) {
			//gets the browser used by user
			$this->request->data['UserActivity']['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
			//gets the user ip
			$this->request->data['UserActivity']['user_ip'] = $_SERVER['REMOTE_ADDR'];
			//gets the login user id
			$this->request->data['UserActivity']['user_id'] = $userData['id'];
			//gets the acessed URl From browser
			$this->request->data['UserActivity']['url'] = $_SERVER['REQUEST_URI'];
			$this->request->data['UserActivity']['timestamp'] = date("Y-m-d H:i:s");
			App::import('model','UserActivity');
			$this->UserActivity = new UserActivity();
			$this->UserActivity->create($this->request->data);
			$this->UserActivity->save(null, array(
				'validate' => false,
				'callbacks' => false));
		}
	}
}