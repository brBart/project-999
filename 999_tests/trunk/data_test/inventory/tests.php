<?php
require_once('../../config/config.php');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('inventory_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('CountingTemplateDAMTest');
		$ts->addTestSuite('CountDetailDAMTest');
		$ts->addTestSuite('CountDAMTest');
		$ts->addTestSuite('CountDAMGetInstanceTest');
		$ts->addTestSuite('ComparisonDAMInsertTest');
		$ts->addTestSuite('ComparisonDAMGetInstanceTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>