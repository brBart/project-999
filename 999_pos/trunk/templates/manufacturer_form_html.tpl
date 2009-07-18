{* Smarty *}
<div id="form">
	<fieldset>
		<label>Status:</label><span id="status_label"></span>
	</fieldset>
	<fieldset>
		<label>Codigo:</label><span>{$manufacturer_id}</span><br />
	  	<label for="name">Nombre:</label><input name="name" id="name" type="text"
	  			onblur="oSetProperty.execute('set_name_object', this.value, this.id)" />
	  	<span id="name_failed">*</span>
	</fieldset>
	<fieldset>
	  	<input name="save" id="save" type="button" value="Guardar" onclick="oSaveObject.execute()" />
	  	<input name="edit" id="edit" type="button" value="Editar" />
	  	<input name="delete" id="delete" type="button" value="Eliminar" />
	  	<input name="cancel" id="cancel" type="button" value="Cancelar"
	  			onclick="document.location.href='{$on_cancel}'"/>
	  	<input name="status" id="status" type="hidden" value="{$status}" />
	</fieldset>
</div>