{* Smarty *}
{literal}
<script type="text/javascript">
	function confirmSubmit()
	{
		var agree = confirm('Asegurese de haber hecho un backup y que no haya actividad en el sistema. \u00BFDesea continuar?');
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
		<form method="post" action="index.php?cmd=apply_general_closure" onsubmit="return oSession.setIsLink(true);">
			<fieldset id="main_data">
				<p>
					<label>Dejar:</label>
					<select name="days" id="days">
	    				<option value="15">15 dias</option>
	    			{section name=i loop=150 start=30 step=30}
	    				<option value="{$smarty.section.i.index}">
	    					{$smarty.section.i.index} dias
	   					</option>
	    			{/section}
	    			</select>
	    			<span class="hint">(informaci&oacute;n)</span>
				</p>
			</fieldset>
			<fieldset id="controls">
				<input name="apply_closure" type="submit"  value="Aceptar"
					onclick="return confirmSubmit();" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
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