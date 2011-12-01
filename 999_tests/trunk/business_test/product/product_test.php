<?php
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/user_account.php');
require_once('business/session.php');
require_once('PHPUnit/Framework/TestCase.php');

class UnitOfMeasureTest extends PHPUnit_Framework_TestCase{
	public function testConstructor(){
		$um = new UnitOfMeasure(4321, PersistObject::CREATED);
		$this->assertEquals(4321, $um->getId());
		$this->assertEquals(PersistObject::CREATED, $um->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$um = new UnitOfMeasure();
		$this->assertNull($um->getId());
		$this->assertEquals(PersistObject::IN_PROGRESS, $um->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$um = new UnitOfMeasure('hola');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$um = new UnitOfMeasure(-2);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$um = UnitOfMeasure::getInstance(123);
		$this->assertEquals('Unitario', $um->getName());
	}
	
	public function testDelete_New(){
		try{
			UnitOfMeasure::delete($this->_mUM);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$um = UnitOfMeasure::getInstance(123);
		UnitOfMeasure::delete($um);
	}
	
	public function testSave_Insert(){
		$um = new UnitOfMeasure();
		$um->setName('yarda');
		$um->save();
		$this->assertEquals(123, $um->getId());
	}
	
	public function testSave_Update(){
		$um = UnitOfMeasure::getInstance(123);
		$um->setName('docena');
		$um->save();
		$other = UnitOfMeasure::getInstance(123);
		$this->assertEquals('docena', $other->getName());
	}
}

class ManufacturerTest extends PHPUnit_Framework_TestCase{
	public function testConstructor(){
		$manufacturer = new Manufacturer(4321, Persist::CREATED);
		$this->assertEquals(4321, $manufacturer->getId());
		$this->assertEquals(Persist::CREATED, $manufacturer->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$manufacturer = new Manufacturer();
		$this->assertNull($manufacturer->getId());
		$this->assertEquals(Persist::IN_PROGRESS, $manufacturer->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$manufacturer = new Manufacturer('yash');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$manufacturer = new Manufacturer(-8);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$manufacturer = Manufacturer::getInstance(123);
		$this->assertEquals('Bayer', $manufacturer->getName());
	}
	
	public function testDelete_New(){
		$manufacturer = new Manufacturer();
		try{
			Manufacturer::delete($manufacturer);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$manufacturer = Manufacturer::getInstance(123);
		Manufacturer::delete($manufacturer);
	}
	
	public function testSave_Insert(){
		$manufacturer = new Manufacturer();
		$manufacturer->setName('Novartis');
		$this->assertEquals(123, $manufacturer->save());
		$this->assertEquals(123, $manufacturer->getId());
	}
	
	public function testSave_Update(){
		$manufacturer = Manufacturer::getInstance(123);
		$manufacturer->setName('Olipharm');
		$this->assertEquals(123, $manufacturer->save());
		$other = Manufacturer::getInstance(123);
		$this->assertEquals('Olipharm', $other->getName());
	}
}

class ProductSupplierTest extends PHPUnit_Framework_TestCase{
	private $_mDetail;
	
	public function setUp(){
		$supplier = Supplier::getInstance(123);
		$this->_mDetail = new ProductSupplier($supplier, 'BEA-214');
	}
	
	public function testConstrutor(){
		$supplier = Supplier::getInstance(123);
		$detail = new ProductSupplier($supplier, 'ABB001', Persist::CREATED);
		$this->assertEquals('123ABB001', $detail->getId());
		$this->assertEquals($supplier, $detail->getSupplier());
		$this->assertEquals('ABB001', $detail->getProductSKU());
		$this->assertEquals(Persist::CREATED, $detail->getStatus());
		$this->assertFalse($detail->isDeleted());
		$details_array = $detail->show();
		$this->assertEquals('Jose Gil', $details_array['supplier']);
		$this->assertEquals('ABB001', $details_array['product_sku']);
	}
	
	public function testConstructor_Defaults(){
		$supplier = Supplier::getInstance(123);
		$detail = new ProductSupplier($supplier, 'BAY-036');
		$this->assertEquals('123BAY-036', $detail->getId());
		$this->assertEquals($supplier, $detail->getSupplier());
		$this->assertEquals('BAY-036', $detail->getProductSKU());
		$this->assertEquals(Persist::IN_PROGRESS, $detail->getStatus());
		$this->assertFalse($detail->isDeleted());
		$details_array = $detail->show();
		$this->assertEquals('Jose Gil', $details_array['supplier']);
		$this->assertEquals('BAY-036', $details_array['product_sku']);
	}
	
	public function testConstructor_BlankSKU(){
		$supplier = Supplier::getInstance(123);
		try{
			$detail = new ProductSupplier($supplier, '');
		} catch(Exception $e){ return; }
		$this->fail('SKU exception expected.');
	}
	
	public function testDelete(){
		$this->_mDetail->delete();
		$this->assertTrue($this->_mDetail->isDeleted());
	}
	
	public function testRestore(){
		$this->_mDetail->delete();
		$this->_mDetail->restore();
		$this->assertFalse($this->_mDetail->isDeleted());
	}
	
	public function testCommit_Insert(){
		$product = Product::getInstance(123);
		$this->_mDetail->commit($product);
	}
	
	public function testCommint_Delete(){
		$supplier = Supplier::getInstance(123);
		$detail = new ProductSupplier($supplier, 'RBO-009', Persist::CREATED);
		$detail->delete();
		$product = Product::getInstance(123);
		$detail->commit($product);
	}
}

class ProductTest extends PHPUnit_Framework_TestCase{
	private $_mProduct;
	
	public function setUp(){
		$this->_mProduct = new Product();
	}
	
	public function testConstructor(){
		$product = new Product(4321, Persist::CREATED);
		$this->assertEquals(4321, $product->getId());
		$this->assertEquals(Persist::CREATED, $product->getStatus());
		$this->assertNull($product->getBarCode());
		$this->assertNull($product->getDescription());
		$this->assertNull($product->getUnitOfMeasure());
		$this->assertNull($product->getManufacturer());
		$this->assertNull($product->getPrice());
		$this->assertFalse($product->isDeactivated());
		$detail = $product->getProductSupplier('XX');
		$this->assertNull($detail);
		$details = $product->showProductSuppliers();
		$this->assertTrue(empty($details));
	}
	
	public function testConstructor_Defaults(){
		$product = new Product();
		$this->assertNull($product->getId());
		$this->assertEquals(Persist::IN_PROGRESS, $product->getStatus());
		$this->assertNull($product->getBarCode());
		$this->assertNull($product->getDescription());
		$this->assertNull($product->getUnitOfMeasure());
		$this->assertNull($product->getManufacturer());
		$this->assertNull($product->getPrice());
		$this->assertFalse($product->isDeactivated());
		$detail = $product->getProductSupplier('XX');
		$this->assertNull($detail);
		$details = $product->showProductSuppliers();
		$this->assertTrue(empty($details));
	}
	
	public function testSetBarCode(){
		$this->_mProduct->setBarCode('1234567');
		$this->assertEquals('1234567', $this->_mProduct->getBarCode());
	}
	
	public function testSetBarCode_Bad(){
		try{
			$this->_mProduct->setBarCode('123*456');
		} catch(Exception $e){ return; }
		$this->fail('Bar code exception expected.');
	}
	
	public function testSetBarCode_InUse(){
		try{
			$this->_mProduct->setBarCode('123456');
		} catch(Exception $e){ return; }
		$this->fail('Bar code exception expected.');
	}
	
	public function testSetBarCode_InUse_2(){
		try{
			$this->_mProduct->setBarCode('0');
		} catch(Exception $e){ return; }
		$this->fail('Bar code exception expected.');
	}
	
	public function testSetDescription(){
		$this->_mProduct->setDescription('Transformer para niño.');
		$this->assertEquals('Transformer para niño.', $this->_mProduct->getDescription());
	}
	
	public function testSetUnitOfMeasure(){
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$this->assertEquals($um, $this->_mProduct->getUnitOfMeasure());
	}
	
	public function testSetManufacturer(){
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->assertEquals($manu, $this->_mProduct->getManufacturer());
	}
	
	public function testSetPrice(){
		$this->_mProduct->setPrice((float)'12');
		$this->assertEquals(12.00, $this->_mProduct->getPrice());
	}
	
	public function testSetPrice_Txt(){
		try{
			$this->_mProduct->setPrice('hola');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testSetPrice_Negative(){
		try{
			$this->_mProduct->setPrice(-1.23);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testDeactivate(){
		$this->_mProduct->deactivate(true);
		$this->assertTrue($this->_mProduct->isDeactivated());
	}
	
	public function testSetData_Defaults(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		$this->_mProduct->setData('09876', 'Optimus', $um, $manu,
				56.90, false, $details);
		$this->assertEquals('Optimus', $this->_mProduct->getName());
		$this->assertEquals('09876', $this->_mProduct->getBarCode());
		$this->assertNull($this->_mProduct->getDescription());
		$this->assertEquals($um, $this->_mProduct->getUnitOfMeasure());
		$this->assertEquals($manu, $this->_mProduct->getManufacturer());
		$this->assertEquals(56.90, $this->_mProduct->getPrice());
		$this->assertFalse($this->_mProduct->isDeactivated());
		$detail = $this->_mProduct->getProductSupplier('123ABB001');
		$this->assertEquals('ABB001', $detail->getProductSKU());
	}
	
	public function testSetData(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		$this->_mProduct->setData('09876', 'Optimus', $um, $manu,
				56.90, false, $details, 'El gran lider.');
		$this->assertEquals('Optimus', $this->_mProduct->getName());
		$this->assertEquals('09876', $this->_mProduct->getBarCode());
		$this->assertEquals('El gran lider.', $this->_mProduct->getDescription());
		$this->assertEquals($um, $this->_mProduct->getUnitOfMeasure());
		$this->assertEquals($manu, $this->_mProduct->getManufacturer());
		$this->assertEquals(56.90, $this->_mProduct->getPrice());
		$this->assertFalse($this->_mProduct->isDeactivated());
		$detail = $this->_mProduct->getProductSupplier('123ABB001');
		$this->assertEquals('ABB001', $detail->getProductSKU());
	}
	
	public function testSetData_BlankName(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('', '09876', 'Caja grande', 'El gran lider.', $um, $manu,
					56.90, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BlankPackaging(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('Optimus', '09876', '', 'El gran lider.', $um, $manu,
					56.90, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BlankDescription(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('Optimus', '09876', 'Caja grande', '', $um, $manu,
					56.90, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_NewUnitOfMeasure(){
		$um = new UnitOfMeasure();
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('Optimus', '09876', 'Caja grande', 'El gran lider.', $um, $manu,
					56.90, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_NewManufacturer(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = new Manufacturer();
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('Optimus', '09876', 'Caja grande', 'El gran lider.', $um, $manu,
					56.90, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadPrice(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB001', Persist::CREATED);
		try{
			$this->_mProduct->setData('Optimus', '09876', 'Caja grande', 'El gran lider.', $um, $manu,
					56, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		try{
			$this->_mProduct->setData('Optimus', '09876', 'Caja grande', 'El gran lider.', $um, $manu,
					56.00, false, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testAddProductSupplier(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$this->_mProduct->addProductSupplier($detail);
		$this->assertEquals($detail, $this->_mProduct->getProductSupplier('123Bay007'));
	}
	
	public function testAddProductSupplier_2(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$detail2 = new ProductSupplier(Supplier::getInstance(123), 'ABB099');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->addProductSupplier($detail2);
		$details = $this->_mProduct->showProductSuppliers();
		$this->assertEquals('Bay007', $details[0]['product_sku']);
		$this->assertEquals('ABB099', $details[1]['product_sku']);
	}
	
	public function testAddProductSupplier_AlreadyAdded(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$detail2 = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$this->_mProduct->addProductSupplier($detail);
		try{
			$this->_mProduct->addProductSupplier($detail2);
		} catch(Exception $e){ return; }
		$this->fail('Detail exception expected.');
	}
	
	public function testAddProductSupplier_Exists(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABC');
		try{
			$this->_mProduct->addProductSupplier($detail);
		} catch(Exception $e){ return; }
		$this->fail('Detail exception expected.');
	}
	
	public function testAddProductSupplier_AlreadyDeleted(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$detail2 = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->deleteProductSupplier($detail);
		$this->_mProduct->addProductSupplier($detail2);
		$this->assertEquals(1, count($this->_mProduct->showProductSuppliers()));
	}
	
	public function testDeleteProductSupplier_New(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->deleteProductSupplier($detail);
		$details = $this->_mProduct->showProductSuppliers();
		$this->assertTrue(empty($details));
	}
	
	public function testDeleteProductSupplier_NotNew(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007', Persist::CREATED);
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->deleteProductSupplier($detail);
		$this->assertTrue($detail->isDeleted());
		$details = $this->_mProduct->showProductSuppliers();
		$this->assertTrue(empty($details));
	}
	
	public function testRestoreProductSupplier(){
		$detail = new ProductSupplier(Supplier::getInstance(123), 'Bay007', Persist::CREATED);
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->deleteProductSupplier($detail);
		$this->_mProduct->addProductSupplier($detail);
		$this->assertFalse($detail->isDeleted());
		$details = $this->_mProduct->showProductSuppliers();
		$this->assertFalse(empty($details));
	}
	
	public function testSave_Insert(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->save();
		$this->assertEquals(123, $this->_mProduct->getId());
		$this->assertEquals(Persist::CREATED, $this->_mProduct->getStatus());
	}
	
	public function testSave_Update(){
		$helper = InventorySession::getInstance();
		$user = UserAccount::getInstance('roboli');
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$product = Product::getInstance(123);
		$product->setPrice(30.00);
		$product->save();
		$other = Product::getInstance(123);
	}
	
	public function testSave_NoName(){
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoBarCode(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->save();
		$this->assertEquals('123', $this->_mProduct->getBarCode());
	}
	
	public function testSave_NoUM(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoManufacturer(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoPrice(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoProductSuppliers(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoProductSuppliers_2(){
		$this->_mProduct->setName('Aspirina');
		$this->_mProduct->setBarCode('54321');
		$this->_mProduct->setDescription('Para el dolor.');
		$um = UnitOfMeasure::getInstance(123);
		$this->_mProduct->setUnitOfMeasure($um);
		$manu = Manufacturer::getInstance(123);
		$this->_mProduct->setManufacturer($manu);
		$this->_mProduct->setPrice(15.80);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->addProductSupplier($detail);
		$this->_mProduct->deleteProductSupplier($detail);
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_BarCode_InUse(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->setData('123456', 'Aspirina', $um, $manu, 15.80, false,
				$details, 'Para el dolor.');
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_BarCode_InUse_2(){
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$details[] = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$this->_mProduct->setData('0', 'Aspirina', $um, $manu, 15.80, false,
				$details, 'Para el dolor.');
		try{
			$this->_mProduct->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_Update_BarCode_IdInUse(){
		$product = new Product(123456, Persist::CREATED);
		$um = UnitOfMeasure::getInstance(123);
		$manu = Manufacturer::getInstance(123);
		$detail = new ProductSupplier(Supplier::getInstance(123), 'ABB456');
		$product->setData('0', 'Aspirina', $um, $manu, 15.80, false,
				array($detail), 'Para el dolor.');
		$product->setBarCode('');
		$product->save();
		$this->assertGreaterThan(99999, $product->getBarCode());
		$this->assertLessThan(1000000, $product->getBarCode());
	}
	
	public function testGetProductIdBySupplier(){
		$supplier = Supplier::getInstance(123);
		$this->assertEquals(123, Product::getProductIdBySupplier($supplier, 'Abb213'));
	}
	
	public function testGetProductIdByBarCode(){
		$this->assertEquals(123, Product::getProductIdByBarCode('12345'));
	}
	
	public function testGetInstance(){
		$product = Product::getInstance(123);
		$this->assertEquals(123, $product->getId());
	}
	
	public function testDelete_New(){
		$product = new Product();
		try{
			Product::delete($product);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$product = Product::getInstance(123);
		Product::delete($product);
	}
}

class BonusTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$product = Product::getInstance(123);
		$user = UserAccount::getInstance('josoli');
		$bonus = new Bonus($product, 4, 25.00, '30/04/2009', '01/04/2009', $user, 4321, Persist::CREATED);
		$this->assertEquals($product, $bonus->getProduct());
		$this->assertEquals(4, $bonus->getQuantity());
		$this->assertEquals(25.00, $bonus->getPercentage());
		$this->assertEquals('30/04/2009', $bonus->getExpirationDate());
		$this->assertEquals('01/04/2009', $bonus->getCreatedDate());
		$this->assertEquals($user, $bonus->getUser());
		$this->assertEquals(4321, $bonus->getId());
		$this->assertEquals(Persist::CREATED, $bonus->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(123);
		$bonus = new Bonus($product, 4, 25.00, '30/04/2030');
		$this->assertEquals($product, $bonus->getProduct());
		$this->assertEquals(4, $bonus->getQuantity());
		$this->assertEquals(25.00, $bonus->getPercentage());
		$this->assertEquals('30/04/2030', $bonus->getExpirationDate());
		$this->assertEquals($bonus->getCreatedDate(), date('d/m/Y'));
		$this->assertEquals($user, $bonus->getUser());
		$this->assertNull($bonus->getId());
		$this->assertEquals(Persist::IN_PROGRESS, $bonus->getStatus());
	}
	
	public function testConstructor_BadQuantityTxt(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 'hey', 25, '30/04/2015');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadQuantityNoPositive(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, -1, 25, '30/04/2015');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadPercentageTxt(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 'no', '30/04/2015');
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testConstructor_BadPercentageNoPositive(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, -1.5, '30/04/2015');
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testConstructor_BadPercentageMoreThan100(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 101.00, '30/04/2015');
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testConstructor_BadExpirationDate(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 25, '30/do/2015');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadCreatedDate(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 25, '30/04/2015', 'yeah!');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadCreatedDateGreaterThan(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 25, '30/04/2015', '01/05/2016');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadIdTxt(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 25, '30/04/2015', '01/04/2009', 'yeas');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		$product = Product::getInstance(123);
		try{
			$bonus = new Bonus($product, 4, 25, '30/04/2015', '01/04/2009', -9);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSave(){
		$product = Product::getInstance(123);
		$bonus = new Bonus($product, 10, 20.00, '30/04/2030');
		$bonus->save();
		$this->assertEquals(123, $bonus->getId());
		$this->assertEquals(Persist::CREATED, $bonus->getStatus());
	}
	
	public function testSave_Exists(){
		$product = Product::getInstance(123);
		$bonus = new Bonus($product, 4, 20.00, '15/04/2030');
		try{
			$bonus->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testGetInstance(){
		$bonus = Bonus::getInstance(123);
		$this->assertEquals(123, $bonus->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$bonus = Bonus::getInstance('helo');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$bonus = Bonus::getInstance(0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetBonusIdByProduct(){
		$product = Product::getInstance(123);
		$this->assertEquals(123, Bonus::getBonusIdByProduct($product, 4));
	}
	
	public function testDelete_New(){
		$bonus = new Bonus(Product::getInstance(123), 6, 1.25, '20/06/2030');
		try{
			Bonus::delete($bonus);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$bonus = Bonus::getInstance(123);
		Bonus::delete($bonus);
	}
}

class LotTest extends PHPUnit_Framework_TestCase{
	private $_mLot;
	
	public function setUp(){
		$product = Product::getInstance(123);
		$this->_mLot = new Lot($product, 16, 0.00, '25/10/2009');
	}
	
	public function testConstructor(){
		$product = Product::getInstance(123);
		$lot = new Lot($product, 16, 10.25, '25/10/2009', '10/01/2009', 54321, Persist::CREATED);
		$this->assertEquals($product, $lot->getProduct());
		$this->assertEquals(0, $lot->getQuantity()); // Because the status property...
		$this->assertEquals(10.25, $lot->getPrice());
		$this->assertEquals('25/10/2009', $lot->getExpirationDate());
		$this->assertEquals('10/01/2009', $lot->getEntryDate());
		$this->assertEquals(54321, $lot->getId());
		$this->assertEquals(Persist::CREATED, $lot->getStatus());
		$this->assertEquals(0, $lot->getAvailable());
		$data_array = $lot->show();
		$this->assertEquals(54321, $data_array['id']);
		$this->assertEquals('10/01/2009', $data_array['entry_date']);
		$this->assertEquals('25/10/2009', $data_array['expiration_date']);
		$this->assertEquals(10.25, $data_array['price']);
		$this->assertEquals(0, $data_array['quantity']);
		$this->assertEquals(0, $data_array['available']);
	}
	
	public function testConstructor_Defaults(){
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$this->assertEquals($product, $lot->getProduct());
		$this->assertEquals(0, $lot->getQuantity());
		$this->assertEquals(0, $lot->getPrice());
		$this->assertNull($lot->getExpirationDate());
		$this->assertNull($lot->getEntryDate());
		$this->assertEquals(0, $lot->getId());
		$this->assertEquals(Persist::IN_PROGRESS, $lot->getStatus());
		$this->assertEquals(0, $lot->getAvailable());
		$this->assertEquals(0, $lot->getQuantity());
		$data_array = $lot->show();
		$this->assertEquals(0, $data_array['id']);
		$this->assertNull($data_array['entry_date']);
		$this->assertEquals('', $data_array['expiration_date']);
		$this->assertEquals(0, $data_array['price']);
		$this->assertEquals(0, $data_array['available']);
		$this->assertEquals(0, $data_array['quantity']);
	}
	
	public function testConstruct_BadQuantityTxt(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 'heu');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadPriceTxt(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, 'yeah');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testConstructor_BadPriceNegative(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, -2.39);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testConstructor_BlankExpirationDate(){
		$product = Product::getInstance(123);
		$lot = new Lot($product, 16, 10.25, '', '10/01/2009', 54321, Persist::CREATED);
		$this->assertEquals($product, $lot->getProduct());
		$this->assertEquals(0, $lot->getQuantity()); // Because the status property...
		$this->assertEquals(10.25, $lot->getPrice());
		$this->assertEquals('', $lot->getExpirationDate());
		$this->assertEquals('10/01/2009', $lot->getEntryDate());
		$this->assertEquals(54321, $lot->getId());
		$this->assertEquals(Persist::CREATED, $lot->getStatus());
		$this->assertEquals(0, $lot->getAvailable());
		$data_array = $lot->show();
		$this->assertEquals(54321, $data_array['id']);
		$this->assertEquals('10/01/2009', $data_array['entry_date']);
		$this->assertEquals('', $data_array['expiration_date']);
		$this->assertEquals(10.25, $data_array['price']);
		$this->assertEquals(0, $data_array['quantity']);
		$this->assertEquals(0, $data_array['available']);
	}
	
	public function testConstructor_BadExpirationDate(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, 22.39, '43/diud');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadEntryDate(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, 22.39, '23/02/2010', '01/45/2019');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadIdTxt(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, 22.39, '23/02/2010', '01/10/2019', 'dud');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNegative(){
		$product = Product::getInstance(123);
		try{
			$lot = new Lot($product, 10, 22.39, '23/02/2010', '01/10/2019', -5);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetPrice(){
		$this->_mLot->setPrice(23.89);
		$this->assertEquals(23.89, $this->_mLot->getPrice());
	}
	
	public function testSetPrice_BadTxt(){
		try{
			$this->_mLot->setPrice('sno');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testSetPrice_BadNegative(){
		try{
			$this->_mLot->setPrice(-3.53);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testSetExpirationDate(){
		$this->_mLot->setExpirationDate('24/11/2008');
		$this->assertEquals('24/11/2008', $this->_mLot->getExpirationDate());
	}
	
	public function testSetExpirationDate_Blank(){
		$this->_mLot->setExpirationDate('');
		$this->assertEquals('', $this->_mLot->getExpirationDate());
	}
	
	public function testSetExpirationDate_Bad(){
		try{
			$this->_mLot->setExpirationDate('sno');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSetExpirationDate_Bad_2(){
		try{
			$this->_mLot->setExpirationDate('0');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testDeactivate(){
		$lot = Lot::getInstance(123);
		$lot->deactivate();
	}
	
	public function testIncrease(){
		$lot = Lot::getInstance(123);
		$lot->increase(5);
	}
	
	public function testIncrease_BadTxt(){
		$lot = Lot::getInstance(123);
		try{
			$lot->increase('jo');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testIncrease_BadNoPositive(){
		$lot = Lot::getInstance(123);
		try{
			$lot->increase(0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testDecrease(){
		$lot = Lot::getInstance(123);
		$lot->decrease(5);
	}
	
	public function testDecrease_BadTxt(){
		$lot = Lot::getInstance(123);
		try{
			$lot->decrease('jo');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testDecrease_BadNoPositive(){
		$lot = Lot::getInstance(123);
		try{
			$lot->decrease(0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testReserve(){
		$lot = Lot::getInstance(123);
		$lot->reserve(5);
	}
	
	public function testReserve_BadTxt(){
		$lot = Lot::getInstance(123);
		try{
			$lot->reserve('jo');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testReserve_BadNoPositive(){
		$lot = Lot::getInstance(123);
		try{
			$lot->reserve(0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testDecreaseReserve(){
		$lot = Lot::getInstance(123);
		$lot->decreaseReserve(5);
	}
	
	public function testDecreaseReserve_BadTxt(){
		$lot = Lot::getInstance(123);
		try{
			$lot->decreaseReserve('jo');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testDecreaseReserve_BadNoPositive(){
		$lot = Lot::getInstance(123);
		try{
			$lot->decreaseReserve(0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testSave(){
		$this->_mLot->save();
		$this->assertEquals(Persist::CREATED, $this->_mLot->getStatus());
	}
	
	public function testGetInstance(){
		$lot = Lot::getInstance(123);
		$this->assertEquals(123, $lot->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$lot = Lot::getInstance('dsl');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$lot = Lot::getInstance(-3);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
}

class InventoryTest extends PHPUnit_Framework_TestCase{
	
	public function testGetAvailable(){
		$product = Product::getInstance(123);
		$this->assertEquals(12, Inventory::getAvailable($product));
	}
	
	public function testGetQuantity(){
		$product = Product::getInstance(123);
		$this->assertEquals(20, Inventory::getQuantity($product));
	}
	
	public function testGetLots_Exact(){
		$product = Product::getInstance(123);
		$lots = Inventory::getLots($product, 12);
		$this->assertEquals(2, count($lots));
		$this->assertEquals(4321, $lots[0]->getId());
		$this->assertEquals(4322, $lots[1]->getId());
	}
	
	public function testGetLots_More(){
		$product = Product::getInstance(123);
		$lots = Inventory::getLots($product, 15);
		$this->assertEquals(3, count($lots));
		$this->assertEquals(4321, $lots[0]->getId());
		$this->assertEquals(4322, $lots[1]->getId());
		$this->assertEquals(123, $lots[2]->getId());
	}
	
	public function testGetLots_Less(){
		$product = Product::getInstance(123);
		$lots = Inventory::getLots($product, 2);
		$this->assertEquals(1, count($lots));
		$this->assertEquals(4321, $lots[0]->getId());
	}
	
	public function testGetLots_NewProduct(){
		$product = new Product();
		try{
			$lots = Inventory::getLots($product, 30);
		} catch(Exception $e){ return; }
		$this->fail('Product exception expected.');
	}
	
	public function testGetLots_BadQuantityTxt(){
		$product = Product::getInstance(123);
		try{
			$lots = Inventory::getLots($product, 'hey');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testGetLots_BadQuantityNoPositive(){
		$product = Product::getInstance(123);
		try{
			$lots = Inventory::getLots($product, 0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testShowLots(){
		$product = Product::getInstance(123);
		$lot_details = Inventory::showLots($product, $quantity, $available);
		$this->assertEquals(2, count($lot_details));
		$this->assertEquals(4321, $lot_details[0]['id']);
		$this->assertEquals(4322, $lot_details[1]['id']);
		$this->assertEquals(20, $quantity);
		$this->assertEquals(12, $available);
	}
	
	public function testIncrease(){
		$product = Product::getInstance(123);
		Inventory::increase($product, 10);
	}
	
	public function testDecrease(){
		$product = Product::getInstance(123);
		Inventory::decrease($product, 10);
	}
	
	public function testReserve(){
		$product = Product::getInstance(123);
		Inventory::reserve($product, 10);
	}
	
	public function testDecreaseReserve(){
		$product = Product::getInstance(123);
		Inventory::decreaseReserve($product, 10);
	}
}

class ChangePriceLogTest extends PHPUnit_Framework_TestCase{
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
	}
	
	public function testWrite(){
		$product = Product::getInstance(123);
		ChangePriceLog::write($product, 10.90, 24.19);
	}
	
	public function testWrite_BadLastPriceTxt(){
		$product = Product::getInstance(123);
		try{
			ChangePriceLog::write($product, 'sja', 24.19);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testWrite_BadLastPriceNegative(){
		$product = Product::getInstance(123);
		try{
			ChangePriceLog::write($product, -2.32, 24.19);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testWrite_BadNewPriceTxt(){
		$product = Product::getInstance(123);
		try{
			ChangePriceLog::write($product, 123.34, 'dsf');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testWrite_BadNewPriceNegative(){
		$product = Product::getInstance(123);
		try{
			ChangePriceLog::write($product, 2.32, -24.19);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
}

class ProductSearchTest extends PHPUnit_Framework_TestCase{
	
	public function testSearch(){
		$result = ProductSearch::search('algo');
		$this->assertEquals(2, count($result));
	}
	
	public function testSearch_Blank(){
		try{
			$result = ProductSearch::search('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ManufacturerProductListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$manu = Manufacturer::getInstance(123);
		$list = ManufacturerProductList::getList($manu);
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_Defaults(){
		$manu = Manufacturer::getInstance(123);
		$list = ManufacturerProductList::getList($manu);
		$this->assertEquals(2, count($list));
	}
}

class KardexTest extends PHPUnit_Framework_TestCase{
	
	public function testShowPage(){
		$product = Product::getInstance(123);
		$data = Kardex::showPage($product, $balance, $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(30, $balance);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testShowPage_Defaults(){
		$product = Product::getInstance(123);
		$data = Kardex::showPage($product, $balance);
		$this->assertEquals(2, count($data));
		$this->assertEquals(30, $balance);
	}
	
	public function testShowPage_BadPageTxt(){
		$product = new Product();
		try{
			$data = Kardex::showPage($product, $balance, $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testShowPage_BadPageNegative(){
		$product = new Product();
		try{
			$data = Kardex::showPage($product, $balance, $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testShowLastPage(){
		$product = Product::getInstance(123);
		$data = Kardex::showLastPage($product, $balance, $pages, $items);
		$this->assertEquals(2, count($data));
		$this->assertEquals(30, $balance);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testLastShowPage_Defaults(){
		$product = Product::getInstance(123);
		$data = Kardex::showLastPage($product, $balance);
		$this->assertEquals(2, count($data));
		$this->assertEquals(30, $balance);
	}
}

class ProductBonusListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ProductBonusList::getList(Product::getInstance(123));
		$this->assertEquals(2, count($list));
	}
}

class ExpiredLotListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = ExpiredLotList::getList('01/01/2010', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = ExpiredLotList::getList('01/01/2010');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = ExpiredLotList::getList('01/01/2010', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = ExpiredLotList::getList('01/01/2010', $pages, $items, -45);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class NearExpirationLotListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = NearExpirationLotList::getList('01/01/2010', 15, $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = NearExpirationLotList::getList('01/01/2010', 15);
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadDaysTxt(){
		try{
			$data = NearExpirationLotList::getList('01/01/2010', 'no', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadDaysNegative(){
		try{
			$data = NearExpirationLotList::getList('01/01/2010', -15, $pages, $items, -45);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = NearExpirationLotList::getList('01/01/2010', 15, $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = NearExpirationLotList::getList('01/01/2010', 15, $pages, $items, -45);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class InactiveProductListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = InactiveProductList::getList(date('d/m/Y'), 15, $pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = InactiveProductList::getList(date('d/m/Y'), 15);
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadDaysTxt(){
		try{
			$list = InactiveProductList::getList(date('d/m/Y'), 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadDaysNoPositive(){
		try{
			$list = InactiveProductList::getList(date('d/m/Y'), 0);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = InactiveProductList::getList(date('d/m/Y'), 15, $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = InactiveProductList::getList(date('d/m/Y'), 15, $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class SupplierProductListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$supplier = Supplier::getInstance(123);
		$list = SupplierProductList::getList($supplier);
		$this->assertEquals(2, count($list));
	}
}

class ReserveListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ReserveList::getList(Product::getInstance(123));
		$this->assertEquals(2, count($list));
	}
}

class ProductDistinctListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ProductDistinctList::getList();
		$this->assertEquals(3, count($list));
	}
}

class ManufacturerDistinctListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ManufacturerDistinctList::getList();
		$this->assertEquals(3, count($list));
	}
}

class InStockListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = InStockList::getList(false, $total, $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(100.00, $total);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = InStockList::getList(true, $total);
		$this->assertEquals(2, count($data));
		$this->assertEquals(100.00, $total);
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = InStockList::getList(false, $total, $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = InStockList::getList(false, $total, $pages, $items, -45);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}
?>