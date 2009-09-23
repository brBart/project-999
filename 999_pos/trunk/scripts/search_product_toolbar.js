/**
 * Library with the SearchProductToolbar class, is the SearchProductDetails but with functionality to be
 * use on the document's toolbar.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param SearchProduct oSearchProduct
 * @param EventDelegator oEventDelegator
 */
function SearchProductToolbar(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
	
	/**
	 * Holds the input elements which displays the product's bar code.
	 * @var object
	 */
	this._mBarCode = null;
}

/**
* Inherit the Command class methods.
*/
SearchProductToolbar.prototype = new SearchProductDetails();


/**
* Sets the text input widget from where the user will input the search query and the one which will display
* the bar code.
* @param string sTxtWidget
* @param string sDetailsObj
*/
SearchProductToolbar.prototype.init = function(sTxtWidget, sDetailsObj, sBarCode){
	// Call the parents method.
	SearchProductDetails.prototype.init.call(this, sTxtWidget, sDetailsObj);
	
	this._mBarCode = document.getElementById(sBarCode);
}

/*
 * Took the respective action.
 * @param Object oTr
 */
SearchProductToolbar.prototype.doAction = function(oTr){
	 // retrieve the name for the current product
	 var oTd = oTr.getElementsByTagName('TD')[0];
	 var crtName = oTd.id.substring(oTd.id.indexOf('-') + 1);
	 // update the keyword's value
	 this._mTxtWidget.value = unescape(crtName);
	 
	 var crtBarCode = oTr.getElementsByTagName('TD')[1].id;
	 this._mBarCode.value = crtBarCode;
	 
	 StateMachine.setFocus(this._mBarCode);
	 this.stopListening();
}