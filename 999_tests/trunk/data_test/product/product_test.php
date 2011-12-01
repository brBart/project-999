<?php
require_once('config/config.php');

require_once('business/product.php');
require_once('business/agent.php');
require_once('business/user_account.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('PHPUnit/Extensions/Database/DataSet/DataSetFilter.php');

class ManufacturerDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/manufacturer-seed.xml');
	}
	
	public function testInsert(){
		$manufacturer = new Manufacturer();
		$manufacturer->setData('Abbot');
		$id = ManufacturerDAM::insert($manufacturer);
		$this->assertGreaterThan(0, $id);
		
		$manufacturer = new Manufacturer();
		$manufacturer->setData('Bayer');
		$id = ManufacturerDAM::insert($manufacturer);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/manufacturer-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('manufacturer')),
				array('manufacturer' => array('manufacturer_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$manufacturer = new Manufacturer();
		$manufacturer->setData('Mattel');
		$id = ManufacturerDAM::insert($manufacturer);
		
		$other_manufacturer = ManufacturerDAM::getInstance($id);
		$this->assertEquals($id, $other_manufacturer->getId());
		$this->assertEquals(Persist::CREATED, $other_manufacturer->getStatus());
		$this->assertEquals('Mattel', $other_manufacturer->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ManufacturerDAM::getInstance(999999999));
	}
	
	public function testUpdate(){
		$manufacturer = new Manufacturer();
		$manufacturer->setData('Mattel');
		$id = ManufacturerDAM::insert($manufacturer);
		
		$other_manufacturer = ManufacturerDAM::getInstance($id);
		$other_manufacturer->setName('Tyko');
		ManufacturerDAM::update($other_manufacturer);
		
		$xml_dataset = $this->createXMLDataSet('data_files/manufacturer-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('manufacturer')),
				array('manufacturer' => array('manufacturer_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$manufacturer = new Manufacturer();
		$manufacturer->setData('Mattel');
		$id = ManufacturerDAM::insert($manufacturer);
		
		$other_manufacturer = ManufacturerDAM::getInstance($id);
		$this->assertTrue(ManufacturerDAM::delete($other_manufacturer));
		$xml_dataset = $this->createXMLDataSet('data_files/manufacturer-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('manufacturer')));
	}
}

class ManufacturerDeleteProductDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/manufacturer-product-dependency.xml');
	}
	
	public function testDelete(){
		$other_manufacturer = ManufacturerDAM::getInstance(1);
		$this->assertFalse(ManufacturerDAM::delete($other_manufacturer));
	}
}

class UnitOfMeasureDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/unit_of_measure-seed.xml');
	}
	
	public function testInsert(){
		$unit_of_measure = new UnitOfMeasure();
		$unit_of_measure->setData('Unidad');
		$id = UnitOfMeasureDAM::insert($unit_of_measure);
		$this->assertGreaterThan(0, $id);
		
		$unit_of_measure = new UnitOfMeasure();
		$unit_of_measure->setData('Docena');
		$id = UnitOfMeasureDAM::insert($unit_of_measure);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/unit_of_measure-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('unit_of_measure')),
				array('unit_of_measure' => array('unit_of_measure_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$unit_of_measure = new UnitOfMeasure();
		$unit_of_measure->setData('Docena');
		$id = UnitOfMeasureDAM::insert($unit_of_measure);
		
		$other_unit_of_measure = UnitOfMeasureDAM::getInstance($id);
		$this->assertEquals($id, $other_unit_of_measure->getId());
		$this->assertEquals(Persist::CREATED, $other_unit_of_measure->getStatus());
		$this->assertEquals('Docena', $other_unit_of_measure->getName());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(UnitOfMeasureDAM::getInstance(999999999));
	}
	
	public function testUpdate(){
		$unit_of_measure = new UnitOfMeasure();
		$unit_of_measure->setData('Unidad');
		$id = UnitOfMeasureDAM::insert($unit_of_measure);
		
		$other_unit_of_measure = UnitOfMeasureDAM::getInstance($id);
		$other_unit_of_measure->setName('Yarda');
		UnitOfMeasureDAM::update($other_unit_of_measure);
		
		$xml_dataset = $this->createXMLDataSet('data_files/unit_of_measure-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('unit_of_measure')),
				array('unit_of_measure' => array('unit_of_measure_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$unit_of_measure = new UnitOfMeasure();
		$unit_of_measure->setData('Yarda');
		$id = UnitOfMeasureDAM::insert($unit_of_measure);
		
		$other_unit_of_measure = UnitOfMeasureDAM::getInstance($id);
		$this->assertTrue(UnitOfMeasureDAM::delete($other_unit_of_measure));
		$xml_dataset = $this->createXMLDataSet('data_files/unit_of_measure-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('unit_of_measure')));
	}
}

class UnitOfMeasureDeleteProductDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/unit_of_measure-product-dependency.xml');
	}
	
	public function testDelete(){
		$other_unit_of_measure = UnitOfMeasureDAM::getInstance(1);
		$this->assertFalse(UnitOfMeasureDAM::delete($other_unit_of_measure));
	}
}

class ProductSupplierDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_supplier-seed.xml');
	}
	
	public function testInsert(){
		$product = new Product(1);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'BAY-001');
		ProductSupplierDAM::insert($product, $detail);
		
		$product = new Product(2);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010');
		ProductSupplierDAM::insert($product, $detail);
		
		$xml_dataset = $this->createXMLDataSet('data_files/product_supplier-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('product_supplier')));
	}
	
	public function testDelete(){
		$product = new Product(1);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'BAY-001');
		ProductSupplierDAM::insert($product, $detail);
		
		ProductSupplierDAM::delete($product, $detail);
		$xml_dataset = $this->createXMLDataSet('data_files/product_supplier-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('product_supplier')));
	}
}

class ProductDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-seed.xml');
	}
	
	public function testInsert(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('54321', 'Barby', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', 'Muneca para nina.');
		$this->assertGreaterThan(0, ProductDAM::insert($product));
		
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setName('Pringles');
		$product->setUnitOfMeasure($um);
		$product->setManufacturer($manufacturer);
		$product->deactivate(true);
		$product->setPrice(15.50);
		$this->assertGreaterThan(0, ProductDAM::insert($product));
		
		$xml_dataset = $this->createXMLDataSet('data_files/product-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('product')),
				array('product' => array('product_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('54321', 'Barby', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', 'Muñeca para niña.');
		$id = ProductDAM::insert($product);
		$temp_product = new Product($id);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010', Persist::CREATED);
		ProductSupplierDAM::insert($temp_product, $detail);
		
		$other_product = ProductDAM::getInstance($id);
		$this->assertEquals($id, $other_product->getId());
		$this->assertEquals(Persist::CREATED, $other_product->getStatus());
		$this->assertEquals('Barby', $other_product->getName());
		$this->assertEquals('Muñeca para niña.', $other_product->getDescription());
		$this->assertEquals($um, $other_product->getUnitOfMeasure());
		$this->assertEquals($manufacturer, $other_product->getManufacturer());
		$this->assertEquals(82.34, $other_product->getPrice());
		$this->assertFalse($other_product->isDeactivated());
		$this->assertEquals($detail, $other_product->getProductSupplier($id . 'ABB-010'));
		$this->assertEquals('54321', $other_product->getBarCode());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ProductDAM::getInstance(9932));
	}
	
	public function testUpdate(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('Barby', 'Muñeca para niña.', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', '54321');
		$id = ProductDAM::insert($product);
		$temp_product = new Product($id);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010', Persist::CREATED);
		ProductSupplierDAM::insert($temp_product, $detail);
		
		$um = UnitOfMeasureDAM::getInstance(2);
		$manufacturer = ManufacturerDAM::getInstance(2);
		$other_product = ProductDAM::getInstance($id);
		$other_product->setData('65432', 'Transformer', $um, $manufacturer, 102.38, true,
				$details, 'Robot para chico.');
		ProductDAM::update($other_product);
		
		$xml_dataset = $this->createXMLDataSet('data_files/product-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('product')),
				array('product' => array('product_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
		
		$other_product->setBarCode('');
		ProductDAM::update($other_product);
		
		$xml_dataset = $this->createXMLDataSet('data_files/product-after-update-2.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('product')),
				array('product' => array('product_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testExistsBarCode(){
		$product = new Product();
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product->setData('54321', 'Barby', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', 'Muñeca para niña.');
		$id = ProductDAM::insert($product);
		$this->assertTrue(ProductDAM::existsBarCode($product, '54321'));
	}
	
	public function testExistsBarCode_NonExistent(){
		$product = new Product(2);
		$this->assertFalse(ProductDAM::existsBarCode($product, '1'));
		
		$product = new Product();
		$this->assertFalse(ProductDAM::existsBarCode($product, '1'));
	}
	
	public function testExistsProductSupplier(){
		$product = new Product(2);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010');
		ProductSupplierDAM::insert($product, $detail);
		$this->assertTrue(ProductDAM::existsProductSupplier($detail));
	}
	
	public function testExistsProductSupplier_NonExistent(){
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010');
		$this->assertFalse(ProductDAM::existsProductSupplier($detail));
	}
	
	public function testSetBarCode(){
		$product = new Product();
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product->setName('Pringles');
		$product->setDescription('Papalinas.');
		$product->setUnitOfMeasure($um);
		$product->setManufacturer($manufacturer);
		$product->deactivate(true);
		$product->setPrice(15.50);
		$id = ProductDAM::insert($product);
		
		$product = new Product($id);
		ProductDAM::setBarCode($product, $id);
		
		$this->assertFalse(ProductDAM::existsBarCode($product, $id));
	}
	
	public function testGetIdByBarCode(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('54321', 'Barby', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', 'Muñeca para niña.');
		$id = ProductDAM::insert($product);
		
		$this->assertEquals($id, ProductDAM::getIdByBarCode('54321', false));

		$product2 = new Product();
		$product2->setData('443211', 'Barby', $um, $manufacturer, 82.34, true,
				$details[] = 'uno', 'Muñeca para niña.');
		$id = ProductDAM::insert($product2);
		
		$this->assertEquals(0, ProductDAM::getIdByBarCode('443211', false));
	}
	
	public function testGetIdByBarCode_IncludeDeactivated(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('54321', 'Barby', $um, $manufacturer, 82.34, true,
				$details[] = 'uno', 'Muñeca para niña.');
		$id = ProductDAM::insert($product);
		
		$this->assertEquals($id, ProductDAM::getIdByBarCode('54321', true));
	}
	
	public function testGetIdByBarCode_NonExistent(){
		$this->assertEquals(0, ProductDAM::getIdByBarCode('54321', false));
	}
	
	public function testGetIdBySupplier(){
		$um = UnitOfMeasureDAM::getInstance(1);
		$manufacturer = ManufacturerDAM::getInstance(1);
		$product = new Product();
		$product->setData('Barby', 'Muñeca para niña.', $um, $manufacturer, 82.34, false,
				$details[] = 'uno', '54321');
		$id = ProductDAM::insert($product);
		$temp_product = new Product($id);
		$supplier = SupplierDAM::getInstance(1);
		$detail = new ProductSupplier($supplier, 'ABB-010');
		ProductSupplierDAM::insert($temp_product, $detail);
		
		$this->assertEquals($id, ProductDAM::getIdBySupplier($supplier, 'ABB-010'));
	}
	
	public function testGetIdBySupplier_NonExistent(){
		$supplier = SupplierDAM::getInstance(1);
		$this->assertEquals(0, ProductDAM::getIdBySupplier($supplier, 'ABB-010'));
	}
}

class ProductDeleteTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-delete-seed.xml');
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$this->assertTrue(ProductDAM::delete($product));
		
		$xml_dataset = $this->createXMLDataSet('data_files/product-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('product',
				'product_supplier', 'change_price_log')));
	}
}

class ProductDeleteLotDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-lot-dependency.xml');
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$this->assertFalse(ProductDAM::delete($product));
	}
}

class ProductDeleteBonusDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-bonus-dependency.xml');
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$this->assertFalse(ProductDAM::delete($product));
	}
}

class ProductDeleteComparisonProductDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-comparison_product-dependency.xml');
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$this->assertFalse(ProductDAM::delete($product));
	}
}

class ProductDeleteCountProductDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product-count_product-dependency.xml');
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$this->assertFalse(ProductDAM::delete($product));
	}
}

class LotDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/lot-seed.xml');
	}
	
	public function testInsert(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$this->assertGreaterThan(0, $id);
		
		$lot = new Lot($product, 10, 31.50, '', '17/06/2009');
		$id = LotDAM::insert($lot);
		$this->assertGreaterThan(0, $id);
		
		$lot = new Lot($product, 15, 11.50, NULL, '15/06/2009');
		$id = LotDAM::insert($lot);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/lot-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('lot')),
				array('lot' => array('lot_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		
		$other_lot = LotDAM::getInstance($id);
		$this->assertEquals($id, $other_lot->getId());
		$this->assertEquals(Persist::CREATED, $other_lot->getStatus());
		$this->assertEquals($product, $other_lot->getProduct());
		$this->assertEquals(12.50, $other_lot->getPrice());
		$this->assertEquals('16/06/2009', $other_lot->getEntryDate());
		$this->assertEquals('10/10/2009', $other_lot->getExpirationDate());
		
		$lot = new Lot($product, 10, 31.50, '', '17/06/2009');
		$id = LotDAM::insert($lot);

		$other_lot = LotDAM::getInstance($id);
		$this->assertEquals($id, $other_lot->getId());
		$this->assertEquals(Persist::CREATED, $other_lot->getStatus());
		$this->assertEquals($product, $other_lot->getProduct());
		$this->assertEquals(31.50, $other_lot->getPrice());
		$this->assertEquals('17/06/2009', $other_lot->getEntryDate());
		$this->assertNull($other_lot->getExpirationDate());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(LotDAM::getInstance(999));
	}
	
	public function testDeactivate(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 10, 31.50, '', '17/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::deactivate($other_lot);
		$this->assertEquals(0, LotDAM::getQuantity($other_lot));
		$this->assertEquals(0, LotDAM::getAvailable($other_lot));
	}
	
	public function testSetPrice(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 10, 31.50, '', '17/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::setPrice($other_lot, 55.55);
		$other_lot = LotDAM::getInstance($id);
		$this->assertEquals(55.55, $other_lot->getPrice());
	}
	
	public function testSetExpirationDate(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 10, 31.50, '', '17/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::setExpirationDate($other_lot, '01/01/2010');
		$other_lot = LotDAM::getInstance($id);
		$this->assertEquals('01/01/2010', $other_lot->getExpirationDate());
	}
	
	public function testSetExpirationDate_Null(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::setExpirationDate($other_lot, NULL);
		$other_lot = LotDAM::getInstance($id);
		$this->assertNull($other_lot->getExpirationDate());
	}
	
	public function testSetExpirationDate_Blank(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::setExpirationDate($other_lot, '');
		$other_lot = LotDAM::getInstance($id);
		$this->assertNull($other_lot->getExpirationDate());
	}
	
	public function testIncrease(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::increase($other_lot, 7);
		$this->assertEquals(12, LotDAM::getQuantity($other_lot));
		$this->assertEquals(12, LotDAM::getAvailable($other_lot));
	}
	
	public function testDecrease(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::decrease($other_lot, 2);
		$this->assertEquals(3, LotDAM::getQuantity($other_lot));
		$this->assertEquals(3, LotDAM::getAvailable($other_lot));
	}
	
	public function testReserve(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::reserve($other_lot, 2);
		$this->assertEquals(5, LotDAM::getQuantity($other_lot));
		$this->assertEquals(3, LotDAM::getAvailable($other_lot));
	}
	
	public function testDecreaseReserve(){
		$product = Product::getInstance(1);
		$lot = new Lot($product, 5, 12.50, '10/10/2009', '16/06/2009');
		$id = LotDAM::insert($lot);
		$other_lot = LotDAM::getInstance($id);
		
		LotDAM::reserve($other_lot, 2);
		LotDAM::decreaseReserve($other_lot, 2);
		$this->assertEquals(5, LotDAM::getQuantity($other_lot));
		$this->assertEquals(5, LotDAM::getAvailable($other_lot));
	}
}

class BonusDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bonus-seed.xml');
	}
	
	public function testInsert(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, '15/07/2009', '17/06/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		$this->assertGreaterThan(0, $id);
		
		$bonus = new Bonus($product, 12, 15.00, '01/10/2009', '18/06/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/bonus-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('bonus')),
				array('bonus' => array('bonus_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, '15/07/2009', '17/06/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$other_bonus = BonusDAM::getInstance($id);
		$this->assertEquals($id, $other_bonus->getId());
		$this->assertEquals(Persist::CREATED, $other_bonus->getStatus());
		$this->assertEquals($product, $other_bonus->getProduct());
		$this->assertEquals(3, $other_bonus->getQuantity());
		$this->assertEquals(5.00, $other_bonus->getPercentage());
		$this->assertEquals('15/07/2009', $other_bonus->getExpirationDate());
		$this->assertEquals('17/06/2009', $other_bonus->getCreatedDate());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(BonusDAM::getInstance(999));
	}
	
	public function testExists(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, '15/07/2012', '17/06/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$this->assertTrue(BonusDAM::exists($product, 3));
	}
	
	public function testExists_Expired(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, date('d/m/Y'), '17/04/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$this->assertFalse(BonusDAM::exists($product, 3));
	}
	
	public function testExists_NonExistent(){
		$product = ProductDAM::getInstance(1);
		$this->assertFalse(BonusDAM::exists($product, 100));
	}
	
	public function testGetId(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, '15/07/2012', '17/06/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$this->assertEquals($id, BonusDAM::getId($product, 3));
	}
	
	public function testGetId_Expired(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, date('d/m/Y'), '17/04/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$this->assertEquals(0, BonusDAM::getId($product, 3));
	}
	
	public function testGetId_NonExistent(){
		$product = ProductDAM::getInstance(1);
		$this->assertEquals(0, BonusDAM::getId($product, 100));
	}
	
	public function testDelete(){
		$product = ProductDAM::getInstance(1);
		$bonus = new Bonus($product, 3, 5.00, date('d/m/Y'), '17/04/2009', UserAccountDAM::getInstance('roboli'));
		$id = BonusDAM::insert($bonus);
		
		$other_bonus = BonusDAM::getInstance($id);
		$this->assertTrue(BonusDAM::delete($other_bonus));
		$xml_dataset = $this->createXMLDataSet('data_files/bonus-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('bonus')));
	}
}

class BonusDeleteInvoiceBonusDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bonus-invoice_bonus-dependency.xml');
	}
	
	public function testDelete(){
		$bonus = BonusDAM::getInstance(1);
		$this->assertFalse(BonusDAM::delete($bonus));
	}
}

class ChangePriceLogDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/change_price_log-seed.xml');
	}
	
	public function testInsert(){
		$user = UserAccountDAM::getInstance('roboli');
		$product = ProductDAM::getInstance(1);
		ChangePriceLogDAM::insert('22/06/2009 00:00:00', $user, $product, 12.12, 23.23);
		
		ChangePriceLogDAM::insert('23/06/2009 00:00:01', $user, $product, 1.00, 2.00);
		
		$xml_dataset = $this->createXMLDataSet('data_files/change_price_log-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('change_price_log')),
				array('change_price_log' => array('entry_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class ProductSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_search-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('bar_code' => '984398349', 'name' => 'Barbosa', 'manufacturer' => 'Abbot'),
				array('bar_code' => '54321', 'name' => 'Barby', 'manufacturer' => 'Abbot'),
				array('bar_code' => '4039493838', 'name' => 'Barcedenz', 'manufacturer' => 'Abbot'),
				array('bar_code' => '428928', 'name' => 'Barcelona', 'manufacturer' => 'Abbot'),
				array('bar_code' => '9348029850', 'name' => 'Barrichelo', 'manufacturer' => 'Bayer'));
				
		$data_list = ProductSearchDAM::getList('bar', false);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$this->assertEquals(0, count(ProductSearchDAM::getList('robs', false)));
	}
	
	public function testGetList_IncludeDeactivated(){
		$list = array(array('bar_code' => '984398349', 'name' => 'Barbosa', 'manufacturer' => 'Abbot'),
				array('bar_code' => '54321', 'name' => 'Barby', 'manufacturer' => 'Abbot'),
				array('bar_code' => '4039493838', 'name' => 'Barcedenz', 'manufacturer' => 'Abbot'),
				array('bar_code' => '428928', 'name' => 'Barcelona', 'manufacturer' => 'Abbot'),
				array('bar_code' => '9348029850', 'name' => 'Barrichelo', 'manufacturer' => 'Bayer'),
				array('bar_code' => '89292988', 'name' => 'Barroco', 'manufacturer' => 'Abbot'));
				
		$data_list = ProductSearchDAM::getList('bar', true);
		$this->assertEquals($list, $data_list);
	}
}

class ManufacturerProductListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/manufacturer_products-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('id' => '5', 'name' => 'Barbosa'),
				array('id' => '1', 'name' => 'Barby'),
				array('id' => '3', 'name' => 'Chino Karate'),
				array('id' => '4', 'name' => 'Juan Talaber'),
				array('id' => '10', 'name' => 'Reloj pulser'),
				array('id' => '9', 'name' => 'Silla'),
				array('id' => '7', 'name' => 'Telefono porattil'));
		
		$manufacturer = ManufacturerDAM::getInstance(1);
		$data_list = ManufacturerProductListDAM::getList($manufacturer);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_1(){
		$list = array(array('id' => '2', 'name' => 'Carrito Veloz'),
				array('id' => '8', 'name' => 'Monitor'),
				array('id' => '6', 'name' => 'Zesa redonda.'));
				
		$manufacturer = ManufacturerDAM::getInstance(2);
		$data_list = ManufacturerProductListDAM::getList($manufacturer);
		$this->assertEquals($list, $data_list);
	}
}

class InventoryDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/inventory-seed.xml');
	}
	
	public function testGetAvailable(){
		$product = ProductDAM::getInstance(1);
		$this->assertEquals(63, InventoryDAM::getAvailable($product));
	}
	
	public function testGetQuantity(){
		$product = ProductDAM::getInstance(1);
		$this->assertEquals(73, InventoryDAM::getQuantity($product));
	}
	
	public function testIncrease(){
		$product = ProductDAM::getInstance(1);
		InventoryDAM::increase($product, 5);		
		$this->assertEquals(78, InventoryDAM::getQuantity($product));
		$this->assertEquals(68, InventoryDAM::getAvailable($product));
	}
	
	public function testDecrease(){
		$product = ProductDAM::getInstance(1);
		InventoryDAM::decrease($product, 3);		
		$this->assertEquals(70, InventoryDAM::getQuantity($product));
		$this->assertEquals(60, InventoryDAM::getAvailable($product));
	}
	
	public function testReserve(){
		$product = ProductDAM::getInstance(1);
		InventoryDAM::reserve($product, 7);		
		$this->assertEquals(73, InventoryDAM::getQuantity($product));
		$this->assertEquals(56, InventoryDAM::getAvailable($product));
	}
	
	public function testDecreaseReserve(){
		$product = ProductDAM::getInstance(1);
		InventoryDAM::decreaseReserve($product, 5);		
		$this->assertEquals(73, InventoryDAM::getQuantity($product));
		$this->assertEquals(68, InventoryDAM::getAvailable($product));
	}
}

class InventoryDAMGetLotsTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/inventory_get_lots-seed.xml');
	}
	
	public function testGetLots(){
		$product = new Product(1, Persist::CREATED);
		
		$list[] = new Lot($product, 0, 11.50, '01/12/2009','25/06/2009', 5, Persist::CREATED);
		$list[] = new Lot($product, 0, 31.50, '01/01/2010','17/06/2009', 2, Persist::CREATED);
		$list[] = new Lot($product, 0, 31.50, NULL,'20/06/2009', 7, Persist::CREATED);
		
		$lots = InventoryDAM::getLots($product);
		$this->assertEquals($list, $lots);
	}
	
	public function testGetLots_2(){
		$product = new Product(2, Persist::CREATED);
		$this->assertEquals(0, count(InventoryDAM::getLots($product)));
	}
	
	public function testGetLotsList(){
		$product = new Product(1, Persist::CREATED);
		
		$list = array(array('id' => '2', 'entry_date' => '17/06/2009', 'expiration_date' => '01/01/2010',
				'price' => '31.50', 'quantity' => '10', 'available' => '5'),
				array('id' => '5', 'entry_date' => '25/06/2009', 'expiration_date' => '01/12/2009',
				'price' => '11.50', 'quantity' => '15', 'available' => '15'),
				array('id' => '7', 'entry_date' => '20/06/2009', 'expiration_date' => '',
				'price' => '31.50', 'quantity' => '10', 'available' => '10'));
				
		$lots = InventoryDAM::getLotsList($product, $quantity, $available);
		$this->assertEquals($list, $lots);
		$this->assertEquals(35, $quantity);
		$this->assertEquals(30, $available);
	}
}

class KardexDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/kardex-seed.xml');
	}
	
	public function testGetList(){
		$product = new Product(1);
		
		$list = array(array('created_date' => '15/06/2009 07:09:15', 'document' => 'Recibo', 'number' => '1',
				'lot_id' => '3', 'entry' => '10', 'withdraw' => '', 'balance' => '40'),
				array('created_date' => '15/06/2009 07:09:15', 'document' => 'Recibo', 'number' => '1',
				'lot_id' => '4', 'entry' => '10', 'withdraw' => '', 'balance' => '50'),
				array('created_date' => '15/06/2009 07:09:15', 'document' => 'Recibo', 'number' => '1',
				'lot_id' => '1', 'entry' => '30', 'withdraw' => '', 'balance' => '80'),
				array('created_date' => '15/06/2009 07:09:15', 'document' => 'Recibo', 'number' => '1',
				'lot_id' => '2', 'entry' => '5', 'withdraw' => '', 'balance' => '85'));
		
		$data_list = KardexDAM::getList($product, $balance_forward, $pages, $items, 1);
		$this->assertEquals(30, $balance_forward);
		$this->assertEquals(8, $pages);
		$this->assertEquals(30, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$product = new Product(1);
		
		$list = array(array('created_date' => '15/06/2009 09:09:17', 'document' => 'Envio', 'number' => '1',
				'lot_id' => '1', 'entry' => '', 'withdraw' => '30', 'balance' => '36'),
				array('created_date' => '15/06/2009 09:09:17', 'document' => 'Envio', 'number' => '1',
				'lot_id' => '2', 'entry' => '', 'withdraw' => '1', 'balance' => '35'),
				array('created_date' => '15/06/2009 09:09:19', 'document' => 'Vale Salida', 'number' => '1',
				'lot_id' => '3', 'entry' => '', 'withdraw' => '5', 'balance' => '30'),
				array('created_date' => '15/06/2009 09:09:19', 'document' => 'Vale Salida', 'number' => '1',
				'lot_id' => '4', 'entry' => '', 'withdraw' => '5', 'balance' => '25'));
		
		$data_list = KardexDAM::getList($product, $balance_forward, $pages, $items, 5);
		$this->assertEquals(66, $balance_forward);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$product = new Product(1);
		
		$list = array(array('created_date' => '16/06/2009 08:08:20', 'document' => 'Vale Salida', 'number' => '2',
				'lot_id' => '1', 'entry' => '', 'withdraw' => '3', 'balance' => '31'),
				array('created_date' => '18/06/2009 08:08:13', 'document' => 'Factura', 'number' => 'A021-12346',
				'lot_id' => '1', 'entry' => '', 'withdraw' => '5', 'balance' => '26'));
		
		$data_list = KardexDAM::getList($product, $balance_forward, $pages, $items, 8);
		$this->assertEquals(34, $balance_forward);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_4(){
		$product = new Product(1);
		
		$list = array(array('created_date' => '15/06/2009 07:09:15', 'document' => 'Recibo', 'number' => '1',
				'lot_id' => '3', 'entry' => '10', 'withdraw' => '', 'balance' => '40'),
				array('created_date' => '15/06/2009 09:09:17', 'document' => 'Envio', 'number' => '1',
				'lot_id' => '1', 'entry' => '', 'withdraw' => '30', 'balance' => '36'),
				array('created_date' => '18/06/2009 08:08:13', 'document' => 'Factura', 'number' => 'A021-12346',
				'lot_id' => '1', 'entry' => '', 'withdraw' => '5', 'balance' => '26'));
		
		$data_list = KardexDAM::getList($product, $balance_forward, $pages, $items, 0);
		$this->assertEquals(30, $balance_forward);
		
		$this->assertEquals($list[0], $data_list[0]);
		$this->assertEquals($list[1], $data_list[16]);
		$this->assertEquals($list[2], $data_list[29]);
	}
	
	public function testGetList_5(){
		$product = new Product(2);
		
		$list = array(array('created_date' => '15/06/2009 09:09:09', 'document' => 'Devolucion', 'number' => '1',
				'lot_id' => '7', 'entry' => '', 'withdraw' => '2', 'balance' => '3'));
		
		$data_list = KardexDAM::getList($product, $balance_forward, $pages, $items, 1);
		$this->assertEquals(5, $balance_forward);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_6(){
		$product = new Product(3);
		$this->assertEquals(0, count(KardexDAM::getList($product, $balance_forward, $pages, $items, 1)));
	}
}

class ProductBonusListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_bonus-seed.xml');
	}
	
	public function testGetList(){
		$product = new Product(1);
		
		$list = array(array('id' => '2', 'quantity' => '12', 'percentage' => '15.00',
				'created_date' => '18/06/2009', 'expired_date' => '01/10/2012'),
				array('id' => '1', 'quantity' => '30', 'percentage' => '5.00',
				'created_date' => '17/06/2009', 'expired_date' => '15/07/2012'));
				
		$data_list = ProductBonusListDAM::getList($product);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$product = new Product(2);
		$this->assertEquals(0, count(ProductBonusListDAM::getList($product)));
	}
}

class ExpiredLotListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/expired_lot-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('lot_id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'expiration_date' => '01/12/2008', 'quantity' => '15', 'available' => '15'),
				array('lot_id' => '4', 'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'expiration_date' => '01/01/2009', 'quantity' => '12', 'available' => '12'),
				array('lot_id' => '8', 'bar_code' => '5332433221', 'manufacturer' => 'Procter Gamble', 'name' => 'Barby',
				'expiration_date' => '01/02/2009', 'quantity' => '5', 'available' => '5'),
				array('lot_id' => '12', 'bar_code' => '5234321', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
				'expiration_date' => '01/03/2009', 'quantity' => '2', 'available' => '2'));
				
		$data_list = ExpiredLotListDAM::getList('01/05/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('lot_id' => '16', 'bar_code' => '5234321', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
				'expiration_date' => '01/04/2009', 'quantity' => '22', 'available' => '22'));
		
		$data_list = ExpiredLotListDAM::getList('01/05/2009', $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$data_list = ExpiredLotListDAM::getList('01/05/2008', $pages, $items, 1);
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
}

class NearExpirationLotListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/near_expiration_lot-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('lot_id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'expiration_date' => '01/12/2008', 'quantity' => '15', 'available' => '15'),
				array('lot_id' => '4', 'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'expiration_date' => '01/01/2009', 'quantity' => '12', 'available' => '12'),
				array('lot_id' => '8', 'bar_code' => '5332433221', 'manufacturer' => 'Procter Gamble', 'name' => 'Barby',
				'expiration_date' => '01/02/2009', 'quantity' => '5', 'available' => '5'),
				array('lot_id' => '12', 'bar_code' => '5234321', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
				'expiration_date' => '01/03/2009', 'quantity' => '2', 'available' => '2'));
				
		$data_list = NearExpirationLotListDAM::getList('01/11/2008', 200, $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('lot_id' => '16', 'bar_code' => '5234321', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
				'expiration_date' => '01/04/2009', 'quantity' => '22', 'available' => '22'));
		
		$data_list = NearExpirationLotListDAM::getList('01/11/2008', 200, $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$data_list = NearExpirationLotListDAM::getList('01/05/2008', 30, $pages, $items, 1);
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
}

class InactiveProductListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	private function daysDifference($endDate, $beginDate)
	{
	   //explode the date by "-" and storing to array
	   $date_parts1=explode("-", $beginDate);
	   $date_parts2=explode("-", $endDate);
	   //gregoriantojd() Converts a Gregorian date to Julian Day Count
	   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
	   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
	   return $end_date - $start_date;
	}
	
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/inactive_products-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('bar_code' => '5433221', 'manufacturer' => 'Mattel', 'name' => 'Chapulin',
				'quantity' => '3', 'last_sale' => '13/06/2009 14:08:08', 'sale_quantity' => '1'),
				array('bar_code' => '125433221', 'manufacturer' => 'Mattel', 'name' => 'Don Balon',
				'quantity' => '35', 'last_sale' => '13/06/2009 14:08:08', 'sale_quantity' => '2'),
				array('bar_code' => '53332433221', 'manufacturer' => 'Mattel', 'name' => 'Paoata',
				'quantity' => '18', 'last_sale' => '13/06/2009 14:08:08', 'sale_quantity' => '4'),
				array('bar_code' => '5332433221', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'quantity' => '12', 'last_sale' => '14/06/2009 10:09:09', 'sale_quantity' => '3'));
		
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-20');
		$data_list = InactiveProductListDAM::getList(date('d/m/Y'), $days, $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(7, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('bar_code' => '553504321', 'manufacturer' => 'Mattel', 'name' => 'Silla',
				'quantity' => '6', 'last_sale' => '14/06/2009 10:09:09', 'sale_quantity' => '3'),
				array('bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'quantity' => '61', 'last_sale' => '15/06/2009 11:08:08', 'sale_quantity' => '15'),
				array('bar_code' => '589493433221', 'manufacturer' => 'Mattel', 'name' => 'Mesa',
				'quantity' => '5', 'last_sale' => '15/06/2009 11:08:08', 'sale_quantity' => '2'));
		
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-20');
		$data_list = InactiveProductListDAM::getList(date('d/m/Y'), $days, $pages, $items, 2);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$list = array(array('bar_code' => '5332433221', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'quantity' => '12', 'last_sale' => '05/06/2009 14:08:08', 'sale_quantity' => '8'),
				array('bar_code' => '5433221', 'manufacturer' => 'Mattel', 'name' => 'Chapulin',
				'quantity' => '3', 'last_sale' => '07/06/2009 11:08:08', 'sale_quantity' => '3'),
				array('bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
				'quantity' => '61', 'last_sale' => '07/06/2009 11:08:08', 'sale_quantity' => '46'),
				array('bar_code' => '125433221', 'manufacturer' => 'Mattel', 'name' => 'Don Balon',
				'quantity' => '35', 'last_sale' => '07/06/2009 11:08:08', 'sale_quantity' => '14'));
		
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-10');
		$data_list = InactiveProductListDAM::getList(date('d/m/Y'), $days, $pages, $items, 1);
		$this->assertEquals(1, $pages);
		$this->assertEquals(4, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_4(){
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-01');
		$this->assertEquals(0, count(InactiveProductListDAM::getList(date('d/m/Y'), $days, $pages, $items, 1)));
	}
}

class SupplierProductListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/supplier_products-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('id' => '5', 'name' => 'Barbosa', 'manufacturer' => 'Mattel'),
				array('id' => '1', 'name' => 'Barby', 'manufacturer' => 'Mattel'),
				array('id' => '3', 'name' => 'Chino Karate', 'manufacturer' => 'Mattel'),
				array('id' => '4', 'name' => 'Juan Talaber', 'manufacturer' => 'Mattel'),
				array('id' => '10', 'name' => 'Reloj pulser', 'manufacturer' => 'Mattel'),
				array('id' => '9', 'name' => 'Silla', 'manufacturer' => 'Mattel'),
				array('id' => '7', 'name' => 'Telefono porattil', 'manufacturer' => 'Mattel'));
		
		$supplier = SupplierDAM::getInstance(1);
		$data_list = SupplierProductListDAM::getList($supplier);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('id' => '2', 'name' => 'Carrito Veloz', 'manufacturer' => 'Procter Gamble'));
				
		$supplier = SupplierDAM::getInstance(2);
		$data_list = SupplierProductListDAM::getList($supplier);
		$this->assertEquals($list, $data_list);
	}
}

class ReserveListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/reserve-seed.xml');
	}
	
	public function testGetList(){
		$product = new Product(1);
		
		$list = array(array('id' => '1', 'created_date' => '18/06/2009 00:00:00',
	 			'username' => 'roboli', 'lot_id' => '1', 'quantity' => '12'),
				array('id' => '2', 'created_date' => '17/06/2009 00:00:00', 'username' => 'roboli',
				 'lot_id' => '2', 'quantity' => '30'));
				
		$data_list = ReserveListDAM::getList($product);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$product = new Product(2);
		$this->assertEquals(0, count(ReserveListDAM::getList($product)));
	}
}

class ProductDistinctListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_distinct-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('name' => 'Barby'), array('name' => 'Carro'),
				array('name' => 'Libro'), array('name' => 'Transformer'));
				
		$data_list = ProductDistinctListDAM::getList();
		$this->assertEquals($list, $data_list);
	}
}

class ManufacturerDistinctListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/manufacturer_distinct-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('name' => 'AOC'), array('name' => 'Mattel'),
				array('name' => 'Sony'), array('name' => 'Xerox'));
				
		$data_list = ManufacturerDistinctListDAM::getList();
		$this->assertEquals($list, $data_list);
	}
}

class InStockListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/product_stock-seed.xml');
	}
	
	public function testGetList(){
		$list = array(
				array('bar_code' => '12345', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
					'available' => '61', 'price' => '22.50', 'total' => '1372.50'),
				array('bar_code' => '623320', 'manufacturer' => 'Procter Gamble', 'name' => 'No no no no',
					'available' => '40', 'price' => '19.50', 'total' => '780.00'),
				array('bar_code' => '94321', 'manufacturer' => 'Procter Gamble', 'name' => 'Barby',
					'available' => '12', 'price' => '30.30', 'total' => '363.60'),
				array('bar_code' => '32189', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
					'available' => '8', 'price' => '45.15', 'total' => '361.20')
			);
				
		$data_list = InStockListDAM::getList(true, $total, $pages, $items, 1);
		$this->assertEquals(3124.32, $total);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('bar_code' => '5433221', 'manufacturer' => 'Mattel', 'name' => 'Chapulin',
				'available' => '3', 'price' => '82.34', 'total' => '247.02'));
		
		$data_list = InStockListDAM::getList(true, $total, $pages, $items, 2);
		$this->assertEquals(3124.32, $total);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_NoMonetary(){
		$list = array(
				array('bar_code' => '12345', 'manufacturer' => 'Mattel', 'name' => 'Chillon',
					'available' => '61'),
				array('bar_code' => '623320', 'manufacturer' => 'Procter Gamble', 'name' => 'No no no no',
					'available' => '40'),
				array('bar_code' => '94321', 'manufacturer' => 'Procter Gamble', 'name' => 'Barby',
					'available' => '12'),
				array('bar_code' => '32189', 'manufacturer' => 'Procter Gamble', 'name' => 'Cheka',
					'available' => '8')
			);
				
		$data_list = InStockListDAM::getList(false, $t, $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2_NoMonetary(){
		$list = array(array('bar_code' => '5433221', 'manufacturer' => 'Mattel', 'name' => 'Chapulin',
				'available' => '3'));
		
		$data_list = InStockListDAM::getList(false, $t, $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		$this->assertEquals($list, $data_list);
	}
}
?>