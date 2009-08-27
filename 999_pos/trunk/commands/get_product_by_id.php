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
		if(is_numeric($id)){
			$product = Product::getInstance((int)$id);
			if(!is_null($product))
				return $product;
			else
				throw new Exception('Product no existe.');
		}
		else
			throw new Exception('C&oacute;digo inv&aacute;lido. Valor debe ser n&uacute;merico.');
	}
}
?>