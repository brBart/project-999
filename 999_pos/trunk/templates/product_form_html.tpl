{* Smarty *}
{* status = 0 Edit, status = 1 Consult *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/sync.js"></script>
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/state_machine.js"></script>
<script type="text/javascript" src="../scripts/async.js"></script>
<script type="text/javascript" src="../scripts/remove_session_object.js"></script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript" src="../scripts/delete.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console();
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{if $status eq 1}
	var oEdit = new EditCommand(oSession, oConsole, createXmlHttpRequestObject());
	var oDelete = new DeleteCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="form" class="frm_medium">
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
		<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
	  	<p>
	  		<label for="name">Nombre:</label>
	  		<input name="form_widget" id="name" type="text" value="{$name}" maxlength="100"
	  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
	  			{if $status eq 1}disabled="disabled"{/if} />
	  		<span id="name-failed" class="hidden">*</span>
	  	</p>
	  	<p>
	  		<label for="bar_code">C&oacute;digo barra:</label>
	  		<input name="form_widget" id="bar_code" type="text" value="{$bar_code}" maxlength="100"
	  			onblur="oSetProperty.execute('set_bar_code_product', this.value, this.id);"
	  			{if $status eq 1}disabled="disabled"{/if} />
	  		<span id="bar_code-failed" class="hidden">*</span>
	  	</p>
	  	<p>
	  		<label for="packaging">Presentaci&oacute;n:</label>
	  		<input name="form_widget" id="packaging" type="text" value="{$packaging}" maxlength="150"
	  			onblur="oSetProperty.execute('set_packaging_product', this.value, this.id);"
	  			{if $status eq 1}disabled="disabled"{/if} />
	  		<span id="packaging-failed" class="hidden">*</span>
	  	</p>
	</fieldset>
	<fieldset id="controls">
	  	<input name="form_widget" id="save" type="button" value="Guardar"
	  		onclick="oSave.execute('{$foward_link}');" {if $status eq 1}disabled="disabled"{/if}  />
	  	<input name="form_widget" id="edit" type="button" value="Editar"
	  		{if $status eq 1}
	  			onclick="oEdit.execute('edit_manufacturer', 'name');"
	  		{else}
	  			disabled="disabled"
	  		{/if} />
	  	<input name="form_widget" id="delete" type="button" value="Eliminar"
	  		{if $status eq 1}
	  			onclick="if(confirm('�Esta seguro que desea eliminar?')) oDelete.execute('delete_manufacturer', '{$back_link}');"
	  		{else}
	  			disabled="disabled"
	  		{/if} />
	  	<input name="form_widget" id="cancel" type="button" value="Cancelar"
	  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$foward_link}{/if}');"
	  			{if $status eq 1}disabled="disabled"{/if} />
	</fieldset>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}