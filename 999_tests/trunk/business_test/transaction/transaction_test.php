<?php
require_once('business/transaction.php');
require_once('business/document.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/user_account.php');
require_once('business/session.php');
require_once('PHPUnit/Framework/TestCase.php');

class WithdrawTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$lot = Lot::getInstance(123);
		$withdraw = new Withdraw();
		$reserve = Reserve::create($lot, 5);
		$detail = new DocProductDetail($lot, $withdraw, 5, 23.45, $reserve);
		$withdraw->apply($detail);
		
		$this->assertEquals(13, $lot->getQuantity());
		$this->assertEquals(10, $lot->getAvailable());
		$product = $lot->getProduct();
		$this->assertEquals(15, Inventory::getQuantity($product));
		$this->assertEquals(7, Inventory::getAvailable($product));
	}
	
	public function testCancel(){
		$lot = Lot::getInstance(123);
		$withdraw = new Withdraw();
		$detail = new DocProductDetail($lot, $withdraw, 8, 23.45);
		$withdraw->cancel($detail);
		$this->assertEquals(21, $lot->getQuantity());
		$product = $lot->getProduct();
		$this->assertEquals(23, Inventory::getQuantity($product));
	}
}

class EntryTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$product = Product::getInstance(123);
		$lot = new Lot($product, 10, 15.75, '15/12/2009');
		$entry = new Entry();
		$detail = new DocProductDetail($lot, $entry, 10, 15.75);
		$entry->apply($detail);
		$this->assertEquals(33, Inventory::getQuantity($product));
		$this->assertEquals(15.75, $product->getPrice());
	}
	
	public function testIsCancellable_NotEqual(){
		$lot = Lot::getInstance(123);
		$entry = new Entry();
		$detail = new DocProductDetail($lot, $entry, 25, 15.75);
		$this->assertFalse($entry->isCancellable($detail));
	}
	
	public function testIsCancellable_Equal(){
		$lot = Lot::getInstance(123);
		$entry = new Entry();
		$detail = new DocProductDetail($lot, $entry, 21, 15.75);
		$this->assertTrue($entry->isCancellable($detail));
	}
	
	public function testCancel(){
		$lot = Lot::getInstance(123);
		$entry = new Entry();
		$detail = new DocProductDetail($lot, $entry, 18, 15.75);
		$entry->cancel($detail);
		$product = $lot->getProduct();
		$this->assertEquals(0, $lot->getQuantity());
		$this->assertEquals(12, Inventory::getQuantity($product));
	}
}
?>