<?php
/**
 * Library containing the MakeDefaultCorrelativeCommand class.
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
 * Defines functionality for setting the correlative as the default correlative.
 * @package Command
 * @author Roberto Oliveros
 */
class MakeDefaultCorrelativeCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		try{
			$user = $helper->getUser();
			if(AccessManager::isAllowed($user, 'correlative', 'write')){
				$correlative = $helper->getObject((int)$request->getProperty('key'));
				Correlative::makeDefault($correlative);
				Page::display(array(), 'success_xml.tpl');
			}
			else
				throw new Exception('Usuario no cuenta con los suficientes privilegios.');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>