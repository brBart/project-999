{* Smarty *}
{* status = 0 Edit, status = 1 Consult *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/async.js"></script>
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/state_machine.js"></script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript" src="../scripts/delete.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console();
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{if $status eq 1}
	var oEdit = new EditCommand(oSession, oConsole, createXmlHttpRequestObject());
	var oDelete = new DeleteCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{/if}
</script>
<div id="form" class="frm_small">
	<fieldset id="status_bar">
		<p>
			<label>Status:</label>
			<span id="status_label">
				{if $status eq 0}
					Creando...
				{else}
					Consulta
				{/if}
			</span>
		</p>
	</fieldset>
	<fieldset id="main_data">
		<p><label>Codigo:</label><span>{$id}&nbsp;</span></p>
	  	<p><label for="name">Nombre:</label><input name="form_widget" id="name" type="text"
	  			value="{$name}" maxlength="100"
	  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
	  			{if $status eq 1}disabled="disabled"{/if} />
	  	<span id="name_failed" class="hidden">*</span></p>
	</fieldset>
	<fieldset id="controls">
	  	<input name="form_widget" id="save" type="button" value="Guardar"
	  		onclick="oSave.execute('get_manufacturer');" {if $status eq 1}disabled="disabled"{/if}  />
	  	<input name="form_widget" id="edit" type="button" value="Editar"
	  		{if $status eq 1}
	  			onclick="oEdit.execute('edit_manufacturer', 'name');"
	  		{else}
	  			disabled="disabled"
	  		{/if} />
	  	<input name="form_widget" id="delete" type="button" value="Eliminar"
	  		{if $status eq 1}
	  			onclick="if(confirm('¿Esta seguro que desea eliminar?')) oDelete.execute('delete_manufacturer', 'show_manufacturers_menu');"
	  		{else}
	  			disabled="disabled"
	  		{/if} />
	  	<input name="form_widget" id="cancel" type="button" value="Cancelar"
	  			onclick="oSession.loadHref('{$on_cancel}');" {if $status eq 1}disabled="disabled"{/if} />
	</fieldset>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}