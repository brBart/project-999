<?php
/**
 * Utility library including classes for accessing the database tables.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */

/**
 * Defines functionality for accessing the role's database tables.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class RoleDAM{
	/**
	 * Returns instance of a role.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Role
	 */
	static public function getInstance($id){
		if($id == 123){
			$role = new Role($id, 'Administrador');
			return $role;
		}
		else
			return NULL;
	}
}


/**
 * Defines functionality for accessing the user account's database tables.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class UserAccountDAM{
	
}
?>