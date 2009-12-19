{* Smarty *}
{if $status eq 0}
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript">
var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
</script>
{else}
<script type="text/javascript" src="../scripts/make_default_correlative.js"></script>
<script type="text/javascript">
var oMakeDefault = new MakeDefaultCorrelativeCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
</script>
{/if}
<fieldset id="controls">
	{if $status eq 0}
  	<input name="form_widget" id="save" type="button" value="Guardar"
  		onclick="if(confirm('Una vez guardado el correlativo no se podra editar mas. &iquest;Desea guardar?')) oSave.execute('{$foward_link}');" />
  	<input name="form_widget" id="undo" type="button" value="Cancelar"
  		onclick="oSession.loadHref('{$back_link}');" />
  	{else}
  	<input name="form_widget" id="default" type="button" value="Predeterminado"
  		{if $is_default eq 0}onclick="oMakeDefault.execute();"{else}disabled="disabled"{/if} />
  	<input name="form_widget" id="delete" type="button" value="Eliminar"
  		onclick="if(confirm('&iquest;Esta seguro que desea eliminar?')) oDelete.execute('{$delete_cmd}', '{$back_link}');" />
  	{/if}
</fieldset>
{if $status eq 1}
<script type="text/javascript">
oMakeDefault.init('{$serial_number_span}', 'default');
</script>
{/if}