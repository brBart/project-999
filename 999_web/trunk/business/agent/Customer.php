<?php
/**
 * @package agent
 */
require_once('Agent.php');
require_once('../../data/agent/CustomerDAM.php');

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
	public function __construct($nit, $status){
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
	public function getInstance($nit){
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