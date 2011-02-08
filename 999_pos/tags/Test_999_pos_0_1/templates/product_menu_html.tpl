{* Smarty * }
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/search_product.js"></script>
<script type="text/javascript" src="../scripts/search_product_details.js"></script>
<script type="text/javascript" src="../scripts/search_product_menu.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oEventDelegator = new EventDelegator();
	var oSearchProduct = new SearchProduct(oSession, oConsole, Request.createXmlHttpRequestObject());
	var oSearchDetails = new SearchProductMenu(oSession, oSearchProduct, oEventDelegator);
</script>
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=create_product" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<a href="index.php?cmd=show_product_list&page=1" onclick="return oSession.setIsLink(true);">
	    		Consultar (Orden Alfab&eacute;tico)</a>
	    </li>
	    <li>
	    	<div id="search_product">
		    	<label for="name">Buscar:</label>
		    	<div>
		    		<input name="name" id="name" type="text" maxlength="100" />
		    		<div>
		    			<div id="scroll"></div>
		    		</div>
		    	</div>
	    	</div>
	    </li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_product_by_id" onsubmit="return oSession.setIsLink(true);">
	    		<label for="product_id">C&oacute;digo:</label>
	    		<input name="id" id="product_id" type="text" value="{$id}" maxlength="11" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_product_by_bar_code" onsubmit="return oSession.setIsLink(true);">
	    		<label for="bar_code">C&oacute;digo barra:</label>
	    		<input name="bar_code" id="bar_code" type="text" value="{$bar_code|escape}" maxlength="100" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=get_product_by_supplier" onsubmit="return oSession.setIsLink(true);">
	    		<label for="supplier_id">Proveedor:</label>
	    		<select name="supplier_id" id="supplier_id">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].id}"
	    					{if $supplier_list[i].id eq $supplier_id}selected="selected"{/if}>
	    					{$supplier_list[i].name|escape}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="product_sku">C&oacute;digo:</label>
	    		<input name="product_sku" id="product_sku" type="text" value="{$product_sku|escape}" maxlength="50" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	</ul>
</div>
<script type="text/javascript">
	oEventDelegator.init();
	oSearchDetails.init('name', 'oSearchDetails');
</script>