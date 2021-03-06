<?php
require_once('../../config/config.php');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('agent_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('CustomerDAMTest');
		$ts->addTestSuite('SupplierDAMTest');
		$ts->addTestSuite('SupplierDeleteProductSupplierDependencyTest');
		$ts->addTestSuite('SupplierDeleteReceiptDependencyTest');
		$ts->addTestSuite('SupplierDeletePurchaseReturnDependencyTest');
		$ts->addTestSuite('BranchDAMTest');
		$ts->addTestSuite('BranchDeleteShipmentDependencyTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>