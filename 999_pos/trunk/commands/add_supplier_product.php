<?php
/**
 * Library containing the add supplier product command.
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
 * Defines functionality for adding a supplier to a product.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class AddSupplierProductCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$id = $request->getProperty('supplierid');
	}
}
?>