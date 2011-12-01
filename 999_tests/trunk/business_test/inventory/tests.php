<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('inventory_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('ComparisonDetailTest');
		$ts->addTestSuite('ComparisonTest');
		$ts->addTestSuite('CountDetailTest');
		$ts->addTestSuite('CountTest');
		$ts->addTestSuite('ComparisonEventTest');
		$ts->addTestSuite('ParserTest');
		$ts->addTestSuite('CountingTemplateTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>