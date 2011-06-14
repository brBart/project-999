<?php
/**
 * Library containing the ShowProductByBarCodeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_product.php');

/**
 * Defines functionality for obtaining a product object.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowProductByBarCodeCommand extends ShowProductCommand{
	/**
	 * Gets the desired product.
	 * @return variant
	 */
	protected function getProduct(){
		$bar_code = $this->_mRequest->getProperty('bar_code');
		if($bar_code != ''){
			$id = Product::getProductIdByBarCode($bar_code, true);
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