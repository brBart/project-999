<?php
require_once('business/document_search.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');

class DepositSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/deposit-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('deposit_id' => '1', 'created_date' => '12/07/2008'),
				array('deposit_id' => '2', 'created_date' => '22/07/2008'),
				array('deposit_id' => '3', 'created_date' => '12/08/2008'),
				array('deposit_id' => '4', 'created_date' => '22/08/2008'));
		$data_list = DepositSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('deposit_id' => '5', 'created_date' => '12/09/2008'),
				array('deposit_id' => '6', 'created_date' => '22/09/2008'),
				array('deposit_id' => '7', 'created_date' => '12/10/2008'),
				array('deposit_id' => '8', 'created_date' => '22/10/2008'));
		$data_list = DepositSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('deposit_id' => '9', 'created_date' => '12/11/2008'),
				array('deposit_id' => '10', 'created_date' => '22/11/2008'));
		$data_list = DepositSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('deposit_id' => '1', 'created_date' => '12/07/2008'),
				array('deposit_id' => '2', 'created_date' => '22/07/2008'));
		$data_list = DepositSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = DepositSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = DepositSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('deposit_id' => '1', 'created_date' => '12/07/2008'));
		$data_list = DepositSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class DepositByWorkingDaySearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/deposit-by_working_day_seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'working_day' => '12/07/2008'),
				array('id' => '2', 'working_day' => '22/07/2008'),
				array('id' => '3', 'working_day' => '12/08/2008'),
				array('id' => '4', 'working_day' => '22/08/2008'));
		$data_list = DepositByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'working_day' => '12/09/2008'),
				array('id' => '6', 'working_day' => '22/09/2008'),
				array('id' => '7', 'working_day' => '12/10/2008'),
				array('id' => '8', 'working_day' => '22/10/2008'));
		$data_list = DepositByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'working_day' => '12/11/2008'),
				array('id' => '10', 'working_day' => '22/11/2008'));
		$data_list = DepositByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'working_day' => '12/07/2008'),
				array('id' => '2', 'working_day' => '22/07/2008'));
		$data_list = DepositByWorkingDaySearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = DepositByWorkingDaySearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = DepositByWorkingDaySearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'working_day' => '12/07/2008'));
		$data_list = DepositByWorkingDaySearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class ComparisonSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/comparison-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = ComparisonSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = ComparisonSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = ComparisonSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = ComparisonSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = ComparisonSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = ComparisonSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = ComparisonSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class CountSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/count-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = CountSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = CountSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = CountSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = CountSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = CountSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = CountSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = CountSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class PurchaseReturnSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/purchase_return-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = PurchaseReturnSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = PurchaseReturnSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = PurchaseReturnSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = PurchaseReturnSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = PurchaseReturnSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = PurchaseReturnSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = PurchaseReturnSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class ShipmentSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/shipment-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = ShipmentSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = ShipmentSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = ShipmentSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = ShipmentSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = ShipmentSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = ShipmentSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = ShipmentSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class InvoiceSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'created_date' => '12/07/2008'),
				array('invoice_id' => '2', 'serial_number' => 'A021', 'number' => '2',
				'created_date' => '22/07/2008'),
				array('invoice_id' => '3', 'serial_number' => 'A021', 'number' => '3',
				'created_date' => '12/08/2008'),
				array('invoice_id' => '4', 'serial_number' => 'A021', 'number' => '4',
				'created_date' => '22/08/2008'));
		$data_list = InvoiceSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('invoice_id' => '5', 'serial_number' => 'A021', 'number' => '5',
				'created_date' => '12/09/2008'),
				array('invoice_id' => '6', 'serial_number' => 'A021', 'number' => '6',
				'created_date' => '22/09/2008'),
				array('invoice_id' => '7', 'serial_number' => 'A021', 'number' => '7',
				'created_date' => '12/10/2008'),
				array('invoice_id' => '8', 'serial_number' => 'A021', 'number' => '8',
				'created_date' => '22/10/2008'));
		$data_list = InvoiceSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('invoice_id' => '9', 'serial_number' => 'A021', 'number' => '9',
				'created_date' => '12/11/2008'),
				array('invoice_id' => '10', 'serial_number' => 'A021', 'number' => '10',
				'created_date' => '22/11/2008'));
		$data_list = InvoiceSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}

	public function testSearch_4(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'created_date' => '12/07/2008'),
				array('invoice_id' => '2', 'serial_number' => 'A021', 'number' => '2',
				'created_date' => '22/07/2008'));
		$data_list = InvoiceSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = InvoiceSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = InvoiceSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'created_date' => '12/07/2008'));
		$data_list = InvoiceSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class InvoiceByWorkingDaySearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice-by_working_day_seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'working_day' => '12/07/2008'),
				array('invoice_id' => '2', 'serial_number' => 'A021', 'number' => '2',
				'working_day' => '22/07/2008'),
				array('invoice_id' => '3', 'serial_number' => 'A021', 'number' => '3',
				'working_day' => '12/08/2008'),
				array('invoice_id' => '4', 'serial_number' => 'A021', 'number' => '4',
				'working_day' => '22/08/2008'));
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('invoice_id' => '5', 'serial_number' => 'A021', 'number' => '5',
				'working_day' => '12/09/2008'),
				array('invoice_id' => '6', 'serial_number' => 'A021', 'number' => '6',
				'working_day' => '22/09/2008'),
				array('invoice_id' => '7', 'serial_number' => 'A021', 'number' => '7',
				'working_day' => '12/10/2008'),
				array('invoice_id' => '8', 'serial_number' => 'A021', 'number' => '8',
				'working_day' => '22/10/2008'));
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('invoice_id' => '9', 'serial_number' => 'A021', 'number' => '9',
				'working_day' => '12/11/2008'),
				array('invoice_id' => '10', 'serial_number' => 'A021', 'number' => '10',
				'working_day' => '22/11/2008'));
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'working_day' => '12/07/2008'),
				array('invoice_id' => '2', 'serial_number' => 'A021', 'number' => '2',
				'working_day' => '22/07/2008'));
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = InvoiceByWorkingDaySearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('invoice_id' => '1', 'serial_number' => 'A021', 'number' => '1',
				'working_day' => '12/07/2008'));
		$data_list = InvoiceByWorkingDaySearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class ReceiptSearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/receipt-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = ReceiptSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = ReceiptSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = ReceiptSearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = ReceiptSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = ReceiptSearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = ReceiptSearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = ReceiptSearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class EntryIASearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/entry_adjustment-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = EntryIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = EntryIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = EntryIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = EntryIASearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = EntryIASearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = EntryIASearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = EntryIASearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}

class WithdrawIASearchDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/withdraw_adjustment-seed.xml');
	}
	
	public function testSearch(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'),
				array('id' => '3', 'created_date' => '12/08/2008'),
				array('id' => '4', 'created_date' => '22/08/2008'));
		$data_list = WithdrawIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_2(){
		$list = array(array('id' => '5', 'created_date' => '12/09/2008'),
				array('id' => '6', 'created_date' => '22/09/2008'),
				array('id' => '7', 'created_date' => '12/10/2008'),
				array('id' => '8', 'created_date' => '22/10/2008'));
		$data_list = WithdrawIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_3(){
		$list = array(array('id' => '9', 'created_date' => '12/11/2008'),
				array('id' => '10', 'created_date' => '22/11/2008'));
		$data_list = WithdrawIASearchDAM::search('2008/06/01', '2008/12/01', $pages, $items, 3);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(3, $pages);
		$this->assertEquals(10, $items);
	}
	
	public function testSearch_4(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'),
				array('id' => '2', 'created_date' => '22/07/2008'));
		$data_list = WithdrawIASearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_5(){
		$data_list = WithdrawIASearchDAM::search('2008/07/01', '2008/08/01', $pages, $items, 2);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testSearch_6(){
		$data_list = WithdrawIASearchDAM::search('2009/01/01', '2009/02/01', $pages, $items, 1);
		$this->assertEquals(0, count($data_list));
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testSearch_7(){
		$list = array(array('id' => '1', 'created_date' => '12/07/2008'));
		$data_list = WithdrawIASearchDAM::search('2008/07/12', '2008/07/12', $pages, $items, 1);
		$this->assertEquals($list, $data_list);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
	}
}
?>