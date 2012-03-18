<?php
/**
 * Library containing the GetComparisonCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the comparison class.
 */
require_once('business/inventory.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');

/**
 * Displays the comparison form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetComparisonCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$comparison = Comparison::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($comparison))
			return $comparison;
		else
			throw new Exception('Comparaci&oacute;n no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
		
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'comparison_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'id' => $id), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_comparison_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
		// Build the forward link.
		$forward_link = 'index.php?cmd=get_comparison';
		$forward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'] . '&start_date=' . $this->_mRequest->getProperty('start_date') .
				'&end_date=' . $this->_mRequest->getProperty('end_date');
				
		$user = $obj->getUser();
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'comparison_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'reason' => $obj->getReason(), 'general' => (int)$obj->isGeneral(),
				'forward_link' => $forward_link), 'site_html.tpl');
	}
}
?>