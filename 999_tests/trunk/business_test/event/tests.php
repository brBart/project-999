<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('config/config.php');
require_once('event_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('EntryEventTest');
		$ts->addTestSuite('WithdrawEventTest');
		$ts->addTestSuite('WithdrawEventTest_2');
		$ts->addTestSuite('RetailEventTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>