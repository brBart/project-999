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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL bank_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL deposit_pending_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL manufacturer_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL correlative_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL bank_account_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL user_account_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL payment_card_brand_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
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
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL product_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating supplier lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class SupplierListDAM{
	/**
	 * Returns an array with the fields supplier_id and name from all the suppliers in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL supplier_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL supplier_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating role lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class RoleListDAM{
	/**
	 * Returns an array with the fields role_id and name from all the roles in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL role_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL role_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating branch lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BranchListDAM{
	/**
	 * Returns an array with the fields branch_id and name from all the branches in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL branch_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL branch_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating payment card types lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class PaymentCardTypeListDAM{
	/**
	 * Returns an array with the fields payment_card_type_id and name from all the types in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL payment_card_type_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL payment_card_type_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating shift lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class ShiftListDAM{
	/**
	 * Returns an array with the fields shift_id and name from all the shifts in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL shift_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL shift_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}


/**
 * Class for accesing database data for creating unit of measure lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class UnitOfMeasureListDAM{
	/**
	 * Returns an array with the fields unit_measure_id and name from all the units of measure in the database.
	 *
	 * The totalPages and totalItems parameters are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL unit_of_measure_list_count()';
		$totalItems = (int)DatabaseHandler::getOne($sql);
		
		if($totalItems > 0){
			$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
			
			if($page > 0)
				$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
			else
				$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
			
			$sql = 'CALL unit_of_measure_list_get(:start_item, :items_per_page)';
			return DatabaseHandler::getAll($sql, $params);
		}
		else{
			$totalPages = 0;
			return array();
		}
	}
}
?>