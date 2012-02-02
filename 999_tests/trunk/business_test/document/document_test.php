<?php
require_once('config/config.php');

require_once('business/document.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/user_account.php');
require_once('business/transaction.php');
require_once('business/cash.php');
require_once('business/session.php');
require_once('business/event.php');
require_once('PHPUnit/Framework/TestCase.php');

class ConcreteDocDetail extends DocumentDetail{
	public function getId(){
		// Do something...
	}
	
	public function show(){
		// Do something...
	}
	
	public function increase($quantity){
		// Do something...
	}
	
	public function cancel(){
		// Do something...
	}
	
	public function isCancellable(){
		// Do something...
	}
	
	protected function insert(Document $doc, $number){
		// Do something...
	}
}

class ConcreteDocument extends Document{
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		// Do something...
	}
	
	public function discard(){
		// Do something...
	}
	
	protected function insert(){
		// Do something...
	}
	
	protected function updateToCancelled(UserAccount $user, $reason = NULL){
		// Do something...
	}
}

class ConcreteAD extends AdjustmentDocument{
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		// Do something...
	}
	
	public function discard(){
		// Do something...
	}
	
	protected function insert(){
		// Do something...
	}
	
	protected function updateToCancelled(UserAccount $user, $reason = NULL){
		// Do something...
	}
}




class ConcreteDocDetailTest extends PHPUnit_Framework_TestCase{
	private $_mDetail;
	
	public function setUp(){
		$this->_mDetail = new ConcreteDocDetail(5, 30.92); 
	}
	
	public function testConstructor(){
		$detail = new ConcreteDocDetail(2, 22.50);
		$this->assertEquals(2, $detail->getQuantity());
		$this->assertEquals(22.50, $detail->getPrice());
		$this->assertEquals(45, $detail->getTotal());
	}
	
	public function testConstructor_BadQuantityTxt(){
		try{
			$detail = new ConcreteDocDetail('hay', 22.50);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadQuantityNoPositive(){
		try{
			$detail = new ConcreteDocDetail(0, 22.50);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadPriceTxt(){
		try{
			$detail = new ConcreteDocDetail(2, 'dao');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$doc = new ConcreteDocument(NULL, NULL, NULL, Persist::CREATED);
		$this->_mDetail->save($doc, 1);
	}
	
	public function testSave_BadNumberTxt(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$doc = new ConcreteDocument(NULL, NULL, Persist::CREATED);
		try{
			$this->_mDetail->save($doc, 'hello');
		} catch(Exception $e){ return; }
		$this->fail('Document exception expected.');
	}
	
	public function testSave_BadNumberNoPositive(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$doc = new ConcreteDocument(NULL, NULL, Persist::CREATED);
		try{
			$this->_mDetail->save($doc, 0);
		} catch(Exception $e){ return; }
		$this->fail('Document exception expected.');
	}
}

class DocBonusDetailTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$bonus = Bonus::getInstance(123);
		$detail = new DocBonusDetail($bonus, 5.65);
		$this->assertEquals($bonus, $detail->getBonus());
		$this->assertEquals(1, $detail->getQuantity());
		$this->assertEquals(5.65, $detail->getPrice());
		$this->assertEquals('bon123', $detail->getId());
		$data_array = $detail->show();
		$this->assertEquals('bon123', $data_array['id']);
		$this->assertEquals('', $data_array['bar_code']);
		$this->assertEquals('', $data_array['manufacturer']);
		$this->assertEquals('Pepto Bismol', $data_array['product']);
		$this->assertEquals('', $data_array['um']);
		$this->assertEquals(1, $data_array['quantity']);
		$this->assertEquals(5.65, $data_array['price']);
		$this->assertEquals(5.65, $data_array['total']);
		$this->assertEquals('', $data_array['expiration_date']);
		$this->assertTrue($detail->isCancellable());
		$detail->cancel();
	}
	
	public function testConstructor_BadPriceTxt(){
		$bonus = Bonus::getInstance(123);
		try{
			$detail = new DocBonusDetail($bonus, 'gis');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testSave_Insert(){
		$bonus = Bonus::getInstance(123);
		$detail = new DocBonusDetail($bonus, 3.29);
		
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$doc = new ConcreteDocument(NULL, NULL, NULL, Persist::CREATED);
		$detail->save($doc, 2);
	}
}

class DocProductDetailTest extends PHPUnit_Framework_TestCase{
	private $_mDetail;
	
	public function setUp(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$this->_mDetail = new DocProductDetail($lot, $transaction, 20, 4.55, $reserve);
	}
	
	public function testConstructor(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 20, 4.55, $reserve);
		$this->assertEquals('123123', $detail->getId());
		$this->assertEquals($lot, $detail->getLot());
		$this->assertEquals($reserve, $detail->getReserve());
		$data_array = $detail->show();
		$this->assertEquals('123123', $data_array['id']);
		$this->assertEquals('12345', $data_array['bar_code']);
		$this->assertEquals('Bayer', $data_array['manufacturer']);
		$this->assertEquals('Pepto Bismol', $data_array['product']);
		$this->assertEquals('Unitario', $data_array['um']);
		$this->assertEquals(20, $data_array['quantity']);
		$this->assertEquals(4.55, $data_array['price']);
		$this->assertEquals(91, $data_array['total']);
		$this->assertEquals('31/12/2009', $data_array['expiration_date']);		
	}
	
	public function testConstructor_Defaults(){
		$lot = Lot::getInstance(123);
		$transaction = new Entry();
		$detail = new DocProductDetail($lot, $transaction, 20, 4.55);
		$this->assertEquals('1231234.55', $detail->getId());
		$this->assertEquals($lot, $detail->getLot());
		$this->assertNull($detail->getReserve());
		$data_array = $detail->show();
		$this->assertEquals('1231234.55', $data_array['id']);
		$this->assertEquals('12345', $data_array['bar_code']);
		$this->assertEquals('Bayer', $data_array['manufacturer']);
		$this->assertEquals('Pepto Bismol', $data_array['product']);
		$this->assertEquals('Unitario', $data_array['um']);
		$this->assertEquals(20, $data_array['quantity']);
		$this->assertEquals(4.55, $data_array['price']);
		$this->assertEquals(91, $data_array['total']);
		$this->assertEquals('31/12/2009', $data_array['expiration_date']);
	}
	
	public function testConstructor_BadQuantityTxt(){
		$lot = Lot::getInstance(123);
		$transaction = new Entry();
		$reserve = Reserve::getInstance(123);
		try{
			$detail = new DocProductDetail($lot, $transaction, $reserve, 'jea', 4.55);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadQuantityNoPositive(){
		$lot = Lot::getInstance(123);
		$transaction = new Entry();
		$reserve = Reserve::getInstance(123);
		try{
			$detail = new DocProductDetail($lot, $transaction, $reserve, 0, 4.55);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadPriceTxt(){
		$lot = Lot::getInstance(123);
		$transaction = new Entry();
		$reserve = Reserve::getInstance(123);
		try{
			$detail = new DocProductDetail($lot, $transaction, $reserve, 20, 'ñsaa');
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testConstructor_BadPriceNegative(){
		$lot = Lot::getInstance(123);
		$transaction = new Entry();
		$reserve = Reserve::getInstance(123);
		try{
			$detail = new DocProductDetail($lot, $transaction, $reserve, 20, -2.43);
		} catch(Exception $e){ return; }
		$this->fail('Price exception expected.');
	}
	
	public function testIncrease(){
		$this->_mDetail->increase(5);
		$this->assertEquals(25, $this->_mDetail->getQuantity());
	}
	
	public function testIncrease_BadQuantityTxt(){
		try{
			$this->_mDetail->increase('hsd');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testIncrease_BadNoPositive(){
		try{
			$this->_mDetail->increase(0);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testSave_Insert(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$doc = new ConcreteDocument(NULL, NULL, NULL, Persist::CREATED);
		$this->_mDetail->save($doc, 3);
	}
	
	public function testCancel(){
		$this->_mDetail->cancel();
	}
	
	public function testIsCancellable(){
		$this->assertTrue($this->_mDetail->isCancellable());
	}
}

class ReserveTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$reserve = new Reserve(4321, $lot, 5, $user, '10/02/2009 00:00:00');
		$this->assertEquals(4321, $reserve->getId());
		$this->assertEquals($lot, $reserve->getLot());
		$this->assertEquals(5, $reserve->getQuantity());
	}
	
	public function testConstructor_BadIdTxt(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve('yey', $lot, 5, $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve(0, $lot, 5, $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_NewLot(){
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve(4321, $lot, 5, $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Lot exception expected.');
	}
	
	public function testConstructor_BadQuantityTxt(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve(4321, $lot, 'no', $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_BadQuantityNoPositive(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve(4321, $lot, 0, $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testConstructor_NewUserAccount(){
		$lot = Lot::getInstance(123);
		$user = new UserAccount();
		try{
			$reserve = new Reserve(4321, $lot, 5, $user, '10/02/2009');
		} catch(Exception $e){ return; }
		$this->fail('UserAccount exception expected.');
	}
	
	public function testConstructor_BadDate(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		try{
			$reserve = new Reserve(4321, $lot, 5, $user, 'yea02/2009');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testCreateReserve(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$reserve = Reserve::create($lot, 5);
		$this->assertEquals(123, $reserve->getId());
	}
	
	public function testCreateReserve_BadQuantityTxt(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		try{
			$reserve = Reserve::create($lot, 'yuea!');
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testCreateReserve_BadQuantityNoPositive(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		try{
			$reserve = Reserve::create($lot, -1);
		} catch(Exception $e){ return; }
		$this->fail('Quantity exception expected.');
	}
	
	public function testMergeReserveTest(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$reserve = new Reserve(4321, $lot, 5, $user, '10/02/2009 00:00:00', Persist::CREATED);
		
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$reserve2 = new Reserve(4322, $lot, 5, $user, '10/02/2009 00:00:00', Persist::CREATED);
		
		$reserve->merge($reserve2);
		$this->assertEquals(10, $reserve->getQuantity());
	}
	
	public function testGetInstance(){
		$reserve = Reserve::getInstance(123);
		$this->assertEquals(123, $reserve->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$reserve = Reserve::getInstance('yua!');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$reserve = Reserve::getInstance(-2);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testDelete_NotNew(){
		$lot = Lot::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$reserve = Reserve::getInstance(123);
		Reserve::delete($reserve);
	}
}

class ConcreteDocumentTest extends PHPUnit_Framework_TestCase{
	private $_mDocument;
	
	public function setUp(){
		$this->_mDocument = new ConcreteDocument();
	}
	
	public function testConstructor(){
		$user = UserAccount::getInstance('roboli');
		$document = new ConcreteDocument('13/02/2009 08:08:08', $user, 4321, PersistDocument::CREATED);
		$this->assertEquals('13/02/2009 08:08:08', $document->getDateTime());
		$this->assertEquals($user, $document->getUser());
		$this->assertEquals(4321, $document->getId());
		$this->assertEquals(Persist::CREATED, $document->getStatus());
		$this->assertEquals(0, $document->getTotal());
		$detail = $document->getDetail('XX');
		$this->assertNull($detail);
		$details = $document->getDetails();
		$this->assertTrue(empty($details_array));
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$document = new ConcreteDocument();
		$this->assertNull($document->getDateTime());
		$this->assertEquals($user, $document->getUser());
		$this->assertNull($document->getId());
		$this->assertEquals(PersistDocument::IN_PROGRESS, $document->getStatus());
		$this->assertEquals(0, $document->getTotal());
		$detail = $document->getDetail('XX');
		$this->assertNull($detail);
		$details = $document->getDetails();
		$this->assertTrue(empty($details_array));
	}
	
	public function testConstructor_BadDate(){
		try{
			$document = new ConcreteDocument('23/12/2009 45:93:21');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$document = new ConcreteDocument(NULL, NULL, 'hello');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$document = new ConcreteDocument(NULL, NULL, 0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetData(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$details[] = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->setData(93.23, $details);
		$this->assertEquals(93.23, $this->_mDocument->getTotal());
		$detail = $this->_mDocument->getDetail('123123');
		$this->assertEquals('123123', $detail->getId());
	}
	
	public function testSetData_BadTotalTxt(){
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$transaction = new Entry();
		$details[] = new DocProductDetail($lot, $transaction, 4, 23.21);
		try{
			$this->_mDocument->setData('ds', $details);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_BadTotalNegative(){
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$transaction = new Entry();
		$details[] = new DocProductDetail($lot, $transaction, 4, 23.21);
		try{
			$this->_mDocument->setData(-1.35, $details);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$details = array();
		try{
			$this->_mDocument->setData(0.00, $details);
		} catch(Exception $e){ return; }
		$this->fail('Details exception expected.');
	}
	
	public function testAddDetail(){
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$transaction = new Entry();
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21);
		$this->_mDocument->addDetail($detail);
		$this->assertEquals(92.84, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(1, count($details));
	}
	
	public function testAddDetail_2(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->addDetail($detail);
		$lot2 = Lot::getInstance(4321);
		$detail2 = new DocProductDetail($lot2, $transaction, 1, 15.95, $reserve);
		$this->_mDocument->addDetail($detail2);
		$this->assertEquals(108.79, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
	}
	
	public function testAddDetail_3(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->addDetail($detail);
		$lot2 = Lot::getInstance(4321);
		$detail2 = new DocProductDetail($lot2, $transaction, 1, 15.95, $reserve);
		$this->_mDocument->addDetail($detail2);
		$bonus = Bonus::getInstance(123);
		$detail3 = new DocBonusDetail($bonus, -34.60);
		$this->_mDocument->addDetail($detail3);
		$this->assertEquals(74.19, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(3, count($details));
	}
	
	public function testAddDetail_4(){
		$product = Product::getInstance(124);
		$lot = new Lot($product, 15, 8.00, '15/01/2010');
		$detail = new DocProductDetail($lot, new Entry(), 15, 8.00);
		$this->_mDocument->addDetail($detail);
		
		$product2 = Product::getInstance(123);
		$lot = new Lot($product2, 10, 15.50, '10/12/2009');
		$detail = new DocProductDetail($lot, new Entry(), 10, 15.50);
		$this->_mDocument->addDetail($detail);
		
		$product->setBarCode('43783922');
		$lot = new Lot($product, 10, 8.00, '10/12/2009');
		$detail = new DocProductDetail($lot, new Entry(), 10, 8.00);
		$this->_mDocument->addDetail($detail);
		
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(3, count($details));
	}
	
	public function testAddDetail_AlreadyAdded(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->addDetail($detail);
		$detail2 = new DocProductDetail($lot, $transaction, 3, 20.20, $reserve);
		$this->_mDocument->addDetail($detail2);
		$this->assertEquals(153.44, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(1, count($details));
	}
	
	public function testAddDetail_AlreadyAdded_2(){
		$product = Product::getInstance(123);
		
		$lot1 = new Lot($product, 1, 32, '');
		$detail1 = new DocProductDetail($lot1, new Entry(), 1, 32);
		$this->_mDocument->addDetail($detail1);
		
		$lot2 = new Lot($product, 1, 32, '');
		$detail2 = new DocProductDetail($lot2, new Entry(), 1, 32);
		$this->_mDocument->addDetail($detail2);
		
		$lot3 = new Lot($product, 1, 35);
		$detail3 = new DocProductDetail($lot3, new Entry(), 1, 35);
		$this->_mDocument->addDetail($detail3);
		
		$lot4 = new Lot($product, 1, 32, '10/10/2010');
		$detail4 = new DocProductDetail($lot4, new Entry(), 1, 32);
		$this->_mDocument->addDetail($detail4);
		
		$lot5 = new Lot($product, 2, 32, '');
		$detail5 = new DocProductDetail($lot5, new Entry(), 2, 32);
		$this->_mDocument->addDetail($detail5);

		$details = $this->_mDocument->getDetails();
		$this->assertEquals(3, count($details));
		$this->assertEquals(195, $this->_mDocument->getTotal());
	}
	
	public function testDeleteDetail(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->addDetail($detail);
		$lot2 = Lot::getInstance(4321);
		$detail2 = new DocProductDetail($lot2, $transaction, 1, 15.95, $reserve);
		$this->_mDocument->addDetail($detail2);
		$lot3 = Lot::getInstance(4322);
		$detail3 = new DocProductDetail($lot3, $transaction, 2, 65.95, $reserve);
		$this->_mDocument->addDetail($detail3);
		$this->assertEquals(240.69, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(3, count($details));
		$other_detail = $this->_mDocument->getDetail('123123');
		$this->_mDocument->deleteDetail($other_detail);
		$this->assertEquals(147.85, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
	}
	
	public function testDeleteDetail_2(){
		$lot = Lot::getInstance(123);
		$transaction = new Withdraw();
		$reserve = Reserve::getInstance(123);
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21, $reserve);
		$this->_mDocument->addDetail($detail);
		$lot2 = Lot::getInstance(4321);
		$detail2 = new DocProductDetail($lot2, $transaction, 1, 15.95, $reserve);
		$this->_mDocument->addDetail($detail2);
		$lot3 = Lot::getInstance(4322);
		$detail3 = new DocProductDetail($lot3, $transaction, 2, 65.95, $reserve);
		$this->_mDocument->addDetail($detail3);
		$bonus = Bonus::getInstance(123);
		$bonus_detail = new DocBonusDetail($bonus, -34.60);
		$this->_mDocument->addDetail($bonus_detail);
		$this->assertEquals(206.09, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();	
		$this->assertEquals(4, count($details));
		
		$other_detail = $this->_mDocument->getDetail('123123');
		$this->_mDocument->deleteDetail($other_detail);
		$this->assertEquals(113.25, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(3, count($details));
		
		$other_detail = $this->_mDocument->getDetail('1234321');
		$this->_mDocument->deleteDetail($other_detail);
		$this->assertEquals(97.30, $this->_mDocument->getTotal());
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
		
		$other_detail = $this->_mDocument->getDetail('1234322');
		$this->_mDocument->deleteDetail($other_detail);
		$this->assertEquals(number_format(-34.60, 2), number_format($this->_mDocument->getTotal(), 2));
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(1, count($details));
		
		$other_detail = $this->_mDocument->getDetail('bon123');
		$this->_mDocument->deleteDetail($other_detail);
		$this->assertEquals(number_format(0, 2), number_format($this->_mDocument->getTotal(), 2));
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(0, count($details));
	}
	
	public function testAddAndDeleteDetail(){
		$product = Product::getInstance(124);
		$lot = new Lot($product, 10, 15.40, '15/12/2009');
		$transaction = new Entry();
		$this->_mDocument->addDetail(new DocProductDetail($lot, $transaction, 10, 15.40));
		
		$lot = new Lot($product, 10, 15.30, '15/12/2009');
		$this->_mDocument->addDetail(new DocProductDetail($lot, $transaction, 10, 15.30));
		$this->assertEquals(2, count($this->_mDocument->getDetails()));
		
		$lot = new Lot($product, 10, 15.40, '15/12/2009');
		$this->_mDocument->addDetail(new DocProductDetail($lot, $transaction, 10, 15.40));
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
		$detail = $details[1];
		$this->assertEquals(20, $detail->getQuantity());
		
		$this->_mDocument->deleteDetail($detail);
		$product2 = Product::getInstance(123);
		$lot = new Lot($product2, 15, 10.45, '15/12/2009');
		$transaction = new Entry();
		$this->_mDocument->addDetail(new DocProductDetail($lot, $transaction, 15, 10.45));
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
		$detail = $details[0];
		$this->assertEquals(10, $detail->getQuantity());
		
		$lot = new Lot($product, 2, 15.30, '15/12/2009');
		$this->_mDocument->addDetail(new DocProductDetail($lot, $transaction, 2, 15.30));
		$details = $this->_mDocument->getDetails();
		$this->assertEquals(2, count($details));
		$detail = $details[1];
		$this->assertEquals(12, $detail->getQuantity());
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(123);
		$lot = new Lot($product);
		$transaction = new Entry();
		$detail = new DocProductDetail($lot, $transaction, 4, 23.21);
		$this->_mDocument->addDetail($detail);
		$this->_mDocument->save();
		$this->assertEquals(PersistDocument::CREATED, $this->_mDocument->getStatus());
	}
	
	public function testSave_NoDetails(){
		try{
			$this->_mDocument->save();
		} catch(Exception $e){ return; }
		$this->fail('Details exception expected.');
	}
	
	public function testCancel(){
		$product = Product::getInstance(123);
		$lot = new Lot($product, 18);
		$transaction = new Entry();
		$detail = new DocProductDetail($lot, $transaction, 18, 23.21);
		$document = new ConcreteDocument(NULL, NULL, NULL, PersistDocument::CREATED);
		$document->addDetail($detail);
		$lot->save();
		$document->cancel(UserAccount::getInstance('roboli'));
		$this->assertEquals(0, $lot->getQuantity());
		$product = $lot->getProduct();
		$this->assertEquals(6, Inventory::getQuantity($product));
		$this->assertEquals(PersistDocument::CANCELLED, $document->getStatus());
	}
}

class CorrelativeTest extends PHPUnit_Framework_TestCase{
	private $_mCorrelative;
	
	public function setUp(){
		$this->_mCorrelative = new Correlative();
	}
	
	public function testConstruct(){
		$correlative = new Correlative(1, 'A021', 54321, Correlative::EXPIRED);
		$this->assertEquals(1, $correlative->getId());
		$this->assertEquals('A021', $correlative->getSerialNumber());
		$this->assertNull($correlative->getResolutionNumber());
		$this->assertNull($correlative->getResolutionDate());
		$this->assertNull($correlative->getRegime());
		$this->assertEquals(0, $correlative->getInitialNumber());
		$this->assertEquals(0, $correlative->getFinalNumber());
		$this->assertEquals(54321, $correlative->getCurrentNumber());
		$this->assertEquals(Correlative::EXPIRED, $correlative->getStatus());
	}
	
	public function testConstruct_Defaults(){
		$correlative = new Correlative();
		$this->assertNull($correlative->getSerialNumber());
		$this->assertNull($correlative->getResolutionNumber());
		$this->assertNull($correlative->getResolutionDate());
		$this->assertNull($correlative->getRegime());
		$this->assertEquals(0, $correlative->getInitialNumber());
		$this->assertEquals(0, $correlative->getFinalNumber());
		$this->assertEquals(0, $correlative->getCurrentNumber());
		$this->assertEquals(Correlative::IN_PROGRESS, $correlative->getStatus());
	}
	
	public function testConstructor_BlankSerialNumber(){
		try{
			$correlative = new Correlative('', 12, Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Serial Number exception expected.');
	}
	
	public function testConstructor_BadCurrentNumberTxt(){
		try{
			$correlative = new Correlative('E21', 'eay', Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Current Number exception expected.');
	}
	
	public function testConstructor_BadCurrentNumberNegative(){
		try{
			$correlative = new Correlative('E21', -2, Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Current Number exception expected.');
	}
	
	public function testGetNextNumber_InProgress(){
		$correlative = new Correlative(NULL, NULL, 0, Correlative::IN_PROGRESS);
		$this->assertEquals(0, $correlative->getNextNumber());
		$this->assertEquals(Correlative::IN_PROGRESS, $correlative->getStatus());
	}
	
	public function testGetNextNumber_Created(){
		$correlative = new Correlative(NULL, NULL, 0, Correlative::CREATED);
		$correlative->getNextNumber();
		$this->assertEquals(Correlative::CURRENT, $correlative->getStatus());
	}
	
	public function testGetNextNumber_Current(){
		$correlative = new Correlative(NULL, NULL, 0, Correlative::CURRENT);
		$correlative->getNextNumber();
		$this->assertEquals(Correlative::CURRENT, $correlative->getStatus());
	}
	
	public function testSetSerialNumber(){
		$this->_mCorrelative->setSerialNumber('B32');
		$this->assertEquals('B32', $this->_mCorrelative->getSerialNumber());
	}
	
	public function testSetSerialNumber_Blank(){
		try{
			$this->_mCorrelative->setSerialNumber('');
		} catch(Exception $e){ return; }
		$this->fail('Serial number exception expected.');
	}
	
	public function testSetSerialNumberInitialNumber(){
		$this->_mCorrelative->setSerialNumber('A022');
		$this->assertEquals(52, $this->_mCorrelative->getInitialNumber());
	}
	
	public function testSetResolutionNumber(){
		$this->_mCorrelative->setResolutionNumber('2009-10');
		$this->assertEquals('2009-10', $this->_mCorrelative->getResolutionNumber());
	}
	
	public function testSetResolutionNumber_Blank(){
		try{
			$this->_mCorrelative->setResolutionNumber('');
		} catch(Exception $e){ return; }
		$this->fail('Resolution number exception expected.');
	}
	
	public function testSetResolutionNumber_Exists(){
		try{
			$this->_mCorrelative->setResolutionNumber('123');
		} catch(Exception $e){ return; }
		$this->fail('Resolution number exception expected.');
	}
	
	public function testSetResolutionDate(){
		$date = date('d/m/Y', strtotime('-10 day'));
		$this->_mCorrelative->setResolutionDate($date);
		$this->assertEquals($date, $this->_mCorrelative->getResolutionDate());
	}
	
	public function testSetResolutionDate_Bad(){
		try{
			$this->_mCorrelative->setResolutionDate('i0/04/2009');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSetResolutionDate_Expired(){
		try{
			$this->_mCorrelative->setResolutionDate('10/04/2011');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSetRegime(){
		$this->_mCorrelative->setRegime('Sujeto a Pagos');
		$this->assertEquals('Sujeto a Pagos', $this->_mCorrelative->getRegime());
	}
	
	public function testSetRegime_Blank(){
		try{
			$this->_mCorrelative->setRegime('');
		} catch(Exception $e){ return; }
		$this->fail('Regime exception expected.');
	}
	
	public function testSetFinalNumber(){
		$this->_mCorrelative->setFinalNumber(13200);
		$this->assertEquals(13200, $this->_mCorrelative->getFinalNumber());
	}
	
	public function testSetFinalNumber_BadTxt(){
		try{
			$this->_mCorrelative->setFinalNumber('ds');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetFinalNumber_BadNoPositive(){
		try{
			$this->_mCorrelative->setFinalNumber(0);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData(){
		$this->_mCorrelative->setData('2008-09', '23/05/2008', '23/05/2009', 'Sujeto pagos', 10, 20);
		$this->assertEquals('2008-09', $this->_mCorrelative->getResolutionNumber());
		$this->assertEquals('23/05/2008', $this->_mCorrelative->getResolutionDate());
		$this->assertEquals('23/05/2009', $this->_mCorrelative->getCreatedDate());
		$this->assertEquals('Sujeto pagos', $this->_mCorrelative->getRegime());
		$this->assertEquals(10, $this->_mCorrelative->getInitialNumber());
		$this->assertEquals(20, $this->_mCorrelative->getFinalNumber());
	}
	
	public function testSetData_BlankResolutionNumber(){
		try{
			$this->_mCorrelative->setData('', '23/05/2008', 'Sujeto pagos', 10, 20);
		} catch(Exception $e){ return; }
		$this->fail('Resolution number exception expected.');
	}
	
	public function testSetData_BadResolutionDate(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/50/2008', 'Sujeto pagos', 10, 20);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSetData_BlankRegime(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/05/2008', '', 10, 20);
		} catch(Exception $e){ return; }
		$this->fail('Resolution number exception expected.');
	}
	
	public function testSetData_BadInitialNumberTxt(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/05/2008', 'Sujeto pagos', 'dsf', 20);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_BadInitialNumberNoPositive(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/05/2008', 'Sujeto pagos', 0, 20);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_BadFinalNumberTxt(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/05/2008', 'Sujeto pagos', 10, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_BadFinalNumberNoPositive(){
		try{
			$this->_mCorrelative->setData('2008-09', '23/05/2008', 'Sujeto pagos', 10, -20);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSave(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y', strtotime('-5 day')));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		$this->_mCorrelative->save();
		$this->assertEquals(Persist::CREATED, $this->_mCorrelative->getStatus());
	}
	
	public function testSave_NoSerialNumber(){
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Serial Number exception expected.');
	}
	
	public function testSave_NoResolutionNumber(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Resolution Number exception expected.');
	}
	
	public function testSave_ResolutionNumberExists(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		try{
			$this->_mCorrelative->setResolutionNumber('123');
		} catch(Exception $e){};
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Resolution Number exception expected.');
	}
	
	public function testSave_NoResolutionDate(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSave_ResolutionDateExpired(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		try{
			$this->_mCorrelative->setResolutionDate('01/05/2011');
		} catch(Exception $e){};
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(500);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testSave_NoFinalNumber(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSave_BadGreaterInitialThanFinalNumbers(){
		$this->_mCorrelative->setSerialNumber('A021');
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(100);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Numbers exception expected.');
	}
	
	public function testSave_BadEqualsInitialFinalNumbers(){
		$this->_mCorrelative->setSerialNumber('ABCD');
		$this->_mCorrelative->setResolutionNumber('2007-10');
		$this->_mCorrelative->setResolutionDate(date('d/m/Y'));
		$this->_mCorrelative->setRegime('Sujeto pagos');
		$this->_mCorrelative->setFinalNumber(1);
		try{
			$this->_mCorrelative->save();
		} catch(Exception $e){ return; }
		$this->fail('Numbers exception expected.');
	}
	
	public function testCreate_QueueNotEmpty(){
		try{
			$correlative = Correlative::create();
		} catch(Exception $e){ return; }
		$this->fail('Create correlative exception expected.');
	}
	
	public function testGetInstance(){
		$correlative = Correlative::getInstance(1);
		$this->assertEquals('A021', $correlative->getSerialNumber());
		$this->assertEquals(Correlative::CURRENT, $correlative->getStatus());
	}
	
	public function testGetInstance_Expired(){
		$correlative = Correlative::getInstance(2);
		$this->assertEquals('A022', $correlative->getSerialNumber());
		$this->assertEquals(Correlative::EXPIRED, $correlative->getStatus());	
	}
	
	public function testGetInstance_UsedUp(){
		$correlative = Correlative::getInstance(3);
		$this->assertEquals('C322', $correlative->getSerialNumber());
		$this->assertEquals(Correlative::USED_UP, $correlative->getStatus());	
	}
	
	public function testGetCurrentCorrelativeId(){
		$this->assertEquals(1, Correlative::getCurrentCorrelativeId());
	}
	
	public function testDelete_New(){
		$correlative = new Correlative();
		try{
			Correlative::delete($correlative);
		} catch(Exception $e){ return; }
		$this->fail('Persist exception expected.');
	}
	
	public function testDelete_NotNew(){
		$correlative = Correlative::getInstance(1);
		Correlative::delete($correlative);
	}
	
	public function testCreate_QueueEmpty(){
		$correlative = Correlative::create();
	}
}

class VatTest extends PHPUnit_Framework_TestCase{
	private $_mVat;
	
	public function setUp(){
		$this->_mVat = new Vat(14.12);
	}
	
	public function testConstructor(){
		$vat = new Vat(15.15);
		$this->assertEquals(15.15, $vat->getPercentage());
	}
	
	public function testConstructor_BadPercentageTxt(){
		try{
			$vat = new Vat('hse');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testConstructor_BadPercentageNoPositive(){
		try{
			$vat = new Vat(0.00);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetPercentage(){
		$this->_mVat->setPercentage(30.12);
		$this->assertEquals(30.12, $this->_mVat->getPercentage());
	}
	
	public function testSetPercentage_BadTxt(){
		try{
			$this->_mVat->setPercentage('slsd');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetPercentage_Negative(){
		try{
			$this->_mVat->setPercentage(-1.23);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetPercentage_OverOneHundred(){
		try{
			$this->_mVat->setPercentage(111.23);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSave(){
		$this->_mVat->save();
	}
	
	public function testGetInstance(){
		$vat = Vat::getInstance();
		$this->assertEquals(12.00, $vat->getPercentage());
	}
}

class DiscountTest extends PHPUnit_Framework_TestCase{
	private $_mDiscount;
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$this->_mDiscount = new Discount($user);
	}
	
	public function testConstructor(){
		$user = UserAccount::getInstance('roboli');
		$discount = new Discount($user, Persist::CREATED);
		$this->assertNull($discount->getInvoice());
		$this->assertNull(($discount->getPercentage()));
		$this->assertEquals($user, $discount->getUser());
		$this->assertEquals(Persist::CREATED, $discount->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$discount = new Discount($user);
		$this->assertNull($discount->getInvoice());
		$this->assertNull(($discount->getPercentage()));
		$this->assertEquals($user, $discount->getUser());
		$this->assertEquals(Persist::IN_PROGRESS, $discount->getStatus());
	}
	
	public function testSetInvoice(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$this->_mDiscount->setInvoice($invoice);
		$this->assertEquals($invoice, $this->_mDiscount->getInvoice());
	}
	
	public function testSetPercentage(){
		$this->_mDiscount->setPercentage(25.00);
		$this->assertEquals(25.00, $this->_mDiscount->getPercentage());
	}
	
	public function testSetPercentage_BadTxt(){
		try{
			$this->_mDiscount->setPercentage('dfs');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetPercentage_Negative(){
		try{
			$this->_mDiscount->setPercentage(-1.00);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetPercentage_OverOneHundred(){
		try{
			$this->_mDiscount->setPercentage(111.00);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register, NULL, NULL, NULL, Persist::CREATED);
		$this->_mDiscount->setData($invoice, 15.00);
		$this->assertEquals($invoice, $this->_mDiscount->getInvoice());
		$this->assertEquals(15.00, $this->_mDiscount->getPercentage());
	}
	
	public function testSetData_BadPercentageTxt(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register, NULL, NULL, Persist::CREATED);
		try{
			$this->_mDiscount->setData($invoice, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testSetData_BadPercentageNoPositive(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register, NULL, NULL, Persist::CREATED);
		try{
			$this->_mDiscount->setData($invoice, -2.43);
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testSave(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$this->_mDiscount->setInvoice($invoice);
		$this->_mDiscount->setPercentage(12.44);
		$this->_mDiscount->save();
		$this->assertEquals(Persist::CREATED, $this->_mDiscount->getStatus());
	}
	
	public function testSave_NoInvoice(){
		$this->_mDiscount->setPercentage(12.44);
		try{
			$this->_mDiscount->save();
		} catch(Exception $e){ return; }
		$this->fail('Invoice exception expected.');
	}
	
	public function testSave_NoPercentage(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$this->_mDiscount->setInvoice($invoice);
		try{
			$this->_mDiscount->save();
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testGetInstance(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register, NULL, NULL, 123, Persist::CREATED);
		$discount = Discount::getInstance($invoice);
		$this->assertEquals(25.00, $discount->getPercentage());
	}
}

class InvoiceTest extends PHPUnit_Framework_TestCase{
	private $_mInvoice;
	
	public function setUp(){
		$this->_mInvoice = new Invoice(CashRegister::getInstance(123));
	}
	
	public function testConstructor(){
		$cash_register = CashRegister::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice($cash_register, '12/10/2009 10:10:10', $user, 4321, PersistDocument::CREATED);
		$this->assertNull($invoice->getNumber());
		$this->assertNull($invoice->getCorrelative());
		$this->assertNull($invoice->getCustomerNit());
		$this->assertNull($invoice->getCustomerName());
		$this->assertNull($invoice->getVatPercentage());
		$this->assertEquals($cash_register, $invoice->getCashRegister());
		$this->assertEquals('12/10/2009 10:10:10', $invoice->getDateTime());
		$this->assertEquals($user, $invoice->getUser());
		$this->assertEquals(4321, $invoice->getId());
		$this->assertEquals(PersistDocument::CREATED, $invoice->getStatus());
		$this->assertEquals(0, $invoice->getSubTotal());
		$this->assertEquals(0, $invoice->getTotalDiscount());
		$this->assertEquals(0, $invoice->getTotal());
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$this->assertNull($invoice->getNumber());
		$this->assertNull($invoice->getCorrelative());
		$this->assertNull($invoice->getCustomerNit());
		$this->assertNull($invoice->getCustomerName());
		$this->assertNull($invoice->getVatPercentage());
		$this->assertEquals($cash_register, $invoice->getCashRegister());
		$this->assertNull($invoice->getDateTime());
		$this->assertEquals($user, $invoice->getUser());
		$this->assertNull($invoice->getId());
		$this->assertEquals(PersistDocument::IN_PROGRESS, $invoice->getStatus());
		$this->assertEquals(0, $invoice->getSubTotal());
		$this->assertEquals(0, $invoice->getTotalDiscount());
		$this->assertEquals(0, $invoice->getTotal());
		$this->assertEquals(0, $invoice->getDiscountPercentage());
	}
	
	public function testConstructor_ClosedCashRegister(){
		$cash_register = CashRegister::getInstance(124);
		$cash_register->close();
		try{
			$invoice = new Invoice($cash_register);
		} catch(Exception $e){ return; }
		$this->fail('Cash Register exception expected.');
	}
	
	public function testConstructor_BadDate(){
		try{
			$invoice = new Invoice(CashRegister::getInstance(123), '23/12/2009');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$invoice = new Invoice(CashRegister::getInstance(123), NULL, NULL, 'hello');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$invoice = new Invoice(CashRegister::getInstance(123), NULL, NULL, -5);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetProductQuantity(){
		$product = Product::getInstance(124);
		$lot = new Lot($product, 15, 8.00, '15/01/2010');
		$detail = new DocProductDetail($lot, new Entry(), 15, 8.00);
		$this->_mInvoice->addDetail($detail);
		
		$product2 = Product::getInstance(123);
		$lot = new Lot($product2, 10, 15.50, '10/12/2009');
		$detail = new DocProductDetail($lot, new Entry(), 10, 15.50);
		$this->_mInvoice->addDetail($detail);
		
		$lot = new Lot($product, 10, 8.00, '15/12/2009');
		$detail = new DocProductDetail($lot, new Entry(), 10, 8.00);
		$this->_mInvoice->addDetail($detail);
		
		$this->assertEquals(25, $this->_mInvoice->getProductQuantity($product));
		$this->assertEquals(10, $this->_mInvoice->getProductQuantity($product2));
	}
	
	public function testGetBonusDetail(){
		$lot = Lot::getInstance(5432);
		$detail = new DocProductDetail($lot, new Withdraw(), 5, 8.10, Reserve::getInstance(123));
		$this->_mInvoice->addDetail($detail);
		
		$lot2 = Lot::getInstance(123);
		$detail = new DocProductDetail($lot2, new Withdraw(), 10, 15.10, Reserve::getInstance(123));
		$this->_mInvoice->addDetail($detail);
		
		$product = Product::getInstance(123);
		$id = Bonus::getBonusIdByProduct($product, 4);
		$bonus = Bonus::getInstance($id);
		$bonus_detail = new DocBonusDetail($bonus, 34.20);
		$this->_mInvoice->addDetail($bonus_detail);
		
		$this->assertEquals($bonus_detail, $this->_mInvoice->getBonusDetail($product));
	}
	
	public function testSetCustomer(){
		$customer = Customer::getInstance('1725045-5');
		$this->_mInvoice->setCustomer($customer);
		$this->assertEquals('1725045-5', $this->_mInvoice->getCustomerNit());
		$this->assertEquals('Infodes', $this->_mInvoice->getCustomerName());
	}
	
	public function testSetDiscount(){
		$discount = new Discount(UserAccount::getInstance('roboli'));
		$discount->setPercentage(15.00);
		$this->_mInvoice->setDiscount($discount);
		
		$lot = Lot::getInstance(123);
		$detail = new DocProductDetail($lot, new Withdraw(), 5, 10.00, Reserve::getInstance(123));
		$this->_mInvoice->addDetail($detail);
		
		$this->assertEquals(50, $this->_mInvoice->getSubTotal());
		$this->assertEquals(42.5, $this->_mInvoice->getTotal());
		$this->assertEquals(15.00, $this->_mInvoice->getDiscountPercentage());
	}
	
	public function testSetData(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$discount->setPercentage(15.33);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00,
				109.50, $details, 'Infodes', $discount);
		$this->assertEquals(1234, $this->_mInvoice->getNumber());
		$this->assertEquals($correlative, $this->_mInvoice->getCorrelative());
		$this->assertEquals('1725045-5', $this->_mInvoice->getCustomerNit());
		$this->assertEquals('Infodes', $this->_mInvoice->getCustomerName());
		$this->assertEquals(12.00, $this->_mInvoice->getVatPercentage());
		$this->assertEquals(16.79, $this->_mInvoice->getTotalDiscount());
		$this->assertEquals('92.71', (string)$this->_mInvoice->getTotal()); // Tried with pure doubles but didn't work.
		$detail = $this->_mInvoice->getDetail('123123');
		$this->assertEquals('123123', $detail->getId());
	}
	
	public function testSetData_Defaults(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00,
				32.54, $details);
		$this->assertEquals(1234, $this->_mInvoice->getNumber());
		$this->assertEquals($correlative, $this->_mInvoice->getCorrelative());
		$this->assertEquals('1725045-5', $this->_mInvoice->getCustomerNit());
		$this->assertNull($this->_mInvoice->getCustomerName());
		$this->assertEquals(12.00, $this->_mInvoice->getVatPercentage());
		$this->assertEquals(32.54, $this->_mInvoice->getTotal());
		$detail = $this->_mInvoice->getDetail('123123');
		$this->assertEquals('123123', $detail->getId());
	}
	
	public function testSetData_BadNumberTxt(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData('dfsf', $correlative, '1725045-5', 12.00,
				$cash_receipt, 32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_BadNumberNoPositive(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(0, $correlative, '1725045-5', 12.00,
				$cash_receipt, 32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_NewCorrelative(){
		$correlative = new Correlative();
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00,
				$cash_receipt, 32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Correlative exception expected.');
	}
	
	public function testSetData_BadVatPercentageTxt(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 'dsf', $cash_register,
				32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}
	
	public function testSetData_BadVatPercentageNoPositive(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 0.00,
				$cash_receipt, 32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Percentage exception expected.');
	}	
	
	public function testSetData_NewDiscount(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'));
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00, $cash_register,
				32.54, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Discount exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '23244-7', 12.00, $cash_register,
				'dsf', $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_BadTotalNegative(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 21.90);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00, $cash_register,
				-2.00, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$correlative = Correlative::getInstance(1);
		$cash_register = CashRegister::getInstance(123);
		$discount = new Discount(UserAccount::getInstance('roboli'), Persist::CREATED);
		try{
			$this->_mInvoice->setData(1234, $correlative, '1725045-5', 12.00, $cash_register,
				52.00, $details, 'Infodes', $discount);
		} catch(Exception $e){ return; }
		$this->fail('Details exception expected.');
	}
	
	public function testDiscard(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $this->_mInvoice, 3);
		$this->assertEquals(1, count($this->_mInvoice->getDetails()));
		$this->assertEquals(27, Inventory::getAvailable($product));
		
		RetailEvent::apply($product, $this->_mInvoice, 5);
		$this->assertEquals(3, count($this->_mInvoice->getDetails()));
		$this->assertEquals(22, Inventory::getAvailable($product));
		
		RetailEvent::apply($product, $this->_mInvoice, 8);
		$this->assertEquals(3, count($this->_mInvoice->getDetails()));
		$this->assertEquals(14, Inventory::getAvailable($product));
		
		$this->_mInvoice->discard();
		$this->assertEquals(0, count($this->_mInvoice->getDetails()));
		$this->assertEquals(30, Inventory::getAvailable($product));
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mInvoice->setCustomer(Customer::getInstance('1725045-5'));
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $this->_mInvoice, 1);
		
		$cash_receipt = $this->_mInvoice->createCashReceipt();
		$this->assertEquals(123, $this->_mInvoice->save());
		$this->assertEquals(PersistDocument::CREATED, $this->_mInvoice->getStatus());
		$this->assertEquals(12, $this->_mInvoice->getVatPercentage());
		$this->assertEquals(458, $this->_mInvoice->getNumber());
		$this->assertEquals(29, Inventory::getAvailable($product));
	}
	
	public function testSave_NoCustomer(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $this->_mInvoice, 1);
		
		try{
			$this->_mInvoice->validate();
			$cash_receipt = $this->_mInvoice->createCashReceipt();
			$this->_mInvoice->save();
		} catch(Exception $e){ return; }
		$this->fail('Nit exception expected.');
	}
	
	public function testSave_NoDetails(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mInvoice->setCustomer(Customer::getInstance('1725045-5'));
		try{
			$this->_mInvoice->validate();
			$cash_receipt = $this->_mInvoice->createCashReceipt();
			$this->_mInvoice->save();
		} catch(Exception $e){ return; }
		$this->fail('Details exception expected.');
	}
	
	public function testCancel(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mInvoice->setCustomer(Customer::getInstance('1725045-5'));
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $this->_mInvoice, 5);
		RetailEvent::apply($product, $this->_mInvoice, 10);
		
		$cash_receipt = $this->_mInvoice->createCashReceipt();
		$this->_mInvoice->save();
		$this->assertEquals(13, Inventory::getAvailable($product));
		$this->assertEquals(29, Inventory::getQuantity($product));
		
		$this->_mInvoice->cancel($user, 'Anulacion.');
		$this->assertEquals(28, Inventory::getAvailable($product));
		$this->assertEquals(44, Inventory::getQuantity($product));
		$this->assertEquals(PersistDocument::CANCELLED, $this->_mInvoice->getStatus());
		$this->assertEquals('Anulacion.', $this->_mInvoice->getCancelledReason());
	}
	
	public function testCancel_NoReason(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mInvoice->setCustomer(Customer::getInstance('1725045-5'));
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $this->_mInvoice, 1);
		
		$cash_receipt = $this->_mInvoice->createCashReceipt();
		$this->_mInvoice->save();

		try{
			$this->_mInvoice->cancel($user);
		} catch(Exception $e){ return; }
		$this->fail('User exception expected.');
	}
	
	public function testGetInstance(){
		$invoice = Invoice::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $invoice->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$invoice = Invoice::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$invoice = Invoice::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInvoiceId(){
		$id = Invoice::getInvoiceId('A022', 457);
		$this->assertEquals(123, $id);
	}
	
	public function testGetInvoiceId_BlankSerialNumber(){
		try{
			$id = Invoice::getInvoiceId('', 457);
		} catch(Exception $e){ return; }
		$this->fail('Serial Number exception expected.');
	}
	
	public function testGetInvoiceId_BadNumberTxt(){
		try{
			$id = Invoice::getInvoiceId('A022', 'dsf');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testGetInvoiceId_BadNumberNoPositive(){
		try{
			$id = Invoice::getInvoiceId('A022', 0);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testGetInvoiceIdByWorkingDay(){
		$working_day = new WorkingDay('01/01/2009');
		$id = Invoice::getInvoiceIdByWorkingDay($working_day, 'A022', 457);
		$this->assertEquals(123, $id);
	}
	
	public function testGetInvoiceIdByWorkingDay_BlankSerialNumber(){
		$working_day = new WorkingDay('01/01/2009');
		try{
			$id = Invoice::getInvoiceIdByWorkingDay($working_day, '', 457);
		} catch(Exception $e){ return; }
		$this->fail('Serial Number exception expected.');
	}
	
	public function testGetInvoiceIdByWorkingDay_BadNumberTxt(){
		$working_day = new WorkingDay('01/01/2009');
		try{
			$id = Invoice::getInvoiceIdByWorkingDay($working_day, 'A022', 'dsf');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testGetInvoiceIdByWorkingDay_BadNumberNoPositive(){
		$working_day = new WorkingDay('01/01/2009');
		try{
			$id = Invoice::getInvoiceIdByWorkingDay($working_day, 'A022', 0);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testCreateCashReceipt(){
		$this->_mInvoice->createCashReceipt();
	}
	
	public function testCreateCashReceipt_CorrelativeExpired(){
		$correlative = Correlative::getInstance(Correlative::getCurrentCorrelativeId());
		
		for($i = $correlative->getCurrentNumber(); $i < $correlative->getFinalNumber(); $i++)
			$correlative->getNextNumber();
		
		try{
			$this->_mInvoice->createCashReceipt();
		} catch(Exception $e){ return; }
		$this->fail('Correlative expired exception expected.');
	}
	
	public function testCreateCashReceipt_NoCorrelative(){
		$correlative = Correlative::getInstance(Correlative::getCurrentCorrelativeId());
		
		for($i = $correlative->getCurrentNumber(); $i < $correlative->getFinalNumber(); $i++)
			// Avoid rules!!!!!
			CorrelativeDAM::getNextNumber($correlative);
		
		try{
			$this->_mInvoice->createCashReceipt();
		} catch(Exception $e){ return; }
		$this->fail('No correlative exception expected.');
	}
}

class PurchaseReturnTest extends PHPUnit_Framework_TestCase{
	private $_mReturn;
	
	public function setUp(){
		$this->_mReturn = new PurchaseReturn();
	}
	
	public function testSetSupplier(){
		$supplier = Supplier::getInstance(123);
		$this->_mReturn->setSupplier($supplier);
		$this->assertEquals($supplier, $this->_mReturn->getSupplier());
		$this->assertEquals('Roberto', $this->_mReturn->getContact());
	}
	
	public function testSetContact(){
		$this->_mReturn->setContact('hey');
		$this->assertEquals('hey', $this->_mReturn->getContact());
	}
	
	public function testSetReason(){
		$this->_mReturn->setReason('ni modo.');
		$this->assertEquals('ni modo.', $this->_mReturn->getReason());
	}
	
	public function testSetReason_Blank(){
		try{
			$this->_mReturn->setReason('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		$this->_mReturn->setData($supplier, 'no hay', 120.00, $details, 'Don');
		$this->assertEquals($supplier, $this->_mReturn->getSupplier());
		$this->assertEquals('no hay', $this->_mReturn->getReason());
		$this->assertEquals(120.00, $this->_mReturn->getTotal());
		$this->assertEquals($details, $this->_mReturn->getDetails());
		$this->assertEquals('Don', $this->_mReturn->getContact());
	}
	
	public function testSetData_Defaults(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		$this->_mReturn->setData($supplier, 'no hay', 120.00, $details);
		$this->assertEquals($supplier, $this->_mReturn->getSupplier());
		$this->assertEquals('no hay', $this->_mReturn->getReason());
		$this->assertEquals(120.00, $this->_mReturn->getTotal());
		$this->assertEquals($details, $this->_mReturn->getDetails());
		$this->assertNull($this->_mReturn->getContact());
	}
	
	public function testSetData_BlankReason(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReturn->setData($supplier, '', 120.00, $details, 'Don');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReturn->setData($supplier, 'no hay', 'sdf', $details, 'Don');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReturn->setData($supplier, 'no hay', 0.00, $details, 'Don');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$supplier = Supplier::getInstance(123);
		try{
			$this->_mReturn->setData($supplier, 'no hay', 120.00, $details, 'Don');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testDiscard(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mReturn, 20);
		WithdrawEvent::apply($product, $this->_mReturn, 20);
		$this->assertEquals(20, Inventory::getAvailable($product));
		
		$this->_mReturn->discard();
		$this->assertEquals(60, Inventory::getAvailable($product));
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReturn->setSupplier(Supplier::getInstance(123));
		$this->_mReturn->setReason('Ninguno.');
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mReturn, 1);
		$this->assertEquals(123, $this->_mReturn->save());
		$this->assertEquals(59, Inventory::getAvailable($product));
	}
	
	public function testSave_NoSupplier(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReturn->setReason('Ninguno.');
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mReturn, 1);
		try{
			$this->_mReturn->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoReason(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReturn->setSupplier(Supplier::getInstance(123));
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mReturn, 1);
		try{
			$this->_mReturn->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_EmptyDetails(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReturn->setSupplier(Supplier::getInstance(123));
		$this->_mReturn->setReason('Ninguno.');
		try{
			$this->_mReturn->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testCancel(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReturn->setSupplier(Supplier::getInstance(123));
		$this->_mReturn->setReason('Ninguno.');
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mReturn, 1);
		$this->_mReturn->save();
		
		$this->_mReturn->cancel($user);
		$this->assertEquals(57, Inventory::getAvailable($product));
	}
	
	public function testGetInstance(){
		$return = PurchaseReturn::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $return->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$return = PurchaseReturn::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$return = PurchaseReturn::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
}

class ShipmentTest extends PHPUnit_Framework_TestCase{
	private $_mShipment;
	
	public function setUp(){
		$this->_mShipment = new Shipment();
	}
	
	public function testSetBranch(){
		$branch = Branch::getInstance(123);
		$this->_mShipment->setBranch($branch);
		$this->assertEquals($branch, $this->_mShipment->getBranch());
		$this->assertEquals('Idania', $this->_mShipment->getContact());
	}
	
	public function testSetContact(){
		$this->_mShipment->setContact('hola');
		$this->assertEquals('hola', $this->_mShipment->getContact());
	}
	
	public function testSetData(){
		$branch = Branch::getInstance(123);
		$details[] = 'uno';
		$this->_mShipment->setData($branch, 150.00, $details, 'Pepe');
		$this->assertEquals($branch, $this->_mShipment->getBranch());
		$this->assertEquals(150.00, $this->_mShipment->getTotal());
		$this->assertEquals(1, count($this->_mShipment->getDetails()));
		$this->assertEquals('Pepe', $this->_mShipment->getContact());
	}
	
	public function testSetData_Defaults(){
		$branch = Branch::getInstance(123);
		$details[] = 'uno';
		$this->_mShipment->setData($branch, 150.00, $details);
		$this->assertEquals($branch, $this->_mShipment->getBranch());
		$this->assertEquals(150.00, $this->_mShipment->getTotal());
		$this->assertEquals(1, count($this->_mShipment->getDetails()));
		$this->assertNull($this->_mShipment->getContact());
	}
	
	public function testSetData_BadTotalTxt(){
		$branch = Branch::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mShipment->setData($branch, 'sdf', $details, 'Pepe');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$branch = Branch::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mShipment->setData($branch, 0.00, $details, 'Pepe');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$branch = Branch::getInstance(123);
		try{
			$this->_mShipment->setData($branch, 150.00, $details, 'Pepe');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testDiscard(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mShipment, 3);
		WithdrawEvent::apply($product, $this->_mShipment, 3);
		$this->assertEquals(51, Inventory::getAvailable($product));
		
		$this->_mShipment->discard();
		$this->assertEquals(57, Inventory::getAvailable($product));
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mShipment->setBranch(Branch::getInstance(123));
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mShipment, 1);
		$this->assertEquals(123, $this->_mShipment->save());
		$this->assertEquals(56, Inventory::getAvailable($product));
	}
	
	public function testSave_NoBranch(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mShipment, 1);
		try{
			$this->_mShipment->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_EmptyDetails(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mShipment->setBranch(Branch::getInstance(123));
		try{
			$this->_mShipment->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testCancel(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mShipment->setBranch(Branch::getInstance(123));
		$product = Product::getInstance(125);
		WithdrawEvent::apply($product, $this->_mShipment, 1);
		$this->_mShipment->save();
		
		$this->_mShipment->cancel($user);
		$this->assertEquals(55, Inventory::getAvailable($product));
	}
	
	public function testGetInstance(){
		$shipment = Shipment::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $shipment->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$shipment = Shipment::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$shipment = Shipment::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
}

class ReceiptTest extends PHPUnit_Framework_TestCase{
	private $_mReceipt;
	
	public function setUp(){
		$this->_mReceipt = new Receipt();
	}
	
	public function testSetSupplier(){
		$supplier = Supplier::getInstance(123);
		$this->_mReceipt->setSupplier($supplier);
		$this->assertEquals($supplier, $this->_mReceipt->getSupplier());
	}
	
	public function testSetShipmentNumber(){
		$this->_mReceipt->setShipmentNumber('432');
		$this->assertEquals('432', $this->_mReceipt->getShipmentNumber());
	}
	
	public function testSetShipmentNumber_Blank(){
		try{
			$this->_mReceipt->setShipmentNumber('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetShipmentTotal(){
		$this->_mReceipt->setShipmentTotal(12.89);
		$this->assertEquals(12.89, $this->_mReceipt->getShipmentTotal());
	}
	
	public function testSetShipmentTotal_BadTxt(){
		try{
			$this->_mReceipt->setShipmentTotal('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetShipmentTotal_BadNotPositive(){
		try{
			$this->_mReceipt->setShipmentTotal(0.00);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		$this->_mReceipt->setData($supplier, '432', 43.76, $details);
		$this->assertEquals($supplier, $this->_mReceipt->getSupplier());
		$this->assertEquals('432', $this->_mReceipt->getShipmentNumber());
		$this->assertEquals(43.76, $this->_mReceipt->getShipmentTotal());
		$this->assertEquals(43.76, $this->_mReceipt->getTotal());
		$this->assertEquals(1, count($this->_mReceipt->getDetails()));
	}
	
	public function testSetData_BlankShipmentNumber(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReceipt->setData($supplier, '', 43.76, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReceipt->setData($supplier, '432', 'sdf', $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$supplier = Supplier::getInstance(123);
		$details[] = 'uno';
		try{
			$this->_mReceipt->setData($supplier, '432', 0.00, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$supplier = Supplier::getInstance(123);
		try{
			$this->_mReceipt->setData($supplier, '432', 43.89, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testDiscard(){
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentNumber('987');
		$this->_mReceipt->setShipmentTotal(60.41);
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		$this->_mReceipt->discard();
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentNumber('987');
		$this->_mReceipt->setShipmentTotal(60.41);
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		$this->_mReceipt->save();
	}
	
	public function testSave_NoSupplier(){
		$this->_mReceipt->setShipmentNumber('987');
		$this->_mReceipt->setShipmentTotal(60.41);
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		try{
			$this->_mReceipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoShipmentNumber(){
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentTotal(60.41);
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		try{
			$this->_mReceipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoShipmentTotal(){
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentNumber('987');
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		try{
			$this->_mReceipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_DiferentShipmentTotal(){
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentNumber('987');
		$this->_mReceipt->setShipmentTotal(61.41);
		EntryEvent::apply(Product::getInstance(123), $this->_mReceipt, 7, 8.63);
		try{
			$this->_mReceipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_EmptyDetails(){
		$this->_mReceipt->setSupplier(Supplier::getInstance(123));
		$this->_mReceipt->setShipmentNumber('987');
		$this->_mReceipt->setShipmentTotal(60.41);
		try{
			$this->_mReceipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testCancel(){
		$receipt = new Receipt(NULL, NULL, NULL, PersistDocument::CREATED);
		$receipt->setSupplier(Supplier::getInstance(123));
		$receipt->setShipmentNumber('987');
		$receipt->setShipmentTotal(60.41);
		$lot = new Lot(Product::getInstance(123), 10, 8.63);
		$receipt->addDetail(new DocProductDetail($lot, new Entry(), 10, 8.63));
		$receipt->cancel(UserAccount::getInstance('roboli'));
	}
	
	public function testGetInstance(){
		$receipt = Receipt::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $receipt->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$receipt = Receipt::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$receipt = Receipt::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
}

class ConcreteADTest extends PHPUnit_Framework_TestCase{
	private $_mAdjustment;
	
	public function setUp(){
		$this->_mAdjustment = new ConcreteAD();
	}
	
	public function testSetReason(){
		$this->_mAdjustment->setReason('si hay');
		$this->assertEquals('si hay', $this->_mAdjustment->getReason());
	}
	
	public function testSetReason_Blank(){
		try{
			$this->_mAdjustment->setReason('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData(){
		$details[] = 'uno';
		$this->_mAdjustment->setData('los hay', 23.45, $details);
		$this->assertEquals('los hay', $this->_mAdjustment->getReason());
		$this->assertEquals(23.45, $this->_mAdjustment->getTotal());
		$this->assertEquals(1, count($this->_mAdjustment->getDetails()));
	}
	
	public function testSetData_BlankReason(){
		$details[] = 'uno';
		try{
			$this->_mAdjustment->setData('', 23.45, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$details[] = 'uno';
		try{
			$this->_mAdjustment->setData('los hay', 'sdf', $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$details[] = 'uno';
		try{
			$this->_mAdjustment->setData('los hay', 0.00, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		try{
			$this->_mAdjustment->setData('los hay', 23.45, $details);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mAdjustment->setReason('hay pues.');
		EntryEvent::apply(Product::getInstance(123), $this->_mAdjustment, 1, 5.00);
		$this->_mAdjustment->save();
	}
	
	public function testSave_NoReason(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		EntryEvent::apply(Product::getInstance(123), $this->_mAdjustment, 1, 5.00);
		try{
			$this->_mAdjustment->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave_EmptyDetails(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mAdjustment->setReason('hay pues.');
		try{
			$this->_mAdjustment->save();
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class EntryIATest extends PHPUnit_Framework_TestCase{
	private $_mEntry;
	
	public function setUp(){
		$this->_mEntry = new EntryIA();
	}
	
	public function testDiscard(){
		EntryEvent::apply(Product::getInstance(123), $this->_mEntry, 1, 5.00);
		$this->_mEntry->discard();
	}
	
	public function testGetInstance(){
		$entry = EntryIA::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $entry->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$entry = EntryIA::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$entry = EntryIA::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSave_Insert(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mEntry->setReason('simon.');
		EntryEvent::apply(Product::getInstance(123), $this->_mEntry, 1, 5.00);
		$this->_mEntry->save();
		$this->assertEquals(123, $this->_mEntry->getId());
	}
	
	public function testCancel_UpdateToCancelled(){
		$entry = new EntryIA(NULL, NULL, NULL, PersistDocument::CREATED);
		$entry->setReason('simon.');
		$detail = new DocProductDetail(Lot::getInstance(4321), new Entry(), 8, 5.00);
		$entry->addDetail($detail);
		
		$entry->cancel(UserAccount::getInstance('roboli'));
		$this->assertEquals(PersistDocument::CANCELLED, $entry->getStatus());
	}
}

class WithdrawIATest extends PHPUnit_Framework_TestCase{
	private $_mWithdraw;
	
	public function setUp(){
		$this->_mWithdraw = new WithdrawIA();
	}
	
	public function testDiscard(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		WithdrawEvent::apply(Product::getInstance(124), $this->_mWithdraw, 1);
		$this->_mWithdraw->discard();
		$this->assertEquals(27, Inventory::getAvailable(Product::getInstance(124)));
	}
	
	public function testGetInstance(){
		$withdraw = WithdrawIA::getInstance(123, $total_pages, $total_items, 0);
		$this->assertEquals(123, $withdraw->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$withdraw = WithdrawIA::getInstance('sdf', $total_pages, $total_items, 1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$withdraw = WithdrawIA::getInstance(0, $total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSave_Insert(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$this->_mWithdraw->setReason('simon.');
		WithdrawEvent::apply(Product::getInstance(124), $this->_mWithdraw, 1, 5.00);
		$this->_mWithdraw->save();
		$this->assertEquals(123, $this->_mWithdraw->getId());
	}
	
	public function testCancel_UpdateToCancelled(){
		$withdraw = new WithdrawIA(NULL, NULL, NULL, PersistDocument::CREATED);
		$withdraw->setReason('simon.');
		$detail = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 8, 5.00);
		$withdraw->addDetail($detail);
		
		$withdraw->cancel(UserAccount::getInstance('roboli'));
		$this->assertEquals(PersistDocument::CANCELLED, $withdraw->getStatus());
	}
}
?>