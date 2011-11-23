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
	const CF = 'C.F.';
	
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
			$this->_mNit = self::CF;
		else{
			try{
				String::validateNit($nit, 'Nit inv&aacute;lido.');
			} catch(ValidateException $e){
				$et = new Exception('Interno: Llamando al metodo construct en Customer con datos erroneos! ' .
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
	 */
	public function setName($name){
		$this->_mName = $name;
		
		if($this->_mNit != self::CF)
			String::validateString($name, 'Nombre inv&aacute;lido.');
	}
	
	/**
	 * Set the object's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @throws Exception
	 */
	public function setData($name){
		try{
			String::validateString($name, 'Nombre inv&aacute;lido.');
		} catch(ValidateException $e){
			$et = new Exception('Interno: Llamando al metodo setData en Agent con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
	}
	
	/**
	 * Saves customer's data to the database.
	 * 
	 * If the object's status set to Persist::IN_PROGRESS the method insert()
	 * is called, if it's set to Persist::CREATED the method update() is called.
	 * @throws Exception
	 */
	public function save(){
		if($this->_mNit == self::CF)
			return;
		
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			if(CustomerDAM::exist($this->_mNit))
				throw new ValidateException('Nit ya existe en la base de datos.', 'nit');
				
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
			return new Customer(self::CF);   
		}
		else{
			String::validateNit($nit, 'Nit inv&aacute;lido.', 'nit');
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
		String::validateNit($this->_mNit, 'Nit inv&aacute;lido.', 'nit');
		String::validateString($this->_mName, 'Nombre inv&aacute;lido.', 'name');
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
		$this->_mNit = $nit;
		String::validateNit($nit, 'Nit inv&aacute;lido.');
	}
	
	/**
	 * Sets organization's telephone number.
	 *
	 * @param string $telephone
	 */
	public function setTelephone($telephone){
		$this->_mTelephone = $telephone;
		String::validateString($telephone, 'Telefono inv&aacute;lido.');
	}
	
	/**
	 * Sets the organization's address.
	 *
	 * @param string $address
	 */
	public function setAddress($address){
		$this->_mAddress = $address;
		String::validateString($address, 'Direcci&oacute;n inv&aacute;lida.');
	}
	
	/**
	 * Sets organization's email address.
	 * 
	 * @param string $email
	 */
	public function setEmail($email){
		$this->_mEmail = $email;
		if($email != '')
			$this->validateEmail($email);
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
	public function setData($nit, $name, $telephone, $address, $email = NULL, $contact = NULL){
		parent::setData($name);
		
		try{
			String::validateNit($nit, 'Nit inv&aacute;lido.');
			String::validateString($telephone, 'Telefono inv&aacute;lido.');
			String::validateString($address, 'Direcci&oacute;n inv&aacute;lida.');
			if(!is_null($email) && $email != '')
				$this->validateEmail($email);
		} catch(ValidateException $e){
			$et = new Exception('Interno: LLamando al metodo setData en Organization con datos erroneos! ' .
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
		
		String::validateNit($this->_mNit, 'Nit inv&aacute;lido.', 'nit');
		String::validateString($this->_mTelephone, 'Telefono inv&aacute;lido.', 'telephone');
		String::validateString($this->_mAddress, 'Direcci&oacute;n inv&aacute;lida.', 'address');
		if(!is_null($this->_mEmail) && $this->_mEmail != '')
			$this->validateEmail($this->_mEmail);
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
		$pattern = '/^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,4})?$/';
		if(!preg_match($pattern, $email))
			throw new ValidateException('Email inv&aacute;lido. Ejemplo: fulano@dominio.com', 'email');
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
		return SupplierDAM::getInstance($id);
	}
	
	/**
	 * Deletes the supplier from database.
	 * 
	 * Throws an exception due dependencies.
	 * @param Supplier $obj
	 * @throws Exception
	 */
	static public function delete(Supplier $obj){
		if(!SupplierDAM::delete($obj))
			throw new Exception('Proveedor tiene dependencias (recibos, devoluciones o productos) y no se ' .
					'puede eliminar.');
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
		return BranchDAM::getInstance($id);
	}
		
	/**
	 * Deletes the branch from the database.
	 *
	 * Throws an exception due dependencies.
	 * @param Branch $obj
	 * @throws Exception
	 */
	static public function delete(Branch $obj){
		if(!BranchDAM::delete($obj))
			throw new Exception('Sucursal tiene dependencias (envios) y no se puede eliminar.');
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