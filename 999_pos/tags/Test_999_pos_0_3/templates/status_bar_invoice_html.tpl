{* Smarty *}
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