/**
 * Library with the routine for creating the XmlHttpRequest object.
 */

/**
 * Creates the adequate object depending on the browser.
 * @return XmlHttpRequest 
 */
function createXmlHttpRequestObject(){
	// will store the reference to the XMLHttpRequest object
	var xmlHttp;
	// this should work for all browsers except IE6 and older
	try
		{
	    	// try to create XMLHttpRequest object
	    	xmlHttp = new XMLHttpRequest();
		}
	  	catch(e)
	  	{
	  		oConsole.displayError('Interno: Imposible crear el objeto XmlHttpRequest. Verifique la version del' +
	  				'navegador.');
	  	}
	  	
  		return xmlHttp;
}