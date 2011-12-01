<?php
require_once('config/config.php');
require_once('business/document.php');
require_once('business/user_account.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/transaction.php');
require_once('business/inventory.php');
require_once('business/session.php');
require_once('PHPUnit/Framework/TestCase.php');

class DetailsPrinterTest extends PHPUnit_Framework_TestCase{
	
	public function testShowPage(){
		$receipt = Receipt::getInstance(124);
		$details = DetailsPrinter::showPage($receipt);
		$this->assertEquals(5, count($details));
	}
	
	public function testShowPage_1(){
		$receipt = Receipt::getInstance(124);
		$details = DetailsPrinter::showPage($receipt, $pages, $items, 1);
		$this->assertEquals(4, count($details));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
	}
	
	public function testShowPage_2(){
		$receipt = Receipt::getInstance(124);
		$details = DetailsPrinter::showPage($receipt, $pages, $items, 2);
		$this->assertEquals(1, count($details));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
	}
	
	public function testShowPage_21(){
		$receipt = Receipt::getInstance(124);
		$details = DetailsPrinter::showPage($receipt, $pages, $items, 3);
		$this->assertEquals(0, count($details));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
	}
	
	public function testShowPage_3(){
		$count = Count::getInstance(124);
		$details = DetailsPrinter::showPage($count);
		$this->assertEquals(9, count($details));
	}
	
	public function testShowPage_4(){
		$count = Count::getInstance(124);
		$details = DetailsPrinter::showPage($count, $pages, $items, 1);
		$this->assertEquals(4, count($details));
		$this->assertEquals(3, $pages);
		$this->assertEquals(9, $items);
	}
	
	public function testShowPage_5(){
		$count = Count::getInstance(124);
		$details = DetailsPrinter::showPage($count, $pages, $items, 2);
		$this->assertEquals(4, count($details));
		$this->assertEquals(3, $pages);
		$this->assertEquals(9, $items);
	}
	
	public function testShowPage_6(){
		$count = Count::getInstance(124);
		$details = DetailsPrinter::showPage($count, $pages, $items, 3);
		$this->assertEquals(1, count($details));
		$this->assertEquals(3, $pages);
		$this->assertEquals(9, $items);
	}
	
	public function testShowPage_BadPageTxt(){
		$count = Count::getInstance(124);
		try{
			$details = DetailsPrinter::showPage($count, $pages, $items, 'sdf');
		} catch(Exception $e){ return; }
	}
	
	public function testShowPage_BadPageNegative(){
		$count = Count::getInstance(124);
		try{
			$details = DetailsPrinter::showPage($count, $pages, $items, -32);
		} catch(Exception $e){ return; }
	}
	
	public function testShowPage_EmptyDetails(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$count = new Count();
		$this->assertEquals(0, count(DetailsPrinter::showPage($count)));
	}
	
	public function testShowLastPage(){
		$receipt = Receipt::getInstance(124);
		$details = DetailsPrinter::showLastPage($receipt, $pages, $items);
		$this->assertEquals(1, count($details));
		$this->assertEquals(2, $pages);
		$this->assertEquals(5, $items);
	}
	
	public function testShowLastPage_1(){
		$count = Count::getInstance(124);
		$details = DetailsPrinter::showLastPage($count, $pages, $items);
		$this->assertEquals(1, count($details));
		$this->assertEquals(3, $pages);
		$this->assertEquals(9, $items);
	}
}
?>