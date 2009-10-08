<?php
/**
 * Library containing the ChangePasswordOperationsCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/change_password.php');

/**
 * Displays the change password form for the operations side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class ChangePasswordOperationsCommand extends ChangePasswordCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getModuleTitle(){
		return OPERATIONS_TITLE;
	}
	
	/**
	 * Returns the main menu template file name.
	 * @return string
	 */
	protected function getMainMenuTemplate(){
		return 'main_menu_operations_html.tpl';
	}
}
?>