<?php
/**
 * Library containing the ShowCountingTemplateMenuCommand class.
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
 * For displaying the list of products and manufacturers.
 */
require_once('business/product.php');

/**
 * Command to display the counting template menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowCountingTemplateMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Inventariados', 'Selectivos');
		
		// For displaying the first blank item.
		$product_list = array(array());
		$product_list = array_merge($product_list, ProductDistinctList::getList());
		
		// For displaying the first blank item.
		$manufacturer_list = array(array());
		$manufacturer_list = array_merge($manufacturer_list, ManufacturerDistinctList::getList());
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'counting_template_menu_html.tpl', 'product_list' => $product_list,
				'manufacturer_list' => $manufacturer_list), 'site_html.tpl');
	}
}
?>