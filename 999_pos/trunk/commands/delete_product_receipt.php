<?php
/**
 * Library containing the DeleteProductReceiptCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_detail_object.php');
/**
 * For deleting the detail.
 */
require_once('business/event.php');

/**
 * Defines functionality for deleting a product from a receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteProductReceiptCommand extends DeleteDetailObjectCommand{
	/**
	 * Returns the detail of the object.
	 * 
	 * @param string $detailId
	 * @param variant $obj
	 * @return variant
	 */
	protected function getDetail($detailId, $obj){
		return $obj->getDetail($detailId);
	}
	
	/**
	 * Deletes the detail from the object.
	 * 
	 * @param variant $detail
	 * @param variant $obj
	 */
	protected function deleteDetail($detail, $obj){
		EntryEvent::cancel($obj, $detail);
	}
}
?>