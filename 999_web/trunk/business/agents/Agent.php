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
	private $_mNit;
	
	/**
	 * Name for the agent.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Status of the object instance, e.g. unsaved = 0, saved = 1.
	 *
	 * @var unknown_type
	 */
	private $_mStatus;
	
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
		if(is_null($name))
			throw new Exception('Name is empty.');
			
		$this->_mName = $name;
	}
}
?>