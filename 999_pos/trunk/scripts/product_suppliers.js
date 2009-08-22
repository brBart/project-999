/**
 * Library with the product suppliers class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param StateMachine oMachine
 */
function ProductSuppliers(oSession, oConsole, oRequest, sKey, oMachine){
	// Call the parent constructor.
	Details.call(this, oSession, oConsole, oRequest, sKey, oMachine);
	
	/**
	 * Holds the command name on the server.
	 * @var string
	 */
	this._mCmd = 'get_product_suppliers';
}

/**
* Inherit the Details class methods.
*/
ProductSuppliers.prototype = new Details();

/**
 * Sends the request to the server for fetching new data.
 */
ProductSuppliers.prototype.update = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}

/**
 * Executes the delete function.
 */
ProductSuppliers.prototype.deleteDetail = function(){
	this.mDeleteFunction('delete_supplier_product');
}