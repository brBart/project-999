<?php
/**
 * Library containing the ShowProductCommand base class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');
/**
 * For obtaining the product info.
 */
require_once('business/product.php');

/**
 * Defines common functionality for getting a product and show it.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowProductCommand extends Command{
	/**
	 * Holds the request object.
	 * @var Request
	 */
	protected $_mRequest;
	
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$this->_mRequest = $request;
		
		try{
			$product = $this->getProduct();
			
			$manufacturer = $product->getManufacturer();
			$um = $product->getUnitOfMeasure();
			
			Page::display(array('id' => $product->getId(), 'name' => $product->getName(),
					'bar_code' => $product->getBarCode(), 'description' => $product->getDescription(),
					'manufacturer' => $manufacturer->getName(), 'um' => $um->getName(), 'price' => $product->getPrice(),
					'deactivated' => (int)$product->isDeactivated(), 'quantity' => Inventory::getQuantity($product),
					'available' => Inventory::getAvailable($product), 'suppliers' => $product->showProductSuppliers(),
					'lots' => Inventory::showLots($product, $x, $y), 'reserves' => ReserveList::getList($product)),
					'product_info_html.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('type' => 'error', 'message' => $msg, 'notify' => '1'),
					'product_info_html.tpl');
		}
	}
	
	/**
	 * Gets the desired product.
	 * @return variant
	 */
	abstract protected function getProduct();
}
?>