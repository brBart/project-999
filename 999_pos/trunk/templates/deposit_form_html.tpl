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
		  		<label id="deposit_number_label">Boleta No:</label><span class="hidden">*</span>
		  		<span id="deposit_number_value">{$deposit_number|htmlchars}&nbsp;</span>
		  		<input name="form_widget" id="deposit_number" class="hidden" type="text" maxlength="50"
		  			onblur="mainWindow.setDepositNumber(this.value);" />
		  		<span id="deposit_number-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_account_label">Cuenta bancaria:</label><span class="hidden">*</span>
		  		<span id="bank_account">{$bank_account|htmlchars}&nbsp;</span>
		  		<select name="form_widget" id="bank_account_id" class="hidden"
		  			onchange="mainWindow.setBankAccount(this.value);">
	    			{section name=i loop=$bank_account_list}
	    				<option value="{$bank_account_list[i].id}">
	    					{$bank_account_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="bank_account-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label id="bank_label">Banco:</label>
		  		<span id="bank">{$bank|htmlchars}&nbsp;</span>
		  	</p>
		  	<div id="details" class="items register"></div>
	  	</fieldset>
	  	<fieldset id="data_footer">
	  		<object id="recordset" type="application/x-recordset"></object>
	  	</fieldset>
	</div>
</div>