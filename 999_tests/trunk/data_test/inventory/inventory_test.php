<?php
require_once('business/inventory.php');
require_once('business/product.php');
require_once('business/user_account.php');
require_once('business/agent.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('PHPUnit/Extensions/Database/DataSet/DataSetFilter.php');

class CountingTemplateDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/counting_template-seed.xml');
	}
	
	public function testGetDataByProduct(){
		$list = CountingTemplateDAM::getDataByProduct('Chino Karate', 'Reloj Pulsera');
		$this->assertEquals(12, count($list));
		
		$this->assertEquals(4, $list[3]['id']);
		$this->assertEquals('908983422', $list[3]['bar_code']);
		$this->assertEquals('Bayer', $list[3]['manufacturer']);
		$this->assertEquals('Juan Talaber', $list[3]['product']);
		
		$this->assertEquals(26, $list[9]['id']);
		$this->assertEquals('9348029850', $list[9]['bar_code']);
		$this->assertEquals('Abbot', $list[9]['manufacturer']);
		$this->assertEquals('Porta vasos', $list[9]['product']);
	}
	
	public function testGetDataByManufacturer(){
		$list = CountingTemplateDAM::getDataByManufacturer('Bretton', 'Western');
		$this->assertEquals(17, count($list));
		
		$this->assertEquals(1, $list[3]['id']);
		$this->assertEquals('54321', $list[3]['bar_code']);
		$this->assertEquals('Mattel', $list[3]['manufacturer']);
		$this->assertEquals('Barby', $list[3]['product']);
		
		$this->assertEquals(31, $list[14]['id']);
		$this->assertEquals('328238928', $list[14]['bar_code']);
		$this->assertEquals('Procter & Gamble', $list[14]['manufacturer']);
		$this->assertEquals('Ventano', $list[14]['product']);
	}
}

class CountDetailDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/count_product-seed.xml');
	}
	
	public function testInsert(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(1, NULL, $user);
		$product = ProductDAM::getInstance(1);
		$detail = new CountDetail($product, 5);
		CountDetailDAM::insert($count, $detail);
		
		$product = ProductDAM::getInstance(2);
		$detail = new CountDetail($product, 7);
		CountDetailDAM::insert($count, $detail);
		
		$xml_dataset = $this->createXMLDataSet('data_files/count_product-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('count_product')),
				array('count_product' => array('count_product_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testDelete(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(1, NULL, $user);
		$product = ProductDAM::getInstance(1);
		$detail = new CountDetail($product, 5);
		CountDetailDAM::insert($count, $detail);
		
		CountDetailDAM::delete($count, $detail);
		$xml_dataset = $this->createXMLDataSet('data_files/count_product-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('count_product')));
	}
}

class CountDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/count-seed.xml');
	}
	
	public function testInsert(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(NULL, '19/06/2009 12:00:00', $user);
		$count->setData('simoncho.', 23, $details[] = 'uno');
		$this->assertGreaterThan(0, CountDAM::insert($count));
		
		$count = new Count(NULL, '20/06/2009 12:00:00', $user);
		$count->setData('yeah!!', 65, $details);
		$this->assertGreaterThan(0, CountDAM::insert($count));
		
		$xml_dataset = $this->createXMLDataSet('data_files/count-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('count')),
				array('count' => array('count_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testUpdate(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(NULL, '19/06/2009 12:00:00', $user);
		$count->setData('simoncho.', 23, $details[] = 'uno');
		$id = CountDAM::insert($count);
		
		$count = new Count($id, NULL, $user);
		$count->setData('simoncho.', 50, $details);
		CountDAM::update($count);
		
		$xml_dataset = $this->createXMLDataSet('data_files/count-after-update.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('count')),
				array('count' => array('count_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class CountDAMGetInstanceTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/count-get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = CountDAM::getInstance(1);
		
		$this->assertEquals(1, $count->getId());
		$this->assertEquals(Persist::CREATED, $count->getStatus());
		$this->assertEquals($user, $count->getUser());
		$this->assertEquals('19/06/2009 12:00:00', $count->getDateTime());
		$this->assertEquals('simoncho.', $count->getReason());
		$this->assertEquals(23, $count->getTotal());
		
		$product1 = ProductDAM::getInstance(1);
		$product2 = ProductDAM::getInstance(2);
		$product3 = ProductDAM::getInstance(3);
		
		$details = $count->getDetails();
		
		$this->assertEquals($product1, $details[0]->getProduct());
		$this->assertEquals(5, $details[0]->getQuantity());
		
		$this->assertEquals($product2, $details[1]->getProduct());
		$this->assertEquals(7, $details[1]->getQuantity());
		
		$this->assertEquals($product3, $details[2]->getProduct());
		$this->assertEquals(7, $details[2]->getQuantity());
		
		$count = CountDAM::getInstance(2);
		$details = $count->getDetails();
		
		$this->assertEquals($product1, $details[0]->getProduct());
		$this->assertEquals(7, $details[0]->getQuantity());
		
		$this->assertEquals($product2, $details[1]->getProduct());
		$this->assertEquals(12, $details[1]->getQuantity());
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(CountDAM::getInstance(99));
	}
	
	public function testDelete(){
		$count = CountDAM::getInstance(1);
		CountDAM::delete($count);
		
		$xml_dataset = $this->createXMLDataSet('data_files/count-after-delete.xml');
		$this->assertDataSetsEqual($xml_dataset,
				$this->getConnection()->createDataSet(array('count', 'count_product')));
	}
}

class ComparisonDAMInsertTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/comparison_insert-seed.xml');
	}
	
	public function testInsert(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(1, NULL, $user);
		$count->setData('as', 48, $details[] = 'uno');
		ComparisonDAM::insert('19/06/2009 12:00:00', $user, $count, 'pq simoncho.', false);
		
		$count = new Count(2, NULL, $user);
		$count->setData('as', 6, $details);
		ComparisonDAM::insert('20/06/2009 12:00:00', $user, $count, 'simonchosito.', false);
		
		$xml_dataset = $this->createXMLDataSet('data_files/comparison-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('comparison', 'comparison_product')),
				array('comparison' => array('comparison_id'),
				'comparison_product' => array('comparison_product_id', 'comparison_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
	
	public function testInserGeneral(){
		$user = UserAccountDAM::getInstance('roboli');
		$count = new Count(1, NULL, $user);
		$count->setData('as', 48, $details[] = 'uno');
		ComparisonDAM::insert('19/06/2009 12:00:00', $user, $count, 'pq simoncho.', true);
		
		$count = new Count(2, NULL, $user);
		$count->setData('as', 6, $details);
		ComparisonDAM::insert('20/06/2009 12:00:00', $user, $count, 'simonchosito.', true);
		
		$xml_dataset = $this->createXMLDataSet('data_files/comparison_general-after-inserts.xml');
		$database_dataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter(
				$this->getConnection()->createDataSet(array('comparison', 'comparison_product')),
				array('comparison' => array('comparison_id'),
				'comparison_product' => array('comparison_product_id', 'comparison_id')));
		$this->assertDataSetsEqual($xml_dataset, $database_dataset);
	}
}

class ComparisonDAMGetInstanceTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/comparison_get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$comparison = ComparisonDAM::getInstance(1, $pages, $items, 1);
		
		$this->assertEquals(1, $comparison->getId());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('19/06/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals('pq simoncho.', $comparison->getReason());
		$this->assertFalse($comparison->isGeneral());
		$this->assertEquals(48, $comparison->getPhysicalTotal());
		$this->assertEquals(47, $comparison->getSystemTotal());
		
		$details = $comparison->getDetails();
		
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(10), 5, 2), $details[0]);
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(2), 7, 11), $details[1]);
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(15), 19, 34), $details[2]);
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(21), 17, 0), $details[3]);
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(1), 1, 0), $details[4]);
		$this->assertEquals(new ComparisonDetail(ProductDAM::getInstance(30), 5, 74), $details[5]);
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ComparisonDAM::getInstance(99));
	}
}

class ComparisonDAMExistsTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/comparison_exists-seed.xml');
	}
	
	public function testExists(){
		$this->assertTrue(ComparisonDAM::exists(123));
	}
	
	public function testExists_2(){
		$this->assertFalse(ComparisonDAM::exists(122));
	}
}

class ComparisonFilterDAMGetInstanceTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/comparison_get_instance-seed.xml');
	}
	
	public function testGetInstance(){
		$user = UserAccountDAM::getInstance('roboli');
		$comparison = ComparisonFilterDAM::getInstance(1, ComparisonFilter::FILTER_NONE, true);
		
		$this->assertEquals(1, $comparison->getId());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('19/06/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals('pq simoncho.', $comparison->getReason());
		$this->assertFalse($comparison->isGeneral());
		$this->assertEquals(48, $comparison->getPhysicalTotal());
		$this->assertEquals(47, $comparison->getSystemTotal());
		$this->assertTrue($comparison->includePrices());
		$this->assertEquals('-23133.96', $comparison->getPriceTotal());
		
		$details = $comparison->getDetails();
		
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(10), 5, 2), $details[0]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(2), 7, 11), $details[1]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(15), 19, 34), $details[2]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(21), 17, 0), $details[3]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(1), 1, 0), $details[4]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(30), 5, 74), $details[5]);
	}
	
	public function testGetInstance_NoPrice(){
		$user = UserAccountDAM::getInstance('roboli');
		$comparison = ComparisonFilterDAM::getInstance(1, ComparisonFilter::FILTER_NONE, false);
		
		$this->assertFalse($comparison->includePrices());
		$this->assertEquals('0.00', $comparison->getPriceTotal());
	}
	
	public function testGetInstance_Positives(){
		$user = UserAccountDAM::getInstance('roboli');
		$comparison = ComparisonFilterDAM::getInstance(1, ComparisonFilter::FILTER_POSITIVES, true);
		
		$this->assertEquals(1, $comparison->getId());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('19/06/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals('pq simoncho.', $comparison->getReason());
		$this->assertFalse($comparison->isGeneral());
		$this->assertEquals(23, $comparison->getPhysicalTotal());
		$this->assertEquals(2, $comparison->getSystemTotal());
		$this->assertTrue($comparison->includePrices());
		$this->assertEquals('+7501.13', $comparison->getPriceTotal());
		
		$details = $comparison->getDetails();
		
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(10), 5, 2), $details[0]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(21), 17, 0), $details[1]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(1), 1, 0), $details[2]);
	}
	
	public function testGetInstance_Negatives(){
		$user = UserAccountDAM::getInstance('roboli');
		$comparison = ComparisonFilterDAM::getInstance(1, ComparisonFilter::FILTER_NEGATIVES, true);
		
		$this->assertEquals(1, $comparison->getId());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('19/06/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals('pq simoncho.', $comparison->getReason());
		$this->assertFalse($comparison->isGeneral());
		$this->assertEquals(31, $comparison->getPhysicalTotal());
		$this->assertEquals(119, $comparison->getSystemTotal());
		$this->assertTrue($comparison->includePrices());
		$this->assertEquals('-30635.09', $comparison->getPriceTotal());
		
		$details = $comparison->getDetails();
		
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(2), 7, 11), $details[0]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(15), 19, 34), $details[1]);
		$this->assertEquals(new ComparisonFilterDetail(ProductDAM::getInstance(30), 5, 74), $details[2]);
	}
	
	public function testGetInstance_NonExistent(){
		$this->assertNull(ComparisonFilterDAM::getInstance(99, ComparisonFilter::FILTER_NONE, false));
	}
}
?>