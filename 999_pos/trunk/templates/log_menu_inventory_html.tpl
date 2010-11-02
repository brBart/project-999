{* Smarty * }
<div id="third_menu">
	<ul>
	    <li id="li_last">
	    	<div id="dates_range_desc">
		    	<form method="post" action="index.php?cmd=show_product_price_log&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
		    			<label for="discount_start_date">Precios de productos del</label>
			    		<input name="discount_start_date" id="discount_start_date" type="text" value="{$discount_start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
		    			<label for="discount_end_date">al</label>
			    		<input name="discount_end_date" id="discount_end_date" type="text" value="{$discount_end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>