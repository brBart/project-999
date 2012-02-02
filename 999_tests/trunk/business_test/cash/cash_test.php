<?php
require_once('business/cash.php');
require_once('business/document.php');
require_once('business/user_account.php');
require_once('business/product.php');
require_once('business/agent.php');
require_once('business/transaction.php');
require_once('business/session.php');
require_once('business/event.php');
require_once('PHPUnit/Framework/TestCase.php');

class BankTest extends PHPUnit_Framework_TestCase{
	public function testConstructor(){
		$bank = new Bank(321, PersistObject::CREATED);
		$this->assertEquals($bank->getId(), 321);
		$this->assertEquals($bank->getStatus(), PersistObject::CREATED);
	}
	
	public function testConstructor_Defaults(){
		$bank = new Bank();
		$this->assertNull($bank->getId());
		$this->assertEquals($bank->getStatus(), PersistObject::IN_PROGRESS);
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$bank = new Bank('hola');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$bank = new Bank(-6);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$bank = Bank::getInstance(123);
		$this->assertEquals($bank->getName(), 'GyT Continental');
		$this->assertEquals($bank->getStatus(), PersistObject::CREATED);
	}
	
	public function testDelete_New(){
		$bank = new Bank();
		try{
			Bank::delete($bank);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$bank = Bank::getInstance(123);
		Bank::delete($bank);
	}
	
	public function testSave_Insert(){
		$bank = new Bank();
		$bank->setName('Bi');
		$bank->save();
		$this->assertEquals(123, $bank->getId());
	}
	
	public function testSave_Update(){
		$bank = Bank::getInstance(123);
		$bank->setData('Bi');
		$bank->save();
		$other_bank = Bank::getInstance(123);
		$this->assertEquals('Bi', $other_bank->getName());
	}
}

class BankAccountTest extends PHPUnit_Framework_TestCase{
	private $_mBankAccount;
	
	public function setUp(){
		$this->_mBankAccount = new BankAccount();
	}
	
	public function tearDown(){
	}
	
	public function testConstructor(){
		$bank_account = new BankAccount('4321', PersistObject::CREATED);
		$this->assertEquals($bank_account->getNumber(), '4321');
		$this->assertEquals($bank_account->getStatus(), PersistObject::CREATED);
		$this->assertNull($bank_account->getHolderName());
		$this->assertNull($bank_account->getBank());
	}
	
	public function testConstructor_Defaults(){
		$bank_account = new BankAccount();
		$this->assertNull($bank_account->getNumber());
		$this->assertEquals($bank_account->getStatus(), PersistObject::IN_PROGRESS);
		$this->assertNull($bank_account->getHolderName());
		$this->assertNull($bank_account->getBank());
	}
	
	public function testConstructor_BlankNumber(){
		try{
			$bank_account = new BankAccount('');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetNumber(){
		$this->_mBankAccount->setNumber('43-1');
		$this->assertEquals($this->_mBankAccount->getNumber(), '43-1');
	}
	
	public function testSetNumber_NotNewBankAccount(){
		$bank_account = BankAccount::getInstance('123');
		try{
			$bank_account->setNumber('7859');
		} catch(Exception $e){ return; }
		$this->fail('BankAccount exception expected.');
	}
	
	public function testSetNumber_Blank(){
		try{
			$this->_mBankAccount->setNumber('');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetNumber_InUseNumber(){
		try{
			$this->_mBankAccount->setNumber('123');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetBank(){
		$bank = Bank::getInstance(123);
		$this->_mBankAccount->setBank($bank);
		$this->assertEquals($this->_mBankAccount->getBank(), $bank);
	}
	
	public function testSetHolderName(){
		$this->_mBankAccount->setHolderName('Roberto');
		$this->assertEquals($this->_mBankAccount->getHolderName(), 'Roberto');
	}
	
	public function testSetHolderName_Blank(){
		try{
			$this->_mBankAccount->setHolderName('');
		} catch(Exception $e){ return; }
		$this->fail('HolderName exception expected.');
	}
	
	public function testSetData(){
		$bank = Bank::getInstance(123);
		$this->_mBankAccount->setData('Carlos', $bank);
		$this->assertEquals($this->_mBankAccount->getHolderName(), 'Carlos');
		$this->assertEquals($this->_mBankAccount->getBank(), $bank);
	}
	
	public function testSetData_BlankHolderName(){
		$bank = Bank::getInstance(123);
		try{
			$this->_mBankAccount->setData('', $bank);
		} catch(Exception $e){ return; }
		$this->fail('HolderName exception expected.');
	}
	
	public function testSave(){
		$this->_mBankAccount->setNumber('4321');
		$this->_mBankAccount->setHolderName('Carlos');
		$bank = Bank::getInstance(123);
		$this->_mBankAccount->setBank($bank);
		$this->_mBankAccount->save();
		$this->assertEquals($this->_mBankAccount->getStatus(), PersistObject::CREATED);
	}
	
	public function testSave_NoNumber(){
		$this->_mBankAccount->setHolderName('Carlos');
		$bank = Bank::getInstance(123);
		$this->_mBankAccount->setBank($bank);
		try{
			$this->_mBankAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testSave_NoHolderName(){
		$this->_mBankAccount->setNumber('4321');
		$bank = Bank::getInstance(123);
		$this->_mBankAccount->setBank($bank);
		try{
			$this->_mBankAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testSave_NoBank(){
		$this->_mBankAccount->setNumber('4321');
		$this->_mBankAccount->setHolderName('Carlos');
		try{
			$this->_mBankAccount->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testGetInstance(){
		$bank_account = BankAccount::getInstance('123');
		$this->assertEquals('123', $bank_account->getNumber());
	}
	
	public function testDelete_New(){
		try{
			BankAccount::delete($this->_mBankAccount);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$bank_account = BankAccount::getInstance('123');
		BankAccount::delete($bank_account);
	}
}

class ShiftTest extends PHPUnit_Framework_TestCase{
	private $_mShift;
	
	public function setUp(){
		$this->_mShift = new Shift();
	}
	
	public function tearDown(){
	}
	
	public function testConstructor(){
		$shift = new Shift(4321, PersistObject::CREATED);
		$this->assertEquals(4321, $shift->getId());
		$this->assertEquals(PersistObject::CREATED, $shift->getStatus());
		$this->assertNull($shift->getTimeTable());
	}
	
	public function testConstructor_Defaults(){
		$shift = new Shift();
		$this->assertNull($shift->getId());
		$this->assertEquals(PersistObject::IN_PROGRESS, $shift->getStatus());
		$this->assertNull($shift->getTimeTable());
	}

	public function testConstructor_BadIdTxt(){
		try{
			$shift = new Shift('adsd');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$shift = new Shift(0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetTimeTable(){
		$this->_mShift->setTimeTable('8am - 6pm');
		$this->assertEquals('8am - 6pm', $this->_mShift->getTimeTable());
	}
	
	public function testSetTimeTable_Blank(){
		try{
			$this->_mShift->setTimeTable('');
		} catch(Exception $e){ return; }
		$this->fail('TimeTable exception expected.');
	}
	
	public function testSetData(){
		$this->_mShift->setData('Diurno', '8am');
		$this->assertEquals('Diurno', $this->_mShift->getName());
		$this->assertEquals('8am', $this->_mShift->getTimeTable());
	}
	
	public function testSetData_BlankName(){
		try{
			$this->_mShift->setData('', '8am');
		} catch(Exception $e){ return; }
		$this->fail('Name exception expected.');
	}
	
	public function testSetData_BlankTimeTable(){
		try{
			$this->_mShift->setData('Diurno', '');
		} catch(Exception $e){ return; }
		$this->fail('TimeTable exception expected.');
	}
		
	public function testGetInstance(){
		$shift = Shift::getInstance(123);
		$this->assertEquals('Diurno', $shift->getName());
	}
	
	public function testDelete_New(){
		try{
			Shift::delete($this->_mShift);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$shift = Shift::getInstance(123);
		Shift::delete($shift);
	}
	
	public function testSave_Insert(){
		$this->_mShift->setName('Diurno');
		$this->_mShift->setTimeTable('8am');
		$this->_mShift->save();
		$this->assertEquals(123, $this->_mShift->getId());
	}
	
	public function testSave_Update(){
		$shift = Shift::getInstance(123);
		$shift->setName('Medio');
		$shift->save();
		$other = Shift::getInstance(123);
		$this->assertEquals('Medio', $other->getName());
	}
	
	public function testSave_NoName(){
		try{
			$this->_mShift->setTimeTable('8am');
			$this->_mShift->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testSave_NoTimeTable(){
		try{
			$this->_mShift->setName('Diurno');
			$this->_mShift->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
}

class CashRegisterTest extends PHPUnit_Framework_TestCase{
	private $_mCashRegister;
	
	public function setUp(){
		$shift = Shift::getInstance(123);
		$this->_mCashRegister = new CashRegister($shift);
	}
	
	public function testConstructor(){
		$shift = Shift::getInstance(123);
		$cash_register = new CashRegister($shift, 4321, Persist::CREATED);
		$this->assertEquals($shift, $cash_register->getShift());
		$this->assertEquals(4321, $cash_register->getId());
		$this->assertFalse($cash_register->isOpen());
		$this->assertEquals(Persist::CREATED, $cash_register->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$shift = Shift::getInstance(123);
		$cash_register = new CashRegister($shift);
		$this->assertEquals($shift, $cash_register->getShift());
		$this->assertNull($cash_register->getId());
		$this->assertFalse($cash_register->isOpen());
		$this->assertEquals(Persist::IN_PROGRESS, $cash_register->getStatus());
	}
	
	public function testConstructor_BadId(){
		$shift = Shift::getInstance(123);
		try{
			$cash_register = new CashRegister($shift, -2);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$cash_register = CashRegister::getInstance(123);
		$this->assertEquals(123, $cash_register->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$cash_register = CashRegister::getInstance('hola');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNegative(){
		try{
			$cash_register = CashRegister::getInstance(-4);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testClose(){
		$cash_register = CashRegister::getInstance(126);
		$cash_register->close();
		$this->assertFalse($cash_register->isOpen());
	}
}

class PaymentCardTypeTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$type = new PaymentCardType(321, PersistObject::CREATED);
		$this->assertEquals(321, $type->getId());
		$this->assertEquals(PersistObject::CREATED, $type->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$type = new PaymentCardType();
		$this->assertNull($type->getId());
		$this->assertEquals(PersistObject::IN_PROGRESS, $type->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$type = new PaymentCardType('hola');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$type = new PaymentCardType(0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$type = PaymentCardType::getInstance(123);
		$this->assertEquals('Credito',$type->getName());
		$this->assertEquals(PersistObject::CREATED, $type->getStatus());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$type = PaymentCardType::getInstance('adios');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$type = PaymentCardType::getInstance(-1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testDelete_New(){
		$type = new PaymentCardType();
		try{
			PaymentCardType::delete($type);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$type = PaymentCardType::getInstance(123);
		PaymentCardType::delete($type);
	}
	
	public function testSave_Insert(){
		$type = new PaymentCardType();
		$type->setName('Debito');
		$type->save();
		$this->assertEquals(123, $type->getId());
	}
	
	public function testSave_Update(){
		$type = PaymentCardType::getInstance(123);
		$type->setData('Debito');
		$type->save();
		$other_type = PaymentCardType::getInstance(123);
		$this->assertEquals('Debito', $other_type->getName());
	}
}

class PaymentCardBrandTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$brand = new PaymentCardBrand(321, PersistObject::CREATED);
		$this->assertEquals(321, $brand->getId());
		$this->assertEquals(PersistObject::CREATED, $brand->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$brand = new PaymentCardBrand();
		$this->assertNull($brand->getId());
		$this->assertEquals(PersistObject::IN_PROGRESS, $brand->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$brand = new PaymentCardBrand('hola');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$brand = new PaymentCardBrand(0);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance(){
		$brand = PaymentCardBrand::getInstance(123);
		$this->assertEquals('Visa',$brand->getName());
		$this->assertEquals(PersistObject::CREATED, $brand->getStatus());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$brand = PaymentCardBrand::getInstance('adios');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$brand = PaymentCardBrand::getInstance(-1);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testDelete_New(){
		$brand = new PaymentCardBrand();
		try{
			PaymentCardBrand::delete($brand);
		} catch(Exception $e){ return; }
		$this->fail('Delete exception expected.');
	}
	
	public function testDelete_NotNew(){
		$brand = PaymentCardBrand::getInstance(123);
		PaymentCardBrand::delete($brand);
	}
	
	public function testSave_Insert(){
		$brand = new PaymentCardBrand();
		$brand->setName('MasterCard');
		$brand->save();
		$this->assertEquals(123, $brand->getId());
	}
	
	public function testSave_Update(){
		$brand = PaymentCardBrand::getInstance(123);
		$brand->setData('MasterCard');
		$brand->save();
		$other_brand = PaymentCardBrand::getInstance(123);
		$this->assertEquals('MasterCard', $other_brand->getName());
	}
}

class PaymentCardTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = new PaymentCard(5357, $type, $brand, 'Roberto', '20/01/2009');
		$this->assertEquals(5357, $card->getNumber());
		$this->assertEquals($type, $card->getType());
		$this->assertEquals($brand, $card->getBrand());
		$this->assertEquals('Roberto', $card->getHolderName());
		$this->assertEquals('20/01/2009', $card->getExpirationDate());
	}
	
	public function testConstructor_BadNumberTxt(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = new PaymentCard('sdf', $type, $brand, 'Roberto', '20/01/2009');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testConstructor_BadNumberNoPositive(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = new PaymentCard(0, $type, $brand, 'Roberto', '20/01/2009');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testConstructor_BlankHolderName(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = new PaymentCard(5357, $type, $brand, '', '20/01/2009');
		} catch(Exception $e){ return; }
		$this->fail('HolderName exception expected.');
	}
	
	public function testConstructor_BadDate(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = new PaymentCard(5357, $type, $brand, 'Roberto', '2001/2009');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testCreate(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		$this->assertEquals(5357, $card->getNumber());
		$this->assertEquals($type, $card->getType());
		$this->assertEquals($brand, $card->getBrand());
		$this->assertEquals('Roberto', $card->getHolderName());
		$this->assertEquals('01/01/2020', $card->getExpirationDate());
	}
	
	public function testCreate_BadNumberTxt(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create('sdf', $type, $brand, 'Roberto', '20/01/2020');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testCreate_BadNumberNoPositive(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(0, $type, $brand, 'Roberto', '20/01/2020');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testCreate_NewType(){
		$type = new PaymentCardType();
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '20/01/2020');
		} catch(Exception $e){ return; }
		$this->fail('Type exception expected.');
	}
	
	public function testCreate_NewBrand(){
		$type = PaymentCardType::getInstance(123);
		$brand = new PaymentCardBrand();
		try{
			$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '20/01/2020');
		} catch(Exception $e){ return; }
		$this->fail('Brand exception expected.');
	}
	
	public function testCreate_BlankHolderName(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(5357, $type, $brand, '', '20/01/2020');
		} catch(Exception $e){ return; }
		$this->fail('HolderName exception expected.');
	}
	
	public function testCreate_BadDate(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '2001/2020');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testCreate_BadDateExpirated(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/09');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testCreate_BadDateExpirated_2(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		try{
			$card = PaymentCard::create(5357, $type, $brand, 'Roberto', date('m/y'));
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
}

class VoucherTest extends PHPUnit_Framework_TestCase{
	private $_mVoucher;
	
	public function setUp(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		$this->_mVoucher = new Voucher('2323', $card, 56.28);
	}
	
	public function testConstructor(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		$voucher = new Voucher('2323', $card, 56.28);
		$this->assertEquals('2323', $voucher->getTransactionNumber());
		$this->assertEquals($card, $voucher->getPaymentCard());
		$this->assertEquals(56.28, $voucher->getAmount());
		
		$data = $voucher->show();
		$this->assertEquals('Debito', $data['type']);
		$this->assertEquals('MasterCard', $data['brand']);
		$this->assertEquals(5357, $data['number']);
		$this->assertEquals('Roberto', $data['name']);
		$this->assertEquals(56.28, $data['amount']);
		$this->assertEquals('01/01/2020', $data['expiration_date']);
	}
	
	public function testConstructor_BlankTransactionNumber(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		try{
			$voucher = new Voucher('', $card, 56.28);
		} catch(Exception $e){ return; }
		$this->fail('Transaction exception expected.');
	}
	
	public function testConstructor_BadAmountTxt(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		try{
			$voucher = new Voucher('2323', $card, 'sdfs');
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
	
	public function testConstructor_BadNoPositive(){
		$type = PaymentCardType::getInstance(123);
		$brand = PaymentCardBrand::getInstance(123);
		$card = PaymentCard::create(5357, $type, $brand, 'Roberto', '01/20');
		try{
			$voucher = new Voucher('2323', $card, 0.00);
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
}

class CashTest extends PHPUnit_Framework_TestCase{
	private $_mCash;
	
	public function setUp(){
		$this->_mCash = new Cash(95.55, 123, NULL, NULL, Persist::CREATED);
	}
	
	public function testConstructor(){
		$cash = new Cash(23.56, 431, NULL, NULL, Persist::CREATED);
		$this->assertEquals(23.56, $cash->getAmount());
		$this->assertEquals(0, $cash->getAvailable());
		$this->assertEquals(431, $cash->getId());
		$this->assertEquals(Persist::CREATED, $cash->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$cash = new Cash(23.56);
		$this->assertEquals(23.56, $cash->getAmount());
		$this->assertEquals(0, $cash->getAvailable());
		$this->assertNull($cash->getId());
		$this->assertEquals(Persist::IN_PROGRESS, $cash->getStatus());
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$cash = new Cash(23.56, 'sdf', Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$cash = new Cash(23.56, 0, Persist::CREATED);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testReserve(){
		$this->_mCash->reserve(10.00);
		$this->assertEquals(75.55, $this->_mCash->getAvailable());
	}
	
	public function testDecreseReserve(){
		$this->_mCash->decreaseReserve(5.55);
		$this->assertEquals(81.10, $this->_mCash->getAvailable());
	}
	
	public function testDeposit(){
		$this->_mCash->deposit(15.90);
		$this->assertEquals(number_format(65.20, 2), number_format($this->_mCash->getAvailable(), 2));
	}
	
	public function testDecreaseDeposit(){
		$this->_mCash->decreaseDeposit(9.85);
		$this->assertEquals(75.05, $this->_mCash->getAvailable());
	}
	
	public function testGetInstance(){
		$cash = Cash::getInstance(123);
		$this->assertEquals(123, $cash->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$cash = Cash::getInstance('ef');
		} catch(Exception $e){ return; }
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$cash = Cash::getInstance(0);
		} catch(Exception $e){ return; }
	}
}

class CashReceiptTest extends PHPUnit_Framework_TestCase{
	private $_mCashReceipt;
	
	public function setUp(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$invoice->setCustomer(Customer::getInstance('cf'));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(4322), new Withdraw(), 1, 1.00));
		
		$this->_mCashReceipt = $invoice->createCashReceipt();
	}
	
	public function testConstructor(){
		$invoice = Invoice::getInstance(123, $pages, $items);
		$receipt = new CashReceipt($invoice, 4321, PersistDocument::CREATED);
		$this->assertEquals(0, $receipt->getCash()->getAmount());
		$this->assertEquals(0, $receipt->getChange());
		$this->assertEquals(0, $receipt->getTotalVouchers());
		$this->assertEquals($invoice, $receipt->getInvoice());
		$this->assertEquals(0, $receipt->getTotal());
		$this->assertNull($receipt->getVoucher('dsfg'));
		$vouchers = $receipt->getVouchers();
		$this->assertTrue(empty($vouchers));
		$this->assertEquals(4321, $receipt->getId());
		$this->assertEquals(PersistDocument::CREATED, $receipt->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		RetailEvent::apply(Product::getInstance(123), $invoice, 1);
		
		$receipt = new CashReceipt($invoice);
		$this->assertEquals(0, $receipt->getCash()->getAmount());
		$this->assertEquals(0, $receipt->getChange());
		$this->assertEquals(0, $receipt->getTotalVouchers());
		$this->assertEquals($invoice, $receipt->getInvoice());
		$this->assertEquals(0, $receipt->getTotal());
		$this->assertNull($receipt->getVoucher('dsfg'));
		$vouchers = $receipt->getVouchers();
		$this->assertTrue(empty($vouchers));
		$this->assertNull($receipt->getId());
		$this->assertEquals(PersistDocument::IN_PROGRESS, $receipt->getStatus());
	}
	
	public function testSetCash(){
		$cash = new Cash(23.57);
		$this->_mCashReceipt->setCash($cash);
		$this->assertEquals($cash, $this->_mCashReceipt->getCash());
		$this->assertEquals(23.57, $this->_mCashReceipt->getTotal());
	}
	
	public function testSetChange(){
		$this->_mCashReceipt->setChange(1.55);
		$this->assertEquals(1.55, $this->_mCashReceipt->getChange());
	}
	
	public function testSetChange_BadTxt(){
		try{
			$this->_mCashReceipt->setChange('sdf');
		} catch(Exception $e){ return; }
		$this->fail('Change exception expected.');
	}
	
	public function testSetChange_Negative(){
		try{
			$this->_mCashReceipt->setChange(-3.00);
		} catch(Exception $e){ return; }
		$this->fail('Change exception expected.');
	}
	
	public function testSetData(){
		$cash = new Cash(95.55, 123, NULL, NULL, Persist::CREATED);
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$vouchers[] = new Voucher('235', $card, 25.20);
		$this->_mCashReceipt->setData($cash, 25.20, 4.31, $vouchers);
		$this->assertEquals($cash, $this->_mCashReceipt->getCash());
		$this->assertEquals(25.20, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(4.31, $this->_mCashReceipt->getChange());
		$this->assertEquals($vouchers, $this->_mCashReceipt->getVouchers());
		$this->assertEquals($vouchers[0], $this->_mCashReceipt->getVoucher('235'));
		$this->assertEquals(120.75, $this->_mCashReceipt->getTotal());
	}
	
	public function testSetData_Defaults(){
		$cash = new Cash(0.0, 999, NULL, NULL, Persist::CREATED);
		$this->_mCashReceipt->setData($cash);
		$this->assertEquals($cash, $this->_mCashReceipt->getCash());
		$this->assertEquals(0, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(0, $this->_mCashReceipt->getChange());
		$vouchers = $this->_mCashReceipt->getVouchers();
		$this->assertTrue(empty($vouchers));
		$this->assertNull($this->_mCashReceipt->getVoucher('235'));
		$this->assertEquals(0, $this->_mCashReceipt->getTotal());
	}
	
	public function testSetData_BadTotalVouchersTxt(){
		$cash = new Cash(0.0, 123, Persist::CREATED);
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$vouchers[] = new Voucher('235', $card, 25.20);
		try{
			$this->_mCashReceipt->setData($cash, 'sdf', 4.31, $vouchers);
		} catch(Exception $e){ return; }
		$this->fail('Total Vouchers exception expected.');
	}
	
	public function testSetData_BadTotalVouchersNegative(){
		$cash = new Cash(0.0, 123, Persist::CREATED);
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$vouchers[] = new Voucher('235', $card, 25.20);
		try{
			$this->_mCashReceipt->setData($cash, -12.34, 4.31, $vouchers);
		} catch(Exception $e){ return; }
		$this->fail('Total Vouchers exception expected.');
	}
	
	public function testSetData_BadChangeTxt(){
		$cash = new Cash(0.0, 123, Persist::CREATED);
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$vouchers[] = new Voucher('235', $card, 25.20);
		try{
			$this->_mCashReceipt->setData($cash, 25.20, 'sdf', $vouchers);
		} catch(Exception $e){ return; }
		$this->fail('Change exception expected.');
	}
	
	public function testSetData_BadChangeNegative(){
		$cash = new Cash(0.0, 123, Persist::CREATED);
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$vouchers[] = new Voucher('235', $card, 25.20);
		try{
			$this->_mCashReceipt->setData($cash, 12.34, -4.31, $vouchers);
		} catch(Exception $e){ return; }
		$this->fail('Change exception expected.');
	}
	
	public function testSetData_NoVouchers(){
		$cash = new Cash(0.0, 123, Persist::CREATED);
		try{
			$this->_mCashReceipt->setData($cash, 12.34, 4.31);
		} catch(Exception $e){ return; }
		$this->fail('Vouchers exception expected.');
	}
	
	public function testAddVoucher(){
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$voucher = new Voucher('6543', $card, 23.56);
		$this->_mCashReceipt->addVoucher($voucher);
		$this->assertEquals(23.56, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(23.56, $this->_mCashReceipt->getTotal());
		$this->assertEquals($voucher, $this->_mCashReceipt->getVoucher('6543'));
		
		$voucher3 = new Voucher('6541', $card, 13.50);
		$this->_mCashReceipt->addVoucher($voucher3);
		$this->assertEquals(37.06, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(37.06, $this->_mCashReceipt->getTotal());
		$this->assertEquals(2, count($this->_mCashReceipt->getVouchers()));
		$this->assertEquals($voucher3, $this->_mCashReceipt->getVoucher('6541'));
	}
	
	public function testDeleteVoucher(){
		$card = new PaymentCard(9876, PaymentCardType::getInstance(123), PaymentCardBrand::getInstance(123),
				'Jose', '01/12/2010');
		$this->_mCashReceipt->addVoucher(new Voucher('6543', $card, 23.56));
		$this->assertEquals(23.56, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(23.56, $this->_mCashReceipt->getTotal());
		
		$this->_mCashReceipt->addVoucher(new Voucher('6541', $card, 23.00));
		$this->assertEquals(46.56, $this->_mCashReceipt->getTotalVouchers());
		$this->assertEquals(46.56, $this->_mCashReceipt->getTotal());
		$this->assertEquals(2, count($this->_mCashReceipt->getVouchers()));
		
		$voucher = $this->_mCashReceipt->getVoucher('6543');
		$this->_mCashReceipt->deleteVoucher($voucher);
		$this->assertEquals(number_format(23, 2), number_format($this->_mCashReceipt->getTotalVouchers(), 2));
		$this->assertEquals(number_format(23, 2), number_format($this->_mCashReceipt->getTotal(), 2));
		$this->assertEquals(1, count($this->_mCashReceipt->getVouchers()));
		
		$voucher = $this->_mCashReceipt->getVoucher('6541');
		$this->_mCashReceipt->deleteVoucher($voucher);
		$this->assertEquals(0, number_format($this->_mCashReceipt->getTotalVouchers()));
		$this->assertEquals(0, number_format($this->_mCashReceipt->getTotal()));
		$this->assertEquals(0, count($this->_mCashReceipt->getVouchers()));
	}
	
	public function testSave(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$invoice->setCustomer(Customer::getInstance('1725045-5'));
		RetailEvent::apply(Product::getInstance(123), $invoice, 3);
		
		$receipt = $invoice->createCashReceipt();
		$receipt->setCash(new Cash(37.95));
		$receipt->save();
		$this->assertEquals(PersistDocument::CREATED, $receipt->getStatus());
	}
	
	public function testSave_2(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$invoice->setCustomer(Customer::getInstance('1725045-5'));
		RetailEvent::apply(Product::getInstance(123), $invoice, 5);
		
		$receipt = $invoice->createCashReceipt();
		$card = PaymentCard::create(9876, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Jose', '01/14/2010');
		$receipt->addVoucher(new Voucher('6541', $card, 50.60));
		$receipt->save();
		$this->assertEquals(PersistDocument::CREATED, $receipt->getStatus());
	}
	
	public function testSave_3(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$invoice->setCustomer(Customer::getInstance('1725045-5'));
		RetailEvent::apply(Product::getInstance(124), $invoice, 7);
		RetailEvent::apply(Product::getInstance(124), $invoice, 7);
		RetailEvent::apply(Product::getInstance(125), $invoice, 7);
		RetailEvent::apply(Product::getInstance(125), $invoice, 7);
		
		$receipt = $invoice->createCashReceipt();
		$receipt->setCash(new Cash(837.02));
		$receipt->save();
		$this->assertEquals(PersistDocument::CREATED, $receipt->getStatus());
	}
	
	public function testSave_NoCashNoVouchers(){
		$cash_register = CashRegister::getInstance(123);
		$invoice = new Invoice($cash_register);
		$invoice->setCustomer(Customer::getInstance('1725045-5'));
		RetailEvent::apply(Product::getInstance(123), $invoice, 1);
		
		$receipt = $invoice->createCashReceipt();
		try{
			$receipt->save();
		} catch(Exception $e){ return; }
		$this->fail('Save exception expected.');
	}
	
	public function testCancel(){
		$user = UserAccount::getInstance('roboli');
		$invoice = new Invoice(CashRegister::getInstance(123), '23/04/2009 22:10:21', $user,
				876, PersistDocument::CANCELLED);
		$receipt = new CashReceipt($invoice, 542, PersistDocument::CREATED);
		$receipt->setData(new Cash(0.00, 542, NULL, NULL, Persist::CREATED));
		$receipt->cancel($user);
		$this->assertEquals(PersistDocument::CANCELLED, $receipt->getStatus());
	}
	
	public function testGetInstance(){
		$invoice = Invoice::getInstance(123, $pages, $items);
		$receipt = CashReceipt::getInstance($invoice);
		$this->assertEquals(123, $receipt->getId());
	}
}

class DepositDetailTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$cash = new Cash(45.23, 432, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 18.12);
		$this->assertEquals($cash, $detail->getCash());
		$this->assertEquals(18.12, $detail->getAmount());
		$data = $detail->show();
		$this->assertEquals(432, $data['receipt_id']);
		$this->assertEquals(45.23, $data['received']); 
		$this->assertEquals(18.12, $data['deposited']);
	}
	
	public function testConstructor_BadAmountTxt(){
		$cash = new Cash(45.23, 432, Persist::CREATED);
		try{
			$detail = new DepositDetail($cash, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
	
	public function testConstructor_BadAmountNoPositive(){
		$cash = new Cash(45.23, 432, Persist::CREATED);
		try{
			$detail = new DepositDetail($cash, 0.00);
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
	
	public function testIncrease(){
		$cash = new Cash(45.23, 432, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 18.12);
		$detail->increase(5.20);
		$this->assertEquals(23.32, $detail->getAmount());
	}
	
	public function testApply(){
		$cash = new Cash(1.0, 123, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 9.50);
		$detail->apply();
		$this->assertEquals(75.05, $cash->getAvailable());
	}
	
	public function testCancel(){
		$cash = new Cash(1.0, 123, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 9.50);
		$detail->cancel();
		$this->assertEquals(84.55, $cash->getAvailable());
	}
}

class DepositTest extends PHPUnit_Framework_TestCase{
	private $_mDeposit;
	
	public function setUp(){
		$this->_mDeposit = new Deposit(CashRegister::getInstance(123));
	}
	
	public function testConstructor(){
		$cash_register = CashRegister::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$deposit = new Deposit($cash_register, '21/04/2009 00:00:00', $user, 432, Deposit::CREATED);
		$this->assertNull($deposit->getNumber());
		$this->assertEquals('21/04/2009 00:00:00', $deposit->getDateTime());
		$this->assertNull($deposit->getBankAccount());
		$this->assertEquals($cash_register, $deposit->getCashRegister());
		$this->assertEquals(0, $deposit->getTotal());
		$this->assertEquals($user, $deposit->getUser());
		$this->assertNull($deposit->getDetail(42211));
		$details = $deposit->getDetails();
		$this->assertTrue(empty($details));
		$this->assertEquals(432, $deposit->getId());
		$this->assertEquals(Deposit::CREATED, $deposit->getStatus());
	}
	
	public function testConstructor_Defaults(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$cash_register = CashRegister::getInstance(123);
		$deposit = new Deposit($cash_register);
		$this->assertNull($deposit->getNumber());
		$this->assertNull($deposit->getDateTime());
		$this->assertNull($deposit->getBankAccount());
		$this->assertEquals($cash_register, $deposit->getCashRegister());
		$this->assertEquals(0, $deposit->getTotal());
		$this->assertEquals($user, $deposit->getUser());
		$this->assertNull($deposit->getDetail(42211));
		$details = $deposit->getDetails();
		$this->assertTrue(empty($details));
		$this->assertNull($deposit->getId());
		$this->assertEquals(Deposit::IN_PROGRESS, $deposit->getStatus());
	}
	
	public function testConstructor_ClosedCashRegister(){
		$cash_register = CashRegister::getInstance(124);
		$cash_register->close();
		try{
			$deposit = new Deposit($cash_register);
		} catch(Exception $e){ return; }
		$this->fail('Cash Register exception expected.');
	}
	
	public function testConstructor_BadDate(){
		try{
			$deposit = new Deposit(CashRegister::getInstance(123), '23/md/32');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testConstructor_BadIdTxt(){
		try{
			$deposit = new Deposit(CashRegister::getInstance(123), NULL, NULL, 'hello');
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testConstructor_BadIdNoPositive(){
		try{
			$deposit = new Deposit(CashRegister::getInstance(123), NULL, NULL, -5);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testSetNumber(){
		$this->_mDeposit->setNumber('764');
		$this->assertEquals('764', $this->_mDeposit->getNumber());
	}
	
	public function testSetNumber_Blank(){
		try{
			$this->_mDeposit->setNumber('');
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetBankAccount(){
		$bank_account = BankAccount::getInstance('123');
		$this->_mDeposit->setBankAccount($bank_account);
		$this->assertEquals($bank_account, $this->_mDeposit->getBankAccount());
	}
	
	public function testSetData(){
		$bank_account = BankAccount::getInstance('123');
		$details[] = new DepositDetail(new Cash(5.45, 654, NULL, NULL, Persist::CREATED), 5.45);
		$this->_mDeposit->setData('829', $bank_account, 23.87, $details);
		$this->assertEquals('829', $this->_mDeposit->getNumber());
		$this->assertEquals($bank_account, $this->_mDeposit->getBankAccount());
		$this->assertEquals(23.87, $this->_mDeposit->getTotal());
		$this->assertEquals($details, $this->_mDeposit->getDetails());
		$this->assertEquals($details[0], $this->_mDeposit->getDetail(654));
	}
	
	public function testSetData_BlankNumber(){
		$bank_account = BankAccount::getInstance('123');
		$details[] = new DepositDetail(new Cash(5.45, 654, NULL, NULL, Persist::CREATED), 5.45);
		try{
			$this->_mDeposit->setData('', $bank_account, 23.87, $details);
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSetData_BadTotalTxt(){
		$bank_account = BankAccount::getInstance('123');
		$details[] = new DepositDetail(new Cash(5.45, 654, NULL, NULL, Persist::CREATED), 5.45);
		try{
			$this->_mDeposit->setData('829', $bank_account, 'sdf', $details);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_BadTotalNoPositive(){
		$bank_account = BankAccount::getInstance('123');
		$details[] = new DepositDetail(new Cash(5.45, 654, NULL, NULL, Persist::CREATED), 5.45);
		try{
			$this->_mDeposit->setData('829', $bank_account, 0.00, $details);
		} catch(Exception $e){ return; }
		$this->fail('Total exception expected.');
	}
	
	public function testSetData_EmptyDetails(){
		$bank_account = BankAccount::getInstance('123');
		try{
			$this->_mDeposit->setData('829', $bank_account, 48.28, $details);
		} catch(Exception $e){ return; }
		$this->fail('Details exception expected.');
	}
	
	public function testAddDetail(){
		$cash = new Cash(20.00, 432, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 10.00);
		$this->_mDeposit->addDetail($detail);
		$this->assertEquals(1, count($this->_mDeposit->getDetails()));
		$this->assertEquals(10.00, $this->_mDeposit->getTotal());
		
		$cash2 = new Cash(23.75, 431, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash2, 11.69);
		$this->_mDeposit->addDetail($detail);
		$this->assertEquals(2, count($this->_mDeposit->getDetails()));
		$this->assertEquals(number_format(21.69, 2), number_format($this->_mDeposit->getTotal(), 2));
		
		$detail = new DepositDetail($cash, 5.55);
		$this->_mDeposit->addDetail($detail);
		$this->assertEquals(2, count($this->_mDeposit->getDetails()));
		$this->assertEquals(27.24, $this->_mDeposit->getTotal());
		
		$cash3 = new Cash(383.28, 430, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash3, 82.18);
		$this->_mDeposit->addDetail($detail);
		$this->assertEquals(3, count($this->_mDeposit->getDetails()));
		$this->assertEquals(number_format(109.42, 2), number_format($this->_mDeposit->getTotal(), 2));
	}
	
	public function testDeleteDetail(){
		$cash = new Cash(20.00, 432, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 10.00);
		$this->_mDeposit->addDetail($detail);
		
		$cash2 = new Cash(23.75, 431, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash2, 11.69);
		$this->_mDeposit->addDetail($detail);
		
		$detail = new DepositDetail($cash, 5.55);
		$this->_mDeposit->addDetail($detail);
		
		$cash = new Cash(383.28, 430, NULL, NULL, Persist::CREATED);
		$detail = new DepositDetail($cash, 82.18);
		$this->_mDeposit->addDetail($detail);
		
		$cash = $this->_mDeposit->getDetail(432);
		$this->_mDeposit->deleteDetail($cash);
		$this->assertEquals(2, count($this->_mDeposit->getDetails()));
		$this->assertEquals(93.87, $this->_mDeposit->getTotal());
		
		$cash = $this->_mDeposit->getDetail(430);
		$this->_mDeposit->deleteDetail($cash);
		$this->assertEquals(1, count($this->_mDeposit->getDetails()));
		$this->assertEquals(number_format(11.69, 2), number_format($this->_mDeposit->getTotal(), 2));
		
		$cash = $this->_mDeposit->getDetail(431);
		$this->_mDeposit->deleteDetail($cash);
		$this->assertEquals(0, count($this->_mDeposit->getDetails()));
		$this->assertEquals(number_format(0, 2), number_format($this->_mDeposit->getTotal(), 2));
	}
	
	public function testSave(){
		$this->_mDeposit->setNumber('372');
		$this->_mDeposit->setBankAccount(BankAccount::getInstance('123'));
		$cash = new Cash(1.0, 124, NULL, NULL, Persist::CREATED);
		$this->_mDeposit->addDetail(new DepositDetail($cash, 20.50));
		$this->_mDeposit->save();
		$this->assertEquals(number_format(22.16, 2), number_format($cash->getAvailable(), 2));
		$this->assertEquals(123, $this->_mDeposit->getId());
		$this->assertEquals(Deposit::CREATED, $this->_mDeposit->getStatus());
	}
	
	public function testSave_NoNumber(){
		$this->_mDeposit->setBankAccount(BankAccount::getInstance('123'));
		$cash = new Cash(1.0, 124, NULL, NULL, Persist::CREATED);
		$this->_mDeposit->addDetail(new DepositDetail($cash, 20.50));
		try{
			$this->_mDeposit->save();
		} catch(Exception $e){ return; }
		$this->fail('Number exception expected.');
	}
	
	public function testSave_NoBankAccount(){
		$this->_mDeposit->setNumber('372');
		$cash = new Cash(1.0, 124, NULL, NULL, Persist::CREATED);
		$this->_mDeposit->addDetail(new DepositDetail($cash, 20.50));
		try{
			$this->_mDeposit->save();
		} catch(Exception $e){ return; }
		$this->fail('Bank Account exception expected.');
	}
	
	public function testSave_EmptyDetails(){
		$this->_mDeposit->setNumber('372');
		$this->_mDeposit->setBankAccount(BankAccount::getInstance('123'));
		try{
			$this->_mDeposit->save();
		} catch(Exception $e){ return; }
		$this->fail('Details Account exception expected.');
	}
	
	public function testConfirm(){
		$cash_register = CashRegister::getInstance(123);
		$user = UserAccount::getInstance('roboli');
		$deposit = new Deposit($cash_register, '21/04/2009 00:00:00', $user, 432, Deposit::CREATED);
		$deposit->confirm();
		$this->assertEquals(Deposit::CONFIRMED, $deposit->getStatus());
	}
	
	public function testDiscard(){
		$cash = new Cash(1.0, 125, NULL, NULL, Persist::CREATED);
		
		DepositEvent::apply($cash, $this->_mDeposit, 15.86);
		$this->assertEquals(number_format(70.00, 2), number_format($cash->getAvailable(), 2));
		
		DepositEvent::apply($cash, $this->_mDeposit, 10.50);
		$this->assertEquals(number_format(59.50, 2), number_format($cash->getAvailable(), 2));
		
		$this->_mDeposit->discard();
		$this->assertEquals(number_format(85.86, 2), number_format($cash->getAvailable(), 2));
	}
	
	public function testCancel(){
		$this->_mDeposit->setNumber('372');
		$this->_mDeposit->setBankAccount(BankAccount::getInstance('123'));
		$cash = new Cash(1.0, 125, NULL, NULL, Persist::CREATED);
		
		DepositEvent::apply($cash, $this->_mDeposit, 15.86);
		DepositEvent::apply($cash, $this->_mDeposit, 10.50);
		
		$this->_mDeposit->save();
		$this->assertEquals(number_format(59.50, 2), number_format($cash->getAvailable(), 2));
		
		$this->_mDeposit->cancel(UserAccount::getInstance('roboli'));
		$this->assertEquals(number_format(85.86, 2), number_format($cash->getAvailable(), 2));
		$this->assertEquals(Deposit::CANCELLED, $this->_mDeposit->getStatus());
	}
	
	public function testGetInstance(){
		$deposit = Deposit::getInstance(123, $total_pages, $total_items);
		$this->assertEquals(123, $deposit->getId());
	}
	
	public function testGetInstance_BadIdTxt(){
		try{
			$deposit = Deposit::getInstance('sdf',$total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadIdNoPositive(){
		try{
			$deposit = Deposit::getInstance(0,$total_pages, $total_items);
		} catch(Exception $e){ return; }
		$this->fail('Id exception expected.');
	}
	
	public function testGetInstance_BadPageTxt(){
		try{
			$deposit = Deposit::getInstance(34, $total_pages, $total_items, 'dfs');
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected.');
	}
	
	public function testGetInstance_BadPageNegative(){
		try{
			$deposit = Deposit::getInstance(32, $total_pages, $total_items, -23);
		} catch(Exception $e){ return; }
		$this->fail('Page exception expected.');
	}
}

class DepositEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$user = UserAccount::getInstance('roboli');
		$helper = InventorySession::getInstance();
		$helper->setUser($user);
		ActiveSession::setHelper($helper);
		
		$cash = new Cash(1.0, 123, NULL, NULL, Persist::CREATED);
		$deposit = new Deposit(CashRegister::getInstance(123));
		
		DepositEvent::apply($cash, $deposit, 20.00);
		$this->assertEquals(1, count($deposit->getDetails()));
		$this->assertEquals(number_format(64.55, 2), number_format($cash->getAvailable(), 2));
		
		DepositEvent::apply($cash, $deposit, 4.55);
		$this->assertEquals(1, count($deposit->getDetails()));
		$this->assertEquals(number_format(60, 2), number_format($cash->getAvailable(), 2));
	}
	
	public function testCancel(){
		$cash = new Cash(22.16, 124, NULL, NULL, Persist::CREATED);
		$deposit = new Deposit(CashRegister::getInstance(123));
		
		DepositEvent::apply($cash, $deposit, 20.00);
		$this->assertEquals(1, count($deposit->getDetails()));
		$this->assertEquals(number_format(2.16, 2), number_format($cash->getAvailable(), 2));
		
		$cash2 = new Cash(69.95, 123, NULL, NULL, Persist::CREATED);
		DepositEvent::apply($cash2, $deposit, 9.95);
		
		DepositEvent::cancel($deposit, $deposit->getDetail(124));
		$this->assertEquals(1, count($deposit->getDetails()));
		$this->assertEquals(number_format(22.16, 2), number_format($cash->getAvailable(), 2));
		$this->assertEquals(9.95, $deposit->getTotal());
		
		DepositEvent::cancel($deposit, $deposit->getDetail(123));
		$this->assertEquals(0, count($deposit->getDetails()));
		$this->assertEquals(number_format(60, 2), number_format($cash2->getAvailable(), 2));
		$this->assertEquals(0, $deposit->getTotal());
	}
}

class SalesReportTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$invoices[] = 'uno';
		$report = new SalesReport(50.00, 70.00, 10.00, 12.00, 0.0, $invoices, array());
		$this->assertEquals(120.00, $report->getTotal());
		$this->assertEquals(50.00, $report->getTotalVouchers());
		$this->assertEquals(70.00, $report->getTotalCash());
		$this->assertEquals(10.00, $report->getTotalDiscount());
		$this->assertEquals(12.00, $report->getTotalVat());
		$this->assertEquals(0.0, $report->getTotalDeposits());
		$this->assertEquals(1, count($report->getInvoices()));
		$this->assertEquals(0, count($report->getDeposits()));
	}
	
	public function testConstructor_BadTotalVouchersTxt(){
		try{
			$report = new SalesReport('sdf', 70.00, 10.00,125.32, $invoices);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadTotalCashTxt(){
		try{
			$report = new SalesReport(50.00, 'sdf', 10.00, 23.34, $invoices);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadTotalDiscountTxt(){
		try{
			$report = new SalesReport(50.00, 70.00, 'fds', 23.43, $invoices);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadTotalVatTxt(){
		try{
			$report = new SalesReport(50.00, 70.00, 10.00, 'sdf', $invoices);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance(){
		$report = SalesReport::getInstance(CashRegister::getInstance(123), true);
		$this->assertEquals(231.49, $report->getTotal());
		$this->assertEquals(23.14, $report->getTotalVat());
		$this->assertEquals(0, $report->getTotalVouchers());
		$this->assertEquals(231.49, $report->getTotalCash());
		$this->assertEquals(25.50, $report->getTotalDiscount());
		$this->assertEquals(3, count($report->getInvoices()));
	}
	
	public function testGetInstance_NoPreliminaryOpenRegister(){
		try{
			$report = SalesReport::getInstance(CashRegister::getInstance(123));
		} catch(Exception $e){ return; }
		$this->fail('Register exception expected.');
	}
	
	public function testGetInstance_PreliminaryClosedRegister(){
		$register = CashRegister::getInstance(123);
		$register->close();
		try{
			$report = SalesReport::getInstance($register, true);
		} catch(Exception $e){ return; }
		$this->fail('Register exception expected.');
	}
}

class DepositDetailListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$cash = new Cash(1.0, 123, NULL, NULL, Persist::CREATED);
		$this->assertEquals(1, count(DepositDetailList::getList($cash)));
	}
}

class CashEntryEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		
		$card = PaymentCard::create(5839, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Carlos', '10/12');
		$voucher = new Voucher('4351', $card, 35.00);
		$receipt->addVoucher($voucher);
		CashEntryEvent::apply($receipt, 50.00);
		$this->assertEquals(35.00, $receipt->getTotalVouchers());
		$this->assertEquals(50.00, $receipt->getCash()->getAmount());
		$this->assertEquals(0, $receipt->getChange());
		
		CashEntryEvent::apply($receipt, 100.00);
		$this->assertEquals(35.00, $receipt->getTotalVouchers());
		$this->assertEquals(100.00, $receipt->getCash()->getAmount());
		$this->assertEquals(0, $receipt->getChange());
		
		CashEntryEvent::apply($receipt, 120.00);
		$this->assertEquals(35.00, $receipt->getTotalVouchers());
		$this->assertEquals(115.72, $receipt->getCash()->getAmount());
		$this->assertEquals(number_format(4.28, 2), number_format($receipt->getChange(), 2));
		
		CashEntryEvent::apply($receipt, 150.00);
		$this->assertEquals(35.00, $receipt->getTotalVouchers());
		$this->assertEquals(115.72, $receipt->getCash()->getAmount());
		$this->assertEquals(number_format(34.28, 2), number_format($receipt->getChange(), 2));
		
		$receipt->deleteVoucher($voucher);
		CashEntryEvent::apply($receipt, 150.00);
		$this->assertEquals(0, $receipt->getTotalVouchers());
		$this->assertEquals(150.00, $receipt->getCash()->getAmount());
		$this->assertEquals(number_format(0, 2), number_format($receipt->getChange(), 2));
		
		CashEntryEvent::apply($receipt, 150.72);
		$this->assertEquals(0, $receipt->getTotalVouchers());
		$this->assertEquals(150.72, $receipt->getCash()->getAmount());
		$this->assertEquals(number_format(0, 2), number_format($receipt->getChange(), 2));
		
		CashEntryEvent::apply($receipt, 200.00);
		$this->assertEquals(0, $receipt->getTotalVouchers());
		$this->assertEquals(150.72, $receipt->getCash()->getAmount());
		$this->assertEquals(number_format(49.28, 2), number_format($receipt->getChange(), 2));
	}
	
	public function testApply_BadAmountTxt(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		try{
			CashEntryEvent::apply($receipt, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
}

class VoucherEntryEventTest extends PHPUnit_Framework_TestCase{
	
	public function testApply(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		
		$card = PaymentCard::create(5839, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Carlos', '10/12');
		VoucherEntryEvent::apply('9876', $card, $invoice, $receipt, 100.00);
		$this->assertEquals(100.00, $receipt->getTotal());
		$this->assertEquals(1, count($receipt->getVouchers()));
		
		CashEntryEvent::apply($receipt, 30.00);
		$this->assertEquals(130.00, $receipt->getTotal());
		
		VoucherEntryEvent::apply('5371', $card, $invoice, $receipt, 20.72);
		$this->assertEquals(150.72, $receipt->getTotal());
		$this->assertEquals(2, count($receipt->getVouchers()));
		
		try{
			VoucherEntryEvent::apply('5371', $card, $invoice, $receipt, 20.72);
		} catch(Exception $e){ return; }
		$this->fail('Voucher exception expected.');
	}
	
	public function testApply_BadAmountTxt(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		
		$card = PaymentCard::create(5839, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Carlos', '10/12');
		try{
			VoucherEntryEvent::apply('9876', $card, $invoice, $receipt, 'sdf');
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
	
	public function testApply_BadAmountNoPositive(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		
		$card = PaymentCard::create(5839, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Carlos', '10/12');
		try{
			VoucherEntryEvent::apply('9876', $card, $invoice, $receipt, 0.00);
		} catch(Exception $e){ return; }
		$this->fail('Amount exception expected.');
	}
	
	public function testCancel(){
		$invoice = new Invoice(CashRegister::getInstance(125));
		$invoice->addDetail(new DocProductDetail(Lot::getInstance(123), new Withdraw(), 1, 150.72));
		$receipt = new CashReceipt($invoice);
		
		$card = PaymentCard::create(5839, PaymentCardType::getInstance(123),
				PaymentCardBrand::getInstance(123), 'Carlos', '10/12');
		VoucherEntryEvent::apply('9876', $card, $invoice, $receipt, 100.00);
		$this->assertEquals(100.00, $receipt->getTotal());
		$this->assertEquals(1, count($receipt->getVouchers()));
		
		VoucherEntryEvent::apply('5371', $card, $invoice, $receipt, 20.72);
		$this->assertEquals(120.72, $receipt->getTotal());
		$this->assertEquals(2, count($receipt->getVouchers()));
		
		$voucher = $receipt->getVoucher('5371');
		VoucherEntryEvent::cancel($receipt, $voucher);
		$this->assertEquals(100.00, $receipt->getTotal());
		$this->assertEquals(1, count($receipt->getVouchers()));
		
		$voucher = $receipt->getVoucher('9876');
		VoucherEntryEvent::cancel($receipt, $voucher);
		$this->assertEquals(0, $receipt->getTotal());
		$this->assertEquals(0, count($receipt->getVouchers()));
	}
}

class WorkingDayTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$working_day = new WorkingDay('01/01/2009', Persist::CREATED);
		$this->assertEquals('01/01/2009', $working_day->getDate());
		$this->assertEquals(Persist::CREATED, $working_day->getStatus());
		$this->assertTrue($working_day->isOpen());
	}
	
	public function testConstructor_Defaults(){
		$working_day = new WorkingDay('01/01/2009');
		$this->assertEquals('01/01/2009', $working_day->getDate());
		$this->assertEquals(Persist::IN_PROGRESS, $working_day->getStatus());
		$this->assertFalse($working_day->isOpen());
	}
	
	public function testConstructor_BadDate(){
		try{
			$working_day = new WorkingDay('0101/2009');
		} catch(Exception $e){ return; }
		$this->fail('Date exception expected.');
	}
	
	public function testGetInstance_PastDate(){
		$working_day = WorkingDay::getInstance('08/05/2009');
		$this->assertEquals('08/05/2009', $working_day->getDate());
		$this->assertFalse($working_day->isOpen());
	}
	
	public function testGetInstance_CurrentDate(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		$this->assertEquals(date('d/m/Y'), $working_day->getDate());
		$this->assertTrue($working_day->isOpen());
	}
	
	public function testGetCashRegister_GetCashRegister(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		$cash_register = $working_day->getCashRegister(Shift::getInstance(123));
		$this->assertEquals(123, $cash_register->getId());
	}
	
	public function testGetCashRegister_InsertCashRegister(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		$cash_register = $working_day->getCashRegister(Shift::getInstance(124));
		$this->assertEquals(127, $cash_register->getId());
	}
	
	public function testGetInstance_ClosedWorkingDayNoExistingRegister(){
		$working_day = WorkingDay::getInstance('08/05/2009');
		try{
			$cash_register = $working_day->getCashRegister(Shift::getInstance(123));
		} catch(Exception $e){ return; }
		$this->fail('WorkingDay exception expected.');
	}
}

class GeneralSalesReportTest extends PHPUnit_Framework_TestCase{
	
	public function testConstructor(){
		$registers[] = 'uno';
		$report = new GeneralSalesReport(23.89, $registers);
		$this->assertEquals(23.89, $report->getTotal());
		$this->assertEquals(1, count($report->getCashRegisters()));
	}
	
	public function testConstructor_BadTotalTxt(){
		try{
			$report = new GeneralSalesReport('sdf', $registers);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testConstructor_BadTotalNegative(){
		try{
			$report = new GeneralSalesReport(-1.23, $registers);
		} catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		$report = GeneralSalesReport::getInstance($working_day, true);
		$this->assertEquals(525.32, $report->getTotal());
		$this->assertEquals(2, count($report->getCashRegisters()));
	}
	
	public function testGetInstance_NoPreliminaryOpenWorkingDay(){
		$working_day = WorkingDay::getInstance(date('d/m/Y'));
		try{
			$report = GeneralSalesReport::getInstance($working_day);
		}catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
	
	public function testGetInstance_PreliminaryClosedWorkingDay(){
		$working_day = WorkingDay::getInstance('05/05/2009');
		try{
			$report = GeneralSalesReport::getInstance($working_day, true);
		}catch(Exception $e){ return; }
		$this->fail('Exception expected.');
	}
}

class DepositListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$this->assertEquals(3, count(DepositList::getList(CashRegister::getInstance(123))));
	}
}

class InvoiceListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$this->assertEquals(3, count(InvoiceList::getList(CashRegister::getInstance(123))));
	}
}

class AvailableCashReceiptListTest extends PHPUnit_Framework_TestCase{
	
	public function testGetList(){
		$this->assertEquals(2, count(AvailableCashReceiptList::getList(CashRegister::getInstance(123))));
	}
}
?>