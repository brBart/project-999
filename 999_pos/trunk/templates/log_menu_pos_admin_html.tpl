{* Smarty * }
<div id="third_menu">
	<ul>
	    <li>
	    	<div class="dates_range_desc">
		    	<form method="post" action="index.php?cmd=show_invoice_discount_log&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
		    			<label for="discount_start_date">Descuento a facturas del</label>
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
	    <li id="li_last">
	    	<div class="dates_range_desc">
		    	<form method="post" action="index.php?cmd=show_cash_document_cancel_log&page=1"
		    			onsubmit="return oSession.setIsLink(true);">
		    		<div>
		    			<label for="cancel_start_date">Documentos anulados del</label>
			    		<input name="cancel_start_date" id="cancel_start_date" type="text" value="{$cancel_start_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
		    		</div>
		    		<div>
		    			<label for="cancel_end_date">al</label>
			    		<input name="cancel_end_date" id="cancel_end_date" type="text" value="{$cancel_end_date}" maxlength="10" />
			    		<span class="hint">dd/mm/aaaa</span>
			    		<input type="submit" value="Buscar" />
		    		</div>
		    	</form>
	    	</div>
	    </li>
	</ul>
</div>