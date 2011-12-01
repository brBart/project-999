<?php
require_once('config/config.php');

require_once('business/cash.php');
require_once('business/user_account.php');
require_once('business/document.php');
require_once('business/product.php');
require_once('business/transaction.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('PHPUnit/Extensions/Database/DataSet/DataSetFilter.php');

class BankDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank-seed.xml');
	}
	
	public function testInsert(){
		$bank = new Bank();
		$bank->setData('Gyt Continental');
		$id = BankDAM::insert($bank);
		$this->assertGreaterThan(0, $id);
		
		$bank = new Bank();
		$bank->setData('Bi');
		$id = BankDAM::insert($bank);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/bank-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('bank')),
				array('bank' => array('bank_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$bank = new Bank();
		$bank->setData('Gyt Continental');
		$id = BankDAM::insert($bank);
		
		$other_bank = BankDAM::getInstance($id);
		$this->assertEquals($id, $other_bank->getId());
		$this->assertEquals(Persist::CREATED, $other_bank->getStatus());
		$this->assertEquals('Gyt Continental', $other_bank->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(BankDAM::getInstance(999999999));
	}
	
	public function testUpdate(){
		$bank = new Bank();
		$bank->setData('Gyt Continental');
		$id = BankDAM::insert($bank);
		
		$other_bank = BankDAM::getInstance($id);
		$other_bank->setName('Bantrab');
		BankDAM::update($other_bank);
		
		$xml_dataset = $this->createXMLDataSet('data_files/bank-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('bank')),
				array('bank' => array('bank_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$bank = new Bank();
		$bank->setData('Gyt Continental');
		$id = BankDAM::insert($bank);
		
		$other_bank = BankDAM::getInstance($id);
		$this->assertTrue(BankDAM::delete($other_bank));
		$xml_dataset = $this->createXMLDataSet('data_files/bank-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('bank')));
	}
}

class BankDeleteBankAccountDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank-bank_account-dependency.xml');
	}
	
	public function testDelete(){
		$other_bank = BankDAM::getInstance(1);
		$this->assertFalse(BankDAM::delete($other_bank));
	}
}

class BankAccountDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank_account-seed.xml');
	}
	
	public function testInsert(){
		$bank_account = new BankAccount('29-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Noe Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$bank_account = new BankAccount('14-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Roberto Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$bank_account = new BankAccount('123-9999');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Chepe Gil', $bank);
		BankAccountDAM::insert($bank_account);
		
		$xml_dataset = $this->createXMLDataSet('data_files/bank_account-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('bank_account')));
	}
	
	//public function testInsert_NoData(){ Sends compile error! }
	
	public function testGetInstance(){
		$bank_account = new BankAccount('29-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Noe Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$other_bank_account = BankAccountDAM::getInstance('29-500');
		$this->assertEquals('29-500', $other_bank_account->getNumber());
		$this->assertEquals(Persist::CREATED, $other_bank_account->getStatus());
		$other_bank = $other_bank_account->getBank();
		$this->assertEquals($bank, $other_bank);
		$this->assertEquals('Noe Oliveros', $other_bank_account->getHolderName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(BankAccountDAM::getInstance('98799'));
	}
	
	public function testExists(){
		$bank_account = new BankAccount('29-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Noe Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$this->assertTrue(BankAccountDAM::exists('29-500'));
	}
	
	public function testExists_NonExistent(){
		$this->assertFalse(BankAccountDAM::exists('29-500'));
	}
	
	public function testUpdate(){
		$bank_account = new BankAccount('29-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Noe Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$other_bank_account = BankAccountDAM::getInstance('29-500');
		$bank = Bank::getInstance(2);
		$other_bank_account->setBank($bank);
		$other_bank_account->setHolderName('Roberts');
		BankAccountDAM::update($other_bank_account);
		
		$xml_dataset = $this->createXMLDataSet('data_files/bank_account-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('bank_account')));
	}
	
	public function testDelete(){
		$bank_account = new BankAccount('29-500');
		$bank = Bank::getInstance(1);
		$bank_account->setData('Noe Oliveros', $bank);
		BankAccountDAM::insert($bank_account);
		
		$other_bank_account = BankAccountDAM::getInstance('29-500');
		$this->assertTrue(BankAccountDAM::delete($other_bank_account));
		$xml_dataset = $this->createXMLDataSet('data_files/bank_account-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('bank_account')));
	}
}

class BankAccountDeleteDepositDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank_account-deposit-dependency.xml');
	}
	
	public function testDelete(){
		$bank_account = BankAccountDAM::getInstance('29-500');
		$this->assertFalse(BankAccountDAM::delete($bank_account));
	}
}

class ShiftDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shift-seed.xml');
	}
	
	public function testInsert(){
		$shift = new Shift();
		$shift->setData('Diurno', '8am - 12pm');
		$id = ShiftDAM::insert($shift);
		$this->assertGreaterThan(0, $id);
		
		$shift = new Shift();
		$shift->setData('Nocturno', '6pm - 11pm');
		$id = ShiftDAM::insert($shift);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/shift-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('shift')),
				array('shift' => array('shift_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$shift = new Shift();
		$shift->setData('Diurno', '8am - 12pm');
		$id = ShiftDAM::insert($shift);
		
		$other_shift = ShiftDAM::getInstance($id);
		$this->assertEquals($id, $other_shift->getId());
		$this->assertEquals(Persist::CREATED, $other_shift->getStatus());
		$this->assertEquals('Diurno', $other_shift->getName());
		$this->assertEquals('8am - 12pm', $other_shift->getTimeTable());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ShiftDAM::getInstance(9948845858));
	}
	
	public function testUpdate(){
		$shift = new Shift();
		$shift->setData('Diurno', '8am - 12pm');
		$id = ShiftDAM::insert($shift);
		
		$other_shift = ShiftDAM::getInstance($id);
		$other_shift->setName('Nocturno');
		$other_shift->setTimeTable('7pm - 3am');
		ShiftDAM::update($other_shift);
		
		$xml_dataset = $this->createXMLDataSet('data_files/shift-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('shift')),
				array('shift' => array('shift_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$shift = new Shift();
		$shift->setData('Diurno', '8am - 12pm');
		$id = ShiftDAM::insert($shift);
		
		$other_shift = ShiftDAM::getInstance($id);
		$this->assertTrue(ShiftDAM::delete($other_shift));
		$xml_dataset = $this->createXMLDataSet('data_files/shift-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('shift')));
	}
}

class ShiftDeleteCashRegisterDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shift-cash_register-dependency.xml');
	}
	
	public function testDelete(){
		$shift = ShiftDAM::getInstance(1);
		$this->assertFalse(ShiftDAM::delete($shift));
	}
}

class CashRegisterDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cash_register-seed.xml');
	}
	
	public function testGetInstance(){
		$shift = ShiftDAM::getInstance(1);
		$cash_register = CashRegisterDAM::getInstance(1);
		$this->assertEquals(1, $cash_register->getId());
		$this->assertEquals(Persist::CREATED, $cash_register->getStatus());
		$this->assertEquals($shift, $cash_register->getShift());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(CashRegisterDAM::getInstance(9999449));
	}
	
	public function testClose(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$this->assertTrue(CashRegisterDAM::isOpen($cash_register));
		
		CashRegisterDAM::close($cash_register);
		$this->assertFalse(CashRegisterDAM::isOpen($cash_register));
	}
}

class CashDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cash-seed.xml');
	}
	
	public function testGetInstance(){
		$cash = CashDAM::getInstance(1);
		$this->assertEquals(1, $cash->getId());
		$this->assertEquals(Persist::CREATED, $cash->getStatus());
		$this->assertEquals(432.45, $cash->getAmount());
		$this->assertEquals(400.00, $cash->getAvailable());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(CashDAM::getInstance(9948));
	}
	
	public function testReserve(){
		$cash = CashDAM::getInstance(1);
		$cash->reserve(10.00);
		$this->assertEquals(390.00, $cash->getAvailable());
	}
	
	public function testDecreaseReserve(){
		$cash = CashDAM::getInstance(1);
		$cash->decreaseReserve(10.00);
		$this->assertEquals(410.00, $cash->getAvailable());
	}
	
	public function testDeposit(){
		$cash = CashDAM::getInstance(1);
		$cash->deposit(15.00);
		$this->assertEquals(385.00, $cash->getAvailable());
	}
	
	public function testDecreaseDeposit(){
		$cash = CashDAM::getInstance(1);
		$cash->decreaseDeposit(15.00);
		$this->assertEquals(415.00, $cash->getAvailable());
	}
}

class DepositDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/deposit-seed.xml');
	}
	
	public function testInsert(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$deposit = new Deposit($cash_register, '03/06/2009 00:00:00', UserAccountDAM::getInstance('roboli'));
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details[] = new DepositDetail(new Cash(0.0, 1, NULL, NULL, Persist::CREATED), 100.00);
		$details[] = new DepositDetail(new Cash(0.0, 2, NULL, NULL, Persist::CREATED), 200.00);
		$deposit->setData('123', $bank_account, 342.56, $details);
		$id = DepositDAM::insert($deposit);
		$this->assertGreaterThan(0, $id);
		
		$cash_register = CashRegisterDAM::getInstance(1);
		$deposit = new Deposit($cash_register, '04/06/2009 00:00:00', UserAccountDAM::getInstance('roboli'));
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details2[] = new DepositDetail(new Cash(0.0, 3, NULL, NULL, Persist::CREATED), 300.00);
		$details2[] = new DepositDetail(new Cash(0.0, 4, NULL, NULL, Persist::CREATED), 400.00);
		$deposit->setData('456', $bank_account, 500.00, $details2);
		$id = DepositDAM::insert($deposit);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/deposit-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('deposit', 'deposit_cash_receipt')),
				array('deposit' => array('deposit_id'),
				'deposit_cash_receipt' => array('deposit_cash_receipt_id', 'deposit_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$deposit = new Deposit($cash_register, '04/06/2009 00:00:00', UserAccountDAM::getInstance('roboli'));
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details[] = new DepositDetail(new Cash(0.0, 3, NULL, NULL, Persist::CREATED), 300.00);
		$details[] = new DepositDetail(new Cash(0.0, 4, NULL, NULL, Persist::CREATED), 400.00);
		$deposit->setData('456', $bank_account, 500.00, $details);
		DepositDAM::insert($deposit);
		
		$cash_register = CashRegisterDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$deposit = new Deposit($cash_register, '03/06/2009 00:00:00', $user);
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details1[] = new DepositDetail(CashDAM::getInstance(8), 100.00);
		$details1[] = new DepositDetail(CashDAM::getInstance(20), 200.00);
		$details1[] = new DepositDetail(CashDAM::getInstance(3), 300.00);
		$details1[] = new DepositDetail(CashDAM::getInstance(4), 400.00);
		$details2[] = new DepositDetail(CashDAM::getInstance(11), 500.00);
		$details2[] = new DepositDetail(CashDAM::getInstance(6), 600.00);
		$details2[] = new DepositDetail(CashDAM::getInstance(7), 700.00);
		$details2[] = new DepositDetail(CashDAM::getInstance(1), 800.00);
		$details3[] = new DepositDetail(CashDAM::getInstance(9), 100.00);
		$details3[] = new DepositDetail(CashDAM::getInstance(10), 200.00);
		$details3[] = new DepositDetail(CashDAM::getInstance(15), 300.00);
		$details3[] = new DepositDetail(CashDAM::getInstance(12), 400.00);
		$details = array_merge($details1, $details2, $details3);
		$deposit->setData('123', $bank_account, 342.56, $details);
		$id = DepositDAM::insert($deposit);

		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		$this->assertEquals($id, $other_deposit->getId());
		$this->assertEquals(Deposit::CREATED, $other_deposit->getStatus());
		$this->assertEquals($cash_register, $other_deposit->getCashRegister());
		$this->assertEquals('03/06/2009 00:00:00', $other_deposit->getDateTime());
		$this->assertEquals($user, $other_deposit->getUser());
		$this->assertEquals('123', $other_deposit->getNumber());
		$this->assertEquals($bank_account, $other_deposit->getBankAccount());
		$this->assertEquals(342.56, $other_deposit->getTotal());
		$this->assertEquals($details1, $other_deposit->getDetails());
		$this->assertEquals(3, $pages);
		$this->assertEquals(12, $items);
		
		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 2);
		$this->assertEquals($details2, $other_deposit->getDetails());
		
		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 3);
		$this->assertEquals($details3, $other_deposit->getDetails());
		
		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 0);
		$this->assertEquals($details, $other_deposit->getDetails());
	}
	
	public function testGetInstance_2(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$deposit = new Deposit($cash_register, '03/06/2009 00:00:00', $user);
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details[] = new DepositDetail(CashDAM::getInstance(1), 100.00);
		$deposit->setData('123', $bank_account, 342.56, $details);
		$id = DepositDAM::insert($deposit);

		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		$this->assertEquals($id, $other_deposit->getId());
		$this->assertEquals(Deposit::CREATED, $other_deposit->getStatus());
		$this->assertEquals($cash_register, $other_deposit->getCashRegister());
		$this->assertEquals('03/06/2009 00:00:00', $other_deposit->getDateTime());
		$this->assertEquals($user, $other_deposit->getUser());
		$this->assertEquals('123', $other_deposit->getNumber());
		$this->assertEquals($bank_account, $other_deposit->getBankAccount());
		$this->assertEquals(342.56, $other_deposit->getTotal());
		$this->assertEquals($details, $other_deposit->getDetails());
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
		
		try{
			$other_deposit = DepositDAM::getInstance($id, $pages, $items, 2);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(DepositDAM::getInstance(9999, $pages, $items, 1));
	}
	
	public function testConfirm(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$deposit = new Deposit($cash_register, '04/06/2009 00:00:00', UserAccountDAM::getInstance('roboli'));
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details[] = new DepositDetail(new Cash(0.0, 4, NULL, NULL, Persist::CREATED), 400.00);
		$deposit->setData('456', $bank_account, 500.00, $details);
		$id = DepositDAM::insert($deposit);
		
		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		DepositDAM::confirm($other_deposit);
		
		$another_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		$this->assertEquals(Deposit::CONFIRMED, $another_deposit->getStatus());
	}
	
	public function testCancel(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$deposit = new Deposit($cash_register, '04/06/2009 00:00:00', $user);
		$bank_account = BankAccountDAM::getInstance('29-500');
		$details[] = new DepositDetail(new Cash(0.0, 4, NULL, NULL, Persist::CREATED), 400.00);
		$deposit->setData('456', $bank_account, 500.00, $details);
		$id = DepositDAM::insert($deposit);
		
		$other_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		DepositDAM::cancel($other_deposit, $user, '04/06/2009 00:00:00');
		
		$another_deposit = DepositDAM::getInstance($id, $pages, $items, 1);
		$this->assertEquals(Deposit::CANCELLED, $another_deposit->getStatus());
		
		$xml_dataset = $this->createXMLDataSet('data_files/deposit-after-cancel.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('deposit_cancelled')),
				array('deposit_cancelled' => array('deposit_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class DepositListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/deposit-list-seed.xml');
	}
	
	public function testGetList(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$list = array(array('id' => '1', 'bank_id' => '2', 'number' => '123', 'status' => '1'),
				array('id' => '3', 'bank_id' => '2', 'number' => '1234', 'status' => '1'),
				array('id' => '5', 'bank_id' => '2', 'number' => '1235', 'status' => '1'));
		$data_list = DepositListDAM::getList($cash_register);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$cash_register = CashRegisterDAM::getInstance(3);
		$data_list = DepositListDAM::getList($cash_register);
		$this->assertTrue(empty($data_list));
	}
}

class SalesReportDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/sales_report-seed.xml');
	}
	
	public function testGetInstance(){
		$invoices = array(
				array(
				'serial_number' => 'A021',
				'number' => '123',
				'name' => 'Juan Perez',
				'sub_total' => '234.56',
				'discount_percentage' => '0.00',
				'discount_value' => '0.00',
				'total' => '234.56',
				'cash' => '234.56',
				'total_vouchers' => '0.00',
				'status' => '1'),
				array(
				'serial_number' => 'A021',
				'number' => '124',
				'name' => '',
				'sub_total' => '123.45',
				'discount_percentage' => '10.00',
				'discount_value' => '12.35',
				'total' => '111.11',
				'cash' => '0.00',
				'total_vouchers' => '123.45',
				'status' => '1'),
				array(
				'serial_number' => 'A021',
				'number' => '125',
				'name' => 'Benito Juarez',
				'sub_total' => '135.79',
				'discount_percentage' => '10.00',
				'discount_value' => '13.58',
				'total' => '122.21',
				'cash' => '0.00',
				'total_vouchers' => '135.79',
				'status' => '1'),
				array(
				'serial_number' => 'A021',
				'number' => '126',
				'name' => 'Charles Brwon',
				'sub_total' => '0.00',
				'discount_percentage' => '0.00',
				'discount_value' => '0.00',
				'total' => '0.00',
				'cash' => '0.00',
				'total_vouchers' => '0.00',
				'status' => '2'),
				array(
				'serial_number' => 'A021',
				'number' => '127',
				'name' => '',
				'sub_total' => '76.32',
				'discount_percentage' => '0.00',
				'discount_value' => '0.00',
				'total' => '76.32',
				'cash' => '0.00',
				'total_vouchers' => '76.32',
				'status' => '1'),
				array(
				'serial_number' => 'A021',
				'number' => '128',
				'name' => '',
				'sub_total' => '0.00',
				'discount_percentage' => '0.00',
				'discount_value' => '0.00',
				'total' => '0.00',
				'cash' => '0.00',
				'total_vouchers' => '0.00',
				'status' => '2'),
				array(
				'serial_number' => 'A021',
				'number' => '129',
				'name' => 'Simoncho',
				'sub_total' => '194.32',
				'discount_percentage' => '0.00',
				'discount_value' => '0.00',
				'total' => '194.32',
				'cash' => '194.32',
				'total_vouchers' => '0.00',
				'status' => '1'));
		
		$cash_register = CashRegisterDAM::getInstance(1);
		$report = SalesReportDAM::getInstance($cash_register);
		$this->assertEquals(764.44, number_format($report->getTotal(), 2));
		$this->assertEquals(428.88, $report->getTotalCash());
		$this->assertEquals(335.56, $report->getTotalVouchers());
		$this->assertEquals(25.92, number_format($report->getTotalDiscount(), 2));
		$this->assertEquals(88.62, number_format($report->getTotalVat(), 2));
		
		foreach($report->getInvoices() as $invoice){
			$list[] = array(
					'serial_number' => $invoice['serial_number'],
					'number' => $invoice['number'],
					'name' => $invoice['name'],
					'sub_total' => $invoice['sub_total'],
					'discount_percentage' => number_format($invoice['discount_percentage'], 2),
					'discount_value' => number_format($invoice['discount_value'], 2),
					'total' => number_format($invoice['total'], 2),
					'cash' => number_format($invoice['cash'], 2),
					'total_vouchers' => number_format($invoice['total_vouchers'], 2),
					'status' => $invoice['status']);
		}
		
		$this->assertEquals($invoices, $list);
	}
	
	public function testGetInstance_2(){
		$invoices = array(
					array(
					'serial_number' => 'A022',
					'number' => '321',
					'name' => '',
					'sub_total' => '87.65',
					'discount_percentage' => '0',
					'discount_value' => '0.00',
					'total' => '87.65',
					'cash' => '87.65',
					'total_vouchers' => '0.00',
					'status' => '1'),
					array(
					'serial_number' => 'A022',
					'number' => '322',
					'name' => 'Juna Lupe',
					'sub_total' => '99.88',
					'discount_percentage' => '15.00',
					'discount_value' => '14.98',
					'total' => '84.90',
					'cash' => '99.88',
					'total_vouchers' => '0.00',
					'status' => '1'));
		
		$cash_register = CashRegisterDAM::getInstance(2);
		$report = SalesReportDAM::getInstance($cash_register);
		$this->assertEquals(187.53, number_format($report->getTotal(), 2));
		$this->assertEquals(187.53, $report->getTotalCash());
		$this->assertEquals(0, $report->getTotalVouchers());
		$this->assertEquals(14.98, number_format($report->getTotalDiscount(), 2));
		$this->assertEquals(20.71, number_format($report->getTotalVat(), 2));
		
		foreach($report->getInvoices() as $invoice){
			$list[] = array(
					'serial_number' => $invoice['serial_number'],
					'number' => $invoice['number'],
					'name' => $invoice['name'],
					'sub_total' => $invoice['sub_total'],
					'discount_percentage' => number_format($invoice['discount_percentage'], 2),
					'discount_value' => number_format($invoice['discount_value'], 2),
					'total' => number_format($invoice['total'], 2),
					'cash' => number_format($invoice['cash'], 2),
					'total_vouchers' => number_format($invoice['total_vouchers'], 2),
					'status' => $invoice['status']);
		}
		
		$this->assertEquals($invoices, $list);
	}
	
	public function testGetInstance_3(){
		$cash_register = CashRegisterDAM::getInstance(3);
		$report = SalesReportDAM::getInstance($cash_register);
		$this->assertEquals(0, $report->getTotal());
		$this->assertEquals(0, $report->getTotalCash());
		$this->assertEquals(0, $report->getTotalVouchers());
		$this->assertEquals(0, $report->getTotalDiscount());
		$this->assertEquals(0, $report->getTotalVat());
		$this->assertEquals(0, count($report->getInvoices()));
	}
}

class DepositDetailListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/deposit_detail-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('deposit_id' => '1'), array('deposit_id' => '3'), array('deposit_id' => '4'));
		$cash = new Cash(0.0, 32, Persist::CREATED);
		$data_list = DepositDetailListDAM::getList($cash);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('deposit_id' => '2'), array('deposit_id' => '5'));
		$cash = new Cash(0.0, 31, Persist::CREATED);
		$data_list = DepositDetailListDAM::getList($cash);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$cash = new Cash(0.0, 33, Persist::CREATED);
		$data_list = DepositDetailListDAM::getList($cash);
		$this->assertTrue(empty($data_list));
	}
}

class InvoiceListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice-list-seed.xml');
	}
	
	public function testGetList(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$list = array(array('id' => '1', 'serial_number' => 'A021', 'number' => '123'),
				array('id' => '3', 'serial_number' => 'A021', 'number' => '125'),
				array('id' => '5', 'serial_number' => 'A021', 'number' => '127'));
		$data_list = InvoiceListDAM::getList($cash_register);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$cash_register = CashRegisterDAM::getInstance(3);
		$data_list = InvoiceListDAM::getList($cash_register);
		$this->assertTrue(empty($data_list));
	}
}

class WorkingDayDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/working_day-seed.xml');
	}
	
	public function testInsert(){
		WorkingDayDAM::insert('08/06/2009');
		WorkingDAyDAM::insert('09/06/2009');
		
		$xml_dataset = $this->createXMLDataSet('data_files/working_day-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('working_day')));
	}
	
	public function testGetInstance(){
		WorkingDayDAM::insert('08/06/2009');
		$working_day = WorkingDayDAM::getInstance('08/06/2009');
		$this->assertEquals('08/06/2009', $working_day->getDate());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(WorkingDayDAM::getInstance('01/01/2001'));
	}
	
	public function testClose(){
		$working_day = WorkingDayDAM::insert('08/06/2009');
		$this->assertTrue($working_day->isOpen());
		
		$working_day->close();
		$this->assertFalse($working_day->isOpen());
	}
	
	public function testInsertCashRegister(){
		$working_day = WorkingDayDAM::insert('08/06/2009');
		$shift = ShiftDAM::getInstance(1);
		WorkingDayDAM::insertCashRegister($working_day, $shift);
		
		$working_day = WorkingDayDAM::insert('09/06/2009');
		$shift = ShiftDAM::getInstance(1);
		WorkingDayDAM::insertCashRegister($working_day, $shift);
		
		$xml_dataset = $this->createXMLDataSet('data_files/working_day-after-cash_registers-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('working_day', 'cash_register')),
				array('cash_register' => array('cash_register_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetCashRegister(){
		$working_day = WorkingDayDAM::insert('08/06/2009');
		$shift = ShiftDAM::getInstance(1);
		WorkingDayDAM::insertCashRegister($working_day, $shift);
		
		$cash_register = WorkingDayDAM::getCashRegister($working_day, $shift);
		$this->assertEquals($shift, $cash_register->getShift());
		$this->assertEquals(Persist::CREATED, $cash_register->getStatus());
		$this->assertGreaterThan(0, $cash_register->getId());
	}
	
	public function testGetCashRegister_NonExistent(){
		$working_day = WorkingDayDAM::insert('08/06/2009');
		$shift = ShiftDAM::getInstance(1);
		$this->assertNull(WorkingDayDAM::getCashRegister($working_day, $shift));
	}
	
	public function testCloseCashRegisters(){
		$working_day = WorkingDayDAM::insert('08/06/2009');
		$shift = ShiftDAM::getInstance(1);
		$cash1 = WorkingDayDAM::insertCashRegister($working_day, $shift);
		$this->assertTrue($cash1->isOpen());
		
		$shift = ShiftDAM::getInstance(2);
		$cash2 = WorkingDayDAM::insertCashRegister($working_day, $shift);
		$this->assertTrue($cash2->isOpen());
		
		$working_day2 = WorkingDayDAM::insert('09/06/2009');
		$shift = ShiftDAM::getInstance(1);
		$cash3 = WorkingDayDAM::insertCashRegister($working_day2, $shift);
		
		WorkingDayDAM::closeCashRegisters($working_day);
		$this->assertFalse($cash1->isOpen());
		$this->assertFalse($cash2->isOpen());
		$this->assertTrue($cash3->isOpen());		
	}
}

class PaymentCardBrandDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_brand-seed.xml');
	}
	
	public function testInsert(){
		$brand = new PaymentCardBrand();
		$brand->setData('Visa');
		$id = PaymentCardBrandDAM::insert($brand);
		$this->assertGreaterThan(0, $id);
		
		$brand = new PaymentCardBrand();
		$brand->setData('Master Card');
		$id = PaymentCardBrandDAM::insert($brand);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_brand-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('payment_card_brand')),
				array('payment_card_brand' => array('payment_card_brand_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$brand = new PaymentCardBrand();
		$brand->setData('Visa');
		$id = PaymentCardBrandDAM::insert($brand);
		
		$other_brand = PaymentCardBrandDAM::getInstance($id);
		$this->assertEquals($id, $other_brand->getId());
		$this->assertEquals(Persist::CREATED, $other_brand->getStatus());
		$this->assertEquals('Visa', $other_brand->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(PaymentCardBrandDAM::getInstance(999999999));
	}
	
	public function testUpdate(){
		$brand = new PaymentCardBrand();
		$brand->setData('Visa');
		$id = PaymentCardBrandDAM::insert($brand);
		
		$other_brand = PaymentCardBrandDAM::getInstance($id);
		$other_brand->setName('American Express');
		PaymentCardBrandDAM::update($other_brand);
		
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_brand-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('payment_card_brand')),
				array('payment_card_brand' => array('payment_card_brand_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$brand = new PaymentCardBrand();
		$brand->setData('Bi Club');
		$id = PaymentCardBrandDAM::insert($brand);
		
		$other_brand = PaymentCardBrandDAM::getInstance($id);
		$this->assertTrue(PaymentCardBrandDAM::delete($other_brand));
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_brand-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('payment_card_brand')));
	}
}

class PaymentCardBrandDeleteVoucherDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_brand-voucher-dependency.xml');
	}
	
	public function testDelete(){
		$other_brand = PaymentCardBrandDAM::getInstance(1);
		$this->assertFalse(PaymentCardBrandDAM::delete($other_brand));
	}
}

class PaymentCardTypeDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_type-seed.xml');
	}
	
	public function testInsert(){
		$type = new PaymentCardType();
		$type->setData('Credito');
		$id = PaymentCardTypeDAM::insert($type);
		$this->assertGreaterThan(0, $id);
		
		$type = new PaymentCardType();
		$type->setData('Debito');
		$id = PaymentCardTypeDAM::insert($type);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_type-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('payment_card_type')),
				array('payment_card_type' => array('payment_card_type_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$type = new PaymentCardType();
		$type->setData('Credito');
		$id = PaymentCardTypeDAM::insert($type);
		
		$other_type = PaymentCardTypeDAM::getInstance($id);
		$this->assertEquals($id, $other_type->getId());
		$this->assertEquals(Persist::CREATED, $other_type->getStatus());
		$this->assertEquals('Credito', $other_type->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(PaymentCardTypeDAM::getInstance(999999999));
	}
	
	public function testUpdate(){
		$type = new PaymentCardType();
		$type->setData('Credito');
		$id = PaymentCardTypeDAM::insert($type);
		
		$other_type = PaymentCardTypeDAM::getInstance($id);
		$other_type->setName('Prepago');
		PaymentCardTypeDAM::update($other_type);
		
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_type-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('payment_card_type')),
				array('payment_card_type' => array('payment_card_type_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$type = new PaymentCardType();
		$type->setData('Prepago');
		$id = PaymentCardTypeDAM::insert($type);
		
		$other_type = PaymentCardTypeDAM::getInstance($id);
		$this->assertTrue(PaymentCardTypeDAM::delete($other_type));
		$xml_dataset = $this->createXMLDataSet('data_files/payment_card_type-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('payment_card_type')));
	}
}

class PaymentCardTypeDeleteVoucherDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_type-voucher-dependency.xml');
	}
	
	public function testDelete(){
		$other_type = PaymentCardTypeDAM::getInstance(1);
		$this->assertFalse(PaymentCardTypeDAM::delete($other_type));
	}
}

class CashReceiptDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cash_receipt-seed.xml');
	}
	
	public function testInsert(){
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 1);
		$product = new Product(1, Persist::CREATED);
		$lot = new Lot($product);
		$detail = new DocProductDetail($lot, new Withdraw(), 1, 10.00);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$cash = new Cash(30.00);
		$type = PaymentCardType::getInstance(1);
		$brand = PaymentCardBrand::getInstance(1);
		$card = new PaymentCard(4321, $type, $brand, 'Carlos', '01/06/2010');
		$receipt->setCash($cash);
		$receipt->addVoucher(new Voucher('65432', $card, 11.50));
		$receipt->addVoucher(new Voucher('54321', $card, 10.00));
		$receipt->setChange(1.50);
		CashReceiptDAM::insert($receipt);
		
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 2);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$cash = new Cash(123.45);
		$receipt->setCash($cash);
		CashReceiptDAM::insert($receipt);
		
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 3);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$type = PaymentCardType::getInstance(1);
		$brand = PaymentCardBrand::getInstance(1);
		$card = new PaymentCard(1234, $type, $brand, 'John', '01/07/2010');
		$receipt->addVoucher(new Voucher('76543', $card, 114.90));
		CashReceiptDAM::insert($receipt);
		
		$xml_dataset = $this->createXMLDataSet('data_files/cash_receipt-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('cash_receipt', 'voucher')),
				array('voucher' => array('voucher_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 1);
		$product = new Product(1, Persist::CREATED);
		$lot = new Lot($product);
		$detail = new DocProductDetail($lot, new Withdraw(), 1, 10.00);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$cash = new Cash(30.00);
		$type = PaymentCardType::getInstance(1);
		$brand = PaymentCardBrand::getInstance(1);
		$card = new PaymentCard(4321, $type, $brand, 'Carlos', '01/06/2010');
		$receipt->setCash($cash);
		$receipt->addVoucher(new Voucher('54321', $card, 10.00));
		$receipt->addVoucher(new Voucher('65432', $card, 11.50));
		$receipt->addVoucher(new Voucher('1111', $card, 11.50));
		$receipt->setChange(1.50);
		CashReceiptDAM::insert($receipt);
		$vouchers = $receipt->getVouchers();
		
		$other_receipt = CashReceiptDAM::getInstance($invoice);
		$this->assertEquals($invoice, $other_receipt->getInvoice());
		$this->assertEquals($invoice->getId(), $other_receipt->getId());
		// NOTE the status because the invoice is a fake one...
		$this->assertEquals(PersistDocument::IN_PROGRESS, $other_receipt->getStatus());
		$other_cash = $other_receipt->getCash();
		$this->assertEquals($cash->getAmount(), $other_cash->getAmount());
		$this->assertEquals($invoice->getId(), $other_cash->getId());
		$this->assertEquals(Persist::CREATED, $other_cash->getStatus());
		$this->assertEquals(33, $other_receipt->getTotalVouchers());
		$this->assertEquals(1.50, $other_receipt->getChange());
		$this->assertEquals($vouchers, $other_receipt->getVouchers());
	}
	
	public function testGetInstance_2(){
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 1);
		$product = new Product(1, Persist::CREATED);
		$lot = new Lot($product);
		$detail = new DocProductDetail($lot, new Withdraw(), 1, 10.00);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$cash = new Cash(123.45);
		$receipt->setCash($cash);
		CashReceiptDAM::insert($receipt);
		
		$other_receipt = CashReceiptDAM::getInstance($invoice);
		$this->assertEquals($invoice, $other_receipt->getInvoice());
		$this->assertEquals($invoice->getId(), $other_receipt->getId());
		// NOTE the status because the invoice is a fake one...
		$this->assertEquals(PersistDocument::IN_PROGRESS, $other_receipt->getStatus());
		$other_cash = $other_receipt->getCash();
		$this->assertEquals($cash->getAmount(), $other_cash->getAmount());
		$this->assertEquals($invoice->getId(), $other_cash->getId());
		$this->assertEquals(Persist::CREATED, $other_cash->getStatus());
		$this->assertEquals(0, $other_receipt->getTotalVouchers());
		$this->assertEquals(0, $other_receipt->getChange());
		$this->assertEquals(0, count($other_receipt->getVouchers()));
	}
	
	public function testGetInstance_3(){
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 1);
		$product = new Product(1, Persist::CREATED);
		$lot = new Lot($product);
		$detail = new DocProductDetail($lot, new Withdraw(), 1, 10.00);
		$invoice->addDetail($detail);
		$receipt = new CashReceipt($invoice);
		$type = PaymentCardType::getInstance(1);
		$brand = PaymentCardBrand::getInstance(1);
		$card = new PaymentCard(1234, $type, $brand, 'John', '01/07/2010');
		$receipt->addVoucher(new Voucher('76543', $card, 114.90));
		CashReceiptDAM::insert($receipt);
		$vouchers = $receipt->getVouchers();
		
		$other_receipt = CashReceiptDAM::getInstance($invoice);
		$this->assertEquals($invoice, $other_receipt->getInvoice());
		$this->assertEquals($invoice->getId(), $other_receipt->getId());
		// NOTE the status because the invoice is a fake one...
		$this->assertEquals(PersistDocument::IN_PROGRESS, $other_receipt->getStatus());
		$other_cash = $other_receipt->getCash();
		$this->assertEquals(0, $other_cash->getAmount());
		$this->assertEquals($invoice->getId(), $other_cash->getId());
		$this->assertEquals(Persist::CREATED, $other_cash->getStatus());
		$this->assertEquals(114.90, $other_receipt->getTotalVouchers());
		$this->assertEquals(0, $other_receipt->getChange());
		$this->assertEquals($vouchers, $other_receipt->getVouchers());		
	}
	
	public function testGetInstance_NonExistent(){
		$cash_register = CashRegister::getInstance(1);
		$invoice = new Invoice($cash_register, '10/06/2009 19:00:00', UserAccount::getInstance('roboli'), 99);
		$this->assertNull(CashReceiptDAM::getInstance($invoice));
	}
}

class AvailableCashReceiptListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cash_receipt_available-seed.xml');
	}
	
	public function testGetList(){
		$list = array(
				array('id' => '2', 'serial_number' => 'A021', 'number' => '124',
						'received_cash' => '23.32', 'available_cash' => '10.00'),
				array('id' => '4', 'serial_number' => 'A021', 'number' => '126',
						'received_cash' => '43.21', 'available_cash' => '13.21'),
				array('id' => '6', 'serial_number' => 'A021', 'number' => '322',
						'received_cash' => '50.00', 'available_cash' => '50.00'));
		
		$cash_register = CashRegister::getInstance(1);
		$data_list = AvailableCashReceiptListDAM::getList($cash_register);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$cash_register = CashRegister::getInstance(3);
		$data_list = AvailableCashReceiptListDAM::getList($cash_register);
		$this->assertTrue(empty($data_list));
	}
}

class GeneralSalesReportDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/general_sales_report-seed.xml');
	}
	
	public function testGetInstance(){
		$list = array(array('id' => '1', 'name' => 'diurno', 'time_table' => '8am - 12pm',
				'total' => '727.41'), array('id' => '2', 'name' => 'nocturno',
				'time_table' => '8pm - 1am', 'total' => '174.38'));
		
		$working_day = new WorkingDay('09/06/2009');
		$report = GeneralSalesReportDAM::getInstance($working_day);
		$this->assertEquals(901.79, number_format($report->getTotal(), 2));
		
		foreach($report->getCashRegisters() as $register){
			$cash_registers[] = array('id' => $register['id'],
					'name' => $register['name'], 'time_table' => $register['time_table'],
					'total' => number_format($register['total'], 2));
		}
		
		$this->assertEquals($list, $cash_registers);
	}
	
	public function testGetInstance_2(){
		$list = array(array('id' => '3', 'name' => 'diurno', 'time_table' => '8am - 12pm',
				'total' => '432.12'));
		
		$working_day = new WorkingDay('10/06/2009');
		$report = GeneralSalesReportDAM::getInstance($working_day);
		$this->assertEquals(432.12, $report->getTotal());
		
		foreach($report->getCashRegisters() as $register){
			$cash_registers[] = array('id' => $register['id'],
					'name' => $register['name'], 'time_table' => $register['time_table'],
					'total' => number_format($register['total'], 2));
		}
		
		$this->assertEquals($list, $cash_registers);
	}
	
	public function testGetInstance_3(){
		$working_day = new WorkingDay('11/06/2009');
		$report = GeneralSalesReportDAM::getInstance($working_day);
		$this->assertEquals(0, count($report->getCashRegisters()));
		$this->assertEquals(0, $report->getTotal());
	}
}
?>