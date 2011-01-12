<?php
/**
 * Library containing the AddProductInvoiceCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/add_product_object.php');
/**
 * For adding the product to the invoice.
 */
require_once('business/event.php');
/**
 * For generating a detail.
 */
require_once('business/transaction.php');

/**
 * Defines functionality for adding a product to an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class AddProductInvoiceCommand extends AddProductObjectCommand{
	/**
	 * Adds the product to the desired object.
	 * 
	 * @param variant $obj
	 * @param Product $product
	 * @param integer $quantity
	 */
	protected function addProduct($obj, Product $product, $quantity){
		RetailEvent::apply($product, $obj, $quantity);
	}
}
?>