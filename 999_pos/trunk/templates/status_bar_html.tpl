{* Smarty *}
<fieldset id="status_bar">
	<p>
		<label>Status:</label>
		<span id="status_label">
			{if $status eq 0}
				Creando...
			{else}
				Consulta
			{/if}
		</span>
	</p>
</fieldset>