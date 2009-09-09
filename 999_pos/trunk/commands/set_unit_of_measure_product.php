<?php
/**
 * Library containing the SetUnitOfMeasureProduct class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's unit of measure.
 * @package Command
 * @author Roberto Oliveros
 */
class SetUnitOfMeasureProductCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$um = UnitOfMeasure::getInstance((int)$value);
		$obj->setUnitOfMeasure($um);
	}
}
?>