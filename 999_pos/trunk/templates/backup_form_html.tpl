{* Smarty *}
{* status = 0 Creating, status = 1 Complete *}
{literal}
<script type="text/javascript">
	function confirmSubmit()
	{
		var agree = confirm('Asegurese de que no haya actividad en el sistema. \u00BFDesea continuar?');
		if (agree)
			return true ;
		else
			return false ;
	}
</script>
{/literal}
<div id="content">
	<div id="frm" class="content_small">
		{include file='header_data_task_html.tpl'}
		<form method="post" action="index.php?cmd=get_backup" onsubmit="return oSession.setIsLink(true);">
			<fieldset id="main_data">
				<p>
				{if $status eq '0'}
					Presione Aceptar para crear el backup.
				{else}
					Para descargar el backup presione <a href="{$file_url}" onclick="return oSession.setIsLink(true);">aqui</a>.
				{/if}
				</p>
			</fieldset>
			<fieldset id="controls">
				{if $status eq '0'}
				<input name="apply_closure" type="submit"  value="Aceptar"
					onclick="return confirmSubmit();" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
			    {/if}
			</fieldset>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	var oInput = document.getElementById('days');
	oInput.focus();
</script>
{/literal}