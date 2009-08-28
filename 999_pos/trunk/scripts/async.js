/**
 * Library with the Async command base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function AsyncCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the settimeout id.
	 * @var integer
	 */
	this._mTimeoutId = 0;
}

/**
* Inherit the Command class methods.
*/
AsyncCommand.prototype = new Command();

/**
 * Send the request to the server.
 * @param string sUrlParams
 */
AsyncCommand.prototype.sendRequest = function(sUrlParams){
	// Clear any timeout that was already waiting.
	if(this._mTimeoutId != 0){
		clearTimeout(this._mTimeoutId);
		this._mTimeoutId = 0;
	}
	
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0){
		sUrlParams = Url.addUrlParam(sUrlParams, 'type', 'xml');
		this._mRequest.open('GET', sUrlParams, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oTemp = this
		this._mRequest.onreadystatechange = function(){
			oTemp.handleRequestStateChange();
		}
		
		this._mRequest.send(null);
	}
	else{
		// If busy try a little later.
		oTemp = this;
		this._mTimeoutId = setTimeout('oTemp.sendRequest(' + sUrlParams + ')', 500);
	}
}