<?php
/**
 * Library containing the GetBonusProductByBarCodeCommand class.
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
class GetBonusProductByBarCodeCommand extends GetBonusProductCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$bar_code = $this->_mRequest->getProperty('bar_code');
		if($bar_code != ''){
			$id = Product::getProductIdByBarCode($bar_code);
			if($id > 0)
				return Product::getInstance($id);
			else
				throw new Exception('Product no existe.');
		}
		else
			throw new Exception('C&oacute;digo de barra inv&aacute;lido. Valor no puede ser vac&iacute;o.');
	}
	
	/**
	 * Display failure in case the product does not exists.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Ofertas');
		$bar_code = $this->_mRequest->getProperty('bar_code');
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'bonus_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'bar_code' => $bar_code), 'site_html.tpl');
	}
}
?>