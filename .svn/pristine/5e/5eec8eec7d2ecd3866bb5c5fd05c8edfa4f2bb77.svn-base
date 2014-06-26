<?php

class CustomFunctionsComponent extends Component {

/**
 * returns list of states in United States
 * @return type Array
 */
	public function state(){
		return $states =  array(
						"" => "",
						"AL" => "Alabama",
						"AK" => "Alaska",
						"AZ" => "Arizona",
						"AR" => "Arkansas",
						"CA" => "California",
						"CO" => "Colorado",
						"CT" => "Connecticut",
						"DE" => "Delaware",
						"FL" => "Florida",
						"GA" => "Georgia",
						"HI" => "Hawaii",
						"ID" => "Idaho",
						"IL" => "Illinois",
						"IN" => "Indiana",
						"IA" => "Iowa",
						"KS" => "Kansas",
						"KY" => "Kentucky",
						"LA" => "Louisiana",
						"ME" => "Maine",
						"MD" => "Maryland",
						"MA" => "Massachusetts",
						"MI" => "Michigan",
						"MN" => "Minnesota",
						"MS" => "Mississippi",
						"MO" => "Missouri",
						"MT" => "Montana",
						"NE" => "Nebraksa",
						"NV" => "Nevada",
						"NH" => "New Hampshire",
						"NJ" => "New Jersey",
						"NM" => "New Mexico",
						"NY" => "New York",
						"NC" => "North Carolina",
						"ND" => "North Dakota",
						"OH" => "Ohio",
						"OK" => "Oklahoma",
						"OR" => "Oregon",
						"PA" => "Pennsylvania",
						"PR" => "Puerto Rico",
						"RI" => "Rhode Island",
						"SC" => "South Carolina",
						"SD" => "South Dakota",
						"TN" => "Tennessee",
						"TX" => "Texas",
						"UT" => "Utah",
						"VT" => "Vermont",
						"VA" => "Virginia",
						"WA" => "Washington",
						"DC" => "Washington, DC",
						"WV" => "West Virginia",
						"WI" => "Wisconsin",
						"WY" => "Wyoming");
	}

	public function interceptEntryClass(){
		return $options = array('' => '',
								'PPD' => 'PPD',
								'CCD' => 'CCD',
								'TEL' => 'TEL',
								'POP' => 'POP',
								'RCK' => 'RCK',
								'WEB' => 'WEB',
								'ARC' => 'ARC',
								'BOC' => 'BOC');

	}

	public function actype(){
		return $options = array(
								'C' => 'Checking',
								'S' => 'Saving',
								'GL' => 'GL');
	}
		
	
	public function status(){
		return $options = array(
								'S' => '( S ) Settled',
								'E' => '( E ) Errored',
								'V' => '( V ) Voided',
								'D' => '( D ) Declined',
								'R' => '( R ) Returned',
								'A' => '( A ) Pending',
								'B' => '( B ) Submitted',
								);

	}
	public function gatewaySource(){
		return $referenceNum = array('PaySimple' => 'PaySimple',
			'fPay' => 'Franchise Payments',
			'USA E Pay' => 'USA E Pay');
	}

	public function getGlobalValues(){
		$chargebackCodes = "(LEFT(Transaction.reason,3)='R05'
			OR LEFT(Transaction.reason,3)='R07'
			OR LEFT(Transaction.reason,3)='R08'
			OR LEFT(Transaction.reason,3)='R10'
			OR LEFT(Transaction.reason,3)='R29')";
		$nsfCodes = "(LEFT(Transaction.reason,3)='R01'
			OR LEFT(Transaction.reason,3)='R09')";
		return array('chargebackCodes' => $chargebackCodes,
			'nsfCodes' => $nsfCodes);
	}
/**
 * Generate three digits number
 * @return string
 */
	public function generatePIN() {
		$consonants = "BCDFGHJKLMNPQRSTVWXZ1234567890";
		$vowels = "AEIOU";
		$password = "";
		$alt = time() % 2;
		for ($i = 0; $i < 3; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
			}
		return $password;
	}

/**
 * Generate three characters alphanumberic word
 * @return string
 */
	public function generateAltPIN() {
		$consonants = "1234567890";
		$vowels = "1234567890";
		$password = "";
		$alt = time() % 2;

		for ($i = 0; $i < 3; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	/**
	 * Assign 0 to if the variable is null or empty.
	 * The decimal precision is set to two places.
	 * 
	 * @param string $checkVar
	 * 
	 * $return 0 or $checkVar;
	 */
	public function assignZeroIfNull($checkVar, $checkPrecision = null) {
		if($checkVar == null || $checkVar == 0) {
			$return = 0;
		} else {
			$return = $checkVar;
		}
		if (isset($checkPrecision)){
			App::uses('CakeNumber', 'Utility');
			$return = CakeNumber::precision($return, 2);
		}
		return $return;
	}
	
	/**
	 * Get variance among two parameters.
	 * @param string $paramA
	 * @param string $paramB
	 * @return string
	 */
	public function getVariance($paramA, $paramB) {
		$divisor = '';
		$divident = '';

		if ($paramA > $paramB) {
			$divisor = $paramA;
			$divident = $paramB;
		} elseif($paramA < $paramB) {
			$divisor = $paramB;
			$divident = $paramA;
		} elseif($paramA == 0 && $paramB == 0){
			return 0;
		} else {
			$divisor = $paramA;
			$divident = $paramB;
		}
		
		$variance = (($divident/$divisor) * 100) - 100;

		return $variance;
	}
}