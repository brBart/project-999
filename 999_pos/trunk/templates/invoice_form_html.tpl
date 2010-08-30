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
		<fieldset id="status_bar_invoice">
			<p>
				<label>Status:</label>
				<span id="status_label" {if $status eq 2}class="cancel_status"{/if}>
					{if $status eq 0}
						Creando...
					{elseif $status eq 1}
						Cerrado
					{else}
						Anulado
					{/if}
				</span>
			</p>
		</fieldset>
		<fieldset id="header_data">
			<p>
				<label>Factura Serie:</label><span id="serial_number">{$serial_number}</span>
			</p>
			<p>
				<label>No:</label><span id="number">{$number}&nbsp;</span>
			</p>
			<p>
				<label>Fecha:</label><span id="date_time">{$date_time}</span>
			</p>
			<p>
				<label>Usuario:</label><span id="username">{$username}</span>
			</p>
		</fieldset>
		<fieldset id="main_data" class="pos disabled">
			<p>
		  		<label id="nit_label">Nit:</label>
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