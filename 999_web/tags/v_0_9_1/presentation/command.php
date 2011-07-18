<?php
/**
 * Library containing the base class for all the commands.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Defines the class signature for all the command derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class Command{
	/**
	 * Accepts no arguments.
	 */
	final public function __construct(){}
	
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	abstract public function execute(Request $request, SessionHelper $helper);
}
?>