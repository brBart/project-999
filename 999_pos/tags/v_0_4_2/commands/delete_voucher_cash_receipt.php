<?php
/**
 * Library containing the DeleteVoucherCashReceiptCommand class.
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
require_once('business/cash.php');

/**
 * Defines functionality for deleting a product from an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteVoucherCashReceiptCommand extends DeleteDetailObjectCommand{
	/**
	 * Returns the detail of the object.
	 * 
	 * @param string $detailId
	 * @param variant $obj
	 * @return variant
	 */
	protected function getDetail($detailId, $obj){
		return $obj->getVoucher($detailId);
	}
	
	/**
	 * Deletes the detail from the object.
	 * 
	 * @param variant $detail
	 * @param variant $obj
	 */
	protected function deleteDetail($detail, $obj){
		VoucherEntryEvent::cancel($obj, $detail);
	}
}
?>