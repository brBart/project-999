<?php
require_once('config/config.php');

require_once('presentation/controller.php');
require_once('PHPUnit/Framework/TestCase.php');

class RequestTest extends PHPUnit_Framework_TestCase{
	
	public function testConstruct(){
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_REQUEST['uno'] = '1';
		$_REQUEST['dos'] = '2';
		
		$request = new Request();
		$this->assertEquals('1', $request->getProperty('uno'));
		$this->assertEquals('2', $request->getProperty('dos'));
	}
}

class CommandResolverTest extends PHPUnit_Framework_TestCase{
	
	public function testGetCommand(){
		$command = CommandResolver::getCommand('show_login_inventory');
		$this->assertTrue($command instanceof ShowLoginInventoryCommand);
	}
}
?>