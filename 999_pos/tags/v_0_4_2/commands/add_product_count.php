<?php
/**
 * Library containing the AddProductCountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/add_product_object.php');
/**
 * For creating the count details.
 */
require_once('business/inventory.php');

/**
 * Defines functionality for adding a product to a count document.
 * @package Command
 * @author Roberto Oliveros
 */
class AddProductCountCommand extends AddProductObjectCommand{
	/**
	 * Adds the product to the desired object.
	 * 
	 * @param variant $obj
	 * @param Product $product
	 * @param integer $quantity
	 */
	protected function addProduct($obj, Product $product, $quantity){
		$obj->addDetail(new CountDetail($product, $quantity));
	}
}
?>