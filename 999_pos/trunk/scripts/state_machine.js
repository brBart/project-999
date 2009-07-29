/**
 * Library with the state machine class in charge of controlling the state of each element in the form.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor. Does nothing because methods are static.
 */
function StateMachine(){}

/**
 * Set the focus on the element with the provided id.
 * @param string sElementId
 */
StateMachine.setFocus = function(sElementId){
	var oElement = document.getElementById(sElementId);
	oElement.focus();
}

/**
 * Set the form the edit state.
 */
StateMachine.changeToEditState = function(sElementId){
	// Disabled and enabled the required widgets in the form.
	var arrElements = document.getElementsByName('form_widget');
	for (var i = 0; i < arrElements.length; i++){
		var oElement = arrElements[i];
		if(oElement.disabled == true)
			oElement.disabled = false;
		else
			oElement.disabled = true;
	}
	
	// Change the status_label to edit.
	var oStatus = document.getElementById('status_label');
	oStatus.innerHTML = 'Editando...';
	
	this.setFocus(sElementId);
}