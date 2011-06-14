<?php
/**
 * Library containing the ShowUploadFileCommand base class.
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
 * Displays the upload file form.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowUploadFileCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		Page::display(array('key' => $request->getProperty('key')), 'upload_file_html.tpl');
	}
}
?>