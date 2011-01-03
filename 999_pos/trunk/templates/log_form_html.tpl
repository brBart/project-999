{* Smarty *}
<div id="content">
	<div id="frm" class="content_small">
		<form method="post" action="{$log_cmd}" onsubmit="return oSession.setIsLink(true);">
			<fieldset>
				<p>
					<label>Bitacora:</label><span>{$log_name}</span>
				</p>
			</fieldset>
			<fieldset id="main_data">
				<p>
	    			<label for="start_date">Fecha inicial:</label>
		    		<input name="start_date" id="start_date" type="text" value="{$start_date}" maxlength="10" />
		    		<span class="hint">dd/mm/aaaa</span>
	    		</p>
	    		<p>
	    			<label for="end_date">Fecha final:</label>
		    		<input name="end_date" id="end_date" type="text" value="{$end_date}" maxlength="10" />
		    		<span class="hint">dd/mm/aaaa</span>
	    		</p>
			</fieldset>
			<fieldset id="controls">
				<input name="show_log" type="submit"  value="Aceptar" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
			</fieldset>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	var oInput = document.getElementById('start_date');
	oInput.focus();
</script>
{/literal}