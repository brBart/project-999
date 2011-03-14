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
  		onclick="oSave.execute('{$forward_link}');" {if $status eq 1}disabled="disabled"{/if}  />
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
  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$forward_link|cat:'&id='|cat:$id}{/if}');"
  			{if $status eq 1}disabled="disabled"{/if} />
  	<input name="form_widget" id="print" type="button" value="Imprimir"
  		{if $status eq 1}
  			onclick="window.open('index.php?cmd={$print_cmd}&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');"
  		{else}
  			disabled="disabled"
  		{/if} />
</fieldset>