<?php
/**
 * Point of departure for the 999 operations system.
 * @author Roberto Oliveros
 */

/**
 * Indicate where all the system libraries reside.
 */
set_include_path(get_include_path() . ';c:\\xampp\\htdocs\\sistema999\\999_project\\999_web\\' .
		';c:\\xampp\\htdocs\\sistema999\\999_pos\\999_pos\\');

/**
 * Load the system variables.
 */
require_once('include/config.php');

/**
 * Load the controller library.
 */
require_once('presentation/controller.php');


/**
 * Execute the operations system.
 */
OperationsController::run();
?>