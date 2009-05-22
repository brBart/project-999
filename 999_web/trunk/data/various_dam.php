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
	 * Makes a closing on the system's data leaving only the provided months of information.
	 *
	 * @param integer $months
	 */
	static public function apply($months){
		// Code here...
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
		return new Company('1725045-5', 'Infodes');
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
class ChangePriceReportDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, bar_code, manufacturer, name, packaging, last_price and new_price.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getData($firstDate, $lastDate, $page, &$total_pages, &$total_items){
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
class DiscountReportDAM{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, serial_number, number, subtotal, percentage, amount and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getData($firstDate, $lastDate, $page, &$total_pages, &$total_items){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '01/05/2009', 'username' => 'roboli', 'serial_number' => 'A021',
				'number' => '4359', 'subtotal' => 124.83, 'percentage' => 12.00, 'total' => 100.83),
				array('date' => '05/05/2009', 'username' => 'roboli', 'serial_number' => 'A021',
				'number' => '1351', 'subtotal' => 245.83, 'percentage' => 12.00, 'total' => 200.83));
	}
}
?>