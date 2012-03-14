<?php
/**
 * Utility library including classes for accessing the database tables.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

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
		$sql = 'CALL role_get(:role_id)';
		$params = array(':role_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result))
			return new role($id, $result['name']);
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
	 * Verifies if the account's username already exists in the database.
	 *
	 * @param string $userName
	 * @return boolean
	 */
	static public function exists($userName){
		$sql = 'CALL user_account_exists(:username)';
		$params = array(':username' => $userName);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Retuns instance of a user account.
	 *
	 * Returns NULL if there was no match fot the provided username in the database.
	 * @param string $userName
	 * @return UserAccount
	 */
	static public function getInstance($userName){
		$sql = 'CALL user_account_get(:username)';
		$params = array(':username' => $userName);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$user = new UserAccount($userName, Persist::CREATED);
			$role = Role::getInstance((int)$result['role_id']);
			$user->setData($result['first_name'], $result['last_name'], $role, (bool)$result['deactivated']);
			return $user;
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
		$sql = 'CALL user_account_insert(:username, :role_id, :first_name, :last_name, :password, :deactivated)';
		$role = $obj->getRole();
		$params = array(':username' => $obj->getUserName(), ':role_id' => $role->getId(),
				':first_name' => $obj->getFirstName(), ':last_name' => $obj->getLastName(),
				':password' => $obj->getPassword(), ':deactivated' => (int)$obj->isDeactivated());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Updates the user account's data in the database.
	 *
	 * @param UserAccount $obj
	 */
	static public function update(UserAccount $obj){
		$sql = 'CALL user_account_update(:username, :role_id, :first_name, :last_name, :password, :deactivated)';
		$role = $obj->getRole();
		$params = array(':username' => $obj->getUserName(), ':role_id' => $role->getId(),
				':first_name' => $obj->getFirstName(), ':last_name' => $obj->getLastName(),
				':password' => $obj->getPassword(), ':deactivated' => (int)$obj->isDeactivated());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Updates the user account's data in the database except the password.
	 *
	 * @param UserAccount $obj
	 */
	static public function updateNoPassword(UserAccount $obj){
		$sql = 'CALL user_account_no_password_update(:username, :role_id, :first_name, :last_name, ' .
				':deactivated)';
		$role = $obj->getRole();
		$params = array(':username' => $obj->getUserName(), ':role_id' => $role->getId(),
				':first_name' => $obj->getFirstName(), ':last_name' => $obj->getLastName(),
				':deactivated' => (int)$obj->isDeactivated());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the user account from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param UserAccount $obj
	 * @return boolean
	 */
	static public function delete(UserAccount $obj){
		$sql = 'CALL user_account_dependencies(:username)';
		$params = array(':username' => $obj->getUserName());
		$result = DatabaseHandler::getOne($sql, $params);
		
		/**
		 * If there are dependencies in the change_price_log, purchase_return, shipment, invoice, count, reserve,
		 * comparison, deposit, entry_adjustment, receipt and withdraw_adjustment database tables.
		 */
		if($result) return false;
		
		$sql = 'CALL user_account_delete(:username)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Utility class for accessing the user acount's tables in the database.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class UserAccountUtilityDAM{
	/**
	 * Verifies the user account exists in the database.
	 *
	 * Returns true if the user account exists and has the provided password. Otherwise returns false.
	 * @param string $userName
	 * @param string $password
	 * @return boolean
	 */
	static public function isValid($userName, $password){
		$sql = 'CALL user_account_is_valid(:username, :password)';
		$params = array(':username' => $userName, ':password' => $password);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Verifies if it is the root's password.
	 *
	 * Returns true it it is the password, otherwise false.
	 * @param string $password
	 * @return boolean
	 */
	static public function isValidRoot($password){
		$sql = 'CALL root_is_valid(:password)';
		$params = array(':password' => $password);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Changes the user account's password in the database.
	 *
	 * @param UserAccount $account
	 * @param string $newPassword
	 */
	static public function changePassword(UserAccount $user, $newPassword){
		$sql = 'CALL user_account_change_password(:username, :password)';
		$params = array(':username' => $user->getUserName(), ':password' => $newPassword);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Changes the root's account password in the database.
	 *
	 * @param string $newPassword
	 */
	static public function changeRootPassword($newPassword){
		$sql = 'CALL root_change_password(:password)';
		$params = array(':password' => $newPassword);
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class in charge of accessing the subject table in the database.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class SubjectDAM{
	/**
	 * Returns the id of the provided subject.
	 * 
	 * Returns 0 in case there is no such subject.
	 * @param string $subject
	 * @return integer
	 */
	static public function getId($subject){
		$sql = 'CALL subject_id_get(:subject)';
		$params = array(':subject' => $subject);
		return (int)DatabaseHandler::getOne($sql, $params);
	}
}


/**
 * Class in charge of accessing the action table in the database.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class ActionDAM{
	/**
	 * Returns the id of the provided action.
	 * 
	 * Returns 0 in case there is no such action.
	 * @param string $action
	 * @return integer
	 */
	static public function getId($action){
		$sql = 'CALL action_id_get(:action)';
		$params = array(':action' => $action);
		return (int)DatabaseHandler::getOne($sql, $params);
	}
}


/**
 * Class in charge of accessing the role_subject_action table in the database.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class AccessManagerDAM{
	/**
	 * Returns true if the user has to right to perform the provided action over the provided subject.
	 * 
	 * @param UserAccount $account
	 * @param integer $subjectId
	 * @param integer $actionId
	 * @return boolean
	 */
	static public function isAllowed(UserAccount $account, $subjectId, $actionId){
		$sql = 'CALL role_subject_action_value_get(:role_id, :subject_id, :action_id)';
		$role = $account->getRole();
		$params = array(':role_id' => $role->getId(), ':subject_id' => $subjectId, ':action_id' => $actionId);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
}
?>