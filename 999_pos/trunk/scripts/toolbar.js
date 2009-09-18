/**
 * Library with the toolbar class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function Toolbar(){
	/**
	 * Holds the toolbar element div.
	 * @var object
	 */
	this._mToolbar = null;
}

/**
 * Sets the toolbar element div and checks the screen resolution.
 */
Toolbar.prototype.init = function(sToolbar){
	this._mToolbar = document.getElementById(sToolbar);
	if(screen.width <= 1000 && screen.height <= 700)
		this._mToolbar.style.fontSize = '6.5px';
}