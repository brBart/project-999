<?php
/**
 * Library containing the GetInvoiceBySerialNumberCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the invoice class.
 */
require_once('business/document.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');
/**
 * Library with the withdraw transaction class.
 */
require_once('business/transaction.php');
/**
 * Library with the customer class.
 */
require_once('business/agent.php');
/**
 * Fort the cash register class.
 */
require_once('business/cash.php');

/**
 * Displays the invoice form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetInvoiceBySerialNumberCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$id = Invoice::getInvoiceId($this->_mRequest->getProperty('serial_number'),
				$this->_mRequest->getProperty('number'));
				
		if($id != 0)
			return Invoice::getInstance($id);
		else
			throw new Exception('Factura no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Movimientos', 'Facturas');
		
		$serial_number = $this->_mRequest->getProperty('serial_number');
		$number = $this->_mRequest->getProperty('number');
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'invoice_menu_html.tpl', 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'serial_number' => $serial_number,
				'number' => $number), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Movimientos', 'Facturas');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_invoice_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
				
		$user = $obj->getUser();
		$correlative = $obj->getCorrelative();
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'invoice_inventory_form_html.tpl', 'status' => $obj->getStatus(),'key' => $key,
				'back_link' => $back_link, 'serial_number' => $correlative->getSerialNumber(), 'number' => $obj->getNumber(),
				'date_time' => $obj->getDateTime(), 'username' => $user->getUserName(),
				'nit' => $obj->getCustomerNit(), 'customer' => $obj->getCustomerName()), 'site_html.tpl');
	}
}
?>