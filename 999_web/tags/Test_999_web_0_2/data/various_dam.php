<?php
/**
 * Library for accessing the database for multiple purposes.
 * @package VariousDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Class to perform a closing event in the system's data.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class ClosingEventDAM{
	/**
	 * Makes a closing on the system's data leaving only the provided days of information.
	 *
	 * @param integer $days
	 */
	static public function apply($days){
		$sql = 'CALL general_closure(:days)';
		$params = array(':days' => $days);
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class to perform a backup of the system's data.
 * @package Various
 * @author Roberto Oliveros
 */
class BackupEventDAM{
	/**
	 * It makes a backup of the database till the actual date. It returns the name of the backup file that
	 * is stored in the BACKUP_DIR constant directory value.
	 * @return string
	 */
	static public function apply(){
		$backup_file = DB_DATABASE . '_' . date('Y-m-d-H-i-s')  . '.sql';
		$command = 'mysqldump -u ' . DB_USERNAME . ' --password=' . DB_PASSWORD . ' --opt --routines ' .
				DB_DATABASE . ' > ' . BACKUP_DIR . $backup_file;
		
		system($command);
		
		return $backup_file;
	}
}


/**
 * Class for accessing database information regarding the company.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class CompanyDAM{
	/**
	 * Returns an instace of the company with database information.
	 *
	 * @return Company
	 */
	static public function getInstance(){
		$sql = 'CALL company_get()';
		$result = DatabaseHandler::getRow($sql);
		return new Company($result['nit'], $result['name']);
	}
	
	/**
	 * Updates the company's data in the database.
	 *
	 * @param Company $obj
	 */
	static public function update(Company $obj){
		$sql = 'CALL company_update(:nit, :name)';
		$params = array(':nit' => $obj->getNit(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Utility class for accessing database information regarding the change of prices report.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class ChangePriceListDAM{
	/**
	 * Returns an array with data regarding price changes that ocurred between the provided dates.
	 *
	 * The array's fields are logged_date, user_account_username, bar_code, manufacturer, name, packaging,
	 * last_price and new_price. If no page argument or cero is passed all the details are returned. The
	 * totalPages and totalItems arguments are necessary to return their respective values.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL change_price_log_count(:first_date, :last_date)';
		$params = array(':first_date' => Date::dbFormat($firstDate), ':last_date' => Date::dbFormat($lastDate));
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL change_price_log_get(:first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for obtaing the discount report data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class DiscountListDAM{
	/**
	 * Retuns an array with data regarding invoice sales discounts that ocurred between the provided dates.
	 *
	 * The array's fields are create_date, user_account_username, serial_number, number, subtotal, percentage,
	 * amount and total. If no page argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL discount_list_count(:first_date, :last_date)';
		$params = array(':first_date' => Date::dbFormat($firstDate), ':last_date' => Date::dbFormat($lastDate));
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL discount_list_get(:first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for obtaing the document cancelled data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class CancelDocumentListDAM{
	/**
	 * Retuns an array with data regarding documents that were cancelled between the provided dates.
	 *
	 * The array's fields are create_date, user_account_username, document, number and total. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL cancel_document_log_count(:first_date, :last_date)';
		$params = array(':first_date' => Date::dbFormat($firstDate), ':last_date' => Date::dbFormat($lastDate));
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL cancel_document_log_get(:first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for obtaing the cash register document cancelled data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class CancelCashDocumentListDAM{
	/**
	 * Retuns an array with data regarding documents that were cancelled between the provided dates.
	 *
	 * The array's fields are create_date, user_account_username, document, number and total. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL cancel_cash_document_log_count(:first_date, :last_date)';
		$params = array(':first_date' => Date::dbFormat($firstDate), ':last_date' => Date::dbFormat($lastDate));
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL cancel_cash_document_log_get(:first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for obtaing the ranking list of sales by product from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class SalesRankingListDAM{
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are rank, bar_code, manufacturer, name, packaging and quantity. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL sales_ranking_count(:first_date, :last_date)';
		$params = array(':first_date' => Date::dbFormat($firstDate), ':last_date' => Date::dbFormat($lastDate));
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL sales_ranking_get(:first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
}


/**
 * Utility class for obtaining the sales and receipts stadistics from certain products from the database.
 * @package Various
 * @author Roberto Oliveros
 */
class SalesAndPurchasesStadisticsListDAM{
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are bar_code, manufacturer, name, and packaging. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getLabelsByProduct($first, $last, &$totalPages, &$totalItems, $page){
		$sql = 'CALL product_stadistics_labels_count(:first, :last)';
		$params = array(':first' => $first, ':last' => $last);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL product_stadistics_labels_get(:first, :last, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are bar_code, manufacturer, name, and packaging. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getLabelsByManufacturer($first, $last, &$totalPages, &$totalItems, $page){
		$sql = 'CALL manufacturer_stadistics_labels_count(:first, :last)';
		$params = array(':first' => $first, ':last' => $last);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL manufacturer_stadistics_labels_get(:first, :last, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are sales and purchases. If no page
	 * argument or cero is passed all the details are returned. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getSalesListByProduct($first, $last, $firstDate, $lastDate, $totalItems, $page){
		$params = array(':first' => $first, ':last' => $last, ':first_date' => Date::dbFormat($firstDate),
				':last_date' => Date::dbFormat($lastDate));
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL product_sales_stadistics_get(:first, :last, :first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are sales and purchases. If no page
	 * argument or cero is passed all the details are returned. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getPurchasesListByProduct($first, $last, $firstDate, $lastDate, $totalItems, $page){
		$params = array(':first' => $first, ':last' => $last, ':first_date' => Date::dbFormat($firstDate),
				':last_date' => Date::dbFormat($lastDate));
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL product_purchases_stadistics_get(:first, :last, :first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are sales and purchases. If no page
	 * argument or cero is passed all the details are returned. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getSalesListByManufacturer($first, $last, $firstDate, $lastDate, $totalItems, $page){
		$params = array(':first' => $first, ':last' => $last, ':first_date' => Date::dbFormat($firstDate),
				':last_date' => Date::dbFormat($lastDate));
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL manufacturer_sales_stadistics_get(:first, :last, :first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are sales and purchases. If no page
	 * argument or cero is passed all the details are returned. Date format: 'dd/mm/yyyy'.
	 * @param string $first
	 * @param string $last
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getPurchasesListByManufacturer($first, $last, $firstDate, $lastDate, $totalItems, $page){
		$params = array(':first' => $first, ':last' => $last, ':first_date' => Date::dbFormat($firstDate),
				':last_date' => Date::dbFormat($lastDate));
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL manufacturer_purchases_stadistics_get(:first, :last, :first_date, :last_date, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}
?>