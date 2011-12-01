<?php
require_once('business/list.php');
require_once('PHPUnit/Framework/TestCase.php');

class BankListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = BankList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = BankList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = BankList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = BankList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class PendingDepositListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = PendingDepositList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = PendingDepositList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = PendingDepositList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = PendingDepositList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ManufacturerListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ManufacturerList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = ManufacturerList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = ManufacturerList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = ManufacturerList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class CorrelativeListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = CorrelativeList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = CorrelativeList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = CorrelativeList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = CorrelativeList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class BankAccountListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = BankAccountList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = BankAccountList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = BankAccountList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = BankAccountList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class UserAccountListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = UserAccountList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = UserAccountList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = UserAccountList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = UserAccountList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class PaymentCardBrandListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = PaymentCardBrandList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = PaymentCardBrandList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = PaymentCardBrandList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = PaymentCardBrandList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ProductListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ProductList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = ProductList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = ProductList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = ProductList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class SupplierListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = SupplierList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = SupplierList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = SupplierList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = SupplierList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class RoleListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = RoleList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = RoleList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = RoleList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = RoleList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class BranchListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = BranchList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = BranchList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = BranchList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = BranchList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class PaymentCardTypeListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = PaymentCardTypeList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = PaymentCardTypeList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = PaymentCardTypeList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = PaymentCardTypeList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class ShiftListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = ShiftList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = ShiftList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = ShiftList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = ShiftList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class UnitOfMeasureListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$list = UnitOfMeasureList::getList($pages, $items, 1);
		$this->assertEquals(2, count($list));
		$this->assertEquals(1, $pages);
		$this->assertEquals(2, $items);
	}
	
	public function testGetList_Defaults(){
		$list = UnitOfMeasureList::getList();
		$this->assertEquals(2, count($list));
	}
	
	public function testGetList_BadPageTxt(){
		try{
			$list = UnitOfMeasureList::getList($pages, $items, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetList_BadPageNegative(){
		try{
			$list = UnitOfMeasureList::getList($pages, $items, -4);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}
?>