<?php
/**
 * Library containing the SetFirstNameUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the first name property to the user account object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetFirstNameUserAccountCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setFirstName($value);
	}
}
?>