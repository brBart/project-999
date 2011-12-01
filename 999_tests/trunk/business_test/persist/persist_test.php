<?php
require_once('business/persist.php');
require_once('PHPUnit/Framework/TestCase.php');

class ConcretePersist extends Persist{}

class ConcreteIdentifier extends Identifier{
	protected function insert(){
		// Do something
	}
	
	protected function update(){
		// Do something
	}
}

class ConcretePersistDocument extends PersistDocument{
	public function discard(){
		// Do something...
	}
	
	public function cancel(UserAccount $user, $reason = NULL){
		// Do something...
	}
	
	protected function insert(){
		// Do something...
	}
}




class ConcretePersistTest extends PHPUnit_Framework_TestCase{
	public function testConstructor(){
		$persist = new ConcretePersist(Persist::CREATED);
		$this->assertEquals(Persist::CREATED, $persist->getStatus());
	}
}

class ConcreteIdentifierTest extends PHPUnit_Framework_TestCase{
	private $_mIdentifier;
	
	public function setUp(){
		$this->_mIdentifier = new ConcreteIdentifier(NULL, Persist::IN_PROGRESS);
	}
	
	public function testConstructor(){
		$identifier = new ConcreteIdentifier(4321, Persist::CREATED);
		$this->assertEquals(4321, $identifier->getId());
		$this->assertEquals(Persist::CREATED, $identifier->getStatus());
		$this->assertNull($identifier->getName());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$identifier = new ConcreteIdentifier('hola', Persist::IN_PROGRESS);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$identifier = new ConcreteIdentifier(-1, Persist::IN_PROGRESS);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetName(){
		$this->_mIdentifier->setName('cualquier');
		$this->assertEquals('cualquier', $this->_mIdentifier->getName());
	}
	
	public function testSetName_Blank(){
		try{
			$this->_mIdentifier->setName('');
		} catch(ValidateException $e){ return; }
		$this->fail('Name exception expected.');
	}
	
	public function testSetData(){
		$this->_mIdentifier->setData('aja');
		$this->assertEquals('aja', $this->_mIdentifier->getName());
	}
	
	public function testSetData_BlankName(){
		try{
			$this->_mIdentifier->setData('');
		} catch(Exception $e){ return; }
		$this->fail('Name exception expected.');
	}
	
	public function testSave(){
		$this->_mIdentifier->setName('yeah');
		$this->_mIdentifier->save();
		$this->assertEquals(Persist::CREATED, $this->_mIdentifier->getStatus());
	}
	
	public function testSave_NoName(){
		try{
			$this->_mIdentifier->save();
		} catch(ValidateException $e){
			$this->assertEquals('name', $e->getProperty());
			return; 
		}
		$this->fail('Name exception expected.');
	}
}

class ConcretePersistDocumentTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$document = new ConcretePersistDocument(4321, Persist::CREATED);
		$this->assertEquals(4321, $document->getId());
		$this->assertEquals(Persist::CREATED, $document->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$document = new ConcretePersistDocument('dfs', Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$document = new ConcretePersistDocument(0, Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected');
	}
}
?>