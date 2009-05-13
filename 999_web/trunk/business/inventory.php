<?php
/**
 * Library with utility classes for making an inventory.
 * @package Inventory
 * @author Roberto Oliveros
 */

/**
 * For persistence purposes.
 */
require_once('business/persist.php');
/**
 * Library for accessing the database.
 */
require_once('data/inventory_dam.php');

/**
 * Represents a detail in a comparison report.
 * @package Inventory
 * @author Roberto Oliveros
 */
class ComparisonDetail{
	/**
	 * Holds the detail's product.
	 *
	 * @var Product
	 */
	private $_mProduct;
	
	/**
	 * Holds the physical quantity of the product.
	 *
	 * @var integer
	 */
	private $_mPhysical;
	
	/**
	 * Holds the system's quantity of the product.
	 *
	 * @var unknown_type
	 */
	private $_mSystem;
	
	/**
	 * Constructs the detail with the provided data.
	 *
	 * Use only when called from the database layer.
	 * @param Product $product
	 * @param integer $physical
	 * @param integer $system
	 * @throws Exception
	 */
	public function __construct(Product $product, $physical, $system){
		try{
			Persist::validateObjectFromDatabase($product);
			Number::validateUnsignedInteger($physical, 'Valor fisico inv&aacute;lido.');
			Number::validateUnsignedInteger($system, 'Valor del sistema inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en ComparisonDetail con datos ' .
					'erroneos! ' . $e->getMessage());
			throw $et;
		}
		
		$this->_mProduct = $product;
		$this->_mPhysical = $physical;
		$this->_mSystem = $system;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The fields in the array are bar_code, manufacturer, name, packaging, um, physical, system and diference.
	 * @return array
	 */
	public function show(){
		$manufacturer = $this->_mProduct->getManufacturer();
		$um = $this->_mProduct->getUnitOfMeasure();
		
		return array('bar_code' => $this->_mProduct->getBarCode(), 'manufacturer' => $manufacturer->getName(),
				'name' => $this->_mProduct->getName(), 'packaging' => $this->_mProduct->getPackaging(),
				'um' => $um->getName(), 'physical' => $this->_mPhysical, 'system' => $this->_mSystem,
				'diference' => sprintf('%+d', ($this->_mPhysical - $this->_mSystem)));
	}
}


/**
 * Represents a comparison report of a physical count of the inventory against the system's inventory.
 * @package Inventory
 * @author Roberto Oliveros
 */
class Comparison{
	/**
	 * Holds the comparison's internal id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the comparison's creation date.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mDate;
	
	/**
	 * Holds the user who created the comparison.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Holds the reason of why the creation of the comparison.
	 *
	 * @var string
	 */
	private $_mReason;
	
	/**
	 * Holds the flag that indicates if the comparison was made against the whole inventory.
	 *
	 * @var boolean
	 */
	private $_mGeneral;
	
	/**
	 * Holds the comparison details.
	 *
	 * @var array<ComparisonDetail>
	 */
	private $_mDetails;
	
	/**
	 * Holds the sum of all the physical quantities.
	 *
	 * @var integer
	 */
	private $_mPhysicalTotal;
	
	/**
	 * Holds the sum of all the system quantities.
	 *
	 * @var integer
	 */
	private $_mSystemTotal;
	
	/**
	 * Constructs the comparison with the provided data.
	 *
	 * Call only from the database layer please.
	 * @param integer $id
	 * @param string $date
	 * @param UserAccount $user
	 * @param string $reason
	 * @param boolean $general
	 * @param array<ComparisonDetail> $details
	 * @param integer $physical
	 * @param integer $system
	 * @throws Exception
	 */
	public function __construct($id, $date, UserAccount $user, $reason, $general, $details, $physical, $system){
		try{
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
			Date::validateDate($date, 'Fecha inv&aacute;lida.');
			Persist::validateObjectFromDatabase($user);
			String::validateString($reason, 'Motivo inv&aacute;lido.');
			if(empty($details))
				throw new Exception('No hay ningun detalle.');
			Number::validateUnsignedInteger($physical, 'Total fisico inv&aacute;lido.');
			Number::validateUnsignedInteger($system, 'Total del sistema inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Comparison con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mId = $id;
		$this->_mDate = $date;
		$this->_mUser = $user;
		$this->_mReason = $reason;
		$this->_mGeneral = (boolean)$general;
		$this->_mDetails = $details;
		$this->_mPhysicalTotal = $physical;
		$this->_mSystemTotal = $system;
	}
	
	/**
	 * Returns the comparison's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the comparison's date.
	 *
	 * @return string
	 */
	public function getDate(){
		return $this->_mDate;
	}
	
	/**
	 * Returns the comparison's creator.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $this->_mUser;
	}
	
	/**
	 * Returns the comparison's reason.
	 *
	 * @return string
	 */
	public function getReason(){
		return $this->_mReason;
	}
	
	/**
	 * Return an array with all the comparison's details.
	 *
	 * @return array<ComparisonDetail>
	 */
	public function getDetails(){
		return $this->_mDetails;
	}
	
	/**
	 * Returns true if the comparison was against the whole inventory.
	 *
	 * @return boolean
	 */
	public function isGeneral(){
		return $this->_mGeneral;
	}
	
	/**
	 * Returns the comparison's physical total.
	 *
	 * @return integer
	 */
	public function getPhysicalTotal(){
		return $this->_mPhysicalTotal;
	}
	
	/**
	 * Returns the comparison's system total.
	 *
	 * @return integer
	 */
	public function getSystemTotal(){
		return $this->_mSystemTotal;
	}
	
	/**
	 * Returns the diference between the total physical and total system quantities.
	 *
	 * Returns an string to display the integer sign also.
	 * @return string
	 */
	public function getTotalDiference(){
		return sprintf('%+d', $this->_mPhysicalTotal - $this->_mSystemTotal);
	}
	
	/**
	 * Returns a comparison with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Comparison
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return ComparisonDAM::getInstance($id, $total_pages, $total_items, $page);
	}
}



class CountDetail extends Persist{
	/**
	 * Holds the detail's product.
	 *
	 * @var Product
	 */
	private $_mProduct;
	
	/**
	 * Holds the product's quantity counted.
	 *
	 * @var integer
	 */
	private $_mQuantity;
	
	/**
	 * Flag that indicates if the detail has to be deteled.
	 *
	 * @var boolean
	 */
	private $_mDeleted = false;
	
	/**
	 * Constructs the detail with the provided data.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 * @param integer $status
	 */
	public function __construct(Product $product, $quantity, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		self::validateObjectFromDatabase($product);
		Number::validateUnsignedInteger($quantity, 'Cantidad inv&aacute;lida.');
		
		$this->_mProduct = $product;
		$this->_mQuantity = $quantity;
	}
	
	/**
	 * Returns the detail's product.
	 *
	 * @return Product
	 */
	public function getProduct(){
		return $this->_mProduct;
	}
	
	/**
	 * Returns the detail's quantity.
	 *
	 * @return integer
	 */
	public function getQuantity(){
		return $this->_mQuantity;
	}
	
	/**
	 * Returns the value of the detail's deleted flag.
	 *
	 * @return boolean
	 */
	public function isDeleted(){
		return $this->_mDeleted;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The array's fields are bar_code, manufacturer, name, packaging, um and quantity.
	 * @return array
	 */
	public function show(){
		$manufacturer = $this->_mProduct->getManufacturer();
		$um = $this->_mProduct->getUnitOfMeasure();
		
		return array('bar_code' => $this->_mProduct->getBarCode(), 'manufacturer' => $manufacturer->getName(),
				'name' => $this->_mProduct->getName(), 'packaging' => $this->_mProduct->getPackaging(),
				'um' => $um->getName(), 'quantity' => $this->_mQuantity);
	}
	
	/**
	 * Increases the detail's quantity property.
	 *
	 * @param integer $quantity
	 */
	public function increase($quantity){
		Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
		$this->_mQuantity += $quantity;
	}
	
	/**
	 * Sets the detail's deleted flag to true.
	 *
	 * Must not be called. If you need to delete the object call Count::deleteDetail() method instead and pass
	 * this object as the parameter.
	 */
	public function delete(){
		$this->_mDeleted = true;
	}
	
	/**
	 * Insert or deletes the detail in the database.
	 *
	 * Depending of the status property or its deleted flag, the appropiate action is taken.
	 * @param Count $count
	 */
	public function commit(Count $count){
		self::validateObjectFromDatabase($count);
		
		if($this->_mStatus == Persist::IN_PROGRESS)
			CountDetailDAM::insert($count, $this);
		elseif($this->_mStatus == Persist::CREATED && $this->_mDeleted)
			CountDetailDAM::delete($count, $this);
	}
}
?>