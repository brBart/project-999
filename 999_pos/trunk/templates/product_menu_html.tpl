{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=create_product" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<a href="index.php?cmd=show_product_list&page=1" onclick="return oSession.setIsLink(true);">
	    		Consultar (Orden Alfab&eacute;tico)</a>
	    </li>
	    <li>
	    	<label for="name">Buscar:</label><input name="name" id="name" type="text"/>
	    </li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_product_by_id" onsubmit="return oSession.setIsLink(true);">
	    		<label for="product_id">C&oacute;digo:</label><input name="id" id="product_id" type="text" value="{$id}" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_product_by_bar_code" onsubmit="return oSession.setIsLink(true);">
	    		<label for="bar_code">C&oacute;digo barra:</label><input name="bar_code" id="bar_code" type="text" value="{$bar_code}" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=get_product_by_supplier" onsubmit="return oSession.setIsLink(true);">
	    		<label for="supplier_id">Proveedor:</label>
	    		<select name="supplier_id" id="supplier_id">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].supplier_id}"
	    					{if $supplier_list[i].supplier_id eq $supplier_id}selected="selected"{/if}>
	    					{$supplier_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="product_sku">C&oacute;digo:</label>
	    		<input name="product_sku" id="product_sku" type="text" value="{$product_sku}" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	</ul>
</div>