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
		
		Page::display(array('list' => $list, 'page' => $page, 'total_pages' => $total_pages,
				'total_items' => $total_items), $this->getTemplate());
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