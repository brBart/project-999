{* Smarty *}
<fieldset id="controls">
	<input name="form_widget" id="show_cash_receipt" type="button" value="Ver Recibo" />
  	<input name="form_widget" id="cancel" type="button" value="Anular"
  			{if $status eq 1 and $cash_register_status eq 1}onclick="oCancel.showForm();"{else}disabled="disabled"{/if} />
</fieldset>
{if $status eq 1 and $cash_register_status eq 1}
{include file='authentication_form_html.tpl'}
{/if}