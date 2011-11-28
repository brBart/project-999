<?php
/**
 * Library for accessing the database for multiple purposes.
 * @package VariousDAM
 * @author Roberto Oliveros
 */

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
	 * @return string
	 */
	static public function apply($days){
		return 'hola.sql';
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
		return 'testo.sql';
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
		return new Company('1725045-5', 'Infodes', 'Infodes S.A.', '32232332', 'zona 1', 'Tienda 999');
	}
	
	/**
	 * Updates the company's data in the database.
	 *
	 * @param Company $obj
	 */
	static public function update(Company $obj){
		// Code here...
	}
}


/**
 * Utility class for accessing database information regarding the change of prices report.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class ChangePriceListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, bar_code, manufacturer, name, packaging, last_price and new_price.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '21/04/2009', 'username' => 'roboli', 'bar_code' => '32532',
				'manufacturer' => 'Mattel', 'name' => 'barby', 'packaging' => 'caja', 'last_price' => 43.80,
				'new_price' => 45.00), array('date' => '25/04/2009', 'username' => 'roboli',
				'bar_code' => '92137', 'manufacturer' => 'Mattel', 'name' => 'joe', 'packaging' => 'caja',
				'last_price' => 40.90, 'new_price' => 41.00));
	}
}


/**
 * Utility class for obtaing the discount report data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class DiscountListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, serial_number, number, subtotal, percentage, amount and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '01/05/2009', 'username' => 'roboli', 'serial_number' => 'A021',
				'number' => '4359', 'subtotal' => 124.83, 'percentage' => 12.00, 'total' => 100.83),
				array('date' => '05/05/2009', 'username' => 'roboli', 'serial_number' => 'A021',
				'number' => '1351', 'subtotal' => 245.83, 'percentage' => 12.00, 'total' => 200.83));
	}
}


/**
 * Utility class for obtaing the cancel document data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class CancelDocumentListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, document, number and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '01/05/2009', 'username' => 'roboli', 'document' => 'Factura',
				'number' => 'A021-4359', 'total' => 100.83),
				array('date' => '05/05/2009', 'username' => 'roboli', 'document' => 'Recibo',
				'number' => '1351', 'total' => 200.83));
	}
}


/**
 * Utility class for obtaing the cancel cash document data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class CancelCashDocumentListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, document, number and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '01/05/2009', 'username' => 'roboli', 'document' => 'Factura',
				'number' => 'A021-4359', 'total' => 100.83),
				array('date' => '05/05/2009', 'username' => 'roboli', 'document' => 'Deposito',
				'number' => '1334', 'total' => 200.00));
	}
}


/**
 * Utility class for obtaing the sales ranking data from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class SalesRankingListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are rank, bar_code, manufacturer, name, packaging and quantity.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('rank' => '1', 'bar_code' => '123', 'manufacturer' => 'Mattel',
				'name' => 'Barby', 'packaging' => 'Playera.', 'quantity' => '31'),
				array('rank' => '2', 'bar_code' => '124', 'manufacturer' => 'Mattel',
				'name' => 'Transformer', 'packaging' => 'Optimus.', 'quantity' => '27'));
	}
}


/**
 * Utility class for obtaining the sales and receipts stadistics from certain products.
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
		$totalPages = 1;
		$totalItems = 2;
		return array(array('one' => 'uno'), array('two' => 'dos'));
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
		$totalPages = 1;
		$totalItems = 2;
		return array(array('one' => 'uno'), array('two' => 'dos'));
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
		return array('one' => 'uno', 'two' => 'dos');
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
		return array('one' => 'uno', 'two' => 'dos');
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
		return array('one' => 'uno', 'two' => 'dos');
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
		return array('one' => 'uno', 'two' => 'dos');
	}
}


/**
 * Class to generate a sales ledger file.
 * @package Various
 * @author Roberto Oliveros
 */
class SalesLedgerDAM{
	/**
	 * Generates a file with the sales ledger data. It returns the url of the file
	 * created. Date params in dd/mm/yyyy format.
	 * 
	 * @param string $firstDate
	 * @param string $lastDate
	 * @return string
	 */
	static public function generate($firstDate, $lastDate){
		return 'file.txt';
	}
}


/**
 * Utility class for obtaining the history of invoice transactions.
 * @package Various
 * @author Roberto Oliveros
 */
class InvoiceTransactionListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are serail_number, number, date, total and state.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		$totalPages = 1;
		$totalItems = 2;
		return array(array('one' => 'uno'), array('two' => 'dos'));
	}
}


/**
 * Utility class for obtaining the history of correlative's resolutions from the database.
 * @package Various
 * @author Roberto Oliveros
 */
class ResolutionListDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are resolution_number, resolution_date, serial_number, initial_number, final_number,
	 * created_date and document_type. If no page argument or cero is passed all the details are returned.
	 * The total_pages and total_items arguments are necessary to return their respective values.
	 * Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		$totalPages = 1;
		$totalItems = 2;
		return array(array('one' => 'uno'), array('two' => 'dos'));
	}
}


/**
 * Utility class for obtaing the list of sales summary from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class SalesSummaryListDAM{
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are rank, bar_code, manufacturer, name, actual_price, avg_price, quantity, subtotal, bonus_total and total. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param float &$subtotal
	 * @param float &$discountTotal
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getListByProduct($firstDate, $lastDate, &$subtotal, &$discountTotal, &$totalPages, &$totalItems, $page){
		$subtotal = 120.00;
		$discountTotal = 20.00; 
		$totalPages = 1;
		$totalItems = 2;
		return array('uno' => 'one', 'dos' => 'two');
	}
	
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are rank, username, name, subtotal, discount_total and total. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param float &$total
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getListByUserAccount($firstDate, $lastDate, &$total, &$totalPages, &$totalItems, $page){
		$total = 120.00; 
		$totalPages = 1;
		$totalItems = 2;
		return array('uno' => 'one', 'dos' => 'two');
	}
	
}


/**
 * Utility class for obtaing the list of purchases summary from the database.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class PurchasesSummaryListDAM{
	/**
	 * Retuns an array with data.
	 *
	 * The array's fields are rank, bar_code, manufacturer, name, actual_price, avg_price, quantity and total. If no page
	 * argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param float &$total
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getListByProduct($firstDate, $lastDate, &$total, &$totalPages, &$totalItems, $page){
		$total = 120.00; 
		$totalPages = 1;
		$totalItems = 2;
		return array('uno' => 'one', 'dos' => 'two');
	}
}


/**
 * Utility class for accessing database information regarding bonus creation.
 * @package VariousDAM
 * @author Roberto Oliveros
 */
class BonusCreatedListDAM{
	/**
	 * Returns an array with data regarding invoice transactions that ocurred between the provided dates.
	 *
	 * The array's fields are bar_code, manufacturer, name, quantity, percentage, created_date, expiration_date and username.
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
		$totalPages = 1;
		$totalItems = 2;
		return array('uno' => 'one', 'dos' => 'two');
	}
}
?>