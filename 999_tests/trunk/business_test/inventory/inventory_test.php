<?php
require_once('business/inventory.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/user_account.php');
require_once('business/session.php');
require_once('PHPUnit/Framework/TestCase.php');

class ComparisonDetailTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$detail = new ComparisonDetail(Product::getInstance(125), 10, 10);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(10, $data['physical']);
		$this->assertEquals(10, $data['system']);
		$this->assertEquals(0, $data['diference']);
	}
	
	public function testConstructor_2(){
		$detail = new ComparisonDetail(Product::getInstance(125), 2, 0);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(2, $data['physical']);
		$this->assertEquals(0, $data['system']);
		$this->assertEquals('+2', $data['diference']);
	}
	
	public function testConstructor_3(){
		$detail = new ComparisonDetail(Product::getInstance(125), 8, 10);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(8, $data['physical']);
		$this->assertEquals(10, $data['system']);
		$this->assertEquals('-2', $data['diference']);
	}
	
	public function testConstructor_BadPhysicalTxt(){
		try{
			$detail = new ComparisonDetail(Product::getInstance(125), 'sdf', 10);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadPhysicalNegative(){
		try{
			$detail = new ComparisonDetail(Product::getInstance(125), -2, 10);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadSystemTxt(){
		try{
			$detail = new ComparisonDetail(Product::getInstance(125), 8, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadSystemNegative(){
		try{
			$detail = new ComparisonDetail(Product::getInstance(125), 8, -5);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ComparisonTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		$comparison = new Comparison(321, '05/05/2009 12:00:00', $user, 'simon', true, $details, 100, 95);
		$this->assertEquals(321, $comparison->getId());
		$this->assertEquals('05/05/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('simon', $comparison->getReason());
		$this->assertTrue($comparison->isGeneral());
		$this->assertEquals(1, count($comparison->getDetails()));
		$this->assertEquals(100, $comparison->getPhysicalTotal());
		$this->assertEquals(95, $comparison->getSystemTotal());
		$this->assertEquals('+5', $comparison->getTotalDiference());
	}
	
	public function testConstructor_BadIdTxt(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison('sdf', '05/05/2009', $user, 'simon', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(0, '05/05/2009', $user, 'simon', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadDate(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(321, 'sd05/2009', $user, 'simon', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_NewUser(){
		$user = new UserAccount();
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BlankReason(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, '', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_EmptyDetails(){
		$user = UserAccount::getInstance('roboli');
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, 100, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadPhysicalTotalTxt(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, 'sdf', 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadPhysicalTotalNegative(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, -2, 95);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadSystemTotalTxt(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, 100, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadSystemTotalNegative(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		try{
			$comparison = new Comparison(432, '05/05/2009', $user, 'simon', true, $details, 100, -1);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance(){
		$comparison = Comparison::getInstance(123);
		$this->assertEquals(123, $comparison->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$comparison = Comparison::getInstance('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$comparison = Comparison::getInstance(0);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testExists(){
		$this->assertTrue(Comparison::exists(123));
	}
	
	public function testExists_2(){
		$this->assertFalse(Comparison::exists(122));
	}
}

class CountDetailTest extends PHPUnit_Framework_TestCase{
	private $_mDetail;
	
	public function setUp(){
		$this->_mDetail = new CountDetail(Product::getInstance(124), 10);
	}
	
	public function testConstructor(){
		$product = Product::getInstance(125);
		$detail = new CountDetail($product, 25, Persist::CREATED);
		$this->assertEquals($product, $detail->getProduct());
		$this->assertEquals(25, $detail->getQuantity());
		$this->assertEquals(Persist::CREATED, $detail->getStatus());
		$this->assertFalse($detail->isDeleted());
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(25, $data['quantity']);
	}
	
	public function testConstructor_Defaults(){
		$product = Product::getInstance(125);
		$detail = new CountDetail($product, 0);
		$this->assertEquals($product, $detail->getProduct());
		$this->assertEquals(0, $detail->getQuantity());
		$this->assertEquals(Persist::IN_PROGRESS, $detail->getStatus());
		$this->assertFalse($detail->isDeleted());
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(0, $data['quantity']);
	}
	
	public function testConstructor_BadQuantityTxt(){
		$product = Product::getInstance(125);
		try{
			$detail = new CountDetail($product, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadQuantityNegative(){
		$product = Product::getInstance(125);
		try{
			$detail = new CountDetail($product, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testIncrease(){
		$this->_mDetail->increase(2);
		$this->assertEquals(12, $this->_mDetail->getQuantity());
	}
	
	public function testIncrease_BadTxt(){
		try{
			$this->_mDetail->increase('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testIncrease_BadNoPositive(){
		try{
			$this->_mDetail->increase(0);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testDelete(){
		$this->_mDetail->delete();
		$this->assertTrue($this->_mDetail->isDeleted());
	}
	
	public function testCommit_Insert(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$count = new Count(NULL, NULL, NULL, Persist::CREATED);
		$this->_mDetail->commit($count);
	}
	
	public function testCommit_Delete(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$detail = new CountDetail(Product::getInstance(124), 5, Persist::CREATED);
		$detail->delete();
		$detail->commit(new Count(NULL, NULL, NULL, Persist::CREATED));
	}
}

class CountTest extends PHPUnit_Framework_TestCase{
	private $_mCount;
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$this->_mCount = new Count();
	}
	
	public function testConstructor(){
		$user = UserAccount::getInstance('roboli');
		$count = new Count(321, '01/01/2009 12:00:00', $user, Persist::CREATED);
		$this->assertEquals(321, $count->getId());
		$this->assertEquals('01/01/2009 12:00:00', $count->getDateTime());
		$this->assertEquals($user, $count->getUser());
		$this->assertNull($count->getReason());
		$this->assertEquals(0, count($count->getDetails()));
		$this->assertNull($count->getDetail(245353));
		$this->assertEquals(0, $count->getTotal());
		$this->assertEquals(Persist::CREATED, $count->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$count = new Count();
		$this->assertNull($count->getId());
		$this->assertNull($count->getDateTime());
		$this->assertEquals($user, $count->getUser());
		$this->assertNull($count->getReason());
		$this->assertEquals(0, count($count->getDetails()));
		$this->assertNull($count->getDetail(245353));
		$this->assertEquals(0, $count->getTotal());
		$this->assertEquals(Persist::IN_PROGRESS, $count->getStatus());
	}
	
	public function testSetReason(){
		$this->_mCount->setReason('simon.');
		$this->assertEquals('simon.', $this->_mCount->getReason());
	}
	
	public function testSetReason_Blank(){
		try{
			$this->_mCount->setReason('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetReason_NotNewCount(){
		$count = new Count(NULL, NULL, NULL, Persist::CREATED);
		try{
			$count->setReason('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData(){
		$details[] = new CountDetail(Product::getInstance(123), 4);
		$this->_mCount->setData('yeah', 91, $details);
		$this->assertEquals('yeah', $this->_mCount->getReason());
		$this->assertEquals(91, $this->_mCount->getTotal());
		$this->assertEquals($details, $this->_mCount->getDetails());
	}
	
	public function testSetData_BlankReason(){
		$details[] = new CountDetail(Product::getInstance(123), 4);
		try{
			$this->_mCount->setData('', 91, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$details[] = new CountDetail(Product::getInstance(123), 4);
		try{
			$this->_mCount->setData('yeah', 'sdf', $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$details[] = new CountDetail(Product::getInstance(123), 4);
		try{
			$this->_mCount->setData('yeah', 0, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		try{
			$this->_mCount->setData('yeah', 92, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testAddDetail(){
		$detail = new CountDetail(Product::getInstance(124), 3, Persist::CREATED);
		$this->_mCount->addDetail($detail);
		$this->assertEquals(3, $this->_mCount->getTotal());
		$this->assertEquals(1, count($this->_mCount->getDetails()));
		
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(123), 5));
		$this->assertEquals(8, $this->_mCount->getTotal());
		$this->assertEquals(2, count($this->_mCount->getDetails()));
		
		$detail2 = new CountDetail(Product::getInstance(124), 15);
		$this->_mCount->addDetail($detail2);
		$this->assertEquals(23, $this->_mCount->getTotal());
		$this->assertEquals(2, count($this->_mCount->getDetails()));
		$this->assertTrue($detail->isDeleted());
		$this->assertEquals(18, $detail2->getQuantity());
		
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(123), 7));
		$this->assertEquals(30, $this->_mCount->getTotal());
		$this->assertEquals(2, count($this->_mCount->getDetails()));
		
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(125), 5));
		$this->assertEquals(35, $this->_mCount->getTotal());
		$this->assertEquals(3, count($this->_mCount->getDetails()));
	}
	
	public function testDeleteDetail(){
		$detail = new CountDetail(Product::getInstance(124), 3, Persist::CREATED);
		$this->_mCount->addDetail($detail);
		
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(123), 5));
		
		$detail2 = new CountDetail(Product::getInstance(124), 15);
		$this->_mCount->addDetail($detail2);
		
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(123), 7));
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(125), 5));
		$this->assertEquals(35, $this->_mCount->getTotal());
		$this->assertEquals(3, count($this->_mCount->getDetails()));
		
		$this->_mCount->deleteDetail($detail);
		$this->assertEquals(17, $this->_mCount->getTotal());
		$this->assertEquals(2, count($this->_mCount->getDetails()));
		
		$this->_mCount->deleteDetail($detail2);
		$this->assertEquals(17, $this->_mCount->getTotal());
		$this->assertEquals(2, count($this->_mCount->getDetails()));
		
		$detail = $this->_mCount->getDetail(125);
		$this->_mCount->deleteDetail($detail);
		$this->assertEquals(12, $this->_mCount->getTotal());
		$this->assertEquals(1, count($this->_mCount->getDetails()));
		
		$detail = $this->_mCount->getDetail(123);
		$this->_mCount->deleteDetail($detail);
		$this->assertEquals(0, $this->_mCount->getTotal());
		$this->assertEquals(0, count($this->_mCount->getDetails()));
	}
	
	public function testGetInstance(){
		$count = Count::getInstance(123);
		$this->assertEquals(123, $count->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$count = Count::getInstance('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$count = Count::getInstance(0);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_Insert(){
		$this->_mCount->setReason('los hay');
		$this->_mCount->addDetail(new CountDetail(Product::getInstance(123), 7));
		$this->_mCount->save();
		$this->assertEquals(123, $this->_mCount->getId());
		$this->assertEquals(Persist::CREATED, $this->_mCount->getStatus());
	}
	
	public function testSave_Update(){
		$count = Count::getInstance(123);
		$count->save();
	}
	
	public function testDelete_NotNew(){
		$count = Count::getInstance(123);
		Count::delete($count);
	}
}

class ComparisonEventTest extends PHPUnit_Framework_TestCase{
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
	}
	
	public function testApply(){
		$count = Count::getInstance(123);
		$this->assertEquals(123, ComparisonEvent::apply($count, 'pues ni', true));
	}
	
	public function testApply_BlankReason(){
		$count = Count::getInstance(123);
		try{
			$this->assertEquals(123, ComparisonEvent::apply($count, '', true));
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ParserTest extends PHPUnit_Framework_TestCase{
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
	}
	
	public function testParseFile(){
		$count = new Count();
		Parser::parseFile($count, 'data.txt');
		$this->assertEquals(3, count($count->getDetails()));
		$this->assertEquals(44, $count->getTotal());
	}
	
	public function testParseFile_2(){
		$count = new Count();
		Parser::parseFile($count, 'data_2.txt');
		$this->assertEquals(3, count($count->getDetails()));
		$this->assertEquals(50, $count->getTotal());
	}
	
	public function testParseFile_NonExistent(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'none.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_Empty(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'empty.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadFormat(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_format.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadFormat_2(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_format_2.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadIdTxt(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_id_txt.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadIdNoPositive(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_id_nopositive.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadQuantityTxt(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_quantity_txt.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadQuantityNegative(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_quantity_negative.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_BadQuantityGreaterMaximum(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'bad_quantity_greater.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testParseFile_NoProduct(){
		$count = new Count();
		try{
			Parser::parseFile($count, 'no_product.txt');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class CountingTemplateTest extends PHPUnit_Framework_TestCase{
	
	public function testGetDataByProduct_BlankFirst(){
		try{
			$data = CountingTemplate::getDataByProduct('', 'hola');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetDataByProduct_BlankLast(){
		try{
			$data = CountingTemplate::getDataByProduct('hola', '');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetDataByManufacturer_BlankFirst(){
		try{
			$data = CountingTemplate::getDataByManufacturer('', 'hola');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetDataByManufacturer_BlankLast(){
		try{
			$data = CountingTemplate::getDataByManufacturer('hola', '');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ComparisonFilterDetailTest extends PHPUnit_Framework_TestCase{
	
	public function testShow(){
		$detail = new ComparisonFilterDetail(Product::getInstance(125), 10, 10);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(10, $data['physical']);
		$this->assertEquals(10, $data['system']);
		$this->assertEquals(0, $data['diference']);
		$this->assertEquals(65.73, $data['price']);
		$this->assertEquals('0.00', $data['total']);
	}
	
	public function testShow_2(){
		$detail = new ComparisonFilterDetail(Product::getInstance(125), 2, 0);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(2, $data['physical']);
		$this->assertEquals(0, $data['system']);
		$this->assertEquals('+2', $data['diference']);
		$this->assertEquals(65.73, $data['price']);
		$this->assertEquals('+131.46', $data['total']);
	}
	
	public function testShow_3(){
		$detail = new ComparisonFilterDetail(Product::getInstance(125), 8, 10);
		$data = $detail->show();
		$this->assertEquals('35138', $data['bar_code']);
		$this->assertEquals('Bayer', $data['manufacturer']);
		$this->assertEquals('Pharmaton', $data['product']);
		$this->assertEquals('Unitario', $data['um']);
		$this->assertEquals(8, $data['physical']);
		$this->assertEquals(10, $data['system']);
		$this->assertEquals('-2', $data['diference']);
		$this->assertEquals(65.73, $data['price']);
		$this->assertEquals('-131.46', $data['total']);
	}
}

class ComparisonFilterTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$user = UserAccount::getInstance('roboli');
		$details[] = 'uno';
		$comparison = new ComparisonFilter(321, '05/05/2009 12:00:00', $user, 'simon', true, $details, 100, 95, ComparisonFilter::FILTER_DIFERENCES, true, 85.9);
		$this->assertEquals(321, $comparison->getId());
		$this->assertEquals('05/05/2009 12:00:00', $comparison->getDateTime());
		$this->assertEquals($user, $comparison->getUser());
		$this->assertEquals('simon', $comparison->getReason());
		$this->assertTrue($comparison->isGeneral());
		$this->assertEquals(1, count($comparison->getDetails()));
		$this->assertEquals(100, $comparison->getPhysicalTotal());
		$this->assertEquals(95, $comparison->getSystemTotal());
		$this->assertEquals('+5', $comparison->getTotalDiference());
		$this->assertEquals(ComparisonFilter::FILTER_DIFERENCES, $comparison->getFilterType());
		$this->assertTrue($comparison->includePrices());
		$this->assertEquals('+85.90', $comparison->getPriceTotal());
	}
	
	public function testGetInstance(){
		$comparison = ComparisonFilter::getInstance(123, ComparisonFilter::FILTER_NEGATIVES);
		$this->assertEquals(123, $comparison->getId());
		$this->assertEquals(ComparisonFilter::FILTER_NEGATIVES, $comparison->getFilterType());
	}
}
?>