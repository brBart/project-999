{* Smarty *}
<fieldset>
	<label>Status:</label><span id="status_label"></span>
</fieldset>
<fieldset>
	<label>Codigo:</label><br />
  	<label for="name">Nombre:</label><input name="name" id="name" type="text"
  			onblur="SetProperty.execute('set_name_object', this.value, this.id)" />
  	<span id="name_failed">*</span>
</fieldset>
<div>
  	<input name="save" id="save" type="button" value="Guardar" onclick="SaveObject.execute()" />
  	<input name="edit" id="edit" type="button" value="Editar" />
  	<input name="delete" id="delete" type="button" value="Eliminar" />
  	<input name="cancel" id="cancel" type="button" value="Cancelar"
  			onclick="document.location.href='{$on_cancel}'"/>
  	<input name="status" id="status" type="hidden" value="{$status}" />
</div>