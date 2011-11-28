/**
 * @fileOverview Library with the SearchProductMenu class.
 * @author Roberto Oliveros
 */

/**
 * @class SearchProductDetails derived class adapted with functionality for the menu template.
 * @extends SearchProductDetails
 * @constructor
 * @param {Session} oSession
 * @param {SearchProduct} oSearchProduct
 * @param {EventDelegator} oEventDelegator
 */
function SearchProductMenu(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
}

/**
 * Inherit the Command class methods.
 */
SearchProductMenu.prototype = new SearchProductDetails();

/**
 * Redirects the html document to the product's form page.
 * @param {HtmlElement} oTr
 */
SearchProductMenu.prototype.doAction = function(oTr){
	var crtBarCode = oTr.getElementsByTagName('TD')[1].id;
	this._mSession.loadHref('index.php?cmd=get_product_by_bar_code&bar_code='
			+ encodeURIComponent(crtBarCode) + '&include_deactivated='
			+ (this._mSearchProduct.includeDeactivated ? '1' : '0'));
}