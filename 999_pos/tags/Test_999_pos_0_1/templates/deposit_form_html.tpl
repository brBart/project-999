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
		{include file='status_bar_deposit_html.tpl'}
		{include file='header_data_html.tpl' document_name='Deposito'}
		<fieldset id="main_data">
			<p>
		  		<label id="slip_number_label">Boleta No:<span class="hidden">*</span></label>
		  		<span id="slip_number_value">{$slip_number|escape}&nbsp;</span>
		  		<object id="slip_number" class="widget_input hidden" type="application/x-slip_number_line_edit"></object>
		  		<span id="slip_number-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_account_label">Cuenta bancaria:<span class="hidden">*</span></label>
		  		<span id="bank_account">{$bank_account|escape}&nbsp;</span>
		  		<object id="bank_account_id" class="widget_input hidden" type="application/x-bank_account_combo_box"></object>
		  		<span id="bank_account_id-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_label">Banco:</label>
		  		<span id="bank">{$bank|escape}&nbsp;</span>
		  	</p>
		  	<p>&nbsp;</p>
		  	<div id="details" class="items register disabled"></div>
	  	</fieldset>
	  	<fieldset id="data_footer">
	  		<object id="recordset" type="application/x-recordset"></object>
	  	</fieldset>
	</div>
</div>