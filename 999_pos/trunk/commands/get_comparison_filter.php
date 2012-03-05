<?php
/**
 * Library containing the GetComparisonFilterCommand class.
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
 * For user accounts validations.
 */
require_once('business/user_account.php');
/**
 * For the comparison object.
 */
require_once('business/inventory.php');

/**
 * Defines functionality for the getting a comparison with filter applied.
 * @package Command
 * @author Roberto Oliveros
 */
class GetComparisonFilterCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(is_null($request->getProperty('get_comparison_filter'))){
			$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'filter_type' => '2', 'content' => 'comparison_filter_request_form_html.tpl'),
					'site_html.tpl');
			return;
		}	
		
		$comparison_id = $request->getProperty('comparison_id');
		$filter_type = $request->getProperty('filter_type');
		$include_prices = (boolean)$request->getProperty('include_prices');
		
		if($include_prices && !AccessManager::isAllowed($helper->getUser(), 'comparison_filter_prices', 'read')){
			$msg = 'Insuficientes privilegios.';
			$this->displayFailure($msg, $comparison_id, $filter_type, $include_prices);
			return;
		}
		
		try{
			$comparison = ComparisonFilter::getInstance($comparison_id, $filter_type, (boolean)$include_prices);
			if(is_null($comparison))
				throw new Exception('Comparaci&oacute;n no existe.');
		} catch(Exception $e){
			$msg = $e->getMessage();
			$this->displayFailure($msg, $comparison_id, $filter_type, $include_prices);
			return;
		}
		
		$key = KeyGenerator::generateKey();
		$helper->setObject($key, $comparison);
		
		$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
		$user = $comparison->getUser();
		
		switch ($comparison->getFilterType()){
			case 0:
				$filter_name = 'Sobrantes';
				break;
			
			case 1:
				$filter_name = 'Faltantes';
				break;
				
			case 2:
				$filter_name = 'Sobrantes y Faltantes';
				break;
		}
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_comparison_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'comparison_filter_form_html.tpl', 'key' => $key,
				'filter_date_time' => $comparison->getFilterDateTime(), 'filter_name' => $filter_name,
				'include_prices' => (int)$comparison->includePrices(),
				'id' => $comparison->getId(), 'username' => $user->getUserName(),
				'date_time' => $comparison->getDateTime(), 'reason' => $comparison->getReason(),
				'general' => (int)$comparison->isGeneral()), 'site_html.tpl');
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 * @param string $startDate
	 * @param string $endDate
	 */
	protected function displayFailure($msg, $comparisonId, $filterType, $includePrices){
		$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'comparison_filter_request_form_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'comparison_id' => $comparisonId, 'filter_type' => $filterType,
				'include_price' => (int)$includePrices), 'site_html.tpl');
	}
}
?>