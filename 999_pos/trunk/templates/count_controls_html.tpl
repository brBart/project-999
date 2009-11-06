{* Smarty *}
<script type="text/javascript" src="../scripts/save.js"></script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript" src="../scripts/delete.js"></script>
{/if}
<script type="text/javascript">
var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
{if $status eq 1}
var oEdit = new EditCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), oMachine);
var oDelete = new DeleteCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
{/if}
</script>
<fieldset id="controls">
  	<input name="form_widget" id="save" type="button" value="Guardar"
  		onclick="oSave.execute('{$foward_link}');" {if $status eq 1}disabled="disabled"{/if}  />
  	<input name="form_widget" id="edit" type="button" value="Editar"
  		{if $status eq 1}
  			onclick="oEdit.execute('{$edit_cmd}', '{$focus_on_edit}');"
  		{else}
  			disabled="disabled"
  		{/if} />
  	<input name="form_widget" id="delete" type="button" value="Eliminar"
  		{if $status eq 1}
  			onclick="if(confirm('&iquest;Esta seguro que desea eliminar?')) oDelete.execute('{$delete_cmd}', '{$back_link}');"
  		{else}
  			disabled="disabled"
  		{/if} />
  	<input name="form_widget" id="undo" type="button" value="Cancelar"
  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$foward_link|cat:'&id='|cat:$id}{/if}');"
  			{if $status eq 1}disabled="disabled"{/if} />
</fieldset>