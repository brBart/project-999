/**
 * Library with the delete detail command class.
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
function DeleteDetailCommand(oSession, oConsole, oRequest, sKey, oDetails){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the details table element.
	 * @var ProductSuppliers
	 */
	this._mDetails = oDetails;
}

/**
* Inherit the Sync command class methods.
*/
DeleteDetailCommand.prototype = new SyncCommand();

/**
 * Executes the command.
 * @param string sCmd
 */
DeleteDetailCommand.prototype.execute = function(sCmd){
	if(sCmd == '')
		this._mConsole.displayError('Interno: Argumentos sCmd inv&aacute;lido.');
	else{
		sDetailId = this._mDetails.getDetailId();
		if(sDetailId != ''){
			var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
			str = Url.addUrlParam(str, 'key', this._mKey);
			str = Url.addUrlParam(str, 'detail_id', sDetailId);
			this.sendRequest(str);
		}
	}
}