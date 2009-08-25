<?php
/**
 * Library containing the SetPriceProduct class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's price.
 * @package Command
 * @author Roberto Oliveros
 */
class SetPriceProductCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setPrice($value);
	}
}
?>