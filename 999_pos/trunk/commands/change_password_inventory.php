<?php
/**
 * Library containing the ChangePasswordInventoryCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/change_password.php');

/**
 * Displays the change password form for the inventory side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class ChangePasswordInventoryCommand extends ChangePasswordCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getModuleTitle(){
		return INVENTORY_TITLE;
	}
	
	/**
	 * Returns the main menu template file name.
	 * @return string
	 */
	protected function getMainMenuTemplate(){
		return 'main_menu_inventory_html.tpl';
	}
}
?>