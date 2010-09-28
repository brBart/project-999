<?php
/**
 * Library containing the GetReceiptCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the receipt class.
 */
require_once('business/document.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');
/**
 * Library with the organization class.
 */
require_once('business/agent.php');
/**
 * Library with the entry transaction class.
 */
require_once('business/transaction.php');

/**
 * Displays the receipt form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetReceiptCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$receipt = Receipt::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($receipt))
			return $receipt;
		else
			throw new Exception('Recibo no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Recibo',
				'create_link' => 'index.php?cmd=create_receipt', 'get_link' => 'index.php?cmd=get_receipt',
				'search_link' => 'index.php?cmd=search_receipt&page=1', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'id' => $id), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_receipt_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
		
		$user = $obj->getUser();
		$supplier = $obj->getSupplier();
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'receipt_form_html.tpl',
				'status' => $obj->getStatus(),'key' => $key, 'back_link' => $back_link, 'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'supplier' => $supplier->getName(), 'shipment_number' => $obj->getShipmentNumber(),
				'shipment_total' => $obj->getShipmentTotal()), 'site_html.tpl');
	}
}
?>