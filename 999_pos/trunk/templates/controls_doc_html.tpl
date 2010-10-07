{* Smarty *}
{if $status eq 0}
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/discard_document.js"></script>
<script type="text/javascript">
var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
var oDiscard = new DiscardDocumentCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
</script>
{/if}
<fieldset id="controls">
	{if $status eq 0}
  	<input name="form_widget" id="save" type="button" value="Guardar"
  			onclick="if(confirm('Una vez guardado el documento no se podra editar mas. &iquest;Desea guardar?')) oSave.execute('{$forward_link}');" />
  	<input name="form_widget" id="undo" type="button" value="Cancelar"
  			onclick="oDiscard.execute('{$back_link}');" />
  	{else}
  	<input name="form_widget" id="cancel" type="button" value="Anular"
  			{if $status eq 1}onclick="oCancel.showForm();"{else}disabled="disabled"{/if} />
  	<input name="form_widget" id="print" type="button" value="Imprimir"
  			onclick="window.open('index.php?cmd={$print_cmd}&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
  	{/if}
</fieldset>
{if $status eq 1}
{include file='authentication_form_html.tpl'}
{/if}