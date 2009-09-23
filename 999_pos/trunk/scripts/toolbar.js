/**
 * Library with the toolbar class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function Toolbar(){}

/**
 * Sets the toolbar element div and checks the screen resolution.
 */
Toolbar.checkResolution = function(sToolbar){
	var oToolbar = document.getElementById(sToolbar);
	if(screen.width <= 1000 && screen.height <= 700)
		oToolbar.style.fontSize = '6.5px';
}