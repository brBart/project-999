<?php
/**
 * Library containing the SetWarehouseNameCompanyCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the company's warehouse name.
 * @package Command
 * @author Roberto Oliveros
 */
class SetWarehouseNameCompanyCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setWarehouseName($value);
	}
}
?>