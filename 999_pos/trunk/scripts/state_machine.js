/**
 * Library with the state machine class in charge of controlling the state of each element in the form.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor. Receives the actual status of the form.
 * @param integer iStatus
 */
function StateMachine(iStatus){
	/**
	 * Holds the actual status of the form. 0 = edit, 1 = idle.
	 * @var integer
	 */
	this._mStatus = iStatus;
}

/**
 * Set the focus on the element with the provided id.
 * @param variant xValue
 */
StateMachine.setFocus = function(xValue){
	var oElement = ((typeof xValue) == 'string') ? document.getElementById(xValue) : xValue;
	
	if(oElement.tagName == 'INPUT' && oElement.value != '')
		TextRange.selectRange(oElement, 0, oElement.value.length);
	
	oElement.focus();
}

/**
 * Set the form the edit state. Receive the name of the element which receives the focus.
 * @param string sElementId
 */
StateMachine.prototype.changeToEditState = function(sElementId){
	// Disabled and enabled the required widgets in the form.
	var arrElements = document.getElementsByName('form_widget');
	for (var i = 0; i < arrElements.length; i++){
		var oElement = arrElements[i];
		
		if(oElement.tagName == 'A')
			oElement.className = 'invisible';
		else
			if(oElement.disabled == true)
				oElement.disabled = false;
			else
				oElement.disabled = true;
	}
	
	// If there is a details table.
	oDetails = document.getElementById('details');
	if(oDetails){
		oTable = oDetails.getElementsByTagName('table')[0];
		oTable.className = '';
	}
	
	// Hide back link option.
	var oLink = document.getElementById('back_link');
	oLink.className = 'hidden';
	
	// Change the status_label to edit.
	var oStatus = document.getElementById('status_label');
	oStatus.innerHTML = 'Editando...';
	
	StateMachine.setFocus(sElementId);
	
	this._mStatus = 0;
}
 
/**
 * Returns the actual value of the status property.
 */
StateMachine.prototype.getStatus = function(){
	return this._mStatus;
}