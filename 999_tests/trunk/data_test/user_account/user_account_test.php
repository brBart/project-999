<?php
require_once('business/user_account.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');

class RoleDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/role-seed.xml');
	}
	
	public function testGetInstance(){
		$role = RoleDAM::getInstance(1);
		$this->assertEquals(1, $role->getId());
		$this->assertEquals('Administrador', $role->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(RoleDAM::getInstance(999));
	}
}

class UserAccountDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-seed.xml');
	}
	
	public function testInsert(){
		$user = new UserAccount('roboli');
		$role = RoleDAM::getInstance(1);
		$user->setData('Roberto', 'Oliveros', $role, false);
		$user->setPassword('hola');
		UserAccountDAM::insert($user);
		
		$user = new UserAccount('edglem');
		$role = RoleDAM::getInstance(1);
		$user->setData('Edgar', 'Lemus', $role, true);
		$user->setPassword('adios');
		UserAccountDAM::insert($user);
		
		$xml_dataset = $this->createXMLDataSet('data_files/user_account-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('user_account')));
	}
	
	public function testGetInstance(){
		$user = new UserAccount('roboli');
		$role = RoleDAM::getInstance(1);
		$user->setData('Roberto', 'Oliveros', $role, false);
		$user->setPassword('hola');
		UserAccountDAM::insert($user);
		
		$other_user = UserAccountDAM::getInstance('roboli');
		$this->assertEquals('roboli', $other_user->getUserName());
		$this->assertEquals(Persist::CREATED, $other_user->getStatus());
		$this->assertEquals('Roberto', $other_user->getFirstName());
		$this->assertEquals('Oliveros', $other_user->getLastName());
		$this->assertEquals($role, $other_user->getRole());
		$this->assertFalse($other_user->isDeactivated());
		$this->assertEquals('', $other_user->getPassword());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(UserAccountDAM::getInstance('no'));
	}
	
	public function testExists(){
		$user = new UserAccount('roboli');
		$role = RoleDAM::getInstance(1);
		$user->setData('Roberto', 'Oliveros', $role, false);
		$user->setPassword('hola');
		UserAccountDAM::insert($user);
		
		$this->assertTrue(UserAccountDAM::exists('roboli'));
	}
	
	public function testExists_NonExistent(){
		$this->assertFalse(UserAccountDAM::exists('roboli'));
	}
	
	public function testUpdate(){
		$user = new UserAccount('roboli');
		$role = RoleDAM::getInstance(1);
		$user->setData('Roberto', 'Oliveros', $role, false);
		$user->setPassword('hola');
		UserAccountDAM::insert($user);
		
		$other_user = UserAccountDAM::getInstance('roboli');
		$role = RoleDAM::getInstance(2);
		$other_user->setRole($role);
		$other_user->setFirstName('Carlos');
		$other_user->setLastName('Gonzalez');
		$other_user->setPassword('robs');
		$other_user->deactivate(true);
		UserAccountDAM::update($other_user);
		
		$xml_dataset = $this->createXMLDataSet('data_files/user_account-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('user_account')));
	}
	
	public function testUpdateNoPassword(){
		$user = new UserAccount('roboli');
		$role = RoleDAM::getInstance(1);
		$user->setData('Roberto', 'Oliveros', $role, false);
		$user->setPassword('hola');
		UserAccountDAM::insert($user);
		
		$other_user = UserAccountDAM::getInstance('roboli');
		$role = RoleDAM::getInstance(2);
		$other_user->setRole($role);
		$other_user->setFirstName('Carlos');
		$other_user->setLastName('Gonzalez');
		$other_user->setPassword('');
		$other_user->deactivate(true);
		UserAccountDAM::updateNoPassword($other_user);
		
		$xml_dataset = $this->createXMLDataSet('data_files/user_account_no_password-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('user_account')));
	}
}

class UserAccountDeleteTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-delete-seed.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertTrue(UserAccountDAM::delete($user));
		
		$xml_dataset = $this->createXMLDataSet('data_files/user_account-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('user_account')));
	}
}

class UserAccountDeleteChangePriceLogDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-change_price_log-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeletePurchaseReturnDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-purchase_return-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteShipmentDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-shipment-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteInvoiceDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-invoice-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteCountDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-count-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteReserveDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-reserve-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteComparisonDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-comparison-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteDepositDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-deposit-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteEntryAdjustmentDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-entry_adjustment-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteReceiptDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-receipt-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteWithdrawAdjustmentDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-withdraw_adjustment-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteDiscountDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-discount-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeletePurchaseReturnCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-purchase_return_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteShipmentCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-shipment_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteInvoiceCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-invoice_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteDepositCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-deposit_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteEntryAdjustmentCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-entry_adjustment_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteReceiptCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-receipt_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountDeleteWithdrawAdjustmentCancelledDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-withdraw_adjustment_cancelled-dependency.xml');
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(UserAccountDAM::delete($user));
	}
}

class UserAccountUtilityDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account-root-seed.xml');
	}
	
	public function testIsValid(){
		$this->assertTrue(UserAccountUtilityDAM::isValid('roboli', 'baa3559aa4bf24bc1494d3f505c3bd25cc3800cf'));
	}
	
	public function testIsValid_BadUserName(){
		$this->assertFalse(UserAccountUtilityDAM::isValid('josoli', 'baa3559aa4bf24bc1494d3f505c3bd25cc3800cf'));
	}
	
	public function testIsValid_BadPassword(){
		$this->assertFalse(UserAccountUtilityDAM::isValid('roboli', 'baa3559aa4bf24bc1494d3f505c3bd25cc3800'));
	}
	
	public function testIsValid_Deactivated(){
		$this->assertFalse(UserAccountUtilityDAM::isValid('edglem', 'baa3559aa4bf24bc1494d3f505c3bd25cc3800'));
	}
	
	public function testIsValidRoot(){
		$this->assertTrue(UserAccountUtilityDAM::isValidRoot('0d20d7268964e684200bf88d5696de48424698fb'));
	}
	
	public function testIsValidRoot_BadPassword(){
		$this->assertFalse(UserAccountUtilityDAM::isValidRoot('0d20d7268964e684200bf88d5696de48424698'));
	}
	
	public function testChangeRootPassword(){
		UserAccountUtilityDAM::changeRootPassword('hola');
		$this->assertTrue(UserAccountUtilityDAM::isValidRoot('hola'));
	}
	
	public function testChangePassword(){
		$user = UserAccountDAM::getInstance('roboli');
		UserAccountUtilityDAM::changePassword($user, 'hola');
		$this->assertTrue(UserAccountUtilityDAM::isValid('roboli', 'hola'));
	}
}

class SubjectDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/subject-seed.xml');
	}
	
	public function testGetId(){
		$this->assertEquals(1, SubjectDAM::getId('inventory'));
	}
	
	public function testGetId_2(){
		$this->assertEquals(2, SubjectDAM::getId('cash_register'));
	}
	
	public function testGetId_NonExistent(){
		$this->assertEquals(0, SubjectDAM::getId('ecole'));
	}
}

class ActionDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/action-seed.xml');
	}
	
	public function testGetId(){
		$this->assertEquals(1, ActionDAM::getId('access'));
	}
	
	public function testGetId_2(){
		$this->assertEquals(2, ActionDAM::getId('close'));
	}
	
	public function testGetId_NonExistent(){
		$this->assertEquals(0, ActionDAM::getId('ecole'));
	}
}

class AccessManagerDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/role_subject_action-seed.xml');
	}
	
	public function testIsAllowed(){
		$user = UserAccountDAM::getInstance('edglem');
		$this->assertTrue(AccessManagerDAM::isAllowed($user, 1, 1));
	}
	
	public function testIsAllowed_2(){
		$user = UserAccountDAM::getInstance('edglem');
		$this->assertTrue(AccessManagerDAM::isAllowed($user, 2, 2));
	}
	
	public function testIsAllowed_3(){
		$user = UserAccountDAM::getInstance('roboli');
		$this->assertFalse(AccessManagerDAM::isAllowed($user, 2, 2));
	}
}
?>