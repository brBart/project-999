<?php
/**
 * Library containing the PrintSalesReportCommand base class.
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
 * Defines functionality for displaying the sales report for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintSalesReportCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
		$shift = $cash_register->getShift();
		
		$company = Company::getInstance();
		
		$is_preliminary = (boolean)$request->getProperty('is_preliminary');
		
		try{
			$report = SalesReport::getInstance($cash_register, $is_preliminary);
			
			Page::display(array('company_name' => $company->getName(),
					'is_preliminary' => (int)$is_preliminary,
					'cash_register_id' => $cash_register->getId(),
					'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
					'date' => $working_day->getDate(), 'total_vouchers' => $report->getTotalVouchers(),
					'cash' => $report->getTotalCash() - $report->getTotalDeposits(),
					'total_deposits' => $report->getTotalDeposits(),
					'total' => $report->getTotal(), 'vat_total' => $report->getTotalVat(),
					'count_invoices' => count($report->getInvoices()), 'invoices' => $report->getInvoices(),
					'total_discount' => $report->getTotalDiscount(), 'total_cash' => $report->getTotalCash(),
					'count_deposits' => count($report->getDeposits()), 'deposits' => $report->getDeposits()),
					'sales_report_print_html.tpl');
		} catch(Exception $e){
			Page::display(array('notify' => '1', 'message' => $e->getMessage()),
					'sales_report_print_html.tpl');
		}
	}
}
?>