<?php
/**
 * Library containing the AddProductWithdrawDocumentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/add_product_object.php');
/**
 * For adding the product to the document.
 */
require_once('business/event.php');
/**
 * For generating a detail.
 */
require_once('business/transaction.php');

/**
 * Defines functionality for adding a product to a withdraw document.
 * @package Command
 * @author Roberto Oliveros
 */
class AddProductWithdrawDocumentCommand extends AddProductObjectCommand{
	/**
	 * Adds the product to the desired object.
	 * 
	 * @param variant $obj
	 * @param Product $product
	 * @param integer $quantity
	 */
	protected function addProduct($obj, Product $product, $quantity){
		WithdrawEvent::apply($product, $obj, $quantity);
	}
}
?>