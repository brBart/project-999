<?php
/**
 * Library with utility classes for making an inventory.
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
	 */
	public function __construct(Product $product, $physical, $system){
		try{
			Persist::validateObjectFromDatabase($product);
			Number::validateInteger($physical);
			Number::validateInteger($system);
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
	 * Returns an array with the details data.
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
?>