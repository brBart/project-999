{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript" src="../scripts/delete.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{if $status eq 1}
	var oEdit = new EditCommand(oSession, oConsole, createXmlHttpRequestObject(), oMachine);
	var oDelete = new DeleteCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_small">
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
		  	<p><label for="name">Nombre:*</label><input name="form_widget" id="name" type="text"
		  			value="{$name}" maxlength="100"
		  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  	<span id="name-failed" class="hidden">*</span></p>
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
		  			onclick="if(confirm('&iquest;Esta seguro que desea eliminar?')) oDelete.execute('delete_manufacturer', '{$back_link}');"
		  		{else}
		  			disabled="disabled"
		  		{/if} />
		  	<input name="form_widget" id="undo" type="button" value="Cancelar"
		  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$foward_link}{/if}');"
		  			{if $status eq 1}disabled="disabled"{/if} />
		</fieldset>
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}