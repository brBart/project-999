{* Smarty *}
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript">
var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
var oEdit = new EditCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), oMachine);
</script>
<fieldset id="controls">
  	<input name="form_widget" id="save" type="button" value="Guardar"
  		onclick="oSave.execute('{$foward_link}');" disabled="disabled" />
  	<input name="form_widget" id="edit" type="button" value="Editar"
  		onclick="oEdit.execute('{$edit_cmd}', '{$focus_on_edit}');" />
  	<input name="form_widget" id="undo" type="button" value="Cancelar"
  			onclick="oSession.loadHref('{$foward_link}');" disabled="disabled" />
</fieldset>