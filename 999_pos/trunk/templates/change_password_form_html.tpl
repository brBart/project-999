{* Smarty *}
<div id="content">
	<div id="form" class="content_small">
		<form method="post" action="index.php?cmd=change_password_operations" onsubmit="return oSession.setIsLink(true);">
			<fieldset id="main_data">
				<p><label>Usuario:</label><span>{$username}</span></p>
			    <p><label>Contrase&ntilde;a actual:</label><input name="password" id="password" type="password" maxlength="20" /></p>
			    <p><label>Contrase&ntilde;a nueva:</label><input name="new_password" id="new_password" type="password" maxlength="20" /></p>
			    <p><label>Confirmar:</label>
			    		<input name="confirm_password" id="confirm_password" type="password" maxlength="20" /></p>
			</fieldset>
			<fieldset id="controls">
				<input name="change_password" type="submit"  value="Guardar" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('index.php');" />
			</fieldset>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	var oInput = document.getElementById('password');
	oInput.focus();
</script>
{/literal}