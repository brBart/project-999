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
 * For paging purposes.
 */
require_once('business/itemized.php');
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
	protected $_mProduct;
	
	/**
	 * Holds the physical quantity of the product.
	 *
	 * @var integer
	 */
	protected $_mPhysical;
	
	/**
	 * Holds the system's quantity of the product.
	 *
	 * @var integer
	 */
	protected $_mSystem;
	
	/**
	 * Holds the diference between the physical and system quantities.
	 * 
	 * @var integer
	 */
	protected $_mDiference;
	
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
		$this->_mDiference = $this->_mPhysical - $this->_mSystem;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The fields in the array are bar_code, manufacturer, name, um, physical, system and diference.
	 * @return array
	 */
	public function show(){
		$manufacturer = $this->_mProduct->getManufacturer();
		$um = $this->_mProduct->getUnitOfMeasure();
		
		return array('bar_code' => $this->_mProduct->getBarCode(), 'manufacturer' => $manufacturer->getName(),
				'product' => $this->_mProduct->getName(), 'um' => $um->getName(),
				'physical' => $this->_mPhysical, 'system' => $this->_mSystem,
				'diference' => ($this->_mDiference == 0) ? '0' : sprintf('%+d', ($this->_mDiference)));
	}
}


/**
 * Represents a comparison report of a physical count of the inventory against the system's inventory.
 * @package Inventory
 * @author Roberto Oliveros
 */
class Comparison implements Itemized{
	/**
	 * Holds the comparison's internal id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the comparison's creation date.
	 *
	 * Date and time format: 'dd/mm/yyyy hh:mm:ss'.
	 * @var string
	 */
	private $_mDateTime;
	
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
	 * @param string $dateTime
	 * @param UserAccount $user
	 * @param string $reason
	 * @param boolean $general
	 * @param array<ComparisonDetail> $details
	 * @param integer $physical
	 * @param integer $system
	 * @throws Exception
	 */
	public function __construct($id, $dateTime, UserAccount $user, $reason, $general, $details, $physical, $system){
		try{
			Number::validatePositiveNumber($id, 'Id inv&aacute;lido.');
			Date::validateDateTime($dateTime, 'Fecha y hora inv&aacute;lida.');
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
		$this->_mDateTime = $dateTime;
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
	 * Returns the comparison's date and time.
	 *
	 * @return string
	 */
	public function getDateTime(){
		return $this->_mDateTime;
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
		$diference = $this->_mPhysicalTotal - $this->_mSystemTotal;
		return ($diference == 0) ? '0' : sprintf('%+d', $diference);
	}
	
	/**
	 * Returns a comparison with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Comparison
	 */
	static public function getInstance($id){
		Number::validatePositiveNumber($id, 'N&uacute;mero inv&aacute;lido.');
		return ComparisonDAM::getInstance($id);
	}
	
	/**
	 * Returns true if the comparison exists.
	 * 
	 * @param integer $id
	 * @return boolean
	 */
	static public function exists($id){
		return ComparisonDAM::exists($id);
	}
}


/**
 * Represents a detail in a count document, a sole product physical count.
 * @package Inventory
 * @author Roberto Oliveros
 */
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
		
		Number::validateUnsignedNumber($quantity, 'Cantidad inv&aacute;lida.');
		
		$this->_mProduct = $product;
		$this->_mQuantity = $quantity;
	}
	
	/**
	 * Returns the detail's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mProduct->getId();
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
	 * The array's fields are bar_code, manufacturer, name, um and quantity.
	 * @return array
	 */
	public function show(){
		$manufacturer = $this->_mProduct->getManufacturer();
		$um = $this->_mProduct->getUnitOfMeasure();
		
		return array('id' => $this->getId(), 'bar_code' => $this->_mProduct->getBarCode(),
				'manufacturer' => $manufacturer->getName(), 'product' => $this->_mProduct->getName(),
				'um' => $um->getName(), 'quantity' => $this->_mQuantity);
	}
	
	/**
	 * Increases the detail's quantity property.
	 *
	 * @param integer $quantity
	 */
	public function increase($quantity){
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
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
		if($this->_mStatus == Persist::IN_PROGRESS)
			CountDetailDAM::insert($count, $this);
		elseif($this->_mStatus == Persist::CREATED && $this->_mDeleted)
			CountDetailDAM::delete($count, $this);
	}
}


/**
 * Represents a physical count of the inventory.
 * @package Inventory
 * @author Roberto Oliveros
 */
class Count extends PersistObject implements Itemized{
	/**
	 * Holds the count's internal id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the count's creation date.
	 *
	 * Date and time format: 'dd/mm/yyyy hh:mm:ss'.
	 * @var string
	 */
	private $_mDateTime;
	
	/**
	 * Holds the user who created the count.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Holds the why of the count.
	 *
	 * @var string
	 */
	private $_mReason;
	
	/**
	 * Holds an array with all the count's details.
	 *
	 * @var array<CountDetail>
	 */
	private $_mDetails = array();
	
	/**
	 * Holds the count's total, the sum of all the details' quantities.
	 *
	 * @var integer
	 */
	private $_mTotal = 0;
	
	/**
	 * Constructs the count with data provided.
	 *
	 * Arguments must be passed only when called from the database layer correponding class.
	 * @param integer $id
	 * @param string $dateTime
	 * @param UserAccount $user
	 * @param integer $status
	 */
	public function __construct($id = NULL, $dateTime = NULL, $user = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($id))
			try{
				Number::validatePositiveNumber($id, 'Id inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Count con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
		
		if(!is_null($dateTime)){
			try{
				Date::validateDateTime($dateTime, 'Fecha y hora inv&aacute;lida.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Count con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			$this->_mDateTime = $dateTime;
		}
		
		if(!is_null($user)){
			$this->_mUser = $user;
		}
		else{
			$helper = ActiveSession::getHelper();
			$this->_mUser = $helper->getUser();
		}
			
		$this->_mId = $id;
	}
	
	/**
	 * Returns the count's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the count's creation date and time.
	 *
	 * @return string
	 */
	public function getDateTime(){
		return $this->_mDateTime;
	}
	
	/**
	 * Retursn the count's creator.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $this->_mUser;
	}
	
	/**
	 * Returns the count's reason.
	 *
	 * @return string
	 */
	public function getReason(){
		return $this->_mReason;
	}
	
	/**
	 * Returns the detail which id matches the id provided.
	 *
	 * NULL is returned in case there was no match.
	 * @param integer $id
	 * @return CountDetail
	 */
	public function getDetail($id){
		Number::validatePositiveNumber($id, 'Id inv&aacute;lido.');
		foreach($this->_mDetails as &$detail)
			if($detail->getId() == $id)
				return $detail;
				
		return NULL;
	}
	
	/**
	 * Returns an array with all the count's details.
	 *
	 * @return array<CountDetail>
	 */
	public function getDetails(){
		$details = array();
		
		foreach($this->_mDetails as &$detail)
			if(!$detail->isDeleted())
				$details[] = $detail;
				
		return $details;
	}
	
	/**
	 * Returns the count's total.
	 *
	 * @return integer
	 */
	public function getTotal(){
		return $this->_mTotal;
	}
	
	/**
	 * Sets the count's reason.
	 *
	 * Method can only be called if the object's status property is set to Persist::IN_PROGRESS.
	 * @param string $reason
	 * @throws Exception
	 */
	public function setReason($reason){
		if($this->_mStatus == Persist::CREATED)
			throw new Exception('No se puede editar el motivo.');
		
		$this->_mReason = $reason;
		String::validateString($reason, 'Motivo inv&aacute;lido.');
	}
	
	/**
	 * Sets the count properties.
	 *
	 * Must be called only from the database layer. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $reason
	 * @param integer $total
	 * @param array<CountDetail> $details
	 */
	public function setData($reason, $total, $details){
		try{
			String::validateString($reason, 'Motivo inv&aacute;lido.');
			Number::validatePositiveInteger($total, 'Total inv&aacute;lido.');
			if(empty($details))
				throw new Exception('No hay ningun detalle.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Count con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mReason = $reason;
		$this->_mTotal = $total;
		$this->_mDetails = $details;
	}
	
	/**
	 * Adds a detail to the count.
	 *
	 * @param CountDetail $newDetail
	 */
	public function addDetail(CountDetail $newDetail){
		$this->_mTotal += $newDetail->getQuantity();
		
		// For moving the modified detail to the last place.
		$temp_details = array();
		foreach($this->_mDetails as &$detail)
			if($detail->getId() != $newDetail->getId())
				$temp_details[] = $detail;
			elseif($detail->_mStatus == Persist::CREATED){
				if(!$detail->isDeleted()){
					$newDetail->increase($detail->getQuantity());
					$detail->delete();
				}
				$temp_details[] = $detail;
			}
			else
				$newDetail->increase($detail->getQuantity());
			
		$temp_details[] = $newDetail;
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Deletes the detail from the count.
	 *
	 * @param CountDetail $purgeDetail
	 */
	public function deleteDetail(CountDetail $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mDetails as &$detail)
			if($detail->getId() != $purgeDetail->getId())
				$temp_details[] = $detail;
			elseif($detail->getStatus() == Persist::CREATED){
				// In case it was already deleted.
				if(!$detail->isDeleted()){
					$this->_mTotal -= $detail->getQuantity();
					$detail->delete();
				}
				$temp_details[] = $detail;
			}
			else
				$this->_mTotal -= $detail->getQuantity();
		
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Saves the count's data in the database.
	 *
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->_mDateTime = date('d/m/Y H:i:s');
			$this->_mId = $this->insert();
			$this->_mStatus = Persist::CREATED;
		}
		else
			$this->update();
		
		foreach($this->_mDetails as &$detail)
			$detail->commit($this);
			
		return $this->_mId;
	}
	
	/**
	 * Returns an instance of a count.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Count
	 */
	static public function getInstance($id){
		Number::validatePositiveNumber($id, 'N&uacute;mero inv&aacute;lido.', 'count_id');
		return CountDAM::getInstance($id);
	}
	
	/**
	 * Deletes the count from the database.
	 * 
	 * @param Count $obj
	 */
	static public function delete(Count $obj){
		CountDAM::delete($obj);
	}
	
	/**
	 * Validates the count main properties.
	 *
	 * Reason must not be NULL and details must not be empty.
	 */
	protected function validateMainProperties(){
		String::validateString($this->_mReason, 'Motivo inv&aacute;lido.', 'reason');
		if(!$this->hasDetails())
			throw new ValidateException('No hay ningun detalle.', 'bar_code');
	}
	
	/**
	 * Inserts the count's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return CountDAM::insert($this);
	}
	
	/**
	 * Updates the count's data in the database.
	 *
	 */
	protected function update(){
		CountDAM::update($this);
	}
	
	/**
	 * Returns true if the count has any details. False if not.
	 *
	 * @return boolean
	 */
	private function hasDetails(){
		if(empty($this->_mDetails))
			return false;
			
		$count = count($this->_mDetails);
		$deleted = 0;
		
		foreach($this->_mDetails as $detail)
			if($detail->isDeleted())
				$deleted++;
				
		if($deleted == $count)
			return false;
		else
			return true;
	}
}


/**
 * Utility class for creating a comparison report.
 * @package Inventory
 * @author Roberto Oliveros
 */
class ComparisonEvent{
	/**
	 * Creates the comparison report against the inventory in the database and returns the new created
	 * report's id.
	 *
	 * @param Count $count
	 * @param string $reason
	 * @param boolean $general
	 * @return integer
	 */
	static public function apply(Count $count, $reason, $general = false){
		String::validateString($reason, 'Motivo inv&aacute;lido.', 'reason');
		
		$date = date('d/m/Y H:i:s');
		$helper = ActiveSession::getHelper();
		$user = $helper->getUser();
		
		return ComparisonDAM::insert($date, $user, $count, $reason, $general);
	}
}


/**
 * Utility class for adding details to a count document through a text file.
 * @package Inventory
 * @author Roberto Oliveros
 */
class Parser{
	/**
	 * Parse the file located in the provided url and add the details to the provided count.
	 *
	 * @param Count $count
	 * @param string $url
	 * @throws Exception
	 */
	static public function parseFile(Count $count, $url){
		String::validateString($url, 'Ruta inv&aacute;lida.');
		
		if(!file_exists($url))
			throw new Exception('Archivo no encontrado en: ' . $url);
			
		$data = file($url);
		
		if(empty($data))
			throw new Exception('Archivo no tiene detalles.');
		
		$newDetails = array();
		for($i = 0; $i < count($data); $i++){
			$line = explode(',', $data[$i]);
			
			if(count($line) < 2)
				throw new Exception('Formato inv&aacute;lido en linea: ' . ($i + 1));
				
			$detail[0] = trim($line[0]);
			$detail[1] = trim($line[1]);
			
			String::validateString($detail[0], 'Barra inv&aacute;lida en linea: ' . ($i + 1) . '.');
			Number::validateUnsignedNumber($detail[1], 'Cantidad inv&aacute;lida en linea: ' . ($i + 1) . '.');
			if($detail[1] > MAX_QUANTITY)
				throw new ValidateException('Cantidad inv&aacute;lida en linea: ' . ($i + 1) .
						'. Excede valor maximo de '. MAX_QUANTITY . '.');
			
			$newDetails[] = $detail;
		}
		
		self::addDetails($count, $newDetails);
	}
	
	/**
	 * Add all the provided array's items to the provided count as details.
	 *
	 * @param Count $count
	 * @param array $newDetails
	 * @throws Exception
	 */
	static private function addDetails(Count $count, $newDetails){
		$details = array();
		
		for($i = 0; $i < count($newDetails); $i++){
			$id = Product::getProductIdByBarCode($newDetails[$i][0]);
			if($id == 0)
				throw new Exception('Codigo barra de producto no existe: ' . $newDetails[$i][0] . '. Linea: ' .
						($i + 1));
			else
				$product = Product::getInstance($id);
						
			$details[] = new CountDetail($product, $newDetails[$i][1]);
		}
		
		foreach($details as $detail)
			$count->addDetail($detail);
	}
}


/**
 * Class with the necessary data for printing a counting template.
 * @package Inventory
 * @author Roberto Oliveros
 */
class CountingTemplate{
	/**
	 * Returns an array with the necessary data for printing the template ordered by product name.
	 *
	 * The array's fields are id, bar_code, manufacturer and name.
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByProduct($first = NULL, $last = NULL){
		String::validateString($first, 'Seleccione el primer producto.');
		String::validateString($last, 'Seleccione el segundo producto.');
		
		return CountingTemplateDAM::getDataByProduct($first, $last);
	}
	
	/**
	 * Returns an array with the necessary data for printing the template ordered by manufacturer name.
	 *
	 * The array's fields are id, bar_code, manufacturer and name.
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	static public function getDataByManufacturer($first = NULL, $last = NULL){
		String::validateString($first, 'Seleccione la primera casa.');
		String::validateString($last, 'Seleccione la segunda casa.');

		return CountingTemplateDAM::getDataByManufacturer($first, $last);
	}
}


/**
 * Represents a detail in a comparison filter report.
 * @package Inventory
 * @author Roberto Oliveros
 */
class ComparisonFilterDetail extends ComparisonDetail{
	/**
	 * Returns an array with the detail's data.
	 *
	 * The fields in the array are bar_code, manufacturer, name, um, physical, system, diference, price and total.
	 * @return array
	 */
	public function show(){
		$price = $this->_mProduct->getPrice();
		$total = $price * $this->_mDiference; 
		
		return array_merge(parent::show(), array('price' => $price,
				'total' => ($total == 0) ? '0.00' : sprintf('%+.2f', $total)));
	}
}


/**
 * Class that acts as a filter to modify a comparison information.
 * @package Inventory
 * @author Roberto Oliveros
 */
class ComparisonFilter{
	/**
	 * Filter type.
	 * Indicates that no filter will be applied to the details.
	 * @var integer
	 */
	const FILTER_NONE = 0;
	
	/**
	 * Filter type.
	 * Indicates that only the details with positive diferences will be return.
	 * @var integer
	 */
	const FILTER_POSITIVES = 1;
	
	/**
	 * Filter type.
	 * Indicates that only the details with negative diferences will be return.
	 * @var integer
	 */
	const FILTER_NEGATIVES = 2;
	
	/**
	 * Holds the filter's creation date.
	 *
	 * Date and time format: 'dd/mm/yyyy hh:mm:ss'.
	 * @var string
	 */
	private $_mFilterDateTime;
	
	/**
	 * Holds the comparison's internal id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the comparison's creation date.
	 *
	 * Date and time format: 'dd/mm/yyyy hh:mm:ss'.
	 * @var string
	 */
	private $_mDateTime;
	
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
	 * True if the details include the product price and subtotal.
	 * 
	 * @var boolean
	 */
	private $_mIncludePrices;
	
	/**
	 * Holds the sum of all the prices, only if $_mIncludePrices is true.
	 * 
	 * @var float
	 */
	private $_mPriceTotal;
	
	/**
	 * Holds bar_code, manufacturer, product, um, physical, system and diference.
	 * If $_mIncludePrices is true will also include price and subtotal.
	 * 
	 * @var array<ComparisonFilterDetail>
	 */
	private $_mDetails;
	
	/**
	 * Constructs the filter with the provided data.
	 *
	 * Call only from the database layer please.
	 * @param integer $id
	 * @param string $dateTime
	 * @param UserAccount $user
	 * @param string $reason
	 * @param boolean $general
	 * @param array<ComparisonFilterDetail> $details
	 * @param integer $physical
	 * @param integer $system
	 * @param boolean $includePrices
	 * @param float $priceTotal
	 * @throws Exception
	 */
	public function __construct($id, $dateTime, UserAccount $user, $reason, $general, $details, $physical, $system, $includePrices = false, $priceTotal = 0.0){
		$this->_mFilterDateTime = date('d/m/Y H:i:s');
		$this->_mId = $id;
		$this->_mDateTime = $dateTime;
		$this->_mUser = $user;
		$this->_mReason = $reason;
		$this->_mGeneral = (boolean)$general;
		$this->_mDetails = $details;
		$this->_mPhysicalTotal = $physical;
		$this->_mSystemTotal = $system;
		$this->_mIncludePrices = $includePrices;
		$this->_mPriceTotal = $priceTotal;
	}
	
	/**
	 * Returns the date and time of the filter creation.
	 * 
	 * @return string
	 */
	public function getFilterDateTime(){
		return $this->_mFilterDateTime;
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
	 * Returns the comparison's date and time.
	 *
	 * @return string
	 */
	public function getDateTime(){
		return $this->_mDateTime;
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
	 * @return array<ComparisonFilterDetail>
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
		$diference = $this->_mPhysicalTotal - $this->_mSystemTotal;
		return ($diference == 0) ? '0' : sprintf('%+d', $diference);
	}
	
	/**
	 * Returns true if the details include the price and subtotal fields.
	 * 
	 * @return boolean
	 */
	public function includePrices(){
		return $this->_mIncludePrices;
	}

	/**
	 * Returns an instances of a comparison with a filter or not, applied.
	 * @param integer $id
	 * @param integer $filterType
	 * @param boolean $includePrices
	 */
	static function create($id, $filterType = ComparisonFilter::FILTER_NONE, $includePrices = false){
		return ComparisonFilterDAM::getInstance($id, $filterType, $includePrices);
	}
}
?>