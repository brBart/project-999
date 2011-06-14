<?php
/**
 * Library containing the GetCorrelativeWarningCommand class.
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
 * For returning the warning.
 */
require_once('business/document.php');

/**
 * Returns a warning if the correlative consume limit had been reach.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCorrelativeWarningCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$id = Correlative::getDefaultCorrelativeId();
		
		if(!is_null($id)){
			$correlative = Correlative::getInstance($id);
			
			$total = ($correlative->getFinalNumber() + 1) - $correlative->getInitialNumber();
			
			if(((CORRELATIVE_CONSUME_WARNING / 100) * $total) < (($correlative->getCurrentNumber() + 1) - $correlative->getInitialNumber())){
				$msg = 'El ' . CORRELATIVE_CONSUME_WARNING . '% de consumo del correlativo se ha alcanzado. Comuniquese con el administrador.';
				Page::display(array('status' => '1', 'message' => $msg),
					'correlative_warning_xml.tpl');
				return;
			}
		}
		
		Page::display(array('status' => '0'), 'correlative_warning_xml.tpl');
	}
}
?>