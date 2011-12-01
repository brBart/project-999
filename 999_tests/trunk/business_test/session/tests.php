<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('session_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('ActiveSessionTest');
		$ts->addTestSuite('SessionHelperTest');
		$ts->addTestSuite('InventorySessionTest');
		$ts->addTestSuite('AdminSessionTest');
		$ts->addTestSuite('POSAdminSessionTest');
		$ts->addTestSuite('POSSessionTest');
		$ts->addTestSuite('KeyGeneratorTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>