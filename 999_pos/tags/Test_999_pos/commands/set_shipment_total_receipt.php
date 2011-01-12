<?php
/**
 * Library containing the SetShipmentTotalReceiptCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the shipment total to a receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class SetShipmentTotalReceiptCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setShipmentTotal($value);
	}
}
?>