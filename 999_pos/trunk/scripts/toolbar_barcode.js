/**
 * Library with the toolbar bar code class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function ToolbarBarCode(){
	/**
	 * Holds the toolbar text input widget.
	 * @var object
	 */
	this._mWidget = null;
	
	/**
	 * Holds the input button in the toolbar.
	 * @var object
	 */
	this._mButton = null;
}

/**
 * Sets the text input widget and the button too.
 * @param string sWidget
 * @param string sButton
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
 * Checks the actual content inside the input widget.
 */
ToolbarBarCode.prototype.checkContent = function(){
	var oWidget = this._mWidget;
	
	if(oWidget.value != '')
		this.setIsReady(true);
	else
		this.setIsReady(false);
}
 
/**
 * Sets the widgets styles rules to indicate that are ready.
 * @param boolean bValue
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
  * @param Event oEvent
  */
ToolbarBarCode.prototype.handleKeyDown = function(oEvent){
 	oEvent = (!oEvent) ? window.event : oEvent;
 	var code = (oEvent.keyCode) ? oEvent.keyCode :
 			((oEvent.which) ? oEvent.which : 0);
 	
 	if(code == 13 && this._mWidget.value != '')
 		this._mButton.click();
}