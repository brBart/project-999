<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('user_account_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('RoleTest');
		$ts->addTestSuite('UserAccountUtilityTest');
		$ts->addTestSuite('UserAccountTest');
		$ts->addTestSuite('SubjectTest');
		$ts->addTestSuite('ActionTest');
		$ts->addTestSuite('AccessManagerTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>