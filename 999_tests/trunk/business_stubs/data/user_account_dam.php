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
		switch($id){
			case 123:
				$role = new Role($id, 'Administrador');
				return $role;
				break;
				
			case 124:
				$role = new Role($id, 'Operador');
				return $role;
				break;
				
			default:
				return NULL;
		}
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
		switch($userName){
			case 'roboli':
				$user_account = new UserAccount($userName, PersistObject::CREATED);
				$role = Role::getInstance(123);
				$user_account->setData('Roberto', 'Oliveros', $role, false);
				return $user_account;
				break;
				
			case 'josoli':
				$user_account = new UserAccount($userName, PersistObject::CREATED);
				$role = Role::getInstance(124);
				$user_account->setData('Jose', 'Oliveros', $role, false);
				return $user_account;
				break;
				
			default:
				return NULL;
		}
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
	 * Updates the user account's data in the database except the password.
	 *
	 * @param UserAccount $obj
	 */
	static public function updateNoPassword(UserAccount $obj){
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


/**
 * Utility class for accessing the user acount's tables in the database.
 * @package UserAccountDAM
 * @author Roberto Oliveros
 */
class UserAccountUtilityDAM{
	static private $_mRoboliPassword = '558236dd6a4c52c8d30d07ad714734ff8f6b3028';
	
	static private $_mRootPassword = 'd8bc384f08624a7ec7426ed5dce21edcab122afe';
	
	/**
	 * Verifies the user account exists in the database.
	 *
	 * Returns true if the user account exists and has the provided password. Otherwise returns false.
	 * @param string $userName
	 * @param string $password
	 * @return boolean
	 */
	static public function isValid($userName, $password){
		if($userName == 'roboli' && $password == self::$_mRoboliPassword)
			return true;
		else
			return false;
	}
	
	/**
	 * Verifies if it is the root's password.
	 *
	 * Returns true it it is the password, otherwise false.
	 * @param string $password
	 * @return boolean
	 */
	static public function isValidRoot($password){
		return ($password == self::$_mRootPassword ? true : false);
	}
	
	/**
	 * Changes the user account's password in the database.
	 *
	 * @param UserAccount $account
	 * @param string $newPassword
	 */
	static public function changePassword(UserAccount $account, $newPassword){
		if($account->getUserName() == 'roboli')
			self::$_mRoboliPassword = $newPassword;
	}
	
	/**
	 * Changes the root's account password in the database.
	 *
	 * @param string $newPassword
	 */
	static public function changeRootPassword($newPassword){
		self::$_mRootPassword = $newPassword;
	}
}



class SubjectDAM{
	
	static public function getId($subject){
		switch($subject){
			case 'prueba1':
				return 1;
				break;
				
			case 'prueba2';
				return 2;
				break;
				
			case 'operaciones':
				return 3;
				break;
				
			case 'caja';
				return 4;
				break;
				
			default:
				return 0;
		}
	}
}



class ActionDAM{
	
	static public function getId($action){
		switch($action){
			case 'prueba1':
				return 1;
				break;
				
			case 'prueba2';
				return 2;
				break;
				
			case 'accesar':
				return 3;
				break;
				
			case 'cerrar';
				return 4;
				break;
				
			default:
				return 0;
		}
	}
}



class AccessManagerDAM{
	
	static public function isAllowed(UserAccount $account, $subjectId, $actionId){
		$role = $account->getRole();
		
		switch($role->getId()){
			case 123:
				if($subjectId == 3 && $actionId == 3)
		 			return true;
		 		elseif($subjectId == 4 && $actionId == 4)
		 			return true;
		 		else
		 			return false;
		 		break;
		 		
			case 124:
				if($subjectId == 3 && $actionId == 3)
		 			return true;
		 		elseif($subjectId == 4 && $actionId == 4)
		 			return false;
		 		else
		 			return false;
		 		break;
		 		
			default:
				return false;
		}
	}
}
?>