<?php
/**
 * Library with the business rules for the Agents.
 * @package Agent
 */

require_once('include/config.php');
require_once('data/agent_dam.php');

/**
 * Defines common functionality for agents derived classes.
 * @package Agent
 * @author Roberto Oliveros
 */
abstract class Agent{
	/**
	 * Nit (Numero de Identificacion Tributaria) of the agent.
	 * @var string
	 */
	protected $_mNit;
	
	/**
	 * Status of the object instance, e.g. JUST_CREATED or FROM_DATABASE.
	 *
	 * @var integer
	 */
	protected $_mStatus;
	
	/**
	 * Name for the agent.
	 *
	 * @var string
	 */
	private $_mName;
	
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
	 * Returns the object's status.
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->_mStatus;
	}
	
	/**
	 * Sets the name of the agent.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Set data provided by the database. Must be call only from the database layer corresponding class.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setData($name){
		try{
			$this->validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Agent\'s setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
	}
	
	/**
	 * Validates if a nit is correct. Throws an exception if it is.
	 *
	 * @param string $nit
	 * @return void
	 */
	protected function validateNit($nit){
		if(!preg_match('/^[0-9]+[-][0-9]$/', $nit))
			throw new Exception('Nit inv&aacute:lido.');
	}
	
	/**
	 * Proves if nit and name are set correctly.
	 * 
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateNit($this->_mNit);
		$this->validateName($this->_mName);
	}
	
	/**
	 * Validates if the name is correct. Throws an exception if it is not.
	 *
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
}


/**
 * Defines functionality for the customer class used for invoices.
 * @package Agent
 * @author Roberto Oliveros
 */
class Customer extends Agent{
	/**
	 * Defines Consumidor Final initials.
	 *
	 */
	const CF = 'CF';
	
	/**
	 * Customer constructor method. Do not use, use Customer::getInstance instead. It is public because is called
	 * from database layer corresponding class also. Lack of experience... sorry.
	 *
	 * @param string $nit
	 * @param integer $status
	 */
	public function __construct($nit, $status = JUST_CREATED){
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
	 * Saves Customer's data to the database.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mNit == CF)
			return;
		
		if($this->_mStatus == JUST_CREATED){
			if(CustomerDAM::exist($this->_mNit))
				throw new Exception('Internal error, Nit already in database.');
				
			CustomerDAM::insert($this);
			$this->_mStatus = FROM_DATABASE;
		}
		else
			CustomerDAM::update($this);
	}
	
	/**
	 * Returns an instance of a Customer with database data. If there is no match for the nit provided, a new
	 * Customer is created and return. Nit is validated, e.g. c/f, 1725045-5 are valids.
	 *
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
	 * Verifies if the provided nit == Consumidor Final. Returns true if equal, otherwise false.
	 *
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
	private $_mTelephone;
	
	/**
	 * Organization's address.
	 *
	 * @var string
	 */
	private $_mAddress;
	
	/**
	 * Organization's email address.
	 *
	 * @var string
	 */
	private $_mEmail;
	
	/**
	 * Organization's direct contact person.
	 *
	 * @var string
	 */
	private $_mContact;
	
	/**
	 * Organization constructor method.
	 *
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = JUST_CREATED){
		parent::__construct($status);
		
		if(!is_null($id))
			$this->validateId($id);
		
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
	 * Sets organization's nit. Nit is validated, e.g. 350682-7, 1725045-5 are valids, c/f or alikes are not.
	 *
	 * @param string $nit
	 */
	public function setNit($nit){
		$this->validateNit($nit);			
		$this->_mNit = $nit;
	}
	
	/**
	 * Sets organization telephone number.
	 *
	 * @param string $telephone
	 */
	public function setTelephone($telephone){
		$this->validateTelephone($telephone);			
		$this->_mTelephone = $telephone;
	}
	
	/**
	 * Sets organization's address.
	 *
	 * @param string $address
	 */
	public function setAddress($address){
		$this->validateAddress($address);			
		$this->_mAddress = $address;
	}
	
	/**
	 * Sets organization's email address. Note that must be a valid email format, e.g. anybody@whatever.com
	 *
	 * @param string $email
	 */
	public function setEmail($email){
		$this->validateEmail($email);
		$this->_mEmail = $email;
	}
	
	/**
	 * Sets organization's direct contact person.
	 *
	 * @param string $contact
	 */
	public function setContact($contact){
		$this->_mContact = $contact;
	}
	
	/**
	 * Set data provided by the database. Must be call only from the database layer corresponding class.
	 *
	 * @param string $nit
	 * @param string $name
	 * @param string $telephone
	 * @param string $address
	 * @param string $email
	 * @param string $contact
	 */
	public function setData($nit, $name, $telephone, $address, $email, $contact){
		parent::setData($name);
		
		try{
			$this->validateNit($nit);
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
	 * Saves Organization's data to the database.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == JUST_CREATED){
			$this->_mId = $this->insert();
			$this->_mStatus = FROM_DATABASE;
		}
		else
			$this->update();
	}
	
	/**
	 * Returns an instance of a organization class.
	 *
	 * @param integer $id
	 * @return Organization
	 */
	abstract static public function getInstance($id);
	
	/**
	 * Validates an organization's id. Throws an exception if it is not.
	 *
	 * @param integer $id
	 */
	protected function validateId($id){
		if(!is_int($id))
			throw new Exception('Id inv&aacute;lido.');
	}
	
	/**
	 * Proves that telephone and address are set correctly. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		$this->validateTelephone($this->_mTelephone);
		$this->validateAddress($this->_mAddress);
	}
	
	/**
	 * Proves that the received organization's status != JUST_CREATED. Otherwise it throws an exception.
	 *
	 * @param Organization $organ
	 * @return boolean
	 */
	static protected function validateStatusForDelete(Organization $obj){
		if ($obj->_mStatus == JUST_CREATED)
			throw new Exception('Cannot delete a just created organization from database.');
	}
	
	/**
	 * Insert Organization's data to the database.
	 * @return integer
	 */
	abstract protected function insert();
	
	/**
	 * Updates Organization's data in the database.
	 * @return void
	 */
	abstract protected function update();
	
	/**
	 * Validates an organization's telephone number. Throws an exception if it is not.
	 *
	 * @param string $telephone
	 * @return void
	 */
	private function validateTelephone($telephone){
		if(empty($telephone))
			throw new Exception('Ingrese telefono.');
	}
	
	/**
	 * Validates an organization's address. Throws an exception if it is not.
	 *
	 * @param string $address
	 * @return void
	 */
	private function validateAddress($address){
		if(empty($address))
			throw new Exception('Ingrese direcci&oacute;n.');
	}
	
	/**
	 * Validates an organization's email address. Throws an exception if it is not.
	 *
	 * @param string $email
	 * @return void
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
	 * Returns an instance of a Supplier. Returns NULL if there was no match in the database.
	 *
	 * @param integer $id
	 * @return Supplier
	 */
	static public function getInstance($id){
		self::validateId($id);
		return SupplierDAM::getInstance($id);
	}
	
	/**
	 * Deletes Supplier from database. Returns true confirming the deletion, false otherwise because it
	 * has dependencies.
	 *
	 * @param Supplier $obj
	 * @return boolean
	 */
	static public function delete(Supplier $obj){
		self::validateStatusForDelete($obj);
		return SupplierDAM::delete($obj);
	}
	
	/**
	 * Insert Supplier's data in the database.
	 * @return integer
	 */
	protected function insert(){
		return SupplierDAM::insert($this);
	}
	
	/**
	 * Updates Supplier's data in the database.
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
	 * Returns an instance of a Branch. Returns NULL if there was no match in the database.
	 *
	 * @param integer $id
	 * @return Branch
	 */
	static public function getInstance($id){
		self::validateId($id);
		return BranchDAM::getInstance($id);
	}
		
	/**
	 * Deletes Branch from the database.
	 *
	 * @param Branch $branch
	 * @return boolean
	 */
	static public function delete(Branch $obj){
		self::validateStatusForDelete($obj);
		return BranchDAM::delete($obj);
	}
	
	/**
	 * Inserts Branch's data in the database.
	 * @return integer
	 */
	protected function insert(){
		return BranchDAM::insert($this);
	}
	
	/**
	 * Updates Branch's data in the database.
	 * @return void
	 */
	protected function update(){
		BranchDAM::update($this);
	}
}
?>