<?php
require_once('business/session.php');
require_once('business/user_account.php');
require_once('business/cash.php');
require_once('PHPUnit/Framework/TestCase.php');

class ActiveSessionTest extends PHPUnit_Framework_TestCase{
	
	public function testGetHelper(){
		$helper = AdminSession::getInstance();
		ActiveSession::setHelper($helper);
		
		$other = ActiveSession::getHelper();
		$this->assertEquals($helper, $other);
	}
}

class SessionHelperTest extends PHPUnit_Framework_TestCase{
	private $_mHelper;
	
	public function setUp(){
		$this->_mHelper = InventorySession::getInstance();
	}
	
	public function testSetUser(){
		$user = UserAccount::getInstance('roboli');
		$this->_mHelper->setUser($user);
		$this->assertEquals($user, $this->_mHelper->getUser());
	}
	
	public function testSetObject(){
		$obj = UserAccount::getInstance('roboli');
		$this->_mHelper->setObject(12, $obj);
		$this->assertEquals($obj, $this->_mHelper->getObject(12));
	}
	
	public function testSetObject_BadKeyTxt(){
		$obj = UserAccount::getInstance('roboli');
		try{
			$this->_mHelper->setObject('helo', $obj);
		} catch(Exception $e){ return; }
		$this->fail('Key exception expected.');
	}
	
	public function testSetObject_BadNoPositive(){
		$obj = UserAccount::getInstance('roboli');
		try{
			$this->_mHelper->setObject(0, $obj);
		} catch(Exception $e){ return; }
		$this->fail('Key exception expected.');
	}
	
	public function testSetSubjects(){
		$subjects_array = array('prueba' => 1);
		$this->_mHelper->setSubjects($subjects_array);
		$this->assertEquals($subjects_array, $this->_mHelper->getSubjects());
	}
	
	public function testSetActions(){
		$actions_array = array('prueba' => 2);
		$this->_mHelper->setActions($actions_array);
		$this->assertEquals($actions_array, $this->_mHelper->getActions());
	}
	
	public function testRemoveUser(){
		$user = UserAccount::getInstance('roboli');
		$this->_mHelper->setUser($user);
		$this->assertEquals($user, $this->_mHelper->getUser());
		$this->_mHelper->removeUser();
		$this->assertNull($this->_mHelper->getUser());
	}
	
	public function testRemoveObject(){
		$obj = UserAccount::getInstance('roboli');
		$this->_mHelper->setObject(12, $obj);
		$this->assertEquals($obj, $this->_mHelper->getObject(12));
		$this->_mHelper->removeObject(12);
		$this->assertNull($this->_mHelper->getObject(12));
	}
	
	public function testRemoveObject_BadKeyTxt(){
		try{
			$this->_mHelper->removeObject('hey');
		} catch(Exception $e){ return; }
		$this->fail('Key exception expected.');
	}
	
	public function testRemoveObject_BadKeyNoPositive(){
		try{
			$this->_mHelper->removeObject(-1);
		} catch(Exception $e){ return; }
		$this->fail('Key exception expected.');
	}
	
	public function testGetInstance(){
		$role = Role::getInstance(123);
		$this->_mHelper->setObject(10, $role);
		unset($this->_mHelper);
		$helper = InventorySession::getInstance();
		$this->assertEquals($role, $helper->getObject(10));
	}
}

class InventorySessionTest extends PHPUnit_Framework_TestCase{
	
	public function testGetInstance(){
		$helper = InventorySession::getInstance();
		$this->assertType('InventorySession', $helper);
	}
}

class AdminSessionTest extends PHPUnit_Framework_TestCase{
	
	public function testGetInstance(){
		$helper = AdminSession::getInstance();
		$this->assertType('AdminSession', $helper);
	}
}

class POSAdminSessionTest extends PHPUnit_Framework_TestCase{
	
	public function testGetInstance(){
		$helper = POSAdminSession::getInstance();
		$this->assertType('POSAdminSession', $helper);
	}
}

class POSSessionTest extends PHPUnit_Framework_TestCase{
	private $_mHelper;
	
	public function setUp(){
		$this->_mHelper = POSSession::getInstance();
	}
	
	public function testGetInstance(){
		$this->assertType('POSSession', $this->_mHelper);
	}
	
	public function testSetWorkingDay(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		$this->_mHelper->setWorkingDay($working_day);
		$this->assertEquals($working_day, $this->_mHelper->getWorkingDay());
	}
}

class KeyGeneratorTest extends PHPUnit_Framework_TestCase{
	
	public function testGenerateKey(){
		$key = KeyGenerator::generateKey();
		$this->assertGreaterThan(1000, $key);
	}
}
?>