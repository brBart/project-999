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
		$general = (boolean)$request->getProperty('general');
		
		if($general)
			$details = $this->getGeneralResults();
		else{
			$first = $request->getProperty('first');
			$last = $request->getProperty('last');
			
			try{
				$details = $this->getRangeResults($first, $last);
			} catch(Exception $e){
				$msg = $e->getMessage();
				$this->displayFailure($msg, $first, $last);
				return;
			}
		}
		
		Page::display(array('report_name' => 'Selectivo', 'date' => date('d/m/Y'),
				'order_by' => $this->getOrderByType(), 'details' => $details), 'counting_template_html.tpl');
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
	 * Display a failure message in case the arguments are invalid.
	 * @param string $msg
	 * @param string $first
	 * @param string $last
	 */
	abstract protected function displayFailure($msg, $first, $last);
	
	/**
	 * Returns the name of the type of order that was used on the details.
	 * @return string
	 */
	abstract protected function getOrderByType();
}
?>