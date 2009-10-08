<?php
/**
 * Library containing the GetBonusProductByIdCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_bonus_product.php');

/**
 * Defines functionality for obtaining a product object or displaying failure on the bonus menu.
 * @package Command
 * @author Roberto Oliveros
 */
class GetBonusProductByIdCommand extends GetBonusProductCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$id = $this->_mRequest->getProperty('id');
		if(is_numeric($id)){
			$product = Product::getInstance((int)$id);
			if(!is_null($product))
				return $product;
			else
				throw new Exception('Product no existe.');
		}
		else
			throw new Exception('C&oacute;digo inv&aacute;lido. Valor debe ser n&uacute;merico.');
	}
	
	/**
	 * Display failure in case the product does not exists.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Ofertas');
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'bonus_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'id' => $id), 'site_html.tpl');
	}
}
?>