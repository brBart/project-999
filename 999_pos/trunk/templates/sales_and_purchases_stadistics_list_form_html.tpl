{* Smarty *}
<div id="content">
	<div id="frm" class="content_medium">
		{include file='header_data_report_html.tpl'}
		<form method="post" action="index.php?cmd=show_sales_and_purchases_stadistics_list&page=1" onsubmit="return oSession.setIsLink(true);">
			<fieldset id="main_data">
				<p>
					<label>Ultimos:</label>
					<select name="months" id="months">
						{section name=i loop=$months_list}
							<option value="{$months_list[i]}"
								{if $months_list[i] eq $months}selected="selected"{/if}>
								{$months_list[i]} meses
							</option>
						{/section}
	    			</select>
				</p>
				<p>
					<label for="product">De producto:</label>
					<input type="radio" id="product" name="order" value="product"
						{if $order eq 'product'}checked="checked"{/if} />
					<select name="product_first" id="product_first">
		    			{section name=i loop=$product_list}
		    				<option value="{$product_list[i].name|escape}"
		    					{if $product_list[i].name eq $product_first}selected="selected"{/if}>
		    					{$product_list[i].name|escape}
		    				</option>
		    			{/section}
		    		</select>
		    		<span>A:</span>
	    			<select name="product_last" id="product_last">
		    			{section name=i loop=$product_list}
		    				<option value="{$product_list[i].name|escape}"
		    					{if $product_list[i].name eq $product_last}selected="selected"{/if}>
		    					{$product_list[i].name|escape}
		    				</option>
		    			{/section}
	    			</select>
		    	</p>
		    	<p>
		    		<label for="manufacturer">De casa:</label>
		    		<input type="radio" id="manufacturer" name="order" value="manufacturer"
		    			{if $order neq 'product'}checked="checked"{/if}/>
		    		<select name="manufacturer_first" id="manufacturer_first">
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].name|escape}"
		    					{if $manufacturer_list[i].name eq $manufacturer_first}selected="selected"{/if}>
		    					{$manufacturer_list[i].name|escape}
		    				</option>
		    			{/section}
		    		</select>
		    		<span>A:</span>
		    		<select name="manufacturer_last" id="manufacturer_last">
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].name|escape}"
		    					{if $manufacturer_list[i].name eq $manufacturer_last}selected="selected"{/if}>
		    					{$manufacturer_list[i].name|escape}
		    				</option>
		    			{/section}
		    		</select>
		    	</p>
			</fieldset>
			<fieldset id="controls">
				<input name="show_report" type="submit"  value="Aceptar"
					onclick="return confirmSubmit();" />
			    <input type="button"  value="Cancelar" onclick="oSession.loadHref('{$back_link}');" />
			</fieldset>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	var oInput = document.getElementById('months');
	oInput.focus();
</script>
{/literal}