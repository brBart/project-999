<?php
/**
 * Library containing the AddProductObjectCommand base class.
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
 * Defines common functionality for adding a product to an object.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class AddProductObjectCommand extends Command{
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
		$bar_code = $this->_mRequest->getProperty('bar_code');
		
		try{
			if($bar_code != ''){
				$id = Product::getProductIdByBarCode($bar_code, true);
				if($id > 0){
					$product = Product::getInstance($id);
					
					if($product->isDeactivated())
						throw new ValidateException('Producto esta desactivado.', 'bar_code');
				}
				else
					throw new ValidateException('Producto no existe.', 'bar_code');
			}
			else
				throw new ValidateException('C&oacute;digo de barra inv&aacute;lido. Valor no puede ser ' .
						'vac&iacute;o.', 'bar_code');
				
			$quantity = $this->_mRequest->getProperty('quantity');
			if(!is_numeric($quantity) || $quantity < 1)
				throw new ValidateException('Cantidad inv&aacute;lida. Valor deber ser mayor que cero.',
						'quantity');
			
			$obj = $helper->getObject((int)$this->_mRequest->getProperty('key'));
			$this->addProduct($obj, $product, $quantity);
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		Page::display(array(), 'success_xml.tpl');
	}
	
	/**
	 * Adds the product to the desired object.
	 * 
	 * @param variant $obj
	 * @param Product $product
	 * @param integer $quantity
	 */
	abstract protected function addProduct($obj, Product $product, $quantity);
}
?>