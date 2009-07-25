{* Smarty *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/state_machine.js"></script>
<script type="text/javascript">
	var oConsole = new Console();
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
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
		<p><label>Codigo:</label><span>{$manufacturer_id}&nbsp;</span></p>
	  	<p><label for="name">Nombre:</label><input name="form_widget" id="name" type="text"
	  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
	  			{if $status eq 1}disabled="disabled"{/if} />
	  	<span id="name_failed" class="hidden">*</span></p>
	</fieldset>
	<fieldset id="controls">
	  	<input name="form_widget" id="save" type="button" value="Guardar" onclick="oSaveObject.execute();"
	  		{if $status eq 1}disabled="disabled"{/if}  />
	  	<input name="form_widget" id="edit" type="button" value="Editar"
	  		{if $status eq 0}disabled="disabled"{/if} />
	  	<input name="form_widget" id="delete" type="button" value="Eliminar"
	  		{if $status eq 0}disabled="disabled"{/if} />
	  	<input name="form_widget" id="cancel" type="button" value="Cancelar"
	  			onclick="oSession.loadHref('{$on_cancel}');" {if $status eq 1}disabled="disabled"{/if} />
	</fieldset>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}