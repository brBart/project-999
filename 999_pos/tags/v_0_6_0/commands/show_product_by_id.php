<?php
/**
 * Library containing the ShowProductByIdCommand class.
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
class ShowProductByIdCommand extends ShowProductCommand{
	/**
	 * Gets the desired product.
	 * @return variant
	 */
	protected function getProduct(){
		$id = $this->_mRequest->getProperty('id');
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