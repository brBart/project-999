<?php
/**
 * Library containing the AddProductReceiptCommand class.
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
 * Defines functionality for adding a product to a receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class AddProductEntryDocumentCommand extends AddProductObjectCommand{
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
		EntryEvent::apply($product, $obj, $quantity, $price, $expiration_date);
	}
}
?>