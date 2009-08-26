<?php
/**
 * Library containing the SetManufacturerProduct class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's manufacturer.
 * @package Command
 * @author Roberto Oliveros
 */
class SetManufacturerProductCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$manufacturer = Manufacturer::getInstance((int)$value);
		$obj->setManufacturer($manufacturer);
	}
}
?>