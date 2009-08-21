<?php
/**
 * Library containing the DeleteSupplierProduct command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_detail_object.php');

/**
 * Displays the manufacturer form.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteSupplierProductCommand extends DeleteDetailObjectCommand{
	/**
	 * Returns the detail of the actual object.
	 * 
	 * @param string $detailId
	 * @param variant $obj
	 * @return variant
	 */
	protected function getDetail($detailId, $obj){
		return $obj->getProductSupplier($detailId);
	}
	
	/**
	 * Deletes the detail from the actual object.
	 * 
	 * @param variant $detail
	 * @param variant $obj
	 */
	protected function deleteDetail($detail, $obj){
		$obj->deleteProductSupplier($detail);
	}
}
?>