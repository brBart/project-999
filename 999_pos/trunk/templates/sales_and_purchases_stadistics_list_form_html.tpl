{* Smarty *}
<div id="content">
	<div id="frm" class="content_medium">
		{include file='header_data_report_html.tpl'}
		<form method="post" action="index.php?cmd=show_sales_and_purchases_stadistics_list&page=1" onsubmit="return oSession.setIsLink(true);">
			<fieldset id="main_data">
				<p>
					<label>Ultimos:</label>
					<select name="months" id="months">
	    				<option value="3">3 meses</option>
	    				<option value="6" selected="selected">6 meses</option>
	    				<option value="9">9 meses</option>
	    			</select>
				</p>
				<p>
					<label for="product">De producto:</label>
					<input type="radio" id="product" name="order" checked="checked" value="product" />
					<select name="first" id="product_first">
		    			{section name=i loop=$product_list}
		    				<option value="{$product_list[i].name|escape}">
		    					{$product_list[i].name|escape}
		    				</option>
		    			{/section}
		    		</select>
		    		<span>A:</span>
	    			<select name="last" id="product_last">
		    			{section name=i loop=$product_list}
		    				<option value="{$product_list[i].name|escape}">
		    					{$product_list[i].name|escape}
		    				</option>
		    			{/section}
	    			</select>
		    	</p>
		    	<p>
		    		<label for="manufacturer">De casa:</label>
		    		<input type="radio" id="manufacturer" name="order" value="manufacturer" />
		    		<select name="first" id="manufacturer_first">
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].name|escape}">
		    					{$manufacturer_list[i].name|escape}
		    				</option>
		    			{/section}
		    		</select>
		    		<span>A:</span>
		    		<select name="last" id="manufacturer_last">
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].name|escape}">
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
	var oInput = document.getElementById('days');
	oInput.focus();
</script>
{/literal}