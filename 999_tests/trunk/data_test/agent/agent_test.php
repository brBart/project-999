<?php
require_once('business/agent.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('PHPUnit/Extensions/Database/DataSet/DataSetFilter.php');

class CustomerDAMTest extends PHPUnit_Extensions_Database_TestCase{
	
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/customer-seed.xml');
	}
	
	public function testInsert(){
		$customer = new Customer('1725045-5');
		$customer->setData('Infodes');
		CustomerDAM::insert($customer);
		
		$customer = new Customer('350682-7');
		$customer->setData('Drogueria Jose Gil');
		CustomerDAM::insert($customer);
		
		$xml_dataset = $this->createXMLDataSet('data_files/customer-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('customer')));
	}
	
	public function testGetInstance(){
		$customer = new Customer('1725045-5');
		$customer->setData('Infodes');
		CustomerDAM::insert($customer);
		
		$other_customer = CustomerDAM::getInstance('1725045-5');
		$this->assertEquals('Infodes', $other_customer->getName());
		$this->assertEquals('1725045-5', $other_customer->getNit());
		$this->assertEquals(Persist::CREATED, $other_customer->getStatus());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(CustomerDAM::getInstance('1725045-5'));
	}
	
	public function testExists(){
		$customer = new Customer('1725045-5');
		$customer->setData('Infodes');
		CustomerDAM::insert($customer);
		
		$this->assertTrue(CustomerDAM::exist('1725045-5'));
	}
	
	public function testExists_NonExistent(){
		$this->assertFalse(CustomerDAM::exist('1725045-5'));
	}
	
	public function testUpdate(){
		$customer = new Customer('1725045-5');
		$customer->setData('Infodes');
		CustomerDAM::insert($customer);
		
		$customer->setName('Roberto Oliveros');
		CustomerDAM::update($customer);
		
		$xml_dataset = $this->createXMLDataSet('data_files/customer-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('customer')));
	}
}

class SupplierDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier-seed.xml');
	}
	
	public function testInsert(){
		$supplier = new Supplier();
		$supplier->setData('12345-6', 'Distelsa', '2412-9999', 'zona 10.', 'distelsa@hotmail.com', 'ruben');
		$id = SupplierDAM::insert($supplier);
		$this->assertGreaterThan(0, $id);
		
		$supplier = new Supplier();
		$supplier->setData('23456-7', 'Max', '2412-0000', 'zona 11.');
		$id = SupplierDAM::insert($supplier);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/supplier-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('supplier')),
				array('supplier' => array('supplier_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$supplier = new Supplier();
		$supplier->setData('12345-6', 'Distelsa', '2412-9999', 'zona 10.', 'distelsa@hotmail.com', 'ruben');
		$id = SupplierDAM::insert($supplier);
		
		$other_supplier = SupplierDAM::getInstance($id);
		$this->assertEquals($id, $other_supplier->getId());
		$this->assertEquals(Persist::CREATED, $other_supplier->getStatus());
		$this->assertEquals('12345-6', $other_supplier->getNit());
		$this->assertEquals('Distelsa', $other_supplier->getName());
		$this->assertEquals('2412-9999', $other_supplier->getTelephone());
		$this->assertEquals('zona 10.', $other_supplier->getAddress());
		$this->assertEquals('distelsa@hotmail.com', $other_supplier->getEmail());
		$this->assertEquals('ruben', $other_supplier->getContact());
	}
	
	public function testGetInstance_2(){
		$supplier = new Supplier();
		$supplier->setData('23456-7', 'Max', '2412-0000', 'zona 11.', '', '');
		$id = SupplierDAM::insert($supplier);
		
		$other_supplier = SupplierDAM::getInstance($id);
		$this->assertEquals($id, $other_supplier->getId());
		$this->assertEquals(Persist::CREATED, $other_supplier->getStatus());
		$this->assertEquals('23456-7', $other_supplier->getNit());
		$this->assertEquals('Max', $other_supplier->getName());
		$this->assertEquals('2412-0000', $other_supplier->getTelephone());
		$this->assertEquals('zona 11.', $other_supplier->getAddress());
		$this->assertEquals('', $other_supplier->getEmail());
		$this->assertEquals('', $other_supplier->getContact());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(SupplierDAM::getInstance(9999999));
	}
	
	public function testUpdate(){
		$supplier = new Supplier();
		$supplier->setData('23456-7', 'Max', '2412-0000', 'zona 11.');
		$id = SupplierDAM::insert($supplier);
		
		$other_supplier = SupplierDAM::getInstance($id);
		$other_supplier->setNit('350682-7');
		$other_supplier->setName('Jose Gil');
		$other_supplier->setTelephone('1740');
		$other_supplier->setAddress('zona 1.');
		$other_supplier->setEmail('jose@jose.net');
		$other_supplier->setContact('roberto');
		SupplierDAM::update($other_supplier);
		
		$xml_dataset = $this->createXMLDataSet('data_files/supplier-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('supplier')),
				array('supplier' => array('supplier_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$supplier = new Supplier();
		$supplier->setData('23456-7', 'Max', '2412-0000', 'zona 11.');
		$id = SupplierDAM::insert($supplier);
		
		$other_supplier = SupplierDAM::getInstance($id);
		$this->assertTrue(SupplierDAM::delete($other_supplier));
		$xml_dataset = $this->createXMLDataSet('data_files/supplier-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('supplier')));
	}
}

class SupplierDeleteProductSupplierDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier-product_supplier-dependency.xml');
	}
	
	public function testDelete(){
		$other_supplier = SupplierDAM::getInstance(1);
		$this->assertFalse(SupplierDAM::delete($other_supplier));
	}
}

class SupplierDeleteReceiptDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier-receipt-dependency.xml');
	}
	
	public function testDelete(){
		$other_supplier = SupplierDAM::getInstance(1);
		$this->assertFalse(SupplierDAM::delete($other_supplier));
	}
}

class SupplierDeletePurchaseReturnDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier-purchase_return-dependency.xml');
	}
	
	public function testDelete(){
		$other_supplier = SupplierDAM::getInstance(1);
		$this->assertFalse(SupplierDAM::delete($other_supplier));
	}
}

class BranchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/branch-seed.xml');
	}
	
	public function testInsert(){
		$branch = new Branch();
		$branch->setData('12345-6', 'Xela', '2412-9999', 'zona 10.', 'xela@hotmail.com', 'ruben');
		$id = BranchDAM::insert($branch);
		$this->assertGreaterThan(0, $id);
		
		$branch = new Branch();
		$branch->setData('23456-7', 'Jutiapa', '2412-0000', 'zona 11.');
		$id = BranchDAM::insert($branch);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/branch-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('branch')),
				array('branch' => array('branch_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}	

	
	public function testGetInstance(){
		$branch = new Branch();
		$branch->setData('12345-6', 'Xela', '2412-9999', 'zona 10.', 'xela@hotmail.com', 'ruben');
		$id = BranchDAM::insert($branch);
		
		$other_branch = BranchDAM::getInstance($id);
		$this->assertEquals($id, $other_branch->getId());
		$this->assertEquals(Persist::CREATED, $other_branch->getStatus());
		$this->assertEquals('12345-6', $other_branch->getNit());
		$this->assertEquals('Xela', $other_branch->getName());
		$this->assertEquals('2412-9999', $other_branch->getTelephone());
		$this->assertEquals('zona 10.', $other_branch->getAddress());
		$this->assertEquals('xela@hotmail.com', $other_branch->getEmail());
		$this->assertEquals('ruben', $other_branch->getContact());
	}
	
	public function testGetInstance_2(){
		$branch = new Branch();
		$branch->setData('23456-7', 'Jutiapa', '2412-0000', 'zona 11.');
		$id = BranchDAM::insert($branch);
		
		$other_branch = BranchDAM::getInstance($id);
		$this->assertEquals($id, $other_branch->getId());
		$this->assertEquals(Persist::CREATED, $other_branch->getStatus());
		$this->assertEquals('23456-7', $other_branch->getNit());
		$this->assertEquals('Jutiapa', $other_branch->getName());
		$this->assertEquals('2412-0000', $other_branch->getTelephone());
		$this->assertEquals('zona 11.', $other_branch->getAddress());
		$this->assertNull($other_branch->getEmail());
		$this->assertNull($other_branch->getContact());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(BranchDAM::getInstance(9999999));
	}
	
	public function testUpdate(){
		$branch = new Branch();
		$branch->setData('23456-7', 'Max', '2412-0000', 'zona 11.');
		$id = BranchDAM::insert($branch);
		
		$other_branch = BranchDAM::getInstance($id);
		$other_branch->setNit('350682-7');
		$other_branch->setName('Jose Gil');
		$other_branch->setTelephone('1740');
		$other_branch->setAddress('zona 1.');
		$other_branch->setEmail('jose@jose.net');
		$other_branch->setContact('roberto');
		BranchDAM::update($other_branch);
		
		$xml_dataset = $this->createXMLDataSet('data_files/branch-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('branch')),
				array('branch' => array('branch_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$branch = new Branch();
		$branch->setData('23456-7', 'Xela', '2412-0000', 'zona 11.');
		$id = BranchDAM::insert($branch);
		
		$other_branch = BranchDAM::getInstance($id);
		$this->assertTrue(BranchDAM::delete($other_branch));
		$xml_dataset = $this->createXMLDataSet('data_files/branch-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('branch')));
	}
}

class BranchDeleteShipmentDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/branch-shipment-dependency.xml');
	}
	
	public function testDelete(){
		$other_supplier = BranchDAM::getInstance(1);
		$this->assertFalse(BranchDAM::delete($other_supplier));
	}
}
?>