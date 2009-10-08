<?php
/**
 * Library containing the LogoutCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Command to logout the user from the system.
 * @package Command
 * @author Roberto Oliveros
 */
class LogoutCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$helper->removeUser();
		header('Location: index.php');
	}
}
?>