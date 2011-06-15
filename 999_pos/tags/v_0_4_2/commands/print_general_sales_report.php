<?php
/**
 * Library containing the PrintGeneralSalesReportCommand base class.
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
 * For displaying the company's name.
 */
require_once('business/various.php');
/**
 * For obtaining the report.
 */
require_once('business/cash.php');

/**
 * Defines functionality for displaying the general sales report for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintGeneralSalesReportCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		
		$company = Company::getInstance();
		
		$is_preliminary = (boolean)$request->getProperty('is_preliminary');
		
		try{
			$report = GeneralSalesReport::getInstance($working_day, $is_preliminary);
			
			Page::display(array('company_name' => $company->getName(),
					'is_preliminary' => (int)$is_preliminary,
					'date' => $working_day->getDate(), 'total' => $report->getTotal(),
					'cash_registers' => $report->getCashRegisters(),
					'count_cash_registers' => count($report->getCashRegisters())),
					'general_sales_report_print_html.tpl');
		} catch(Exception $e){
			Page::display(array('notify' => '1', 'message' => $e->getMessage()),
					'general_sales_report_print_html.tpl');
		}
	}
}
?>