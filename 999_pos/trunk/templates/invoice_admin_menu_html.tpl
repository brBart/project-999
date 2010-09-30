{* Smarty * }
<div id="third_menu">
	<ul>
	    <li>
	    	<form method="post" action="index.php?cmd=get_invoice_by_working_day" onsubmit="return oSession.setIsLink(true);">
	    		<div>
		    		<label for="working_day">Jornada:</label>
		    		<input name="working_day" id="working_day" type="text" value="{$working_day}" maxlength="10" />
		    		<span class="hint">dd/mm/aaaa</span>
	    		</div>
	    		<label for="serial_number">Serie:</label>
	    		<input name="serial_number" id="serial_number" type="text" value="{$serial_number}" maxlength="10" />
	    		<label for="number">No:</label>
	    		<input name="number" id="number" type="text" value="{$number}" maxlength="20" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<div id="dates_range">
		    	<form method="post" action="index.php?cmd=search_invoice_by_working_day&page=1"
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