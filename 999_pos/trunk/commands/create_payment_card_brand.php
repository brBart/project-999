<?php
/**
 * Library containing the CreatePaymentCardBrandCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the payment card brand class.
 */
require_once('business/cash.php');

/**
 * Displays the payment card brand form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreatePaymentCardBrandCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'payment_card_brand', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Marcas de Tarjeta');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_payment_card_brand',
				'show_list_link' => 'index.php?cmd=show_payment_card_brand_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new PaymentCardBrand();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Marcas de Tarjeta');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'identifier_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_payment_card_brand_menu',
				'foward_link' => 'index.php?cmd=get_payment_card_brand'), 'site_html.tpl');
	}
}
?>