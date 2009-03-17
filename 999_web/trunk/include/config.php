<?php
/**
 * Configuration file. Sets the constants with path to the proyect's libraries.
 * @author Roberto Oliveros
 */

// SITE_ROOT contains the full path to the proyect's root
define('SITE_ROOT', dirname(dirname(__FILE__)));

// Libraries
define('BUSINESS_DIR', SITE_ROOT . '/business/');
define('DATA_DIR', SITE_ROOT . '/data/');
?>