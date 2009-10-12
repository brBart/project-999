{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="{$create_link}" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<form method="post" action="{$get_link}" onsubmit="return oSession.setIsLink(true);">
	    		<label for="receipt_id">{$document_name} No:</label>
	    		<input name="id" id="receipt_id" type="text" value="{$id}" maxlength="11" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<div id="dates_range">
		    	<form method="post" action="{$search_link}"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
			    		<label for="start_date">Fecha inicial:</label>
			    		<input name="start_date" id="start_date" type="text" value="{$start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
			    		<label for="end_date">Fecha final:</label>
			    		<input name="end_date" id="end_date" type="text" value="{$end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>