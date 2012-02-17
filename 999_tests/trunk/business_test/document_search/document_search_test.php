<?php
require_once('business/document_search.php');
require_once('business/agent.php');
require_once('PHPUnit/Framework/TestCase.php');

class DepositSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = DepositSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = DepositSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = DepositSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = DepositSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = DepositSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class ComparisonSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = ComparisonSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = ComparisonSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = ComparisonSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = ComparisonSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = ComparisonSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class CountSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = CountSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = CountSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = CountSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = CountSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = CountSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class PurchaseReturnSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = PurchaseReturnSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = PurchaseReturnSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = PurchaseReturnSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = PurchaseReturnSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = PurchaseReturnSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class ShipmentSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = ShipmentSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = ShipmentSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = ShipmentSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = ShipmentSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = ShipmentSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class InvoiceSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = InvoiceSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = InvoiceSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = InvoiceSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = InvoiceSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = InvoiceSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class InvoiceByWorkingDaySearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = InvoiceByWorkingDaySearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = InvoiceByWorkingDaySearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = InvoiceByWorkingDaySearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = InvoiceByWorkingDaySearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = InvoiceByWorkingDaySearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class ReceiptSearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = ReceiptSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = ReceiptSearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = ReceiptSearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = ReceiptSearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = ReceiptSearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
	
	public function testSearchBySupplier(){
		$new_array = ReceiptSearch::searchBySupplier(Supplier::getInstance(123), '321', $totalPages, $totalItems, 1); 
		$this->assertEquals(2, count($new_array));
		$this->assertEquals($totalPages, 1);
		$this->assertEquals($totalItems, 2);
	}
	
	public function testSearchBySupplier_Defaults(){
		$new_array = ReceiptSearch::searchBySupplier(Supplier::getInstance(123), '321'); 
		$this->assertEquals(2, count($new_array));
	}
	
	public function testSearchBySupplier_BlankShipmentNumber(){
		try{
			$new_array = ReceiptSearch::searchBySupplier(Supplier::getInstance(123), '', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearchBySupplier_BadPage(){
		try{
			$new_array = ReceiptSearch::searchBySupplier(Supplier::getInstance(123), '1009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class EntryIASearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = EntryIASearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = EntryIASearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = EntryIASearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = EntryIASearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = EntryIASearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}

class WithdrawIASearchTest extends PHPUnit_Framework_TestCase{
	private $_mArray;
	
	public function setUp(){
		$this->_mArray = array();
		$this->_mArray[] = array('date' => '10/01/2009', 'id' => '123');
		$this->_mArray[] = array('date' => '11/01/2009', 'id' => '124');
		$this->_mArray[] = array('date' => '12/01/2009', 'id' => '125');
		$this->_mArray[] = array('date' => '13/01/2009', 'id' => '126');
		$this->_mArray[] = array('date' => '14/01/2009', 'id' => '127');
	}
	
	public function testSearch(){
		$new_array = WithdrawIASearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, 1); 
		$this->assertEquals($new_array, $this->_mArray);
		$this->assertEquals($totalPages, 2);
		$this->assertEquals($totalItems, 7);
	}
	
	public function testSearch_Defaults(){
		$new_array = WithdrawIASearch::search('10/01/2009', '15/01/2009'); 
		$this->assertEquals($new_array, $this->_mArray);
	}
	
	public function testSearch_BadStartDate(){
		try{
			$new_array = WithdrawIASearch::search('35/01/2009', '15/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadEndDate(){
		try{
			$new_array = WithdrawIASearch::search('10/01/2009', 'a/01/2009', $totalPages, $totalItems, 1);
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected');
	}
	
	public function testSearch_BadPage(){
		try{
			$new_array = WithdrawIASearch::search('10/01/2009', '15/01/2009', $totalPages, $totalItems, -4);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected');
	}
}
?>