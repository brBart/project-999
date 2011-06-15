<?php
/**
 * Library class with utility classes for accessing the database.
 * @package DatabaseHandler
 * @author Roberto Oliveros (With help of the PHP E-Commerce authors).
 */

/**
 * Utility class for accessing the database.
 * @package DatabaseHandler
 * @author Roberto Oliveros
 */
class DatabaseHandler{
	/**
	 * Holds an instance of the PDO class.
	 *
	 * @var PDO
	 */
	static private $_mHandler;
	
	/**
	 * Private constructor to prevent direct creation of object.
	 *
	 */
	private function __construct(){}
	
	/**
	 * Clears the PDO class instance.
	 *
	 */
	static public function close(){
		self::$_mHandler = NULL;
	}
	
	/**
	 * Wrapper method for PDOStatement::execute().
	 *
	 * @param string $sqlQuery
	 * @param array $params
	 */
	static public function execute($sqlQuery, $params = NULL){
		try{
			$database_handler = self::getHandler();
			
			$statement_handler = $database_handler->prepare($sqlQuery);
			
			$statement_handler->execute($params);
		} catch(PDOException $e){
			self::close();
			throw $e;
		}
	}
	
	/**
	 * Wrapper method for PDOStatement::fetchALL().
	 *
	 * @param string $sqlQuery
	 * @param array $params
	 * @param integer $fetchStyle
	 * @return array
	 */
	public static function getAll($sqlQuery, $params = NULL, $fetchStyle = PDO::FETCH_ASSOC){
	    // Initialize the return value to null
	    $result = NULL;
	
	    // Try to execute an SQL query or a stored procedure
	    try{
	    	// Get the database handler
	      	$database_handler = self::getHandler();
	
	      	// Prepare the query for execution
	      	$statement_handler = $database_handler->prepare($sqlQuery);
	
	      	// Execute the query
	      	$statement_handler->execute($params);
	      
	      	// Fetch result
	      	$result = $statement_handler->fetchAll($fetchStyle);
	    } catch(PDOException $e){
	      	// Close the database handler and trigger an error
	      	self::close();
	      	throw $e;
	    }
	
		// Return the query results
		return $result;
	}
	
	/**
	 * Wrapper method for PDOStatement::fetch().
	 *
	 * @param string $sqlQuery
	 * @param array $params
	 * @param integer $fetchStyle
	 * @return array
	 */
	public static function getRow($sqlQuery, $params = NULL, $fetchStyle = PDO::FETCH_ASSOC){
	    // Initialize the return value to null
	    $result = NULL;
	
	    // Try to execute an SQL query or a stored procedure
	    try{
		      // Get the database handler
		      $database_handler = self::getHandler();
		
		      // Prepare the query for execution
		      $statement_handler = $database_handler->prepare($sqlQuery);
		
		      // Execute the query
		      $statement_handler->execute($params);
		
		      // Fetch result
		      $result = $statement_handler->fetch($fetchStyle);
	    } catch(PDOException $e){
		      // Close the database handler and trigger an error
		      self::close();
		      throw $e;
	    }
	
	    // Return the query results
	    return $result;
 	}
 	
 	/**
 	 * Returns the first column value from the row.
 	 *
 	 * @param string $sqlQuery
 	 * @param array $params
 	 * @return variant
 	 */
	public static function getOne($sqlQuery, $params = NULL){
	    // Initialize the return value to null    
	    $result = null;
	
	    // Try to execute an SQL query or a stored procedure
	    try{
		      // Get the database handler
		      $database_handler = self::getHandler();
		
		      // Prepare the query for execution
		      $statement_handler = $database_handler->prepare($sqlQuery);
		
		      // Execute the query
		      $statement_handler->execute($params);
		
		      // Fetch result
		      $result = $statement_handler->fetch(PDO::FETCH_NUM);
		
		      /* Save the first value of the result set (first column of the first row)
		         to $result */
		      $result = $result[0];
	    } catch(PDOException $e){
		      // Close the database handler and trigger an error
		      self::close();
		      throw $e;
	    }
	
	    // Return the query results
	    return $result;
  	}
	
	/**
	 * Returns an initialized database handler.
	 *
	 * @return PDO
	 */
	static private function getHandler(){
		if(is_null(self::$_mHandler)){
			try{
				self::$_mHandler = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD,
						array(PDO::ATTR_PERSISTENT => DB_PERSISTENCY));
						
				self::$_mHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e){
				self::close();
				throw $e;
			}
		}
		
		return self::$_mHandler;
	}
}
?>