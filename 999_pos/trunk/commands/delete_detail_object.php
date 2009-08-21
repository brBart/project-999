<?php
/**
 * Library containing the DeleteDetailObject base class command.
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
 * Defines common functionality for deleting a object's detail.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class DeleteDetailObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $helper->getObject((int)$request->getProperty('key'));
		$detail_id = $request->getProperty('detail_id');
		
		try{
			$detail = $this->getDetail($detail_id);
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		if(is_null($detail)){
			$msg = 'Detalle no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
		else{
			$this->deleteDetail($detail);
			Page::display(array(), 'success_xml.tpl');
		}	
	}
}
?>