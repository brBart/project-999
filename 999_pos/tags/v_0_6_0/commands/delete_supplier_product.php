<?php
/**
 * Library containing the DeleteSupplierProductCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_detail_object.php');

/**
 * Defines functionality for deleting a supplier from a product.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteSupplierProductCommand extends DeleteDetailObjectCommand{
	/**
	 * Returns the detail of the object.
	 * 
	 * @param string $detailId
	 * @param variant $obj
	 * @return variant
	 */
	protected function getDetail($detailId, $obj){
		return $obj->getProductSupplier($detailId);
	}
	
	/**
	 * Deletes the detail from the object.
	 * 
	 * @param variant $detail
	 * @param variant $obj
	 */
	protected function deleteDetail($detail, $obj){
		$obj->deleteProductSupplier($detail);
	}
}
?>