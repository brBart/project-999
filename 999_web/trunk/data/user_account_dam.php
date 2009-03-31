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
	/**
	 * Verifies if the account's name already exists in the database.
	 *
	 * @param string $userName
	 * @return boolean
	 */
	static public function exists($userName){
		if($userName == 'roboli')
			return true;
		else
			return false;
	}
	
	/**
	 * Retuns instance of a user account.
	 *
	 * Returns NULL if there was no match fot the provided username in the database.
	 * @param string $userName
	 * @return UserAccount
	 */
	static public function getInstance($userName){
		if($userName == 'roboli'){
			$user_account = new UserAccount($userName, PersistObject::CREATED);
			$role = Role::getInstance(123);
			$user_account->setData('Roberto', 'Oliveros', $role,false);
			return $user_account;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts the user account's data in the database.
	 *
	 * @param UserAccount $obj
	 */
	static public function insert(UserAccount $obj){
		// Code here...
	}
	
	/**
	 * Updates the user account's data in the database.
	 *
	 * @param UserAccount $obj
	 */
	static public function update(UserAccount $obj){
		// Code here...
	}
	
	/**
	 * Deletes the user account from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param UserAccount $obj
	 * @return boolean
	 */
	static public function delete(UserAccount $obj){
		if($obj->getUserName() == 'roboli')
			return true;
		else
			return false;
	}
}
?>