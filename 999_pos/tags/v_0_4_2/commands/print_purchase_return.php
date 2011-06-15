<?php
/**
 * Library containing the PrintPurchaseReturnCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/print_object.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Displays the purchase return data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintPurchaseReturnCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$supplier = $obj->getSupplier();
		
		Page::display(array('main_data' => 'purchase_return_main_data_html.tpl',
				'document_name' => 'Devoluci&oacute;n', 'status' => $obj->getStatus(), 'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'supplier' => $supplier->getName(), 'contact' => $obj->getContact(),
				'reason' => $obj->getReason(), 'total' => $obj->getTotal(), 'total_items' => count($details),
				'details' => $details), 'document_print_html.tpl');
	}
}
?>