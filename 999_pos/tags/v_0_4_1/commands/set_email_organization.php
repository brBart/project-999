<?php
/**
 * Library containing the SetEmailOrganizationCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property email to a organization object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetEmailOrganizationCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setEmail($value);
	}
}
?>