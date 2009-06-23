<?php
/**
 * Library with utility classes to accessing database tables.
 * @package ListDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Class for accesing database data for creating bank lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BankListDAM{
	/**
	 * Returns an array with the fields bank_id and name from the banks in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL bank_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL bank_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating pending of confirmation deposit lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PendingDepositListDAM{
	/**
	 * Returns an array with the deposits' created_date, deposit_id, number, bank_account, bank and total from
	 * the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL deposit_pending_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL deposit_pending_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating manufacturer lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ManufacturerListDAM{
	/**
	 * Returns an array with the fields manufacturer_id and name from all the manufacturers in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL manufacturer_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL manufacturer_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating correlative lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class CorrelativeListDAM{
	/**
	 * Returns an array with the fields serial_number and is_default from all the correlatives in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL correlative_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL correlative_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating bank account lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BankAccountListDAM{
	/**
	 * Returns an array with the fields bank_account_number and name from all the bank accounts in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL bank_account_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL bank_account_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating user account lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class UserAccountListDAM{
	/**
	 * Returns an array with the fields username, first_name and last_name from all the user account in the
	 * database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL user_account_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL user_account_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating payment card brands lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PaymentCardBrandListDAM{
	/**
	 * Returns an array with the fields payment_card_brand_id and name from all the brands in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL payment_card_brand_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL payment_card_brand_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accesing database data for creating product lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ProductListDAM{
	/**
	 * Returns an array with the fields product_id, name and packaging from all the products in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL product_list_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL product_list_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
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
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$totalPages = 1;
		$totalItems = 2;
		return array(array('id' => 123, 'name' => 'Unidad'), array('id' => 124, 'name' => 'Docena'));
	}
}
?>