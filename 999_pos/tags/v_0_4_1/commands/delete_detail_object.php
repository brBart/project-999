<?php
/**
 * Library containing the DeleteDetailObjectCommand base class.
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
 * Defines common functionality for deleting an object's detail.
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
			$detail = $this->getDetail($detail_id, $obj);
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
			$this->deleteDetail($detail, $obj);
			Page::display(array(), 'success_xml.tpl');
		}
	}
	
	/**
	 * Returns the detail of the object.
	 * 
	 * @param string $detailId
	 * @param variant $obj
	 * @return variant
	 */
	abstract protected function getDetail($detailId, $obj);
	
	/**
	 * Deletes the detail from the object.
	 * 
	 * @param variant $detail
	 * @param variant $obj
	 */
	abstract protected function deleteDetail($detail, $obj);
}
?>