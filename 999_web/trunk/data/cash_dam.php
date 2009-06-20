<?php
/**
 * Library with the utility database classes for the Cash package.
 * @package CashDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Defines functionality for accessing the bank's database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class BankDAM{
	/**
	 * Returns a bank if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		$sql = 'CALL bank_get(:bank_id)';
		$params = array(':bank_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$bank = new Bank($id, Persist::CREATED);
			$bank->setData($result['name']);
			return $bank;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a bank in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Bank $obj
	 * @return integer
	 */
	static public function insert(Bank $obj){
		$sql = 'CALL bank_insert(:name)';
		$params = array(':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates a bank data in the database.
	 *
	 * @param Bank $obj
	 */
	static public function update(Bank $obj){
		$sql = 'CALL bank_update(:bank_id, :name)';
		$params = array(':bank_id' => $obj->getId(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes a bank from the database. Returns true on success, otherwise it has dependencies and
	 * returns false.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Bank $obj){
		$sql = 'CALL bank_dependencies(:bank_id)';
		$params = array(':bank_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the bank_account table.
		if($result) return false;
		
		$sql = 'CALL bank_delete(:bank_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Defines functionality for accessing the database.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class BankAccountDAM{
	/**
	 * Verifies if the provided number already exists in the database.
	 *
	 * @param string $number
	 * @return boolean
	 */
	static public function exists($number){
		$sql = 'CALL bank_account_exists(:bank_account_number)';
		$params = array(':bank_account_number' => $number);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
 	/** Returns a bank account if it founds a match in the database. Otherwise returns NULL.
	 *
	 * @param string $number
	 * @return BankAccount
	 */
	static public function getInstance($number){
		$sql = 'CALL bank_account_get(:bank_account_number)';
		$params = array(':bank_account_number' => $number);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$bank = Bank::getInstance((int)$result['bank_id']);
			$bank_account = new BankAccount($number, Persist::CREATED);
			$bank_account->setData($result['name'], $bank);
			return $bank_account;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a bank account in the database.
	 *
	 * @param BankAccount $obj
	 */
	static public function insert(BankAccount $obj){
		$sql = 'CALL bank_account_insert(:bank_account_number, :bank_id, :name)';
		$bank = $obj->getBank();
		$params = array(':bank_account_number' => $obj->getNumber(), ':bank_id' => $bank->getId(),
				':name' => $obj->getHolderName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Updates a bank account data in the database.
	 *
	 * @param BankAccount $obj
	 */
	static public function update(BankAccount $obj){
		$sql = 'CALL bank_account_update(:bank_account_number, :bank_id, :name)';
		$bank = $obj->getBank();
		$params = array(':bank_account_number' => $obj->getNumber(), ':bank_id' => $bank->getId(),
				':name' => $obj->getHolderName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes a bank account from the datase. Returns true on success, otherwise it has dependencies and 
	 * returns false.
	 *
	 * @param BankAccount $obj
	 * @return boolean
	 */
	static public function delete(BankAccount $obj){
		$sql = 'CALL bank_account_dependencies(:bank_account_number)';
		$params = array(':bank_account_number' => $obj->getNumber());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the deposit table.
		if($result) return false;
		
		$sql = 'CALL bank_account_delete(:bank_account_number)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Defines functionality for accessing the shift's database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class ShiftDAM{
	/**
	 * Returns a shift if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Shift
	 */
	static public function getInstance($id){
		$sql = 'CALL shift_get(:shift_id)';
		$params = array(':shift_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$shift = new Shift($id, Persist::CREATED);
			$shift->setData($result['name'], $result['time_table']);
			return $shift;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a shift in the database.
	 *
	 * @param Shift $obj
	 */
	static public function insert(Shift $obj){
		$sql = 'CALL shift_insert(:name, :time_table)';
		$params = array(':name' => $obj->getName(), ':time_table' => $obj->getTimeTable());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates a shift data in the database.
	 *
	 * @param Shift $obj
	 */
	static public function update(Shift $obj){
		$sql = 'CALL shift_update(:shift_id, :name, :time_table)';
		$params = array(':shift_id' => $obj->getId(), ':name' => $obj->getName(),
				':time_table' => $obj->getTimeTable());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes a shift from the datase. Returns true on success, otherwise it has dependencies and returns false.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Shift $obj){
		$sql = 'CALL shift_dependencies(:shift_id)';
		$params = array(':shift_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the cash_register table.
		if($result) return false;
		
		$sql = 'CALL shift_delete(:shift_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Defines functionality for accessing the cash register tables.
 *	@package CashDAM
 *  @author Roberto Oliveros
 */
class CashRegisterDAM{
	/**
	 * Returns the status of the cash register.
	 *
	 * @param CashRegister $obj
	 * @return boolean
	 */
	static public function isOpen(CashRegister $obj){
		$sql = 'CALL cash_register_is_open(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Close the cash register in the database.
	 *
	 * @param CashRegister $obj
	 */
	static public function close(CashRegister $obj){
		$sql = 'CALL cash_register_close(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a cash register.
	 *
	 * Returns NULL if not match was found in the database.
	 * @param integer $id
	 * @return CashRegister
	 */
	static public function getInstance($id){
		$sql = 'CALL cash_register_get(:cash_register_id)';
		$params = array(':cash_register_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$shift = Shift::getInstance((int)$result['shift_id']);
			$cash_register = new CashRegister($shift, $id, Persist::CREATED);
			return $cash_register;
		}
		else
			return NULL;
	}
}


/**
 * Defines functionality for accessing the type of payment card database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class PaymentCardTypeDAM{
	/**
	 * Returns a type of payment card if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return PaymentCardType
	 */
	static public function getInstance($id){
		$sql = 'CALL payment_card_type_get(:payment_card_type_id)';
		$params = array(':payment_card_type_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$type = new PaymentCardType($id, Persist::CREATED);
			$type->setData($result['name']);
			return $type;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a type of payment card in the database.
	 *
	 * @param PaymentCardType $obj
	 */
	static public function insert(PaymentCardType $obj){
		$sql = 'CALL payment_card_type_insert(:name)';
		$params = array(':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates a type of payment card data in the database.
	 *
	 * @param PaymentCardType $obj
	 */
	static public function update(PaymentCardType $obj){
		$sql = 'CALL payment_card_type_update(:payment_card_type_id, :name)';
		$params = array(':payment_card_type_id' => $obj->getId(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes a type of payment card from the database. Returns true on success, otherwise it has dependencies
	 * and returns false.
	 *
	 * @param PaymentCardType $obj
	 * @return boolean
	 */
	static public function delete(PaymentCardType $obj){
		$sql = 'CALL payment_card_type_dependencies(:payment_card_type_id)';
		$params = array(':payment_card_type_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the voucher table.
		if($result) return false;
		
		$sql = 'CALL payment_card_type_delete(:payment_card_type_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Defines functionality for accessing the payment card brand database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class PaymentCardBrandDAM{
	/**
	 * Returns a payment card brand if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return PaymentCardBrand
	 */
	static public function getInstance($id){
		$sql = 'CALL payment_card_brand_get(:payment_card_brand_id)';
		$params = array(':payment_card_brand_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$brand = new PaymentCardBrand($id, Persist::CREATED);
			$brand->setData($result['name']);
			return $brand;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a payment card brand in the database.
	 *
	 * @param PaymentCardBrand $obj
	 */
	static public function insert(PaymentCardBrand $obj){
		$sql = 'CALL payment_card_brand_insert(:name)';
		$params = array(':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates a payment card brand data in the database.
	 *
	 * @param PaymentCardBrand $obj
	 */
	static public function update(PaymentCardBrand $obj){
		$sql = 'CALL payment_card_brand_update(:payment_card_brand_id, :name)';
		$params = array(':payment_card_brand_id' => $obj->getId(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes a payment card brand from the database. Returns true on success, otherwise it has dependencies
	 * and returns false.
	 *
	 * @param PaymentCardBrand $obj
	 * @return boolean
	 */
	static public function delete(PaymentCardBrand $obj){
		$sql = 'CALL payment_card_brand_dependencies(:payment_card_brand_id)';
		$params = array(':payment_card_brand_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the voucher table.
		if($result) return false;
		
		$sql = 'CALL payment_card_brand_delete(:payment_card_brand_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Utility class for modifiying cash amount values on the receipt table in the database.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class CashDAM{	
	/**
	 * Returns the cash amount of the provided object from the database.
	 *
	 * @param Cash $obj
	 * @return float
	 */
	static public function getAmount(Cash $obj){
		$sql = 'CALL cash_receipt_cash_get(:cash_receipt_id)';
		$params = array(':cash_receipt_id' => $obj->getId());
		return (float)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the cash amount available of the provided object from the database.
	 *
	 * @param Cash $obj
	 * @return float
	 */
	static public function getAvailable(Cash $obj){
		$sql = 'CALL cash_receipt_cash_available_get(:cash_receipt_id)';
		$params = array(':cash_receipt_id' => $obj->getId());
		return (float)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Reserves the provided cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function reserve(Cash $obj, $amount){
		$sql = 'CALL cash_receipt_increase_reserved(:cash_receipt_id, :amount)';
		$params = array(':cash_receipt_id' => $obj->getId(), ':amount' => $amount);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decreases the reserved cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function decreaseReserve(Cash $obj, $amount){
		$sql = 'CALL cash_receipt_decrease_reserved(:cash_receipt_id, :amount)';
		$params = array(':cash_receipt_id' => $obj->getId(), ':amount' => $amount);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Increases the deposited cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function deposit(Cash $obj, $amount){
		$sql = 'CALL cash_receipt_increase_deposited(:cash_receipt_id, :amount)';
		$params = array(':cash_receipt_id' => $obj->getId(), ':amount' => $amount);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decreases the deposited cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function decreaseDeposit(Cash $obj, $amount){
		$sql = 'CALL cash_receipt_decrease_deposited(:cash_receipt_id, :amount)';
		$params = array(':cash_receipt_id' => $obj->getId(), ':amount' => $amount);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a cash class.
	 *
	 * @param integer $id
	 * @return Cash
	 */
	static public function getInstance($id){
		$sql = 'CALL cash_receipt_exists(:cash_receipt_id)';
		$params = array(':cash_receipt_id' => $id);
		$result = DatabaseHandler::getOne($sql, $params);
		
		if($result > 0)
			// Because the cash object consults directly with the database.
			return new Cash(0.00, $id, Persist::CREATED);
		else
			return NULL;
	}
}


/**
 * Utility class for accessing database tables regarding receipts.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class CashReceiptDAM{
	/**
	 * Returns an instance of a receipt.
	 *
	 * Returns NULL if there was no match for the provided invoice in the database.
	 * @param Invoice $obj
	 * @return CashReceipt
	 */
	static public function getInstance(Invoice $obj){
		$sql = 'CALL cash_receipt_get(:cash_receipt_id)';
		$params = array(':cash_receipt_id' => $obj->getId());
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$cash_receipt = new CashReceipt($obj, $obj->getId(), $obj->getStatus());
			$cash = new Cash(0.0, $obj->getId(), Persist::CREATED);
			
			$sql = 'CALL voucher_get(:cash_receipt_id)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$vouchers = array();
			foreach($items_result as $voucher){
				$type = PaymentCardType::getInstance((int)$voucher['payment_card_type_id']);
				$brand = PaymentCardBrand::getInstance((int)$voucher['payment_card_brand_id']);
				$card = new PaymentCard((int)$voucher['payment_card_number'], $type, $brand, $voucher['name'],
						$voucher['expiration_date']);
				$vouchers[] = new Voucher($voucher['transaction'], $card, (float)$voucher['amount']);
			}
			
			$cash_receipt->setData($cash, (float)$result['total_vouchers'], (float)$result['change_amount'],
					$vouchers);
			return $cash_receipt;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the receipt's data in the database.
	 *
	 * @param CashReceipt $obj
	 */
	static public function insert(CashReceipt $obj){
		$sql = 'CALL cash_receipt_insert(:cash_receipt_id, :change, :cash, :total_vouchers)';
		$invoice = $obj->getInvoice();
		$cash = $obj->getCash();
		$params = array(':cash_receipt_id' => $invoice->getId(), ':change' => $obj->getChange(),
				':cash' => $cash->getAmount(), ':total_vouchers' => $obj->getTotalVouchers());
		DatabaseHandler::execute($sql, $params);
		
		$vouchers = $obj->getVouchers();
		foreach($vouchers as $voucher){
			$sql = 'CALL voucher_insert(:cash_receipt_id, :transaction, :amount, :payment_card_number, ' .
					':payment_card_type_id, :payment_card_brand_id, :name, :expiration_date)';
			$card = $voucher->getPaymentCard();
			$type = $card->getType();
			$brand = $card->getBrand();
			$params = array(':cash_receipt_id' => $invoice->getId(),
					':transaction' => $voucher->getTransactionNumber(), ':amount' => $voucher->getAmount(),
					':payment_card_number' => $card->getNumber(), ':payment_card_type_id' => $type->getId(),
					':payment_card_brand_id' => $brand->getId(), ':name' => $card->getHolderName(),
					':expiration_date' => Date::dbFormat($card->getExpirationDate()));
			DatabaseHandler::execute($sql, $params);
		}
	}
}


/**
 * Utility class for accessing deposit database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositDAM{
	/**
	 * Change the deposit's status to confirmed in the database.
	 *
	 * @param Deposit $obj
	 */
	static public function confirm(Deposit $obj){
		$sql = 'CALL deposit_confirm(:deposit_id)';
		$params = array(':deposit_id' => $obj->getId());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Inserts the deposit's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Deposit $obj
	 * @return integer
	 */
	static public function insert(Deposit $obj){
		$sql = 'CALL deposit_insert(:bank_account_number, :cash_register_id, :username, :date, :number, ' .
				':total, :status)';
		
		$bank_account = $obj->getBankAccount();
		$cash_register = $obj->getCashRegister();
		$user = $obj->getUser();
		
		$params = array(':bank_account_number' => $bank_account->getNumber(),
				':cash_register_id' => $cash_register->getId(), ':username' => $user->getUserName(),
				':date' => Date::dbFormat($obj->getDate()), ':number' => $obj->getNumber(),
				':total' => $obj->getTotal(), ':status' => Deposit::CREATED);
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		$id = (int)DatabaseHandler::getOne($sql);
		
		$details = $obj->getDetails();
		foreach($details as $detail){
			$sql = 'CALL deposit_cash_receipt_insert(:deposit_id, :cash_receipt_id, :amount)';
			$cash = $detail->getCash();
			$params = array(':deposit_id' => $id, ':cash_receipt_id' => $cash->getId(),
					':amount' => $detail->getAmount());
			DatabaseHandler::execute($sql, $params);
		}
		
		return $id;
	}
	
	/**
	 * Updates the deposit status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param Deposit $deposit
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(Deposit $deposit, UserAccount $user, $date){
		$sql = 'CALL deposit_cancel(:deposit_id, :username, :date)';
		$params = array(':deposit_id' => $deposit->getId(), ':username' => $user->getUserName(),
				':date' => Date::dbFormat($date));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns a deposit with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Deposit
	 */
	static public function getInstance($id, &$totalPages, &$totalItems, $page){
		$sql = 'CALL deposit_get(:deposit_id)';
		$params = array(':deposit_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$bank_account = BankAccount::getInstance($result['bank_account_number']);
			$cash_register = CashRegister::getInstance((int)$result['cash_register_id']);
			$user = UserAccount::getInstance($result['user_account_username']);
			$deposit = new Deposit($cash_register, $result['created_date'], $user, $id, (int)$result['status']);
					
			$sql = 'CALL deposit_cash_receipt_count(:deposit_id)';
			$totalItems = DatabaseHandler::getOne($sql, $params);
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array('deposit_id' => $id, ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
						'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array('deposit_id' => $id, ':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL deposit_cash_receipt_get(:deposit_id, :start_item, :items_per_page)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				// Because the cash object consults directly with the database.
				$cash = new Cash(0.00, (int)$detail['cash_receipt_id'], Persist::CREATED);
				$details[] = new DepositDetail($cash, (float)$detail['amount']);
			}
			
			$deposit->setData($result['number'], $bank_account, (float)$result['total'], $details);
			return $deposit;
		}
		else
			return NULL;
	}
}


/**
 * Utility class for creating a sales report with database data.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class SalesReportDAM{
	/**
	 * Returns an instance of a cash register's sales report.
	 *
	 * @param CashRegister $obj
	 * @return SalesReport
	 */
	static public function getInstance(CashRegister $obj){
		$sql = 'CALL sales_report_total_vouchers(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		$total_vouchers = (float)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL sales_report_total_cash(:cash_register_id)';
		$total_cash = (float)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL sales_report_total_discount(:cash_register_id)';
		$total_discount = (float)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL sales_report_total_vat(:cash_register_id)';
		$total_vat = (float)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL sales_report_invoices_get(:cash_register_id)';
		$invoices = DatabaseHandler::getAll($sql, $params);
		
		return new SalesReport($total_vouchers, $total_cash, $total_discount, $total_vat, $invoices);
	}
}


/**
 * Class used for obtaining certain receipts' cash deposits.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositDetailListDAM{
	/**
	 * Returns an array with all the deposits' id which contain the provided cash.
	 *
	 * @param Cash $obj
	 * @return array
	 */
	static public function getList(Cash $obj){
		$sql = 'CALL deposit_cash_list_get(:cash_receipt_id)';
		$params = array(':cash_receipt_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accessing working day database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class WorkingDayDAM{
	/**
	 * Returns true if the working day stills open.
	 *
	 * @param WorkingDay $obj
	 * @return boolean
	 */
	static public function isOpen(WorkingDay $obj){
		$sql = 'CALL working_day_is_open(:working_day)';
		$params = array(':working_day' => Date::dbFormat($obj->getDate()));
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Close all the cash registers that belongs to the provided working day.
	 *
	 * @param WorkingDay $obj
	 */
	static public function closeCashRegisters(WorkingDay $obj){
		$sql = 'CALL working_day_close_cash_registers(:working_day)';
		$params = array(':working_day' => Date::dbFormat($obj->getDate()));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Close the provided working day in the database.
	 *
	 * @param WorkingDay $obj
	 */
	static public function close(WorkingDay $obj){
		$sql = 'CALL working_day_close(:working_day)';
		$params = array(':working_day' => Date::dbFormat($obj->getDate()));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a cash register.
	 *
	 * Returns NULL if there was no match for the provided data in the database.
	 * @param WorkingDay $workingDay
	 * @param Shift $shift
	 * @return CashRegister
	 */
	static public function getCashRegister(WorkingDay $workingDay, Shift $shift){
		$sql = 'CALL cash_register_working_day_shift_get(:working_day, :shift_id)';
		$params = array(':working_day' => Date::dbFormat($workingDay->getDate()), ':shift_id' => $shift->getId());
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result))
			return new CashRegister($shift, (int)$result['cash_register_id'], Persist::CREATED);
		else
			return NULL;
	}
	
	/**
	 * Inserts the provided data in the database and returns an instance of a cash register.
	 *
	 * @param Shift $shift
	 * @return CashRegister
	 */
	static public function insertCashRegister(WorkingDay $workingDay, Shift $shift){
		$sql = 'CALL cash_register_insert(:working_day, :shift_id)';
		$params = array(':working_day' => Date::dbFormat($workingDay->getDate()), ':shift_id' => $shift->getId());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'Call get_last_insert_id()';
		$id = (int)DatabaseHandler::getOne($sql);
		return new CashRegister($shift, $id, Persist::CREATED);
	}
	
	/**
	 * Returns an instance of a working day.
	 *
	 * Returns NULL if there is no match for the provided date in the database.
	 * @param string $date
	 * @return WorkingDay
	 */
	static public function getInstance($date){
		$sql = 'CALL working_day_exists(:working_day)';
		$params = array(':working_day' => Date::dbFormat($date));
		$result = DatabaseHandler::getOne($sql, $params);
		
		if($result > 0)
			return new WorkingDay($date, Persist::CREATED);
		else
			return NULL;
	}
	
	/**
	 * Inserts the provided data into the database.
	 *
	 * It returns an instance of the new created working day.
	 * @param string $date
	 * @return WorkingDay
	 */
	static public function insert($date){
		$sql = 'CALL working_day_insert(:working_day)';
		$params = array(':working_day' => Date::dbFormat($date));
		DatabaseHandler::execute($sql, $params);
		
		return new WorkingDay($date, Persist::CREATED);
	}
}


/**
 * Utility class for accessing database tables with information regarding the daily sales report.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class GeneralSalesReportDAM{
	/**
	 * Returns a general sales report with data from the database.
	 *
	 * @param WorkingDay $obj
	 * @return GeneralSalesReport
	 */
	static public function getInstance(WorkingDay $obj){
		$sql = 'CALL general_sales_report_cash_registers_get(:working_day)';
		$params = array(':working_day' => Date::dbFormat($obj->getDate()));
		$cash_registers = DatabaseHandler::getAll($sql, $params);
		
		$sql = 'CALL general_sales_report_total(:working_day)';
		$total = (float)DatabaseHandler::getOne($sql, $params);
		
		return new GeneralSalesReport($total, $cash_registers);
	}
}


/**
 * Utility class for accessing deposit database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositListDAM{
	/**
	 * Returns an array with all the deposits belonging to the provided cash register.
	 *
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		$sql = 'CALL deposit_list_get(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for accessing invoice database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class InvoiceListDAM{
	/**
	 * Returns an array with all the invoices belonging to the provided cash register.
	 *
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		$sql = 'CALL invoice_list_get(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for accessing receipt database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class AvailableCashReceiptListDAM{
	/**
	 * Returns an array with all the receipts with cash available that belongs to the provided cash register.
	 *
	 * The array contains the fields cash_receipt_id, received_cash and available_cash.
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		$sql = 'CALL cash_receipt_available_list_get(:cash_register_id)';
		$params = array(':cash_register_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
}
?>