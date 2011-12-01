<?php
require_once('business/agent.php');
require_once('PHPUnit/Framework/TestCase.php');

class ConcreteOrganization extends Organization{
	static public function getInstance($id){
		// Do something
	}
	
	protected function insert(){
		// Do something
	}
	
	protected function update(){
		// Do something
	}
}




class CustomerTest extends PHPUnit_Framework_TestCase{
	private $_mCustomer;
	
	public function setUp(){
		$this->_mCustomer = Customer::getInstance('12345-9');
	}
	
	public function testConstructor(){
		$customer = new Customer('1234-9', PersistObject::CREATED);
		$this->assertEquals($customer->getNit(), '1234-9');
		$this->assertEquals($customer->getStatus(), PersistObject::CREATED);
		$this->assertNull($customer->getName());
	}
	
	public function testConstructor_CF(){
		$customer = new Customer('C.F');
		$this->assertEquals($customer->getNit(), Customer::CF);
		$this->assertEquals($customer->getStatus(), PersistObject::IN_PROGRESS);
		$this->assertNull($customer->getName());
	}
	
	public function testConstructor_BadNit(){
		try{
			$customer = new Customer('hola');
		} catch(Exception $e){ return; }
		$this->fail('Constructor exception expected.');
	}
	
	public function testGetInstance_New(){
		$customer = Customer::getInstance('350682-7');
		$this->assertEquals($customer->getNit(), '350682-7');
		$this->assertEquals($customer->getStatus(), PersistObject::IN_PROGRESS);
		$this->assertNull($customer->getName());
	}
	
	public function testGetInstance_NotNew(){
		$customer = Customer::getInstance('1725045-5');
		$this->assertEquals($customer->getNit(), '1725045-5');
		$this->assertEquals($customer->getStatus(), PersistObject::CREATED);
		$this->assertEquals($customer->getName(), 'Infodes');
	}
	
	/**
	 * @dataProvider providerCF
	 */
	public function testGetInstance_CF($cf){
		$customer = Customer::getInstance($cf);
		$this->assertEquals(Customer::CF, $customer->getNit());
		$this->assertNull($customer->getName());
	}
	
	public function providerCF(){
		return array(
			array('cf'),
			array('CF'),
			array('c.f'),
			array('C.F'),
			array('c.f.'),
			array('c\f'),
			array('c\f.'),
			array('c/f'),
			array('c/f.')
		);
	}
	
	public function testGetInstance_BadNit(){
		try{
			$customer = Customer::getInstance('12345');
		} catch(Exception $e){ return; }
		$this->fail('Nit exception expected.');
	}
	
	public function testSetName(){
		$customer = new Customer('42343-6');
		$customer->setName('robert');
		$this->assertEquals('robert', $customer->getName());
	}
	
	public function testSetName_Blank(){
		$customer = new Customer('424343-6');
		try{
			$customer->setName('');
		} catch(ValidateException $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSetName_CF(){
		$customer = new Customer('cf');
		$customer->setName('robert');
		$this->assertEquals('robert', $customer->getName());
	}
	
	public function testSetName_CF_Blank(){
		$customer = new Customer('cf');
		$customer->setName('');
		$this->assertEquals('', $customer->getName());
	}
	
	public function testSetData(){
		$this->_mCustomer->setData('gil');
		$this->assertEquals('gil', $this->_mCustomer->getName());
	}
	
	public function testSetData_NameBlank(){
		try{
			$this->_mCustomer->setData('');
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testSave(){
		$this->_mCustomer->setName('Jose');
		$this->_mCustomer->save();
		$this->assertEquals($this->_mCustomer->getStatus(), PersistObject::CREATED);
	}
	
	public function testSave_CF(){
		$customer = new Customer('CF', Persist::IN_PROGRESS);
		$customer->setName('Roberto');
		$customer->save();
	}
	
	public function testSave_NoName(){
		try{
			$this->_mCustomer->save();
		} catch(ValidateException $e){
			$this->assertEquals('name', $e->getProperty());
			return; 
		}
		$this->fail('Save exception expected.');
	}
	
	public function testSave_CF_NoName(){
		$customer = new Customer('cf');
		$customer->save();
	}
}

class ConcreteOrganizationTest extends PHPUnit_Framework_TestCase{
	private $_mOrganization;
	
	public function setUp(){
		$this->_mOrganization = new ConcreteOrganization();
	}
	
	public function tearDown(){
	}
	
	public function testConstructor(){
		$organization = new ConcreteOrganization(123, PersistObject::CREATED);
		$this->assertEquals($organization->getId(), 123);
		$this->assertEquals($organization->getStatus(), PersistObject::CREATED);
	}
	
	public function testConstruct_Defaults(){
		$organization = new ConcreteOrganization();
		$this->assertNull($organization->getId());
		$this->assertEquals($organization->getStatus(), PersistObject::IN_PROGRESS);
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$organization = new ConcreteOrganization('sflwe');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$organization = new ConcreteOrganization(0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetNit(){
		$this->_mOrganization->setNit('123-8');
		$this->assertEquals($this->_mOrganization->getNit(), '123-8');
	}
	
	public function testSetNit_BadNit(){
		try{
			$this->_mOrganization->setNit('adios');
		} catch(ValidateException $e){ return; }
		$this->fail('Nit exception expected.');
	}
	
	public function testSetTelephone(){
		$this->_mOrganization->setTelephone('24129999');
		$this->assertEquals($this->_mOrganization->getTelephone(), '24129999');
	}
	
	public function testSetTelephone_Blank(){
		try{
			$this->_mOrganization->setTelephone('');
		} catch(ValidateException $e){ return; }
		$this->fail('Telephone exception expected.');
	}
	
	public function testSetAddress(){
		$this->_mOrganization->setAddress('3a calle 7-32 z.1');
		$this->assertEquals($this->_mOrganization->getAddress(), '3a calle 7-32 z.1');
	}
	
	public function testSetAddress_Blank(){
		try{
			$this->_mOrganization->setAddress('');
		} catch(ValidateException $e){ return; }
		$this->fail('Address exception expected.');
	}
	
	public function testSetEmail(){
		$this->_mOrganization->setEmail('roberto.oliveros@josegil.net');
		$this->assertEquals($this->_mOrganization->getEmail(), 'roberto.oliveros@josegil.net');
	}
	
	public function testSetEmail_BlankEmail(){
		$this->_mOrganization->setEmail('');
		$this->assertEquals($this->_mOrganization->getEmail(), '');
	}
	
	public function testSetEmail_BadEmail(){
		try{
			$this->_mOrganization->setEmail('hola.com');
		} catch(ValidateException $e){ return; }
		$this->fail('Email exception expected.');
	}
	
	public function testSetEmail_BadEmail_2(){
		try{
			$this->_mOrganization->setEmail('0');
		} catch(ValidateException $e){ return; }
		$this->fail('Email exception expected.');
	}
	
	public function testSetContact(){
		$this->_mOrganization->setContact('Jose Gilberto');
		$this->assertEquals($this->_mOrganization->getContact(), 'Jose Gilberto');
	}
	
	public function testSetData(){
		$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '3a calle',
				'info@josegil.net', 'Roberto');
		$this->assertEquals($this->_mOrganization->getNit(), '350682-7');
		$this->assertEquals($this->_mOrganization->getName(), 'Jose Gil');
		$this->assertEquals($this->_mOrganization->getTelephone(), '24120099');
		$this->assertEquals($this->_mOrganization->getAddress(), '3a calle');
		$this->assertEquals($this->_mOrganization->getEmail(), 'info@josegil.net');
		$this->assertEquals($this->_mOrganization->getContact(), 'Roberto');
	}
	
	public function testSetData_Defaults(){
		$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '3a calle');
		$this->assertEquals($this->_mOrganization->getNit(), '350682-7');
		$this->assertEquals($this->_mOrganization->getName(), 'Jose Gil');
		$this->assertEquals($this->_mOrganization->getTelephone(), '24120099');
		$this->assertEquals($this->_mOrganization->getAddress(), '3a calle');
		$this->assertNull($this->_mOrganization->getEmail());
		$this->assertNull($this->_mOrganization->getContact());
	}
	
	public function testSetData_BlankName(){
		try{
			$this->_mOrganization->setData('350682-7', '', '24120099', '3a calle',
				'info@josegil.net', 'Roberto');
		} catch(Exception $e){ return; }
		$this->fail('Data exception expected.');
	}
	
	public function testSetData_BlankTelephone(){
		try{
			$this->_mOrganization->setData('350682-7', 'Jose Gil', '', '3a calle',
				'info@josegil.net', 'Roberto');
		} catch(Exception $e){ return; }
		$this->fail('Data exception expected.');
	}
	
	public function testSetData_BlankAddress(){
		try{
			$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '',
				'info@josegil.net', 'Roberto');
		} catch(Exception $e){ return; }
		$this->fail('Data exception expected.');
	}
	
	public function testSetData_BlankEmail(){
		$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '3a calle',
				'', 'Roberto');
		$this->assertEquals('', $this->_mOrganization->getEmail());
	}
	
	public function testSetData_BlankContact(){
		$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '3a calle',
				'info@josegil.net', '');
		$this->assertEquals('', $this->_mOrganization->getContact());
	}
	
	public function testSetData_BadNit(){
		try{
			$this->_mOrganization->setData('3506827', 'Jose Gil', '24120099', '3a calle',
				'info@josegil.net', 'Roberto');
		} catch(Exception $e){ return; }
		$this->fail('Data exception expected.');
	}
	
	public function testSetData_BadEmail(){
		try{
			$this->_mOrganization->setData('350682-7', 'Jose Gil', '24120099', '3a calle',
				'info.josegil.net', 'Roberto');
		} catch(Exception $e){ return; }
		$this->fail('Data exception expected.');
	}
	
	public function testSave(){
		$this->_mOrganization->setNit('35073-2');
		$this->_mOrganization->setName('9.99');
		$this->_mOrganization->setTelephone('24129999');
		$this->_mOrganization->setAddress('3a calle.');
		$this->_mOrganization->save();
	}
	
	public function testSave_NoNit(){
		try{
			$this->_mOrganization->setName('9.99');
			$this->_mOrganization->setTelephone('24129999');
			$this->_mOrganization->setAddress('3a calle.');
			$this->_mOrganization->save();
		} catch(ValidateException $e){
			$this->assertEquals('nit', $e->getProperty());
			return;
		}
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoName(){
		try{
			$this->_mOrganization->setNit('35073-2');
			$this->_mOrganization->setTelephone('24129999');
			$this->_mOrganization->setAddress('3a calle.');
			$this->_mOrganization->save();
		} catch(ValidateException $e){
			$this->assertEquals('name', $e->getProperty());
			return;
		}
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoTelephone(){
		try{
			$this->_mOrganization->setNit('35073-2');
			$this->_mOrganization->setName('9.99');
			$this->_mOrganization->setAddress('3a calle.');
			$this->_mOrganization->save();
		} catch(ValidateException $e){
			$this->assertEquals('telephone', $e->getProperty());
			return;
		}
		$this->fail('Exception expected.');
	}
	
	public function testSave_NoAddress(){
		try{
			$this->_mOrganization->setNit('35073-2');
			$this->_mOrganization->setName('9.99');
			$this->_mOrganization->setTelephone('24129999');
			$this->_mOrganization->save();
		} catch(ValidateException $e){
			$this->assertEquals('address', $e->getProperty());
			return;
		}
		$this->fail('Exception expected.');
	}
}

class SupplierTest extends PHPUnit_Framework_TestCase{
	
	public function testGetInstance(){
		$supplier = Supplier::getInstance(123);
		$this->assertEquals($supplier->getNit(), '350682-7');
	}
	
	public function testDelete_New(){
		try{
			Supplier::delete($this->_mSupplier);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');		
	}
	
	public function testDelete_NotNew(){
		$supplier = Supplier::getInstance(123);
		Supplier::delete($supplier);
	}
	
	public function testSave_Insert(){
		$supplier = new Supplier();
		$supplier->setName('Bodega');
		$supplier->setNit('3506-3');
		$supplier->setTelephone('38293');
		$supplier->setAddress('centro');
		$supplier->save();
		$this->assertEquals(123, $supplier->getId());
	}
	
	public function testSave_Update(){
		$supplier = Supplier::getInstance(123);
		$supplier->setName('Central');
		$supplier->save();
		$other = Supplier::getInstance(123);
		$this->assertEquals('Central', $other->getName());
	}
}

class BranchTest extends PHPUnit_Framework_TestCase{
	public function testGetInstance(){
		$branch = Branch::getInstance(123);
		$this->assertEquals($branch->getNit(), '350682-7');
	}
	
	public function testDelete_New(){
		try{
			Branch::delete($this->_mBranch);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');		
	}
	
	public function testDelete_NotNew(){
		$branch = Branch::getInstance(123);
		Branch::delete($branch);
	}
	
	public function testSave_Insert(){
		$branch = new Branch();
		$branch->setName('Xela');
		$branch->setNit('3506-3');
		$branch->setTelephone('38293');
		$branch->setAddress('centro');
		$branch->save();
		$this->assertEquals(123, $branch->getId());
	}
	
	public function testSave_Update(){
		$branch = Branch::getInstance(123);
		$branch->setName('Barrios');
		$branch->save();
		$other = Branch::getInstance(123);
		$this->assertEquals('Barrios', $other->getName());
	}
}
?>