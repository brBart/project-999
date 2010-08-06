/**
 * @fileOverview Library with the Toolbar class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages the toolbar font size so it can be display properly on low resolution screens. Use only if
 * necessary.
 * @constructor
 */
function Toolbar(){}

/**
 * Sets the toolbar element div and checks the screen resolution.
 * @param {String} sToolbar The id of the div element.
 */
Toolbar.checkResolution = function(sToolbar){
	var oToolbar = document.getElementById(sToolbar);
	
	if(screen.width < 900) {
		oToolbar.style.fontSize = '6.5px';
	} else if(screen.width < 1200) {
		oToolbar.style.fontSize = '8px';
	}
}