{* Smarty *}
<fieldset id="cash_register">
	<p><label>Caja Id:</label><span>{$cash_register_id}</span></p>
	<p><label>Fecha:</label><span>{$date}</span></p>
	<p><label>Turno:</label><span>{$shift}</span></p>
	<p>
		<label>Status:</label>
		<span id="cash_register_status" class="{if $cash_register_status eq 1}pos_open_status{else}pos_closed_status{/if}">
			{if $cash_register_status eq 1}Abierto{else}Cerrado{/if}
		</span>
	</p>
</fieldset>