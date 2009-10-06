/**
 * @fileOverview Library with the AddSupplierProductCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Adds a supplier to a product on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHtttpRequest} oRequest
 * @param {String} sKey
 * @param {ProductSuppliers} oProductSuppliers
 */
function AddSupplierProductCommand(oSession, oConsole, oRequest, sKey, oProductSuppliers){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the product suppliers object.
	 * @type ProductSuppliers
	 */
	this._mProductSuppliers = oProductSuppliers;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'add_supplier_product';
	
	/**
	 * Holds a reference to the supplier id input element.
	 * @type HtmlElement
	 */
	this._mSupplierId = null;
	
	/**
	 * Holds a reference to the product sku input element.
	 * @type HtmlElement
	 */
	this._mProductSku = null;
}

/**
 * Inherit the Sync command class methods.
 */
AddSupplierProductCommand.prototype = new SyncCommand();

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sSupplierId The id of the supplier id input element.
 * @param {String} sProductSku The id of the product sku input element.
 */
AddSupplierProductCommand.prototype.init = function(sSupplierId, sProductSku){
	this._mSupplierId = document.getElementById(sSupplierId);
	this._mProductSku = document.getElementById(sProductSku);
}

/**
 * Executes the command. Sends the supplier id and the product sku.
 */
AddSupplierProductCommand.prototype.execute = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	str = Url.addUrlParam(str, 'supplier_id', this._mSupplierId.value);
	str = Url.addUrlParam(str, 'product_sku', this._mProductSku.value);
	this.sendRequest(str);
}

/**
 * Method for displaying success.
 * @param {DocumentElement} xmlDoc
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
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
AddSupplierProductCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean all 3 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('supplier_id');
	this._mConsole.cleanFailure('product_sku');
	this._mConsole.cleanFailure('product_suppliers');
	this._mConsole.displayFailure(strMsg, elementId);
}