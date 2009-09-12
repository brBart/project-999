/**
 * Library with the add product entry command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param ObjectPage oDetails
 */
function AddProductEntryCommand(oSession, oConsole, oRequest, sKey, oDetails){
	// Call the parent constructor.
	AddProductObjectCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails);
	
	/**
	 * Holds a reference to the price element.
	 * @var object
	 */
	this._mPrice = null;
	
	/**
	 * Holds a reference to the expiration date element.
	 * @var object
	 */
	this._mExpirationDate = null;
}

/**
* Inherit the Sync command class methods.
*/
AddProductEntryCommand.prototype = new AddProductObjectCommand();

/**
 * Set the input elements from where the values will be read.
 * @param string sBarCode
 * @param string sQuantity
 * @param string sProductName
 * @param string sPrice
 * @param string sExpirationDate
 */
AddProductEntryCommand.prototype.init = function(sBarCode, sQuantity, sProductName, sPrice, sExpirationDate){
	// Call parent's init function first.
	AddProductObjectCommand.prototype.init.call(this, sBarCode, sQuantity, sProductName);
	
	this._mPrice = document.getElementById(sPrice);
	this._mExpirationDate = document.getElementById(sExpirationDate);
}

/**
 * Executes the command. Retreives the quantity, price, expiration_date and bar_code.
 */
AddProductEntryCommand.prototype.execute = function(sCmd){
	 if(sCmd == '')
			this._mConsole.displayError('Interno: Argumento sCmd inv&aacute;lido.');
	 else{
		 var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		 str = Url.addUrlParam(str, 'key', this._mKey);
		 str = Url.addUrlParam(str, 'quantity', this._mQuantity.value);
		 str = Url.addUrlParam(str, 'price', this._mPrice.value);
		 str = Url.addUrlParam(str, 'expiration_date', this._mExpirationDate.value);
		 str = Url.addUrlParam(str, 'bar_code', this._mBarCode.value);
		 this.sendRequest(str);
	 }
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
AddProductEntryCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear 2 possibilities.
	this._mConsole.cleanFailure('price');
	this._mConsole.cleanFailure('expiration_date');
	
	// Clear elements.
	this._mPrice.value = '';
	this._mExpirationDate.value = '';
	
	// Call parent's method.
	AddProductObjectCommand.prototype.displaySuccess.call(this, xmlDoc);
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
AddProductEntryCommand.prototype.displayFailure = function(xmlDoc, strMsg){	
	// Must clean the 2 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('price');
	this._mConsole.cleanFailure('expiration_date');
	
	// Call parent's method.
	AddProductObjectCommand.prototype.displayFailure.call(this, xmlDoc, strMsg);
}