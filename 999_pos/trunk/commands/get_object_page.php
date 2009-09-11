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
		
		$actual_cmd = $request->getProperty('cmd');
		$link = 'index.php?cmd=' . $actual_cmd . '&page=';
		$previous_link = ($page == 1) ? '' : $link . ($page - 1);
		$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
		
		Page::display(array('details' => $details, 'page' => $page, 'total_pages' => $total_pages,
				'total_items' => $total_items, 'first_item' => $first_item, 'last_item' => $last_item,
				'previous_link' => $previous_link, 'next_link' => $next_link), $this->getTemplate());
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	abstract protected function getTemplate();
}
?>