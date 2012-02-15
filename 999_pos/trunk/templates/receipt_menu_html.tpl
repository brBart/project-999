{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=create_receipt" onclick="return oSession.setIsLink(true);">Crear</a></li>
	    <li>
	    	<form method="post" action="index.php?cmd=get_receipt" onsubmit="return oSession.setIsLink(true);">
	    		<label for="id">Recibo No:</label>
	    		<input name="id" id="id" type="text" value="{$id}" maxlength="11" />
	    		<input type="submit" value="Consultar" />
	    	</form>
	    </li>
	    <li>
	    	<div id="dates_range">
		    	<form method="post" action="index.php?cmd=search_receipt&page=1"
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
	    <li id="li_last">
	    	<div class="receipt_search">
		    	<form method="post" action="index.php?cmd=search_receipt_by_supplier&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
			    		<label for="supplier_id">Proveedor:</label>
			    		<select name="supplier_id" id="supplier_id">
		    			{section name=i loop=$supplier_list}
		    				<option value="{$supplier_list[i].id}"
		    					{if $supplier_list[i].id eq $supplier_id}selected="selected"{/if}>
		    					{$supplier_list[i].name|escape}
		    				</option>
		    			{/section}
	    				</select>
		    		</div>
		    		<div>
			    		<label for="shipment_number">Env&iacute;o No:</label>
			    		<input name="shipment_number" id="shipment_number" type="text" value="{$shipment_number}" maxlength="50" />
		    		</div>
		    		<input type="submit" value="Buscar" />
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>