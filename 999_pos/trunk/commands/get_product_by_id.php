<?php
/**
 * Library containing the GetProductById command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_product.php');

/**
 * Displays the product form.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductByIdCommand extends GetProductCommand{
	/**
	 * Gets the desired object.
	 * @param Request $request
	 * @return variant
	 */
	protected function getObject($request){
		$id = $request->getProperty('id');
		if(is_numeric($id))
			return Product::getInstance((int)$id);
	}
}
?>