<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('document_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('ConcreteDocDetailTest');
		$ts->addTestSuite('DocBonusDetailTest');
		$ts->addTestSuite('DocProductDetailTest');
		$ts->addTestSuite('ReserveTest');
		$ts->addTestSuite('ConcreteDocumentTest');
		$ts->addTestSuite('CorrelativeTest');
		$ts->addTestSuite('VatTest');
		$ts->addTestSuite('DiscountTest');
		$ts->addTestSuite('InvoiceTest');
		$ts->addTestSuite('PurchaseReturnTest');
		$ts->addTestSuite('ShipmentTest');
		$ts->addTestSuite('ReceiptTest');
		$ts->addTestSuite('ConcreteADTest');
		$ts->addTestSuite('EntryIATest');
		$ts->addTestSuite('WithdrawIATest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>