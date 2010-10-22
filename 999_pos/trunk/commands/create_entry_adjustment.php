<?php
/**
 * Library containing the CreateEntryAdjustmentCommand class.
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
 * Displays the entry adjustment form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateEntryAdjustmentCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'entry_adjustment', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Entrada');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Vale de Entrada',
				'create_link' => 'index.php?cmd=create_entry_adjustment',
				'get_link' => 'index.php?cmd=get_entry_adjustment',
				'search_link' => 'index.php?cmd=search_entry_adjustment&page=1', 'notify' => '1',
				'type' => 'error', 'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new EntryIA();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Entrada');
		
		$user = $obj->getUser();
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'entry_adjustment_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_entry_adjustment_menu',
				'forward_link' => 'index.php?cmd=get_entry_adjustment', 'username' => $user->getUserName()),
				'site_html.tpl');
	}
}
?>