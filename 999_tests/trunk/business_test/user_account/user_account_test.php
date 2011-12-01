<?php
require_once('business/user_account.php');
require_once('business/session.php');
require_once('PHPUnit/Framework/TestCase.php');

class RoleTest extends PHPUnit_Framework_TestCase{
	public function tearDown(){
	}
	
	public function testConstructor(){
		$role = new Role(4321, 'Operador');
		$this->assertEquals(4321, $role->getId());
		$this->assertEquals('Operador', $role->getName());
	}
	
	public function testConstructor_BadId(){
		try{
			$role = new Role('hola', 'Operador');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BlankName(){
		try{
			$role = new Role(4321, '');
		} catch(Exception $e){ return; }
		$this->fail('Name exception expected.');
	}
	
	public function testGetInstance(){
		$role = Role::getInstance(123);
		$this->assertEquals(123, $role->getId());
	}
}

class UserAccountUtilityTest extends PHPUnit_Framework_TestCase{
	public function tearDown(){
	}
	
	public function testIsValid(){
		$this->assertTrue(UserAccountUtility::isValid('roboli', 'robert'));
	}
	
	public function testIsValid_BadUserName(){
		$this->assertFalse(UserAccountUtility::isValid('carlos', 'robert'));
	}
	
	public function testIsValid_BadPassword(){
		$this->assertFalse(UserAccountUtility::isValid('roboli', 'roboli'));
	}
	
	public function testIsValid_Root(){
		$this->assertTrue(UserAccountUtility::isValid('root', 'ruth'));
	}
	
	public function testIsValid_RootBadPassword(){
		$this->assertFalse(UserAccountUtility::isValid('root', 'root'));
	}
	
	public function testIsRoot(){
		$this->assertTrue(UserAccountUtility::isRoot('root'));
	}
	
	public function testIsRoot_Bad(){
		$this->assertFalse(UserAccountUtility::isRoot('ROT'));
	}
	
	public function testChangePassword(){
		$user_account = UserAccount::getInstance('roboli');
		UserAccountUtility::changePassword($user_account, 'robert', 'newpass');
		$this->assertTrue(UserAccountUtility::isValid('roboli', 'newpass'));
	}
	
	public function testChangePassword_NewUser(){
		$user_account = new UserAccount('carlos');
		try{
			UserAccountUtility::changePassword($user_account, 'sdg', 'sitiene');
		} catch(Exception $e){ return; }
		$this->fail('Account exception expected');
	}
	
	public function testChangePassword_BadPassword(){
		$user_account = UserAccount::getInstance('roboli');
		try{
			UserAccountUtility::changePassword($user_account, 'ajasimon', 'yeah');
		} catch(Exception $e){ return; }
		$this->fail('Password exception expected.');
	}
	
	public function testChangePassword_BlankPassword(){
		$user_account = UserAccount::getInstance('roboli');
		try{
			UserAccountUtility::changePassword($user_account, '', 'yeah');
		} catch(Exception $e){ return; }
		$this->fail('Password exception expected.');
	}
	
	public function testChangePassword_BlankNewPassword(){
		$user_account = UserAccount::getInstance('roboli');
		try{
			UserAccountUtility::changePassword($user_account, 'robert', '');
		} catch(Exception $e){ return; }
		$this->fail('New password exception expected.');
	}
	
	public function testChangePassword_Root(){
		$user_account = UserAccount::getInstance('root');
		UserAccountUtility::changePassword($user_account, 'ruth', 'newpass');
		$this->assertTrue(UserAccountUtility::isValid('root', 'newpass'));
	}
	
	public function testChangePassword_RootBadPassword(){
		$user_account = UserAccount::getInstance('root');
		try{
			UserAccountUtility::changePassword($user_account, 'tadsimodn', 'yeah');
		} catch(Exception $e){ return; }
		$this->fail('Password exception expected.');
	}
}

class UserAccountTest extends PHPUnit_Framework_TestCase{
	private $_mUserAccount;
	
	public function setUp(){
		$this->_mUserAccount = new UserAccount();
	}
	
	public function tearDown(){
	}
	
	public function testConstructor(){
		$user_account = new UserAccount('cargon', PersistObject::CREATED);
		$this->assertEquals('cargon', $user_account->getUserName());
		$this->assertEquals(PersistObject::CREATED, $user_account->getStatus());
		$this->assertNull($user_account->getFirstName());
		$this->assertNull($user_account->getLastName());
		$this->assertNull($user_account->getPassword());
		$this->assertNull($user_account->getRole());
		$this->assertFalse($user_account->isDeactivated());
	}
	
	public function testConstructor_Defaults(){
		$user_account = new UserAccount();
		$this->assertNull($user_account->getUserName());
		$this->assertEquals(PersistObject::IN_PROGRESS, $user_account->getStatus());
		$this->assertNull($user_account->getFirstName());
		$this->assertNull($user_account->getLastName());
		$this->assertNull($user_account->getPassword());
		$this->assertNull($user_account->getRole());
		$this->assertFalse($user_account->isDeactivated());
	}
	
	public function testSetUserName(){
		$this->_mUserAccount->setUserName('cargon');
		$this->assertEquals('cargon', $this->_mUserAccount->getUserName());
	}
	
	public function testSetUserName_NotNewAccount(){
		$user_account = UserAccount::getInstance('roboli');
		try{
			$user_account->setUserName('cargon');
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testSetUserName_Blank(){
		try{
			$this->_mUserAccount->setUserName('');
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testSetUserName_InUseUserName(){
		try{
			$this->_mUserAccount->setUserName('roboli');
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testSetFirstName(){
		$this->_mUserAccount->setFirstName('Roberto');
		$this->assertEquals('Roberto', $this->_mUserAccount->getFirstName());
	}
	
	public function testSetFirstName_Blank(){
		try{
			$this->_mUserAccount->setFirstName('');
		} catch(Exception $e){ return; }
		$this->fail('FirstName exception expected.');
	}
	
	public function testSetLastName(){
		$this->_mUserAccount->setLastName('Oliveros');
		$this->assertEquals('Oliveros', $this->_mUserAccount->getLastName());
	}
	
	public function testSetLastName_Blank(){
		try{
			$this->_mUserAccount->setLastName('');
		} catch(Exception $e){ return; }
		$this->fail('LastName exception expected.');
	}
	
	public function testSetPassword(){
		$this->_mUserAccount->setPassword('hola');
		$this->assertEquals(UserAccountUtility::encrypt('hola'), $this->_mUserAccount->getPassword());
	}
	
	public function testSetPassword_Blank(){
		try{
			$this->_mUserAccount->setPassword('');
		} catch(Exception $e){ return; }
		$this->fail('Password exception expected.');
	}
	
	public function testSetPassword_NotNew_Blank(){
		$user = UserAccount::getInstance('roboli');
		$user->setPassword('');
	}
	
	public function testSetRole(){
		$role = new Role(321, 'operador');
		$this->_mUserAccount->setRole($role);
		$this->assertEquals($role, $this->_mUserAccount->getRole());
	}
	
	public function testDeactivate(){
		$this->_mUserAccount->deactivate(true);
		$this->assertTrue($this->_mUserAccount->isDeactivated());
	}
	
	public function testSetData(){
		$role = new Role(321, 'Cajero');
		$this->_mUserAccount->setData('Carlos', 'Gonzalez', $role, true);
		$this->assertEquals('Carlos', $this->_mUserAccount->getFirstName());
		$this->assertEquals('Gonzalez', $this->_mUserAccount->getLastName());
		$this->assertEquals($role, $this->_mUserAccount->getRole());
		$this->assertTrue($this->_mUserAccount->isDeactivated());
	}
	
	public function testSetData_BlankFirstName(){
		$role = new Role(321, 'Cajero');
		try{
			$this->_mUserAccount->setData('', 'Gonzalez', $role, true);
		} catch(Exception $e){ return; }
		$this->fail('FirstName exception expected');
	}
	
	public function testSetData_BlankLastName(){
		$role = new Role(321, 'Cajero');
		try{
			$this->_mUserAccount->setData('Carlos', '', $role, true);
		} catch(Exception $e){ return; }
		$this->fail('LastName exception expected');
	}
	
	public function testSave(){
		$this->_mUserAccount->setUserName('cargon');
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setLastName('Gonzalez');
		$this->_mUserAccount->setPassword('robert');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		$this->_mUserAccount->save();
		$this->assertEquals(PersistObject::CREATED, $this->_mUserAccount->getStatus());
	}
	
	public function testSave_Root(){
		$this->_mUserAccount->setUserName('root');
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setLastName('Gonzalez');
		$this->_mUserAccount->setPassword('robert');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testSave_NoUserName(){
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setLastName('Gonzalez');
		$this->_mUserAccount->setPassword('robert');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testSave_NoFirstName(){
		$this->_mUserAccount->setUserName('cargon');
		$this->_mUserAccount->setLastName('Gonzalez');
		$this->_mUserAccount->setPassword('robert');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('FirstName exception expected.');
	}
	
	public function testSave_NoLastName(){
		$this->_mUserAccount->setUserName('cargon');
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setPassword('robert');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('LastName exception expected.');
	}
	
	public function testSave_NoPassword(){
		$this->_mUserAccount->setUserName('cargon');
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setLastName('Gonzalez');
		$role = Role::getInstance(123);
		$this->_mUserAccount->setRole($role);
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('Password exception expected.');
	}
	
	public function testSave_NotNew_NoPassword(){
		$user = UserAccount::getInstance('roboli');
		$user->setPassword('');
		$user->save();
	}
	
	public function testSave_NoRole(){
		$this->_mUserAccount->setUserName('cargon');
		$this->_mUserAccount->setFirstName('Carlos');
		$this->_mUserAccount->setLastName('Gonzalez');
		$this->_mUserAccount->setPassword('robert');
		try{
			$this->_mUserAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('Role exception expected.');
	}
	
	public function testGetInstance(){
		$user_account = UserAccount::getInstance('roboli');
		$this->assertEquals('Roberto', $user_account->getFirstName());
	}
	
	public function testGetInstance_Root(){
		$user_account = UserAccount::getInstance('root');
		$this->assertEquals(PersistObject::CREATED, $user_account->getStatus());
	}
	
	public function testGetInstance_BlankUserName(){
		try{
			$user_account = UserAccount::getInstance('');
		} catch(Exception $e){ return; }
		$this->fail('UserName exception expected.');
	}
	
	public function testDelete_New(){
		try{
			UserAccount::delete($this->_mUserAccount);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$user_account = UserAccount::getInstance('roboli');
		UserAccount::delete($user_account);
	}
}

class SubjectTest extends PHPUnit_Framework_TestCase{
	
	public function testGetId(){
		$helper = InventorySession::getInstance();
		ActiveSession::setHelper($helper);
		$this->assertEquals(2, Subject::getId('prueba2'));
		$this->assertEquals(array('prueba2' => 2), $helper->getSubjects());
		
		$this->assertEquals(1, Subject::getId('prueba1'));
		$this->assertEquals(array('prueba2' => 2, 'prueba1' => 1), $helper->getSubjects());
	}
	
	public function testGetId_NonExistent(){
		$this->assertEquals(0, Subject::getId('nohay'));
	}
	
	public function testGetId_Cached(){
		$helper = InventorySession::getInstance();
		ActiveSession::setHelper($helper);
		$subjects = array();
		$subjects['nuevo'] = 3;
		$helper->setSubjects($subjects);
				
		$this->assertEquals(3, Subject::getId('nuevo'));
	}
}

class ActionTest extends PHPUnit_Framework_TestCase{
	
	public function testGetId(){
		$helper = InventorySession::getInstance();
		ActiveSession::setHelper($helper);
		$this->assertEquals(2, Action::getId('prueba2'));
		$this->assertEquals(array('prueba2' => 2), $helper->getActions());
		
		$this->assertEquals(1, Action::getId('prueba1'));
		$this->assertEquals(array('prueba2' => 2, 'prueba1' => 1), $helper->getActions());
	}
	
	public function testGetId_NonExistent(){
		$this->assertEquals(0, Action::getId('nohay'));
	}
	
	public function testGetId_Cached(){
		$helper = InventorySession::getInstance();
		ActiveSession::setHelper($helper);
		$actions = array();
		$actions['nuevo'] = 3;
		$helper->setActions($actions);
				
		$this->assertEquals(3, Action::getId('nuevo'));
	}
}

class AccessManagerTest extends PHPUnit_Framework_TestCase{
	
	public function testIsAllowed(){
		$user = UserAccount::getInstance('roboli');
		$this->assertTrue(AccessManager::isAllowed($user, 'operaciones', 'accesar'));
	}
	
	public function testIsAllowed_1(){
		$user = UserAccount::getInstance('roboli');
		$this->assertTrue(AccessManager::isAllowed($user, 'caja', 'cerrar'));
	}
	
	public function testIsAllowed_2(){
		$user = UserAccount::getInstance('josoli');
		$this->assertTrue(AccessManager::isAllowed($user, 'operaciones', 'accesar'));
	}
	
	public function testIsAllowed_3(){
		$user = UserAccount::getInstance('josoli');
		$this->assertFalse(AccessManager::isAllowed($user, 'caja', 'cerrar'));
	}
	
	public function testIsAllowed_4(){
		$user = UserAccount::getInstance('root');
		$this->assertTrue(AccessManager::isAllowed($user, 'caja', 'cerrar'));
	}
	
	public function testIsAllowed_BlankSubject(){
		$user = UserAccount::getInstance('josoli');
		try{
			AccessManager::isAllowed($user, '', 'accesar');
		} catch(Exception $e){ return; }
		$this->assertFail('Exception expected.');
	}
	
	public function testIsAllowed_BlankAction(){
		$user = UserAccount::getInstance('josoli');
		try{
			AccessManager::isAllowed($user, 'operaciones', '');
		} catch(Exception $e){ return; }
		$this->assertFail('Exception expected.');
	}
	
	public function testIsAllowed_NonExistentSubject(){
		$user = UserAccount::getInstance('josoli');
		try{
			AccessManager::isAllowed($user, 'ecole', 'accesar');
		} catch(Exception $e){ return; }
		$this->assertFail('Exception expected.');
	}
	
	public function testIsAllowed_NonExistentAction(){
		$user = UserAccount::getInstance('josoli');
		try{
			AccessManager::isAllowed($user, 'operaciones', 'ecole');
		} catch(Exception $e){ return; }
		$this->assertFail('Exception expected.');
	}
}
?>