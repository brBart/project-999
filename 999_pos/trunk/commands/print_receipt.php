<?php
/**
 * Library containing the PrintReceipt command.
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
 * Displays the receipt data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintReceiptCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$supplier = $obj->getSupplier();
		
		Page::display(array('status' => $obj->getStatus(),'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'supplier' => $supplier->getName(), 'shipment_number' => $obj->getShipmentNumber(),
				'shipment_total' => $obj->getShipmentTotal(), 'total' => $obj->getTotal(),
				'total_items' => count($details), 'details' => $details), 'receipt_print_html.tpl');
	}
}
?>