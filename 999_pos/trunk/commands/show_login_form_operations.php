<?php
/**
 * Library containing the ShowLoginOperations command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');

/**
 * Displays the login form for the operations side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowLoginFormOperationsCommand extends Command{
	/**
	 * (non-PHPdoc)
	 * @see 999_web/presentation/commands/Command#execute($request, $helper)
	 */
	public function execute(Request $request, SessionHelper $helper){
		echo 'Hello System!!!';
	}
}
?>