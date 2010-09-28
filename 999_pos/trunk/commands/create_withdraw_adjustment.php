<?php
/**
 * Library containing the CreateWithdrawAdjustmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the document classes.
 */
require_once('business/document.php');

/**
 * Displays the withdraw adjustment form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateWithdrawAdjustmentCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'withdraw_adjustment', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Salida');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Vale de Salida',
				'create_link' => 'index.php?cmd=create_withdraw_adjustment',
				'get_link' => 'index.php?cmd=get_withdraw_adjustment',
				'search_link' => 'index.php?cmd=search_withdraw_adjustment&page=1', 'notify' => '1',
				'type' => 'error', 'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new WithdrawIA();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Salida');
		
		$user = $obj->getUser();
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'withdraw_adjustment_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_withdraw_adjustment_menu',
				'foward_link' => 'index.php?cmd=get_withdraw_adjustment', 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime()), 'site_html.tpl');
	}
}
?>