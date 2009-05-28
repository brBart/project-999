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
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$sql = 'CALL deposit_search_count(:start_date, :end_date)';
		$params = array(':start_date' => $startDate, ':end_date' => $endDate);
		$total_count = DatabaseHandler::getOne($sql, $params);
		
		$total_pages = ceil($total_count / ITEMS_PER_PAGE);
		
		$sql = 'CALL deposit_search_get(:start_date, :end_date, :start_item, :items_per_page)';
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
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Count Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class CountSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Purchase Return Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class PurchaseReturnSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Shipment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class ShipmentSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Invoice Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class InvoiceSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Receipt Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class ReceiptSearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Entry Inventory Adjustment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class EntryIASearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}


/**
 * Defines functionality for searching the database for Withdraw Inventory Adjustment Documents.
 * @package DocumentSearchDAM
 * @author Roberto Oliveros
 */
class WithdrawIASearchDAM{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id.
	 *
	 * @param string $startDate
	 * @param string_type $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totaItems
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages, &$totaItems, $page){
		$data_array = array();
		
		if($page == 1){
			$data_array[] = array('date' => '10/01/2009', 'id' => '123');
			$data_array[] = array('date' => '11/01/2009', 'id' => '124');
			$data_array[] = array('date' => '12/01/2009', 'id' => '125');
			$data_array[] = array('date' => '13/01/2009', 'id' => '126');
			$data_array[] = array('date' => '14/01/2009', 'id' => '127');
		}
		else{
			$data_array[] = array('date' => '15/01/2009', 'id' => '128');
			$data_array[] = array('date' => '16/01/2009', 'id' => '129');
		}
		
		$totalPages = 2;
		$totaItems = 7;
		
		return $data_array;
	}
}
?>