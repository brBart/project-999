<?php
set_include_path(get_include_path() . ';c:\\Users\\pc\\999_project\\999_middle\\' .
		';c:\\Users\\pc\\999_project\\999_tests\\');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('cash_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('BankDAMTest');
		$ts->addTestSuite('BankDeleteBankAccountDependencyTest');
		$ts->addTestSuite('BankAccountDAMTest');
		$ts->addTestSuite('BankAccountDeleteDepositDependencyTest');
		$ts->addTestSuite('ShiftDAMTest');
		$ts->addTestSuite('ShiftDeleteCashRegisterDependencyTest');
		$ts->addTestSuite('CashRegisterDAMTest');
		$ts->addTestSuite('CashDAMTest');
		$ts->addTestSuite('DepositDAMTest');
		$ts->addTestSuite('DepositListDAMTest');
		$ts->addTestSuite('SalesReportDAMTest');
		$ts->addTestSuite('DepositDetailListDAMTest');
		$ts->addTestSuite('InvoiceListDAMTest');
		$ts->addTestSuite('WorkingDayDAMTest');
		$ts->addTestSuite('PaymentCardBrandDAMTest');
		$ts->addTestSuite('PaymentCardBrandDeleteVoucherDependencyTest');
		$ts->addTestSuite('PaymentCardTypeDAMTest');
		$ts->addTestSuite('PaymentCardTypeDeleteVoucherDependencyTest');
		$ts->addTestSuite('CashReceiptDAMTest');
		$ts->addTestSuite('AvailableCashReceiptListDAMTest');
		$ts->addTestSuite('GeneralSalesReportDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>