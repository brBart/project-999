<?php
set_include_path(get_include_path() . ';c:\\Users\\pc\\999_project\\999_middle\\' .
		';c:\\Users\\pc\\999_project\\999_tests\\');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('document_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('CorrelativeDAMTest');
		$ts->addTestSuite('CorrelativeDeleteInvoiceDependencyTest');
		$ts->addTestSuite('DiscountDAMTest');
		$ts->addTestSuite('DocBonusDetailDAMTest');
		$ts->addTestSuite('DocProductDetailDAMTest');
		$ts->addTestSuite('PurchaseReturnDAMInsertTest');
		$ts->addTestSuite('PurchaseReturnDAMGetInstanceAndCancelTest');
		$ts->addTestSuite('ShipmentDAMInsertTest');
		$ts->addTestSuite('ShipmentDAMGetInstanceAndCancelTest');
		$ts->addTestSuite('InvoiceDAMInsertTest');
		$ts->addTestSuite('InvoiceDAMGetInstanceAndOthersTest');
		$ts->addTestSuite('VatDAMTest');
		$ts->addTestSuite('ReceiptDAMInsertTest');
		$ts->addTestSuite('ReceiptDAMGetInstanceAndCancelTest');
		$ts->addTestSuite('ReserveDAMTest');
		$ts->addTestSuite('EntryIADAMInsertTest');
		$ts->addTestSuite('EntryIADAMGetInstanceAndCancelTest');
		$ts->addTestSuite('WithdrawIADAMInsertTest');
		$ts->addTestSuite('WithdrawIADAMGetInstanceAndCancelTest');
		$ts->addTestSuite('InvoiceTransactionLogDAMTest');
		$ts->addTestSuite('ResolutionLogDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>