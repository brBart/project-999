/**
 * Library with the Url class in charge of creating the urls for communicating with the server.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor. Does nothing because methods are static.
 */
function Url(){}

/**
 * Method for returning the site url.
 */
Url.getUrl = function(){
	return 'index.php';
}

/**
 * Concatenates the params the provided url.
 * @param string sUrl
 * @param string sParam
 * @param string sValue
 */
Url.addUrlParam = function(sUrl, sParam, sValue){
	sUrl += (sUrl.indexOf('?') == -1) ? '?' : '&';
	sUrl += encodeURIComponent(sParam) + '=' + encodeURIComponent(sValue);
	return sUrl;
}