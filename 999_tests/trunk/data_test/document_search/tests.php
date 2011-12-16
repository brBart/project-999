<?php
require_once('../../config/config.php');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('document_search_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('DepositSearchDAMTest');
		$ts->addTestSuite('DepositByWorkingDaySearchDAMTest');
		$ts->addTestSuite('ComparisonSearchDAMTest');
		$ts->addTestSuite('CountSearchDAMTest');
		$ts->addTestSuite('PurchaseReturnSearchDAMTest');
		$ts->addTestSuite('ShipmentSearchDAMTest');
		$ts->addTestSuite('InvoiceSearchDAMTest');
		$ts->addTestSuite('InvoiceByWorkingDaySearchDAMTest');
		$ts->addTestSuite('ReceiptSearchDAMTest');
		$ts->addTestSuite('EntryIASearchDAMTest');
		$ts->addTestSuite('WithdrawIASearchDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>