/**
 * @fileOverview Library with the SearchProductReserve class.
 * @author Roberto Oliveros
 */

/**
 * @class SearchProductDetails derived class adapted with functionality for the reserve menu template.
 * @extends SearchProductDetails
 * @constructor
 * @param {Session} oSession
 * @param {SearchProduct} oSearchProduct
 * @param {EventDelegator} oEventDelegator
 */
function SearchProductReserve(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
}

/**
 * Inherit the Command class methods.
 */
SearchProductReserve.prototype = new SearchProductDetails();

/**
 * Redirects the html document to the product's reserve form page.
 * @param {HtmlElement} oTr
 */
SearchProductReserve.prototype.doAction = function(oTr){
	var crtBarCode = oTr.getElementsByTagName('TD')[1].id;
	this._mSession.loadHref('index.php?cmd=get_reserve_product_by_bar_code&bar_code=' + crtBarCode);
}