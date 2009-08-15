{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=create_product" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<a href="index.php?cmd=show_product_list&page=1" onclick="return oSession.setIsLink(true);">
	    		Consultar (Orden Alfab&eacute;tico)</a>
	    </li>
	    <li>
	    	<form action="index.php?cmd=get_product">
	    		<label for="product_id">C&oacute;digo:</label><input name="product_id" id="product_id" type="text"/>
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<form action="index.php?cmd=get_product">
	    		<label for="bar_code">C&oacute;digo barra:</label><input name="bar_code" id="bar_code" type="text"/>
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<form action="index.php?cmd=get_product_supplier">
	    		<label for="supplier_id">Proveedor:</label>
	    		<select name="supplier_id" id="supplier_id">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].supplier_id}">{$supplier_list[i].name}</option>
	    			{/section}
	    		</select>
	    		<label for="product_code">C&oacute;digo:</label>
	    		<input name="product_code" id="product_code" type="text"/>
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form action="index.php?cmd=search_product&page=1">
	    		<label for="name">Nombre:</label><input name="name" id="name" type="text"/>
	    		<input type="submit" value="Buscar" />
	    	</form>
	    </li>
	</ul>
</div>