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
  			onclick="if(confirm('Una vez guardado el documento no se podra editar mas. &iquest;Desea guardar?')) oSave.execute('{$foward_link}');" />
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
<script type="text/javascript" src="../scripts/modal_form.js"></script>
<script type="text/javascript" src="../scripts/cancel_document.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<div id="authenticate_container" class="hidden">
	<div id="authenticate_form">
		<fieldset>
			<legend>Autorizaci&oacute;n</legend>
		  	<p>
				<label for="username">Usuario:</label>
				<input name="username" id="username" type="text" maxlength="20" />
		  	</p>
		  	<p>
				<label for="password">Contrase&ntilde;a:</label>
				<input name="password" id="password" type="password" maxlength="20" />
		  	</p>
		  	<div id="mini_console"></div>
		  	<p>
				<input id="authenticate" type="button" value="Aceptar" onclick="oCancel.execute('{$cancel_cmd}');" />
				<input id="return" type="button" value="Cancelar" onclick="oCancel.hideForm();" />
		  	</p>
		 </fieldset>
	 </div>
</div>
<script type="text/javascript">
var oModalFrm = new ModalForm('authenticate_container');
var miniConsole = new Console('mini_console');
var oCancel = new CancelDocumentCommand(oSession, miniConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oModalFrm);
oCancel.init('username', 'password');
</script>
{/if}