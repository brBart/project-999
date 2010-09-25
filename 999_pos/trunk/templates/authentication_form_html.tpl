{* Smarty *}
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