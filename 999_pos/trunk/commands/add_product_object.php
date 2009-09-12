<?php
/**
 * Library containing the AddProductObject base class command.
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
				$id = Product::getProductIdByBarCode($bar_code);
				if($id > 0)
					$product = Product::getInstance($id);
				else
					throw new ValidateException('Product no existe.', 'bar_code');
			}
			else
				throw new ValidateException('C&oacute;digo de barra inv&aacute;lido. Valor no puede ser ' .
						'vac&iacute;o.', 'bar_code');
				
			$obj = $helper->getObject((int)$this->_mRequest->getProperty('key'));
			$quantity = $this->_mRequest->getProperty('quantity');
			
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