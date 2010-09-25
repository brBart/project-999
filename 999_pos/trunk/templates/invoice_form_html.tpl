{* Smarty * }
<script type="text/javascript">
var cashRegisterStatus = {$cash_register_status};
var documentStatus = {$status};
{if $key neq ''}
var objectKey = {$key};
{/if}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='cash_register_status_bar_html.tpl'}
		{include file='status_bar_invoice_html.tpl'}
		{include file='header_data_invoice_html.tpl'}
		<fieldset id="main_data" class="pos disabled">
			<p>
		  		<label id="nit_label">Nit:<span class="hidden">*</span></label>
		  		<span id="nit">{$nit|htmlchars}&nbsp;</span>
		  		<span id="nit-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="customer_label">Cliente:</label>
		  		<span id="customer">{$customer|htmlchars}&nbsp;</span>
		  		<span id="customer-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<object id="bar_code_input" type="application/x-bar_code_line_edit"></object>
		  		<span id="bar_code-failed" class="hidden">*</span>
	  		</p>
		  	<div id="details" class="items register"></div>
		  	<div id="receipt_info">
		  		<p >
			  		<label>Efectivo:</label><span id="cash_amount">{$cash_amount|nf:2}&nbsp;</span>
		  		</p>
		  		<p>
			  		<label>Tarjetas:</label><span id="vouchers_total">{$vouchers_total|nf:2}&nbsp;</span>
			  	</p>
			  	<p>
			  		<label>Cambio:</label><span id="change_amount">{$change_amount|nf:2}&nbsp;</span>
			  	</p>
		  	</div>
	  	</fieldset>
	  	<fieldset id="data_footer">
	  		<object id="recordset" type="application/x-recordset"></object>
	  	</fieldset>
	</div>
</div>