<?php
/**
 * Library package for the DocumentSearch derived classes for access to the database.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Defines functionality for searching the database for Deposit Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class DepositSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & deposit_id which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL deposit_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL deposit_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Deposit Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class DepositByWorkingDaySearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are working_day & id
	 * which is the working day when the document was created and its respective ids.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL deposit_by_working_day_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL deposit_by_working_day_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Comparison Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class ComparisonSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & comparison_id
	 * which is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL comparison_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL comparison_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Count Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class CountSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & count_id which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL count_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL count_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Purchase Return Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class PurchaseReturnSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date &
	 * purchase_return_id which is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL purchase_return_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL purchase_return_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Shipment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class ShipmentSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & shipment_id
	 * which is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL shipment_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL shipment_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Invoice Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class InvoiceSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date, serial_number &
	 * number which is the date when the document was created and its respective ids.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL invoice_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL invoice_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Invoice Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class InvoiceByWorkingDaySearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are working_day, serial_number &
	 * number which is the working day when the document was created and its respective ids.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL invoice_by_working_day_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL invoice_by_working_day_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Receipt Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class ReceiptSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & receipt_id which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL receipt_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL receipt_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Returns an array with the found data in the database. The array fields are created_date & receipt_id which
	 * is the date when the document was created and its respective id.
	 *
	 * @param Supplier $supplier
	 * @param string $shipmentNumber
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function searchBySupplier(Supplier $supplier, $shipmentNumber, &$totalPages, &$totalItems, $page){
		$sql = 'CALL receipt_supplier_search_count(:supplier_id, :shipment_number)';
		$params = array(':supplier_id' => $supplier->getId(), ':shipment_number' => $shipmentNumber);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL receipt_supplier_search_get(:supplier_id, :shipment_number, :start_item, :items_per_page)';
		$params = array(':supplier_id' => $supplier->getId(), ':shipment_number' => $shipmentNumber,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Entry Inventory Adjustment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class EntryIASearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date &
	 * entry_adjustment_id which is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL entry_adjustment_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL entry_adjustment_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Defines functionality for searching the database for Withdraw Inventory Adjustment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class WithdrawIASearchDAM{
	/**
	 * Returns an array with the found data in the database. The array fields are created_date &
	 * withdraw_adjustment_id which is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totalItems, $page){
		$sql = 'CALL withdraw_adjustment_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL withdraw_adjustment_search_get(:start_date, :end_date, :start_item, :items_per_page)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate,
				':start_item' => ($page - 1) * ITEMS_PER_PAGE, ':items_per_page' => ITEMS_PER_PAGE);
		return DatabaseHandler::getAll($sql, $params);
	}
}
?>