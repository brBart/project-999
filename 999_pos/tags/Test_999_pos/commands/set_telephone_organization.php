<?php
/**
 * Library containing the SetTelephoneOrganizationCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property telephone to a organization object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetTelephoneOrganizationCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setTelephone($value);
	}
}
?>