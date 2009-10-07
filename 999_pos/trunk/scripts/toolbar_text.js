/**
 * @fileOverview Library with the ToolbarText class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a text input element in the toolbar.
 * @constructor
 */
function ToolbarText(){
	/**
	 * Holds the toolbar text input element.
	 * @type HtmlElement
	 */
	this._mWidget = null;
	
	/**
	 * Holds the next input element in the toolbar.
	 * @type HtmlElement
	 */
	this._mNextWidget = null;
}

/**
 * Sets the text input element and the next one too. Also attach the focus and blur event handlers to the
 * object's handleFocus and handleBlur methods respectively, only for the IE7 browser. 
 * @param {String} sWidget The id of the input element.
 * @param {String} sNextWidget The id of the next input element.
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
 * @param {Event} oEvent
 */
ToolbarText.prototype.handleKeyDown = function(oEvent){
 	oEvent = (!oEvent) ? window.event : oEvent;
 	var code = (oEvent.keyCode) ? oEvent.keyCode :
 			((oEvent.which) ? oEvent.which : 0);
 	
 	if(code == 13)
 		StateMachine.setFocus(this._mNextWidget);
}
  
/**
 * Changes the background color of the input element.
 */
ToolbarText.prototype.handleFocus = function(){
	this._mWidget.className = 'input_focus';
}

/**
 * Restores the background color of the input element to its original color.
 */
ToolbarText.prototype.handleBlur = function(){
	this._mWidget.className = 'tb_input';
}