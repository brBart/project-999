<?php
/**
 * Library containing the GetWithdrawAdjustmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the withdraw adjustment document class.
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
 * Displays the withdraw adjustment document form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetWithdrawAdjustmentCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$withdraw = WithdrawIA::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($withdraw))
			return $withdraw;
		else
			throw new Exception('Vale de Entrada no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Salida');
		
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Vale de Salida',
				'create_link' => 'index.php?cmd=create_withdraw_adjustment',
				'get_link' => 'index.php?cmd=get_withdraw_adjustment',
				'search_link' => 'index.php?cmd=search_withdraw_adjustment&page=1', 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'id' => $id), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Salida');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_withdraw_adjustment_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
				
		$user = $obj->getUser();
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'withdraw_adjustment_form_html.tpl', 'status' => $obj->getStatus(),'key' => $key,
				'back_link' => $back_link, 'id' => $obj->getId(), 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime(), 'reason' => $obj->getReason()), 'site_html.tpl');
	}
}
?>