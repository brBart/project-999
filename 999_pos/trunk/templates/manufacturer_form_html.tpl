{* Smarty *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript">
	var oConsole = new Console();
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
</script>
<div id="form" class="frm_small">
	<fieldset id="status_bar">
		<p><label>Status:</label><span id="status_label">&nbsp;</span></p>
	</fieldset>
	<fieldset id="main_data">
		<p><label>Codigo:</label><span>{$manufacturer_id}&nbsp;</span></p>
	  	<p><label for="name">Nombre:</label><input name="name" id="name" type="text"
	  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);" />
	  	<span id="name_failed">*</span></p>
	</fieldset>
	<fieldset id="controls">
	  	<input name="save" id="save" type="button" value="Guardar" onclick="oSaveObject.execute();" />
	  	<input name="edit" id="edit" type="button" value="Editar" />
	  	<input name="delete" id="delete" type="button" value="Eliminar" />
	  	<input name="cancel" id="cancel" type="button" value="Cancelar"
	  			onclick="oSession.loadHref('{$on_cancel}');"/>
	  	<input name="status" id="status" type="hidden" value="{$status}" />
	</fieldset>
</div>