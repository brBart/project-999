<?php
/**
 * Library containing the GetCountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the count class.
 */
require_once('business/inventory.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');

/**
 * Displays the count form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCountCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$count = Count::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($count))
			return $count;
		else
			throw new Exception('Conteo no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Inventariados', 'Conteos');
		
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Conteo',
				'create_link' => 'index.php?cmd=create_count', 'get_link' => 'index.php?cmd=get_count',
				'search_link' => 'index.php?cmd=search_count&page=1', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'id' => $id), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Inventariados', 'Conteos');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_count_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_count';
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'] . '&start_date=' . $this->_mRequest->getProperty('start_date') .
				'&end_date=' . $this->_mRequest->getProperty('end_date');;
				
		$user = $obj->getUser();
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'count_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'reason' => $obj->getReason(), 'foward_link' => $foward_link), 'site_html.tpl');
	}
}
?>