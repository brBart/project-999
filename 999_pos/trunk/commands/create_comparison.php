<?php
/**
 * Library containing the CreateComparisonCommand base class.
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
 * For creating the comparison.
 */
require_once('business/inventory.php');

/**
 * Defines functionality for creating a comparison.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateComparisonCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		try{
			 
		} catch(ValidateException $e){
			
		} catch(Exception $e){
			
		}
	}
}
?>