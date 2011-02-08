/**
 * @fileOverview Library with the EventDelegator class.
 * @author Roberto Oliveros
 */

/**
 * @class Handles the click events on the html document.
 * @constructor
 */
function EventDelegator(){
	/**
	 * Holds the array of registered objects that needs control on the click events.
	 * @type Array
	 */
	 this._mObjects = null; 
}

/**
 * Attachs the document click event to the documentHandleClick method.
 */
EventDelegator.prototype.init = function(){
	 this._mObjects = new Array();
	 
	 var oTemp = this;
	 document.onclick = function(oEvent){
		 oTemp.documentHandleClick(oEvent);
	 }
}
 
/**
 * Handles the click event and maps from which of the registered objects the event was created.
 * @param {Event} oEvent
 */
EventDelegator.prototype.documentHandleClick = function(oEvent){
	for(var i = 0; i < this._mObjects.length; i++){
		var oObject = this._mObjects[i];
		if(!oObject.mWasClicked)
			oObject.blur();
		else
			oObject.mWasClicked = false;
	}
}

/**
 * Adds the object to the array of registered objects.
 * @param {HtmlElement} oObject
 */
EventDelegator.prototype.registerObject = function(oObject){
	this._mObjects.push(oObject);
}