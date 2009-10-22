<?php
/**
 * Library containing the SetSupplierDocumentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');
/**
 * For the supplier class reference.
 */
require_once('business/agent.php');

/**
 * Defines functionality for setting the document's supplier.
 * @package Command
 * @author Roberto Oliveros
 */
class SetSupplierDocumentCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$supplier = Supplier::getInstance((int)$value);
		$obj->setSupplier($supplier);
	}
}
?>