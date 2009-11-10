<?php
/**
 * Library containing the ParseFileCommand base class.
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
 * For parsing the file.
 */
require_once('business/inventory.php');

/**
 * Parse a file containing a count's details.
 * @package Command
 * @author Roberto Oliveros
 */
class ParseFileCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$count = $helper->getObject((int)$request->getProperty('key'));
		$url = $_FILES['count_file']['tmp_name'];
		
		try{
			Parser::parseFile($count, $url);
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('key' => $request->getProperty('key'), 'notify' => '1', 'type' => 'error',
					'message' => $msg), 'upload_file_html.tpl');
			return;
		}
		
		Page::display(array('uploaded' => '1'), 'upload_file_html.tpl');
	}
}
?>