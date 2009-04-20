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
 * Class in charge of accessing the database tables regarding the reserves.
 * @package DocumentDAM
 * @author Roberto Oliveros
 */
class ReserveDAM{
	/**
	 * Verifies if the reserve exists in the database.
	 *
	 * Returns true if it does.
	 * @param Reserve $obj
	 * @return boolean
	 */
	static public function exists(Reserve $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
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
			$reserve = new Reserve($id, $lot, 5, $user, '15/04/2009');
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
		return new Reserve(123, $lot, $quantity, $user, $date);
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
?>