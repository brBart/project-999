{* Smarty *}
<fieldset id="status_bar">
	<p>
		<label>Status:</label>
		<span id="status_label" {if $status eq 2}class="cancel_status"{/if}>
			{if $status eq 0}
				Creando...
			{elseif $status eq 1}
				Cerrado
			{elseif $status eq 2}
				Anulado
			{else}
				Confirmado
			{/if}
		</span>
	</p>
</fieldset>