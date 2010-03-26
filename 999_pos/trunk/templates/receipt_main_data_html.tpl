{* Smarty *}
<fieldset id="main_data">
	<p>
  		<label>Proveedor:</label>
  		<span>{$supplier|htmlchars}</span>
  	</p>
  	<p>
  		<label>Env&iacute;o No:</label>
  		<span>{$shipment_number|htmlchars}</span>
  	</p>
  	<p>
  		<label>Total env&iacute;o:</label>
  		<span>{$shipment_total}</span>
  	</p>
</fieldset>