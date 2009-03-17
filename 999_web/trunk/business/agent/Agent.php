<?php
/**
 * @package agent
 */

/**
 * Defines common functionality for agents derived classes.
 * @package agent
 * @author Roberto Oliveros
 */
abstract class Agent{
	/**
	 * Nit (Numero de Identificacion Tributaria) of the agent.
	 * @var string
	 */
	protected $_mNit;
	
	/**
	 * Name for the agent.
	 *
	 * @var string
	 */
	protected $_mName;
	
	/**
	 * Status of the object instance, e.g. unsaved = 0, saved = 1.
	 *
	 * @var unknown_type
	 */
	protected $_mStatus;
	
	/**
	 * Agent constructor method. Receives the status for the created instance object. If created from database,
	 * the status must be set to 1, otherwise set to 0 (default = 0). Due to the lack of experience... sorry.
	 *
	 * @param integer $status
	 */
	public function __construct($status = 0){
		$this->_mStatus = $status;
	}
	
	/**
	 * Returns the nit of the agent.
	 *
	 * @return string
	 */
	public function getNit(){
		return $this->_mNit;
	}
	
	/**
	 * Returns the name of the agent.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Sets the name of the agent.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		if(empty($name))
			throw new Exception('Name is empty.');
			
		$this->_mName = $name;
	}
	
	/**
	 * Saves agent's data to the database. Nit and Name must be set.
	 * 
	 * @return void
	 */
	public function save(){
		if(empty($this->_mNit))
			throw new Exception('Nit is empty.');
			
		if(empty($this->_mName))
			throw new Exception('Name is empty.');
	}
}
?>