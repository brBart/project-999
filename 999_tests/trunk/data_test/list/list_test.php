<?php
require_once('config/config.php');

require_once('business/list.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');

class BankListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank_list-seed.xml');
	}
	
	public function testGetList(){
		$list = BankListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(5, $list[0]['id']);
		$this->assertEquals('Bac', $list[0]['name']);
		
		$this->assertEquals(4, $list[1]['id']);
		$this->assertEquals('Banrural', $list[1]['name']);
		
		$this->assertEquals(3, $list[2]['id']);
		$this->assertEquals('Bantrab', $list[2]['name']);
		
		$this->assertEquals(2, $list[3]['id']);
		$this->assertEquals('Bi', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = BankListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('City Bank', $list[0]['name']);
		
		$this->assertEquals(1, $list[1]['id']);
		$this->assertEquals('GytContinental', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(BankListDAM::getList($pages, $items, 3)));
	}
}

class PendingDepositListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/pending_deposit_list-seed.xml');
	}
	
	public function testGetList(){
		$list = PendingDepositListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(3, $list[0]['id']);
		$this->assertEquals('01/06/2009', $list[0]['created_date']);
		$this->assertEquals('001-500', $list[0]['bank_account_number']);
		$this->assertEquals('986', $list[0]['number']);
		$this->assertEquals('Bi', $list[0]['bank']);
		$this->assertEquals(800, $list[0]['total']);
		
		$this->assertEquals(1, $list[1]['id']);
		$this->assertEquals('03/06/2009', $list[1]['created_date']);
		$this->assertEquals('29-500', $list[1]['bank_account_number']);
		$this->assertEquals('123', $list[1]['number']);
		$this->assertEquals('Gyt Continental', $list[1]['bank']);
		$this->assertEquals(342.56, $list[1]['total']);
		
		$this->assertEquals(2, $list[2]['id']);
		$this->assertEquals('04/06/2009', $list[2]['created_date']);
		$this->assertEquals('29-500', $list[2]['bank_account_number']);
		$this->assertEquals('456', $list[2]['number']);
		$this->assertEquals('Gyt Continental', $list[2]['bank']);
		$this->assertEquals(500, $list[2]['total']);
		
		$this->assertEquals(5, $list[3]['id']);
		$this->assertEquals('09/06/2009', $list[3]['created_date']);
		$this->assertEquals('001-500', $list[3]['bank_account_number']);
		$this->assertEquals('432', $list[3]['number']);
		$this->assertEquals('Bi', $list[3]['bank']);
		$this->assertEquals(500, $list[3]['total']);
	}
	
	public function testGetList_2(){
		$list = PendingDepositListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('11/06/2009', $list[0]['created_date']);
		$this->assertEquals('001-500', $list[0]['bank_account_number']);
		$this->assertEquals('098', $list[0]['number']);
		$this->assertEquals('Bi', $list[0]['bank']);
		$this->assertEquals(300, $list[0]['total']);
		
		$this->assertEquals(7, $list[1]['id']);
		$this->assertEquals('15/06/2009', $list[1]['created_date']);
		$this->assertEquals('29-500', $list[1]['bank_account_number']);
		$this->assertEquals('3298', $list[1]['number']);
		$this->assertEquals('Gyt Continental', $list[1]['bank']);
		$this->assertEquals(100, $list[1]['total']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(PendingDepositListDAM::getList($pages, $items, 3)));
	}
}

class ManufacturerListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/manufacturer_list-seed.xml');
	}
	
	public function testGetList(){
		$list = ManufacturerListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('Abbot', $list[0]['name']);
		
		$this->assertEquals(5, $list[1]['id']);
		$this->assertEquals('Hersheys', $list[1]['name']);
		
		$this->assertEquals(2, $list[2]['id']);
		$this->assertEquals('Mattel', $list[2]['name']);
		
		$this->assertEquals(4, $list[3]['id']);
		$this->assertEquals('Nikkon', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = ManufacturerListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('Sony', $list[0]['name']);
		
		$this->assertEquals(3, $list[1]['id']);
		$this->assertEquals('Tyko', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(ManufacturerListDAM::getList($pages, $items, 3)));
	}
}

class BankAccountListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bank_account_list-seed.xml');
	}
	
	public function testGetList(){
		$list = BankAccountListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals('29-500', $list[0]['id']);
		$this->assertEquals('Drogueria Jose Gil', $list[0]['name']);
		
		$this->assertEquals('124-567', $list[1]['id']);
		$this->assertEquals('Jose', $list[1]['name']);
		
		$this->assertEquals('001-500', $list[2]['id']);
		$this->assertEquals('Noe Oliveros', $list[2]['name']);
		
		$this->assertEquals('001-600', $list[3]['id']);
		$this->assertEquals('Roberto Oliveros', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = BankAccountListDAM::getList($pages, $items, 2);
		
		$this->assertEquals('900-100', $list[0]['id']);
		$this->assertEquals('Samuel', $list[0]['name']);
		
		$this->assertEquals('2345-678', $list[1]['id']);
		$this->assertEquals('Vicks', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(BankAccountListDAM::getList($pages, $items, 3)));
	}
}

class CorrelativeListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/correlative_list-seed.xml');
	}
	
	public function testGetList(){
		$list = CorrelativeListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('A021', $list[0]['serial_number']);
		$this->assertEquals(0, $list[0]['status']);
		$this->assertEquals(1000, $list[0]['initial_number']);
		$this->assertEquals(9000, $list[0]['final_number']);
		
		$this->assertEquals(2, $list[1]['id']);
		$this->assertEquals('A099', $list[1]['serial_number']);
		$this->assertEquals(0, $list[1]['status']);
		$this->assertEquals(1000, $list[1]['initial_number']);
		$this->assertEquals(9000, $list[1]['final_number']);
		
		$this->assertEquals(4, $list[2]['id']);
		$this->assertEquals('B021', $list[2]['serial_number']);
		$this->assertEquals(0, $list[2]['status']);
		$this->assertEquals(1000, $list[2]['initial_number']);
		$this->assertEquals(9000, $list[2]['final_number']);
		
		$this->assertEquals(3, $list[3]['id']);
		$this->assertEquals('C021', $list[3]['serial_number']);
		$this->assertEquals(1, $list[3]['status']);
		$this->assertEquals(1000, $list[3]['initial_number']);
		$this->assertEquals(9000, $list[3]['final_number']);
	}
	
	public function testGetList_2(){
		$list = CorrelativeListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('N099', $list[0]['serial_number']);
		$this->assertEquals(0, $list[0]['status']);
		$this->assertEquals(1000, $list[0]['initial_number']);
		$this->assertEquals(9000, $list[0]['final_number']);
		
		$this->assertEquals(5, $list[1]['id']);
		$this->assertEquals('Z021', $list[1]['serial_number']);
		$this->assertEquals(0, $list[1]['status']);
		$this->assertEquals(1000, $list[1]['initial_number']);
		$this->assertEquals(9000, $list[1]['final_number']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(CorrelativeListDAM::getList($pages, $items, 3)));
	}
}

class UserAccountListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/user_account_list-seed.xml');
	}
	
	public function testGetList(){
		$list = UserAccountListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals('carmej', $list[0]['id']);
		$this->assertEquals('Carlos', $list[0]['first_name']);
		$this->assertEquals('Mejia', $list[0]['last_name']);
		
		$this->assertEquals('edglem', $list[1]['id']);
		$this->assertEquals('Edgar', $list[1]['first_name']);
		$this->assertEquals('Lemus', $list[1]['last_name']);
		
		$this->assertEquals('josoli', $list[2]['id']);
		$this->assertEquals('Jose', $list[2]['first_name']);
		$this->assertEquals('Oliveros', $list[2]['last_name']);
		
		$this->assertEquals('marper', $list[3]['id']);
		$this->assertEquals('Marvin', $list[3]['first_name']);
		$this->assertEquals('Perez', $list[3]['last_name']);
	}
	
	public function testGetList_2(){
		$list = UserAccountListDAM::getList($pages, $items, 2);
		
		$this->assertEquals('roboli', $list[0]['id']);
		$this->assertEquals('Roberto', $list[0]['first_name']);
		$this->assertEquals('Oliveros', $list[0]['last_name']);
		
		$this->assertEquals('vicoli', $list[1]['id']);
		$this->assertEquals('Vicks', $list[1]['first_name']);
		$this->assertEquals('Oliveros', $list[1]['last_name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(UserAccountListDAM::getList($pages, $items, 3)));
	}
}

class PaymentCardBrandListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_brand_list-seed.xml');
	}
	
	public function testGetList(){
		$list = PaymentCardBrandListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(3, $list[0]['id']);
		$this->assertEquals('American Express', $list[0]['name']);
		
		$this->assertEquals(4, $list[1]['id']);
		$this->assertEquals('Bi Club', $list[1]['name']);
		
		$this->assertEquals(5, $list[2]['id']);
		$this->assertEquals('Horizon', $list[2]['name']);
		
		$this->assertEquals(2, $list[3]['id']);
		$this->assertEquals('Master Card', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = PaymentCardBrandListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('Visa', $list[0]['name']);
		
		$this->assertEquals(6, $list[1]['id']);
		$this->assertEquals('Visa Electron', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(PaymentCardBrandListDAM::getList($pages, $items, 3)));
	}
}

class ProductListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_list-seed.xml');
	}
	
	public function testGetList(){
		$list = ProductListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(5, $list[0]['id']);
		$this->assertEquals('Aspirina', $list[0]['name']);
		
		$this->assertEquals(1, $list[1]['id']);
		$this->assertEquals('Barby', $list[1]['name']);
		
		$this->assertEquals(4, $list[2]['id']);
		$this->assertEquals('Chino Karate', $list[2]['name']);
		
		$this->assertEquals(2, $list[3]['id']);
		$this->assertEquals('PlayStation', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = ProductListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('Pringles', $list[0]['name']);
		
		$this->assertEquals(3, $list[1]['id']);
		$this->assertEquals('Transformer', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(ProductListDAM::getList($pages, $items, 3)));
	}
}

class SupplierListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier_list-seed.xml');
	}
	
	public function testGetList(){
		$list = SupplierListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(3, $list[0]['id']);
		$this->assertEquals('Amicelco', $list[0]['name']);
		
		$this->assertEquals(6, $list[1]['id']);
		$this->assertEquals('Cemaco', $list[1]['name']);
		
		$this->assertEquals(2, $list[2]['id']);
		$this->assertEquals('Central', $list[2]['name']);
		
		$this->assertEquals(4, $list[3]['id']);
		$this->assertEquals('Four Seasons', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = SupplierListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(5, $list[0]['id']);
		$this->assertEquals('Jutiapa', $list[0]['name']);
		
		$this->assertEquals(1, $list[1]['id']);
		$this->assertEquals('Xela', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(SupplierListDAM::getList($pages, $items, 3)));
	}
}

class RoleListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/role_list-seed.xml');
	}
	
	public function testGetList(){
		$list = RoleListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('Administrador', $list[0]['name']);
		
		$this->assertEquals(2, $list[1]['id']);
		$this->assertEquals('Operador', $list[1]['name']);
		
		$this->assertEquals(3, $list[2]['id']);
		$this->assertEquals('Auditor', $list[2]['name']);
		
		$this->assertEquals(4, $list[3]['id']);
		$this->assertEquals('Supervisor', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = RoleListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(5, $list[0]['id']);
		$this->assertEquals('Cajero', $list[0]['name']);
		
		$this->assertEquals(6, $list[1]['id']);
		$this->assertEquals('Contador', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(RoleListDAM::getList($pages, $items, 3)));
	}
}

class BranchListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/branch_list-seed.xml');
	}
	
	public function testGetList(){
		$list = BranchListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('Barberena', $list[0]['name']);
		
		$this->assertEquals(2, $list[1]['id']);
		$this->assertEquals('Central', $list[1]['name']);
		
		$this->assertEquals(3, $list[2]['id']);
		$this->assertEquals('Coatepeque', $list[2]['name']);
		
		$this->assertEquals(5, $list[3]['id']);
		$this->assertEquals('Jutiapa', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = BranchListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('Xela', $list[0]['name']);
		
		$this->assertEquals(4, $list[1]['id']);
		$this->assertEquals('Zona 18', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(BranchListDAM::getList($pages, $items, 3)));
	}
}

class PaymentCardTypeListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/payment_card_type_list-seed.xml');
	}
	
	public function testGetList(){
		$list = PaymentCardTypeListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(4, $list[0]['id']);
		$this->assertEquals('Cash Back', $list[0]['name']);
		
		$this->assertEquals(5, $list[1]['id']);
		$this->assertEquals('Chequera', $list[1]['name']);
		
		$this->assertEquals(1, $list[2]['id']);
		$this->assertEquals('Credito', $list[2]['name']);
		
		$this->assertEquals(2, $list[3]['id']);
		$this->assertEquals('Debito', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = PaymentCardTypeListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(6, $list[0]['id']);
		$this->assertEquals('Efectivon', $list[0]['name']);
		
		$this->assertEquals(3, $list[1]['id']);
		$this->assertEquals('Pre-pago', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(PaymentCardTypeListDAM::getList($pages, $items, 3)));
	}
}

class ShiftListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shift_list-seed.xml');
	}
	
	public function testGetList(){
		$list = ShiftListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(4, $list[0]['id']);
		$this->assertEquals('Almuerzo', $list[0]['name']);
		
		$this->assertEquals(1, $list[1]['id']);
		$this->assertEquals('Diurno', $list[1]['name']);
		
		$this->assertEquals(6, $list[2]['id']);
		$this->assertEquals('Feriado', $list[2]['name']);
		
		$this->assertEquals(3, $list[3]['id']);
		$this->assertEquals('Matutino', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = ShiftListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(2, $list[0]['id']);
		$this->assertEquals('Nocturno', $list[0]['name']);
		
		$this->assertEquals(5, $list[1]['id']);
		$this->assertEquals('Refa', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(ShiftListDAM::getList($pages, $items, 3)));
	}
}

class UnitOfMeasureListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/unit_of_measure_list-seed.xml');
	}
	
	public function testGetList(){
		$list = UnitOfMeasureListDAM::getList($pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals(2, $list[0]['id']);
		$this->assertEquals('Docena', $list[0]['name']);
		
		$this->assertEquals(4, $list[1]['id']);
		$this->assertEquals('Metro', $list[1]['name']);
		
		$this->assertEquals(6, $list[2]['id']);
		$this->assertEquals('Pie', $list[2]['name']);
		
		$this->assertEquals(5, $list[3]['id']);
		$this->assertEquals('Pulgada', $list[3]['name']);
	}
	
	public function testGetList_2(){
		$list = UnitOfMeasureListDAM::getList($pages, $items, 2);
		
		$this->assertEquals(1, $list[0]['id']);
		$this->assertEquals('Unidad', $list[0]['name']);
		
		$this->assertEquals(3, $list[1]['id']);
		$this->assertEquals('Yarda', $list[1]['name']);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(UnitOfMeasureListDAM::getList($pages, $items, 3)));
	}
}
?>