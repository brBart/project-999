<?php
/**
 * Library containing the PrintShipmentCommand class.
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
 * Displays the shipment data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintShipmentCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$branch = $obj->getBranch();
		
		Page::display(array('main_data' => 'shipment_main_data_html.tpl', 'document_name' => 'Envio',
				'status' => $obj->getStatus(), 'id' => $obj->getId(), 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime(), 'branch' => $branch->getName(),
				'contact' => $obj->getContact(), 'total' => $obj->getTotal(), 'include_product_id' => '1',
				'total_items' => count($details), 'details' => $details), 'document_print_html.tpl');
	}
}
?>