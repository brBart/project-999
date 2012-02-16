<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('config/config.php');
require_once('cash_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('BankTest');
		$ts->addTestSuite('BankAccountTest');
		$ts->addTestSuite('ShiftTest');
		$ts->addTestSuite('CashRegisterTest');
		$ts->addTestSuite('PaymentCardTypeTest');
		$ts->addTestSuite('PaymentCardBrandTest');
		$ts->addTestSuite('PaymentCardTest');
		$ts->addTestSuite('VoucherTest');
		$ts->addTestSuite('CashTest');
		$ts->addTestSuite('CashReceiptTest');
		$ts->addTestSuite('DepositDetailTest');
		$ts->addTestSuite('DepositTest');
		$ts->addTestSuite('DepositEventTest');
		$ts->addTestSuite('SalesReportTest');
		$ts->addTestSuite('DepositDetailListTest');
		$ts->addTestSuite('CashEntryEventTest');
		$ts->addTestSuite('VoucherEntryEventTest');
		$ts->addTestSuite('WorkingDayTest');
		$ts->addTestSuite('GeneralSalesReportTest');
		$ts->addTestSuite('DepositListTest');
		$ts->addTestSuite('InvoiceListTest');
		$ts->addTestSuite('AvailableCashReceiptListTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>