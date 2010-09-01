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
		{include file='status_bar_doc_html.tpl'}
		{include file='header_data_html.tpl' document_name='Deposito'}
		<fieldset id="main_data">
			<p>
		  		<label id="deposit_number_label">Boleta No:<span class="hidden">*</span></label>
		  		<span id="deposit_number_value">{$deposit_number|htmlchars}&nbsp;</span>
		  		<object id="deposit_number" class="widget_input hidden" type="application/x-deposit_number_line_edit"></object>
		  		<span id="deposit_number-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_account_label">Cuenta bancaria:<span class="hidden">*</span></label>
		  		<span id="bank_account">{$bank_account|htmlchars}&nbsp;</span>
		  		<object id="bank_account_id" class="widget_input hidden" type="application/x-bank_account_combo_box"></object>
		  		<span id="bank_account-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_label">Banco:</label>
		  		<span id="bank">{$bank|htmlchars}&nbsp;</span>
		  	</p>
		  	<p>&nbsp;</p>
		  	<div id="details" class="items register"></div>
	  	</fieldset>
	  	<fieldset id="data_footer">
	  		<object id="recordset" type="application/x-recordset"></object>
	  	</fieldset>
	</div>
</div>