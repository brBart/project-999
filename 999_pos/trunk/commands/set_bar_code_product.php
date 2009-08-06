<?php
/**
 * Library containing the SetBarCodeProduct class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's bar code.
 * @package Command
 * @author Roberto Oliveros
 */
class SetBarCodeProductCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setBarCode($value);
	}
}
?>