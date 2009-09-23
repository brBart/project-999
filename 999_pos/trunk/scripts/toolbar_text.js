/**
 * Library with the toolbar text class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function ToolbarText(){
	/**
	 * Holds the toolbar text input widget.
	 * @var object
	 */
	this._mWidget = null;
	
	/**
	 * Holds the next input widget in the toolbar.
	 * @var object
	 */
	this._mNextWidget = null;
}

/**
 * Sets the text input widget and the next one too.
 * @param string sWidget
 * @param string sNextWidget
 */
ToolbarText.prototype.init = function(sWidget, sNextWidget){
	this._mWidget = document.getElementById(sWidget);
	this._mNextWidget = document.getElementById(sNextWidget); 
	
	var oTemp = this;
	this._mWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	
	// For IE7 lack of :focus pseudo class support.
	if(navigator.userAgent.indexOf('MSIE 7') > -1){
		this._mWidget.onfocus = function(){
			oTemp.handleFocus();
		}
		
		this._mWidget.onblur = function(){
			oTemp.handleBlur();
		}
	}
}
 
/**
  * Handles the key down press event.
  * @param Event oEvent
  */
ToolbarText.prototype.handleKeyDown = function(oEvent){
 	oEvent = (!oEvent) ? window.event : oEvent;
 	var code = (oEvent.keyCode) ? oEvent.keyCode :
 			((oEvent.which) ? oEvent.which : 0);
 	
 	if(code == 13)
 		StateMachine.setFocus(this._mNextWidget);
}
  
/**
  * Handles the key down press event.
  * @param Event oEvent
  */
ToolbarText.prototype.handleFocus = function(){
	this._mWidget.className = 'input_focus';
}

/**
* Handles the key down press event.
* @param Event oEvent
*/
ToolbarText.prototype.handleBlur = function(){
	this._mWidget.className = 'tb_input';
}