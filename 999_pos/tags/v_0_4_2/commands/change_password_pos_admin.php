<?php
/**
 * Library containing the ChangePasswordPosAdminCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/change_password.php');

/**
 * Displays the change password form for the POS Admin side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class ChangePasswordPosAdminCommand extends ChangePasswordCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getModuleTitle(){
		return POS_ADMIN_TITLE;
	}
	
	/**
	 * Returns the main menu template file name.
	 * @return string
	 */
	protected function getMainMenuTemplate(){
		return 'main_menu_pos_admin_html.tpl';
	}
	
	/**
	 * Returns the link to forward the form on action.
	 * @return string
	 */
	protected function getForwardLink(){
		return 'index.php?cmd=change_password_pos_admin';	
	}
}
?>