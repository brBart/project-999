{* Smarty *}
<div id="content">
	<div id="frm" class="content_small">
		<form method="post" action="index.php?cmd=show_expired_lot_list&date={$date|escape:'url'}&page=1" onsubmit="return oSession.setIsLink(true);">
			{include file='header_data_report_html.tpl'}
			<fieldset id="main_data">
				<p>
					Presione Aceptar para ver el reporte.
				</p>
			</fieldset>
			<fieldset id="controls">
				<input name="show_report" type="submit"  value="Aceptar" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
			</fieldset>
		</form>
	</div>
</div>