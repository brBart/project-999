{* Smarty * }
<div id="third_menu">
	<ul>
	    <li>
	    	<div class="working_day_search many_search">
		    	<form method="post" action="index.php?cmd=get_deposit_admin" onsubmit="return oSession.setIsLink(true);">
		    		<div>
			    		<label for="deposit_working_day">Jornada:</label>
			    		<input name="deposit_working_day" id="deposit_working_day" type="text" value="{$working_day}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<label for="deposit_id">Deposito No:</label>
		    		<input name="deposit_id" id="deposit_id" type="text" value="{$deposit_id}" maxlength="11" />
		    		<input type="submit" value="Consultar" />
		    	</form>
	    	</div>
	    </li>
	    <li>
	    	<div class="working_day_search many_search">
		    	<form method="post" action="index.php?cmd=get_deposit_by_slip" onsubmit="return oSession.setIsLink(true);">
		    		<div>
			    		<label for="slip_working_day">Jornada:</label>
			    		<input name="slip_working_day" id="slip_working_day" type="text" value="{$working_day}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<label for="bank_id">Banco:</label>
		    		<select name="bank_id" id="bank_id">
	    			{section name=i loop=$bank_list}
	    				<option value="{$bank_list[i].id}"
	    					{if $bank_list[i].id eq $bank_id}selected="selected"{/if}>
	    					{$bank_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    			</select>
		    		<label for="slip_number">Boleta No:</label>
		    		<input name="slip_number" id="slip_number" type="text" value="{$slip_number}" maxlength="50" />
	    		</select>
		    		<input type="submit" value="Consultar" />
		    	</form>
	    	</div>
	    </li>
	    <li id="li_last">
	    	<div id="dates_range">
		    	<form method="post" action="index.php?cmd=search_deposit_by_working_day&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
			    		<label for="start_date">Jornada inicial:</label>
			    		<input name="start_date" id="start_date" type="text" value="{$start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
			    		<label for="end_date">Jornada final:</label>
			    		<input name="end_date" id="end_date" type="text" value="{$end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>