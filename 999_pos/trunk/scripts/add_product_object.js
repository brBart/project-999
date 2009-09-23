/**
 * Library with the add product object command base class.
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
function AddProductObjectCommand(oSession, oConsole, oRequest, sKey, oDetails){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the object which handles the details table element.
	 * @var ObjectPage
	 */
	this._mDetails = oDetails;
	
	/**
	 * Holds a reference to the bar code element.
	 * @var object
	 */
	this._mBarCode = null;
	
	/**
	 * Holds a reference to the quantity element.
	 * @var object
	 */
	this._mQuantity = null;
	
	/**
	 * Holds a reference to the product name element.
	 * @var object
	 */
	this._mProductName = null;
}

/**
* Inherit the Sync command class methods.
*/
AddProductObjectCommand.prototype = new SyncCommand();

/**
 * Set the input elements from where the values will be read.
 * @param string sBarCode
 * @param string sQuantity
 * @param string sProductName
 */
AddProductObjectCommand.prototype.init = function(sBarCode, sQuantity, sProductName){
	this._mBarCode = document.getElementById(sBarCode);
	this._mQuantity = document.getElementById(sQuantity);
	this._mProductName = document.getElementById(sProductName);
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
AddProductObjectCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear all 2 possibilities.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('bar_code');
	
	this._mDetails.getLastPage();
	
	// Clear elements.
	this._mQuantity.value = '1';
	this._mBarCode.value = '';
	this._mProductName.value = '';
	StateMachine.setFocus(this._mQuantity);
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
AddProductObjectCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean the 2 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('bar_code');
	this._mConsole.displayFailure(strMsg, elementId);
}