<?php
/**
 * Library containing the SetLastNameUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the last name property to the user account object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetLastNameUserAccountCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setLastName($value);
	}
}
?>