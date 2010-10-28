{* Smarty * }
<div id="third_menu">
	<ul>
	    <li id="li_last">
	    	<div id="dates_range_desc">
		    	<form method="post" action="{$search_link}"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
		    			<label for="start_date">Descuento a facturas del</label>
			    		<input name="start_date" id="start_date" type="text" value="{$start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
		    			<label for="end_date">al</label>
			    		<input name="end_date" id="end_date" type="text" value="{$end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>