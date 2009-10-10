/**
 * @fileOverview Library with the SearchProductBonus class.
 * @author Roberto Oliveros
 */

/**
 * @class SearchProductDetails derived class adapted with functionality for the bonus menu template.
 * @extends SearchProductDetails
 * @constructor
 * @param {Session} oSession
 * @param {SearchProduct} oSearchProduct
 * @param {EventDelegator} oEventDelegator
 */
function SearchProductBonus(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
}

/**
 * Inherit the Command class methods.
 */
SearchProductBonus.prototype = new SearchProductDetails();

/**
 * Redirects the html document to the product's bonus form page.
 * @param {HtmlElement} oTr
 */
SearchProductBonus.prototype.doAction = function(oTr){
	var crtBarCode = oTr.getElementsByTagName('TD')[1].id;
	this._mSession.loadHref('index.php?cmd=get_bonus_product_by_bar_code&bar_code=' + crtBarCode);
}