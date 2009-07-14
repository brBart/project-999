{* Smarty *}
<fieldset>
	<label>Usuario:</label><span>{$username}</span><br />
    <label>Contrase&ntilde;a Anterior:</label><input name="password" id="password" type="password" /><br />
    <label>Nueva Contrase&ntilde;a:</label><input name="new_password" id="new_password" type="password" /><br />
    <label>Confirme Contrase&ntilde;a:</label>
    		<input name="confirm_password" id="confirm_password" type="password" />
</fieldset>
<div>
	<input name="change_password" type="submit"  value="Guardar" />
    <input type="button"  value="Cancelar" onclick="document.location.href='index.php'" />
</div>