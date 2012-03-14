<?php
/**
 * Library containing the SetRoleUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the user account's role.
 * @package Command
 * @author Roberto Oliveros
 */
class SetRoleUserAccountCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$role = Role::getInstance((int)$value);
		$obj->setRole($role);
	}
}
?>