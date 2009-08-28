/**
 * Library with the SearchProduct command base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function SearchProductCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the text input.
	 * @var object
	 */
	this._mTxtWidget = null;
	
	/**
	 * Holds the settimeout id.
	 * @var integer
	 */
	this._mTimeoutId = 0;
}

/**
* Inherit the Command class methods.
*/
SearchProductCommand.prototype = new Command();

/**
 * Sets the text input widget from where the user will input the search query.
 * @param object oTxtWidget
 */
SearchProductCommand.prototype.init = function(oTxtWidget){
	oTemp = this;
	oTxtWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	oTxtWidget.onfocus = function(){
		oTemp.startListening();
	}
	oTxtWidget.onblur = function(){
		oTemp.stopListening();
	}
	this._mTxtWidget = oTxtWidget;
}

/**
 * Start the timeout cycle for the checkForChanges method.
 */
SearchProductCommand.prototype.startListening = function(){
	oTemp = this;
	this._mTimeoutId = setTimeout('oTemp.checkForChanges()', 500);
}
 
/**
 * Clears the timeout cycle.
 */
SearchProductCommand.prototype.stopListening = function(){
	clearTimeout(this._mTimeoutId);
}


SearchProductCommand.prototype.checkForChanges  = function(){
	// retrieve the keyword object 
	var keyword = this._mTxtWidget.value;
	// check to see if the keyword is empty
	if(keyword == "")
	{
	  // hide the suggestions
	  this.hideSuggestions();
	  // reset the keywords 
	  userKeyword="";
	  httpRequestKeyword="";
	}
	// set the timer for a new check 
	oTemp = this;
	this._mTimeoutId = setTimeout('oTemp.checkForChanges()', 500);
	// check to see if there are any changes
	if((userKeyword != keyword) && 
	   (autocompletedKeyword != keyword) && 
	   (!isKeyUpDownPressed))
	  // update the suggestions
	  this.getSuggestions(keyword);
}