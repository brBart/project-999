<?php
/**
 * Library containing the ChangePasswordOperations command.
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
}
?>