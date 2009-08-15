/**
 * Library with the add supplier product command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 */
function AddSupplierProductCommand(oSession, oConsole, oRequest, sKey, oProductSuppliers){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the product suppliers table element.
	 * @var ProductSuppliers
	 */
	this._mProductSuppliers = oProductSuppliers;
	
	/**
	 * Holds the command name on the server.
	 * @var string
	 */
	this._mCmd = 'add_supplier_product';
	
	/**
	 * Holds a reference to the supplier_id element.
	 * @var object
	 */
	this._mSupplierId = null;
	
	/**
	 * Holds a reference to the product_sku element.
	 * @var object
	 */
	this._mProductSku = null;
}

/**
* Inherit the Sync command class methods.
*/
AddSupplierProductCommand.prototype = new SyncCommand();

/**
 * Executes the command. Receives the supplier id and the product sku.
 * @param string sProductId
 * @param string sProductSku
 */
AddSupplierProductCommand.prototype.execute = function(oSupplierId, oProductSku){
	this._mSupplierId = oSupplierId;
	this._mProductSku = oProductSku;
	
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	str = Url.addUrlParam(str, 'supplier_id', this._mSupplierId.value);
	str = Url.addUrlParam(str, 'product_sku', this._mProductSku.value);
	this.sendRequest(str);
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
AddSupplierProductCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear all 3 possibilities.
	this._mConsole.cleanFailure('supplier_id');
	this._mConsole.cleanFailure('product_sku');
	this._mConsole.cleanFailure('product_suppliers');
	
	this._mProductSuppliers.update();
	
	// Clear elements.
	this._mSupplierId.selectedIndex = 0;
	this._mProductSku.value = '';
	this._mSupplierId.focus();
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
AddSupplierProductCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean all 3 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('supplier_id');
	this._mConsole.cleanFailure('product_sku');
	this._mConsole.cleanFailure('product_suppliers');
	this._mConsole.displayFailure(strMsg, elementId);
}