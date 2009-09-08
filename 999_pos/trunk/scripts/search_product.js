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
function SearchProduct(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the name of the command on the server.
	 * @var string
	 */
	this._mCmd = 'search_product';
	
	/**
	 * Holds the text input.
	 * @var object
	 */
	this._mTxtWidget = null;
	
	/**
	 * Holds the div containing the suggest table.
	 * @var object
	 */
	this._mScrollDiv = null;
	
	/**
	 * Holds the settimeout id.
	 * @var integer
	 */
	this._mTimeoutId = 0;
	
	/**
	 * Holds the setinterval id.
	 * @var integer
	 */
	this._mIntervalId = 0;
	
	/**
	 * Holds the last keyword for which suggests have been requested.
	 * @var string
	 */
	this.mUserKeyword = '';
	
	/**
	 * Holds the keyword for which an HTTP request has been initiated.
	 * @var string
	 */
	this._mHttpRequestKeyword = '';
	
	/**
	 * Cache containing the retrieved suggestions for different keywords.
	 * @var object
	 */
	this._mCache = null;
	
	/**
	 * Flag that indicates if the up or down keys were pressed the last time the keydown event occurred.
	 * @var boolean
	 */
	this._mIsKeyUpDownPressed = false;
	
	/**
	 * The last suggestion that has been used for autocompleting the keyword.
	 * @var string
	 */
	this._mAutocompletedKeyword = '';
	
	/**
	 * Number of suggestions received as results for the keyword.
	 * @var integer
	 */
	this._mSuggestions = 0;
	
	/**
	 * The currently selected suggestion (by arrow keys or mouse).
	 * @var integer
	 */
	this._mPosition = -1;
	
	/**
	 * The maximum number of characters to be displayed for a suggestion.
	 * @var integer
	 */
	this._mSuggestionMaxLength = 30;
	
	/**
	 * The minimum position of the visible suggestions.
	 * @var integer
	 */
	this._mMinVisiblePosition = 0;
	
	/**
	 * The maximum position of the visible suggestions.
	 * @var integer
	 */
	this._mMaxVisiblePosition = 9;
	
	/**
	 * Flag that indicates if the listening cycle has started.
	 * @var boolean
	 */
	this._mIsListening = false;
}

/**
* Inherit the Command class methods.
*/
SearchProduct.prototype = new Command();
  
/**
 * Method that checks to see if the keyword specified as parameter is in the cache or tries to find the
 * longest matching prefixes in the cache and adds them in the cache for the current keyword parameter.
 * @param string sKeyword
 */
SearchProduct.prototype.checkCache = function(sKeyword){
	// check to see if the sKeyword is already in the cache
	if(this._mCache[sKeyword])
		return true;
	// try to find the biggest prefixes
	for(i = sKeyword.length - 2; i >= 0; i--)
	{
		// compute the current prefix sKeyword 
		var currentKeyword = sKeyword.substring(0, i + 1);
 		// check to see if we have the current prefix sKeyword in the cache
 		if(this._mCache[currentKeyword])
 		{            
 			// the current keyword's results already in the cache
 			var cacheResults = this._mCache[currentKeyword];
 			// the results matching the sKeyword in the current cache results
 			var keywordResults = new Array();
 			var keywordResultsSize = 0;            
 			// try to find all matching results starting with the current prefix
 			for(j = 0; j < cacheResults.length; j++)
 			{
 				if(cacheResults[j].name.toUpperCase().indexOf(sKeyword.toUpperCase()) == 0)
 					keywordResults[keywordResultsSize++] = cacheResults[j];
 			}      
 			// add all the sKeyword's prefix results to the cache
 			this.addToCache(sKeyword, keywordResults);      
 			return true;  
 		}
 	}
 	// no match found
 	return false;
}

/**
 * Method that adds to a keyword an array of values.
 * @param string sKeyword
 * @param array values
 */
SearchProduct.prototype.addToCache = function(sKeyword, values){
	// create a new array entry in the cache
	this._mCache[sKeyword] = new Array();
 	// add all the values to the sKeyword's entry in the cache
 	for(i = 0; i < values.length; i++)
 		this._mCache[sKeyword][i] = values[i];
}

/**
 * Returns a list of results from the keyword suggestions.
 * @param string sKeyword
 */
SearchProduct.prototype.getSuggestions = function(sKeyword){
	/* continue if sKeyword isn't null and the last pressed key wasn't up or 
    down */
	if(sKeyword != '' && !this._mIsKeyUpDownPressed)
	{
		// check to see if the sKeyword is in the cache
		isInCache = this.checkCache(sKeyword);
		// if sKeyword is in cache...
		if(isInCache == true)          
		{   
			// retrieve the results from the cache          
			this._mHttpRequestKeyword = sKeyword;
			this.mUserKeyword = sKeyword;     
			// display the results in the cache
			this.displayResults(sKeyword, this._mCache[sKeyword]);                          
		}
		// if the sKeyword isn't in cache, make an HTTP request
		else    
		{
			this.sendRequest(sKeyword);
		}
	}
}

/**
* Send the request to the server.
* @param string sUrlParams
*/
SearchProduct.prototype.sendRequest = function(sKeyword){
	/* if the XMLHttpRequest object isn't busy with a previous
	request... */
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0) 
	{
		this._mHttpRequestKeyword = sKeyword;
		this.mUserKeyword = sKeyword;
		
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'search_str', sKeyword);
		str = Url.addUrlParam(str, 'type', 'xml');
		this._mRequest.open('GET', str, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oTemp = this
		this._mRequest.onreadystatechange = function(){
			oTemp.handleRequestStateChange();
		}
		
		this._mRequest.send(null);
	}
	// if the XMLHttpRequest object is busy...
	else
	{
		this.mUserKeyword = sKeyword;
		
		// clear any previous timeouts already set
		if(this._mTimeoutId != 0)
			clearTimeout(this._mTimeoutId);

		// try again in 0.5 seconds
		oTemp = this;
		this._mTimeoutId = setTimeout('oTemp.getSuggestions(oTemp.mUserKeyword);', 500);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SearchProduct.prototype.displaySuccess = function(xmlDoc){
	// initialize the new array of products' data
	nameArray = new Array();
	// check to see if we have any results for the searched keyword
 
	if(xmlDoc.childNodes.length)
	{
		/* we retrieve the new products' data from the document element as 
       	an array */
		nameArray = this.xmlToArray(xmlDoc.getElementsByTagName('result'));       
	}
	// check to see if other keywords are already being searched for
	if(this._mUserKeyword != '' && (this._mHttpRequestKeyword == this.mUserKeyword))    
	{
		// display the results array
		this.displayResults(this._mHttpRequestKeyword, nameArray);
	}
	else
	{
		// add the results to the cache
		// we don't need to display the results since they are no longer useful
		this.addToCache(this._mHttpRequestKeyword, nameArray);              
	}
}
 
/**
 * Transforms all the children of an xml node into an array.
 * @param Xml resultsXml
 */
SearchProduct.prototype.xmlToArray = function(resultsXml){
 	// initiate the resultsArray
 	var resultsArray = new Array();  
 	// loop through all the xml nodes retrieving the content
 	for(i = 0; i < resultsXml.length; i++){
 		resultsArray[i] = {};
 		resultsArray[i].bar_code = resultsXml.item(i).getElementsByTagName('bar_code')[0].firstChild.data;
 		resultsArray[i].name = resultsXml.item(i).getElementsByTagName('name')[0].firstChild.data;
 		resultsArray[i].packaging = resultsXml.item(i).getElementsByTagName('packaging')[0].firstChild.data;
 	}
 	// return the node's content as an array
 	return resultsArray;
}