<?php
/**
 * Library with the __autoload function for the session stored objects that need to initiate.
 * @package 999_pos
 * @author Roberto Oliveros
 */

/**
 * Autoload interceptor function for loading libraries for unknwon classes.
 * 
 * Such case occur when a object is retreived from the session but its class definition hasn't been declared.
 * @param string $className
 */
function __autoload($className){
	switch($className){
		case 'UserAccount':
			require_once('business/user_account.php');
			break;
		
		default:
	}
}
?>