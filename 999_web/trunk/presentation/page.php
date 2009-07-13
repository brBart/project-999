<?php
/**
 * Library with the necessary classes for displaying a html or xml page.
 * @package Page
 * @author Roberto Oliveros
 */

/**
 * Reference to the Smarty library
 */
require_once(SMARTY_DIR . 'Smarty.class.php');

/**
 * Extends Smarty class to displays smarty files.
 * @package Page
 * @author Roberto Oliveros
 */
class SmartyPage extends Smarty{
	/**
	 * Load the the Smarty constructor and reset the directories variables.
	 */
	public function __construct(){
		parent::Smarty();
		
		$this->template_dir = TEMPLATE_DIR;
		$this->compile_dir = COMPILE_DIR;
		$this->config_dir = CONFIG_DIR;
	}
}


/**
 * Class for displaying the final html or xml result.
 * @package Page
 * @author Roberto Oliveros
 */
class Page{
	/**
	 * Displays the result using the provided template with the provided arguments.
	 * 
	 * @param array $args
	 * @param string $template
	 */
	static public function display($args, $template){
		$smarty_page = new SmartyPage();
		
		foreach($args as $key => $value)
			$smarty_page->assign($key, $value);
			
		$smarty_page->display($template);
	}
}
?>