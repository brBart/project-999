<?php
/**
 * Library containing the CreateBonusCommand class.
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
 * Defines functionality for creating a product's bonus.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateBonusCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if(!AccessManager::isAllowed($user, 'bonus', 'write')){
			$msg = 'Usuario no cuenta con los suficientes privilegios.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		try{
			$product = $helper->getObject((int)$request->getProperty('key'));
			$quantity = $request->getProperty('quantity');
			$percentage = $request->getProperty('percentage');
			$expiration_date = $request->getProperty('expiration_date');
			
			$bonus = new Bonus($product, $quantity, $percentage, $expiration_date);
			$bonus->save();
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('element_id' => $element_id, 'message' => $msg, 'success' => '0'),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		Page::display(array(), 'success_xml.tpl');
	}
}
?>