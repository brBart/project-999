/**
 * @fileOverview Library with the ToolbarBarCode class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a bar code input element in a toolbar.
 * @constructor
 */
function ToolbarBarCode(){
	/**
	 * Holds the bar code text input element.
	 * @type HtmlElement
	 */
	this._mWidget = null;
	
	/**
	 * Holds the input button in the toolbar.
	 * @type HtmlElement
	 */
	this._mButton = null;
}

/**
 * Sets the text input element and the button too.
 * @param {String} sWidget The id of the input element.
 * @param {String} sButton The id of the input button element.
 */
ToolbarBarCode.prototype.init = function(sWidget, sButton){
	this._mWidget = document.getElementById(sWidget);
	this._mButton = document.getElementById(sButton); 
	
	var oTemp = this;
	this._mWidget.onfocus = function(){
		oTemp.checkContent();
	}
	this._mWidget.onblur = function(){
		oTemp.setIsReady(false);
	}
	this._mWidget.onkeyup = function(){
		oTemp.checkContent();
	}
	this._mWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
}
 
/**
 * Checks the actual content inside the bar code input element.
 */
ToolbarBarCode.prototype.checkContent = function(){
	var oWidget = this._mWidget;
	
	if(oWidget.value != '')
		this.setIsReady(true);
	else
		this.setIsReady(false);
}
 
/**
 * Sets the input elements' styles rules to indicate that are ready.
 * @param {Boolean} bValue
 */
ToolbarBarCode.prototype.setIsReady = function(bValue){
	if(bValue){
		this._mWidget.className = 'input_focus';
		this._mButton.className = 'button_focus';
	}
	else{
		this._mWidget.className = '';
		this._mButton.className = '';
	}
}
 
/**
 * Handles the key down press event.
 * @param {Event} oEvent
 */
ToolbarBarCode.prototype.handleKeyDown = function(oEvent){
 	oEvent = (!oEvent) ? window.event : oEvent;
 	var code = (oEvent.keyCode) ? oEvent.keyCode :
 			((oEvent.which) ? oEvent.which : 0);
 	
 	if(code == 13 && this._mWidget.value != '')
 		this._mButton.click();
}