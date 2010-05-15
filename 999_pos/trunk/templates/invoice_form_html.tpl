{* Smarty * }
<script type="text/javascript">
var cashRegisterStatus = {$cash_register_status};
var documentStatus = 1;
</script>
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="cash_register">
			<p><label>Caja Id:</label><span>{$cash_register_id}</span></p>
			<p><label>Fecha:</label><span>{$date}</span></p>
			<p><label>Turno:</label><span>{$shift}</span></p>
			<p><label>Status:</label><span>{if $cash_register_status eq 1}Abierto{else}Cerrado{/if}</span></p>
		</fieldset>
		{include file='status_bar_doc_html.tpl' status='1'}
		<fieldset id="header_data">
			<p>
				<label>Factura Serie:</label><span>{$serial_number}</span>
			</p>
			<p>
				<label>No:</label><span>{$number}&nbsp;</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date_time}</span>
			</p>
			<p>
				<label>Usuario:</label><span>{$username}</span>
			</p>
		</fieldset>
		<fieldset id="main_data" class="pos">
			<p>
		  		<label>Nit:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<span id="nit">&nbsp;</span>
		  		<span id="nit-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$nit|htmlchars}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label>Cliente:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<span id="customer">&nbsp;</span>
		  		<span id="customer-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$customer|htmlchars}</span>
		  		{/if}
		  	</p>
	  	</fieldset>
	</div>
</div>