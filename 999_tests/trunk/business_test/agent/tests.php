<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('agent_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('CustomerTest');
		$ts->addTestSuite('ConcreteOrganizationTest');
		$ts->addTestSuite('SupplierTest');
		$ts->addTestSuite('BranchTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>