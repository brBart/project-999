{* Smarty * }
<div id="third_menu">
	<ul>
	    <li>
	    	<form method="post" action="index.php?cmd=print_counting_template_by_product" onsubmit="return oSession.setIsLink(true);">
	    		<label for="product_first">De Producto:</label>
	    		<select name="product_first" id="product_first">
	    			{section name=i loop=$product_list}
	    				<option value="{$product_list[i].name}"
	    					{if $product_list[i].name eq $product_first}selected="selected"{/if}>
	    					{$product_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="product_last">A:</label>
	    		<select name="product_last" id="product_last">
	    			{section name=i loop=$product_list}
	    				<option value="{$product_list[i].name}"
	    					{if $product_list[i].name eq $product_last}selected="selected"{/if}>
	    					{$product_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="product_all">Todos</label>
	    		<input name="product_all" id="product_all" type="checkbox" />
	    		<input type="submit" value="Imprimir" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=print_counting_template_by_manufacturer" onsubmit="return oSession.setIsLink(true);">
	    		<label for="manufacturer_first">De Casa:</label>
	    		<select name="manufacturer_first" id="manufacturer_first">
	    			{section name=i loop=$manufacturer_list}
	    				<option value="{$manufacturer_list[i].name}"
	    					{if $manufacturer_list[i].name eq $manufacturer_first}selected="selected"{/if}>
	    					{$manufacturer_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="manufacturer_last">A:</label>
	    		<select name="manufacturer_last" id="manufacturer_last">
	    			{section name=i loop=$manufacturer_list}
	    				<option value="{$manufacturer_list[i].name}"
	    					{if $manufacturer_list[i].name eq $manufacturer_last}selected="selected"{/if}>
	    					{$manufacturer_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="manufacturer_all">Todos</label>
	    		<input name="manufacturer_all" id="manufacturer_all" type="checkbox" />
	    		<input type="submit" value="Imprimir" />
	    	</form>
	    </li>
	</ul>
</div>