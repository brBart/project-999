/**
 * @fileOverview Library with the SearchProduct class.
 * @author Roberto Oliveros
 */

/**
 * @class Class in charge for making product searches on the server.
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function SearchProduct(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the name of the command on the server.
	 * @type String
	 */
	this._mCmd = 'search_product';
	
	/**
	 * Holds the settimeout id.
	 * @type Integer
	 */
	this._mTimeoutId = 0;
	
	/**
	 * Cache containing the retrieved suggestions for different keywords.
	 * @type Array
	 */
	this._mCache = null;
	
	/**
	 * Holds a reference to the object which handles displaying the result details.
	 * @type SearchProductDetails
	 */
	this._mSearchProductDetails = null;
}

/**
 * Inherit the Command class methods.
 */
SearchProduct.prototype = new Command();

/**
 * Initialize the object properties.
 * @param {SearchProductDetails} oSearchProductDetails
 */
SearchProduct.prototype.init = function(oSearchProductDetails){
	this._mCache = new Array();
	this._mSearchProductDetails = oSearchProductDetails;
}
  
/**
 * Method that checks to see if the keyword specified as parameter is in the cache or tries to find the
 * longest matching prefixes in the cache and adds them in the cache for the current keyword parameter.
 * @param {String} sKeyword
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
 * Method that adds to a keyword in the cache an array of values.
 * @param {String} sKeyword
 * @param {Array} values
 */
SearchProduct.prototype.addToCache = function(sKeyword, values){
	// create a new array entry in the cache
	this._mCache[sKeyword] = new Array();
 	// add all the values to the sKeyword's entry in the cache
 	for(i = 0; i < values.length; i++)
 		this._mCache[sKeyword][i] = values[i];
}

/**
 * Returns a list of results from the keyword suggestions if there is a match in the cache or sends a
 * request to the server.
 * @param {String} sKeyword
 */
SearchProduct.prototype.getSuggestions = function(sKeyword){
	// check to see if the sKeyword is in the cache
	isInCache = this.checkCache(sKeyword);
	// if sKeyword is in cache...
	if(isInCache == true)          
	{   
		this._mSearchProductDetails.displayResults(sKeyword, this._mCache[sKeyword]);                          
	}
	// if the sKeyword isn't in cache, make an HTTP request
	else    
	{
		this.sendRequest(sKeyword);
	}
}

/**
 * Send the request to the server.
 * @param {String} sKeyword
 */
SearchProduct.prototype.sendRequest = function(sKeyword){
	/* if the XMLHttpRequest object isn't busy with a previous
	request... */
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0) 
	{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'keyword', sKeyword);
		str = Url.addUrlParam(str, 'type', 'xml');
		this._mRequest.open('GET', str, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oTemp = this;
		this._mRequest.onreadystatechange = function(){
			oTemp.handleRequestStateChange();
		}
		
		this._mRequest.send(null);
	}
	// if the XMLHttpRequest object is busy...
	else
	{
		// clear any previous timeouts already set
		if(this._mTimeoutId != 0)
			clearTimeout(this._mTimeoutId);

		// try again in 0.5 seconds
		var oTemp = this;
		this._mTimeoutId = setTimeout(function(){oTemp.getSuggestions(sKeyword);}, 500);
	}
}

/**
 * Adds the results to the object's cache and display the results.
 * @param {DocumentElement} xmlDoc
 */
SearchProduct.prototype.displaySuccess = function(xmlDoc){
	// Get the original keyword.
	var keyword = xmlDoc.getElementsByTagName('keyword')[0].firstChild.data;
	// initialize the new array of products' data
	nameArray = new Array();
	// check to see if we have any results for the searched keyword
 
	if(xmlDoc.childNodes.length)
	{
		/* we retrieve the new products' data from the document element as 
       	an array */
		nameArray = this.xmlToArray(xmlDoc.getElementsByTagName('result'));       
	}
	// add the results to the cache
	this.addToCache(keyword, nameArray);
	// display the results array
	this._mSearchProductDetails.displayResults(keyword, nameArray);
}
 
/**
 * Transforms all the children of an xml node into an array.
 * @param {DocumentElement} resultsXml
 */
SearchProduct.prototype.xmlToArray = function(resultsXml){
 	// initiate the resultsArray
 	var resultsArray = new Array();  
 	// loop through all the xml nodes retrieving the content
 	for(i = 0; i < resultsXml.length; i++){
 		resultsArray[i] = new Array();
 		resultsArray[i]['bar_code'] = resultsXml.item(i).getElementsByTagName('bar_code')[0].firstChild.data;
 		resultsArray[i]['name'] = resultsXml.item(i).getElementsByTagName('name')[0].firstChild.data;
 		resultsArray[i]['packaging'] = resultsXml.item(i).getElementsByTagName('packaging')[0].firstChild.data;
 	}
 	// return the node's content as an array
 	return resultsArray;
}