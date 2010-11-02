{* Smarty * }
<div id="third_menu">
	<ul>
	    <li id="li_last">
	    	<div id="dates_range_desc">
		    	<form method="post" action="index.php?cmd=show_product_price_log&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
		    			<label for="price_start_date">Precios de productos del</label>
			    		<input name="price_start_date" id="price_start_date" type="text" value="{$price_start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
		    			<label for="price_end_date">al</label>
			    		<input name="price_end_date" id="price_end_date" type="text" value="{$price_end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>