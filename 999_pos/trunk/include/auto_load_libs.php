<?php
/**
 * Library with the __autoload function for the session stored objects that need to instantiate.
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
			
		case 'Manufacturer':
			require_once('business/product.php');
			break;
			
		case 'Product':
			require_once('business/product.php');
			break;
			
		case 'Supplier':
			require_once('business/agent.php');
			break;
			
		case 'Receipt':
			require_once('business/document.php');
			break;
			
		case 'DocProductDetail':
			require_once('business/document.php');
			break;
			
		case 'Lot':
			require_once('business/product.php');
			break;
			
		case 'Entry':
			require_once('business/transaction.php');
			break;
			
		case 'UnitOfMeasure':
			require_once('business/product.php');
			break;
			
		case 'EntryIA':
			require_once('business/document.php');
			break;
			
		case 'NegativeLot':
			require_once('business/product.php');
			break;
			
		case 'WithdrawIA':
			require_once('business/document.php');
			break;
			
		case 'Withdraw':
			require_once('business/transaction.php');
			break;
			
		case 'Shipment':
			require_once('business/document.php');
			break;
			
		default:
	}
}
?>