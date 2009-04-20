<?php
/**
 * Library with the business rules for the Agents.
 * @package Agent
 */

/**
 * Includes the Persist package.
 */
require_once('business/persist.php');
/**
 * Includes the AgentDAM package.
 */
require_once('data/agent_dam.php');

/**
 * Represents an invoice's customer.
 * @package Agent
 * @author Roberto Oliveros
 */
class Customer extends PersistObject{
	/**
	 * Consumidor Final initials.
	 *
	 */
	const CF = 'CF';
	
	/**
	 * Holds the agent's nit (Numero de Identificacion Tributaria).
	 * 
	 * Note that must match the "^[0-9]+[-][0-9]$" pattern to be valid, e.g. 1725045-5.
	 * @var string
	 */
	private $_mNit;
	
	/**
	 * Holds the agent's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Constructs the customer object with the provided nit and status.
	 * 
	 * Do not use, use getInstance() instead. It is public because is called from database layer 
	 * corresponding class. Valid nit or consumidor final initials are required.
	 * @param string $nit
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($nit, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if($this->isConsumidorFinal($nit))
			$this->_mNit = CF;
		else{
			try{
				$this->validateNit($nit);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling Customer\'s constructor method with bad data! ' .
						$e->getMessage());
				throw $et;
			}
			$this->_mNit = $nit;
		}
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
	 * Returns the customer's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Sets the customer's name.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		Identifier::validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Set the object's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @return void
	 * @throws Exception
	 */
	public function setData($name){
		try{
			Identifier::validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Agent\'s setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
	}
	
	/**
	 * Validates the customer's nit.
	 * 
	 * Verifies that the nit is set correctly, e.g. 1725045-5. Otherwise it throws an exception.
	 * @param string $nit
	 * @return void
	 * @throws Exception
	 */
	public function validateNit($nit){
		if(!preg_match('/^[0-9]+[-][0-9]$/', $nit))
			throw new Exception('Nit inv&aacute:lido.');
	}
	
	/**
	 * Saves customer's data to the database.
	 * 
	 * If the object's status set to Persist::IN_PROGRESS the method insert()
	 * is called, if it's set to Persist::CREATED the method update() is called.
	 * @return void
	 * @throws Exception
	 */
	public function save(){
		if($this->_mNit == CF)
			return;
		
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			if(CustomerDAM::exist($this->_mNit))
				throw new Exception('Internal error, Nit already in database.');
				
			$this->insert();
			$this->_mStatus = self::CREATED;
		}
		else
			$this->update();
	}
	
	/**
	 * Returns an instance of a customer with database data. 
	 * 
	 * If there is no match for the nit provided, a new customer is created and return.
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if(self::isConsumidorFinal($nit)){
			return new Customer(CF);   
		}
		else{
			self::validateNit($nit);
			$customer = CustomerDAM::getInstance($nit);
			if(!$customer)
				return new Customer($nit);
			else
				return $customer;
		}
	}
	
	/**
	 * Inserts customer's data in the database.
	 * @return void
	 */
	protected function insert(){
		CustomerDAM::insert($this);
	}
	
	/**
	 * Updates customer's data in the database.
	 * @return void
	 */
	protected function update(){
		CustomerDAM::update($this);
	}
	
	/**
	 * Validates the object's main properties.
	 * 
	 * Verifies that the customer's nit and name are set correctly. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateNit($this->_mNit);
		Identifier::validateName($this->_mName);
	}
	
	/**
	 * Verifies if the provided nit represents a consumidor final.
	 * 
	 * Checks if the provided nit match the "^[cC][\\\/.]?([fF]$|[fF]\.?$)" pattern. Returns true if 
	 * equal, otherwise false.
	 * @param string $nit
	 * @return boolean
	 */
	private function isConsumidorFinal($nit){
		if(preg_match('@^[cC][\\\/.]?([fF]$|[fF]\.?$)@', $nit))
			return true;
		else
			return false;
	}
}


/**
 * Defines common functionality for a organizations derived classes.
 * @package Agent
 * @author Roberto Oliveros
 *
 */
abstract class Organization extends Identifier{
	/**
	 * Holds the organization's nit (Numero de Identificacion Tributaria).
	 * 
	 * Note that must match the "^[0-9]+[-][0-9]$" pattern to be valid, e.g. 1725045-5.
	 * @var string
	 */
	private $_mNit;
	
	/**
	 * Holds the organization's telephone number.
	 *
	 * @var string
	 */
	private $_mTelephone;
	
	/**
	 * Holds the organization's address.
	 *
	 * @var string
	 */
	private $_mAddress;
	
	/**
	 * Holds the organization's email address.
	 *
	 * Note that must be a valid email format, e.g. anybody@whatever.com
	 * @var string
	 */
	private $_mEmail;
	
	/**
	 * Holds the organization's direct contact person.
	 *
	 * @var string
	 */
	private $_mContact;
	
	/**
	 * Constructs the organization with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns the organization's nit.
	 *
	 * @return string
	 */
	public function getNit(){
		return $this->_mNit;
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
	 * Returns the organization's address.
	 *
	 * @return string
	 */
	public function getAddress(){
		return $this->_mAddress;
	}
	
	/**
	 * Returns the organization's email address.
	 *
	 * @return string
	 */
	public function getEmail(){
		return $this->_mEmail;
	}
	
	/**
	 * Returns the organization's direct contact person.
	 *
	 * @return string
	 */
	public function getContact(){
		return $this->_mContact;
	}
	
	/**
	 * Sets organization's nit.
	 * 
	 * Valid nit is required, consumidor final initials are not accepted.
	 * @param string $nit
	 */
	public function setNit($nit){
		Customer::validateNit($nit);			
		$this->_mNit = $nit;
	}
	
	/**
	 * Sets organization's telephone number.
	 *
	 * @param string $telephone
	 */
	public function setTelephone($telephone){
		$this->validateTelephone($telephone);			
		$this->_mTelephone = $telephone;
	}
	
	/**
	 * Sets the organization's address.
	 *
	 * @param string $address
	 */
	public function setAddress($address){
		$this->validateAddress($address);			
		$this->_mAddress = $address;
	}
	
	/**
	 * Sets organization's email address.
	 * 
	 * @param string $email
	 */
	public function setEmail($email){
		$this->validateEmail($email);
		$this->_mEmail = $email;
	}
	
	/**
	 * Sets the organization's direct contact person.
	 *
	 * @param string $contact
	 */
	public function setContact($contact){
		$this->_mContact = $contact;
	}
	
	/**
	 * Set the organization's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $nit
	 * @param string $name
	 * @param string $telephone
	 * @param string $address
	 * @param string $email
	 * @param string $contact
	 * @throws Exception
	 */
	public function setData($nit, $name, $telephone, $address, $email, $contact){
		parent::setData($name);
		
		try{
			Customer::validateNit($nit);
			$this->validateTelephone($telephone);
			$this->validateAddress($address);
			$this->validateEmail($email);
		} catch(Exception $e){
			$et = new Exception('Internal Error, calling Organization\'s setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mNit = $nit;
		$this->_mTelephone = $telephone;
		$this->_mAddress = $address;
		$this->_mEmail = $email;
		$this->_mContact = $contact;
	}
	
	/**
	 * Returns an instance of a organization class.
	 *
	 * @param integer $id
	 * @return Organization
	 */
	abstract static public function getInstance($id);
	
	/**
	 * Validates the organization's main properties.
	 * 
	 * Verifies the organization's name, telephone and address are not empty. The nit must be valid. Otherwise
	 * it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		Customer::validateNit($this->_mNit);
		$this->validateTelephone($this->_mTelephone);
		$this->validateAddress($this->_mAddress);
	}
	
	/**
	 * Validates the organization's telephone number.
	 * 
	 * Verifies that the telephone is not empty. Otherwise it throws an exception.
	 * @param string $telephone
	 * @return void
	 * @throws Exception
	 */
	private function validateTelephone($telephone){
		if(empty($telephone))
			throw new Exception('Ingrese telefono.');
	}
	
	/**
	 * Validates an organization's address.
	 * 
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $address
	 * @return void
	 * @throws Exception
	 */
	private function validateAddress($address){
		if(empty($address))
			throw new Exception('Ingrese direcci&oacute;n.');
	}
	
	/**
	 * Validates an organization's email address.
	 * 
	 * Must be in the correct format, e.g. example@yeah.com. Otherwise it throws an exception.
	 * @param string $email
	 * @return void
	 * @throws Exception
	 */
	private function validateEmail($email){
		if(!empty($email)){
			$pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/';
			if(!preg_match($pattern, $email))
				throw new Exception('Email inv&aacute;lido.');
		}
	}
}


/**
 * Defines functionality for a supplier class, mostly used in receipts documents.
 * 
 * @package Agent
 * @author Roberto Oliveros
 */
class Supplier extends Organization{
	/**
	 * Returns an instance of a supplier.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return Supplier
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return SupplierDAM::getInstance($id);
	}
	
	/**
	 * Deletes the supplier from database.
	 * 
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Supplier $obj
	 * @return boolean
	 */
	static public function delete(Supplier $obj){
		self::validateObjectFromDatabase($obj);
		return SupplierDAM::delete($obj);
	}
	
	/**
	 * Insert the supplier's data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return SupplierDAM::insert($this);
	}
	
	/**
	 * Updates the supplier's data in the database.
	 * @return void
	 */
	protected function update(){
		SupplierDAM::update($this);
	}
}


/**
 * Defines functionality for a branch class, mostly used in shipments documents.
 * @package Agent
 * @author Roberto Oliveros
 */
class Branch extends Organization{
	/**
	 * Returns an instance of a branch.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return Branch
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return BranchDAM::getInstance($id);
	}
		
	/**
	 * Deletes the branch from the database.
	 *
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Branch $obj
	 * @return boolean
	 */
	static public function delete(Branch $obj){
		self::validateObjectFromDatabase($obj);
		return BranchDAM::delete($obj);
	}
	
	/**
	 * Inserts the branch's data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return BranchDAM::insert($this);
	}
	
	/**
	 * Updates the branch's data in the database.
	 * @return void
	 */
	protected function update(){
		BranchDAM::update($this);
	}
}
?>