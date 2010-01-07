/**
 * @fileOverview Library with the GetProductBalanceCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Gets the product balance from the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function GetProductBalanceCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'get_product_balance';
	
	/**
	 * Holds the quantity span element.
	 * @type HtmlElement
	 */
	this._mQuantity = null;
	
	/**
	 * Holds the available span element.
	 * @type HtmlElement
	 */
	this._mAvailable = null;
}

/**
 * Inherit the Sync command class methods.
 */
GetProductBalanceCommand.prototype = new SyncCommand();
 
/** 
 * Sets the quantity and available span elements.
 * @param {String} sQuantity The id of the quantity span element.
 * @param {String} sAvailable The id of the available span element.
 */
GetProductBalanceCommand.prototype.init = function(sQuantity, sAvailable){
	this._mQuantity = document.getElementById(sQuantity);
	this._mAvailable = document.getElementById(sAvailable);
}
 
/**
 * Executes the command.
 */
GetProductBalanceCommand.prototype.execute = function(){
	 var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	 str = Url.addUrlParam(str, 'key', this._mKey);
	 this.sendRequest(str);
}

/**
 * Displays the product balance data.
 * @param {DocumentElement} xmlDoc
 */
GetProductBalanceCommand.prototype.displaySuccess = function(xmlDoc){
	 this._mQuantity.innerHTML = xmlDoc.getElementsByTagName('quantity')[0].firstChild.data;
	 this._mAvailable.innerHTML = xmlDoc.getElementsByTagName('available')[0].firstChild.data;
}