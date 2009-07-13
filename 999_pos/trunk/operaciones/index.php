<?php
/**
 * Point of departure for the 999 operations system.
 * @package 999_pos
 * @author Roberto Oliveros
 */

/**
 * Web site path.
 */
define('SITE_ROOT', dirname(dirname(__FILE__)));

/**
 * Load the system configuration.
 */
require_once(SITE_ROOT . '/include/config.php');

/**
 * Load the controller library.
 */
require_once('presentation/controller.php');


/**
 * Execute the operations system.
 */
OperationsController::run();
?>