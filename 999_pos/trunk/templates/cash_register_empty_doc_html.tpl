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
	</div>
</div>