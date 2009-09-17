/**
 * Library with the object page class.
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
function ObjectPage(oSession, oConsole, oRequest, sKey, oMachine){
	// Call the parent constructor.
	Details.call(this, oSession, oConsole, oRequest, sKey, oMachine);
}

/**
* Inherit the Details class methods.
*/
ObjectPage.prototype = new Details();

/**
 * Sends the request to the server for fetching the last page.
 */
ObjectPage.prototype.getLastPage = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this.getLastPageCmd());
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}

/**
* Sends the request to the server for fetching the desired page.
* @param integer iPage
*/
ObjectPage.prototype.getPage = function(iPage){
	if(iPage < 0)
		this._mConsole.displayError('Interno: Argumento iPage inv&aacute;lido.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this.getPageCmd());
		str = Url.addUrlParam(str, 'page', iPage);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
 * Updates the actual page.
 */
ObjectPage.prototype.updatePage = function(){
	this.getPage(this._mPage);
}

/**
 * Returns the name of the last page command on the server.
 * @return string
 */
ObjectPage.prototype.getLastPageCmd = function(){
	return 0;
}

/**
* Returns the name of the page command on the server.
* @return string
*/
ObjectPage.prototype.getPageCmd = function(){
	return 0;
}