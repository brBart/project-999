<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('config/config.php');
require_once('transaction_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('WithdrawTest');
		$ts->addTestSuite('EntryTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>