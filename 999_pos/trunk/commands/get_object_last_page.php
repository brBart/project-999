<?php
/**
 * Library containing the GetObjectLastPageCommand base class.
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
 * For displaying the object details.
 */
require_once('business/itemized.php');

/**
 * Defines common functionality for obtaining an object's details last page.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetObjectLastPageCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		$details = DetailsPrinter::showLastPage($obj, $total_pages, $total_items);
		$first_item = ($total_pages > 0) ? (($total_pages - 1) * ITEMS_PER_PAGE) + 1 : 0;
		$last_item = $total_items;
		$page_items = count($details);
		
		$previous_page = ($total_pages <= 1) ? '' : $total_pages - 1;
		$next_page = '';
		
		$params = array('details' => $details, 'page' => $total_pages, 'total_pages' => $total_pages,
				'total_items' => $total_items, 'first_item' => $first_item, 'last_item' => $last_item,
				'previous_page' => $previous_page, 'next_page' => $next_page, 'page_items' => $page_items);
		
		$params = array_merge($params, $this->getObjectParams($obj));
		
		Page::display($params, $this->getTemplate());
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	abstract protected function getTemplate();
	
	/**
	 * Returns the params to display for the object.
	 * @param variant $obj
	 */
	abstract protected function getObjectParams($obj);
}
?>