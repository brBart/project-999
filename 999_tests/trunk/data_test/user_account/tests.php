<?php
require_once('../../config/config.php');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('user_account_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('RoleDAMTest');
		$ts->addTestSuite('UserAccountDAMTest');
		$ts->addTestSuite('UserAccountDeleteTest');
		$ts->addTestSuite('UserAccountDeleteChangePriceLogDependencyTest');
		$ts->addTestSuite('UserAccountDeletePurchaseReturnDependencyTest');
		$ts->addTestSuite('UserAccountDeleteShipmentDependencyTest');
		$ts->addTestSuite('UserAccountDeleteInvoiceDependencyTest');
		$ts->addTestSuite('UserAccountDeleteCountDependencyTest');
		$ts->addTestSuite('UserAccountDeleteReserveDependencyTest');
		$ts->addTestSuite('UserAccountDeleteComparisonDependencyTest');
		$ts->addTestSuite('UserAccountDeleteDepositDependencyTest');
		$ts->addTestSuite('UserAccountDeleteEntryAdjustmentDependencyTest');
		$ts->addTestSuite('UserAccountDeleteReceiptDependencyTest');
		$ts->addTestSuite('UserAccountDeleteWithdrawAdjustmentDependencyTest');
		$ts->addTestSuite('UserAccountDeleteDiscountDependencyTest');
		$ts->addTestSuite('UserAccountDeletePurchaseReturnCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteShipmentCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteInvoiceCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteDepositCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteEntryAdjustmentCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteReceiptCancelledDependencyTest');
		$ts->addTestSuite('UserAccountDeleteWithdrawAdjustmentCancelledDependencyTest');
		$ts->addTestSuite('UserAccountUtilityDAMTest');
		$ts->addTestSuite('SubjectDAMTest');
		$ts->addTestSuite('ActionDAMTest');
		$ts->addTestSuite('AccessManagerDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>