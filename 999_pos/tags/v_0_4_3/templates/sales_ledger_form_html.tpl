{* Smarty *}
{* status = 0 Creating, status = 1 Complete *}
<div id="content">
	<div id="frm" class="content_fit">
		<form method="post" action="index.php?cmd=get_sales_ledger" onsubmit="return oSession.setIsLink(true);">
			{include file='header_data_task_html.tpl'}
			<fieldset id="main_data">
				{if $status eq '0'}
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
				{else}
				<p>
					Para descargar el libro de ventas presione <a href="#"
						onclick="window.open('{$file_url}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=1,toolbar=1,resizable=0,scrollbars=1');">aqui</a>.
				</p>
				{/if}
			</fieldset>
			<fieldset id="controls">
				{if $status eq '0'}
				<input name="get_sales_ledger" type="submit"  value="Aceptar" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
			    {/if}
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