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
	  		// assume IE6 or older
	  		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
	  					"MSXML2.XMLHTTP.5.0",
	                    "MSXML2.XMLHTTP.4.0",
	                    "MSXML2.XMLHTTP.3.0",
	                    "MSXML2.XMLHTTP",
	                    "Microsoft.XMLHTTP");
	  		// try every id until one works
	  		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
	  		{
	  			try 
	  			{ 
	  				// try to create XMLHttpRequest object
	  				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
	  			} 
	  			catch (e) {} // ignore potential error
	  		}
	  	}
	  	// return the created object or display an error message
	  	if (!xmlHttp)
	  		oConsole.displayError('Interno: Imposible crear el objeto XmlHttpRequest.');
	  	else 
	  		return xmlHttp;
}