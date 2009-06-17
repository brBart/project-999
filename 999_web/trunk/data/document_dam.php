<?php
/**
 * Library with utility classes for accessing database tables regarding documents.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Class for accessing document bonus detail tables.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class DocBonusDetailDAM{
	/**
	 * Inserts the detail's data into the database.
	 *
	 * @param DocBonusDetail $detail
	 * @param Document $doc
	 * @param integer $number
	 */
	static public function insert(DocBonusDetail $detail, Document $doc, $number){
		$sql = 'CALL invoice_bonus_insert(:invoice_id, :bonus_id, :number, :price)';
		$bonus = $detail->getBonus();
		$params = array(':invoice_id' => $doc->getId(), ':bonus_id' => $bonus->getId(), ':number' => $number,
				':price' => $detail->getPrice());
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class for accessing document product detail tables.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class DocProductDetailDAM{
	/**
	 * Inserts the detail's data into the database.
	 *
	 * @param DocProductDetail $detail
	 * @param Document $doc
	 * @param integer $number
	 */
	static public function insert(DocProductDetail $detail, Document $doc, $number){
		if($doc instanceof PurchaseReturn)
			$sp = 'purchase_return_lot_insert';
		elseif($doc instanceof Shipment)
			$sp = 'shipment_lot_insert';
		elseif($doc instanceof Invoice)
			$sp = 'invoice_lot_insert';
		elseif($doc instanceof Receipt)
			$sp = 'receipt_lot_insert';
		elseif($doc instanceof EntryIA)
			$sp = 'entry_adjustment_lot_insert';
		elseif($doc instanceof WithdrawIA)
			$sp = 'withdraw_adjustment_lot_insert';
			
		$sql = 'CALL '. $sp . '(:document_id, :lot_id, :quantity, :price, :number)';
		$lot = $detail->getLot();
		$params = array(':document_id' => $doc->getId(), ':lot_id' => $lot->getId(),
				':quantity' => $detail->getQuantity(), ':price' => $detail->getPrice(), ':number' => $number);
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class in charge of accessing the database tables regarding the reserves.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class ReserveDAM{
	/**
	 * Increases the reserve's quantity in the database.
	 *
	 * Used in case there has been a merge of reserves.
	 * @param Reserve $obj
	 * @param integer $quantity
	 */
	static public function increase(Reserve $obj, $quantity){
		// Code here...
	}
	
	/**
	 * Returns an instance of a reserve with database data.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return Reserve
	 */
	static public function getInstance($id){
		if($id == 123){
			$lot = Lot::getInstance(123);
			$user = UserAccount::getInstance('roboli');
			$reserve = new Reserve($id, $lot, 5, $user, '15/04/2009', Persist::CREATED);
			return $reserve;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the provided data into the database.
	 *
	 * It returns an instance of the new created reserve.
	 * @param Lot $lot
	 * @param integer $quantity
	 * @param UserAccount $user
	 * @param string $date
	 * @return Reserve
	 */
	static public function insert(Lot $lot, $quantity, UserAccount $user, $date){
		return new Reserve(123, $lot, $quantity, $user, $date, Persist::CREATED);
	}
	
	/**
	 * Deletes the reserve from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Reserve $obj
	 * @return boolean
	 */
	static public function delete(Reserve $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Utility class for accessing database tables regarding the correlative.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class CorrelativeDAM{
	/**
	 * Returns true if a a correlative with the provided serial number exists in the database.
	 *
	 * @param string $serialNumber
	 * @return boolean
	 */
	static public function exists($serialNumber){
		$sql = 'CALL correlative_exists(:serial_number)';
		$params = array(':serial_number' => $serialNumber);
		$result = DatabaseHandler::getOne($sql, $params);
		
		if($result > 0)
			return true;
		else
			return false;
	}
	
	/**
	 * Returns true if there are no correlatives in the database.
	 *
	 * @return boolean
	 */
	static public function isEmpty(){
		$sql = 'CALL correlative_is_empty()';
		return (boolean)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Returns the next number in the correlative and increments the current field by one.
	 *
	 * @param Correlative $obj
	 * @return integer
	 */
	static public function getNextNumber(Correlative $obj){
		$sql = 'CALL correlative_next_number(:serial_number)';
		$params = array(':serial_number' => $obj->getSerialNumber());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Makes default the provided correlative.
	 *
	 * @param Correlative $obj
	 */
	static public function makeDefault(Correlative $obj){
		$sql = 'CALL correlative_make_default(:serial_number)';
		$params = array(':serial_number' => $obj->getSerialNumber());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a correlative with database data.
	 *
	 * Returns NULL if there was no match for the provided serial number in the database.
	 * @param string $serialNumber
	 * @return Correlative
	 */
	static public function getInstance($serialNumber){
		$sql = 'CALL correlative_get(:serial_number)';
		$params = array(':serial_number' => $serialNumber);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$correlative = new Correlative($serialNumber, (boolean)$result['is_default'], (int)$result['current'],
					Persist::CREATED);
			$correlative->setData($result['resolution_number'], $result['resolution_date'],
					(int)$result['initial_number'], (int)$result['final_number']);
			return $correlative;
		}
		else
			return NULL;
	}
	
	/**
	 * Returns the serial number of the default correlative.
	 *
	 * @return string
	 */
	static public function getDefaultSerialNumber(){
		$sql = 'CALL correlative_default_serial_number()';
		return DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Inserts the correlative's data in the database.
	 *
	 * @param Correlative $obj
	 */
	static public function insert(Correlative $obj){
		$sql = 'CALL correlative_insert(:serial_number, :resolution_number, :resolution_date, :initial_number, ' .
				':final_number)';
		$params = array(':serial_number' => $obj->getSerialNumber(),
				':resolution_number' => $obj->getResolutionNumber(),
				':resolution_date' => Date::dbFormat($obj->getResolutionDate()),
				':initial_number' => $obj->getInitialNumber(), ':final_number' => $obj->getFinalNumber());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the correlative from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Correlative $obj
	 * @return boolean
	 */
	static public function delete(Correlative $obj){
		$sql = 'CALL correlative_dependencies(:serial_number)';
		$params = array(':serial_number' => $obj->getSerialNumber());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the invoice table.
		if($result) return false;
		
		$sql = 'CALL correlative_delete(:serial_number)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Utility class for accessing the vat table in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class VatDAM{
	/**
	 * Returns an instance of system's V.A.T.
	 *
	 * @return Vat
	 */
	static public function getInstance(){
		return new Vat(12.00);
	}
	
	/**
	 * Updates the vat's values in the database.
	 *
	 * @param Vat $obj
	 */
	static public function update(Vat $obj){
		// Code here...
	}
}


/**
 * Class for accessing the discount table in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class DiscountDAM{
	/**
	 * Returns an instance of a discount.
	 *
	 * Returns NULL if there was no match for the provided invoice in the database.
	 * @param Invoice $obj
	 * @return Discount
	 */
	static public function getInstance(Invoice $obj){
		$sql = 'CALL discount_get(:invoice_id)';
		$params = array(':invoice_id' => $obj->getId());
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = UserAccount::getInstance($result['user_account_username']);
			$discount = new Discount($user, Persist::CREATED);
			$discount->setData($obj, (float)$result['percentage']);
			return $discount;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the discount's data in the database.
	 *
	 * @param Discount $obj
	 */
	static public function insert(Discount $obj){
		$sql = 'CALL discount_insert(:invoice_id, :username, :percentage)';
		$invoice = $obj->getInvoice();
		$user = $obj->getUser();
		$params = array(':invoice_id' => $invoice->getId(), ':username' => $user->getUserName(),
				':percentage' => $obj->getPercentage());
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class for accessing invoice tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class InvoiceDAM{
	/**
	 * Updates the invoice status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param Invoice $invoice
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(Invoice $invoice, UserAccount $user, $date){
		$sql = 'CALL invoice_cancel(:invoice_id, :username, :date)';
		$params = array(':invoice_id' => $shipment->getId(), ':username' => $user->getUserName(),
				':date' => Date::dbFormat($date));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns the invoice identifier.
	 *
	 * Returns 0 if there was no match for the provided serial number and number in the database.
	 * @param string $serialNumber
	 * @param integer $number
	 * @return integer
	 */
	static public function getId($serialNumber, $number){
		if($serialNumber == 'A022' && $number == 457)
			return 123;
		else
			return 0;
	}
	
	/**
	 * Returns an invoice with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Invoice
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		$sql = 'CALL invoice_get(:invoice_id)';
		$params = array(':invoice_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = UserAccount::getInstance($result['user_account_username']);
			$cash_register = CashRegister::getInstance((int)$result['cash_register_id']);
			$invoice = new Invoice($cash_register, $result['created_date'], $user, $id, (int)$result['status']);
			
			$correlative = Correlative::getInstance($result['serial_number']);
			$discount = Discount::getInstance($invoice);
			$cash_receipt = CashReceipt::getInstance($invoice);
			
			$sql = 'CALL invoice_items_count(:invoice_id)';
			$totalItems = DatabaseHandler::getOne($sql, $params);
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array('invoice_id' => $id, ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
						'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array('invoice_id' => $id, ':start_item' => 0,
						':items_per_page' => $totalItems);
			
			$sql = 'CALL invoice_items_get(:invoice_id, :start_item, :items_per_page)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail)
				if(!is_null($detail['lot_id'])){
					$lot = Lot::getInstance((int)$detail['lot_id']);
					$details[] = new DocProductDetail($lot, new Withdraw(), (int)$detail['quantity'],
							(float)$detail['price']);
				}
				else{
					$bonus = Bonus::getInstance($detail['bonus_id']);
					$details[] = new DocBonusDetail($bonus, $detail['price']);
				}
			
			$invoice->setData((int)$result['number'], $correlative, $result['nit'], (float)$result['vat'],
					(float)$result['total'], $details, $result['name'], $discount);
			return $invoice;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the invoice's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Invoice $obj
	 * @return integer
	 */
	static public function insert(Invoice $obj){
		$sql = 'CALL invoice_insert(:serial_number, :number, :username, :date, :nit, :name, total, :vat, ' .
				'cash_register_id, :status)';
		$correlative = $obj->getCorrelative();
		$user = $obj->getUser();
		$cash_register = $obj->getCashRegister();
		$params = array(':serial_number' => $correlative->getSerialNumber(), ':number' => $obj->getNumber(),
				':username' => $user->getUserName(), ':date' => Date::dbFormat($obj->getDate()),
				':nit' => $obj->getCustomerNit(), ':name' => $obj->getCustomerName(), ':total' => $obj->getTotal(),
				':vat' => $obj->getVatPercentage(), ':cash_register_id' => $cash_register->getId(),
				':status' => PersistDocument::CREATED);
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
}


/**
 * Class for accessing purchase return tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class PurchaseReturnDAM{
	/**
	 * Updates the purchase return status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param PurchaseReturn $purchaseReturn
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(PurchaseReturn $purchaseReturn, UserAccount $user, $date){
		$sql = 'CALL purchase_return_cancel(:purchase_return_id, :username, :date)';
		$params = array(':purchase_return_id' => $purchaseReturn->getId(), ':username' => $user->getUserName(),
				':date' => Date::dbFormat($date));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns a purchase return with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return PurchaseReturn
	 */
	static public function getInstance($id, &$totalPages, &$totalItems, $page){
		$sql = 'CALL purchase_return_get(:purchase_return_id)';
		$params = array(':purchase_return_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = UserAccount::getInstance($result['user_account_username']);
			$purchase_return = new PurchaseReturn($result['created_date'], $user, $id, (int)$result['status']);
			$supplier = Supplier::getInstance((int)$result['supplier_id']);
			
			$sql = 'CALL purchase_return_lot_count(:purchase_return_id)';
			$totalItems = DatabaseHandler::getOne($sql, $params);
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array('purchase_return_id' => $id, ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
						'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array('purchase_return_id' => $id, ':start_item' => 0,
						':items_per_page' => $totalItems);
			
			$sql = 'CALL purchase_return_lot_get(:purchase_return_id, :start_item, :items_per_page)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$lot = Lot::getInstance((int)$detail['lot_id']);
				$details[] = new DocProductDetail($lot, new Withdraw(), (int)$detail['quantity'],
						(float)$detail['price']);
			}
			
			$purchase_return->setData($supplier, $result['reason'], (float)$result['total'], $details,
					$result['contact']);
			return $purchase_return;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the purchase return's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param PurchaseReturn $obj
	 * @return integer
	 */
	static public function insert(PurchaseReturn $obj){
		$sql = 'CALL purchase_return_insert(:username, :supplier_id, :date, :reason, :contact, :total, :status)';
		$user = $obj->getUser();
		$supplier = $obj->getSupplier();
		$params = array(':username' => $user->getUserName(), ':supplier_id' => $supplier->getId(),
				':date' => Date::dbFormat($obj->getDate()), ':reason' => $obj->getReason(),
				':contact' => $obj->getContact(), ':total' => $obj->getTotal(),
				':status' => PersistDocument::CREATED);
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
}


/**
 * Class for accessing shipment tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class ShipmentDAM{
	/**
	 * Updates the shipment status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param Shipment $shipment
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(Shipment $shipment, UserAccount $user, $date){
		$sql = 'CALL shipment_cancel(:shipment_id, :username, :date)';
		$params = array(':shipment_id' => $shipment->getId(), ':username' => $user->getUserName(),
				':date' => Date::dbFormat($date));
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns a shipment with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Shipment
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		$sql = 'CALL shipment_get(:shipment_id)';
		$params = array(':shipment_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = UserAccount::getInstance($result['user_account_username']);
			$shipment = new Shipment($result['created_date'], $user, $id, (int)$result['status']);
			$branch = Branch::getInstance((int)$result['branch_id']);
			
			$sql = 'CALL shipment_lot_count(:shipment_id)';
			$totalItems = DatabaseHandler::getOne($sql, $params);
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array('shipment_id' => $id, ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
						'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array('shipment_id' => $id, ':start_item' => 0,
						':items_per_page' => $totalItems);
			
			$sql = 'CALL shipment_lot_get(:shipment_id, :start_item, :items_per_page)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$lot = Lot::getInstance((int)$detail['lot_id']);
				$details[] = new DocProductDetail($lot, new Withdraw(), (int)$detail['quantity'],
						(float)$detail['price']);
			}
			
			$shipment->setData($branch, (float)$result['total'], $details, $result['contact']);
			return $shipment;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the shipment's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Shipment $obj
	 * @return integer
	 */
	static public function insert(Shipment $obj){
		$sql = 'CALL shipment_insert(:username, :branch_id, :date, :contact, :total, :status)';
		$user = $obj->getUser();
		$branch = $obj->getBranch();
		$params = array(':username' => $user->getUserName(), ':branch_id' => $branch->getId(),
				':date' => Date::dbFormat($obj->getDate()), ':contact' => $obj->getContact(),
				':total' => $obj->getTotal(), ':status' => PersistDocument::CREATED);
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
}


/**
 * Class for accessing receipt tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class ReceiptDAM{
	/**
	 * Updates the receipt status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param Receipt $receipt
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(Receipt $receipt, UserAccount $user, $date){
		// Code here...
	}
	
	/**
	 * Returns a receipt with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Receipt
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		switch($id){
			case 123:
				$receipt = new Receipt('25/04/2009', UserAccount::getInstance('roboli'), $id,
						PersistDocument::CREATED);
				$lot = new Lot(Product::getInstance(123), 10, 8.00, '10/12/2009');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$receipt->setData(Supplier::getInstance(123), '8289', 39.50, $details);
				$total_pages = 1;
				$total_items = 1;
				return $receipt;
				break;
				
			case 124:
				$receipt = new Receipt('15/05/2009', UserAccount::getInstance('roboli'), $id,
						PersistDocument::CREATED);
				$lot = new Lot(Product::getInstance(123), 10, 8.00, '10/12/2009');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$lot = new Lot(Product::getInstance(124), 10, 8.00, '10/12/2009');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$lot = new Lot(Product::getInstance(125), 10, 8.00, '10/12/2009');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$lot = new Lot(Product::getInstance(123), 10, 8.00, '01/02/2010');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$lot = new Lot(Product::getInstance(125), 10, 8.00, '01/02/2010');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$receipt->setData(Supplier::getInstance(123), '8289', 39.50, $details);
				$total_pages = 1;
				$total_items = 5;
				return $receipt;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the receipt's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Receipt $obj
	 * @return integer
	 */
	static public function insert(Receipt $obj){
		return 123;
	}
}


/**
 * Class for accessing the entry inventory adjustment tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class EntryIADAM{
	/**
	 * Updates the document to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param EntryIA $entry
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(EntryIA $entry, UserAccount $user, $date){
		// Code here...
	}
	
	/**
	 * Returns an entry inventory adjustment document with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return EntryIA
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		switch($id){
			case 123:
				$entry = new EntryIA('25/04/2009', UserAccount::getInstance('roboli'), $id,
						PersistDocument::CREATED);
				$lot = new Lot(Product::getInstance(123), 10, 8.00, '10/12/2009');
				$details[] = new DocProductDetail($lot, new Entry(), 5, 7.90);
				$entry->setData('Ajuste.', 39.50, $details);
				$total_pages = 1;
				$total_items = 1;
				return $entry;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the document's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param EntryIA $obj
	 * @return integer
	 */
	static public function insert(EntryIA $obj){
		return 123;
	}
}


/**
 * Class for accessing the withdraw inventory adjustment tables in the database.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class WithdrawIADAM{
	/**
	 * Updates the document to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param WithdrawIA $withdraw
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(WithdrawIA $withdraw, UserAccount $user, $date){
		// Code here...
	}
	
	/**
	 * Returns a withdraw inventory adjustment document with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return WithdrawIA
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		switch($id){
			case 123:
				$withdraw = new WithdrawIA('25/04/2009', UserAccount::getInstance('roboli'), $id,
						PersistDocument::CREATED);
				$details[] = new DocProductDetail(Lot::getInstance(123), new Withdraw(), 5, 7.90);
				$withdraw->setData('Ajuste.', 39.50, $details);
				$total_pages = 1;
				$total_items = 1;
				return $withdraw;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the document's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param WithdrawIA $obj
	 * @return integer
	 */
	static public function insert(WithdrawIA $obj){
		return 123;
	}
}
?>