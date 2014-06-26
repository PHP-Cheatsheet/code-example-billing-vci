<?php
abstract class Log {

	private $__logInfo;

	private $__logDir;

	private $__logFormat;
/**
 *
 * Enter description here ...
 * @param unknown_type $logtype
 */
	public function __construct() {
		$this->__logInfo = "";
		$this->__logDir = APP . '/webroot/Log/';
		$this->__logFormat = "json";
	}

/**
 *
 * Enter description here ...
 * @param unknown_type $object
 * @param unknown_type $error
 * @param unknown_type $debug
 */
	abstract public function logInformation($object, $error = null, $debug = null);

/**
 *
 * Enter description here ...
 * @param unknown_type $data
 * @param unknown_type $format
 */
	abstract public function logResultSet($data);

/**
 *
 * Enter description here ...
 * @param Object $object
 * @param String $error
 * @param String logFormat
 */
	private function __getExecutedSQL($object = null, $error = null, $logFormat) {
		//check if file log  format is set
		$this->__logFormat = (isset($logFormat) && $logFormat != null) ? $logFormat : $this->__logFormat;
		$formatFunction = "_" . $this->__logFormat . "Format";
		//call format function
		$log = array();
		if (isset($error) && $error > 0) {
			//get error while executing sql
			$log = array("Error Message" => $object->getMessage(),
						 "Query" => $object->queryString);
			$this->__logInfo = $this->$formatFunction($log);
		} else {
			//get executed sql
			$logArray = $object->getDataSource()->getLog(false, false);

			//print_r($logArray['log'][0]['query']); die;
 			$count = count($logArray['log']);
			for ($i = 0; $i < $count; $i++) {
				$log[] = array("Query" => $logArray['log'][$i]['query']);
			}
			$this->__logInfo = $this->$formatFunction($log);
		}
	}

/**
 *
 * Log executed SQL
 * @param Object $object
 * @param String $error
 * if error is set then $error contains the batch date
 * @param String $debug
 * @param String $logFormat
 */
	protected function _logInformation($object, $error = null, $debug = null, $logFormat = null) {
		$this->__logFile = $error > 0  ? 'error.log' : 'sql.log';
		$this->__getExecutedSQL($object, $error, $logFormat);
		if ($debug == true) {
			debug($this->__logInfo);
		}
		$this->__writeToFile();
		if (isset($error) && $error > 0) {
			$this->__writeErrorToDb($error);
		}
	}

/**
 *
 * Enter description here ...
 * @param unknown_type $data
 */
	protected function _logData($data, $logFormat = null) {
		$this->__logFile = 'data.log';
		$this->__logFormat = (isset($logFormat) && $logFormat != null) ? $logFormat : $this->__logFormat;
		$formatFunction = "_" . $this->__logFormat . "Format";
		$this->__logInfo = $this->$formatFunction($data);
		$this->__writeToFile();
//		$this->writeErrorToDb();
	}

/**
 *
 * Write to file
 */
	private function __writeToFile() {
		App::uses('File', 'Utility');
		$file = new File($this->__logDir . $this->__logFile);
		$file->append($this->__logInfo, true);
		$file->close();
	}

/**
 *
 * Write to database
 */
	private function __writeErrorToDb($batchId) {
		$data = array ('id' => String::uuid(),
						'batch_id' => $batchId,
						'message' => $this->__logInfo);
		App::uses('BillingError', 'Model');
		$eOBJ = new BillingError();
		$eOBJ -> logError($data);
	}

/**
 *
 * Enter description here ...
 * @param unknown_type $data
 */
	protected function _jsonFormat($data) {
		return json_encode($data);
	}

/**
 *
 * Enter description here ...
 * @param unknown_type $data
 */
	protected function _yamlFormat($data) {
		App::uses('Spyc','Vendor/spyc');
		if (!class_exists('Spyc')) {
			echo 'Required Class Spyc Not Found!';
		}
		$spyc = new Spyc();
		return $spyc->YAMLDump($data);
		//to parse YAML file into array
		//$data = Spyc::YAMLLoad($myfile);
	}
}