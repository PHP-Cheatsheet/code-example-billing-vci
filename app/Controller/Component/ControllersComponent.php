<?php

class ControllersComponent extends Component {

	public $paginate;

	/*
	 * function search: used to search data according to search parameters
	 * is called from index function
	 * $reads: data that are stored in session for seach
	 * $num: static variable that repesents, 1 = Merchants, 2 = Isos, 3 = Transactions
	 * $label: consists of label of each field
	 * $mer : value of the label
	 */

	public function search($reads, $num) {
		
		$conditions = array();
		$label = array();
		$mer = array();
		$label = $this->labelcoll($num);
		$mer = $this->mercoll($num);
		$totalcount = count($mer);
		$readcountdata = 0;
		
		foreach ($reads as $key => $val) {
			$sessiondata[$readcountdata++] = $key;
		}
		$sessioncount = count($sessiondata);
		for ($valuecount = 0; $valuecount < $sessioncount; $valuecount++) {
			for ($labelcount = 0; $labelcount < $totalcount; $labelcount++) {
				if ($label[$labelcount] == $sessiondata[$valuecount]) {
					if ($label[$labelcount] == 'Search_Active') {
						if ($reads[$label[$labelcount]] == 'Active') {
							$entrysessionvalue = '1';
						} elseif ($reads[$label[$labelcount]] == 'InActive') {
							$entrysessionvalue = '0';
						} else {
							$entrysessionvalue = trim($reads[$label[$labelcount]]);
						}
						$this->paginate['conditions'][][$mer[$labelcount]] = $entrysessionvalue;
						$conditions[] = array($mer[$labelcount] . " LIKE '$entrysessionvalue%'");
						$labelcount = $totalcount;
					} elseif (strpos($label[$labelcount], 'Date') != false && is_array($reads[$label[$labelcount]]) == true) {
						$fromdate = $reads[$label[$labelcount]][0];
						$todate = $reads[$label[$labelcount]][1];
						$conditions[] = array('and' => array(
								array($mer[$labelcount] . ' >= ' => $fromdate,
									$mer[$labelcount] . ' <= ' => $todate)
						));
					} elseif ($label[$labelcount] == 'Search_Account_Num') {
						$entrysessionvalue = trim($reads[$label[$labelcount]]);
						$conditions[] = array($mer[$labelcount] . " LIKE '$entrysessionvalue'");
					} else {
						$entrysessionvalue = trim($reads[$label[$labelcount]]);
						$this->paginate['conditions'][][$mer[$labelcount]] = $entrysessionvalue;
						$conditions[] = array($mer[$labelcount] . " LIKE '$entrysessionvalue%'");
						$labelcount = $totalcount;
					}
				}
			}
		}

		/**
		 * metaphoneCondtion method: condition for the transaction id that matches the passed metaphone
		 * $transactionMID : array value of transaction id mapped with the search metaphone
		 * $mer: array value of all the table names
		 * $labelcount: count number that indicates the specific tablename for search query
		 */
		foreach ($reads as $data => $val) {
			
			if ($data == 'meta') {
				$j = 0;
				
				foreach ($val as $datas => $value) {
					
					$count = count($value);
					
					for ($i = 0; $i < $count; $i++) {
						$entrysessionvalue = $value[$i][0];
						if ($datas == 'Search_Transaction') {
							$metacondition[$j++] = array('Transaction.transaction_id' . " LIKE '$entrysessionvalue%'");
						} elseif ($datas == 'Search_Merchant') {
							
							if($value[$count-1] == "trans" ){
								$metacondition[$j++] = array('Transaction.merchantId' . " LIKE '$entrysessionvalue%'");
							} else {
								$metacondition[$j++] = array('Merchant.merchantId' . " LIKE '$entrysessionvalue%'");
							} 
						} else {
							$metacondition[$j++] = array('Iso.id' . " LIKE '$entrysessionvalue%'");
						}
					}
				}
				$conditions[] = array('OR' => $metacondition);
			}
		}
		if ($conditions != null) {
			return $conditions;
		}
	}

	/* labelcoll function : label for search Parameters passed from session variable
	 * 1 = Merchant search parameters
	 * 2 = Iso search parameters
	 * 3 = Transaction search parameters
	 * 4 = Holiday search parameters
	 * 5 = ReturnCheck search parameters
	 * 6 = Return Codes table search parameters
	 * 7 = Notice of change search parameters
	 */

	public function labelcoll($num) {
		if ($num == 1) {
			$label[0] = 'Search_MID';
			$label[1] = 'Search_Funding';
			$label[2] = 'Search_Merchant_Name';
			$label[3] = 'Search_Contact_Name';
			$label[4] = 'Search_Email';
			$label[5] = 'Search_Phone';
			$label[6] = 'Search_Support';
			$label[7] = 'Search_Street';
			$label[8] = 'Search_City';
			$label[9] = 'Search_Zip';
			$label[10] = 'Search_Classes';
			$label[11] = 'Search_Issue_Date';
			$label[12] = 'Search_ISO';
			$label[13] = 'Search_ISO_Name';
		} elseif ($num == 2) {
			$label[0] = 'Search_ISO_Number';
			$label[1] = 'Search_ISO_Name';
			$label[2] = 'Search_PIN';
			$label[3] = 'Search_Merchants';
			$label[4] = 'Search_Contact_Name';
			$label[5] = 'Search_Phone';
			$label[6] = 'Search_Email';
			$label[7] = 'Search_City';
			$label[8] = 'Search_Zip';
			$label[9] = 'Search_Issue_Date';
			$label[10] = 'Search_Active';
		} elseif ($num == 3) {
			$label[0] = 'Search_Creation_Date';
			$label[1] = 'Search_ISO';
			$label[2] = 'Search_Transaction';
			$label[3] = 'Search_Auth_Code';
			$label[4] = 'Search_OrgID';
			$label[5] = 'Search_Class';
			$label[6] = 'Search_MID';
			$label[7] = 'Search_Merchant_Name';
			$label[8] = 'Search_Merch_Phone';
			$label[9] = 'Search_Chk';
			$label[10] = 'Search_Routing_Num';
			$label[11] = 'Search_Account_Num';
			$label[12] = 'Search_Type';
			$label[13] = 'Search_Amount';
			$label[14] = 'Search_Customer_Name';
			$label[15] = 'Search_Status';
			$label[16] = 'Search_Settle_Date';
			$label[17] = 'Search_Description';
			$label[18] = 'Search_Note';
			$label[19] = 'Search_Settlement_Amount';
			$label[20] = 'Search_Settlement_Date';
			$label[21] = 'Search_Settlement_OrgDate';
		} elseif ($num == 4) {
			$label[0] = 'Search_Holiday_Date';
			$label[1] = 'Search_Description';
		} elseif ($num == 5) {
			$label[0] = 'Search_Routing_Number';
			$label[1] = 'Search_Account_Number';
			$label[2] = 'Search_Amount';
			$label[3] = 'Search_Check_Number';
			$label[4] = 'Search_Identification_Number';
			$label[5] = 'Search_Identification_State';
			$label[6] = 'Search_merchant_Id';
			$label[7] = 'Search_Return_Date';
			$label[8] = 'Search_Reason';
			$label[9] = 'Search_Transaction_ID';
			$label[10] = 'Search_Iso_Number';
			$label[11] = 'Search_Reason_Code';
		} elseif ($num == 6) {
			$label[0] = 'Search_Return_Code';
			$label[1] = 'Search_Description';
			$label[2] = 'Search_Type';
			$label[3] = 'Search_Post_Reply';
		} elseif ($num == 7) {
			$label[0] = 'Search_Iso_Number';
			$label[1] = 'Search_MerchantId';
			$label[2] = 'Search_Return_Date';
			$label[3] = 'Search_Return_Reason';
			$label[4] = 'Search_Routing_Number';
			$label[5] = 'Search_Account_Number';
			$label[6] = 'Search_Check_Number';
			$label[7] = 'Search_Amount';
			$label[8] = 'Search_Transaction';
			$label[9] = 'Search_Billed';
		}
		return $label;
	}

	/* mercoll function : table names used in search Parameters passed from session variable
	 * 1 = Merchant table search parameters
	 * 2 = Iso table search parameters
	 * 3 = Transaction table search parameters
	 * 4 = Holiday table search parameters
	 * 5 = ReturnCheck table  search parameters
	 * 6 = Return Codes table search parameters
	 * 7 = Notice of change table search parameters
	 */

	public function mercoll($num) {
		if ($num == 1) {
			$mer[0] = 'Merchant.merchantId';
			$mer[1] = 'Merchant.funding_time';
			$mer[2] = 'Merchant.name';
			$mer[3] = 'Merchant.contactName';
			$mer[4] = 'Merchant.email';
			$mer[5] = 'Merchant.phoneNum';
			$mer[6] = 'Merchant.support_phone';
			$mer[7] = 'Merchant.address1';
			$mer[8] = 'Merchant.city';
			$mer[9] = 'Merchant.zip';
			$mer[10] = 'Merchant.interceptEntryClass';
			$mer[11] = 'Merchant.issueDate';
			$mer[12] = 'Merchant.isoNumber';
			$mer[13] = 'Merchant.isoName';
		} elseif ($num == 2) {
			$mer[0] = 'Iso.isoNumber';
			$mer[1] = 'Iso.name';
			$mer[2] = 'Iso.isoPIN';
			$mer[3] = '';
			$mer[4] = 'Iso.contact';
			$mer[5] = 'Iso.phone_w';
			$mer[6] = 'Iso.email';
			$mer[7] = 'Iso.city';
			$mer[8] = 'Iso.zip';
			$mer[9] = 'Iso.issueDate';
			$mer[10] = 'Iso.active';
		} elseif ($num == 3) {
			$mer[0] = 'Transaction.posted_date';
			$mer[1] = 'Transaction.isoNumber';
			$mer[2] = 'Transaction.transaction_id';
			$mer[3] = 'Webcheck.rockyMtnResponse';
			$mer[4] = 'Transaction.original_transaction_id';
			$mer[5] = 'Transaction.standard_entry_class';
			$mer[6] = 'Transaction.merchantId';
			$mer[7] = 'Transaction.merchant_name';
			$mer[8] = 'Merchant.phoneNum';
			$mer[9] = 'Transaction.check_number';
			$mer[10] = 'Transaction.cust_routing_number';
			$mer[11] = 'Transaction.cust_account_number';
			$mer[12] = 'Transaction.account_type';
			$mer[13] = 'Transaction.amount';
			$mer[14] = 'Transaction.customer_name';
			$mer[15] = 'Transaction.response_status';
			$mer[16] = 'Transaction.settle_date';
			$mer[17] = 'Transaction.description';
			$mer[18] = 'Transaction.reason';
			$mer[19] = 'SettlementWarehouse.settlement_amount';
			$mer[20] = 'SettlementWarehouse.settlement_scheduled_date';
			$mer[21] = 'SettlementWarehouse.origination_scheduled_date';
		} elseif ($num == 4) {
			$mer[0] = 'Holiday.holiday';
			$mer[1] = 'Holiday.Description';
		} elseif ($num == 5) {
			$mer[0] = 'ReturnedCheck.routing_number';
			$mer[1] = 'ReturnedCheck.account_number';
			$mer[2] = 'ReturnedCheck.amount';
			$mer[3] = 'ReturnedCheck.check_number';
		} elseif ($num == 6) {
			$mer[0] = 'ReturnCode.return_code';
			$mer[1] = 'ReturnCode.description';
			$mer[2] = 'ReturnCode.type';
			$mer[3] = 'ReturnCode.post_reply';
		} elseif ($num == 7) {
			$mer[0] = 'NoticeOfChange.isoNumber';
			$mer[1] = 'NoticeOfChange.merchantId';
			$mer[2] = 'NoticeOfChange.return_date';
			$mer[3] = 'NoticeOfChange.return_reason';
			$mer[4] = 'NoticeOfChange.routing_number';
			$mer[5] = 'NoticeOfChange.account_number';
			$mer[6] = 'NoticeOfChange.check_number';
			$mer[7] = 'NoticeOfChange.amount';
			$mer[8] = 'NoticeOfChange.transaction_id';
			$mer[9] = 'NoticeOfChange.billed';
		}
		return $mer;
	}

}