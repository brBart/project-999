/**
 * Library with the save object command class.
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
function SaveObjectCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SaveCommand.call(this, oSession, oConsole, oRequest, sKey, 'save_object');
}

/**
* Inherit the Async command class methods.
*/
SaveObjectCommand.prototype = new SaveCommand();

/**
 * Executes the command.
 */
SaveObjectCommand.prototype.execute = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}