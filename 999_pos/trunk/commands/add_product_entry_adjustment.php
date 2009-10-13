<?php
/**
 * Library containing the AddProductEntryAdjustmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/add_product_object.php');
/**
 * For adding the product to the receipt.
 */
require_once('business/event.php');
/**
 * For generating a detail.
 */
require_once('business/transaction.php');

/**
 * Defines functionality for adding a product to a entry adjustment document.
 * @package Command
 * @author Roberto Oliveros
 */
class AddProductEntryAdjustmentCommand extends AddProductObjectCommand{
	/**
	 * Adds the product to the desired object.
	 * 
	 * @param variant $obj
	 * @param Product $product
	 * @param integer $quantity
	 */
	protected function addProduct($obj, Product $product, $quantity){
		$price = $this->_mRequest->getProperty('price');
		$expiration_date = $this->_mRequest->getProperty('expiration_date');
		EntryAdjustmentEvent::apply($product, $obj, $quantity, $price, $expiration_date, $msg);
	}
}
?>