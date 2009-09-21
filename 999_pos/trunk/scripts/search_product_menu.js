/**
 * Library with the SearchProductMenu class, is the SearchProductDetails but with functionality to be use
 * on the menu template.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param SearchProduct oSearchProduct
 * @param EventDelegator oEventDelegator
 */
function SearchProductMenu(oSession, oSearchProduct, oEventDelegator){
	// Call the parent constructor.
	SearchProductDetails.call(this, oSession, oSearchProduct, oEventDelegator);
}

/**
* Inherit the Command class methods.
*/
SearchProductMenu.prototype = new SearchProductDetails();

/*
 * Took the respective action.
 * @param string sBarCode
 */
SearchProductMenu.prototype.doAction = function(sBarCode){
	this._mSession.loadHref('index.php?cmd=get_product_by_bar_code&bar_code=' + sBarCode);
}