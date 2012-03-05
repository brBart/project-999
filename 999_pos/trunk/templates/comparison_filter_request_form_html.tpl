{* Smarty *}
<div id="content">
	<div id="frm" class="content_fit">
		<form method="post" action="index.php?cmd=get_comparison_filter" onsubmit="return oSession.setIsLink(true);">
			<fieldset>
				<p>
					<label>Filtro:</label><span>Comparaci&oacute;n</span>
				</p>
			</fieldset>
			<fieldset id="main_data">
				<p>
	    			<label for="comparison_id">Comparaci&oacute;n No:</label>
		    		<input name="comparison_id" id="comparison_id" type="text" value="{$comparison_id}" maxlength="11" />
	    		</p>
	    		<p>
	    			<label for="diferences">Filtro:</label>
		    		<input type="radio" id="positives" name="filter_type" value="0"
						{if $filter_type eq '0'}checked="checked"{/if} /> Sobrantes
					<input type="radio" id="negatives" name="filter_type" value="1"
						{if $filter_type eq '1'}checked="checked"{/if} /> Faltantes
					<input type="radio" id="diferences" name="filter_type" value="2"
						{if $filter_type eq '2'}checked="checked"{/if} /> Sobrantes y Faltantes
	    		</p>
	    		<p>
			  		<label for="include_prices">Incluir precios:</label>
			  		<input name="include_prices" id="include_prices" type="checkbox"
			  				{if $include_prices eq 1}checked="checked"{/if} />
			  	</p>
			</fieldset>
			<fieldset id="controls">
				<input name="get_comparison_filter" type="submit"  value="Aceptar" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('index.php?cmd=show_comparison_menu');" />
			</fieldset>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	var oInput = document.getElementById('comparison_id');
	oInput.focus();
</script>
{/literal}