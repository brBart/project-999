<?php
/**
 * Library containing the GetProductByBarCode command.
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
class GetProductByBarCodeCommand extends GetProductCommand{
	/**
	 * Gets the desired object.
	 * @param Request $request
	 * @return variant
	 */
	protected function getObject($request){
		$bar_code = $request->getProperty('bar_code');
		if($bar_code != ''){
			$id = Product::getProductIdByBarCode($bar_code);
			if($id > 0)
				return Product::getInstance($id);
			else
				throw new Exception('Product no existe.');
		}
		else
			throw new Exception('C&oacute;digo de barra inv&aacute;lido. Valor no puede ser vac&iacute;o.');
	}
}
?>