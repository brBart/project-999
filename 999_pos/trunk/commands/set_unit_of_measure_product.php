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
 * Defines functionality for setting the product's manufacturer.
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
		if($value == '')
			throw new ValidateException('Seleccione una unidad de medida.');
			
		$um = UnitOfMeasure::getInstance((int)$value);
		
		if(is_null($um))
			throw new Exception('Unidad de medida no existe.');
		
		$obj->setUnitOfMeasure($um);
	}
}
?>