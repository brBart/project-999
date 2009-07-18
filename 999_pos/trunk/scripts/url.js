/**
 * Library with the Url class in charge of creating the urls for communicating with the server.
 * @package Client
 * @author Roberto Oliveros
 */

**
 * Constructor function.
 */
function Url(){
	this.siteUrl = 'index.php';
}

/**
 * Method for returning the site url.
 */
Url.prototype.getUrl = function(){
	return this.siteUrl;
}

/**
 * Concatenates the params the provided url.
 * @param string sUrl
 * @param string sParam
 * @param string sValue
 */
Url.prototype.addUrlParam = function(sUrl, sParam, sValue){
	sUrl += (sUrl.indexOf('?') == -1) ? '?' : '&';
	sUrl += encodeURIComponent(sParam) + '=' + encodeURIComponent(sValue);
	return sUrl;
}