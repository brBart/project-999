<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('config/config.php');
require_once('document_search_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('DepositSearchTest');
		$ts->addTestSuite('ComparisonSearchTest');
		$ts->addTestSuite('CountSearchTest');
		$ts->addTestSuite('PurchaseReturnSearchTest');
		$ts->addTestSuite('ShipmentSearchTest');
		$ts->addTestSuite('InvoiceSearchTest');
		$ts->addTestSuite('InvoiceByWorkingDaySearchTest');
		$ts->addTestSuite('ReceiptSearchTest');
		$ts->addTestSuite('EntryIASearchTest');
		$ts->addTestSuite('WithdrawIASearchTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>