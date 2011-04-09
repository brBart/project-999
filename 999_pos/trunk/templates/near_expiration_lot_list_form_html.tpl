{* Smarty *}
<div id="content">
	<div id="frm" class="content_small">
		<form method="post" action="index.php?cmd=show_near_expiration_lot_list&date={$date|escape:'url'}&page=1" onsubmit="return oSession.setIsLink(true);">
			{include file='header_data_report_html.tpl'}
			<fieldset id="main_data">
				<p>
					<label>Proximos:</label>
					<select name="days" id="days">
	    				<option value="15">15 dias</option>
	    				{section name=i loop=150 start=30 step=30}
	    				<option value="{$smarty.section.i.index}">
	    					{$smarty.section.i.index} dias
	   					</option>
	    				{/section}
	    				<option value="180">180 dias</option>
    					<option value="365">365 dias</option>
	    			</select>
				</p>
			</fieldset>
			<fieldset id="controls">
				<input name="show_report" type="submit"  value="Aceptar" />
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