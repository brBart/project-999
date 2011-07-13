{* Smarty *}
{if $status eq 0}
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript">
var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
</script>
{else}
<script type="text/javascript" src="../scripts/delete.js"></script>
<script type="text/javascript">
var oDelete = new DeleteCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
</script>
{/if}
<fieldset id="controls">
	{if $status eq 0}
  	<input name="form_widget" id="save" type="button" value="Guardar"
  		onclick="if(confirm('Una vez guardado el correlativo no se podra editar mas. &iquest;Desea guardar?')) oSave.execute('{$forward_link}');" />
  	<input name="form_widget" id="undo" type="button" value="Cancelar"
  		onclick="oSession.loadHref('{$back_link}');" />
  	{else}
  	<input name="form_widget" id="delete" type="button" value="Eliminar"
  		onclick="if(confirm('&iquest;Esta seguro que desea eliminar?')) oDelete.execute('{$delete_cmd}', '{$back_link}');"
  		{if $status eq 3 or $status eq 4}disabled="disabled"{/if} />
  	{/if}
</fieldset>
