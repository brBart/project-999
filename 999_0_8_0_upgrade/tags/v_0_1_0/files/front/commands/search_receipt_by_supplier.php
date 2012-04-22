<?php
/**
 * Library containing the SearchObjectByDateCommand base class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');
/**
 * For making the search.
 */
require_once('business/document_search.php');

/**
 * Defines functionality for the receipt search by supplier and shipment number.
 * @package Command
 * @author Roberto Oliveros
 */
class SearchReceiptBySupplierCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$supplier_id = $request->getProperty('supplier_id');
		$shipment_number = $request->getProperty('shipment_number');
		
		if($supplier_id == ''){
			$this->displayFailure('Seleccione un proveedor.', $supplier_id, $shipment_number);
			return;
		}
		
		$supplier = Supplier::getInstance((int)$supplier_id);
		if(is_null($supplier)){
			$this->displayFailure('Proveedor no existe.', $supplier_id, $shipment_number);
			return;
		}
		
		if($shipment_number == ''){
			$this->displayFailure('Ingrese el n&uacute;mero de env&iacute;o.', $supplier_id, $shipment_number);
			return;
		}
		
		$page = (int)$request->getProperty('page');
		
		try{
			$list = ReceiptSearch::searchBySupplier($supplier, $shipment_number, $total_pages, $total_items, $page);
		} catch(Exception $e){
			$msg = $e->getMessage();
			$this->displayFailure($msg, $supplier_id, $shipment_number);
			return;
		}
		
		if($total_items > 0){
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
			
			// For back link purposes.
			$actual_cmd = $request->getProperty('cmd');
			
			$link = 'index.php?cmd=' . $actual_cmd . '&page=';
			$params = '&supplier_id=' . $supplier_id . '&shipment_number=' . $shipment_number;
			$previous_link = ($page == 1) ? '' : $link . ($page - 1) . $params;
			$next_link = ($page == $total_pages) ? '' : $link . ($page + 1) . $params;
			
			$back_trace = array('Inicio', 'Movimientos', 'Recibos');
			
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_receipt_menu', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'receipt_list_html.tpl',
					'list' => $list, 'supplier' => $supplier->getName(),
					'supplier_id' => $supplier_id, 'shipment_number' => $shipment_number,
					'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
					'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
					'next_link' => $next_link, 'item_link' => 'index.php?cmd=get_receipt&id=',
					'actual_cmd' => $actual_cmd), 'site_html.tpl');
		}
		else{
			$back_trace = array('Inicio', 'Movimientos', 'Recibos');
			$msg = 'No hay recibos con ese proveedor y n&uacute;mero de env&iacute;o.';
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_receipt_menu', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
					'message' => $msg), 'site_html.tpl');
		}
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	private function displayFailure($msg, $supplierId, $shipmentNumber){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'receipt_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'supplier_list' => $supplier_list, 'supplier_id' => $supplierId,
				'shipment_number' => $shipmentNumber), 'site_html.tpl');
	}
}
?>