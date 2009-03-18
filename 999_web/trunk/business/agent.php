<?php
/**
 * Library with the business rules for the agents.
 * @package agent
 */

require_once('include/config.php');
require_once('data/agent_dam.php');

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
	 * the status must be set to FROM_DATABASE, otherwise set to JUST_CREATED. Due to the lack of 
	 * experience... sorry.
	 *
	 * @param integer $status
	 */
	public function __construct($status){
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
			throw new Exception('Ingrese el nombre.');
			
		$this->_mName = $name;
	}
	
	/**
	 * Saves agent's data to the database. Nit and Name must be set.
	 * 
	 * @return void
	 */
	public function save(){
		if(empty($this->_mNit))
			throw new Exception('Ingrese el nit.');
			
		if(empty($this->_mName))
			throw new Exception('Ingrese el nombre');
	}
	
	/**
	 * Validates if a nit is correct. Returns true if it is or false otherwise.
	 *
	 * @param string $nit
	 * @return boolean
	 */
	protected function validateNit($nit){
		if(preg_match('/^[0-9]+[-][0-9]$/', $nit))
			return true;
		else
			return false;
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
	public function __construct($nit, $status = JUST_CREATED){
		parent::__construct($status);
		
		$this->_mNit = $nit;
	}
	
	/**
	 * Returns an instance of a Customer with database data. If there is no match for the nit provided, a new
	 * Customer is created and return. Nit is validated, e.g. c/f, 1725045-5 are valids.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if(preg_match('@^[cC][\\\/.]?([fF]$|[fF]\.?$)@', $nit)){
			return new Customer('CF');   
		}
		elseif($this->validateNit($nit)){
			if(CustomerDAM::exist($nit))
				return CustomerDAM::getInstance($nit);
			else
				return new Customer($nit);
		}
		else
			throw new Exception('Nit invalido.');
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
		
		if($this->_mNit == 'CF')
			return;
		
		if($this->_mStatus == JUST_CREATED){
			CustomerDAM::insert($this);
			$this->_mStatus = FROM_DATABASE;
		}
		else
			CustomerDAM::update($this);
	}
}


/**
 * Defines common functionality for a organizations derived classes.
 * @package agent
 * @author Roberto Oliveros
 *
 */
abstract class Organization extends Agent{
	/**
	 * Internal object id.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Organization's telephone number.
	 *
	 * @var string
	 */
	protected $_mTelephone;
	
	/**
	 * Organization's address.
	 *
	 * @var string
	 */
	protected $_mAddress;
	
	/**
	 * Organization's email address.
	 *
	 * @var string
	 */
	protected $_mEmail;
	
	/**
	 * Organization's direct contact person.
	 *
	 * @var string
	 */
	protected $_mContact;
	
	/**
	 * Organization constructor method.
	 *
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = null, $status = JUST_CREATED){
		parent::__construct($status);
		
		$this->_mId = $id;
	}
	
	/**
	 * Returns object's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns organization's telephone number.
	 *
	 * @return string
	 */
	public function getTelephone(){
		return $this->_mTelephone;
	}
	
	/**
	 * Returns organization's address.
	 *
	 * @return string
	 */
	public function getAddress(){
		return $this->_mAddress;
	}
	
	/**
	 * Returns organization's email address.
	 *
	 * @return string
	 */
	public function getEmail(){
		return $this->_mEmail;
	}
	
	/**
	 * Returns organization's direct contact person.
	 *
	 * @return string
	 */
	public function getContact(){
		return $this->_mContact;
	}
	
	/**
	 * Returns object's status.
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->_mStatus;
	}
	
	/**
	 * Sets organization's nit. Nit is validated, e.g. 350682-7, 1725045-5 are valids, c/f or alikes are not.
	 *
	 * @param string $nit
	 */
	public function setNit($nit){
		if($this->validateNit())
			$this->_mNit = $nit;
		else
			throw new Exception('Nit invalido.');
	}
	
	/**
	 * Sets organization telephone number.
	 *
	 * @param string $telephone
	 */
	public function setTelephone($telephone){
		if(empty($telephone))
			throw new Exception('Ingrese telefono.');
			
		$this->_mTelephone = $telephone;
	}
	
	/**
	 * Sets organization's address.
	 *
	 * @param string $address
	 */
	public function setAddress($address){
		if(empty($address))
			throw new Exception('Ingrese direccion.');
			
		$this->_mAddress = $address;
	}
	
	/**
	 * Sets organization's email address. Note that must be a valid email format, e.g. anybody@whatever.com
	 *
	 * @param string $email
	 */
	public function setEmail($email){
		$pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/';
		if(!(preg_match($pattern, $email)))
			throw new Exception('Correo electronico invalido.');
			
		$this->_mEmail = $email;
	}
	
	/**
	 * Sets organization's direct contact person.
	 *
	 * @param string $contact
	 */
	public function setContact($contact){
		if(empty($contact))
			throw new Exception('Ingrese contacto.');
			
		$this->_mContact = $contact;
	}
	
	/**
	 * Returns an instance of a organization class.
	 *
	 * @param integer $id
	 * @return Organization
	 */
	abstract function getInstance($id){
		
	}
	
	/**
	 * Sets the received organization object to null and returns true if the object's status == JUST_CREATED.
	 * Otherwise just returns false.
	 *
	 * @param Organization $organ
	 * @return boolean
	 */
	static protected function delete(Organization $organ){
		if ($organ->getStatus() == JUST_CREATED){
			$organ = null;
			return true;
		}
		else
			return false;
	}
}
?>