<?php
set_include_path(get_include_path() . ';c:\\Users\\pc\\999_project\\999_middle\\' .
		';c:\\Users\\pc\\999_project\\999_tests\\');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('list_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('BankListDAMTest');
		$ts->addTestSuite('PendingDepositListDAMTest');
		$ts->addTestSuite('ManufacturerListDAMTest');
		$ts->addTestSuite('BankAccountListDAMTest');
		$ts->addTestSuite('CorrelativeListDAMTest');
		$ts->addTestSuite('UserAccountListDAMTest');
		$ts->addTestSuite('PaymentCardBrandListDAMTest');
		$ts->addTestSuite('ProductListDAMTest');
		$ts->addTestSuite('SupplierListDAMTest');
		$ts->addTestSuite('RoleListDAMTest');
		$ts->addTestSuite('BranchListDAMTest');
		$ts->addTestSuite('PaymentCardTypeListDAMTest');
		$ts->addTestSuite('ShiftListDAMTest');
		$ts->addTestSuite('UnitOfMeasureListDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>