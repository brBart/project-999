<?php
/**
 * Library containing the GetObjectLastPage base class command.
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
		
		$actual_cmd = $request->getProperty('cmd');
		$link = 'index.php?cmd=' . $actual_cmd . '&page=';
		$previous_link = ($total_pages <= 1) ? '' : $link . ($tota_pages - 1);
		$next_link = '';
		
		Page::display(array('details' => $details, 'page' => $total_pages, 'total_pages' => $total_pages,
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