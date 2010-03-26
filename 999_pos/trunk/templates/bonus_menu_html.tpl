{* Smarty * }
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/search_product.js"></script>
<script type="text/javascript" src="../scripts/search_product_details.js"></script>
<script type="text/javascript" src="../scripts/search_product_bonus.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oEventDelegator = new EventDelegator();
	var oSearchProduct = new SearchProduct(oSession, oConsole, Request.createXmlHttpRequestObject());
	var oSearchDetails = new SearchProductBonus(oSession, oSearchProduct, oEventDelegator);
</script>
<div id="third_menu">
	<ul>
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
	    	<form method="post" action="index.php?cmd=get_bonus_product_by_id" onsubmit="return oSession.setIsLink(true);">
	    		<label for="product_id">C&oacute;digo:</label>
	    		<input name="id" id="product_id" type="text" value="{$id}" maxlength="11" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=get_bonus_product_by_bar_code" onsubmit="return oSession.setIsLink(true);">
	    		<label for="bar_code">C&oacute;digo barra:</label>
	    		<input name="bar_code" id="bar_code" type="text" value="{$bar_code|htmlchars}" maxlength="100" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	</ul>
</div>
<script type="text/javascript">
	oEventDelegator.init();
	oSearchDetails.init('name', 'oSearchDetails');
</script>