<?php
/**
 * Library with utility classes for accessing inventory related tables in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */

/**
 * Utility class for accessing comparison data in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class ComparisonDAM{
	/**
	 * Returns a comparison with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database. 
	 * @param integer $id
	 * @param integer $total_pages
	 * @param integer $total_items
	 * @param integer $page
	 * @return Comparison
	 */
	static public function getInstance($id){
		switch($id){
			case 123:
				$details[] = new ComparisonDetail(Product::getInstance(125), 10, 10);
				$comparison = new Comparison($id, '01/05/2009 12:00:00', UserAccount::getInstance('roboli'), 'Los hay.',
						false, $details, 10, 10);
				return $comparison;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Creates a comparison in the database and returns the new created comparison's id.
	 *
	 * @param string $date
	 * @param UserAccount $user
	 * @param Count $count
	 * @param string $reason
	 * @param boolean $general
	 * @return integer
	 */
	static public function insert($date, UserAccount $user, Count $count, $reason, $general){
		return 123;
	}
}


/**
 * Utility class to manipulate the detail data in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class CountDetailDAM{
	/**
	 * Inserts the detail's data in the database.
	 *
	 * @param Count $count
	 * @param CountDetail $detail
	 */
	static public function insert(Count $count, CountDetail $detail){
		// Code here...
	}
	
	/**
	 * Deletes the provided detail from the database.
	 *
	 * @param Count $count
	 * @param CountDetail $detail
	 */
	static public function delete(Count $count, CountDetail $detail){
		// Code here...
	}
}


/**
 * Utility class for manipulate counts' data in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class CountDAM{
	/**
	 * Returns an instance of a count.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Count
	 */
	static public function getInstance($id){
		switch($id){
			case 123:
				$count = new Count($id, '01/04/2009 12:00:00', UserAccount::getInstance('roboli'), Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(125), 21, Persist::CREATED);
				$count->setData('Los hay pues.', 21, $details);
				return $count;
				break;
				
			case 124:
				$count = new Count($id, '11/05/2009 12:00:00', UserAccount::getInstance('roboli'), Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(125), 21, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(123), 21, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(124), 21, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(125), 2, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(123), 2, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(124), 2, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(125), 12, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(123), 12, Persist::CREATED);
				$details[] = new CountDetail(Product::getInstance(124), 12, Persist::CREATED);
				$count->setData('Siempre hay.', 21, $details);
				return $count;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the count's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Count $obj
	 * @return integer
	 */
	static public function insert(Count $obj){
		return 123;
	}
	
	/**
	 * Updates the count's data in the database.
	 *
	 * @param Count $obj
	 */
	static public function update(Count $obj){
		// Code here...
	}
	
	/**
	 * Deletes the count from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Count $obj
	 * @return boolean
	 */
	static public function delete(Count $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Utility class for recolecting the necessary data from the database for printing a counting template.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class CountingTemplateDAM{
	/**
	 * Returns an array with the necessary data for printing the template ordered by product name.
	 *
	 * The array's fields are id, bar_code, manufacturer, name and packaging.
	 * @param boolean $general
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByProduct($general, $first, $last){
		return array(array('id' => 123, 'bar_code' => '32535', 'manufacturer' => 'Bayer', 'name' => 'Aspirina',
				'packaging' => 'caja'), array('id' => 124, 'bar_code' => '92238', 'manufacturer' => 'Bayer',
				'name' => 'Racomin', 'packaging' => 'caja'));
	}
	
	/**
	 * Returns an array with the necessary data for printing the template ordered by manufacturer name.
	 *
	 * The array's fields are id, bar_code, manufacturer, name and packaging.
	 * @param boolean $general
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByManufacturer($general, $first, $last){
		return array(array('id' => 123, 'bar_code' => '32535', 'manufacturer' => 'Bayer', 'name' => 'Aspirina',
				'packaging' => 'caja'), array('id' => 124, 'bar_code' => '92238', 'manufacturer' => 'Bayer',
				'name' => 'Racomin', 'packaging' => 'caja'));
	}
}
?>