<?php
/**
 * Library with the business rules for the agents.
 * @package agent
 */

require_once ('data/agent_dam.php');

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


/**
 * Defines functionality for the customer class used for invoices.
 * @package agent
 * @author Roberto Oliveros
 */
class Customer extends Agent{
	/**
	 * Customer constructor method. Do not use, use Customer::getInstance instead. It is public because is called
	 * from database layer corresponding class also. Lack of experience... sorry.
	 *
	 * @param string $nit
	 * @param integer $status
	 */
	public function __construct($nit, $status = 0){
		parent::__construct($status);
		
		$this->_mNit = $nit;
	}
	
	/**
	 * Returns an instance of a Customer with database data. If there is no match for the nit provided, a new
	 * Customer is created and return.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if(empty($nit))
			throw new Exception('Nit is empty.');
		
		try{
			$customer = CustomerDAM::getInstance($nit);
		} catch(Exception $e){
			return new Customer($nit);
		}
		
		return $customer;
	}
	
	/**
	 * Set data provided by the database. Must be call only from the database layer corresponding class.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setData($name){
		$this->_mName = $name;
	}
	
	/**
	 * Saves Customer data to the database.
	 * @return void
	 */
	public function save(){
		parent::save();
		
		if(preg_match('/^c\\/f$/i',$this->_mNit))
			return;
			
		if($this->_mStatus == 0)
			if(CustomerDAM::exist($this->_mNit))
				throw new Exception('Customer already exist.');
			else{
				CustomerDAM::insert($this);
				$this->_mStatus = 1;
			}
		else
			CustomerDAM::update($this);
	}
}
?>