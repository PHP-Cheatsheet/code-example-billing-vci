<?php

App::import('model', 'ColumnVisibility');
App::import('model', 'MapUserType');

class MainViewsComponent extends Component {

	public $paginate;
	public $components = array('Session');

	/**
	 * Fetch all the data from the Columnvisibility table for passed Id and Columnvisibility field[tablename]
	 * @param string $id  logged on User id, $tablename field name
	 * @return Array
	 */
	public function checkColumn($id, $tablename) {
		$this->ColumnVisibility = new ColumnVisibility();
		$columnVisibilityData = $this->ColumnVisibility->getColumnVisData($id, $tablename);
		return $columnVisibilityData;
	}

	/**
	 * Stores the data into the Columnvisibility table for passed Id and Columnvisibility field[tablename]
	 * @param string $id logged on User id, $tablename field name ;
	 * @param Array $label consist of default label heading to be stored
	 * @return Array
	 */
	public function defaultColumn($label, $id, $tablename) {
		$i = 0;
		$this->ColumnVisibility = new ColumnVisibility();
		$columnVisibilityData = $this->ColumnVisibility->getColumnVisData($id, $tablename);
		$cntLabel = count($label);
		for ($i = 0; $i < $cntLabel; $i++) {
			$this->ColumnVisibility->create();
			$columnVisibilityData['ColumnVisibility']['user_id'] = $id;
			$columnVisibilityData['ColumnVisibility']['table_name'] = $tablename;
			$columnVisibilityData['ColumnVisibility']['column_name'] = $label[$i];
			$this->ColumnVisibility->editcolumnvisibility($columnVisibilityData);
		}
	}

	/**
	 * Stores the data into the Columnvisibility table for passed Id and Columnvisibility field[tablename]
	 * @param string $id logged on User id, $tablename field name ;
	 * @param Array $data consist of checked label heading chosen by user
	 * @return Array
	 */
	public function assignVisibility($data, $id, $tablename) {
		$this->ColumnVisibility = new ColumnVisibility();
		$columnVisibilityData = $this->ColumnVisibility->getColumnVisData($id, $tablename);
		if (!empty($columnVisibilityData)) {
			if ($columnVisibilityData[0]['ColumnVisibility']['user_id'] == $id &&
					$columnVisibilityData[0]['ColumnVisibility']['table_name'] == $tablename) {
				$conditions = array('table_name' => $tablename, 'user_id' => $id);
				$this->ColumnVisibility->deletecolumnvisibility($conditions);
			}
			$newId = $col['ColumnVisibility']['id'];
			$this->__assign($data, $id, $tablename);
		} else {
			$this->__assign($data, $id, $tablename);
		}
	}

	/**
	 * Stores the data into the Columnvisibility table for passed Id and Columnvisibility field[tablename]
	 * Function called by assignVisibility
	 * @param string $id logged on User id, $tablename field name ;
	 * @param Array $data consist of checked label heading chosen by user
	 * @return Array
	 */
	private function __assign($data, $id, $tablename) {
		$this->ColumnVisibility = new ColumnVisibility();
		$columnVisibilityData = $this->ColumnVisibility->getColumnVisData($id, $tablename);
		foreach ($data as $datas => $key) {
			$this->ColumnVisibility->create();
			if ($key == 1) {
				$this->ColumnVisibility->create();
				$columnVisibilityData['ColumnVisibility']['user_id'] = $id;
				$columnVisibilityData['ColumnVisibility']['table_name'] = $tablename;
				$columnVisibilityData['ColumnVisibility']['column_name'] = $datas;
				$this->ColumnVisibility->editcolumnvisibility($columnVisibilityData);
			}
		}
	}

	/**
	 * Checks and customise fields to be stored in session variable accordingly
	 * @param array $data All the search parameters passed from searchbox
	 * @param string $model model name Merchant, Transaction, Iso
	 */
	public function checkFields($data, $model) {
		$metacond = false;
		if (empty($data)) { //deletes the session is search is made with blank parameters
			$this->Session->delete('Search' . $model . 'filters');
			$this->Session->delete('Metaphone' . $model . 'Search');
		} else { //if data are passed through the filter params from dropselect.js
			if ($model == 'Transaction') {
				$meta = $this->__transData($data, $model, $modelID);
				$data = $meta;
				
			} else {
				if ((array_key_exists('Search_' . $model . '_Name', $data) && $data['Search_' . $model . '_Name'] != "") ||
						(array_key_exists('Search_Contact_Name', $data) && $data['Search_Contact_Name'] != "")) {
					$modelID = strtolower($model) . '_id';
					$data = $this->__searchMetaphone($data, $model, $modelID,'2');
				}
			}

			$datelabel = array('Issue_Date', 'Creation_Date', 'Settle_Date', 'Settlement_Date', 'Settlement_OrgDate');
			for ($cnt = 0; $cnt < 5; $cnt++) {
				if (array_key_exists('Search_start_' . $datelabel[$cnt], $data)) {
					$startdate = $data['Search_start_' . $datelabel[$cnt]];
					$enddate = $data['Search_end_' . $datelabel[$cnt]];
					$actDate = $this->__date($data, $startdate, $enddate, $datelabel[$cnt]);
					$data = $actDate;
				}
			}
			$this->Session->write('Search' . $model . 'filters', $data);
		}
	}

	private function __transData($data, $model, $modelID) {
		if ((array_key_exists('Search_Customer_Name', $data) && $data['Search_Customer_Name'] != "") ||
				(array_key_exists('Search_Account_Num', $data) && $data['Search_Account_Num'] != "")) {
			$modelID = 'transaction_id';
			$metaData = $this->__searchMetaphone($data, $model, $modelID,'1');
			$data = $metaData;
		}
		if ((array_key_exists('Search_Merchant_Name', $data) && $data['Search_Merchant_Name'] != "")) {
			$model = 'Merchant';
			$modelID = strtolower($model) . '_id';
			$metaData = $this->__searchMetaphone($data, $model, $modelID,'1');
			$count = count($metaData['meta']['Search_Merchant']);
			$metaData['meta']['Search_Merchant'][$count] = 'trans';
			$data = $metaData;
		}
		return $data;
	}

	/**
	 *
	 * Used for metaphone while searching with two date fields Startdate and end date
	 * @param array $data date search parameters
	 * @param $startdate
	 * @param $enddate
	 * @param $datelabel
	 */
	private function __date($data, $startdate, $enddate, $datelabel) {
		if ($startdate != "" && $enddate != "") {
			$data['Search_' . $datelabel] = array($startdate, $enddate);
		} elseif ($startdate != "" && $enddate == "") {
			$data['Search_' . $datelabel] = $startdate;
		} elseif ($startdate == "" && $enddate != "") {
			$data['Search_' . $datelabel] = $enddate;
		}
		return $data;
	}

	/**
	 *
	 * Search of metaphone with specific fields
	 * @param array $name
	 * @param string $passedmodel: model Name
	 * @param string $modelID
	 */
	private function __searchMetaphone($name, $passedmodel, $modelID,$type) {
		if ($passedmodel == 'ISO') {
			$passedmodel = 'Iso';
		}
		foreach ($name as $namearray => $val) {
			if ($namearray == 'Search_' . $passedmodel . '_Name') {
				$metacondition[] = array(strtolower($passedmodel) . '_name_mphone' . " like '" . metaphone($val) . "%'");
			} elseif ($namearray == 'Search_Contact_Name') {
				$metacondition[] = array('contact_name_mphone' . " like '" . metaphone($val) . "%'");
			} elseif ($namearray == 'Search_Customer_Name') {
				$metacondition[] = array('customer_name_mphone' . " like '" . metaphone($val) . "%'");
			} elseif ($namearray == 'Search_Merchant_Name') {
				$metacondition[] = array('merchant_name_mphone' . " like '" . metaphone($val) . "%'");
			} elseif ($namearray == 'Search_Account_Num') {
				$metacondition[] = array('account_number_last_four_digits' . " like '" . substr($val, -4) . "%'");
			} elseif ($namearray == 'Search_ISO_Name') {
				$metacondition[] = array('iso_name_mphone' . " like '" . metaphone($val) . "%'");
			}
		}
		$sessionArray = $name;
		
		$name = $this->__subMetaphone($metacondition, $passedmodel, $name, $modelID);
		
		if($type =="1" && $passedmodel =="Merchant"){
			$passedmodel = 'Transaction';
		}
		$this->Session->write('Metaphone' . $passedmodel . 'Search', $sessionArray);
		return $name;
	}

	private function __subMetaphone($metacondition, $passedmodel, $name, $modelID) {
		$model = $passedmodel . 'Auxiliary';
		App::import('model', $model);
		$this->$model = new $model();
		$metaphoneValue = $this->$model->find('all', array('conditions' => $metacondition));
		//		//error handling for condition when the metaphone is not found. it simply makes search for the input string
		if (empty($metaphoneValue) !== true) {
			foreach ($metaphoneValue as $key => $val) {
				$mappedid[] = array($metaphoneValue[$key][$model][$modelID]);
			}
			if (array_key_exists('Search_Merchant_Name', $name)) {
				$name['Search_Merchant_Name'] = "";
			}
			if (array_key_exists('Search_Contact_Name', $name)) {
				$name['Search_Contact_Name'] = "";
			}
			if (array_key_exists('Search_Customer_Name', $name)) {
				$name['Search_Customer_Name'] = "";
			}
			if (array_key_exists('Search_Account_Num', $name)) {
				$name['Search_Account_Num'] = "";
			}
			if (array_key_exists('Search_ISO_Name', $name)) {
				$name['Search_ISO_Name'] = "";
			}
			$name['meta']['Search_' . $passedmodel] = $mappedid;
		}
		return $name;
	}

	/**
	 * Fetch the data from Mapusertype table and displays only permitted data
	 * eg: if for model merchant there is 2 MID for the logged on User then User can see only 2 merchants data
	 * @param string $model name of the model
	 * @return Array
	 */
	public function checkUser($model) {
		$this->MapUserType = new MapUserType();
		$mapdatas = $this->MapUserType->getData($this->Session->read('Auth.User.id'), $model);

		foreach ($mapdatas as $mapdata) {
			if ($model == 'Iso') {
				$newConditions[] = array('OR' => array(
						array($model . '.isoNumber' => $mapdata['MapUserType']['type_id'])));
				$newcond = array('OR' => $newConditions);
			} else {
				$newConditions[] = array('OR' => array(
						array($model . '.isoNumber' => $mapdata['MapUserType']['type_id']),
						array($model . '.merchantId' => $mapdata['MapUserType']['type_id'])));
				$newcond = array('OR' => $newConditions);
			}
		}
		return $newcond;
	}

}