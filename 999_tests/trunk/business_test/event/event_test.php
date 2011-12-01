<?php
require_once('business/event.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/document.php');
require_once('business/session.php');
require_once('business/transaction.php');
require_once('business/user_account.php');
require_once('business/cash.php');
require_once('PHPUnit/Framework/TestCase.php');

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




class EntryEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(124);
		$document = new ConcreteDocument();
		EntryEvent::apply($product, $document, 25, 12.50, '10/01/2009');
		$details = $document->getDetails();
		$this->assertEquals(1, count($details));
		$detail = $details[0];
		$lot = $detail->getLot();
		$this->assertEquals($product, $lot->getProduct());
		$this->assertEquals(25, $lot->getQuantity());
		$this->assertEquals(12.50, $lot->getPrice());
		$this->assertEquals('10/01/2009', $lot->getExpirationDate());
		$this->assertEquals(25, $detail->getQuantity());
		$this->assertEquals(12.50, $detail->getPrice());
	}
	
	public function testApply_2(){
		$product = Product::getInstance(124);
		$document = new ConcreteDocument();
		EntryEvent::apply($product, $document, 25, 12.50, '10/01/2009');
		EntryEvent::apply($product, $document, 25, 12.50, '10/01/2009');
		$details = $document->getDetails();
		$this->assertEquals(1, count($details));
		$detail = $details[0];
		$lot = $detail->getLot();
		$this->assertEquals($product, $lot->getProduct());
		$this->assertEquals(50, $lot->getQuantity());
		$this->assertEquals(12.50, $lot->getPrice());
		$this->assertEquals('10/01/2009', $lot->getExpirationDate());
		$this->assertEquals(50, $detail->getQuantity());
		$this->assertEquals(12.50, $detail->getPrice());
	}
	
	public function testCancel(){
		$product = Product::getInstance(124);
		$document = new ConcreteDocument();
		EntryEvent::apply($product, $document, 25, 12.50, '10/01/2009');
		$details = $document->getDetails();
		$this->assertEquals(1, count($details));
		$detail = $details[0];
		EntryEvent::cancel($document, $detail);
		$details = $document->getDetails();
		$this->assertTrue(empty($details));
	}
}

class WithdrawEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApplyAndCancel(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$document = new ConcreteDocument();
		$product = Product::getInstance(123);
		WithdrawEvent::apply($product, $document, 8);
		WithdrawEvent::apply($product, $document, 2);
		$details = $document->getDetails();
		$this->assertEquals(2, count($details));
		$this->assertEquals(2, Inventory::getAvailable($product));
		
		$detail1 = $details[0];
		$lot1 = $detail1->getLot();
		$this->assertEquals(0, $lot1->getAvailable());
		
		$detail2 = $details[1];
		$lot2 = $detail2->getLot();
		$this->assertEquals(2, $lot2->getAvailable());
		
		WithdrawEvent::cancel($document, $detail1);
		$this->assertEquals(6, Inventory::getAvailable($product));
		$this->assertEquals(4, $lot1->getAvailable());
		
		WithdrawEvent::cancel($document, $detail2);
		$this->assertEquals(12, Inventory::getAvailable($product));
		$this->assertEquals(8, $lot2->getAvailable());
	}
}

class WithdrawEventTest_2 extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$document = new ConcreteDocument();
		$product = Product::getInstance(123);
		
		WithdrawEvent::apply($product, $document, 12);
		$details = $document->getDetails();
		$this->assertEquals(2, count($details));
		
		WithdrawEvent::cancel($document, $details[1]);
		$this->assertEquals(8, Inventory::getAvailable($product));
	}
	
	public function testApply_NotAvailable(){
		$document = new ConcreteDocument();
		$product = Product::getInstance(123);
		try{
			WithdrawEvent::apply($product, $document, 50);
		} catch(Exception $e){ return; }
		$this->fail('Available exception expected.');
	}
}

class RetailEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApplyAndCancel(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$invoice = new Invoice(CashRegister::getInstance(123));
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $invoice, 2);
		$this->assertEquals(1, count($invoice->getDetails()));
		$this->assertEquals(15.80, $invoice->getTotal());
		
		RetailEvent::apply($product, $invoice, 2);
		$this->assertEquals(2, count($invoice->getDetails()));
		$this->assertEquals(number_format(30.02, 2), number_format($invoice->getTotal(), 2));
		
		RetailEvent::apply($product, $invoice, 5);
		$this->assertEquals(3, count($invoice->getDetails()));
		$this->assertEquals(number_format(67.94, 2), number_format($invoice->getTotal(), 2));
		
		RetailEvent::apply($product, $invoice, 5);
		$this->assertEquals(3, count($invoice->getDetails()));
		$this->assertEquals(97.56, $invoice->getTotal());
		
		$product2 = Product::getInstance(123);
		RetailEvent::apply($product2, $invoice, 3);
		$this->assertEquals(4, count($invoice->getDetails()));
		$this->assertEquals(135.51, $invoice->getTotal());
		
		$detail = $invoice->getDetail('1245433');
		RetailEvent::cancel($invoice, $detail);
		$this->assertEquals(3, count($invoice->getDetails()));
		$this->assertEquals(number_format(75.87, 2), number_format($invoice->getTotal(), 2));
		
		$detail = $invoice->getDetail('1245432');
		RetailEvent::cancel($invoice, $detail);
		$this->assertEquals(1, count($invoice->getDetails()));
		$this->assertEquals(number_format(37.95, 2), number_format($invoice->getTotal(), 2));
		
		$detail = $invoice->getDetail('1234322');
		RetailEvent::cancel($invoice, $detail);
		$this->assertEquals(0, count($invoice->getDetails()));
		$this->assertEquals(number_format(0, 2), number_format($invoice->getTotal(), 2));
	}
	
	public function testApplyAndCancel_2(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$invoice = new Invoice(CashRegister::getInstance(123));
		$product = Product::getInstance(124);
		RetailEvent::apply($product, $invoice, 2);
		$this->assertEquals(1, count($invoice->getDetails()));
		$this->assertEquals(15.80, $invoice->getTotal());
		
		RetailEvent::apply($product, $invoice, 2);
		$this->assertEquals(2, count($invoice->getDetails()));
		$this->assertEquals(number_format(30.02, 2), number_format($invoice->getTotal(), 2));
		
		RetailEvent::apply($product, $invoice, 5);
		$this->assertEquals(3, count($invoice->getDetails()));
		$this->assertEquals(number_format(67.94, 2), number_format($invoice->getTotal(), 2));
		
		RetailEvent::apply($product, $invoice, 5);
		$this->assertEquals(3, count($invoice->getDetails()));
		$this->assertEquals(97.56, $invoice->getTotal());
		
		$product2 = Product::getInstance(123);
		RetailEvent::apply($product2, $invoice, 3);
		$this->assertEquals(4, count($invoice->getDetails()));
		$this->assertEquals(135.51, $invoice->getTotal());
		
		$invoice->discard();
		$this->assertEquals(0, count($invoice->getDetails()));
		$this->assertEquals(number_format(0, 2), number_format($invoice->getTotal(), 2));
	}
}
?>