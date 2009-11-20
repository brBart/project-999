<?php
/**
 * Library containing the PrintCountingTemplateCommand class.
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
 * Defines common functionality to display a counting template for printing.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class PrintCountingTemplateCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$first = $request->getProperty('first');
		$last = $request->getProperty('last');
		
		try{
			$details = $this->getRangeResults($first, $last);
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('date' => date('d/m/Y'), 'notify' => '1', 'type' => 'error',
					'message' => $msg, 'total_items' => '0'), 'counting_template_print_html.tpl');
			return;
		}
		
		Page::display(array('date' => date('d/m/Y'), 'order_by' => $this->getOrderByType(),
				'details' => $details, 'total_items' => count($details)), 'counting_template_print_html.tpl');
	}
	
	/**
	 * Return an array with all the products' data for displaying on the template as details.
	 * @return array
	 */
	abstract protected function getGeneralResults();
	
	/**
	 * Return an array consisting on a range of products' data for displaying on the template as details.
	 * @param string $first
	 * @param string $last
	 * @return array
	 */
	abstract protected function getRangeResults($first, $last);
	
	/**
	 * Returns the name of the type of order that was used on the details.
	 * @return string
	 */
	abstract protected function getOrderByType();
}
?>