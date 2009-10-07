/**
 * @fileOverview Library with the SearchProductToolbar class.
 * @author Roberto Oliveros
 */

/**
 * @class SearchProductDetails derived class adapted with functionality for being on a toolbar.
 * @extends SearchProductDetails
 * @constructor
 * @param {Session} oSession
 * @param {SearchProduct} oSearchProduct
 * @param {EventDelegator} oEventDelegator
 */
function SearchProductToolbar(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
	
	/**
	 * Holds the input element which displays the product's bar code.
	 * @type HtmlElement
	 */
	this._mBarCode = null;
}

/**
 * Inherit the Command class methods.
 */
SearchProductToolbar.prototype = new SearchProductDetails();


/**
 * Sets the text input element from where the user will enter the search query and the one which will display the bar code.
 * @param {String} sTxtWidget The id of the input element.
 * @param {String} sDetailsObj The variable name which holds the instance of this class.
 * @param {String} sBarCode The id of the input element for displaying the bar code.
 */
SearchProductToolbar.prototype.init = function(sTxtWidget, sDetailsObj, sBarCode){
	// Call the parents method.
	SearchProductDetails.prototype.init.call(this, sTxtWidget, sDetailsObj);
	
	this._mBarCode = document.getElementById(sBarCode);
}

/**
 * Displays the bar code and sets focus on the input element.
 * @param {HtmlElement} oTr
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