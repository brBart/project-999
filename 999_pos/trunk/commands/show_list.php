<?php
/**
 * Library containing the show list base class command.
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
 * Defines common functionality for the show list derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$page = $request->getProperty('page');
		$list = $this->getList($total_pages, $total_items, $page);
		
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		$args = array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_manufacturer_menu', 'back_trace' => $back_trace,
					'second_menu' => 'blank.tpl', 'content' => 'manufacturer_list_html.tpl');
		
		if($total_items > 0){
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
			
			$link = 'index.php?cmd=' . $request->getProperty('cmd');
			$previous_link = ($page == 1) ? '' : $link . '&page=' . $page - 1;
			$next_link = ($page == $total_pages) ? '' : $link . '&page=' . $page + 1;
			
			Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_manufacturer_menu', 'back_trace' => $back_trace,
					'second_menu' => 'blank.tpl', 'content' => 'manufacturer_list_html.tpl',
					'total_items' => '0'), 'site_html.tpl');
		}
		else
			Page::display(array_merge($args, array('total_items' => '0')), 'site_html.tpl');
	}
	
	/**
	 * Returns a list with information.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	abstract protected function getList(&$totalPages, &$totalItems, $page);
	
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	abstract protected function getTemplate();
}
?>