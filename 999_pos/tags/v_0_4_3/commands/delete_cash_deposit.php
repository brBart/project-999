<?php
/**
 * Library containing the DeleteCashDepositCommand class.
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
 * Defines functionality for deleting cash from a deposit.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteCashDepositCommand extends DeleteDetailObjectCommand{
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
		DepositEvent::cancel($obj, $detail);
	}
}
?>