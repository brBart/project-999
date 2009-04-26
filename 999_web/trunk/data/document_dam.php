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
	static private $_mDefault = false;
	
	/**
	 * Returns true if a a correlative with the provided serial number exists in the database.
	 *
	 * @param string $serialNumber
	 * @return boolean
	 */
	static public function exists($serialNumber){
		if($serialNumber == 'A021')
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
		return false;
	}
	
	/**
	 * Makes default the provided correlative.
	 *
	 * @param Correlative $obj
	 */
	static public function makeDefault(Correlative $obj){
		if($obj->getSerialNumber() == 'A021')
			self::$_mDefault = true;
	}
	
	/**
	 * Returns an instance of a correlative with database data.
	 *
	 * Returns NULL if there was no match for the provided serial number in the database.
	 * @param string $serialNumber
	 * @return Correlative
	 */
	static public function getInstance($serialNumber){
		if($serialNumber == 'A021'){
			$correlative = new Correlative($serialNumber, self::$_mDefault, 457, Persist::CREATED);
			$correlative->setData('2008-10', '15/01/2008', 100, 5000);
			return $correlative;
		}
		else
			return NULL;
	}
	
	/**
	 * Returns the default correlative.
	 *
	 * @return Correlative
	 */
	static public function getDefaultInstance(){
		$correlative = new Correlative('A022', true, 457, Persist::CREATED);
		$correlative->setData('2008-05', '15/01/2008', 100, 5000);
		return $correlative;
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
?>