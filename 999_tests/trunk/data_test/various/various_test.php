<?php
require_once('business/various.php');
require_once('PHPUnit/Extensions/Database/TestCase.php');

class CompanyDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/company-seed.xml');
	}
	
	public function testGetInstance(){
		$company = CompanyDAM::getInstance();
		$this->assertEquals('35-2', $company->getNit());
		$this->assertEquals('Jose Gil', $company->getName());
		$this->assertEquals('Comercial y Drogueria', $company->getCorporateName());
	}
	
	public function testUpdate(){
		$company = CompanyDAM::getInstance();
		$company->setNit('1735-5');
		$company->setName('Infodes');
		$company->setCorporateName('Infodes S.A.');
		$company->setTelephone('123');
		$company->setAddress('zona');
		$company->setWarehouseName('999 Chiqui');
		CompanyDAM::update($company);
		
		$xml_dataset = $this->createXMLDataSet('data_files/company-after-update.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('company')));
	}
}

class ChangePriceListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/change_price_log-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('logged_date' => '22/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'last_price' => '12.12', 'new_price' => '23.23'),
				array('logged_date' => '23/06/2009 00:00:00', 'username' => 'edglem',
				'bar_code' => '543', 'manufacturer' => 'Mattel', 'name' => 'Pringles',
				'last_price' => '1.00', 'new_price' => '2.00'),
				array('logged_date' => '25/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'last_price' => '12.12', 'new_price' => '23.23'),
				array('logged_date' => '26/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'last_price' => '1.00', 'new_price' => '2.00'));
				
		$data_list = ChangePriceListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('logged_date' => '28/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '54321', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'last_price' => '12.12', 'new_price' => '23.23'),
				array('logged_date' => '29/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '543', 'manufacturer' => 'Mattel', 'name' => 'Pringles',
				'last_price' => '1.00', 'new_price' => '2.00'));
				
		$data_list = ChangePriceListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 2);
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$list = array(array('logged_date' => '29/06/2009 00:00:00', 'username' => 'roboli',
				'bar_code' => '543', 'manufacturer' => 'Mattel', 'name' => 'Pringles',
				'last_price' => '1.00', 'new_price' => '2.00'));
				
		$data_list = ChangePriceListDAM::getList('29/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(1, $pages);
		$this->assertEquals(1, $items);
		$this->assertEquals($list, $data_list);
	}
}

class DiscountListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/discount-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('created_date' => '22/06/2009 10:00:00', 'username' => 'roboli',
				'serial_number' => 'A021', 'number' => '123', 'subtotal' => '120.00', 'percentage' => '10.00',
				'amount' => '12.00', 'total' => '108.00'),
				array('created_date' => '23/06/2009 10:00:00', 'username' => 'edglem',
				'serial_number' => 'A021', 'number' => '124', 'subtotal' => '817.72', 'percentage' => '25.00',
				'amount' => '204.43', 'total' => '613.29'),
				array('created_date' => '24/06/2009 10:00:00', 'username' => 'edglem',
				'serial_number' => 'A021', 'number' => '125', 'subtotal' => '543.90', 'percentage' => '25.00',
				'amount' => '135.98', 'total' => '407.93'),
				array('created_date' => '25/06/2009 10:00:00', 'username' => 'edglem',
				'serial_number' => 'A021', 'number' => '126', 'subtotal' => '328.32', 'percentage' => '15.00',
				'amount' => '49.25', 'total' => '279.07'));
				
		$data_list = DiscountListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('created_date' => $data_list[$i]['created_date'], 
					'username' => $data_list[$i]['username'],
					'serial_number' => $data_list[$i]['serial_number'], 'number' => $data_list[$i]['number'],
					'subtotal' => $data_list[$i]['subtotal'], 'percentage' => $data_list[$i]['percentage'],
					'amount' => number_format($data_list[$i]['amount'], 2),
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_2(){
		$list = array(array('created_date' => '28/06/2009 10:00:00', 'username' => 'roboli',
				'serial_number' => 'A021', 'number' => '128', 'subtotal' => '150.00', 'percentage' => '10.00',
				'amount' => '15.00', 'total' => '135.00'));
				
		$data_list = DiscountListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 2);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('created_date' => $data_list[$i]['created_date'], 
					'username' => $data_list[$i]['username'],
					'serial_number' => $data_list[$i]['serial_number'], 'number' => $data_list[$i]['number'],
					'subtotal' => $data_list[$i]['subtotal'], 'percentage' => $data_list[$i]['percentage'],
					'amount' => number_format($data_list[$i]['amount'], 2),
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(DiscountListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class CancelDocumentListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cancel_document-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('cancelled_date' => '21/06/2009 00:00:01', 'username' => 'roboli',
				'document' => 'Devolucion', 'number' => '3', 'total' => '33.44'),
				array('cancelled_date' => '21/06/2009 00:00:05', 'username' => 'roboli',
				'document' => 'Vale Entrada', 'number' => '2', 'total' => '33.44'),
				array('cancelled_date' => '22/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Factura', 'number' => 'A021-14230', 'total' => '123.21'),
				array('cancelled_date' => '22/06/2009 00:00:05', 'username' => 'roboli',
				'document' => 'Recibo', 'number' => '2', 'total' => '33.44'));
				
		$data_list = CancelDocumentListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('cancelled_date' => $data_list[$i]['cancelled_date'], 
					'username' => $data_list[$i]['username'],
					'document' => $data_list[$i]['document'],
					'number' => $data_list[$i]['number'],
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_2(){
		$list = array(array('cancelled_date' => '23/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Envio', 'number' => '2', 'total' => '33.44'),
				array('cancelled_date' => '23/06/2009 00:00:05', 'username' => 'roboli',
				'document' => 'Vale Salida', 'number' => '2', 'total' => '33.44'));
				
		$data_list = CancelDocumentListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 2);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('cancelled_date' => $data_list[$i]['cancelled_date'], 
					'username' => $data_list[$i]['username'],
					'document' => $data_list[$i]['document'],
					'number' => $data_list[$i]['number'],
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(CancelDocumentListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class CancelCashDocumentListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/cancel_cash_document-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('cancelled_date' => '20/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Deposito', 'number' => '3', 'total' => '500.00'),
				array('cancelled_date' => '21/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Factura', 'number' => 'A021-12346', 'total' => '123.21'),
				array('cancelled_date' => '22/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Factura', 'number' => 'A021-14230', 'total' => '123.21'),
				array('cancelled_date' => '25/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Factura', 'number' => 'A021-1230', 'total' => '123.21'));
				
		$data_list = CancelCashDocumentListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('cancelled_date' => $data_list[$i]['cancelled_date'], 
					'username' => $data_list[$i]['username'],
					'document' => $data_list[$i]['document'],
					'number' => $data_list[$i]['number'],
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_2(){
		$list = array(array('cancelled_date' => '26/06/2009 00:00:00', 'username' => 'roboli',
				'document' => 'Deposito', 'number' => '4', 'total' => '500.00'));
				
		$data_list = CancelCashDocumentListDAM::getList('20/06/2009', '30/06/2009', $pages, $items, 2);
		
		for($i = 0; $i < count($data_list); $i++)
			$new_list[] = array('cancelled_date' => $data_list[$i]['cancelled_date'], 
					'username' => $data_list[$i]['username'],
					'document' => $data_list[$i]['document'],
					'number' => $data_list[$i]['number'],
					'total' => number_format($data_list[$i]['total'], 2));
		
		$this->assertEquals($list, $new_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(CancelDocumentListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class SalesRankingListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/sales_ranking-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('rank' => '1', 'bar_code' => '5433221', 'manufacturer' => 'Mattel',
				'name' => 'Transformer', 'quantity' => '27'),
				array('rank' => '2', 'bar_code' => '5433225', 'manufacturer' => 'Mattel',
				'name' => 'Lapicero', 'quantity' => '6'),
				array('rank' => '3', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'name' => 'Barby', 'quantity' => '5'),
				array('rank' => '4', 'bar_code' => '54323', 'manufacturer' => 'Mattel',
				'name' => 'Monitor','quantity' => '5'));
				
		$data_list = SalesRankingListDAM::getList('15/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('rank' => '5', 'bar_code' => '5433224', 'manufacturer' => 'Mattel',
				'name' => 'Reloj', 'quantity' => '1'));
				
		$data_list = SalesRankingListDAM::getList('15/06/2009', '30/06/2009', $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(SalesRankingListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class SalesAndPurchasesStadisticsListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/sales_and_purchases_stadistics-seed.xml');
	}
	
	public function testGetLabelsByProduct(){
		$list = array(array('id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'product' => 'Barby'),
				array('id' => '5', 'bar_code' => '5433225', 'manufacturer' => 'Compaq',
				'product' => 'Lapicero'),
				array('id' => '3', 'bar_code' => '54323', 'manufacturer' => 'Microsoft',
				'product' => 'Monitor'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByProduct('Barby', 'Monitor', $pages, $items, 1);
		$this->assertEquals(1, $pages);
		$this->assertEquals(3, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByProduct_2(){
		$list = array(array('id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'product' => 'Barby'),
				array('id' => '5', 'bar_code' => '5433225', 'manufacturer' => 'Compaq',
				'product' => 'Lapicero'),
				array('id' => '3', 'bar_code' => '54323', 'manufacturer' => 'Microsoft',
				'product' => 'Monitor'),
				array('id' => '4', 'bar_code' => '5433224', 'manufacturer' => 'HP',
				'product' => 'Reloj'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByProduct('Barby', 'Transformer', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByProduct_3(){
		$list = array(array('id' => '2', 'bar_code' => '5433221', 'manufacturer' => 'Nikon',
				'product' => 'Transformer'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByProduct('Barby', 'Transformer', $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByProduct_4(){
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByProduct('Yes', 'Zoom', $pages, $items, 1);
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testGetLabelsByManufacturer(){
		$list = array(array('id' => '5', 'bar_code' => '5433225', 'manufacturer' => 'Compaq',
				'product' => 'Lapicero'),
				array('id' => '4', 'bar_code' => '5433224', 'manufacturer' => 'HP',
				'product' => 'Reloj'),
				array('id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'product' => 'Barby'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByManufacturer('Compaq', 'Mattel', $pages, $items, 1);
		$this->assertEquals(1, $pages);
		$this->assertEquals(3, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByManufacturer_2(){
		$list = array(array('id' => '5', 'bar_code' => '5433225', 'manufacturer' => 'Compaq',
				'product' => 'Lapicero'),
				array('id' => '4', 'bar_code' => '5433224', 'manufacturer' => 'HP',
				'product' => 'Reloj'),
				array('id' => '1', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'product' => 'Barby'),
				array('id' => '3', 'bar_code' => '54323', 'manufacturer' => 'Microsoft',
				'product' => 'Monitor'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByManufacturer('Compaq', 'Nikon', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByManufacturer_3(){
		$list = array(array('id' => '2', 'bar_code' => '5433221', 'manufacturer' => 'Nikon',
				'product' => 'Transformer'));
				
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByManufacturer('Compaq', 'Nikon', $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetLabelsByManufacturer_4(){
		$data_list = SalesAndPurchasesStadisticsListDAM::getLabelsByManufacturer('Yes', 'Zoom', $pages, $items, 1);
		$this->assertEquals(0, $pages);
		$this->assertEquals(0, $items);
	}
	
	public function testGetSalesListByProduct(){
		$list = array(array('sales' => '5'),
					array('sales' => '6'),
					array('sales' => '5'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByProduct('Barby', 'Monitor', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetSalesListByProduct_2(){
		$list = array(array('sales' => '5'),
					array('sales' => '6'),
					array('sales' => '5'),
					array('sales' => '1'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByProduct('Barby', 'Transformer', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetSalesListByProduct_3(){
		$list = array(array('sales' => '27'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByProduct('Barby', 'Transformer', '01/06/2009', '01/07/2009', 3, 2);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByProduct(){
		$list = array(array('purchases' => '0'),
					array('purchases' => '0'),
					array('purchases' => '0'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByProduct('Barby', 'Monitor', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByProduct_2(){
		$list = array(array('purchases' => '0'),
					array('purchases' => '0'),
					array('purchases' => '0'),
					array('purchases' => '1'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByProduct('Barby', 'Transformer', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByProduct_3(){
		$list = array(array('purchases' => '85'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByProduct('Barby', 'Transformer', '01/06/2009', '01/07/2009', 3, 2);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetSalesListByManufacturer(){
		$list = array(array('sales' => '6'),
					array('sales' => '1'),
					array('sales' => '5'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByManufacturer('Compaq', 'Mattel', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetSalesListByManufacturer_2(){
		$list = array(array('sales' => '6'),
					array('sales' => '1'),
					array('sales' => '5'),
					array('sales' => '5'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByManufacturer('Compaq', 'Nikon', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetSalesListByManufacturer_3(){
		$list = array(array('sales' => '27'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getSalesListByManufacturer('Compaq', 'Nikon', '01/06/2009', '01/07/2009', 3, 2);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByManufacturer(){
		$list = array(array('purchases' => '0'),
					array('purchases' => '1'),
					array('purchases' => '0'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByManufacturer('Compaq', 'Mattel', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByManufacturer_2(){
		$list = array(array('purchases' => '0'),
					array('purchases' => '1'),
					array('purchases' => '0'),
					array('purchases' => '0'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByManufacturer('Compaq', 'Nikon', '01/06/2009', '01/07/2009', 3, 1);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetPurchasesListByManufacturer_3(){
		$list = array(array('purchases' => '85'));
		
		$data_list = SalesAndPurchasesStadisticsListDAM::getPurchasesListByManufacturer('Compaq', 'Nikon', '01/06/2009', '01/07/2009', 3, 2);
		
		$this->assertEquals($list, $data_list);
	}
}

class ClosingEventDAMTest extends PHPUnit_Extensions_Database_TestCase{
	private function daysDifference($endDate, $beginDate){
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
		return $this->createXMLDataSet('data_files/closing_event-seed.xml');
	}
	
	public function testApply(){
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-20');
		ClosingEventDAM::apply($days);
		
		$xml_dataset = $this->createXMLDataSet('data_files/closing_event-after-apply.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('cash_receipt',
				'bonus', 'change_price_log', 'comparison', 'comparison_product', 'count', 'count_product',
				'deposit', 'deposit_cancelled', 'deposit_cash_receipt', 'discount', 'entry_adjustment',
				'entry_adjustment_cancelled', 'entry_adjustment_lot', 'invoice', 'invoice_bonus',
				'invoice_cancelled', 'invoice_lot', 'purchase_return', 'purchase_return_cancelled',
				'purchase_return_lot', 'receipt', 'receipt_cancelled', 'receipt_lot', 'shipment',
				'shipment_cancelled', 'shipment_lot', 'voucher', 'withdraw_adjustment',
				'withdraw_adjustment_cancelled', 'withdraw_adjustment_lot', 'product')));
	}
	
	public function testApply_2(){
		$days = $this->daysDifference(date('Y-m-d'), '2009-06-16');
		ClosingEventDAM::apply($days);
		
		$xml_dataset = $this->createXMLDataSet('data_files/closing_event-after-apply_2.xml');
		$this->assertDataSetsEqual($xml_dataset, $this->getConnection()->createDataSet(array('cash_receipt',
				'bonus', 'change_price_log', 'comparison', 'comparison_product', 'count', 'count_product',
				'deposit', 'deposit_cancelled', 'deposit_cash_receipt', 'discount', 'entry_adjustment',
				'entry_adjustment_cancelled', 'entry_adjustment_lot', 'invoice', 'invoice_bonus',
				'invoice_cancelled', 'invoice_lot', 'purchase_return', 'purchase_return_cancelled',
				'purchase_return_lot', 'receipt', 'receipt_cancelled', 'receipt_lot', 'shipment',
				'shipment_cancelled', 'shipment_lot', 'voucher', 'withdraw_adjustment',
				'withdraw_adjustment_cancelled', 'withdraw_adjustment_lot', 'product')));
	}
}

class BackupEventDAMTest extends PHPUnit_Framework_TestCase{
	public function testApply(){
		$file = BackupEventDAM::apply();
		$this->assertTrue(file_exists(BACKUP_DIR . $file));
	}
}

class SalesLedgerDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/sales_ledger-seed.xml');
	}
	
	public function testGenerate(){
		$data = array(
				array('17/06/2010', 'A031', '12345', '12347', '883.99'),
				array('18/06/2010', 'A031', '12348', '12348', '123.21'),
				array('19/06/2010', 'A031', '12349', '12349', '0.00'),
				array('25/06/2010', 'A031', '12350', '12351', '23.00'),
				array('25/06/2010', 'A032', '12300', '12300', '23.00')
				);
		
		$file = SalesLedgerDAM::generate('01/06/2010', '30/06/2010');
		
		$this->assertTrue($file != '');
		
		$fp = fopen(SALES_LEDGER_DIR . $file, 'r');
		
		$file_data = array();
		
		while (($line = fgetcsv($fp)) !== FALSE) {
			$file_data[] = $line;
		}
		
		$this->assertEquals($data, $file_data);
	}
	
	public function testGenerate_Empty(){
		$file = SalesLedgerDAM::generate('01/07/2010', '30/07/2010');
		
		$this->assertTrue($file == '');
	}
}

class InvoiceTransactionListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/invoice_transaction-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('serial_number' => 'A021', 'number' => '1000', 'date' => '01/03/2010', 'total' => '150.00',
						'state' => 'EMITIDO'),
				array('serial_number' => 'A021', 'number' => '1001', 'date' => '01/03/2010', 'total' => '200.50',
						'state' => 'EMITIDO'),
				array('serial_number' => 'A021', 'number' => '1001', 'date' => '02/03/2010', 'total' => '200.50',
						'state' => 'ANULADO'),
				array('serial_number' => 'B055', 'number' => '5000', 'date' => '20/03/2010', 'total' => '15.30',
						'state' => 'EMITIDO'));
				
		$data_list = InvoiceTransactionListDAM::getList('01/03/2010', '30/03/2010', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('serial_number' => 'B055', 'number' => '5001', 'date' => '30/03/2010', 'total' => '20.00',
						'state' => 'EMITIDO'));
				
		$data_list = InvoiceTransactionListDAM::getList('01/03/2010', '30/03/2010', $pages, $items, 2);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(InvoiceTransactionListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class ResolutionListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/resolution-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('resolution_number' => '100-3930', 'resolution_date' => '05/12/2010', 'serial_number' => 'A021',
					'initial_number' => '1000', 'final_number' => '4000', 'created_date' => '01/03/2011', 'document_type' => 'FACTURA'),
				array('resolution_number' => '100-3931', 'resolution_date' => '31/12/2010', 'serial_number' => 'A021',
					'initial_number' => '5000', 'final_number' => '9999', 'created_date' => '05/03/2011', 'document_type' => 'FACTURA'),
				array('resolution_number' => '100-3932', 'resolution_date' => '01/01/2011', 'serial_number' => 'A021',
					'initial_number' => '10000', 'final_number' => '15000', 'created_date' => '15/03/2011', 'document_type' => 'FACTURA'),
				array('resolution_number' => '100-3933', 'resolution_date' => '15/01/2011', 'serial_number' => 'B055',
					'initial_number' => '5000', 'final_number' => '9999', 'created_date' => '01/04/2011', 'document_type' => 'FACTURA'));
				
		$data_list = ResolutionListDAM::getList('01/03/2011', '30/04/2011', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('resolution_number' => '100-3940', 'resolution_date' => '01/02/2011', 'serial_number' => 'B055',
					'initial_number' => '10000', 'final_number' => '15000', 'created_date' => '30/04/2011', 'document_type' => 'FACTURA'));
				
		$data_list = ResolutionListDAM::getList('01/03/2011', '30/04/2011', $pages, $items, 2);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(ResolutionListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}

class SalesSummaryListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/sales_summary-seed.xml');
	}
	
	public function testGetListByProduct(){
		$list = array(array('rank' => '1', 'bar_code' => '5433221', 'manufacturer' => 'Mattel',
				'name' => 'Transformer', 'actual_price' => '82.34', 'avg_price' => '37.23', 'quantity' => '27',
				'subtotal' => '997.05', 'bonus_total' => '-37.00', 'total' => '960.05'),
				array('rank' => '2', 'bar_code' => '5433225', 'manufacturer' => 'Mattel',
				'name' => 'Lapicero', 'actual_price' => '82.34', 'avg_price' => '10.00', 'quantity' => '6',
				'subtotal' => '60.00', 'bonus_total' => '0.00', 'total' => '60.00'),
				array('rank' => '3', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'name' => 'Barby', 'actual_price' => '82.34', 'avg_price' => '45.23',  'quantity' => '5',
				'subtotal' => '226.15', 'bonus_total' => '0.00', 'total' => '226.15'),
				array('rank' => '4', 'bar_code' => '54323', 'manufacturer' => 'Mattel',
				'name' => 'Monitor', 'actual_price' => '82.34', 'avg_price' => '45.23', 'quantity' => '5',
				'subtotal' => '226.15', 'bonus_total' => '0.00', 'total' => '226.15'));
				
		$db_list = SalesSummaryListDAM::getListByProduct('15/06/2009', '30/06/2009', $subtotal, $discount_total, $pages, $items, 1);
		
		foreach($db_list as $product){
			$data_list[] = array(
					'rank' => $product['rank'],
					'bar_code' => $product['bar_code'],
					'manufacturer' => $product['manufacturer'],
					'name' => $product['name'],
					'actual_price' => $product['actual_price'],
					'avg_price' => number_format($product['avg_price'], 2),
					'quantity' => $product['quantity'],
					'subtotal' => $product['subtotal'],
					'bonus_total' => $product['bonus_total'],
					'total' => $product['total']);
		}
		
		$this->assertEquals(1504.8, $subtotal);
		$this->assertEquals(86.76, number_format($discount_total, 2));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByProduct_2(){
		$list = array(array('rank' => '5', 'bar_code' => '5433224', 'manufacturer' => 'Mattel',
				'name' => 'Reloj', 'actual_price' => '82.34', 'avg_price' => '32.45', 'quantity' => '1',
				'subtotal' => '32.45', 'bonus_total' => '0.00', 'total' => '32.45'));
				
		$db_list = SalesSummaryListDAM::getListByProduct('15/06/2009', '30/06/2009', $subtotal, $discount_total, $pages, $items, 2);
		
		foreach($db_list as $product){
			$data_list[] = array(
				'rank' => $product['rank'],
				'bar_code' => $product['bar_code'],
				'manufacturer' => $product['manufacturer'],
				'name' => $product['name'],
				'actual_price' => $product['actual_price'],
				'avg_price' => number_format($product['avg_price'], 2),
				'quantity' => $product['quantity'],
				'subtotal' => $product['subtotal'],
				'bonus_total' => $product['bonus_total'],
				'total' => $product['total']);
		}
		
		$this->assertEquals(1504.8, $subtotal);
		$this->assertEquals(86.76, number_format($discount_total, 2));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByProduct_3(){
		$this->assertEquals(0, count(SalesSummaryListDAM::getListByProduct('01/07/2009', '15/07/2009', $subtotal, $discount_total, $pages, $items, 1)));
	}
	
	public function testGetListByUserAccount(){
		$list = array(array('rank' => '1', 'username' => 'roboli', 'name' => 'Roberto Oliveros',
				'subtotal' => '1037.73', 'discount_total' => '96.01', 'total' => '941.73'),
				array('rank' => '2', 'username' => 'josoli', 'name' => 'Jose Oliveros',
				'subtotal' => '562.43', 'discount_total' => '0.00', 'total' => '562.43'),
				array('rank' => '3', 'username' => 'lizram', 'name' => 'Lizeth Ramirez',
				'subtotal' => '484.75', 'discount_total' => '0.00', 'total' => '484.75'),
				array('rank' => '4', 'username' => 'edglem', 'name' => 'Edgar Lemus',
				'subtotal' => '197.68', 'discount_total' => '9.00', 'total' => '188.68'));
				
		$db_list = SalesSummaryListDAM::getListByUserAccount('20/06/2009', '30/07/2009', $total, $pages, $items, 1);
		
		foreach($db_list as $product){
			$data_list[] = array(
					'rank' => $product['rank'],
					'username' => $product['username'],
					'name' => $product['name'],
					'subtotal' => $product['subtotal'],
					'discount_total' => number_format($product['discount_total'], 2),
					'total' => number_format($product['total'], 2));
		}
		
		$this->assertEquals('2,210.04', number_format($total, 2));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByUserAccount_2(){
		$list = array(array('rank' => '5', 'username' => 'davcox', 'name' => 'David Coxaj',
				'subtotal' => '32.45', 'discount_total' => '0.00', 'total' => '32.45'));
				
		$db_list = SalesSummaryListDAM::getListByUserAccount('20/06/2009', '30/07/2009', $total, $pages, $items, 2);
		
		foreach($db_list as $product){
			$data_list[] = array(
					'rank' => $product['rank'],
					'username' => $product['username'],
					'name' => $product['name'],
					'subtotal' => $product['subtotal'],
					'discount_total' => number_format($product['discount_total'], 2),
					'total' => number_format($product['total'], 2));
		}
		
		$this->assertEquals('2,210.04', number_format($total, 2));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByUserAccount_3(){
		$this->assertEquals(0, count(SalesSummaryListDAM::getListByUserAccount('01/07/2009', '15/07/2009', $total, $pages, $items, 1)));
	}
}

class PurchasesSummaryListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/purchases_summary-seed.xml');
	}
	
	public function testGetListByProduct(){
		$list = array(array('rank' => '1', 'bar_code' => '5433221', 'manufacturer' => 'Mattel',
				'name' => 'Transformer', 'actual_price' => '82.34', 'avg_price' => '37.23', 'quantity' => '27',
				'total' => '997.05'),
				array('rank' => '2', 'bar_code' => '5433225', 'manufacturer' => 'Mattel',
				'name' => 'Lapicero', 'actual_price' => '82.34', 'avg_price' => '10.00', 'quantity' => '6',
				'total' => '60.00'),
				array('rank' => '3', 'bar_code' => '54321', 'manufacturer' => 'Mattel',
				'name' => 'Barby', 'actual_price' => '82.34', 'avg_price' => '45.23',  'quantity' => '5',
				'total' => '226.15'),
				array('rank' => '4', 'bar_code' => '54323', 'manufacturer' => 'Mattel',
				'name' => 'Monitor', 'actual_price' => '82.34', 'avg_price' => '45.23', 'quantity' => '5',
				'total' => '226.15'));
				
		$db_list = PurchasesSummaryListDAM::getListByProduct('15/06/2009', '30/06/2009', $total, $pages, $items, 1);
		
		foreach($db_list as $product){
			$data_list[] = array(
					'rank' => $product['rank'],
					'bar_code' => $product['bar_code'],
					'manufacturer' => $product['manufacturer'],
					'name' => $product['name'],
					'actual_price' => $product['actual_price'],
					'avg_price' => number_format($product['avg_price'], 2),
					'quantity' => $product['quantity'],
					'total' => $product['total']);
		}
		
		$this->assertEquals(1504.8, $total);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByProduct_2(){
		$list = array(array('rank' => '5', 'bar_code' => '5433224', 'manufacturer' => 'Mattel',
				'name' => 'Reloj', 'actual_price' => '82.34', 'avg_price' => '32.45', 'quantity' => '1',
				'total' => '32.45'));
				
		$db_list = PurchasesSummaryListDAM::getListByProduct('15/06/2009', '30/06/2009', $total, $pages, $items, 2);
		
		foreach($db_list as $product){
			$data_list[] = array(
				'rank' => $product['rank'],
				'bar_code' => $product['bar_code'],
				'manufacturer' => $product['manufacturer'],
				'name' => $product['name'],
				'actual_price' => $product['actual_price'],
				'avg_price' => number_format($product['avg_price'], 2),
				'quantity' => $product['quantity'],
				'total' => $product['total']);
		}
		
		$this->assertEquals(1504.8, $total);
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetListByProduct_3(){
		$this->assertEquals(0, count(PurchasesSummaryListDAM::getListByProduct('01/07/2009', '15/07/2009', $total, $pages, $items, 1)));
	}
}

class BonusCreatedListDAMTest extends PHPUnit_Extensions_Database_TestCase{
	protected function getConnection(){
		$pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		return $this->createDefaultDBConnection($pdo, '999_store');
	}
	
	protected function getDataSet(){
		return $this->createXMLDataSet('data_files/bonus_created-seed.xml');
	}
	
	public function testGetList(){
		$list = array(array('bar_code' => '3242', 'manufacturer' => 'Mattel', 'name' => 'Shampoo',
				'quantity' => '3', 'percentage' => '5.00', 'created_date' => '18/06/2009', 'expiration_date' => '01/01/2015', 'username' => 'roboli'),
				array('bar_code' => '3242', 'manufacturer' => 'Mattel', 'name' => 'Shampoo',
				'quantity' => '8', 'percentage' => '10.00', 'created_date' => '19/06/2009', 'expiration_date' => '01/02/2015', 'username' => 'josoli'),
				array('bar_code' => '3242', 'manufacturer' => 'Mattel', 'name' => 'Shampoo',
				'quantity' => '15', 'percentage' => '15.00', 'created_date' => '20/06/2009', 'expiration_date' => '01/04/2015', 'username' => 'roboli'),
				array('bar_code' => '32425', 'manufacturer' => 'Mattel', 'name' => 'Transformer',
				'quantity' => '3', 'percentage' => '15.00', 'created_date' => '21/06/2009', 'expiration_date' => '01/01/2015', 'username' => 'roboli'));
				
		$data_list = BonusCreatedListDAM::getList('15/06/2009', '30/06/2009', $pages, $items, 1);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_2(){
		$list = array(array('bar_code' => '32425', 'manufacturer' => 'Mattel', 'name' => 'Transformer',
				'quantity' => '10', 'percentage' => '25.00', 'created_date' => '21/06/2009', 'expiration_date' => '01/01/2015', 'username' => 'josoli'),
				array('bar_code' => '32425', 'manufacturer' => 'Mattel', 'name' => 'Transformer',
				'quantity' => '18', 'percentage' => '35.00', 'created_date' => '25/06/2009', 'expiration_date' => '01/01/2015', 'username' => 'roboli'));
				
		$data_list = BonusCreatedListDAM::getList('15/06/2009', '30/06/2009', $pages, $items, 2);
		$this->assertEquals(2, $pages);
		$this->assertEquals(6, $items);
		
		$this->assertEquals($list, $data_list);
	}
	
	public function testGetList_3(){
		$this->assertEquals(0, count(BonusCreatedListDAM::getList('15/07/2009', '20/07/2009', $pages, $items, 1)));
	}
}
?>