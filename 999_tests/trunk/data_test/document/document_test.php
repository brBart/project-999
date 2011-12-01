<?php
require_once('config/config.php');

require_once('business/document.php');
require_once('business/cash.php');
require_once('business/user_account.php');
require_once('business/product.php');
require_once('business/transaction.php');
require_once('business/agent.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('PHPUnit/Extensions/Database/DataSet/DataSetFilter.php');

class CorrelativeDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/correlative-seed.xml');
	}
	
	public function testInsert(){
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-100', '01/06/2008', '05/06/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$correlative = new Correlative(2, 'A032');
		$correlative->setData('2009-1009', '01/10/2008', '05/10/2008', 'Sujeto pagos', 10000, 30000);
		CorrelativeDAM::insert($correlative);
		
		$xml_dataset = $this->createXMLDataSet('data_files/correlative-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('correlative')));
	}
	
	public function testGetInstance(){
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-100', '01/06/2008', '05/06/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$other_correlative = CorrelativeDAM::getInstance(1);
		$this->assertEquals(1, $other_correlative->getId());
		$this->assertEquals('A031', $other_correlative->getSerialNumber());
		$this->assertEquals(Correlative::CREATED, $other_correlative->getStatus());
		$this->assertEquals('2009-100', $other_correlative->getResolutionNumber());
		$this->assertEquals('01/06/2008', $other_correlative->getResolutionDate());
		$this->assertEquals('05/06/2008', $other_correlative->getCreatedDate());
		$this->assertEquals('Sujeto pagos', $other_correlative->getRegime());
		$this->assertEquals(1000, $other_correlative->getInitialNumber());
		$this->assertEquals(9000, $other_correlative->getFinalNumber());
		$this->assertEquals(0, $other_correlative->getCurrentNumber());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(CorrelativeDAM::getInstance(43));
	}
	
	public function testExists(){
		$this->assertFalse(CorrelativeDAM::exists('A031', 1, 25));
		
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-100', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertTrue(CorrelativeDAM::exists('A031', 1000, 9000));
	}
	
	public function testGetSerialFinalNumber_Exists(){
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-100', '01/06/2008', '05/06/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertEquals(9000, CorrelativeDAM::getSerialFinalNumber('A031'));
	}
	
	public function testGetSerialFinalNumber_NonExistent(){
		$this->assertEquals(0, CorrelativeDAM::getSerialFinalNumber('CHI999'));
	}
	
	public function testExistsResolutionNumber(){
		$this->assertFalse(CorrelativeDAM::existsResolutionNumber('2009-1010'));
		
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-1010', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertTrue(CorrelativeDAM::existsResolutionNumber('2009-1010'));
	}
	
	public function testIsQueueEmpty(){
		$this->assertTrue(CorrelativeDAM::isQueueEmpty());
		
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-1010', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertFalse(CorrelativeDAM::isQueueEmpty());
		
		CorrelativeDAM::getNextNumber($correlative);
		
		$this->assertTrue(CorrelativeDAM::isQueueEmpty());
	}
	
	public function testGetNextNumber(){
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-100', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertEquals(1000, CorrelativeDAM::getNextNumber($correlative));
		$this->assertEquals(1001, CorrelativeDAM::getNextNumber($correlative));
		$this->assertEquals(1002, CorrelativeDAM::getNextNumber($correlative));
		
		$other_correlative = CorrelativeDAM::getInstance(1);
		$this->assertEquals(1002, $other_correlative->getCurrentNumber());
	}
	
	public function testUpdateStatus(){
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-1010', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1000, 9000);
		CorrelativeDAM::insert($correlative);
		
		$correlative = CorrelativeDAM::getInstance(1);
		$this->assertEquals(Correlative::CREATED, $correlative->getStatus());
		
		CorrelativeDAM::updateStatus($correlative, Correlative::EXPIRED);
		
		$correlative = CorrelativeDAM::getInstance(1);
		$this->assertEquals(Correlative::EXPIRED, $correlative->getStatus());
	}
	
	public function testGetCurrentCorrelativeId(){
		$this->assertNull(CorrelativeDAM::getCurrentCorrelativeId());
		
		$correlative = new Correlative(1, 'A031');
		$correlative->setData('2009-1010', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1, 2);
		CorrelativeDAM::insert($correlative);
		
		$this->assertEquals(1, CorrelativeDAM::getCurrentCorrelativeId());
		
		CorrelativeDAM::getNextNumber($correlative);
		CorrelativeDAM::getNextNumber($correlative);
		
		$this->assertNull(CorrelativeDAM::getCurrentCorrelativeId());
		
		$correlative = new Correlative(2, 'A032');
		$correlative->setData('20039-10110', '01/06/2008', '05/10/2008', 'Sujeto pagos', 1, 2);
		CorrelativeDAM::insert($correlative);
		
		$this->assertEquals(2, CorrelativeDAM::getCurrentCorrelativeId());
	}
	
	public function testDelete(){
		$correlative = new Correlative(1, 'A032');
		$correlative->setData('2009-100', '01/10/2008', '05/10/2008', 'Sujeto pagos', 10000, 30000);
		CorrelativeDAM::insert($correlative);
		
		$this->assertTrue(CorrelativeDAM::delete($correlative));
		$xml_dataset = $this->createXMLDataSet('data_files/correlative-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('correlative')));
	}
}

class CorrelativeDeleteInvoiceDependencyTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/correlative-invoice-dependency.xml');
	}
	
	public function testDelete(){
		$correlative = CorrelativeDAM::getInstance(1);
		$this->assertFalse(CorrelativeDAM::delete($correlative));
	}
}

class DiscountDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/discount-seed.xml');
	}
	
	public function testInsert(){
		$cash_register = CashRegister::getInstance(1);
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice($cash_register, NULL, $user, 1);
		$discount = new Discount($user);
		$discount->setInvoice($invoice);
		$discount->setPercentage(25.00);
		DiscountDAM::insert($discount);
		
		$invoice = new Invoice($cash_register, NULL, $user, 2);
		$discount = new Discount($user);
		$discount->setInvoice($invoice);
		$discount->setPercentage(15.00);
		DiscountDAM::insert($discount);
		
		$xml_dataset = $this->createXMLDataSet('data_files/discount-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('discount')));
	}
	
	public function testGetInstance(){
		$cash_register = CashRegister::getInstance(1);
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice($cash_register, NULL, $user, 1, Persist::CREATED);
		$discount = new Discount($user);
		$discount->setData($invoice, 25.00);
		DiscountDAM::insert($discount);
		
		$other_discount = DiscountDAM::getInstance($invoice);
		$this->assertEquals(Persist::CREATED, $other_discount->getStatus());
		$this->assertEquals($invoice, $other_discount->getInvoice());
		$this->assertEquals($user, $other_discount->getUser());
		$this->assertEquals(25.00, $other_discount->getPercentage());
	}
	
	public function testGetInstance_NonExistent(){
		$cash_register = CashRegister::getInstance(1);
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice($cash_register, NULL, $user, 1, Persist::CREATED);
		$this->assertNull(DiscountDAM::getInstance($invoice));
	}
}

class DocBonusDetailDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice_bonus-seed.xml');
	}
	
	public function testInsert(){
		$cash_register = CashRegister::getInstance(1);
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice($cash_register, NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$bonus = new Bonus($product, 1, 10.00, '10/10/2012', NULL, $user, 1, Persist::CREATED);
		$detail = new DocBonusDetail($bonus, 23.45);
		
		DocBonusDetailDAM::insert($detail, $invoice, 1);
		
		$bonus = new Bonus($product, 1, 10.00, '10/10/2012', NULL, $user, 2, Persist::CREATED);
		$detail = new DocBonusDetail($bonus, 43.21);
		
		DocBonusDetailDAM::insert($detail, $invoice, 2);
		
		$bonus = new Bonus($product, 1, 10.00, '10/10/2012', NULL, $user, 3, Persist::CREATED);
		$detail = new DocBonusDetail($bonus, 12.34);
		
		DocBonusDetailDAM::insert($detail, $invoice, 4);
		
		$xml_dataset = $this->createXMLDataSet('data_files/invoice_bonus-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('invoice_bonus')));
	}
}

class DocProductDetailDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/document_lot-seed.xml');
	}
	
	public function testInsert_Invoice(){
		$cash_register = CashRegister::getInstance(1);
		$user = UserAccount::getInstance('roboli');
		$document = new Invoice($cash_register, NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/invoice_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('invoice_lot')));
	}
	
	public function testInsert_PurchaseReturn(){
		$user = UserAccount::getInstance('roboli');
		$document = new PurchaseReturn(NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/purchase_return_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('purchase_return_lot')));
	}
	
	public function testInsert_Receipt(){
		$user = UserAccount::getInstance('roboli');
		$document = new Receipt(NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/receipt_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('receipt_lot')));
	}
	
	public function testInsert_Shipment(){
		$user = UserAccount::getInstance('roboli');
		$document = new Shipment(NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/shipment_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('shipment_lot')));
	}
	
	public function testInsert_EntryAdjustment(){
		$user = UserAccount::getInstance('roboli');
		$document = new EntryIA(NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/entry_adjustment_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('entry_adjustment_lot')));
	}
	
	public function testInsert_WithdrawAdjustment(){
		$user = UserAccount::getInstance('roboli');
		$document = new WithdrawIA(NULL, $user, 1);
		$product = new Product(1, Persist::CREATED);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 1);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 1);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 3);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 2);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 2);
		$detail = new DocProductDetail($lot, new Entry(), 1, 32.45);
		DocProductDetailDAM::insert($detail, $document, 3);
		
		$lot = new Lot($product, 1, 1.50, NULL, NULL, 7);
		$detail = new DocProductDetail($lot, new Entry(), 3, 45.23);
		DocProductDetailDAM::insert($detail, $document, 5);
		
		$xml_dataset = $this->createXMLDataSet('data_files/withdraw_adjustment_lot-after-inserts.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('withdraw_adjustment_lot')));
	}
}

class PurchaseReturnDAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/purchase_return-seed.xml');
	}
	
	public function testInsert(){
		$supplier = SupplierDAM::getInstance(1);
		$return = new PurchaseReturn('15/06/2009 09:09:09', UserAccountDAM::getInstance('roboli'));
		$return->setData($supplier, 'pq si.', 23.45, $details[] = 'uno', 'Robs');
		$id = PurchaseReturnDAM::insert($return);
		$this->assertGreaterThan(0, $id); 
		
		$return = new PurchaseReturn('16/06/2009 08:08:08', UserAccountDAM::getInstance('roboli'));
		$return->setData($supplier, 'pq no.', 33.44, $details);
		$id = PurchaseReturnDAM::insert($return);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/purchase_return-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('purchase_return')),
				array('purchase_return' => array('purchase_return_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class PurchaseReturnDAMGetInstanceAndCancelTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/purchase_return-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$supplier = SupplierDAM::getInstance(1);
				
		$return = PurchaseReturnDAM::getInstance(1);
		$this->assertEquals(1, $return->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $return->getStatus());
		$this->assertEquals($user, $return->getUser());
		$this->assertEquals($supplier, $return->getSupplier());
		$this->assertEquals('15/06/2009 09:09:09', $return->getDateTime());
		$this->assertEquals('pq si.', $return->getReason());
		$this->assertEquals('Robs', $return->getContact());
		$this->assertEquals(23.45, $return->getTotal());
		
		$details = $return->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(3), $details[0]->getLot());
		$this->assertEquals(1, $details[0]->getQuantity());
		$this->assertEquals(10.00, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(20.00, $details[1]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(1), $details[2]->getLot());
		$this->assertEquals(3, $details[2]->getQuantity());
		$this->assertEquals(45.23, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(32.45, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(5), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(50.00, $details[4]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(6), $details[5]->getLot());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(60.00, $details[5]->getPrice());
	}
	
	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
		$supplier = SupplierDAM::getInstance(1);
				
		$return = PurchaseReturnDAM::getInstance(2);
		$this->assertEquals(2, $return->getId());
		$this->assertEquals(PersistDocument::CREATED, $return->getStatus());
		$this->assertEquals($user, $return->getUser());
		$this->assertEquals($supplier, $return->getSupplier());
		$this->assertEquals('16/06/2009 08:08:08', $return->getDateTime());
		$this->assertEquals('pq no.', $return->getReason());
		$this->assertNull($return->getContact());
		$this->assertEquals(33.44, $return->getTotal());
		
		$details = $return->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(PurchaseReturnDAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$return = PurchaseReturnDAM::getInstance(2);
		PurchaseReturnDAM::cancel($return, $user, '17/06/2009 00:00:00');
		
		$xml_dataset = $this->createXMLDataSet('data_files/purchase_return-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('purchase_return', 'purchase_return_cancelled')));
	}
}

class ShipmentDAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shipment-seed.xml');
	}
	
	public function testInsert(){
		$branch = BranchDAM::getInstance(1);
		$shipment = new Shipment('15/06/2009 09:09:09', UserAccountDAM::getInstance('roboli'));
		$shipment->setData($branch, 23.45, $details[] = 'uno', 'Robs');
		$id = ShipmentDAM::insert($shipment);
		$this->assertGreaterThan(0, $id); 
		
		$shipment = new Shipment('16/06/2009 08:08:08', UserAccountDAM::getInstance('roboli'));
		$shipment->setData($branch, 33.44, $details);
		$id = ShipmentDAM::insert($shipment);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/shipment-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('shipment')),
				array('shipment' => array('shipment_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class ShipmentDAMGetInstanceAndCancelTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shipment-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$branch = BranchDAM::getInstance(1);
				
		$shipment = ShipmentDAM::getInstance(1);
		$this->assertEquals(1, $shipment->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $shipment->getStatus());
		$this->assertEquals($user, $shipment->getUser());
		$this->assertEquals($branch, $shipment->getBranch());
		$this->assertEquals('15/06/2009 09:09:09', $shipment->getDateTime());
		$this->assertEquals('Robs', $shipment->getContact());
		$this->assertEquals(23.45, $shipment->getTotal());
		
		$details = $shipment->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(3), $details[0]->getLot());
		$this->assertEquals(1, $details[0]->getQuantity());
		$this->assertEquals(10.00, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(20.00, $details[1]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(1), $details[2]->getLot());
		$this->assertEquals(3, $details[2]->getQuantity());
		$this->assertEquals(45.23, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(32.45, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(5), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(50.00, $details[4]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(6), $details[5]->getLot());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(60.00, $details[5]->getPrice());
	}
	
	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
		$branch = BranchDAM::getInstance(1);
				
		$shipment = ShipmentDAM::getInstance(2);
		$this->assertEquals(2, $shipment->getId());
		$this->assertEquals(PersistDocument::CREATED, $shipment->getStatus());
		$this->assertEquals($user, $shipment->getUser());
		$this->assertEquals($branch, $shipment->getBranch());
		$this->assertEquals('16/06/2009 08:08:08', $shipment->getDateTime());
		$this->assertNull($shipment->getContact());
		$this->assertEquals(33.44, $shipment->getTotal());
		
		$details = $shipment->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ShipmentDAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$shipment = ShipmentDAM::getInstance(2);
		ShipmentDAM::cancel($shipment, $user, '17/06/2009 00:00:00');
		
		$xml_dataset = $this->createXMLDataSet('data_files/shipment-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('shipment', 'shipment_cancelled')));
	}
}

class InvoiceDAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice-seed.xml');
	}
	
	public function testInsert(){
		$cash_register = CashRegisterDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$correlative = CorrelativeDAM::getInstance(1);
		$invoice = new Invoice($cash_register, '17/06/2009 09:09:09', $user);
		$invoice->setData(12345, $correlative, '1725-5', 12.00, 321.45, $details[] = 'uno', 'Robs');
		$id = InvoiceDAM::insert($invoice);
		$this->assertGreaterThan(0, $id); 
		
		$invoice = new Invoice($cash_register, '18/06/2009 08:08:08', $user);
		$invoice->setData(12346, $correlative, 'cf', 12.00, 123.21, $details);
		$id = InvoiceDAM::insert($invoice);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/invoice-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('invoice')),
				array('invoice' => array('invoice_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class InvoiceDAMGetInstanceAndOthersTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$cash_register = CashRegisterDAM::getInstance(1);
		$correlative = CorrelativeDAM::getInstance(1);
		
		$invoice = InvoiceDAM::getInstance(1);
		$this->assertEquals(1, $invoice->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $invoice->getStatus());
		$this->assertEquals($correlative, $invoice->getCorrelative());
		$this->assertEquals(12345, $invoice->getNumber());
		$this->assertEquals($user, $invoice->getUser());
		$this->assertEquals('17/06/2009 09:09:09', $invoice->getDateTime());
		$this->assertEquals('1725-5', $invoice->getCustomerNit());
		$this->assertEquals('Robs', $invoice->getCustomerName());
		$this->assertEquals(241.09, number_format($invoice->getTotal(), 2));
		$this->assertEquals(12, $invoice->getVatPercentage());
		$this->assertEquals($cash_register, $invoice->getCashRegister());
		
		$details = $invoice->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(32.45, $details[1]->getPrice());
		
		$this->assertEquals(BonusDAM::getInstance(1), $details[2]->getBonus());
		$this->assertEquals(1, $details[2]->getQuantity());
		$this->assertEquals(10, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(3), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(10.00, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(20.00, $details[4]->getPrice());
		
		$this->assertEquals(BonusDAM::getInstance(2), $details[5]->getBonus());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(20, $details[5]->getPrice());
	}
	
	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
		$cash_register = CashRegisterDAM::getInstance(1);
		$correlative = CorrelativeDAM::getInstance(1);
		
		$invoice = InvoiceDAM::getInstance(2);
		$this->assertEquals(2, $invoice->getId());
		$this->assertEquals(PersistDocument::CREATED, $invoice->getStatus());
		$this->assertEquals($correlative, $invoice->getCorrelative());
		$this->assertEquals(12346, $invoice->getNumber());
		$this->assertEquals($user, $invoice->getUser());
		$this->assertEquals('18/06/2009 08:08:08', $invoice->getDateTime());
		$this->assertEquals('cf', $invoice->getCustomerNit());
		$this->assertNull($invoice->getCustomerName());
		$this->assertEquals(123.21, $invoice->getTotal());
		$this->assertEquals(12, $invoice->getVatPercentage());
		$this->assertEquals($cash_register, $invoice->getCashRegister());
		
		$details = $invoice->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(InvoiceDAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$invoice = InvoiceDAM::getInstance(2);
		InvoiceDAM::cancel($invoice, $user, '18/06/2009 00:00:00', 'Prueba.');
		
		$xml_dataset = $this->createXMLDataSet('data_files/invoice-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('invoice', 'invoice_cancelled')));
	}
	
	public function testGetId(){
		$this->assertEquals(2, InvoiceDAM::getId('A021', 12346));
	}
	
	public function testGetId_NonExistent(){
		$this->assertEquals(0, InvoiceDAM::getId('A022', 12346));
	}
}

class VatDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/vat-seed.xml');
	}
	
	public function testGetInstance(){
		$vat = VatDAM::getInstance();
		$this->assertEquals(10, $vat->getPercentage());
	}
	
	public function testUpdate(){
		$vat = VatDAM::getInstance();
		$vat->setPercentage(12.00);
		VatDAM::update($vat);
		
		$xml_dataset = $this->createXMLDataSet('data_files/vat-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('vat')));
	}
}

class ReceiptDAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/receipt-seed.xml');
	}
	
	public function testInsert(){
		$supplier = SupplierDAM::getInstance(1);
		$receipt = new Receipt('15/06/2009 09:09:09', UserAccountDAM::getInstance('roboli'));
		$receipt->setData($supplier, '54321', 23.45, $details[] = 'uno');
		$id = ReceiptDAM::insert($receipt);
		$this->assertGreaterThan(0, $id); 
		
		$receipt = new Receipt('16/06/2009 08:08:08', UserAccountDAM::getInstance('roboli'));
		$receipt->setData($supplier, '65432', 33.44, $details);
		$id = ReceiptDAM::insert($receipt);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/receipt-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('receipt')),
				array('receipt' => array('receipt_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class ReceiptDAMGetInstanceAndCancelTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/receipt-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$supplier = SupplierDAM::getInstance(1);
				
		$receipt = ReceiptDAM::getInstance(1);
		$this->assertEquals(1, $receipt->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $receipt->getStatus());
		$this->assertEquals($user, $receipt->getUser());
		$this->assertEquals($supplier, $receipt->getSupplier());
		$this->assertEquals('15/06/2009 09:09:09', $receipt->getDateTime());
		$this->assertEquals('54321', $receipt->getShipmentNumber());
		$this->assertEquals(23.45, $receipt->getShipmentTotal());
		$this->assertEquals(23.45, $receipt->getTotal());
		
		$details = $receipt->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(3), $details[0]->getLot());
		$this->assertEquals(1, $details[0]->getQuantity());
		$this->assertEquals(10.00, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(20.00, $details[1]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(1), $details[2]->getLot());
		$this->assertEquals(3, $details[2]->getQuantity());
		$this->assertEquals(45.23, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(32.45, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(5), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(50.00, $details[4]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(6), $details[5]->getLot());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(60.00, $details[5]->getPrice());
	}
	
	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
		$supplier = SupplierDAM::getInstance(1);
				
		$receipt = ReceiptDAM::getInstance(2);
		$this->assertEquals(2, $receipt->getId());
		$this->assertEquals(PersistDocument::CREATED, $receipt->getStatus());
		$this->assertEquals($user, $receipt->getUser());
		$this->assertEquals($supplier, $receipt->getSupplier());
		$this->assertEquals('16/06/2009 08:08:08', $receipt->getDateTime());
		$this->assertEquals('65432', $receipt->getShipmentNumber());
		$this->assertEquals(33.44, $receipt->getShipmentTotal());
		$this->assertEquals(33.44, $receipt->getTotal());
		
		$details = $receipt->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ReceiptDAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$receipt = ReceiptDAM::getInstance(2);
		ReceiptDAM::cancel($receipt, $user, '17/06/2009 00:00:00');
		
		$xml_dataset = $this->createXMLDataSet('data_files/receipt-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('receipt', 'receipt_cancelled')));
	}
}

class ReserveDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/reserve-seed.xml');
	}
	
	public function testInsert(){
		$lot = LotDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$reserve = ReserveDAM::insert($lot, 5, $user, '18/06/2009 00:00:00');
		$this->assertGreaterThan(0, $reserve->getId());
		
		$reserve = ReserveDAM::insert($lot, 10, $user, '19/06/2009 00:00:00');
		$this->assertGreaterThan(0, $reserve->getId());
		
		$xml_dataset = $this->createXMLDataSet('data_files/reserve-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('reserve')),
				array('reserve' => array('reserve_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testGetInstance(){
		$lot = LotDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$reserve = ReserveDAM::insert($lot, 5, $user, '18/06/2009 00:00:00');
		
		$other_reserve = ReserveDAM::getInstance($reserve->getId());
		$this->assertEquals($reserve->getId(), $other_reserve->getId());
		$this->assertEquals(Persist::CREATED, $other_reserve->getStatus());
		$this->assertEquals($lot, $other_reserve->getLot());
		$this->assertEquals(5, $other_reserve->getQuantity());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ReserveDAM::getInstance(99));
	}
	
	public function testIncrease(){
		$lot = LotDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$reserve = ReserveDAM::insert($lot, 5, $user, '18/06/2009 00:00:00');
		
		ReserveDAM::increase($reserve, 7);
		$other_reserve = ReserveDAM::getInstance($reserve->getId());
		$this->assertEquals(12, $other_reserve->getQuantity());
	}
	
	public function testDelete(){
		$lot = LotDAM::getInstance(1);
		$user = UserAccountDAM::getInstance('roboli');
		$reserve = ReserveDAM::insert($lot, 5, $user, '18/06/2009 00:00:00');
		
		$other_reserve = ReserveDAM::getInstance($reserve->getId());
		ReserveDAM::delete($other_reserve);
		
		$xml_dataset = $this->createXMLDataSet('data_files/reserve-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('reserve')));
	}
}

class EntryIADAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/entry_adjustment-seed.xml');
	}
	
	public function testInsert(){
		$entry = new EntryIA('15/06/2009 09:09:09', UserAccountDAM::getInstance('roboli'));
		$entry->setData('pq si.', 23.45, $details[] = 'uno');
		$id = EntryIADAM::insert($entry);
		$this->assertGreaterThan(0, $id); 
		
		$entry = new EntryIA('16/06/2009 08:08:08', UserAccountDAM::getInstance('roboli'));
		$entry->setData('pq no.', 33.44, $details);
		$id = EntryIADAM::insert($entry);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/entry_adjustment-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('entry_adjustment')),
				array('entry_adjustment' => array('entry_adjustment_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class EntryIADAMGetInstanceAndCancelTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/entry_adjustment-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
				
		$entry = EntryIADAM::getInstance(1);
		$this->assertEquals(1, $entry->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $entry->getStatus());
		$this->assertEquals($user, $entry->getUser());
		$this->assertEquals('15/06/2009 09:09:09', $entry->getDateTime());
		$this->assertEquals('pq si.', $entry->getReason());
		$this->assertEquals(23.45, $entry->getTotal());
		
		$details = $entry->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(3), $details[0]->getLot());
		$this->assertEquals(1, $details[0]->getQuantity());
		$this->assertEquals(10.00, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(20.00, $details[1]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(1), $details[2]->getLot());
		$this->assertEquals(3, $details[2]->getQuantity());
		$this->assertEquals(45.23, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(32.45, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(5), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(50.00, $details[4]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(6), $details[5]->getLot());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(60.00, $details[5]->getPrice());
	}

	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
				
		$entry = EntryIADAM::getInstance(2);
		$this->assertEquals(2, $entry->getId());
		$this->assertEquals(PersistDocument::CREATED, $entry->getStatus());
		$this->assertEquals($user, $entry->getUser());
		$this->assertEquals('16/06/2009 08:08:08', $entry->getDateTime());
		$this->assertEquals('pq no.', $entry->getReason());
		$this->assertEquals(33.44, $entry->getTotal());
		
		$details = $entry->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(EntryIADAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$entry = EntryIADAM::getInstance(2);
		EntryIADAM::cancel($entry, $user, '17/06/2009 00:00:00');
		
		$xml_dataset = $this->createXMLDataSet('data_files/entry_adjustment-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('entry_adjustment', 'entry_adjustment_cancelled')));
	}
}

class WithdrawIADAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/withdraw_adjustment-seed.xml');
	}
	
	public function testInsert(){
		$withdraw = new WithdrawIA('15/06/2009 09:09:09', UserAccountDAM::getInstance('roboli'));
		$withdraw->setData('pq si.', 23.45, $details[] = 'uno');
		$id = WithdrawIADAM::insert($withdraw);
		$this->assertGreaterThan(0, $id); 
		
		$withdraw = new WithdrawIA('16/06/2009 08:08:08', UserAccountDAM::getInstance('roboli'));
		$withdraw->setData('pq no.', 33.44, $details);
		$id = WithdrawIADAM::insert($withdraw);
		$this->assertGreaterThan(0, $id);
		
		$xml_dataset = $this->createXMLDataSet('data_files/withdraw_adjustment-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('withdraw_adjustment')),
				array('withdraw_adjustment' => array('withdraw_adjustment_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class WithdrawIADAMGetInstanceAndCancelTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/withdraw_adjustment-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
				
		$withdraw = WithdrawIADAM::getInstance(1);
		$this->assertEquals(1, $withdraw->getId());
		$this->assertEquals(PersistDocument::CANCELLED, $withdraw->getStatus());
		$this->assertEquals($user, $withdraw->getUser());
		$this->assertEquals('15/06/2009 09:09:09', $withdraw->getDateTime());
		$this->assertEquals('pq si.', $withdraw->getReason());
		$this->assertEquals(23.45, $withdraw->getTotal());
		
		$details = $withdraw->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(3), $details[0]->getLot());
		$this->assertEquals(1, $details[0]->getQuantity());
		$this->assertEquals(10.00, $details[0]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(4), $details[1]->getLot());
		$this->assertEquals(1, $details[1]->getQuantity());
		$this->assertEquals(20.00, $details[1]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(1), $details[2]->getLot());
		$this->assertEquals(3, $details[2]->getQuantity());
		$this->assertEquals(45.23, $details[2]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(2), $details[3]->getLot());
		$this->assertEquals(1, $details[3]->getQuantity());
		$this->assertEquals(32.45, $details[3]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(5), $details[4]->getLot());
		$this->assertEquals(1, $details[4]->getQuantity());
		$this->assertEquals(50.00, $details[4]->getPrice());
		
		$this->assertEquals(LotDAM::getInstance(6), $details[5]->getLot());
		$this->assertEquals(1, $details[5]->getQuantity());
		$this->assertEquals(60.00, $details[5]->getPrice());
	}
	
	public function testGetInstance_2(){
		$user = UserAccountDAM::getInstance('roboli');
				
		$withdraw = WithdrawIADAM::getInstance(2);
		$this->assertEquals(2, $withdraw->getId());
		$this->assertEquals(PersistDocument::CREATED, $withdraw->getStatus());
		$this->assertEquals($user, $withdraw->getUser());
		$this->assertEquals('16/06/2009 08:08:08', $withdraw->getDateTime());
		$this->assertEquals('pq no.', $withdraw->getReason());
		$this->assertEquals(33.44, $withdraw->getTotal());
		
		$details = $withdraw->getDetails();
		
		$this->assertEquals(LotDAM::getInstance(1), $details[0]->getLot());
		$this->assertEquals(3, $details[0]->getQuantity());
		$this->assertEquals(45.23, $details[0]->getPrice());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(WithdrawIADAM::getInstance(99));
	}
	
	public function testCancel(){
		$user = UserAccountDAM::getInstance('roboli');
		$withdraw = WithdrawIADAM::getInstance(2);
		WithdrawIADAM::cancel($withdraw, $user, '17/06/2009 00:00:00');
		
		$xml_dataset = $this->createXMLDataSet('data_files/withdraw_adjustment-after-cancel.xml');
		$this->assertDataSetsEqual($xml_dataset,
			$this->getConnection()->createDataSet(array('withdraw_adjustment', 'withdraw_adjustment_cancelled')));
	}
}

class InvoiceTransactionLogDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice_transaction_log-seed.xml');
	}
	
	public function testInsert(){
		InvoiceTransactionLogDAM::insert('A021', '1003', '05/05/2011 10:10:05', 12.12, 'ANULADO');
		InvoiceTransactionLogDAM::insert('A022', '2002', '21/05/2011 11:15:00', 220.15, 'EMITIDO');
		
		$xml_dataset = $this->createXMLDataSet('data_files/invoice_transaction_log-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('invoice_transaction_log')),
				array('invoice_transaction_log' => array('entry_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class ResolutionLogDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/resolution_log-seed.xml');
	}
	
	public function testInsert(){
		$correlative = CorrelativeDAM::getInstance(1);
		ResolutionLogDAM::insert($correlative, 'FACTURA');
		$correlative = CorrelativeDAM::getInstance(2);
		ResolutionLogDAM::insert($correlative, 'FACTURA');
		
		$xml_dataset = $this->createXMLDataSet('data_files/resolution_log-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('resolution_log')),
				array('resolution_log' => array('entry_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}
?>