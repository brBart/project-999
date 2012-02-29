{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=show_create_comparison_form" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_comparison" onsubmit="return oSession.setIsLink(true);">
	    		<label for="id">Comparaci&oacute;n No:</label>
	    		<input name="id" id="id" type="text" value="{$id}" maxlength="11" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<div id="dates_range">
		    	<form method="post" action="index.php?cmd=search_comparison&page=1"
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
		    		</div>
		    		<input type="submit" value="Buscar" />
		    	</form>
	    	</div>
	    </li>
	    <li id="li_last"><a href="index.php?cmd=show_get_comparison_filter_form" onclick="return oSession.setIsLink(true);">Filtrar Comparaci&oacute;n</a></li>
	</ul>
</div>