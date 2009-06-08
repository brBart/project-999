<?php
/**
 * Library with utility classes to accessing database tables.
 * @package ListDAM
 * @author Roberto Oliveros
 */

/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BankListDAM{
	/**
	 * Returns an array with the banks' ids and names from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Gyt'), array('id' => 124, 'name' => 'Bi'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PendingDepositListDAM{
	/**
	 * Returns an array with the deposits' date, id, number, bank_account, bank and amount from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '01/02/2009', 'id' => 123, 'number' => '4822', 'bank_acount' => '29500530',
				'bank' => 'Gyt', 'amount' => 344.24), array('date' => '02/04/2009', 'id' => 124,
				'number' => '7223', 'bank_acount' => '29500530', 'bank' => 'Bi', 'amount' => 145.10));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ManufacturerListDAM{
	/**
	 * Returns an array with the manufacturers' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Bayer'), array('id' => 124, 'name' => 'Novartis'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class CorrelativeListDAM{
	/**
	 * Returns an array with the correlatives' serial_number and default flag from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('serial_number' => 'A021', 'default' => 1), array('serial_number' => 'A022',
				'default' => 0));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BankAccountListDAM{
	/**
	 * Returns an array with the bank accounts' number and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('number' => '2950000', 'name' => 'Central'), array('number' => '49230004',
				'name' => 'Jutiapa'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class UserAccountListDAM{
	/**
	 * Returns an array with the user accounts' username and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('username' => 'roboli', 'name' => 'Roberto'), array('username' => 'josoli',
				'name' => 'Jose'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PaymentCardBrandListDAM{
	/**
	 * Returns an array with the payment card brands' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Visa'), array('id' => 124, 'name' => 'Master Card'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ProductListDAM{
	/**
	 * Returns an array with the products' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Aspirina'), array('id' => 124, 'name' => 'Pepto Bismol'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class SupplierListDAM{
	/**
	 * Returns an array with the suppliers' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Central'), array('id' => 124, 'name' => 'Xela'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class RoleListDAM{
	/**
	 * Returns an array with the user roles' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Administrador'), array('id' => 124, 'name' => 'Operador'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BranchListDAM{
	/**
	 * Returns an array with the branches' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Xela'), array('id' => 124, 'name' => 'Barberena'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PaymentCardTypeListDAM{
	/**
	 * Returns an array with the payment card types' id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Credito'), array('id' => 124, 'name' => 'Debito'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ShiftListDAM{
	/**
	 * Returns an array with the cash register shifts' id, name and time_table from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Diurno', 'time_table' => '8am - 12pm'), array('id' => 124,
				'name' => 'Nocturno', 'time_table' => '6pm - 11pm'));
	}
}


/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class UnitOfMeasureListDAM{
	/**
	 * Returns an array with the units' of measure id and name from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Unidad'), array('id' => 124, 'name' => 'Docena'));
	}
}
?>