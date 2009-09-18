/**
 * Library with the get amount command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function GetAmountCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	GetPropertyCommand.call(this, oSession, oConsole, oRequest, sKey);
	
	/**
	 * Holds the object to display to amount value.
	 */
	this._mWidgetName = '';
}

/**
* Inherit the GetPropertyCommand class methods.
*/
GetAmountCommand.prototype = new GetPropertyCommand();

/**
 * Sets the element to display the amount value.
 * @param string sCmd
 * @param string sWidget
 */
GetAmountCommand.prototype.init = function(sCmd, sWidget){
	//Call the parent init function first.
	GetPropertyCommand.prototype.init.call(this, sCmd);
	
	this._mWidgetName = sWidget;
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
GetAmountCommand.prototype.displaySuccess = function(xmlDoc){
	var oWidget = document.getElementById(this._mWidgetName);
	oWidget.innerHTML = xmlDoc.getElementsByTagName('value')[0].firstChild.data;
}