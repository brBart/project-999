<?php
/**
 * Library with utility classes for accessing database tables regarding documents.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */

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
		// Code here...
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
		// Code here...
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
	static private $_mDefaultA021 = false;
	static private $_mCurrentA021 = 457;
	
	static private $_mDefaultA022 = true;
	static private $_mCurrentA022 = 0;
	
	/**
	 * Returns true if a a correlative with the provided serial number exists in the database.
	 *
	 * @param string $serialNumber
	 * @return boolean
	 */
	static public function exists($serialNumber){
		switch($serialNumber){
			case 'A021':
				return true;
				break;
				
			case 'A022':
				return true;
				break;
				
			default:
				return false;
		}
	}
	
	/**
	 * Returns true if there are no correlatives in the database.
	 *
	 * @return boolean
	 */
	static public function isEmpty(){
		return false;
	}
	
	
	static public function getNextNumber(Correlative $obj){
		switch($obj->getSerialNumber()){
			case 'A021':
				return ++self::$_mCurrentA021;
				break;
			
			case 'A022':
				return ++self::$_mCurrentA022;
				break;
				
			default:
		}
	}
	
	/**
	 * Makes default the provided correlative.
	 *
	 * @param Correlative $obj
	 */
	static public function makeDefault(Correlative $obj){
		switch($obj->getSerialNumber()){
			case 'A021':
				self::$_mDefaultA021 = true;
				self::$_mDefaultA022 = false;
				break;
			
			case 'A022':
				self::$_mDefaultA021 = false;
				self::$_mDefaultA022 = true;
				break;
				
			default:
		}
	}
	
	/**
	 * Returns an instance of a correlative with database data.
	 *
	 * Returns NULL if there was no match for the provided serial number in the database.
	 * @param string $serialNumber
	 * @return Correlative
	 */
	static public function getInstance($serialNumber){
		switch($serialNumber){
			case 'A021':
				$correlative = new Correlative($serialNumber, self::$_mDefaultA021,
						self::$_mCurrentA021, Persist::CREATED);
				$correlative->setData('2008-10', '15/01/2008', 100, 5000);
				return $correlative;
				break;
				
			case 'A022':
				$correlative = new Correlative($serialNumber, self::$_mDefaultA022,
						self::$_mCurrentA022, Persist::CREATED);
				$correlative->setData('2008-05', '15/01/2008', 5000, 10000);
				return $correlative;
				break;
			
			default:
				return NULL;
		}
	}
	
	/**
	 * Returns the default correlative.
	 *
	 * @return Correlative
	 */
	static public function getDefaultInstance(){
		if(self::$_mDefaultA021){
			$correlative = new Correlative('A021', true, self::$_mCurrentA021, Persist::CREATED);
			$correlative->setData('2008-10', '15/01/2008', 5000, 10000);
			return $correlative;
		}
		elseif(self::$_mDefaultA022){
			$correlative = new Correlative('A022', true, self::$_mCurrentA022, Persist::CREATED);
			$correlative->setData('2008-05', '15/01/2008', 100, 5000);
			return $correlative;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the correlative's data in the database.
	 *
	 * @param Correlative $obj
	 */
	static public function insert(Correlative $obj){
		// Code here...
	}
	
	/**
	 * Deletes the correlative from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Correlative $obj
	 * @return boolean
	 */
	static public function delete(Correlative $obj){
		if($obj->getSerialNumber() == 'A021')
			return true;
		else
			return false;
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
		if($obj->getId() == 123){
			$user = UserAccount::getInstance('roboli');
			$discount = new Discount($user, Persist::CREATED);
			$discount->setData($obj, 25.00);
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
		// Code here...
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
		// Code here...
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
		switch($id){
			case 123:
				$invoice = new Invoice(CashRegister::getInstance(123), '25/04/2009',
						UserAccount::getInstance('roboli'), $id, PersistDocument::CREATED);
				$details[] = new DocProductDetail(Lot::getInstance(5432), new Withdraw(), 5, 7.90);
				$invoice->setData(457, Correlative::getInstance('A022'), 'C/F', '', 12.00,
						39.50, $details);
				$total_pages = 1;
				$total_items = 1;
				return $invoice;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the invoice's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Invoice $obj
	 * @return integer
	 */
	static public function insert(Invoice $obj){
		return 123;
	}
}
?>