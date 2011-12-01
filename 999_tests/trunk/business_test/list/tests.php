<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('list_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('BankListTest');
		$ts->addTestSuite('PendingDepositListTest');
		$ts->addTestSuite('ManufacturerListTest');
		$ts->addTestSuite('CorrelativeListTest');
		$ts->addTestSuite('BankAccountListTest');
		$ts->addTestSuite('UserAccountListTest');
		$ts->addTestSuite('PaymentCardBrandListTest');
		$ts->addTestSuite('ProductListTest');
		$ts->addTestSuite('SupplierListTest');
		$ts->addTestSuite('RoleListTest');
		$ts->addTestSuite('BranchListTest');
		$ts->addTestSuite('PaymentCardTypeListTest');
		$ts->addTestSuite('ShiftListTest');
		$ts->addTestSuite('UnitOfMeasureListTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>