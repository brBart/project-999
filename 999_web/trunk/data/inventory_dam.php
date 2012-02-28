<?php
/**
 * Library with utility classes for accessing inventory related tables in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Utility class for accessing comparison data in the database.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class ComparisonDAM{
	/**
	 * Returns a comparison with the details.
	 *
	 * Returns NULL if there was no match for the provided id in the database. 
	 * @param integer $id
	 * @return Comparison
	 */
	static public function getInstance($id){
		$sql = 'CALL comparison_get(:comparison_id)';
		$params = array(':comparison_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$sql = 'CALL comparison_product_get(:comparison_id)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$product = Product::getInstance((int)$detail['product_id']);
				$details[] = new ComparisonDetail($product, (int)$detail['physical'], (int)$detail['system']);
			}
			
			$user = UserAccount::getInstance($result['user_account_username']);
			
			return new Comparison($id, $result['created_date'], $user, $result['reason'],
					(boolean)$result['general'], $details, (int)$result['physical_total'],
					(int)$result['system_total']);
		}
		else
			return NULL;
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
		$sql = 'CALL comparison_insert(:username, :date, :reason, :general, :physical_total)';
		$params = array(':username' => $user->getUserName(),
				':date' => Date::dbDateTimeFormat($date), ':reason' => $reason,
				':general' => (int)$general, ':physical_total' => $count->getTotal());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		$id = (int)DatabaseHandler::getOne($sql);
		
		if($general)
			$sql = 'CALL comparison_product_general_insert(:comparison_id, :count_id)';
		else
			$sql = 'CALL comparison_product_insert(:comparison_id, :count_id)';
			
		$params = array(':comparison_id' => $id, ':count_id' => $count->getId());
		DatabaseHandler::execute($sql, $params);
		
		return $id;
	}
	
	/**
	 * Returns true if the comparison exists.
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	static public function exists($id){
		$sql = 'CALL comparison_exists(:comparison_id)';
		$params = array(':comparison_id' => $id);
		return (boolean)DatabaseHandler::getOne($sql, $params);
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
		$sql = 'CALL count_product_insert(:count_id, :product_id, :quantity)';
		$product = $detail->getProduct();
		$params = array(':count_id' => $count->getId(), ':product_id' => $product->getId(),
				':quantity' => $detail->getQuantity());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the provided detail from the database.
	 *
	 * @param Count $count
	 * @param CountDetail $detail
	 */
	static public function delete(Count $count, CountDetail $detail){
		$sql = 'CALL count_product_delete(:count_id, :product_id)';
		$product = $detail->getProduct();
		$params = array(':count_id' => $count->getId(), ':product_id' => $product->getId());
		DatabaseHandler::execute($sql, $params);
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
		$sql = 'CALL count_get(:count_id)';
		$params = array(':count_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = UserAccount::getInstance('roboli');
			$count = new Count($id, $result['created_date'], $user, Persist::CREATED);
			
			$sql = 'CALL count_product_get(:count_id)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$product = Product::getInstance((int)$detail['product_id']);
				$details[] = new CountDetail($product, (int)$detail['quantity'], Persist::CREATED);
			}
			
			$count->setData($result['reason'], (int)$result['total'], $details);
			return $count;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the count's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Count $obj
	 * @return integer
	 */
	static public function insert(Count $obj){
		$sql = 'CALL count_insert(:username, :date, :reason, :total)';
		$user = $obj->getUser();
		$params = array(':username' => $user->getUserName(),
				':date' => Date::dbDateTimeFormat($obj->getDateTime()), ':reason' => $obj->getReason(),
				':total' => $obj->getTotal());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates the count's data in the database.
	 *
	 * @param Count $obj
	 */
	static public function update(Count $obj){
		$sql = 'CALL count_update(:count_id, :total)';
		$params = array(':count_id' => $obj->getId(), ':total' => $obj->getTotal());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the count from the database.
	 *
	 * @param Count $obj
	 */
	static public function delete(Count $obj){
		$sql = 'CALL count_delete(:count_id)';
		$params = array(':count_id' => $obj->getId());
		DatabaseHandler::execute($sql, $params);
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
	 * The array's fields are product_id, bar_code, manufacturer and name.
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByProduct($first, $last){
		$sql = 'CALL product_counting_template_get(:first, :last)';
		$params = array(':first' => $first, ':last' => $last);
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Returns an array with the necessary data for printing the template ordered by manufacturer name.
	 *
	 * The array's fields are product_id, bar_code, manufacturer and name.
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByManufacturer($first, $last){
		$sql = 'CALL manufacturer_counting_template_get(:first, :last)';
		$params = array(':first' => $first, ':last' => $last);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for accessing comparison data with filter.
 * @package InventoryDAM
 * @author Roberto Oliveros
 */
class ComparisonFilterDAM{
	/**
	 * Returns a comparison with the details filtered.
	 * 
	 * @param integer $id
	 * @param integer $filterType
	 * @param boolean $includePrices
	 * @return ComparisonFilter
	 */
	static public function getInstance($id, $filterType, $includePrices){
		$sql = 'CALL comparison_filter_get(:comparison_id, :filter)';
		$params = array(':comparison_id' => $id);
		$result = DatabaseHandler::getRow($sql, array_merge($params, array(':filter' => $filterType)));
		
		if(!empty($result)){
			switch($filterType){
				case ComparisonFilter::FILTER_NONE:
					$sql = 'CALL comparison_product_get(:comparison_id)';
					break;
					
				case ComparisonFilter::FILTER_POSITIVES:
					$sql = 'CALL comparison_positives_product_get(:comparison_id)';
					break;
					
				case ComparisonFilter::FILTER_NEGATIVES:
					$sql = 'CALL comparison_negatives_product_get(:comparison_id)';
					break;
			}
			
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$product = Product::getInstance((int)$detail['product_id']);
				$details[] = new ComparisonFilterDetail($product, (int)$detail['physical'], (int)$detail['system']);
			}
			
			$user = UserAccount::getInstance($result['user_account_username']);
			
			if($includePrices){
				switch($filterType){
					case ComparisonFilter::FILTER_NONE:
						$sql = 'CALL comparison_total_price_get(:comparison_id)';
						break;
						
					case ComparisonFilter::FILTER_POSITIVES:
						$sql = 'CALL comparison_positives_total_price_get(:comparison_id)';
						break;
						
					case ComparisonFilter::FILTER_NEGATIVES:
						$sql = 'CALL comparison_negatives_total_price_get(:comparison_id)';
						break;
				}
				
				$total_price = DatabaseHandler::getOne($sql, $params);
				
				return new ComparisonFilter($id, $result['created_date'], $user, $result['reason'],
						(boolean)$result['general'], $details, (int)$result['physical_total'],
						(int)$result['system_total'], $includePrices, $total_price);
			}
			
			return new ComparisonFilter($id, $result['created_date'], $user, $result['reason'],
					(boolean)$result['general'], $details, (int)$result['physical_total'],
					(int)$result['system_total']);
		}
		else
			return NULL;
	}
}
?>