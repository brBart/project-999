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
 * For creating the select options.
 */
require_once('business/list.php');

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
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'receipt_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'id' => $id, 'supplier_list' => $supplier_list), 'site_html.tpl');
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
		if(is_null($backQuery))
			$back_link = 'index.php?cmd=show_receipt_menu';
		elseif($this->_mRequest->getProperty('start_date') != '') // Called from date search.
			$back_link = 'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
					$this->_mRequest->getProperty('start_date') . '&end_date=' .
					$this->_mRequest->getProperty('end_date');
		else // Called from supplier and shipment search.
			$back_link = 'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&supplier_id=' .
					$this->_mRequest->getProperty('supplier_id') . '&shipment_number=' .
					$this->_mRequest->getProperty('shipment_number');
		
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