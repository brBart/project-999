/**
 * Library with the object in charge for keeping the status of the actual session.
 */

/**
 * Constructor function, session flag is true by default.
 */
function Session(){
	/**
	 * Flag to keep track of the actual session.
	 */
	this._mIsActive = true;
	
	/**
	 * Flag to keep track of the system flow. False by default, meaning that if the system closes it wasn't by
	 * the logout link. 
	 */
	this._mIsLink = false;
}

/**
 * Sets the value of the session flag.
 * @param boolean bValue
 */
Session.prototype.setIsActive = function(bValue){
	this._mIsActive = bValue;
}

/**
 * Used by all the system's links to notify... they have been click. Always return true to keep up with the link.
 * @param boolean bValue
 * @return boolean
 */
Session.prototype.setIsLink = function(bValue){
	this._mIsLink = bValue;
	return true;
}
 

Session.prototype.loadHref = function(sHref){
	this._mIsLink = true;
	document.location.href = sHref;
}

/**
 * Check before unloading the current page if the event was a system click event or other thing.
 * @param event oEvent
 */
Session.prototype.verifyStatus = function(oEvent){
	// It was a click on a system link.
	if(this._mIsLink){
		this._mIsLink = false;
		return;
	}
	
	// Otherwise not.
	if(this._mIsActive){
		var oEvent = oEvent || window.event;
		oEvent.returnValue = 'Abandonar el sistema de esta forma puede causar perdida de datos. Cierre la sesión '
				+ 'mediante la opción de logout en el menu principal antes de cerrar la ventana.';
	}
}