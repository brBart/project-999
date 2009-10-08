<?php
/**
 * Library containing the SetPackagingProductCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's packaging.
 * @package Command
 * @author Roberto Oliveros
 */
class SetPackagingProductCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setPackaging($value);
	}
}
?>