<?php
require_once('business/various.php');
require_once('PHPUnit/Framework/TestCase.php');

class ClosingEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$this->assertEquals('hola.sql', ClosingEvent::apply(3));
	}
	
	public function testApply_BadMonthsTxt(){
		try{
			ClosingEvent::apply('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testApply_BadMonthsNoPositive(){
		try{
			ClosingEvent::apply(0);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class BackupEventTest extends PHPUnit_Framework_TestCase{
	public function testApply(){
		$this->assertEquals('testo.sql', BackupEvent::apply());
	}
}

class CompanyTest extends PHPUnit_Framework_TestCase{
	private $_mCompany;
	
	public function setUp(){
		$this->_mCompany = new Company('1725045-5', 'Infodes', 'Infodes S.A.', '34322332',
				'zona 1', 'Tienda 999');
	}
	
	public function testConstructor(){
		$company = new Company('350682-7', 'Jose Gil', 'Comercial Gil', '123', 'zona', 'Tienda 999');
		$this->assertEquals('350682-7', $company->getNit());
		$this->assertEquals('Jose Gil', $company->getName());
		$this->assertEquals('Comercial Gil', $company->getCorporateName());
		$this->assertEquals('123', $company->getTelephone());
		$this->assertEquals('zona', $company->getAddress());
		$this->assertEquals('Tienda 999', $company->getWarehouseName());
	}
	
	public function testConstructor_BadNit(){
		try{
			$company = new Company('sab', 'Jose Gil', 'Comercial Gil', '123', 'zona', 'Tienda 999');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankName(){
		try{
			$company = new Company('1725045-5', '', 'Comercial Gil', '123', 'zona', 'Tienda 999');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankCorporateName(){
		try{
			$company = new Company('1725045-5', 'Jose Gil', '', '123', 'zona', 'Tienda 999');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankTelephone(){
		try{
			$company = new Company('1725045-5', 'Jose Gil', 'Comercial Gil', '', 'zona', 'Tienda 999');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankAddress(){
		try{
			$company = new Company('1725045-5', 'Jose Gil', 'Comercial Gil', '123', '', 'Tienda 999');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankWarehouseName(){
		try{
			$company = new Company('1725045-5', 'Jose Gil', 'Comercial Gil', '123', 'zona', '');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetNit(){
		$this->_mCompany->setNit('123-4');
		$this->assertEquals('123-4', $this->_mCompany->getNit());
	}
	
	public function testSetNit_Bad(){
		try{
			$this->_mCompany->setNit('kw');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetName(){
		$this->_mCompany->setName('Cemaco');
		$this->assertEquals('Cemaco', $this->_mCompany->getName());
	}
	
	public function testSetName_Blank(){
		try{
			$this->_mCompany->setName('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetCorporateName(){
		$this->_mCompany->setCorporateName('Cemaco');
		$this->assertEquals('Cemaco', $this->_mCompany->getCorporateName());
	}
	
	public function testSetCorporateName_Blank(){
		try{
			$this->_mCompany->setCorporateName('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetTelephone(){
		$this->_mCompany->setTelephone('123');
		$this->assertEquals('123', $this->_mCompany->getTelephone());
	}
	
	public function testSetTelephone_Blank(){
		try{
			$this->_mCompany->setTelephone('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetAddress(){
		$this->_mCompany->setAddress('zona');
		$this->assertEquals('zona', $this->_mCompany->getAddress());
	}
	
	public function testSetAddress_Blank(){
		try{
			$this->_mCompany->setAddress('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetWarehouseName(){
		$this->_mCompany->setWarehouseName('999 chiqui');
		$this->assertEquals('999 chiqui', $this->_mCompany->getWarehouseName());
	}
	
	public function testSetWarehouseName_Blank(){
		try{
			$this->_mCompany->setWarehouseName('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave(){
		$this->_mCompany->save();
	}
	
	public function testGetInstance(){
		$company = Company::getInstance();
		$this->assertEquals('1725045-5', $company->getNit());
	}
}

class ChangePriceListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = ChangePriceList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = ChangePriceList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = ChangePriceList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = ChangePriceList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = ChangePriceList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = ChangePriceList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class DiscountListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = DiscountList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = DiscountList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = DiscountList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = DiscountList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = DiscountList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = DiscountList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class CancelDocumentListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = CancelDocumentList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = CancelDocumentList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = CancelDocumentList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = CancelDocumentList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = CancelDocumentList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = CancelDocumentList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class CancelCashDocumentListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = CancelCashDocumentList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = CancelCashDocumentList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = CancelCashDocumentList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = CancelCashDocumentList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = CancelCashDocumentList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = CancelCashDocumentList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class SalesRankingListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = SalesRankingList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = SalesRankingList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = SalesRankingList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = SalesRankingList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = SalesRankingList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = SalesRankingList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class SalesAndPurchasesStadisticsListTest extends PHPUnit_Framework_TestCase{
	public function testGetListByProduct(){
		$data = SalesAndPurchasesStadisticsList::getListByProduct('first', 'last', date('d/m/Y'), '3', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(3, count($data[0]['sales']));
		$this->assertEquals(3, count($data[0]['purchases']));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetListByProduct_Defaults(){
		$data = SalesAndPurchasesStadisticsList::getListByProduct('first', 'last', date('d/m/Y'), '3');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetListByProduct_BlankFirst(){
		try {
			$data = SalesAndPurchasesStadisticsList::getListByProduct('', 'last', date('d/m/Y'), '3');
		} catch (Exception $e) { return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BlankLast(){
		try {
			$data = SalesAndPurchasesStadisticsList::getListByProduct('first', '', date('d/m/Y'), '3');
		} catch (Exception $e) { return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByManufacturer(){
		$data = SalesAndPurchasesStadisticsList::getListByManufacturer('first', 'last', date('d/m/Y'), '3', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(3, count($data[0]['sales']));
		$this->assertEquals(3, count($data[0]['purchases']));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetListByManufacturer_Defaults(){
		$data = SalesAndPurchasesStadisticsList::getListByManufacturer('first', 'last', date('d/m/Y'), '3');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetListByManufacturer_BlankFirst(){
		try {
			$data = SalesAndPurchasesStadisticsList::getListByManufacturer('', 'last', date('d/m/Y'), '3');
		} catch (Exception $e) { return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByManufacturer_BlankLast(){
		try {
			$data = SalesAndPurchasesStadisticsList::getListByManufacturer('first', '', date('d/m/Y'), '3');
		} catch (Exception $e) { return; }
		$this->fail('Exception expected.');
	}
	
	public function testBuildMonths(){
		$data = SalesAndPurchasesStadisticsList::buildMonthsNames('01/10/2010', 3);
		$this->assertEquals('Jul 10', $data[0]);
		$this->assertEquals('Ago 10', $data[1]);
		$this->assertEquals('Sep 10', $data[2]);
	}
}

class SalesLedgerTest extends PHPUnit_Framework_TestCase{
	
	public function testGenerate(){
		$file = SalesLedger::generate('12/04/2009', '15/05/2009');
		$this->assertTrue((strlen($file) > 0));
	}
	
	public function testGenerate_BadFirstDate(){
		try{
			$data = SalesLedger::generate('abs04/2009', '15/05/2009');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGenerate_BadLastDate(){
		try{
			$data = SalesLedger::generate('15/05/2009', 'abs04/2009');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class InvoiceTransactionListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = InvoiceTransactionList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = InvoiceTransactionList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = InvoiceTransactionList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = InvoiceTransactionList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = InvoiceTransactionList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = InvoiceTransactionList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ResolutionListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = ResolutionList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = ResolutionList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = ResolutionList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadLastDate(){
		try{
			$data = ResolutionList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = ResolutionList::getList('15/05/2009', '21/05/2009', $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = ResolutionList::getList('15/05/2009', '21/05/2009', $pages, $items, -3);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class SalesSummaryListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetListByProduct(){
		$data = SalesSummaryList::getListByProduct('12/04/2009', '15/05/2009', $subtotal, $discount_total, $total, $pages, $items, 1);
		$this->assertEquals(2, count($data));
		$this->assertEquals(120, $subtotal);
		$this->assertEquals(20, $discount_total);
		$this->assertEquals(100, $total);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetListByProduct_Defaults(){
		$data = SalesSummaryList::getListByProduct('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetListByProduct_BadFirstDate(){
		try{
			$data = SalesSummaryList::getListByProduct('abs04/2009', '15/05/2009', $subtotal, $discount_total, $total, $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadLastDate(){
		try{
			$data = SalesSummaryList::getListByProduct('15/05/2009', 'abs04/2009', $subtotal, $discount_total, $total, $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadPageTxt(){
		try{
			$data = SalesSummaryList::getListByProduct('15/05/2009', '21/05/2009', $subtotal, $discount_total, $total, $pages, $items, 'abc');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadPageNegative(){
		try{
			$data = SalesSummaryList::getListByProduct('15/05/2009', '21/05/2009', $subtotal, $discount_total, $total, $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByUserAccount(){
		$data = SalesSummaryList::getListByUserAccount('12/04/2009', '15/05/2009', $total,  $pages, $items, 1);
		$this->assertEquals(120, $total);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetListByUserAccount_Defaults(){
		$data = SalesSummaryList::getListByUserAccount('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetListByUserAccount_BadFirstDate(){
		try{
			$data = SalesSummaryList::getListByUserAccount('abs04/2009', '15/05/2009', $total,  $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByUserAccount_BadLastDate(){
		try{
			$data = SalesSummaryList::getListByUserAccount('15/05/2009', 'abs04/2009', $total,  $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByUserAccount_BadPageTxt(){
		try{
			$data = SalesSummaryList::getListByUserAccount('15/05/2009', '21/05/2009', $total,  $pages, $items, 'abc');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByUserAccount_BadPageNegative(){
		try{
			$data = SalesSummaryList::getListByUserAccount('15/05/2009', '21/05/2009', $total, $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class PurchasesSummaryListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetListByProduct(){
		$data = PurchasesSummaryList::getListByProduct('12/04/2009', '15/05/2009', $total, $pages, $items, 1);
		$this->assertEquals(120, $total);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetListByProduct_Defaults(){
		$data = PurchasesSummaryList::getListByProduct('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetListByProduct_BadFirstDate(){
		try{
			$data = PurchasesSummaryList::getListByProduct('abs04/2009', '15/05/2009', $total, $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadLastDate(){
		try{
			$data = PurchasesSummaryList::getListByProduct('15/05/2009', 'abs04/2009', $total, $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadPageTxt(){
		try{
			$data = PurchasesSummaryList::getListByProduct('15/05/2009', '21/05/2009', $total, $pages, $items, 'abc');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListByProduct_BadPageNegative(){
		try{
			$data = PurchasesSummaryList::getListByProduct('15/05/2009', '21/05/2009', $total, $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class BonusCreatedListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$data = BonusCreatedList::getList('12/04/2009', '15/05/2009', $pages, $items, 1);
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$data = BonusCreatedList::getList('12/04/2009', '15/05/2009');
		$this->assertEquals(2, count($data));
	}
	
	public function testGetList_BadFirstDate(){
		try{
			$data = BonusCreatedList::getList('abs04/2009', '15/05/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetListt_BadLastDate(){
		try{
			$data = BonusCreatedList::getList('15/05/2009', 'abs04/2009', $pages, $items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$data = BonusCreatedList::getList('15/05/2009', '21/05/2009', $pages, $items, 'abc');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$data = BonusCreatedList::getList('15/05/2009', '21/05/2009', $pages, $items, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}
?>