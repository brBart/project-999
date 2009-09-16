<?php
/**
 * Library containing the GetObjectPage base class command.
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
 * Defines common functionality for obtaining an object's details page.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetObjectPageCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$page = $request->getProperty('page');
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		$details = DetailsPrinter::showPage($obj, $total_pages, $total_items, (int)$page);
		$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
		$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
		$page_items = count($details);
		
		$previous_page = ($page <= 1) ? '' : $page - 1;
		$next_page = ($page == $total_pages) ? '' : $page + 1;
		
		Page::display(array('details' => $details, 'page' => $page, 'total_pages' => $total_pages,
				'total_items' => $total_items, 'first_item' => $first_item, 'last_item' => $last_item,
				'previous_page' => $previous_page, 'next_page' => $next_page, 'page_items' => $page_items),
				$this->getTemplate());
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	abstract protected function getTemplate();
}
?>