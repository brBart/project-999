<?php
/**
 * Library containing the GetPaymentCardTypeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the payment card type class.
 */
require_once('business/cash.php');

/**
 * Displays the payment card type form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetPaymentCardTypeCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$type = PaymentCardType::getInstance((int)$this->_mRequest->getProperty('id'));
		if(!is_null($type))
			return $type;
		else
			throw new Exception('Tipo de tarjeta no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Tipos de Tarjeta');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_payment_card_type',
				'show_list_link' => 'index.php?cmd=show_payment_card_type_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Tipos de Tarjeta');
		$id = $obj->getId();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_payment_card_type_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the forward link.
		$forward_link = 'index.php?cmd=get_payment_card_type';
		$forward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'identifier_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $id,
				'name' => $obj->getName(), 'forward_link' => $forward_link,
				'edit_cmd' => 'edit_payment_card_type', 'delete_cmd' => 'delete_payment_card_type'),
				'site_html.tpl');
	}
}
?>