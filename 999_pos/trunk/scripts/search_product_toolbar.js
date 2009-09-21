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
*/
SearchProductToolbar.prototype.init = function(sTxtWidget, sBarCode){
	// Call the parents method.
	SearchProductDetails.prototype.init.call(this, sTxtWidget)
	
	this._mBarCode = document.getElementById(sBarCode);
}

/*
 * Took the respective action.
 * @param string sBarCode
 */
SearchProductToolbar.prototype.doAction = function(sBarCode){
	this._mBarCode.value = sBarCode;
	this._mBarCode.focus();
	this.stopListening();
}